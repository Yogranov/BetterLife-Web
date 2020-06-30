<?php
require_once '../header.php';
use BetterLife\BetterLife;

$data = BetterLife::GetDB()->where('Token', $_POST['userToken'])->getOne('users', ['Id', 'Email', 'FirstName', 'LastName']);

if(empty($data))
    exit("User cannot be found");

$mole = \BetterLife\Mole\Mole::getById(explode('_', $_POST["imgUrl"])[0]);
if ($mole->getUserId() != $data["Id"])
    exit("Mole doesnt belong to user");

$file = '/home/goru/public_html/betterlife/media/moles/' . $_POST["dir"] .'/' . $_POST["imgUrl"] . '.jpg';

header('Content-type: image/jpeg');
echo file_get_contents($file);
