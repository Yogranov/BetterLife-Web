<?php

//todo: make this file protected!

$image = $_GET["image"];
$dir = $_GET["dir"];

$file = '/home/goru/public_html/betterlife/media/moles/' . $dir .'/' . $image . '.jpg';

header('Content-type: image/jpeg');
echo file_get_contents($file);
?>