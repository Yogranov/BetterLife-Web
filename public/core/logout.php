<?php

require_once '../../vendor/autoload.php';
use BetterLife\User\Login;

session_start();
Login::Disconnect();