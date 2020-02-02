<?php
require_once "core/templates/header.php";
use BetterLife\BetterLife;
use BetterLife\User\User;
use BetterLife\System\Services;


$csrf = \BetterLife\System\CSRF::formField();
$errorMsg = "";
$errors = array();




if(!isset($_GET["reset"])) {
    Services::flashUser("הלכת לאיבוד? מחזירים אותך לדף הראשי");
} else {
    $userDetailsDecode =  base64_decode($_GET["reset"]);
    $userDetails = explode("_",$userDetailsDecode);
}

$dbUser = "";
if(!empty($userDetails))
    $dbUser = BetterLife::GetDB()->where("Email", $userDetails[0])->where("RecoverToken",$userDetails[1])->getOne("users","Id");

if(empty($dbUser)) {
    Services::flashUser("משתמש לא נמצא, חוזרים לדף הראשי");
} else {
    $userObj = User::GetById($dbUser["Id"]);
}


if(isset($_POST["submit"])){

    $password = BetterLife::GetDB()->escape(htmlspecialchars(trim($_POST["password"])));
    $rePassword = BetterLife::GetDB()->escape(htmlspecialchars(trim($_POST["rePassword"])));

    if(empty($password))
        array_push($errors, "לא הוזנה סיסמה");
    elseif (Services::PasswordStrengthCheck($password))
        array_push($errors, "הוזנה סיסמה חלשה");

    if (empty($rePassword))
        array_push($errors, "לא הוזנה סיסמה חוזרת");
    elseif (Services::PasswordStrengthCheck($rePassword))
        array_push($errors, "הוזנה סיסמה חוזרת חלשה");

    if($password != $rePassword)
        array_push($errors, "הסיסמאות לא תואמות");

    if(empty($errors)) {
        BetterLife::GetDB()->where("Email", $userDetails[0])->where("RecoverToken",$userDetails[1])->update("users", ["RecoverToken" => null, "Password" => password_hash($password, PASSWORD_DEFAULT)]);
        //log
        $userId = $dbUser["Id"];
        $log = new \BetterLife\System\Logger("המשתמש $userId שינה סיסמה");
        $log->info();
        $log->writeToDb();

        Services::flashUser("סיסמתך שונתה בהצלחה! אתה מוזמן לחזור ולהנות ביחד איתנו!");
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
            <h2>שינוי סיסמה</h2>
            <hr>
        </div>
    </div>
   
   
    <div class="row justify-content-center">
        <div class="col-12 col-md-6" >
            {$errorMsg}
            <form class="form-row" method="post" id="msform">
                
                <div class="form-group col-12">
                    <label>
                        <p class="label-txt">סיסמה</p>
                        <input id="reset-password" type="password" class="input form-control" name="password" required>
                        <div class="line-box">
                            <div class="line"></div>
                        </div>
                        <span class="text-danger"></span>
                    </label>
                </div>
                
                <div class="form-group col-12">
                    <label>
                        <p class="label-txt">חזור על הסיסמה</p>
                        <input type="password" class="input form-control" name="rePassword" required>
                        <div class="line-box">
                            <div class="line"></div>
                        </div>
                        <span class="text-danger"></span>
                    </label>
                </div>
                {$csrf}
                <input type="hidden" value="{$_GET["reset"]}">
                <div class="form-group col-md-12 justify-content-center">
                    <button type="submit" name="submit" class="btn btn-block btn-secondary">שנה סיסמה</button>
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
        $.validator.addMethod("passCheck", function(value) {
           return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) && /[a-z]/.test(value) && /\d/.test(value) && /[A-Z]/.test(value);
        });
        
        $("#msform").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 8,
                    passCheck: true
                },
                rePassword: {
                    required: true,
                    minlength: 8,
                    equalTo: "#reset-password",
                    passCheck: true
                }
            },
            messages: {
                password: {
                    required: 'אנא הכנס סיסמה',
                    minlength: 'הסיסמה חייבת להכיל לפחות 8 תווים',
                    passCheck: 'הסיסמה חייבת להכיל לפחות אות קטנה, אות גדולה ומספר'
                },
                rePassword: {
                    required: 'אנא חזור על הסיסמה',
                    minlength: 'הסיסמה חייבת להכיל לפחות 8 תווים',
                    equalTo: 'סיסמאות לא תואמות',
                    passCheck: 'הסיסמה חייבת להכיל לפחות אות קטנה, אות גדולה ומספר'
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