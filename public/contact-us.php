<?php
require_once "core/templates/header.php";
use BetterLife\System\SystemConstant;
use BetterLife\System\Services;
use BetterLife\System\EmailsConstant;
use BetterLife\BetterLife;

$errors = array();
$errorMsg = "";
$csrfToken = \BetterLife\System\CSRF::formField();

if(isset($_POST['submit'])) {
    $name = BetterLife::GetDB()->escape(htmlspecialchars(trim($_POST['name'])));
    $phone = BetterLife::GetDB()->escape(htmlspecialchars(trim($_POST['phoneNumber'])));
    $email = BetterLife::GetDB()->escape(htmlspecialchars(trim($_POST['email'])));
    $subject = BetterLife::GetDB()->escape(htmlspecialchars(trim($_POST['subject'])));
    $content = BetterLife::GetDB()->escape(htmlspecialchars(trim($_POST['content'])));


    if (empty($name))
        array_push($errors, "לא הוזן שם");

    if (empty($email))
        array_push($errors, "לא הוזן אימייל");
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
        array_push($errors, "לא הוכנס אימייל תקין");

    if (empty($phone))
        array_push($errors, "לא הוזן מספר טלפון");
    elseif (Services::validatePhoneNumber($phone))
        array_push($errors, "מספר טלפון לא תקין");

    if (empty($subject))
        array_push($errors, "לא הוזן נושא");

    if (empty($content))
        array_push($errors, "לא הוזן תוכן");


    if(empty($errors)) {
        $emailSubject = "פנייה חדשה";
        $emailBody = EmailsConstant::contactUs;
        Services::setPlaceHolder($emailBody, "name", $name);
        Services::setPlaceHolder($emailBody, "phone", $phone);
        Services::setPlaceHolder($emailBody, "email", $email);
        Services::setPlaceHolder($emailBody, "subject", $subject);
        Services::setPlaceHolder($emailBody, "content", $content);
        $emailObj = BetterLife::GetEmail($emailSubject, $emailBody);
        $emailObj->addAddress(SystemConstant::MAIN_GMAIL);
        if($emailObj->send()) {
            $log = new \BetterLife\System\Logger(null, "נשלח מייל צור קשר");
            $log->info();
            $log->writeToDb();
        }
    }

    else {
        $errorMsg = "<h4 class='text-center'>נא לתקן את השגיאות הבאות: </h4>";
        $errorMsg .= "<ul class='list-group list-group-flush mb-5 ml-5' style='text-align: center'>";
        foreach ($errors as $error)
            $errorMsg .= "<li class='list-group-item' style='background: none; border-top: none'>$error</li>";
        $errorMsg .= "</ul>";
    }

}



$pageTemplate .= <<<PageBody
<div class="container">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>שמרו על קשר</h2>
            <hr>
        </div>
    </div>
    {$errorMsg}
    <div class="row mb-5">

        <div class="col-xl-6 col-12" data-aos="zoom-in-down">
            <h5 class="text-center mb-5"><i class="fas fa-file-signature" style="color: #4e4e4e;"></i> טופס פנייה</h5>
            <form  id="contact-form" method="post">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="contact-name">שם מלא</label>
                        <input id="contact-name" name="name" type="text" class="form-control" placeholder='ישראל ישראלי' required>
                        <span class="text-danger"></span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="contact-phone">מספר טלפון</label>
                        <input id="contact-phone" name="phoneNumber" type="text" class="form-control" placeholder='0500000000' required>
                        <span class="text-danger"></span>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="contact-email">דואר אלקטרוני</label>
                        <input id="contact-email" name="email" type="email" class="form-control" placeholder='example@example.com' required>
                        <span class="text-danger"></span>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="contact-subject">נושא הפנייה</label>
                        <input id="contact-subject" name="subject" type="text" class="form-control" placeholder='נושא לדוגמה' required>
                        <span class="text-danger"></span>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="contact-content">תוכן הפנייה</label>
                        <textarea class="form-control" name="content" type="textarea" id="contact-content" placeholder="כתוב הודעה כאן..." maxlength="140" rows="7" required></textarea>
                        <span class="text-danger"></span>
                    </div>
                    {$csrfToken}
                    <div class="form-group col-md-12">
                        <button type="submit" name="submit" class="btn btn-block btn-secondary mb-5 custom-button" style="width: 80%; margin: auto">שלח טופס</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-xl-6 col-12 iframe" data-aos="zoom-in-down">
            <div class="row">
                <div class="col-12" style="direction: ltr">
                <h5 class="text-center mb-5">בואו למשרדינו הראשי <i class="fas fa-map-marker-alt" style="color: #4e4e4e"></i></h5>
                    
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $("#contact-form").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                phoneNumber: {
                    required: true,
                    rangelength: [10, 10]
                },
                email: {
                    required: true,
                    email: true
                },
                subject: {
                    required: true,
                    minlength: 2
                },
                content: {
                    required: true,
                    rangelength: [2, 400]
                }
            },
            messages: {
                email: {
                    required: 'אנא הכנס אימייל',
                    email: 'אנא הכנס כתובת תקינה'
                },
                name: {
                    required:'אנא הכנס שם',
                    minlength: 'השם חייב להכיל לפחות 2 תווים'
                },
                subject: {
                    required:'אנא הכנס נושא',
                    minlength: 'הנושא חייב להכיל לפחות 2 תווים'
                },
                content: {
                    required:'אנא הכנס תוכן',
                    rangelength: 'ניתן להכניס בין 2 ל-400 אותיות'
                },
                phoneNumber: {
                    required: "אנא הכנס מספר",
                    rangelength: "יש להכניס מספר בעל 10 מספרים"
                    
                }
            },
            highlight: function(element) {
              $(element).removeClass('is-valid').addClass('is-invalid');
            },
            unhighlight: function(element) {
              $(element).removeClass('is-invalid').addClass('is-valid');
            },
            errorPlacement: function(error, element) {
                error.appendTo( element.parent("div").children("span"))
            }});
    });

</script>
PageBody;

echo $pageTemplate;
include "core/templates/footer.php";