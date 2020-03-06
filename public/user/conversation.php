<?php
require_once "../core/templates/header.php";
use BetterLife\User\Session;
use BetterLife\User\User;
use BetterLife\BetterLife;
use BetterLife\System\Services;
use BetterLife\MailBox\Conversation;
use BetterLife\System\SystemConstant;


if(Session::checkUserSession() && isset($_GET["Con"]))
    $userObj = User::GetUserFromSession();
else
    Services::RedirectHome();

try {
    $conObj = new Conversation(explode('-', base64_decode($_GET["Con"]))[0]);
} catch (Exception $e) {
    Services::RedirectHome();
}

if($userObj->getToken() != explode('-', base64_decode($_GET["Con"]))[1])
    Services::RedirectHome();

$conObj->setView($userObj->getId());
$messagesRows = "";

$notiCount = $userObj->countUnreadCon();
$refreshNoti = "<script>";
$refreshNoti .= $notiCount != 0 ? "$('.icon-badge i').text({$notiCount})" : "$('.icon-badge').remove()";
$refreshNoti .= "</script>";

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

$pageTemplate .= <<<PageBody
<div class="container mt-5 mb-5">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>תיבת הודעות</h2>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-12 chat-box">
            <div class="chat-header">
                <h4 class="mt-1">נושא השיחה: {$conObj->getSubject()}</h4>
                <h6 class="mb-2">{$userObj->getFullName()} ,{$conObj->getRecipient()->getFullName()}</h6>
            </div>
            
            <ol class="chat">
                {$messagesRows}
            </ol>
            <h4 id="messageEmpty" class="text-center"></h4>
            <div class="row">
                <div class="col-10" style="padding: 0">
                    <div class="form-group">
                        <input type="text" class="form-control" id="NewMessage" placeholder="תוכן ההודעה" required autocomplete="off">
                    </div>
                </div>
            
                <div class="col-2" style="padding: 0">                   
                    <input id="submit-button" type="submit" value="שלח הודעה" onclick="addConMessage({$conObj->getId()}, $('#NewMessage'), {$userObj->getId()}, '{$userObj->getToken()}')" class="btn btn-info btn-block">
                </div> 
            </div>
            
        </div>
    </div>
       
</div>

<script>
    loadMessages({$conObj->getId()} ,{$userObj->getId()}, '{$userObj->getToken()}');
</script>
{$refreshNoti}
<script>
$(document).ready(function(){
    $('#NewMessage').keypress(function(e){
      if(e.keyCode==13)
      $('#submit-button').click();
    });
});
</script>
PageBody;


echo $pageTemplate;
include "../core/templates/footer.php";
