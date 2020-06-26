<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST,PUT");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once '/home/goru/public_html/betterlife/vendor/autoload.php';

if(!isset($_POST['TOKEN']) || $_POST['TOKEN'] != \BetterLife\System\SystemConstant::FLUTTER_TOKEN) {
    header("HTTP/1.1 401 Not Authorized");
    exit();
}

