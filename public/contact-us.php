<?php
require_once "core/templates/header.php";
?>
<div class="container">
    <div class="row mb-5">
        <div class="col-sm-5 col-md-4 col-lg-3 col-xs-12 text-center" data-aos="zoom-in">
            <h2 class="page-title">שמרו על קשר</h2>
        </div>
    </div>

    <div class="row mb-5">
            <div class="col-xl-6 col-12" data-aos="zoom-in-down">
                <form>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="contact-name">שם מלא</label>
                            <input id=""contact-name" type="text" class="form-control" placeholder='ישראל ישראלי'>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="contact-phone">מספר טלפון</label>
                            <input id="contact-phone" type="text" class="form-control" placeholder='0500000000'>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="contact-email">דואר אלקטרוני</label>
                            <input id="contact-email" type="email" class="form-control" placeholder='example@example.com'>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="contact-subject">נושא הפנייה</label>
                            <input id="contact-subject" type="text" class="form-control" placeholder='נושא לדוגמה'>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="contact-content">תוכן הפנייה</label>
                            <textarea class="form-control" type="textarea" id="contact-content" placeholder="Message" maxlength="140" rows="7"></textarea>
                        </div>
                        <button type="submit" class="btn btn-block btn-primary">שלח טופס</button>
                    </div>
                </form>
            </div>

        <div class="col-xl-6 col-12 iframe">
            <div class="row">
                <div class="col-12" style="direction: ltr">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d847.5950285261038!2d34.656721170761706!3d31.814639998829563!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1502a3150a99af63%3A0xd208a6dee36193b9!2z15TXnteb15zXnNeUINec157Xmdeg15TXnA!5e0!3m2!1siw!2sil!4v1579814244651!5m2!1siw!2sil" width="500" height="530" allowfullscreen=""></iframe>
                </div>
            </div>

        </div>
    </div>
</div>




<?php
include "core/templates/footer.php";
?>