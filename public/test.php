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

$pageBody = /** @lang HTML */<<<PageBody
<style>

#msform {
    
    text-align: center;
    position: relative;
    margin-top: 20px
}

#msform fieldset .form-card {
    background: white;
    border: 0 none;
    border-radius: 0px;
    box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
    padding: 20px 40px 30px 40px;
    box-sizing: border-box;
    width: 94%;
    margin: 0 3% 20px 3%;
    position: relative
}

#msform fieldset {
    background: white;
    border: 0 none;
    border-radius: 0.5rem;
    box-sizing: border-box;
    width: 100%;
    margin: 0;
    padding-bottom: 20px;
    position: relative
}

#msform fieldset:not(:first-of-type) {
    display: none
}

#msform fieldset .form-card {
    text-align: left;
    color: #9E9E9E
}

#msform input,
#msform textarea {
    padding: 0px 8px 4px 8px;
    border: none;
    border-bottom: 1px solid #ccc;
    border-radius: 0px;
    margin-bottom: 25px;
    margin-top: 2px;
    width: 100%;
    box-sizing: border-box;
    font-family: montserrat;
    color: #2C3E50;
    font-size: 16px;
    letter-spacing: 1px
}

#msform input:focus,
#msform textarea:focus {
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    box-shadow: none !important;
    border: none;
    font-weight: bold;
    border-bottom: 2px solid skyblue;
    outline-width: 0
}

#msform .action-button {
    width: 100px;
    background: skyblue;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px
}

#msform .action-button:hover,
#msform .action-button:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue
}

#msform .action-button-previous {
    width: 100px;
    background: #616161;
    font-weight: bold;
    color: white;
    border: 0 none;
    border-radius: 0px;
    cursor: pointer;
    padding: 10px 5px;
    margin: 10px 5px
}

#msform .action-button-previous:hover,
#msform .action-button-previous:focus {
    box-shadow: 0 0 0 2px white, 0 0 0 3px #616161
}

select.list-dt {
    border: none;
    outline: 0;
    border-bottom: 1px solid #ccc;
    padding: 2px 5px 3px 5px;
    margin: 2px
}

select.list-dt:focus {
    border-bottom: 2px solid skyblue
}

.card {
    z-index: 0;
    border: none;
    border-radius: 0.5rem;
    position: relative
}

.fs-title {
    font-size: 25px;
    color: #2C3E50;
    margin-bottom: 10px;
    font-weight: bold;
    text-align: left
}

#progressbar {
    margin-bottom: 30px;
    overflow: hidden;
    color: lightgrey
}

#progressbar .active {
    color: #000000
}

#progressbar li {
    list-style-type: none;
    font-size: 12px;
    width: 25%;
    float: left;
    position: relative
}

#progressbar #account:before {
    font-family: FontAwesome;
    content: "\f023"
}

#progressbar #personal:before {
    font-family: FontAwesome;
    content: "\f007"
}

#progressbar #payment:before {
    font-family: FontAwesome;
    content: "\f09d"
}

#progressbar #confirm:before {
    font-family: FontAwesome;
    content: "\f00c"
}

#progressbar li:before {
    width: 50px;
    height: 50px;
    line-height: 45px;
    display: block;
    font-size: 18px;
    color: #ffffff;
    background: lightgray;
    border-radius: 50%;
    margin: 0 auto 10px auto;
    padding: 2px
}

#progressbar li:after {
    content: '';
    width: 100%;
    height: 2px;
    background: lightgray;
    position: absolute;
    left: 0;
    top: 25px;
    z-index: -1
}

#progressbar li.active:before,
#progressbar li.active:after {
    background: skyblue
}

.radio-group {
    position: relative;
    margin-bottom: 25px
}

.radio {
    display: inline-block;
    width: 204;
    height: 104;
    border-radius: 0;
    background: lightblue;
    box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
    box-sizing: border-box;
    cursor: pointer;
    margin: 8px 2px
}

.radio:hover {
    box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.3)
}

.radio.selected {
    box-shadow: 1px 1px 2px 2px rgba(0, 0, 0, 0.1)
}

