<?php
require_once '../header.php';
use BetterLife\BetterLife;
use BetterLife\Repositories\Address;
use BetterLife\System\EmailsConstant;
use BetterLife\System\Services;
use BetterLife\System\SystemConstant;
use BetterLife\User\User;



$firstName = htmlspecialchars(trim($_POST["firstName"]));
$lastName = htmlspecialchars(trim($_POST["lastName"]));
$email = htmlspecialchars(trim($_POST["email"]));
$personId = htmlspecialchars(trim($_POST["personId"]));
$phoneNumber = htmlspecialchars(trim($_POST["phoneNumber"]));
$address = htmlspecialchars(trim($_POST["address"]));
$city = htmlspecialchars(trim($_POST["cityId"]));
$sex = $_POST["sex"];
$haveHistory = $_POST["haveHistory"] == '1' ? 1 : 0;
$password = htmlspecialchars(trim($_POST["password"]));
$rePassword = htmlspecialchars(trim($_POST["repeatPassword"]));

$birthdate = htmlspecialchars(trim($_POST["birthdate"]));

$errors = [];
if (empty($firstName))
    array_push($errors, "לא הוזן שם פרטי");

if (empty($lastName))
    array_push($errors, "לא הוזן שם משפחה");

if (empty($email))
    array_push($errors, "לא הוזן אימייל");
elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
    array_push($errors, "לא הוכנס אימייל תקין");


if (empty($personId))
    array_push($errors, "לא הוזנה תעודת זהות");
elseif (Services::validateID($personId))
    array_push($errors, "תעודת זהות לא תקינה");


$dbEmail = BetterLife::GetDB()->where("Email", $email)->getOne(User::TABLE_NAME, ['Email']);

if(!empty($dbEmail))
    array_push($errors, "דואר אלקטרוני קיים במערכת");

$dbPersonId = BetterLife::GetDB()->where("PersonId", $personId)->getOne(User::TABLE_NAME);
if(!empty($dbPersonId))
    array_push($errors, "לא ניתן לעדכן תעודת זהות");

if (empty($phoneNumber))
    array_push($errors, "לא הוזן מספר טלפון");
elseif (!Services::validatePhoneNumber($phoneNumber))
    array_push($errors, "מספר טלפון לא תקין");

if (empty($address))
    array_push($errors, "לא הוזנה כתובת");

if (empty($city))
    array_push($errors, "לא הוזנה עיר");

if (empty($birthdate))
    array_push($errors, "לא הוזן תאריך לידה");



if (empty($password))
    array_push($errors, "לא הוזנה סיסמה");
elseif (Services::PasswordStrengthCheck($password))
    array_push($errors, "הוזנה סיסמה חלשה");

if (empty($rePassword))
    array_push($errors, "לא הוזנה סיסמה חוזרת");
elseif (Services::PasswordStrengthCheck($rePassword))
    array_push($errors, "הוזנה סיסמה חוזרת חלשה");

if($password != $rePassword)
    array_push($errors, "הסיסמאות לא תואמות");


$respone = 0;
$data = array();
if(empty($errors)) {
    $dateTime = new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));
    $randomToken = md5(Services::RandChars(64));

    $data = [
        "Email" => $email,
        "Password" => password_hash($password, PASSWORD_DEFAULT),
        "FirstName" => $firstName,
        "LastName" => $lastName,
        "PersonId" => $personId,
        "Sex" => $sex,
        "PhoneNumber" => $phoneNumber,
        "BirthDate" => $birthdate,
        "Address" => $address,
        "City" => $city,
        "Roles" => "[1]",
        "HaveHistory" => $haveHistory,
        "RegisterTime" => $dateTime->format("Y-m-d H:i:s"),
        "Token" => Services::GenerateRandomString(256),
        "RecoverToken" => $randomToken,
        "Enable" => 0
    ];

    try {
        $userId = BetterLife::GetDB()->insert(User::TABLE_NAME, $data);

        $emailUrl = SystemConstant::SYSTEM_DOMAIN . "/userConfirm.php?ConfirmToken=" . $randomToken;
        $emailSubject = "אישור הרשמה לאתר";
        $emailBody = EmailsConstant::emailConfirm;
        Services::setPlaceHolder($emailBody, "firstName", $firstName);
        Services::setPlaceHolder($emailBody, "emailUrl", $emailUrl);

        $emailObj = BetterLife::GetEmail($emailSubject, $emailBody);
        $emailObj->addAddress($email);
        if($emailObj->send()) {
            $log = new \BetterLife\System\Logger($userId, "נשלח מייל אימות");
            $log->info();
            $log->writeToDb();
        }

        $log = new \BetterLife\System\Logger($userId, "נרשם לאתר");
        $log->info();
        $log->writeToDb();
        Services::flashUser("היי {$firstName}, נרשמת בהצלחה, על מנת להשלים את הרישום, נשלח אליך לדואר האלקטרוני מייל עם קישור לאימות הכתובת.");

        $respone = [];
    } catch (\Exception $e) {
        $respone = ["Error, cannot update info right now"];
    }
} else {
    $respone = $errors;
}

echo json_encode($respone);