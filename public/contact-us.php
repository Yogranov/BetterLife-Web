<?php
require_once "core/templates/header.php";

$pageBody = <<<PageBody
<div class="container">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>שמרו על קשר</h2>
            <hr>
        </div>
    </div>
    
    <div class="row mb-5">
            <div class="col-xl-6 col-12" data-aos="zoom-in-down">
                <h5 class="text-center mb-5"><i class="fas fa-file-signature" style="color: #4e4e4e;"></i> טופס פנייה</h5>
                <form  id="contact-form" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="contact-name">שם מלא</label>
                            <input id="contact-name " name="name" type="text" class="form-control" placeholder='ישראל ישראלי'>
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contact-phone">מספר טלפון</label>
                            <input id="contact-phone" name="phoneNumber" type="text" class="form-control" placeholder='0500000000'>
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="contact-email">דואר אלקטרוני</label>
                            <input id="contact-email" name="email" type="email" class="form-control" placeholder='example@example.com'>
                            <span class="text-danger"></span>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="contact-subject">נושא הפנייה</label>
                            <input id="contact-subject" name="subject" type="text" class="form-control" placeholder='נושא לדוגמה'>
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="contact-content">תוכן הפנייה</label>
                            <textarea class="form-control" name="content" type="textarea" id="contact-content" placeholder="כתוב הודעה כאן..." maxlength="140" rows="7"></textarea>
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-block btn-secondary mb-5 custom-button" style="width: 80%; margin: auto">שלח טופס</button>
                        </div>
                    </div>
                </form>
            </div>

        <div class="col-xl-6 col-12 iframe">
            <div class="row">
                <div class="col-12" style="direction: ltr">
                <h5 class="text-center mb-5">בואו למשרדינו הראשי <i class="fas fa-map-marker-alt" style="color: #4e4e4e"></i></h5>
                    <iframe class="google-maps" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d847.5950285261038!2d34.656721170761706!3d31.814639998829563!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1502a3150a99af63%3A0xd208a6dee36193b9!2z15TXnteb15zXnNeUINec157Xmdeg15TXnA!5e0!3m2!1siw!2sil!4v1579814244651!5m2!1siw!2sil"></iframe>
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

echo $pageBody;
include "core/templates/footer.php";