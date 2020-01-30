<?php
require_once "core/templates/header.php";
/*
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
                            <input type="checkbox" class="custom-control-input" id="termOfUseCheckBox" name="termOfUse" required>
                            <label class="custom-control-label" for="termOfUseCheckBox">אני מאשר שקראתי את <a target="_blank" href="terms-of-use.php">תנאי השימוש</a> ואני מאשר אותם</label>
                            <span class="text-danger"><div></div></span>
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
                termOfUse:{
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
                    passCheck: "הסיסמה חייבת להכיל לפחות אות קטנה, אות גדולה ומספר"
                },
                rePassword: {
                    required: "אנא חזור על הסיסמה",
                    minlength: 'הסיסמה חייבת להכיל לפחות 8 תווים',
                    equalTo: "סיסמאות לא תואמות",
                    passCheck: "הסיסמה חייבת להכיל לפחות אות קטנה, אות גדולה ומספר"
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
                    required: "חייב לקרוא את תנאי השימוש"
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
*/


$pageBody = /** @lang HTML */
    <<<PageBody


<div class="container register-form">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>הרשמה</h2>
            <hr>
        </div>
    </div>


<div class="container-fluid" id="grad1">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 text-center">
            <div class="card">
                <div class="row">
                    <div class="col-12" id="register-form">
                        <form id="msform">
                        
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li class="active" id="account"><strong>פרטי כניסה</strong></li>
                                <li id="personal"><strong>מידע אישי</strong></li>
                                <li id="confirm"><strong>מידע נוסף</strong></li>
                                <li id="confirm"><strong>אישור</strong></li>
                            </ul> <!-- fieldsets -->
                            
                           <div class="form-row">
                                <fieldset>
                                    <div class="form-card">
                                        <h2 class="fs-title">פרטי כניסה</h2>
                                        
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
                                        
                                        <div class="form-group col-12">
                                           <label>
                                             <p class="label-txt">סיסמה</p>
                                             <input type="password" id="register-password" class="input form-control" name="password" required>
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
                                        
                                    </div> 
                                    <input type="button" name="next" class="next action-button" value="המשך" />
                                </fieldset>
                                
                                <fieldset>
                                    <div class="form-card">
                                    
                                        <div class="form-group col-12">
                                           <label>
                                             <p class="label-txt">שם פרטי</p>
                                             <input type="text" class="input form-control" name="firstName" required>
                                             <div class="line-box">
                                               <div class="line"></div>
                                             </div>
                                             <span class="text-danger"></span>
                                           </label>
                                        </div>
                                
                                        <div class="form-group col-12">
                                           <label>
                                             <p class="label-txt">שם משפחה</p>
                                             <input type="text" class="input form-control" name="lastName" required>
                                             <div class="line-box">
                                               <div class="line"></div>
                                             </div>
                                             <span class="text-danger"></span>
                                           </label>
                                        </div>
                                        
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
                                             <p class="label-txt">מספר טלפון</p>
                                             <input type="text" class="input form-control" name="phoneNumber" required>
                                             <div class="line-box">
                                               <div class="line"></div>
                                             </div>
                                             <span class="text-danger"></span>
                                           </label>
                                        </div>
       
                                    </div>
                                    <input type="button" name="previous" class="previous action-button-previous" value="חזור" />
                                    <input type="button" name="next" class="next action-button" value="המשך" />
                                </fieldset>
                                
                            
                                <fieldset>
                                    <div class="form-card">
                                    
                                        <div class="form-group col-12">
                                            <label>
                                                <p class="label-txt">כתובת מגורים</p>
                                                <input type="text" class="input form-control" name="address" required>
                                                <div class="line-box">
                                                  <div class="line"></div>
                                                </div>
                                                <span class="text-danger"></span>
                                          </label>
                                        </div>
                                
                                        <div class="form-group col-12 ">
                                            <label>
                                                <p class="label-txt">עיר</p>
                                                <input type="text" class="input form-control" name="city" required>
                                                <div class="line-box">
                                                  <div class="line"></div>
                                                </div>
                                                <span class="text-danger"></span>
                                          </label>
                                        </div>
                                        
                                        <div class="form-group col-12">
                                            <label>
                                                <p class="label-txt">תאריך לידה</p>
                                                <input id="datepicker" type="text" class="input form-control" name="birthdate" required>
                                                <div class="line-box">
                                                  <div class="line"></div>
                                                </div>
                                                <span class="text-danger"></span>
                                          </label>
                                        </div>
                                        
                                        <div class="form-group col-12">
                                            <label>
                                                <p class="label-txt">מין</p>
                                                <select id="register-sex" required class="custom-select form-control" name="sex">
                                                    <option value="-1" hidden selected disabled>נא לבחור מהרשימה</option>
                                                    <option value="0">זכר</option>
                                                    <option value="1">נקבה</option>
                                                </select>
                                                <div class="line-box">
                                                  <div class="line"></div>
                                                </div>
                                                <span class="text-danger"></span>
                                          </label>
                                        </div>
                                        
                                        <div class="custom-control custom-checkbox col-12">
                                            <input type="checkbox" class="custom-control-input" id="haveHistoryCheckBox" name="haveHistory">
                                            <label class="custom-control-label" for="haveHistoryCheckBox">האם הינך או אחד מקרובי משפחתך חלה פעם בסרטן העור?</label>
                                            <span class="text-danger"></span>
                                        </div>
    
       
                                    </div>
                                    <input type="button" name="previous" class="previous action-button-previous" value="חזור" />
                                    <input type="button" name="next" class="next action-button" value="המשך" />
                                </fieldset>
                                
                            
                            
                                <fieldset>
                                    <div class="form-card">
                                        <h2 class="fs-title">סיכום</h2>
                                         <p>שם פרטי: <span id="register-summery-firstName"></span></p>
                                         <p>שם משפחה: <span id="register-summery-lastName"></span></p>
                                         <p>מספר טלפון: <span id="register-summery-phoneNumber"></span></p>
                                         <p>דוא"ל: <span id="register-summery-email"></span></p>
                                         <p>שם כתובת: <span id="register-summery-address"></span></p>
                                         <p>שם העיר: <span id="register-summery-city"></span></p>
                                         <p>תאריך לידה: <span id="register-summery-birthdate"></span></p>
                                         <p>מין: <span id="register-summery-sex"></span></p>
                                         <p><b><span id="register-summery-history"></span></b></p>
                                         <br>
                                         <div class="custom-control custom-checkbox col-md-12 mb-4 mt-2">
                                             <input type="checkbox" class="custom-control-input" id="termOfUseCheckBox" name="termOfUse" required>
                                             <label class="custom-control-label" for="termOfUseCheckBox">אני מאשר שקראתי את <a target="_blank" href="terms-of-use.php">תנאי השימוש</a> ואני מאשר אותם</label>
                                             <span class="text-danger"><div></div></span>
                                         </div>
                                    </div>
                                    <input type="button" name="previous" class="previous action-button-previous" value="חזור" />
                                    <input type="submit" name="next" class="action-button" value="שלח" />
                                </fieldset>
                                
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

