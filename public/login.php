<?php
require_once "core/templates/header.php";
use BetterLife\User\Login;
use BetterLife\User\Session;
use BetterLife\System\Services;
use BetterLife\System\CSRF;


if(Session::checkUserSession())
    Services::flashUser("הינך כבר מחובר, מועבר לדף הבית...");

$errors = array();
$errorMsg = "";
$disable = "";
$exceptionError = "";
$userEmail = "";

$token = CSRF::formField();
$modal = '';
$qrcode = '';

if(Login::checkLoginAttempts()) {
    $qrcode = Services::GenerateRandomString(128);
    $modal = "
        <div class='modal fade' id='appLogin' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
          <div class='modal-dialog modal-lg' role='document'>
            <div class='modal-content'>
              <div class='modal-header' style='background: #f1f9ff'>
                <h5 class='modal-title' id='exampleModalLabel'>כניסה באמצעות אפליקציה</h5>
                <button type='button' class='close m-0 p-0'' style='outline: none' data-dismiss='modal' aria-label='Close'>
                  <span aria-hidden='true'>&times;</span>
                </button>
              </div>
              <div class='modal-body'>
                <div class='row justify-content-center align-items-center'>
                    <div class='col-12 col-lg-5'>
                        <h2 class='text-center'>יש לסרוק את הברקוד באמצעות האפליקציה</h2>
                    </div>
                    <div class='col-0 col-lg-2'></div>
                    <div class='col-12 col-lg-5'>
                        <div id='qrcode'>
                            <script>
                                var qrcode = new QRCode(document.getElementById('qrcode'), {
                                    text: '{$qrcode}',
                                    width: 256,
                                    height: 256,
                                    colorDark : '#000000',
                                    colorLight : '#ffffff',
                                    correctLevel : QRCode.CorrectLevel.H
                                });
                            </script>
                        </div>
                    </div>
                </div>
              </div>
              <div class='modal-footer' style='background: #f5f5f5'>
                <button type='button' class='btn btn-secondary' data-dismiss='modal'>סגירה</button>
              </div>
            </div>
          </div>
        </div>
    ";

    if(isset($_POST['userTokenInput']))
        Login::connectViaQrCode($_POST['userTokenInput']);



    if(isset($_POST['signInSubmit'])) {
        Login::incLoginAttempts();


        if(empty($errors)) {
            if (empty($_POST["email"]))
                array_push($errors, "לא הוזן אימייל");
            elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL))
                array_push($errors, "לא הוכנס אימייל תקין");
            if (empty($_POST["password"]))
                array_push($errors, "לא הוזנה סיסמה");
        } else {
            $disable = "disabled";
        }

        if(empty($errors)){
            $email = $_POST["email"];
            $password = $_POST["password"];
            $remember = (isset($_POST["remember"]) && $_POST["remember"] == "on") ? true : false;

            try {
                new Login($email, $password, $remember);
            } catch (Exception $e) {
                $exceptionError = "<h4 class='text-center mb-2'>" . $e->getMessage() . "</h4>";
                $userEmail = "value='" . $email . "'";
            }
        } else {
            $errorMsg = "<ul class='list-group list-group-flush mb-3 ml-5' style='text-align: center'>";
            foreach ($errors as $error)
                $errorMsg .= "<li class='list-group-item' style='background: none; border-top: none'>$error</li>";
            $errorMsg .= "</ul>";

        }
    } else
        \BetterLife\User\Session::savePreviousPage();
}
else {
    $errorMsg = "<div style='margin-bottom: 10px; text-align: center; font-weight: bold'>יותר מדי ניסיונות</div>";
    $disable = "disabled";
}


$pageTemplate .= <<<PageBody
<link rel="stylesheet" href="core/css/loginpage.css">
<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <a href="register.php" type="button" class="btn btn-success register-btn">להרשמה</a>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-9 col-md-8 col-lg-6 col-xl-5 mx-auto">
            <div class="card card-signin my-3">
                <div class="card-body" >
                    <h5 class="card-title text-center">כניסה למערכת</h5>
                    <img class="img-fluid mb-4" src="media/icons/user-Icon.png">
                    {$errorMsg}
                    {$exceptionError}
                    <form class="form-signin" method="post" id="login-form">
                        <div class="form-label-group">
                            <input type="email" id="inputEmail" {$userEmail} class="form-control" placeholder="דואר אלקטרוני" name="email" {$disable} autofocus>
                            <label for="inputEmail">דואר אלקטרוני</label>
                            <span class="text-danger"></span>
                        </div>

                        <div class="form-label-group">
                            <input type="password" id="inputPassword" class="form-control" placeholder="סיסמה" name="password" {$disable} >
                            <label for="inputPassword">סיסמה</label>
                            <span class="text-danger"></span>
                        </div>

                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember" {$disable}>
                            <label class="custom-control-label" for="customCheck1">זכור סיסמה</label>
                        </div>

                        {$token}
                        <button class="btn btn-lg btn-primary btn-block"  name="signInSubmit" type="submit" {$disable}>התחבר</button>
                        
                        
                        <hr class="my-4">
                    </form>
                    <form method="post" id="qrLogin">
                        <div class="row">
                            <div class="col-12">
                                <button class="btn btn-lg btn-facebook btn-block" onclick="qrCodeStore('{$qrcode}')" data-toggle="modal" data-target="#appLogin" type="button" {$disable}> כניסה באמצעות אפליקציה<i class="fas fa-qrcode mr-2"></i></button>
                                {$token}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 forgot-password">
            <a href="/forgot-password.php">שכחת סיסמה?</a>
        </div>
    </div>
</div>

{$modal}

<script>



function abortTimer() {
  clearInterval(tid);
}


$(function() {
    
    $.validator.addMethod("passCheck", function(value) {
       return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) && /[a-z]/.test(value) && /\d/.test(value) && /[A-Z]/.test(value);
    });
    
  $("#login-form").validate({
  rules: {
      email: {
          required: true,
          email: true
      },
      password: {
          required: true,
          minlength: 8,
          passCheck: true
      }
  },
  messages: {
      email: {
          required: 'אנא הכנס אימייל',
          email: 'אנא הכנס כתובת תקינה'
      },
      password: {
          required:'אנא הכנס סיסמה',
          minlength: 'הסיסמה חייבת להכיל לפחות 8 תווים',
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
      error.appendTo( element.parent("div").children("span") )
  }})
});

</script>

PageBody;

echo $pageTemplate;
include "core/templates/footer.php";

