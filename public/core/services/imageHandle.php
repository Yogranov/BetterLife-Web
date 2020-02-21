<?php
require_once '/home/goru/public_html/betterlife/vendor/autoload.php';
use BetterLife\User\User;
use BetterLife\Mole\MoleDetails;

$switch = $_GET["Method"];



if($switch == "DoctorsPage"){
    $file = '../../../media/doctors/' . $_GET["image"] . '.jpg';

    header('Content-type: image/jpeg');
    echo file_get_contents($file);
}


if($switch == "Patient"){
    $userObj = User::getById($_GET["UserId"]);
    if($userObj->getToken() != $_GET["Token"])
        return;

    $moleDetail = new MoleDetails($_GET["MoleId"]);

    if($moleDetail->getPatientObj()->getId() != $userObj->getId())
        return;
    $file = '/home/goru/public_html/betterlife/media/moles/' . $_GET["Dir"] .'/' . $moleDetail->getImgUrl() . '.jpg';

    header('Content-type: image/jpeg');
    echo file_get_contents($file);
}




if($switch == "DoctorMole") {
    $userObj = User::getById($_GET["UserId"]);

    if($userObj->getToken() != $_GET["Token"])
        return;

    if(!$userObj->checkRole(3))
        return;

    $file = '/home/goru/public_html/betterlife/media/moles/' . $_GET["dir"] .'/' . $_GET["image"] . '.jpg';

    header('Content-type: image/jpeg');
    echo file_get_contents($file);
}



?>