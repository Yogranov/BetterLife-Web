<?php
require_once "core/templates/navbar.php";

use BetterLife\System\Services;
use BetterLife\User\Login;

if(!empty($_POST)) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $remember = (isset($_POST["remember"]) && $_POST["remember"] == "on") ? true : false;

    try {
        new Login($email, $password, $remember);
    } catch (Exception $e) {
        echo ($e->getMessage());
    }
}


?>
<link rel="stylesheet" href="core/css/loginpage.css">

<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <button type="button" class="btn btn-success register-btn">להרשמה</button>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-9 col-md-8 col-lg-6 col-xl-5 mx-auto">
            <div class="card card-signin my-3">
                <div class="card-body" >
                    <h5 class="card-title text-center">כניסה למערכת</h5>
                    <img class="img-fluid mb-4" src="media/icons/user-Icon.png">
                    <form class="form-signin" method="post">
                        <div class="form-label-group">
                            <input type="email" id="inputEmail" class="form-control" placeholder="דואר אלקטרוני" name="email" required autofocus>
                            <label for="inputEmail">דואר אלקטרוני</label>
                        </div>

                        <div class="form-label-group">
                            <input type="password" id="inputPassword" class="form-control" placeholder="סיסמה" name="password" required>
                            <label for="inputPassword">סיסמה</label>
                        </div>

                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember">
                            <label class="custom-control-label" for="customCheck1">זכור סיסמה</label>
                        </div>

                        <button class="btn btn-lg btn-primary btn-block" type="submit">התחבר</button>
                        <hr class="my-4">
                        <div class="row">
                            <div class="col-12 col-md-6 mb-2">
                                <button class="btn btn-lg btn-google btn-block" type="submit"> כניסה עם גוגל<i class="fab fa-google mr-2"></i></button>
                            </div>
                            <div class="col-12 col-md-6">
                                <button class="btn btn-lg btn-facebook btn-block" type="submit"> כניסה עם פייסבוק<i class="fab fa-facebook-f mr-2"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 forgot-password">
            <h5>שכחת סיסמה?</h5>
        </div>
    </div>
</div>


<?php
include "core/templates/footer.php";
?>
