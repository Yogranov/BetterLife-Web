<?php
require_once "core/templates/header.php";

$pageBody = <<<PageBody
<div class="container">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>הרשמה</h2>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-12" data-aos="zoom-in-down">
                <form  id="register-form">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="register-firstName">שם משפחה</label>
                            <input id="register-firstName " name="firstName" type="text" class="form-control" placeholder='ישראל'>
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="register-lastName">שם משפחה</label>
                            <input id="register-lastName " name="lastName" type="text" class="form-control" placeholder='ישראלי'>
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="register-personId">תעודת זהות</label>
                            <input id="register-personId " name="personId" type="text" class="form-control" placeholder='306129965'>
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="register-phone">מספר טלפון</label>
                            <input id="register-phone" name="phoneNumber" type="text" class="form-control" placeholder='0500000000'>
                            <span class="text-danger"></span>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="register-email">דואר אלקטרוני</label>
                            <input id="register-email" name="email" type="email" class="form-control" placeholder='example@example.com'>
                            <span class="text-danger"></span>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="register-password">סיסמה</label>
                            <input id="register-password" name="password" type="password" class="form-control" placeholder='********'>
                            <span class="text-danger"></span>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="register-repassword">חזור על הסיסמה</label>
                            <input id="register-repassword" name="rePassword" type="password" class="form-control" placeholder='********'>
                            <span class="text-danger"></span>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="register-sex">מין</label>
                            <select id="register-sex" class="custom-select" name="sex">
                              <option value="0">זכר</option>
                              <option value="1">נקבה</option>
                            </select>
                            <span class="text-danger"></span>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="register-birthDate">תאריך לידה</label>
                            <input id="register-birthDate" name="birthdate" type="date" class="form-control" placeholder='01/01/1990'>
                            <span class="text-danger"></span>
                        </div>
                        
                        <div class="custom-control custom-checkbox col-md-12 mb-3 mt-2">
                            <input type="checkbox" class="custom-control-input" id="haveHistoryCheckBox" name="haveHistory">
                            <label class="custom-control-label" for="haveHistoryCheckBox">האם הינך או אחד מקורבי משפחתך חלה פעם בסרטן העור?</label>
                            <span class="text-danger"></span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="register-address">כתובת מגורים</label>
                            <input id="register-address" name="address" type="text" class="form-control" placeholder='ויצמן 3'>
                            <span class="text-danger"></span>
                        </div>
                        
                        <div class="form-group col-md-6">
                            <label for="register-city">עיר</label>
                            <input id="register-city" name="city" type="text" class="form-control" placeholder='אשדוד'>
                            <span class="text-danger"></span>
                        </div>
                        
                        
                        
                        <div class="custom-control custom-checkbox col-md-12 mb-4 mt-2">
                            <input type="checkbox" class="custom-control-input" id="termOfUseCheckBox" name="termOfUse">
                            <label class="custom-control-label" for="termOfUseCheckBox">אני מאשר שקראתי את <a target="_blank" href="terms-of-use.php">תנאי השימוש</a> ואני מאשר אותם</label>
                            <span class="text-danger"></span>
                        </div>

                        <button type="submit" class="btn btn-block btn-primary mb-5">שלח טופס</button>
                    </div>
                </form>
            </div>
    </div>
</div>

<script>
    $( document ).ready(function() {
        $.validator.addMethod("passCheck", function(value) {
           return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) && /[a-z]/.test(value) && /\d/.test(value) && /[A-Z]/.test(value)
        });
        $("#register-form").validate({
            rules: {
                firstName: {
                    required: true,
                    minlength: 2
                },
                lastName: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
                phoneNumber: {
                    required: true,
                    rangelength: [10, 10],
                    number: true
                },
                password: {
                    required: true,
                    minlength: 8,
                    passCheck: true
                },
                rePassword: {
                    required: true,
                    minlength: 8,
                    equalTo: "#register-password",
                    passCheck: true
                },
                personId: {
                    required: true,
                    minlength: 8,
                    number: true
                },
                sex: {
                    required: true
                },
                address: {
                    required: true,
                    minlength: 2
                },
                city: {
                    required: true,
                    minlength: 2
                },
                termOfUse: {
                    required: true
                }
            },
            messages: {
                firstName: {
                    required: 'אנא הכנס שם פרטי',
                    minlength: 'חייב להכיל לפחות 2 תווים'
                },
                lastName: {
                    required:'אנא הכנס שם משפחה',
                    minlength: 'חייב להכיל לפחות 2 תווים'
                },
                email: {
                    required:'אנא הכנס דואר אלקטרוני',
                    email: 'אנא הזן כתובת תקינה'
                },
                phoneNumber: {
                    required:'אנא הכנס תוכן',
                    rangelength: 'יש להכניס מספר בעל 10 מספרים',
                    number: 'מספרים בלבד'
                },
                password: {
                    required: "אנא הכנס סיסמה",
                    minlength: 'הסיסמה חייבת להכיל לפחות 8 תווים',
                    pwcheck: "הסיסמה חייבת להכיל לפחות אות קטנה, אות גדולה ומספר"
                },
                rePassword: {
                    required: "אנא חזור על הסיסמה",
                    minlength: 'הסיסמה חייבת להכיל לפחות 8 תווים',
                    equalTo: "סיסמאות לא תואמות",
                    pwcheck: "הסיסמה חייבת להכיל לפחות אות קטנה, אות גדולה ומספר"
                    
                },
                personId: {
                    required: "אנא הכנס תעודת זהות",
                    minlength: 'המספר חייב להכיל לפחות 8 ספרות',
                    number: "מספרים בלבד"
                    
                },sex: {
                    required: "חובה לבחור מין"
                    
                },address: {
                    required: "אנא הזן כתובת",
                    minlength: 'הכתובת חייבת להכיל לפחות 2 תווים'
                    
                },city: {
                    required: "אנא הזן עיר",
                    minlength: 'השם חייב להכיל לפחות 2 תווים'
                    
                },
                termOfUse: {
                    required: "חובה לקרוא את תנאי השימוש"
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