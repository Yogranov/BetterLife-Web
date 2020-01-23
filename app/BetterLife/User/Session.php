<?php


namespace BetterLife\User;

use BetterLife\System\SystemConstant;

class Session {

    const PREVIOUS_PAGE = "PreviousPage";
    const LOGIN_ATTEMTS = "LoginAttemps";

    public static function checkUserSession() {
        return isset($_SESSION[SystemConstant::USER_SESSION_NAME]) ? true : false;
    }


    public static function newSession(string $sessionName, string $content) {
        $_SESSION[$sessionName] = $content;
    }


    public static function checkSession($sessionName) {
        if(isset($_SESSION[$sessionName]))
            return true;

        return false;
    }

    public static function savePreviousPage() {
        if(isset($_SERVER['HTTP_REFERER']))
            $_SESSION[self::PREVIOUS_PAGE] = $_SERVER['HTTP_REFERER'];
        else
            $_SESSION[self::PREVIOUS_PAGE] = SystemConstant::SYSTEM_DOMAIN;
    }

    public static function checkLoginAttempts(int $attemps = 3) {
        if(!isset($_SESSION[self::LOGIN_ATTEMTS])) {
            $_SESSION[self::LOGIN_ATTEMTS] = 0;
            return true;
        }
        else {
            if($_SESSION[self::LOGIN_ATTEMTS] > $attemps)
                return false;
        }
        return true;
    }

    public static function incLoginAttempts() {
        if(!isset($_SESSION[self::LOGIN_ATTEMTS]))
            $_SESSION[self::LOGIN_ATTEMTS] = 0;
        else
            $_SESSION[self::LOGIN_ATTEMTS]++;
    }
}