<?php
require_once '../header.php';
use BetterLife\BetterLife;
use BetterLife\System\Services;
use BetterLife\System\SystemConstant;

$data = BetterLife::GetDB()->where('Token', $_POST['userToken'])->getOne('users', ['Id']);

if(empty($data))
    exit("User cannot be found");

//$name = $_POST['name'];


$location = htmlspecialchars(trim($_POST["location"]));
$color = htmlspecialchars(trim($_POST["color"]));
$size = htmlspecialchars(trim($_POST["size"]));
$image64Base = $_POST['image'];

$errors = [];
if (empty($location))
    array_push($errors, "לא הוזן מיקום");

if (empty($color))
    array_push($errors, "לא הוזן צבע");

if (empty($size))
    array_push($errors, "לא הוזן גודל");

if (!isset($_POST["image"]))
    array_push($errors, "לא הוזנה תמונה");



$response = 0;
if(empty($errors)) {

    $realImage = base64_decode($image64Base);

    $dateTime = new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));

    $moleData = array(
        "UserId" => $data['Id'],
        "Location" => $location,
        "CreateTime" => $dateTime->format("Y-m-d H:i:s"),
    );

    $moleId = BetterLife::GetDB()->insert("moles", $moleData);
    $imgName = $moleId . "_" . "1";

    $moleDetailsData = array(
        "MoleId" => $moleId,
        "ImgUrl" => $imgName,
        "ImgFigureUrl" => $imgName,
        "ImgSurfaceUrl" => $imgName,
        "Size" => $size,
        "Color" => $color,
        "CreateTime" => $dateTime->format("Y-m-d H:i:s"),
        "RiskLevel" => 1
    );

    BetterLife::GetDB()->insert("moleDetails", $moleDetailsData);


    $path = '/home/goru/public_html/betterlife/media/moles/regular/'. $imgName . '.jpg';
    file_put_contents($path, $realImage);



    $img = new Imagick($path);
    $img->resizeImage(SystemConstant::MOLE_IMAGE_WIDTH, SystemConstant::MOLE_IMAGE_HEIGHT, 1,false);
    $img->writeImage($path);


    try{
        Services::sendPostRequest('http://frizen700.ddns.net:587/saveToDir', array("moleImage" => $image64Base, "name" => $imgName));
    } catch (\Exception $e) {

    }
    $respone = [];
} else {
    $respone = $errors;
}

echo json_encode($respone);
