<?php
namespace BetterLife\User;

class Login
{

    const COOKIE_NAME = "RememberCookie";
    const COOKIE_EXPIRY = 604800;
    const COOKIE_TABLE_NAME = "cookies";

    private $id;
    private $password;
    private $remember;
    private $connected = False;
    private $loginTimestamp;

    /**
     * LoginC constructor.
     * @param string $id
     * @param string $password
     * @param bool $remember
     * @throws \Exception
     */
    public function __construct(string $id, string $password, bool $remember = false) {
        if (empty($id) || empty($password))
            throw new \Exception("unable to login without proper credentials!");

        $this->id = $id;
        $this->password = $password;
        $this->remember = $remember;
        $this->connect();
    }


    /**
     * @return bool
     */
    public function IsConnected(){
        return $this->connected;
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

    /**
     * @throws \Exception
     */
    private function connect() {
        if ($this->isConnected())
            throw new \Exception("Login class already connected!");

        $DBPassword = Rimon::GetDB()->where("Id", $this->id)->getOne("users","Password");

        $wp_hasher = new PasswordHash(16, true);
        if($wp_hasher->CheckPassword($this->password,$DBPassword["Password"])) {
            $connectedUserData = Rimon::GetDB()->where("Id", $this->id)->getOne("users");
        } else {
            throw new \Exception("שם המשתמש או הסיסמה אינם נכונים");
        }


        if (empty($connectedUserData))
            throw new \Exception("שם המשתמש או הסיסמה אינם נכונים");

        $this->connected = True;
        $this->loginTimestamp = new \DateTime();

        if ($connectedUserData)
            $_SESSION["UserId"] = $connectedUserData["Id"];
            $newLoginUser = User::GetById($connectedUserData["Id"]);
            $newLoginUser->SetLastLogin();

            //log
            $logString = "המשתמש {$newLoginUser->GetFullName()} תז {$newLoginUser->GetId()} נכנס למערכת";
            Rimon::NewLog($logString);

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


}