<?php


namespace BetterLife\System;


class CSRF {

    const SESSION_NAME = "CSRF_TOKEN";
    const FORM_FIELD_NAME = "TOKEN";


    public static function generateToken() {
        if(!isset($_SESSION[self::SESSION_NAME]))
            $_SESSION[self::SESSION_NAME] = bin2hex(random_bytes(16));
    }

    public static function formField() {
        return "<input type='hidden' name='" . self::FORM_FIELD_NAME . "' value='" . $_SESSION[self::SESSION_NAME] . "'>";
    }
}