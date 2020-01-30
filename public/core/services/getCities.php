<?php
require_once '../../../vendor/autoload.php';

use BetterLife\BetterLife;
use BetterLife\System\SystemConstant;

if($_SERVER["HTTP_REFERER"] !== "https://betterlife.845.co.il/register.php" && $_SERVER['REMOTE_ADDR'] == SystemConstant::SERVER_IP)
    die("The access rejected");

$searchKey = htmlspecialchars(trim(BetterLife::GetDB()->escape($_POST["term"])));

if(isset($_POST["term"])) {
    $sql = BetterLife::GetDB()->where("HebrewName", "%" . $searchKey . "%", 'like')->get("cities", 5);
    $arrayToSend = array();
    foreach ($sql as $data) {
        $arrayToSend[] = array(
            'label' => $data["HebrewName"],
            'value' => $data["Id"]
        );
    }
    echo json_encode($arrayToSend);
}