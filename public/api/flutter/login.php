<?php
require_once '../header.php';
use BetterLife\BetterLife;
use BetterLife\User\User;

$errors = [];

$userData = BetterLife::GetDB()->where("Email", $_POST['email'])->getOne(User::TABLE_NAME,["Id", "Password, Token"]);




if (empty($userData) || !password_verify($_POST['password'], $userData["Password"]))
    array_push($errors, "שם המשתמש או הסיסמה אינם נכונים");


if(empty($errors)) {
    $userObj = User::getById($userData['Id']);
    if($userObj->checkNewUser())
        array_push($errors, 'משתמש חדש, יש לאמת דוא"ל לפני הכניסה');
}




$response;
if(empty($errors))
    $response["Token"] = $userData['Token'];
else
    $response["Errors"] = $errors;


echo json_encode($response);