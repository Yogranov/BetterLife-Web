<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '/home/goru/public_html/betterlife/vendor/autoload.php';
session_start();

use BetterLife\BetterLife;
use BetterLife\System\Services;
use BetterLife\System\CSRF;

if($_SERVER['REQUEST_METHOD'] === 'POST')
    if(!isset($_POST[CSRF::FORM_FIELD_NAME]) || $_POST[CSRF::FORM_FIELD_NAME] !== $_SESSION[CSRF::SESSION_NAME])
        Services::flashUser("לא נמצא מטבע");


CSRF::generateToken();

//Header
const HeaderTemplate = <<<Header
<html lang="he" xmlns="http://www.w3.org/1999/html">
<head>
    {googleAnalytics}
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
    <title>BetterLife</title>
    <link rel="icon" href="../../media/favicon.png">
    
    <link rel="stylesheet" type="text/css" href="../../system/jquery-ui/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="../../system/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="../../system/aos/aos.css">
    <link rel="stylesheet" type="text/css" href="../../system/fontawesome/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="../../system/datatables/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="../../core/css/main.css">
 
    <script src="../../system/other/chart.min.js"></script>
    <script src="../../system/jquery/jquery-3.4.1.min.js"></script>
    <script src="../../system/jquery-ui/jquery-ui.min.js"></script>
    <script src="../../system/jquery/jquery.ui.touch-punch.min.js"></script>
    <script src="../../system/jquery-ui/datepicker-he.js"></script>
    <script src="../../system/fontawesome/js/all.min.js"></script>
    <script src="../../system/jquery/jquery.validate.min.js"></script>
    <script src="../../system/jquery/jquery.validate.additional.methods.min.js"></script>
    <script src="../../system/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../system/aos/aos.js"></script>
    <script type="text/javascript" src="../../system/tinymce/tinymce.min.js"></script>
    <script type="text/javascript" src="../../system/tinymce/init-tinymce.js"></script>
    <script type="text/javascript" src="../../system/datatables/datatables.min.js"></script>
    <script type="text/javascript" src="../../system/qrcodejs/qrcodejs.min.js"></script>

    <script src="../../core/js/ajaxFunctions.js"></script>
    <script src="../../core/js/main.js"></script>
    
    
    
</head>
<body>
Header;


const Navbar = <<<NAVBAR
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-lg-5 shadow-sm mb-5 flex-row-reverse">
    <div class="container-fluid flex-row-reverse">
    <a class="navbar-brand pl-lg-3" href="https://betterlife.845.co.il">
            <img src="../../media/logos/BetterLifeLogoWhite.png" alt="logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="https://betterlife.845.co.il/index.php">ראשי</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://betterlife.845.co.il/articles/articles.php">מידע</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://betterlife.845.co.il/doctors.php">הרופאים</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://betterlife.845.co.il/do-it-yourself.php">בדיקה עצמאית</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://betterlife.845.co.il/about-us.php">אודות</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://betterlife.845.co.il/contact-us.php">צור קשר</a>
                </li>
            </ul>
            {Menu}
        </div>
    </div>
</nav>
NAVBAR;



//Menus
const NoneUserMenu = <<<NoneUser
    <a class="nav-item mr-auto ml-lg-2" href="../../login.php" style="color: rgba(255,255,255,0.5)"><i class="fas fa-sign-in-alt"></i> התחבר</a>
NoneUser;


const MemberMenu = <<<Member
            <ul class="mr-auto navbar-nav">
                <div class="dropdown">
                    <a class="nav-item mr-auto ml-lg-2 dropdown-toggle" style="color: rgba(255,255,255,0.5)" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {NotiBadge}{userFirstName}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="text-align: right">
                        <a class="dropdown-item" href="../../user/mail-box.php"><i class="fas fa-inbox"></i> תיבת הודעות</a>
                        {MemberMenu}
                        <hr>
                        <a class="dropdown-item" href="../../user/profile.php"><i class="fas fa-id-card"></i> פרופיל אישי</a>
                        <a class="dropdown-item" style="text-align: center" href="../../core/logout.php">יציאה</a>
                    </div>
                </div>
            </ul>
Member;


//Account Dropdowns
const PatientMenu = <<<PatientMenu
                        <hr>
                        <a class="dropdown-item" href="../../patient/medical-profile.php"><i class="fas fa-clinic-medical"></i> פרופיל רפואי</a>
                        <a class="dropdown-item" href="../../patient/add-mole.php"><i class="fas fa-file-medical"></i> בדיקה חדשה</a>
PatientMenu;

const DoctorMenu = <<<PatientMenu
                        <hr>
                        <a class="dropdown-item" href="../../doctors/moles-list.php"><i class="fas fa-vial"></i> בדיקת שומות</a>
                        <a class="dropdown-item" href="../../doctors/patient-search.php"><i class="fas fa-search"></i> חיפוש מטופל</a>
PatientMenu;

const ContentWriterMenu = <<<PatientMenu
                        <hr>
                        <a class="dropdown-item" href="../../articles/new-article.php"><i class="fas fa-paragraph"></i> כתבה חדשה</a>
PatientMenu;

const AdminMenu = <<<PatientMenu
                        <hr>
                        <a class="dropdown-item" href="../../admin/user-management.php"><i class="fas fa-user-friends"></i> ניהול משתמשים</a>
                        <a class="dropdown-item" href="../../admin/log.php"><i class="fas fa-list-alt"></i> לוג</a>
PatientMenu;

$tmpHeader = HeaderTemplate;
if(!\BetterLife\System\SystemConstant::SYSTEM_DEBUGGING_MODE)
    Services::setPlaceHolder($tmpHeader, "googleAnalytics", \BetterLife\System\SystemConstant::GOOGLE_ANALYTICS_CODE);
else
    Services::setPlaceHolder($tmpHeader, "googleAnalytics", "");

echo $tmpHeader;

\BetterLife\User\Login::Reconnect();

$pageTemplate = Navbar;
Services::setPlaceHolder($pageTemplate, "Menu", BetterLife::navBuilder());
//echo $pageTemplate;