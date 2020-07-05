<?php
require_once '../header.php';
use BetterLife\BetterLife;
use BetterLife\User\User;


$data = BetterLife::GetDB()->get('cities');



echo json_encode($data);