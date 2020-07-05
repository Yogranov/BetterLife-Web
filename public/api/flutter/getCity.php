<?php
require_once '../header.php';
use BetterLife\BetterLife;
use BetterLife\User\User;


$data = BetterLife::GetDB()->where('Id', $_POST['cityId'])->getOne('cities');



echo json_encode($data);