<?php
require_once '../header.php';
use BetterLife\BetterLife;
use BetterLife\User\User;

$data = BetterLife::GetDB()->where('Token', $_POST['userToken'])->getOne('users', ['Id', 'Email', 'FirstName', 'LastName', 'PhoneNumber', 'PersonId', 'HaveHistory', 'Birthdate', 'Sex', 'Enable']);


if(empty($data))
    exit("User cannot be found");

BetterLife::GetDB()->where('QrCode', $_POST['qrCode'])->update('qrCodes', ['UserToken' => $_POST['userToken']]);