</div>


<script>
$(document).ready(function(){
    
    var current_fs, next_fs, previous_fs; //fieldsets
    var opacity;
    
    $(".next").click(function(){
        $('#msform').validate();
        if (!$('#msform').valid()) {
            return false;
        }
        
        $("#register-summery-firstName").text($("input[name=firstName]").val());
        $("#register-summery-lastName").text($("input[name=lastName]").val());
        $("#register-summery-email").text($("input[name=email]").val());
        $("#register-summery-phoneNumber").text($("input[name=phoneNumber]").val());
        $("#register-summery-address").text($("input[name=address]").val());
        $("#register-summery-city").text($("input[name=city]").val());
        $("#register-summery-birthdate").text($("input[name=birthdate]").val());
        
        if($("select[name=sex]").val() == 0)
            $("#register-summery-sex").text("זכר");
        else if($("select[name=sex]").val() == 1)
            $("#register-summery-sex").text("נקבה");
        else 
            $("#register-summery-sex").html("<b>לא הוזן!</b>");
        
        if($("#haveHistoryCheckBox").is(':checked'))
            $("#register-summery-history").text("יש לי או לבן משפחתי עבר של סרטן העור");
        else 
            $("#register-summery-history").text("");

        
        current_fs = $(this).parent();
        next_fs = $(this).parent().next();
        
        //Add Class Active
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");
        
        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now) {
                // for making fielset appear animation
                opacity = 1 - now;
                
                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                next_fs.css({'opacity': opacity});
            },
            duration: 400
        });
    });
    
    $(".previous").click(function(){
    
        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();
        
        //Remove class active
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");
        
        //show the previous fieldset
        previous_fs.show();
        
        //hide the current fieldset with style
        current_fs.animate({opacity: 0}, {
            step: function(now) {
                // for making fielset appear animation
                opacity = 1 - now;
                
                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                previous_fs.css({'opacity': opacity});
            },
            duration: 400
        });
    });
    
    
    $(".submit").click(function(){
        return false;
    })

});
</script>


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
    $( function() {
        $("#datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        maxDate: "-18Y",
        yearRange: "-100:-18"
        });
        $.datepicker.setDefaults($.datepicker.regional['he']); 
        
        

    });
  </script>

<script>
    $( document ).ready(function() {
        
        $.validator.addMethod("israeliPhoneCheck", function(value) {
          return /^05\d([-]{0,1})\d{7}$/.test(value);
        });
        
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
        
        
        $.validator.addMethod("passCheck", function(value) {
           return /^[A-Za-z0-9\d=!\-@._*]*$/.test(value) && /[a-z]/.test(value) && /\d/.test(value) && /[A-Z]/.test(value);
        });
        
        $("#msform").validate({
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
                    number: true,
                    israeliPhoneCheck: true
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
                    number: true,
                    idCheck: true
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
                termOfUse:{
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
                    number: 'מספרים בלבד',
                    israeliPhoneCheck: 'יש להזין מספר תקין'
                },
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
                },
                personId: {
                    required: 'אנא הכנס תעודת זהות',
                    minlength: 'המספר חייב להכיל לפחות 8 ספרות',
                    number: 'מספרים בלבד',
                    idCheck: 'תעודת זהות לא תקינה'
                },sex: {
                    required: 'חובה לבחור מין'
                },address: {
                    required: 'אנא הזן כתובת',
                    minlength: 'הכתובת חייבת להכיל לפחות 2 תווים'
                },city: {
                    required: 'אנא הזן עיר',
                    minlength: 'השם חייב להכיל לפחות 2 תווים'
                },
                termOfUse: {
                    required: 'חייב לקרוא את תנאי השימוש'
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