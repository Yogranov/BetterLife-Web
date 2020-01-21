<?php
namespace BetterLife;

use BetterLife\Enum\EUserRoles;
use BetterLife\System\SystemConstant;
use BetterLife\User\User;
use MysqliDb;
use PHPMailer\PHPMailer\PHPMailer;
use BetterLife\System\Exception;
use BetterLife\System\Credential;
use BetterLife\User\Session;
use BetterLife\System\Services;


class BetterLife {

    private static $db;
    private static $email;


    /**
     * @return MysqliDb
     * @throws Exception
     */
    public static function GetDB() {
        if (isset(self::$db) && !empty(self::$db)) {
            return self::$db;
        }
        else {
            if (!class_exists("MysqliDb"))
                throw new Exception("Mandatory 'MysqliDB' class not exist!");


            $SqlCredential = Credential::GetCredential('sql_' . SystemConstant::MYSQL_SERVER . '_' . SystemConstant::MYSQL_SERVER_PORT . '_' . SystemConstant::MYSQL_DATABASE);
            self::$db = new MysqliDb (SystemConstant::MYSQL_SERVER, $SqlCredential->GetUsername(), $SqlCredential->GetPassword(), SystemConstant::MYSQL_DATABASE, SystemConstant::MYSQL_SERVER_PORT);
            return self::$db;
        }
    }


    /**
     * @param string $subject
     * @param string $message
     * @param bool $clear
     * @return PHPMailer
     * @throws Exception
     */
    public static function GetEmail(string $subject, string $message, bool $clear = True) {
        try {
            if (isset(self::$email) && !empty(self::$email)) {
                $ret = self::$email;
            } else {
                $Email = new PHPMailer();

                //$Email->isSendmail();
                $Email->IsHTML(true);
                $Email->setFrom(SystemConstant::EMAIL_MAIN_ADDRESS, SystemConstant::EMAIL_MAIN_NAME);
                $Email->ContentType = "text/html;charset=utf-8";
                $Email->headerLine("MIME-Version", 1.0);
                $Email->CharSet = "UTF-8";

                $body = <<<RIMON
                    <html dir=rtl>
                        <body>
                            {$message}
                        </body>
                    </html>
RIMON;
                $Email->Subject = $subject;
                $Email->Body = $body;
                $Email->AltBody = strip_tags($message);


                $ret = self::$email = $Email;
            }

            /*
            $ret->addReplyTo(Constant::EMAIL_MAIN_ADDRESS, Constant::EMAIL_MAIN_NAME);
            $ret->headerLine("Return-Path", Constant::EMAIL_MAIN_ADDRESS);
            $ret->headerLine("From", Constant::EMAIL_MAIN_ADDRESS);
            $ret->headerLine("Organization", Constant::EMAIL_MAIN_NAME);
            $ret->headerLine("X-Priority", 1);
            */

            if ($clear) {
                self::$email->ClearAddresses(); //
                self::$email->ClearCCs();
                self::$email->ClearBCCs();
                self::$email->clearAttachments();
            }
            return $ret;
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage());
        }

    }

    public static function buildNavbar($navbarTemplate) {
        if(Session::checkUserSession()) {
            $userObj = User::GetUserFromSession();
            Services::setPlaceHolder($navbarTemplate, "Menu", MemberMenu);
            Services::setPlaceHolder($navbarTemplate, "MemberMenu", PatientMenu);
            Services::setPlaceHolder($navbarTemplate, "userFirstName", $userObj->getFirstName());
        } else {
            Services::setPlaceHolder($navbarTemplate, "Menu", NoneUserMenu);
        }
        return $navbarTemplate;
    }

    /**
     * @param null $fromRole
     * @return array
     * @throws Exception
     */
    public static function GetPermissions($fromRole = null){
        $permissions = array();
        ///Menu Setting
        if (Session::checkUserSession()) {
            $userObj = User::GetUserFromSession();
            if($userObj->GetRole()->getValue() !== EUserRoles::NewUser[0]) {
                $permissions["Menu"] = MemberMenu;

                switch ($userObj->GetRole()->getValue()){

                    case EUserRoles::Patient[0]:
                        $memberMenu = PatientMenu;
                        break;

                    case EUserRoles::Doctor[0]:
                        $memberMenu = DoctorMenu;
                        break;

                    case EUserRoles::Admin[0]:
                        $memberMenu = AdminMenu;
                        break;

                    default:
                        $memberMenu = "";
                        break;
                }
                $permissions["ManagerMenu"] = $memberMenu;

            } else {
                Login::Disconnect();
            }
        } else {
            $permissions["Menu"] = NoneUserMenu;
            $permissions["ManagerMenu"] = "";
        }

        /// Page Permissions
        if ($fromRole != null) {
            if(isset($_SESSION["UserId"])) {
                if ($userObj->GetRole()->getValue() < $fromRole)
                    \Services::RedirectHome();
            } else
                \Services::RedirectHome();
        }
        return $permissions;
    }
}
