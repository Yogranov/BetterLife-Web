<?php


namespace BetterLife\User;

use BetterLife\System\SystemConstant;

class Session {

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


}