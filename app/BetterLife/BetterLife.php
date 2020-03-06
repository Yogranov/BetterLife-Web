<?php
namespace BetterLife;

use BetterLife\System\SystemConstant;
use BetterLife\User\Role;
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



    public static function GetEmail(string $subject, string $message) {
        $credential = Credential::GetCredential('sql_' . SystemConstant::MYSQL_SERVER . '_' . SystemConstant::MYSQL_SERVER_PORT . '_' . SystemConstant::MYSQL_DATABASE);

        $Email = new PHPMailer();
        $Email->IsSMTP();
        $Email->SMTPAuth = true;
        $Email->SMTPSecure = 'tls';
        $Email->Host = "mail.845.co.il";
        $Email->Port = 587;
        $Email->IsHTML(true);
        $Email->CharSet = 'UTF-8';
        $Email->Username = SystemConstant::SYSTEM_EMAIL;
        $Email->Password = $credential->GetPassword();
        $Email->SetFrom(SystemConstant::SYSTEM_EMAIL, SystemConstant::SYSTEM_NAME);


        $body ="<html dir=rtl>
                    <body>
                        {$message}
                    </body>
                </html>";

        $Email->Subject = $subject;
        $Email->Body = $body;
        $Email->AltBody = strip_tags($message);

        return $Email;

    }



    public static function navBuilder() {
        $menu = "";
        if(Session::checkUserSession()) {
            $userObj = User::GetUserFromSession();
            if(!$userObj->checkNewUser()) {
                $menu = MemberMenu;
                Services::setPlaceHolder($menu, "userFirstName", $userObj->getFirstName());
                $noti = $userObj->countUnreadCon() > 0 ? "<div class='icon-badge'><i>{$userObj->countUnreadCon()}</i></div>" : "";
                Services::setPlaceHolder($menu, "NotiBadge", $noti);
                $tmpSubMenu = "";
                foreach ($userObj->getRoles() as $role) {
                    switch ($role->getId()){
                        case Role::PATIENT_ID:
                            $tmpSubMenu .= PatientMenu;
                            break;

                        case Role::DOCTOR_ID:
                            $tmpSubMenu .= DoctorMenu;
                            break;

                        case Role::CONTENT_WRITER:
                            $tmpSubMenu .= ContentWriterMenu;
                            break;

                        case Role::ADMIN_ID:
                            $tmpSubMenu .= AdminMenu;
                            break;

                        default:
                            $tmpSubMenu .= "";
                            break;
                    }
                }
                Services::setPlaceHolder($menu, "MemberMenu", $tmpSubMenu);
            } else {
                unset($_SESSION[SystemConstant::USER_SESSION_NAME]);
                Services::flashUser("משתמש לא מאומת");
            }
        } else
            $menu = NoneUserMenu;

        return $menu;
    }





    public static function GetPermissions($toRoles = null) {
        if ($toRoles != null) {
            if(Session::checkUserSession()) {
                $userObj = User::GetUserFromSession();
                $flag = 1;

                if(is_array($toRoles)) {
                    foreach ($userObj->getRoles() as $role) {
                        foreach ($toRoles as $toRole) {
                            if($role->getId() == $toRole)
                                $flag = 0;
                        }
                    }
                } else {
                    foreach ($userObj->getRoles() as $role)
                        if($role->getId() == $toRoles)
                            $flag = 0;
                }
                if ($flag)
                    Services::flashUser("הגישה נדחתה.");
            } else
                Services::redirectUser(SystemConstant::SYSTEM_DOMAIN . "/login.php");
        }
    }

}
