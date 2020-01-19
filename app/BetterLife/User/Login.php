<?php
namespace BetterLife\User;
use BetterLife\BetterLife;
use BetterLife\System\Exception;
use BetterLife\System\Logger;
use BetterLife\System\SystemConstant;
class Login {

    const COOKIE_NAME = "RememberCookie";
    const COOKIE_EXPIRY = 604800;
    const COOKIE_TABLE_NAME = "cookies";

    private $id;
    private $password;
    private $remember;

    /**
     * LoginC constructor.
     * @param string $id
     * @param string $password
     * @param bool $remember
     * @throws \Exception
     */
    public function __construct(string $id, string $password, bool $remember = false) {
        if(isset($_SESSION[SystemConstant::USER_SESSION_NAME]))
            throw new \Exception("User already Connect!");

        if (empty($id) || empty($password))
            throw new \Exception("unable to login without proper credentials!");

        $this->id = $id;
        $this->password = $password;
        $this->remember = $remember;
        $this->connect();
    }


    /**
     * @throws \Exception
     */
    private function connect() {
        $DBPassword = BetterLife::GetDB()->where("Id", $this->id)->getOne("users","Password");

        $hashPassword = password_hash($this->password, PASSWORD_DEFAULT);
        if(password_verify($DBPassword, $hashPassword))
            $userData = BetterLife::GetDB()->where("Id", $this->id)->getOne("users");
        else
            throw new \Exception("שם המשתמש או הסיסמה אינם נכונים");


        if (empty($userData))
            throw new \Exception("לא נמצא משתמש התואם את הפרטים שהוכנסו");

        //todo: Continue from here after User class have been completed.
        if ($userData)
            $_SESSION["UserId"] = $userData["Id"];
            $newLoginUser = User::GetById($userData["Id"]);
            $newLoginUser->SetLastLogin();

            //log
            $log = new Logger("המשתמש {0} התחבר בהצלחה!", $userData["UserId"]);
            $log->info();
            $log->writeToDb();
            $log->writeToFile();


                if ($this->remember) {
                    $hash = md5(uniqid());
                    $hashCheck = Rimon::GetDB()->where("UserId", $this->id)->getOne(self::COOKIE_TABLE_NAME, "Hash");
                    $hashCheck = $hashCheck["Hash"];

                    if (count($hashCheck) == 0)
                        Rimon::GetDB()->insert(self::COOKIE_TABLE_NAME, array("UserId" => $this->id, "Hash" => $hash));
                    else
                        $hash = $hashCheck;

                    Cookie::Put(self::COOKIE_NAME, $hash, self::COOKIE_EXPIRY);
                }

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
                $hashCheck = Rimon::GetDB()->where("Hash",$hashCookie)->getOne(self::COOKIE_TABLE_NAME);

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