<?php
//todo: make this file protected!

$file = '../media/doctors/' . $_GET["image"] . '.jpg';

header('Content-type: image/jpeg');
echo file_get_contents($file);
?>