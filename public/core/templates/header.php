<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once '../vendor/autoload.php';
session_start();

use BetterLife\BetterLife;


//Header
const HeaderTemplate = <<<Header
<html lang="he" xmlns="http://www.w3.org/1999/html">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
    <link rel="stylesheet" href="system/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="system/aos/aos.css">
    <link rel="stylesheet" href="system/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="core/css/main.css">

    <title>BetterLife</title>
    <link rel="icon" href="media/favicon.png">

</head>
<body>
Header;


const Navbar = <<<NAVBAR
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-lg-5 shadow-sm mb-5 flex-row-reverse">
        <a class="navbar-brand pl-lg-3" href="#">
            <img src="media/logos/BetterLifeLogo.png" alt="logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="../../index.php">ראשי</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">כתבות</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">אודות</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">צור קשר</a>
                </li>
            </ul>
            {Menu}
        </div>
    </nav>
NAVBAR;



//Menus
const NoneUserMenu = <<<NoneUser
    <a class="nav-item mr-auto ml-lg-2" href="../../login.php" style="color: rgba(0,0,0,0.5)"><i class="fas fa-sign-in-alt"></i> התחבר</a>
NoneUser;


const MemberMenu = <<<Member
            <ul class="mr-auto navbar-nav">
                <div class="dropdown ">
                    <a style="color: rgba(0,0,0,0.5)" class="nav-item mr-auto ml-lg-2 dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {userFirstName}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="text-align: right">
                        <a class="dropdown-item" href="#"><i class="fas fa-inbox"></i> תיבת הודעות</a>
                        {MemberMenu}
                        <hr>
                        <a class="dropdown-item" style="text-align: center" href="#">יציאה</a>
                    </div>
                </div>
            </ul>
Member;


//Account Dropdowns
const PatientMenu = <<<PatientMenu
                        <hr>
                        <a class="dropdown-item" href="#"><i class="fas fa-clinic-medical"></i> פרופיל רפואי</a>
PatientMenu;

const DoctorMenu = <<<PatientMenu
                        <hr>
                        <a class="dropdown-item" href="#"><i class="fas fa-clinic-medical"></i> בדיקת שומות</a>
                        <a class="dropdown-item" href="#"><i class="fas fa-inbox"></i> חיפוש מטופל</a>
PatientMenu;

const AdminMenu = <<<PatientMenu
                        <hr>
                        <a class="dropdown-item" href="#"><i class="fas fa-clinic-medical"></i> ניהול משתמשים</a>
PatientMenu;


echo BetterLife::buildNavbar( HeaderTemplate. Navbar);
