<?php
require_once "../core/templates/header.php";
use BetterLife\BetterLife;
use BetterLife\User\User;
use BetterLife\System\Services;
use BetterLife\User\Session;
use BetterLife\MailBox\Conversation;
use BetterLife\MailBox\Message;
use BetterLife\System\SystemConstant;
use BetterLife\System\Encryption;

$csrf = \BetterLife\System\CSRF::formField();
$errorMsg = "";
$errors = array();
$recipientField = "";

if(Session::checkUserSession())
    $userObj = User::GetUserFromSession();
else
    Services::RedirectHome();


if(isset($_GET["Token"])) {
    try {
        $recipientObj = User::getById(explode('-',base64_decode($_GET["Token"]))[0]);
        if(!$recipientObj->checkRole([3,4,5]) && !$userObj->checkRole([3,5]) || $recipientObj == $userObj)
            Services::RedirectHome();
    } catch (Exception $e) {
        Services::RedirectHome();
    }



    $recipientField = "<div class='form-group col-12'>
                            <h5 class=''>עבור:</h5>
                            <h6>{$recipientObj->getFullName()}</h6>
                            <input type='hidden' value='{$recipientObj->getId()}' class='custom-select form-control' name='recipient' required>
                        </div>";
} else {
    $users = BetterLife::GetDB()->where("Roles", "%3%", "LIKE")
        ->orWhere("Roles", "%4%", "LIKE")
        ->orWhere("Roles", "%5%", "LIKE")->get("users", null, "Id");
    $userOptions = "";
    foreach ($users as $user){
        $userTmp = User::getById($user["Id"]);
        $userOptions .= "<option value='{$userTmp->getId()}'>{$userTmp->getFullName()} - ";
        foreach ($userTmp->getRoles() as $role)
            $userOptions .= " {$role->getName()}, ";
        $userOptions[strlen($userOptions)-2] = " </option>";
    }
    $recipientField = "<div class='form-group col-12'>
                            <label>
                                <p class='label-txt'>למי לשלוח?</p>
                                <select class='custom-select form-control' name='recipient' required>
                                    <option value='-1' hidden selected disabled>נא לבחור מהרשימה</option>
                                    {$userOptions}
                                </select>
                                <div class='line-box'>
                                  <div class='line'></div>
                                </div>
                                <span class='text-danger'></span>
                          </label>
                        </div>";

}




if(isset($_POST["submit"])) {
    $recipientId = htmlspecialchars(trim($_POST["recipient"]));
    $subject = htmlspecialchars(trim($_POST["subject"]));
    $content = htmlspecialchars(trim($_POST["content"]));

    if (empty($recipientId))
        array_push($errors, "לא נבחר נמען");
    elseif(!User::checkIfUserExist($recipientId))
        array_push($errors, "לא קיים משתמש");

    if (empty($subject))
        array_push($errors, "לא הוזן נושא");

    if (empty($content))
        array_push($errors, "לא הוזן תוכן");

    if(empty($errors)) {
        $dateTime = new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));

        $conData = [
            "CreatorId" => $userObj->getId(),
            "RecipientId" => $recipientId,
            "Subject" => Encryption::Encrypt($subject),
            "Views" => json_encode(array($userObj->getId())),
            "CreateTime" => $dateTime->format("Y-m-d H:i:s")
        ];

        $conId = BetterLife::GetDB()->insert(Conversation::TABLE_NAME, $conData);

        $messageData = [
            "ConversationId" => $conId,
            "CreatorId" => $userObj->getId(),
            "Content" => Encryption::Encrypt($content),
            "CreateTime" => $dateTime->format("Y-m-d H:i:s")
        ];

        BetterLife::GetDB()->insert(Message::TABLE_NAME, $messageData);

        $link = "conversation.php?Con=" . base64_encode($conId . '-' . $userObj->getToken());

        Services::redirectUser($link);
    } else {
        $errorMsg = "<h4>נא לתקן את השגיאות הבאות: </h4>";
        $errorMsg .= "<ul class='list-group list-group-flush mb-3 ml-5' style='text-align: center'>";
        foreach ($errors as $error)
            $errorMsg .= "<li class='list-group-item' style='background: none; border-top: none'>$error</li>";
        $errorMsg .= "</ul>";
    }

}


$pageTemplate .= <<<PageBody
<div class="container register-form">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>שיחה חדשה</h2>
            <hr>
        </div>
    </div>
   
   
    <div class="row justify-content-center">
        <div class="col-12 col-md-6" >
            {$errorMsg}
            <form class="form-row" method="post">
                
                 {$recipientField}
                
                <div class="form-group col-12">
                    <label>
                        <p class="label-txt">נושא השיחה</p>
                        <input type="text" class="input form-control" name="subject" required>
                        <div class="line-box">
                            <div class="line"></div>
                        </div>
                        <span class="text-danger"></span>
                    </label>
                </div>
                
                <div class="form-group col-12">
                    <label>
                        <p class="label-txt mt-2">תוכן ההודעה</p>
                        <textarea type="text" style="border: 1px solid rgba(0,0,0,0.3);background-color: red" rows="8" class="input form-control" name="content" required></textarea>
                        <div class="line-box">
                            <div class="line"></div>
                        </div>
                        <span class="text-danger"></span>
                    </label>
                </div>
                
                {$csrf}
                <div class="form-group col-md-12 justify-content-center">
                    <button type="submit" name="submit" class="btn btn-block btn-secondary">שלח הודעה</button>
                </div>
  
            </form>
        </div>
     </div>
</div>

<script>
$(document).ready(function(){
  $('.input').focus(function(){
    $(this).parent().find(".label-txt").addClass('label-active');
  });

  $(".input").focusout(function(){
    if ($(this).val() == '') {
      $(this).parent().find(".label-txt").removeClass('label-active');
    };
  });
  });
</script>

<script>
    $( document ).ready(function() {
        $("form").validate({
            rules: {
                recipient: {
                    required: true
                },
                subject: {
                    required: true,
                    minlength: 2,
                    maxlength: 20
                },
                content: {
                    required: true,
                    minlength: 2,
                    maxlength: 200
                }
            },
            messages: {
                recipient: {
                    required: "חובה לציין עבור מי ההודעה"
                },
                subject: {
                    required: "חובה לציין נושא",
                    minlength: "מינימום 2 תווים",
                    maxlength: "מקסימום 20 תווים"
                },
                content: {
                    required: "חובה לכתוב תוכן",
                    minlength: "מינימום 2 תווים",
                    maxlength: "מקסימום 200 תווים"
                }
            },
            highlight: function(element) {
              $(element).removeClass('is-valid').addClass('is-invalid');
            },
            unhighlight: function(element) {
              $(element).removeClass('is-invalid').addClass('is-valid');
            },
            errorPlacement: function(error, element) {
                error.appendTo( element.parent("label").children("span"))
            }});

    });

</script>

PageBody;

echo $pageTemplate;
include "../core/templates/footer.php";