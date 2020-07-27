<?php
require_once '/home/goru/public_html/betterlife/vendor/autoload.php';

use BetterLife\System\Services;
use BetterLife\User\Login;
use BetterLife\User\User;
use BetterLife\Article\ArticleComment;
use BetterLife\Article\Article;
use BetterLife\System\SystemConstant;
use BetterLife\BetterLife;
use BetterLife\MailBox\Message;
use BetterLife\MailBox\Conversation;
use BetterLife\System\Encryption;
use BetterLife\User\Session;

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


if($switch === 'ConMessage') {

    if(User::checkIfUserExist($_POST["UserId"])) {

        $conObj = new Conversation($_POST["ConId"]);
        $userObj = User::getById($_POST["UserId"]);

        if($userObj->getToken() === $_POST["Token"] && ($conObj->getCreator() == $userObj || $conObj->getRecipient() == $userObj)) {
            if(empty($_POST["Message"])) {
                echo  json_encode(array("Error" => 1));
                return ;
            }

            $conObj->newMessage($_POST["Message"], $userObj->getId());

            $dateTime = new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));
            $json = array("FirstName" => $userObj->getFirstName(), "Timestamp" => $dateTime->format("d/m/y h:i"), "Sex" => $userObj->getSex());
            echo json_encode($json);

        }
    }
}


if($switch === 'LoadMessages') {

    if (User::checkIfUserExist($_POST["UserId"])) {

        $conObj = new Conversation($_POST["ConId"]);
        $userObj = User::getById($_POST["UserId"]);

        if ($userObj->getToken() === $_POST["Token"] && ($conObj->getCreator() == $userObj || $conObj->getRecipient() == $userObj)) {
            !$conObj->checkView($userObj->getId()) ? $conObj->setView($userObj->getId()) : null;
            $messagesRows = "";
            foreach ($conObj->getMessages() as $message) {

                $side = $message->getCreator() == $userObj ? 'self' : 'other';
                $otherImg = file_exists(SystemConstant::DOCTOR_IMG_PATH . $message->getCreator()->getId() . '.jpg') ? "../../core/services/imageHandle.php?Method=DoctorsPage&image={$message->getCreator()->getId()}" : '../media/characters/male2.jpg';
                $selfImg = $userObj->getSex() ? '../media/characters/female2.jpg' : '../media/characters/male1.jpg';
                $img = $side == 'self' ? $selfImg : $otherImg;

                $messagesRows .= "<li class='{$side}'>
                                        <div class='avatar'><img src='{$img}'/></div>
                                        <div class='msg'>
                                            <h6>{$message->getCreator()->getFirstName()}</h6>
                                            <p>{$message->getContent()}</p>
                                            <time>{$message->getCreateTime()->format("d/m/y H:i")}</time>
                                        </div>
                                    </li>";

            }

            $json = array("Messages" => $messagesRows);
            echo json_encode($json);
            $conObj = null;
            unset($conObj);

        }
    }
}


if($switch === 'qrCodeStore') {
    $data = BetterLife::GetDB()->where('UserIp', Services::getClientIp())->getOne('qrCodes');
    if(!empty($data))
        BetterLife::GetDB()->where('UserIp', Services::getClientIp())->delete('qrCodes');

    if(isset($_POST['QrCode']) && !empty($_POST['QrCode']))
        BetterLife::GetDB()->insert('qrCodes', ['UserIp' => Services::getClientIp(), 'QrCode' => $_POST['QrCode']]);
}



if($switch === 'qrCodeCheck') {
    $data = BetterLife::GetDB()->where('UserIp', Services::getClientIp())->where('QrCode', $_POST['QrCode'])->getOne('qrCodes');

    $token = json_encode(['UserToken' => 'noToken']);
    if(!empty($data) && !empty($data['UserToken'])) {
        $userData = BetterLife::GetDB()->where('Token', $data['UserToken'])->getOne('users');
        BetterLife::GetDB()->where('UserIp', Services::getClientIp())->delete('qrCodes');
        $token = json_encode(['UserToken' => $userData['Token']]);

    }
    echo $token;



}