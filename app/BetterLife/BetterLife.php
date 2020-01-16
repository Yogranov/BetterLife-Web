<?php
namespace BetterLife;

use BetterLife\System\SystemConstant;
use MysqliDb;
use PHPMailer\PHPMailer\PHPMailer;
use BetterLife\System\Exception;
use BetterLife\System\Credential;

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

}
