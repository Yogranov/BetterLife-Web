<?php
require_once "core/templates/header.php";
use BetterLife\BetterLife;
use BetterLife\User\User;
use BetterLife\System\Services;

$csrf = \BetterLife\System\CSRF::formField();


$errorMsg = "";
$errors = array();
if(isset($_POST["submit"])){

    $email = htmlspecialchars(trim($_POST["email"]));
    $personId = htmlspecialchars(trim($_POST["personId"]));

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
    if(empty($dbEmail) || empty($dbPersonId))
        array_push($errors, "המשתמש לא קיים במערכת");


    if(empty($errors)) {
        $checkEmail = BetterLife::GetDB()->where("Email", $email)->where("PersonId", $personId)->getOne("users","Id");
        if(!empty($checkEmail)) {
            $userObj = User::GetById($checkEmail["Id"]);

            //log
            $log = new \BetterLife\System\Logger("נשלחה הודעת איפוס סיסמה לאימייל {$userObj->getEmail()}");
            $log->info();
            $log->writeToDb();

            $userObj->sendResetPasswordEmail();
        } else {
            $errorMsg = "לא נמצא משתמש התואם את הפרטים";

        }

    } else {
        $errorMsg = "<div class='col-12 text-center'><h4>נא לתקן את השגיאות הבאות: </h4>";
        $errorMsg .= "<ul class='list-group list-group-flush mb-3 ml-5'>";
        foreach ($errors as $error)
            $errorMsg .= "<li class='list-group-item' style='background: none; border-top: none'>$error</li>";
        $errorMsg .= "</ul></div>";
    }
}



$pageBody = <<<PageBody
<div class="container register-form">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>איפוס סיסמה</h2>
            <hr>
        </div>
    </div>
   
   
    <div class="row justify-content-center">
        <div class="col-12 col-md-6" >
            {$errorMsg}

            <form class="form-row" method="post" id="msform">
                
                <div class="form-group col-12">
                    <label>
                        <p class="label-txt">תעודת זהות</p>
                        <input type="text" class="input form-control" name="personId" required>
                        <div class="line-box">
                            <div class="line"></div>
                        </div>
                        <span class="text-danger"></span>
                    </label>
                </div>
                
                <div class="form-group col-12">
                    <label>
                        <p class="label-txt">דואר אלקטרוני</p>
                        <input type="email" class="input form-control" name="email" required>
                        <div class="line-box">
                            <div class="line"></div>
                        </div>
                        <span class="text-danger"></span>
                    </label>
                </div>
                {$csrf}
                <div class="form-group col-md-12 justify-content-center">
                    <button type="submit" name="submit" class="btn btn-block btn-secondary">אפס סיסמה</button>
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
        

        
        $.validator.addMethod("idCheck", function(value) {
            var count = 0;
            var id = new String(value);
            for (i=0; i<8; i++) {
                    x = (((i%2)+1)*id.charAt(i));
                    if (x > 9) {
                        x =x.toString();
                        x=parseInt(x.charAt(0))+parseInt(x.charAt(1));
                    }
                count += x;
             }
            
            if ((count+parseInt(id.charAt(8)))%10 == 0) {
                return true;
            } else {
                return false;
            }
        });
        
        $("#msform").validate({
            rules: {
                email: {
                    required: true,
                    email: true
                },
                personId: {
                    required: true,
                    rangelength: [5, 9],
                    number: true,
                    idCheck: true
                }
            },
            messages: {
                email: {
                    required:'אנא הכנס דואר אלקטרוני',
                    email: 'אנא הזן כתובת תקינה'
                },
                personId: {
                    required: 'אנא הכנס תעודת זהות',
                    rangelength: 'המספר חייב להיות בין 5 ל-9 ספרות',
                    number: 'מספרים בלבד',
                    idCheck: 'תעודת זהות לא תקינה'
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

echo $pageBody;
include "core/templates/footer.php";