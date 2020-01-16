<?php
/**
 * Created by PhpStorm.
 * User: Yogev
 * Date: 15-Feb-18
 * Time: 10:05
 */
namespace BetterLife\User;
class Token
{
    public static function Generate(){
        return $_SESSION['token'] = base64_encode(random_bytes(32));
    }

    public static function  Check($token) {
        if(isset($_SESSION['token']) && $token === $_SESSION['token']) {
            unset($_SESSION['token']);
            return true;
        }
        return false;
    }
}