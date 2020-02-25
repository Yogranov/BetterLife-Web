<?php
require_once '/home/goru/public_html/betterlife/vendor/autoload.php';
use BetterLife\User\User;
use BetterLife\Article\ArticleComment;
use BetterLife\Article\Article;
use BetterLife\System\SystemConstant;
use BetterLife\BetterLife;

$switch = $_POST["Type"];

if($switch === 'CommentLike') {
    if(User::checkIfUserExist($_POST["UserId"])) {
        $userObj = User::getById($_POST["UserId"]);
        if($userObj->getToken() === $_POST["Token"]) {
            $commentObj = ArticleComment::getById($_POST["CommentId"]);
            $commentObj->addOrRemoveLike($_POST["UserId"]);
        }
    }
    echo $commentObj->getLikes();
}


if($switch === 'ArticleLike') {
    if(User::checkIfUserExist($_POST["UserId"])) {
        $userObj = User::getById($_POST["UserId"]);
        if($userObj->getToken() === $_POST["Token"]) {
            $articleObj = Article::getById($_POST["ArticleId"]);
            $articleObj->addOrRemoveLike($_POST["UserId"]);
        }
    }
    echo $articleObj->getLikes();
}


if($switch === 'ArticleComment'){

    if(empty($_POST["Content"])){
        echo  json_encode(array("Error" => 1));
        return ;
    }



    $dateTime = new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));
    $userObj = User::getById($_POST["UserId"]);
    $data = array(
        "ArticleId" => $_POST["ArticleId"],
        "Content" => $_POST["Content"],
        "Creator" => $userObj->getId(),
        "Likes" => "",
        "CreateTime" => $dateTime->format("Y-m-d H:i:s")
    );
    try{
        $commentId = BetterLife::GetDB()->insert(ArticleComment::TABLE_NAME, $data);
    } catch (\Throwable $e){
        echo  json_encode(array("Error" => 1));
        return ;
    }

    $json = array("FullName" => $userObj->getFullName(), "Timestamp" => $dateTime->format("d/m/y h:i"), "CommentId" => $commentId, "Sex" => $userObj->getSex());
    echo json_encode($json);

}

if($switch === 'showHideArticle') {
    if(User::checkIfUserExist($_POST["UserId"])) {

        $userObj = User::getById($_POST["UserId"]);
        if($userObj->getToken() === $_POST["Token"] && $userObj->checkRole([4,5])) {
            $articleObj = Article::getById($_POST["ArticleId"]);

            if($_POST["Method"] == "hide")
                $articleObj->hideArticle();

            if($_POST["Method"] == "show")
                $articleObj->showArticle();

        }
    }
}


if($switch === 'enableDisableUser') {
    if(User::checkIfUserExist($_POST["adminId"])) {

        $adminObj = User::getById($_POST["adminId"]);
        if($adminObj->getToken() === $_POST["adminToken"] && $adminObj->checkRole([5])) {
            $userObj = User::getById($_POST["UserId"]);

            if($_POST["Method"] == "enable")
                $userObj->enableUser();

            if($_POST["Method"] == "disable")
                $userObj->disableUser();

        }
    }
}