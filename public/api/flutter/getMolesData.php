<?php
require_once '../header.php';
use BetterLife\BetterLife;

$data = BetterLife::GetDB()->where('Token', $_POST['userToken'])->getOne('users', ['Id', 'Email', 'FirstName', 'LastName']);

if(empty($data))
    exit("User cannot be found");


$userObj = \BetterLife\User\User::getById($data["Id"]);

$moles = $userObj->getMoles();

$jsonMoles = [];
foreach ($moles as $mole)
    array_push($jsonMoles, $mole->convertToJson());

echo json_encode($jsonMoles);