.fit-image {
    width: 100%;
    object-fit: cover
}
</style>
<div class="container register-form">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>הרשמה</h2>
            <hr>
        </div>
    </div>
</div>

<div class="container-fluid" id="grad1">
    <div class="row justify-content-center mt-0">
        <div class="col-11 col-sm-9 col-md-7 col-lg-6 text-center p-0 mt-3 mb-2">
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form id="msform">
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li class="active" id="account"><strong>פרטי כניסה</strong></li>
                                <li id="personal"><strong>פרטיים אישיים</strong></li>
                                <li id="payment"><strong>מידע רפואי</strong></li>
                                <li id="confirm"><strong>אישור</strong></li>
                            </ul> <!-- fieldsets -->
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title">Account Information</h2> <input type="email" name="email" placeholder="Email Id" /> <input type="text" name="uname" placeholder="UserName" /> <input type="password" name="pwd" placeholder="Password" /> <input type="password" name="cpwd" placeholder="Confirm Password" />
                                </div> <input type="button" name="next" class="next action-button" value="Next Step" />
                            </fieldset>
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title">Personal Information</h2> <input type="text" name="fname" placeholder="First Name" /> <input type="text" name="lname" placeholder="Last Name" /> <input type="text" name="phno" placeholder="Contact No." /> <input type="text" name="phno_2" placeholder="Alternate Contact No." />
                                </div> <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> <input type="button" name="next" class="next action-button" value="Next Step" />
                            </fieldset>
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title">Payment Information</h2>
                                    <div class="radio-group">
                                        <div class='radio' data-value="credit"><img src="https://i.imgur.com/XzOzVHZ.jpg" width="200px" height="100px"></div>
                                        <div class='radio' data-value="paypal"><img src="https://i.imgur.com/jXjwZlj.jpg" width="200px" height="100px"></div> <br>
                                    </div> <label class="pay">Card Holder Name*</label> <input type="text" name="holdername" placeholder="" />
                                    <div class="row">
                                        <div class="col-9"> <label class="pay">Card Number*</label> <input type="text" name="cardno" placeholder="" /> </div>
                                        <div class="col-3"> <label class="pay">CVC*</label> <input type="password" name="cvcpwd" placeholder="***" /> </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-3"> <label class="pay">Expiry Date*</label> </div>
                                        <div class="col-9"> <select class="list-dt" id="month" name="expmonth">
                                                <option selected>Month</option>
                                                <option>January</option>
                                                <option>February</option>
                                                <option>March</option>
                                                <option>April</option>
                                                <option>May</option>
                                                <option>June</option>
                                                <option>July</option>
                                                <option>August</option>
                                                <option>September</option>
                                                <option>October</option>
                                                <option>November</option>
                                                <option>December</option>
                                            </select> <select class="list-dt" id="year" name="expyear">
                                                <option selected>Year</option>
                                            </select> </div>
                                    </div>
                                </div> <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> <input type="button" name="make_payment" class="next action-button" value="Confirm" />
                            </fieldset>
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title text-center">Success !</h2> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-3"> <img src="https://img.icons8.com/color/96/000000/ok--v2.png" class="fit-image"> </div>
                                    </div> <br><br>
                                    <div class="row justify-content-center">
                                        <div class="col-7 text-center">
                                            <h5>You Have Successfully Signed Up</h5>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
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
duration: 600
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
duration: 600
});
});

$('.radio-group .radio').click(function(){
$(this).parent().find('.radio').removeClass('selected');
$(this).addClass('selected');
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
        
        $.validator.addMethod("idCheck", function(value) {
            var count = 0;
            var id = new String(value);
            for (i=0; i<8; i++) {
                    x = (((i%2)+1)*id.charAt(i));
                    if (x > 9) {
                        x =x.toString();
                        x=parseInt(x.charAt(0))+parseInt(x.charAt(1))
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
                    number: "מספרים בלבד",
                    idCheck: "תעודת זהות לא תקינה"
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
                error.appendTo( element.parent("label").children("span"))
            }});
    });

</script>


PageBody;


echo $pageBody;
include "core/templates/footer.php";