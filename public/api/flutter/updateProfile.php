<?php
require_once '../header.php';
use BetterLife\BetterLife;
use BetterLife\System\Services;
use BetterLife\User\User;

$data = BetterLife::GetDB()->where('Token', $_POST['userToken'])->getOne('users', ['Id']);

if(empty($data))
    exit("User cannot be found");



$firstName = htmlspecialchars(trim($_POST["firstName"]));
$lastName = htmlspecialchars(trim($_POST["lastName"]));
$email = htmlspecialchars(trim($_POST["email"]));
$personId = htmlspecialchars(trim($_POST["personId"]));
$phoneNumber = htmlspecialchars(trim($_POST["phoneNumber"]));
$address = htmlspecialchars(trim($_POST["address"]));
$city = htmlspecialchars(trim($_POST["cityId"]));
$sex = $_POST["sex"];
$haveHistory = isset($_POST["haveHistory"]) ? true : false;

$changePassword = isset($_POST["changePassword"]) ? true : false;

$birthdate = htmlspecialchars(trim($_POST["birthdate"]));
$birthdateTmp = explode('/', $birthdate);
$birthdate = $birthdateTmp[2] . "-";
$birthdate .= $birthdateTmp[1] . "-";
$birthdate .= $birthdateTmp[0];


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

$dbEmail = BetterLife::GetDB()->where("Email", $email)->getOne(User::TABLE_NAME);
$dbPersonId = BetterLife::GetDB()->where("PersonId", $personId)->getOne(User::TABLE_NAME);
if(!empty($dbEmail) && $userObj->getEmail() != $email)
    array_push($errors, "דואר אלקטרוני קיים במערכת");

if(!empty($dbPersonId) && $userObj->getPersonId() != $personId)
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
elseif (!checkdate($birthdateTmp[1], $birthdateTmp[0], $birthdateTmp[2]))
    array_push($errors, "תאריך לידה לא תקין");


if ($changePassword) {
    $oldPassword = htmlspecialchars(trim($_POST["oldPassword"]));
    $password = htmlspecialchars(trim($_POST["password"]));
    $rePassword = htmlspecialchars(trim($_POST["rePassword"]));

    if (empty($oldPassword))
        array_push($errors, "לא הוזנה סיסמה ישנה");
    elseif (!password_verify($oldPassword, $userObj->getPassword()))
        array_push($errors, "סיסמה ישנה לא נכונה");


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
}

$respone = 0;
if(empty($errors)) {
    $insertData = BetterLife::GetDB()->where('Id', $data["Id"])->getOne('users');
    $respone = 0;
} else {
    $respone = json_encode($errors);
}

echo $respone;