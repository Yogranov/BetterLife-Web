<?php
require_once '/home/goru/public_html/betterlife/vendor/autoload.php';
use BetterLife\BetterLife;

$dir = $_GET["dir"];
$imgDec = explode('-', base64_decode($_GET["image"]));

$img = $imgDec[1];
$moleId = explode('_', $img)[1];
$userId = BetterLife::GetDB()->where("Id", $moleId)->getOne("moles", null, "UserId")["UserId"];


if($userId == $imgDec[0]) {
    $file = '/home/goru/public_html/betterlife/media/moles/' . $dir .'/' . $img . '.jpg';

    header('Content-type: image/jpeg');
    echo file_get_contents($file);
}