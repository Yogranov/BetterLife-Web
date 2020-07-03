<?php
require_once '../header.php';
use BetterLife\BetterLife;
use BetterLife\System\SystemConstant;

$data = BetterLife::GetDB()->where('Token', $_POST['userToken'])->getOne('users', ['Id', 'Email', 'FirstName', 'LastName']);

if(empty($data))
    exit("User cannot be found");


$userObj = \BetterLife\User\User::getById($data["Id"]);

$moles = $userObj->getMoles();

$pieDataTmp = array();
foreach ($userObj->getMoles() as $mole) {
    $risk = $mole->getLastDetails()->getRiskLevel();
    if (array_key_exists($risk->getName(), $pieDataTmp))
        $pieDataTmp[$risk->getName()]++;
    else
        $pieDataTmp[$risk->getName()] = 1;
}
//$pieDataTmp = json_encode($pieDataTmp);

$jsonToSend = [
    "molesCount" => count($userObj->getMoles()),
    "lastCheck" =>  array_reverse($userObj->getMoles())[0]->getCreateTime()->diff(new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE)))->days,
    "riskDiagram" => $pieDataTmp
];


echo json_encode($jsonToSend);

