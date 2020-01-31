<?php
require_once "core/templates/header.php";
use BetterLife\BetterLife;
use BetterLife\User\User;
use BetterLife\System\Services;



if(isset($_GET["ConfirmToken"])) {
    $token = BetterLife::GetDB()->escape(htmlspecialchars(trim($_GET["ConfirmToken"])));
    BetterLife::GetDB()->where("RecoverToken", $token)->update(User::TABLE_NAME, ["Roles" => "[2]", "RecoverToken" => ""]);
}

Services::flashUser("המשתמש אומת, מיד נעבור לדף הבית");