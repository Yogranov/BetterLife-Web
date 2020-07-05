<?php
require_once '../header.php';
use BetterLife\BetterLife;
use BetterLife\User\User;

$data = BetterLife::GetDB()->where('Token', $_POST['userToken'])->getOne('users', ['Id', 'Email', 'FirstName', 'LastName', 'phoneNumber', 'personId', 'haveHistory', 'birthdate', 'sex']);


if(empty($data))
    exit("User cannot be found");

$userObj = User::getById($data["Id"]);
$data["address"] = $userObj->getAddress()->getAddress();
$data["cityId"] = $userObj->getAddress()->getCityId();


echo json_encode($data);