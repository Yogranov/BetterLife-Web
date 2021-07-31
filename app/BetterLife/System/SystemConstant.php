<?php
namespace BetterLife\System;

class SystemConstant {
    const SYSTEM_DEBUGGING_MODE = true;
    const SYSTEM_NAME = "BetterLife";
    const SYSTEM_TIMEZONE = "Asia/Jerusalem";
    const SYSTEM_DOMAIN = "https://betterlife.yourdomain.com";
    const SYSTEM_LOCAL_ABSOLUTE_PATH = "/";
    const SYSTEM_WEB_HOST_ROOT_DIRECTORY = "/betterlife/";
    const SYSTEM_EMAIL = "example@yourdomain.com";
    const WEBMASTER_EMAIL = "example@example.com";
    const MAIN_GMAIL = "example@example.com";
    const SEO_EMAIL = "example@example.com";
    const USER_SESSION_NAME = "User";
    const SERVER_IP = "";
    const ENCRYPT_KEY = "encrypt_key.xml";

    const LOG_PATH = __DIR__ . "../logs/";

    const SYSTEM_FLASH = "https://betterlife.yourdomain.com/flash.php";
    const FLASH_MESSAGE = "Flash_Message";


    const MOLE_IMAGES_PATH = '';
    const DOCTOR_IMG_PATH = '';

    //Mysql
    const MYSQL_SERVER = "";
    const MYSQL_PROTOCOL = "tcp";
    const MYSQL_SERVER_PORT = 0;
    const MYSQL_DATABASE = "";

    //Python Server Api Token
    const PYTHON_SERVER_TOKEN = "";

    //Mole Image Info
    const MOLE_IMAGE_WIDTH = 600;
    const MOLE_IMAGE_HEIGHT = 450;

    //Flutter Api Token
    const FLUTTER_TOKEN = "";


    //Google reCAPCHA
    const GOOGLE_RECAPTCHA_SECRET_KEY = "";

    //Google Analytics
    const GOOGLE_ANALYTICS_ACTIVE = true;
    const GOOGLE_ANALYTICS_CODE = "";

    const DB_BACKUP_DAYS = 7;
}