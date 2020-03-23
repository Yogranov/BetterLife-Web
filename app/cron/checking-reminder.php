<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';
use BetterLife\System\Services;
use BetterLife\System\EmailsConstant;
use BetterLife\System\SystemConstant;
use BetterLife\BetterLife;
use BetterLife\User\Role;
use BetterLife\User\User;
use BetterLife\Mole\MoleDetails;



$emailSubject = "תזכורת לבדיקת שומות";

$users = BetterLife::GetDB()->where("Roles", "%" . Role::PATIENT_ID . "%", "LIKE")->get(User::TABLE_NAME);
foreach ($users as $user) {
    $userObj = User::getById($user["Id"]);
    $now = new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));

    $sql = "SELECT MD.CreateTime 
            FROM `moles` M JOIN `moleDetails` MD ON M.Id = MD.MoleId
            WHERE M.UserId = {$userObj->getId()}
            ORDER BY MD.CreateTime DESC
            LIMIT 1";

    $dates = BetterLife::GetDB()->query($sql);
    foreach ($dates as $date) {
        $lastCheck = new \DateTime($date["CreateTime"]);

        $diff = date_diff($now, $lastCheck);
        $months = round($diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24);

        if($months == 6) {
            $emailBody = EmailsConstant::contactUs;
            Services::setPlaceHolder($emailBody, "firstName", $userObj->getFirstName());
            $emailObj = BetterLife::GetEmail($emailSubject, $emailBody);
            $emailObj->addAddress($userObj->getEmail());
            if($emailObj->send()) {
                $log = new \BetterLife\System\Logger($userObj->getId(), "נשלחה תזכורת לבצע בדיקה חצי שנתית");
                $log->info();
                $log->writeToDb();
            }
        }
    }



}