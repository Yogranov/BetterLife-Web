<?php
/**
 * Created by PhpStorm.
 * User: Yogev
 * Date: 03-Oct-17
 * Time: 11:02
 */

namespace BetterLife\User;


class Cookie {

    public static function Exists($name) {
        return (isset($_COOKIE[$name])) ? true : false;
    }

    public static function Get($name) {
        return $_COOKIE[$name];
    }

    public static function Put($name, $value, $expiry) {
        if(setcookie($name, $value, time() + $expiry, '/', null, true,true)) {
            return true;
        }
        return false;
    }

    public static function Delete($name) {
        self::Put($name,"", time() - 1);
    }









}