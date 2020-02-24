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


    private function connect() {
        $userData = BetterLife::GetDB()->where("Email", $this->email)->getOne(User::TABLE_NAME,["Id", "Password"]);
        if (empty($userData))
            throw new \Exception("לא נמצא משתמש התואם את הפרטים שהוכנסו");

        if (!password_verify($this->password, $userData["Password"]))
            throw new \Exception("שם המשתמש או הסיסמה אינם נכונים");

        //reset login attempts
        BetterLife::GetDB()->where("Ip", Services::getClientIp())->delete("loginAttempts");

        $userObj = User::getById($userData["Id"]);

        if(!isset($userObj))
            throw new \Exception("User error, cannot sign right now");

        if($userObj->checkNewUser())
            throw new \Exception("משתמש לא מאומת!");

        $userObj->setLastLogin();
        $userObj->setToken(Services::GenerateRandomString(64));
        $userObj->save();
        Session::newSession(SystemConstant::USER_SESSION_NAME, $userObj->getId());


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

        if(isset($_SESSION[Session::LOGIN_ATTEMTS]))
            unset($_SESSION[Session::LOGIN_ATTEMTS]);

        if(isset($_SESSION[Session::PREVIOUS_PAGE])){
            $tmpUrl = $_SESSION[Session::PREVIOUS_PAGE];
            unset($_SESSION[Session::PREVIOUS_PAGE]);
            header("Location: " . $tmpUrl);
        }
        else
            Services::RedirectHome();
    }


    public static function Reconnect() {
        if(Cookie::Exists(self::COOKIE_NAME) && !Session::checkUserSession()) {
            $hashCheck = BetterLife::GetDB()->where("Hash",Cookie::Get(self::COOKIE_NAME))->getOne(Cookie::TABLE_NAME);

            if(!empty($hashCheck)){
                $userObj = User::GetById($hashCheck["UserId"]);
                $userObj->SetLastLogin();
                $userObj->save();
                $_SESSION[SystemConstant::USER_SESSION_NAME] = serialize($userObj);

                //log
                $log = new Logger("המשתמש {0} התחבר אוטומטית", $userObj->getId());
                $log->info();
                $log->writeToDb();
                $log->writeToFile();

                header("Refresh:0");
            }
        }
        return False;
    }


    public static function Disconnect(){
        if(!Session::checkUserSession())
            throw new \Exception("Session doesnt found");
        $userObj = User::GetUserFromSession();

        if(Cookie::Exists(self::COOKIE_NAME))
            Cookie::Delete(self::COOKIE_NAME);

        $hashCheck = BetterLife::GetDB()->where("UserId", $userObj->getId())->get(Cookie::TABLE_NAME);
        if(!empty($hashCheck))
            BetterLife::GetDB()->where("UserId", $userObj->getId())->delete(Cookie::TABLE_NAME);

        //log
        $log = new Logger("המשתמש {0} התנתק מהמערכת", $userObj->getId());
        $log->info();
        $log->writeToDb();
        $log->writeToFile();


        unset($_SESSION[SystemConstant::USER_SESSION_NAME]);
        session_destroy();
        header('Location: ' . $_SERVER["HTTP_REFERER"]);
    }



    public static function checkLoginAttempts(int $MaxAttempts= 5) {
        $tableName = "loginAttempts";
        $userIp = Services::getClientIp();
        if(!$userIp)
            throw new \Exception("אירע תקלה, אנא נסה מאוחר יותר");

        $userAttempts = BetterLife::GetDB()->where("Ip", $userIp)->getOne($tableName, "Attempts");

        if(!isset($userAttempts)){
            BetterLife::GetDB()->insert($tableName, ["Ip" => $userIp, "Attempts" => 0]);
            return true;
        }
        elseif($userAttempts["Attempts"] < $MaxAttempts)
            return true;
        else
            return false;
    }


    public static function incLoginAttempts() {
        $tableName = "loginAttempts";
        $userIp = Services::getClientIp();
        if(!$userIp)
            throw new \Exception("אירע תקלה, אנא נסה מאוחר יותר");

        $userAttempts = BetterLife::GetDB()->where("Ip", $userIp)->getOne($tableName, "Attempts");
        if(!isset($userAttempts))
            throw new \Exception("Cannot add attempts");

        BetterLife::GetDB()->where("Ip", $userIp)->update($tableName, ["Attempts" => ++$userAttempts["Attempts"]]);

    }

}