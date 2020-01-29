<?php
require_once "core/templates/header.php";
use BetterLife\User\Login;
use BetterLife\User\Session;
use BetterLife\System\Services;

if(Session::checkUserSession())
    Services::flashUser("הינך כבר מחובר, מועבר לדף הבית...");

$errors = array();
$errorMsg = "";
$disable = "";

if(Session::checkLoginAttempts())
    if(isset($_POST['signInSubmit'])) {
        Session::incLoginAttempts();

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
                echo ($e->getMessage());
            }
        } else {
            $errorMsg = "<ul class='list-group list-group-flush mb-3 ml-5' style='text-align: center'>";
            foreach ($errors as $error)
                $errorMsg .= "<li class='list-group-item' style='background: none; border-top: none'>$error</li>";
            $errorMsg .= "</ul>";

        }
    } else
        \BetterLife\User\Session::savePreviousPage();
else {
    $errorMsg = "<div style='margin-bottom: 10px; text-align: center; font-weight: bold'>יותר מדי ניסיונות</div>";
    $disable = "disabled";
}


$pageBody = <<<PageBody
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
                    <form class="form-signin" method="post" id="login-form">
                        <div class="form-label-group">
                            <input type="email" id="inputEmail" class="form-control" placeholder="דואר אלקטרוני" name="email" {$disable} autofocus>
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

                        <button class="btn btn-lg btn-primary btn-block"  name="signInSubmit" type="submit" {$disable}>התחבר</button>
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-2">
                                <button class="btn btn-lg btn-google btn-block" type="submit" {$disable}> כניסה עם גוגל<i class="fab fa-google mr-2"></i></button>
                            </div>
                            <div class="col-12 col-md-6">
                                <button class="btn btn-lg btn-facebook btn-block" type="submit" {$disable}> כניסה עם פייסבוק<i class="fab fa-facebook-f mr-2"></i></button>
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
<script>
$(function() {
  $("#login-form").validate({
  rules: {
      email: {
          required: true,
          email: true
      },
      password: {
          required: true,
          minlength: 4
      }
  },
  messages: {
      email: {
          required: 'אנא הכנס אימייל',
          email: 'אנא הכנס כתובת תקינה'
      },
      password: {
          required:'אנא הכנס סיסמה',
          minlength: 'הסיסמה חייבת להכיל לפחות 4 תווים'
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

echo $pageBody;
include "core/templates/footer.php";

