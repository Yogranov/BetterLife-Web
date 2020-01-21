<?php
namespace BetterLife\User;
use BetterLife\BetterLife;
use BetterLife\System\Exception;
use BetterLife\System\Logger;
use BetterLife\System\Services;
use BetterLife\System\SystemConstant;

class Login {

    const COOKIE_NAME = "RememberCookie";
    const COOKIE_EXPIRY = 604800;


    private $email;
    private $password;
    private $remember;


    /**
     * Login constructor.
     * @param string $email
     * @param string $hashPass
     * @param bool $remember
     * @throws \Exception
     */
    public function __construct(string $email, string $hashPass, bool $remember = false) {
        if(Session::checkUserSession())
            throw new \Exception("User already Connected!");

        if (empty($email) || empty($hashPass))
            throw new \Exception("unable to login without proper credentials!");

        $this->email = $email;
        $this->password = $hashPass;
        $this->remember = $remember;
        $this->connect();
    }


    /**
     * @throws \Exception
     */
    private function connect() {
        $userData = BetterLife::GetDB()->where("Email", $this->email)->getOne(User::TABLE_NAME,["Id", "Password"]);
        if (empty($userData))
            throw new \Exception("לא נמצא משתמש התואם את הפרטים שהוכנסו");

        if (!password_verify($this->password, $userData["Password"]))
            throw new \Exception("שם המשתמש או הסיסמה אינם נכונים");

        $userObj = User::getById($userData["Id"]);
        $userObj->setLastLogin();
        $userObj->save();
        Session::newSession(SystemConstant::USER_SESSION_NAME, serialize($userObj));


        //log
        $log = new Logger("המשתמש {0} התחבר בהצלחה!", $userObj->getId());
        $log->info();
        $log->writeToDb();
        $log->writeToFile();

        if ($this->remember) {
            if(Cookie::Exists(self::COOKIE_NAME))
                Cookie::Delete(self::COOKIE_NAME);

            $hashCheck = BetterLife::GetDB()->where("UserId", $userData["Id"])->get(Cookie::TABLE_NAME);
            if(!empty($hashCheck))
                BetterLife::GetDB()->where("UserId", $userData["Id"])->delete(Cookie::TABLE_NAME);

            $hashCookie = md5(uniqid());
            BetterLife::GetDB()->insert(Cookie::TABLE_NAME, array("UserId" => $userData["Id"], "Hash" => $hashCookie));
            Cookie::Put(self::COOKIE_NAME, $hashCookie, self::COOKIE_EXPIRY);
        }

        Services::RedirectHome();
    }
        /**
         * @param string $redirectHeader
         * @return bool|void
         * @throws Exception
         * @throws \Exception
         */

        public static function Reconnect(string $redirectHeader = "index.php") {
            if(Cookie::Exists(self::COOKIE_NAME) && empty($_SESSION["UserId"])) {
                $hashCookie = Cookie::Get(self::COOKIE_NAME);
                $hashCheck = Rimon::GetDB()->where("Hash",$hashCookie)->getOne(Cookie::TABLE_NAME);

                if(count($hashCheck) > 0){
                    $userObject = &User::GetById($hashCheck["UserId"]);
                    $userObject->SetLastLogin();
                    $_SESSION["UserId"] = $userObject->GetId();

                    //log
                    $logString = "המשתמש {$userObject->GetFullName()} תז {$userObject->GetId()} נכנס למערכת אוטומטית על ידי עוגיה";
                    Rimon::NewLog($logString);

                    header("Location: ".$redirectHeader);
                }
            }
            return False;
        }


    /**
     *
     */
    public static function Disconnect(){
        Cookie::Delete(self::COOKIE_NAME);
        if(Rimon::GetDB()->where("UserId", $_SESSION["UserId"])->getOne(self::COOKIE_TABLE_NAME))
            Rimon::GetDB()->where("UserId", $_SESSION["UserId"])->delete(self::COOKIE_TABLE_NAME,1);
        $userLoginObj = User::GetById($_SESSION["UserId"]);
        //log
        $logString = "המשתמש {$userLoginObj->GetFullName()} תז {$userLoginObj->GetId()} יצא מהמערכת";
        Rimon::NewLog($logString);

        unset($_SESSION["UserId"]);
        session_destroy();
        \Services::RedirectHome();

    }

}