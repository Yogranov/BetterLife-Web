<?php
require_once "../core/templates/header.php";
use BetterLife\System\Services;
use BetterLife\User\Session;
use BetterLife\User\User;
use BetterLife\BetterLife;
use BetterLife\System\SystemConstant;
use BetterLife\Repositories\Address;
use BetterLife\User\Role;
use BetterLife\User\Doctor;

$csrf = \BetterLife\System\CSRF::formField();
$errorMsg = "";

if(isset($_GET["UserId"]) && User::GetUserFromSession()->checkRole(5)) {
    $userObj = User::getById($_GET["UserId"]);
    $admin = true;
}
else {
    $userObj = User::GetUserFromSession();
    $admin = false;
}
$errors = array();
if(isset($_POST['submit'])) {
    $firstName = htmlspecialchars(trim($_POST["firstName"]));
    $lastName = htmlspecialchars(trim($_POST["lastName"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $personId = htmlspecialchars(trim($_POST["personId"]));
    $phoneNumber = htmlspecialchars(trim($_POST["phoneNumber"]));
    $address = htmlspecialchars(trim($_POST["address"]));
    $city = htmlspecialchars(trim($_POST["cityId"]));
    $birthdate = htmlspecialchars(trim($_POST["birthdate"]));
    $sex = $_POST["sex"];
    $haveHistory = isset($_POST["haveHistory"]) ? true : false;
    $changePassword = isset($_POST["changePassword"]) ? true : false;

    $birthdateTmp = explode('/', $birthdate);
    $birthdate = $birthdateTmp[2] . "-";
    $birthdate .= $birthdateTmp[1] . "-";
    $birthdate .= $birthdateTmp[0];

    if (empty($firstName))
        array_push($errors, "לא הוזן שם פרטי");

    if (empty($lastName))
        array_push($errors, "לא הוזן שם משפחה");

    if (empty($email))
        array_push($errors, "לא הוזן אימייל");
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
        array_push($errors, "לא הוכנס אימייל תקין");

    if ($changePassword) {
        $oldPassword = htmlspecialchars(trim($_POST["oldPassword"]));
        $password = htmlspecialchars(trim($_POST["password"]));
        $rePassword = htmlspecialchars(trim($_POST["rePassword"]));

        if (!$admin) {
            if (empty($oldPassword))
                array_push($errors, "לא הוזנה סיסמה ישנה");
            elseif (!password_verify($oldPassword, $userObj->getPassword()))
                array_push($errors, "סיסמה ישנה לא נכונה");
        }

        if (empty($password))
            array_push($errors, "לא הוזנה סיסמה");
        elseif (Services::PasswordStrengthCheck($password))
            array_push($errors, "הוזנה סיסמה חלשה");

        if (empty($rePassword))
            array_push($errors, "לא הוזנה סיסמה חוזרת");
        elseif (Services::PasswordStrengthCheck($rePassword))
            array_push($errors, "הוזנה סיסמה חוזרת חלשה");

        if($password != $rePassword)
            array_push($errors, "הסיסמאות לא תואמות");
    }

    if($admin) {
        if (isset($_POST["doctorLicenseNumber"]) && empty($_POST["doctorLicenseNumber"]))
            array_push($errors, "רופא חייב מספר רישיון");

        if (isset($_POST["doctorTitle"]) && empty($_POST["doctorTitle"]))
            array_push($errors, "רופא חייב כותרת");

        if (isset($_POST["doctorAbout"]) && empty($_POST["doctorAbout"]))
            array_push($errors, "רופא חייב תיאור");

        if (empty($_FILES["doctorProfileImg"]["name"])) {
            if (!file_exists("/home/goru/public_html/betterlife/media/doctors/" . $userObj->getId() . ".jpg"))
                array_push($errors, "לא הוגדרה תמונת פרופיל לרופא");
        }
    }

    if (empty($personId))
        array_push($errors, "לא הוזנה תעודת זהות");
    elseif (Services::validateID($personId))
        array_push($errors, "תעודת זהות לא תקינה");

    $dbEmail = BetterLife::GetDB()->where("Email", $email)->getOne(User::TABLE_NAME);
    $dbPersonId = BetterLife::GetDB()->where("PersonId", $personId)->getOne(User::TABLE_NAME);
    if(!empty($dbEmail) && $userObj->getEmail() != $email)
        array_push($errors, "דואר אלקטרוני קיים במערכת");

    if(!empty($dbPersonId) && $userObj->getPersonId() != $personId)
        array_push($errors, "לא ניתן לעדכן תעודת זהות");

    if (empty($phoneNumber))
        array_push($errors, "לא הוזן מספר טלפון");
    elseif (!Services::validatePhoneNumber($phoneNumber))
        array_push($errors, "מספר טלפון לא תקין");

    if (empty($address))
        array_push($errors, "לא הוזנה כתובת");

    if (empty($city))
        array_push($errors, "לא הוזנה עיר");

    if (empty($birthdate))
        array_push($errors, "לא תאריך לידה");
    elseif (!checkdate($birthdateTmp[1], $birthdateTmp[0], $birthdateTmp[2]))
        array_push($errors, "תאריך לידה לא תקין");

    if($admin){
        if(!isset($_POST["role"]) || empty($_POST["role"]))
            array_push($errors, "משתמש חייב תפקיד אחד לפחות");
    }

    $data = array();
    if(empty($errors)) {
        $dateTime = new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));

        $userObj->setFirstName($firstName);
        $userObj->setLastName($lastName);
        $userObj->setSex($sex);
        $userObj->setPhoneNumber($phoneNumber);
        $userObj->setBirthDate(new \DateTime($birthdate));
        $userObj->setHaveHistory($haveHistory);
        $userObj->setEmail($email);
        $userObj->setPersonId($personId);

        $tmpAdd = new Address($address, $city);
        $userObj->setAddress($tmpAdd);

        if ($changePassword)
            $userObj->setPassword(password_hash($password, PASSWORD_DEFAULT));

        if($admin)
            $userObj->setRoles($_POST["role"]);

        if(isset($_POST["doctorLicenseNumber"])){
            $doctorData = array(
                "LicenseNumber" => $_POST["doctorLicenseNumber"],
                "Title" => $_POST["doctorTitle"],
                "About" => $_POST["doctorAbout"]
            );
            $doctorCheck = BetterLife::GetDB()->where(Doctor::TABLE_KEY_COLUMN, $userObj->getId())->getOne(Doctor::TABLE_NAME, Doctor::TABLE_KEY_COLUMN);

            if(is_null($doctorCheck)) {
                $doctorData["UserId"] = $userObj->getId();
                BetterLife::GetDB()->insert(Doctor::TABLE_NAME, $doctorData);
            } else
                BetterLife::GetDB()->where(Doctor::TABLE_KEY_COLUMN, $userObj->getId())->update(Doctor::TABLE_NAME, $doctorData);

            if(!empty($_FILES["doctorProfileImg"]["name"])) {
                $imgName = $userObj->getId() . ".jpg";
                $uploaddir = '/home/goru/public_html/betterlife/media/doctors/';
                $uploadfile = $uploaddir . $imgName;
                $path = $_FILES['doctorProfileImg']['tmp_name'];

                $img = new Imagick($path);
                $img->resizeImage(350, 350, 1,false);
                $img->writeImage($path);

                $data = file_get_contents($path);

                move_uploaded_file($_FILES['doctorProfileImg']['tmp_name'], $uploadfile);

            }
        }


        try {
            $userObj->save();

            if($admin)
                Services::redirectUser("profile.php?UserId={$userObj->getId()}");

            Services::redirectUser("profile.php");

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    } else {
        $errorMsg = "<h4>נא לתקן את השגיאות הבאות: </h4>";
        $errorMsg .= "<ul class='list-group list-group-flush mb-3 ml-5' style='text-align: center'>";
        foreach ($errors as $error)
            $errorMsg .= "<li class='list-group-item' style='background: none; border-top: none'>$error</li>";
        $errorMsg .= "</ul>";
    }

}

$adminJs = "";
$adminOldPassword = "";
if(!$admin)
    $adminOldPassword = "<div class='form-group col-12'>
                              <label>
                                  <p class='label-txt'>סיסמה נוכחית</p>
                                  <input type='password'  class='input form-control' name='oldPassword' required>
                                  <div class='line-box'>
                                  <div class='line'></div>
                                  </div>
                                  <span class='text-danger'></span>
                              </label>
                          </div>";

$adminChangeRole = "";
if($admin) {
    $rolesArr = Role::getAll();
    foreach ($userObj->getRoles() as $key => $userRole)
        unset($rolesArr[array_search($userRole, $rolesArr)]);

    $userRoles = "";
    $userHiddenRoles = "";
    foreach ($userObj->getRoles() as $role){
        $userRoles .= "<li roleId='{$role->getId()}' class=ui-state-default'>{$role->getName()}</li>";
        $userHiddenRoles .= "<input type='hidden' name='role[]' value='{$role->getId()}' />";
    }

    $otherRoles = "";
    foreach ($rolesArr as $role)
             $otherRoles .= "<li roleId='{$role->getId()}' class=ui-state-default'>{$role->getName()}</li>";


    $doctorLicenseNumber = "";
    $doctorTitle = "";
    $doctorAbout ="";
    $docTmp = BetterLife::GetDB()->where(Doctor::TABLE_KEY_COLUMN, $userObj->getId())->getOne(Doctor::TABLE_NAME);
    if(!is_null($docTmp)){
        $doctorLicenseNumber = $docTmp["LicenseNumber"];
        $doctorTitle = $docTmp["Title"];
        $doctorAbout = $docTmp["About"];
    }

    $adminChangeRole = "
        <div class='form-group col-12 role-sortable'>
                    <div class='row'>
                        <div class='col-lg-3'></div>
                        <div class='col-6 col-sm-4 col-lg-3 mx-auto text-center'>
                            <h5>תפקידי המשתמש</h5>
                            <ul id='sortable1' class='connectedSortable' style='background-color: rgba(46, 204, 113, 0.2)'>
                              {$userRoles}
                            </ul>
                        </div>
                        
                        <div class='col-6 col-sm-4 col-lg-3 mx-auto text-center'>
                            <h5>תפקידים זמינים</h5>
                            <ul id='sortable2' class='connectedSortable' style='background-color: rgba(236, 112, 99, 0.2)'>
                              {$otherRoles}
                            </ul>
                        </div>
                        <div class='col-lg-3'></div>   
                    </div>
                    <div class='row' id='doctor-details'>
                        
                        <div class='form-group col-sm-6 col-12'>
                           <label>
                             <p class='label-txt'>מספר רישיון</p>
                             <input type='text' class='input form-control' value='{$doctorLicenseNumber}' name='doctorLicenseNumber' required>
                             <div class='line-box'>
                               <div class='line'></div>
                             </div>
                             <span class='text-danger'></span>
                           </label>
                        </div>
                        
                        <div class='form-group col-sm-6 col-12'>
                           <label>
                             <p class='label-txt'>כותרת</p>
                             <input type='text' class='input form-control' value='{$doctorTitle}' name='doctorTitle' required>
                             <div class='line-box'>
                               <div class='line'></div>
                             </div>
                             <span class='text-danger'></span>
                           </label>
                        </div>
                        
                        <div class='form-group col-6'>
                           <label>
                             <p class='label-txt'>תיאור במשפט</p>
                             <input type='text' class='input form-control' value='{$doctorAbout}' name='doctorAbout' required>
                             <div class='line-box'>
                               <div class='line'></div>
                             </div>
                             <span class='text-danger'></span>
                           </label>
                        </div>
                        
                        <div class='form-group col-6'>
                           <label>
                             <p class='label-txt'>תמונת פרופיל</p>
                             <input type='file' class='input form-control' name='doctorProfileImg'>
                             <div class='line-box'>
                               <div class='line'></div>
                             </div>
                             <span class='text-danger'></span>
                           </label>
                        </div>
                        
                        
                    </div>
                    <div id='sort-results'>
                        {$userHiddenRoles}
                    </div>
                </div>";

    $adminJs = "<script>
                  $( function() {
                    $('#sortable1').sortable({
                      connectWith: '.connectedSortable',
                      cursor: 'grabbing',
                      update: function() {
                         let flag = 0;
                        $('#sort-results').empty();
                        $('#sortable1 li').each(function() {
                            $('#sort-results').append(\"<input type='hidden' name='role[]' value='\" + $(this).attr(\"roleId\") + \"' />\");
                                if($(this).attr('roleId') == 3)
                                    flag = 1;
                            });
                            if(flag)
                                $('#doctor-details').show(500);
                            else 
                                $('#doctor-details').hide(500)
                      }
                    }).disableSelection();
                 
                  
                  $('#sortable2').sortable({
                      connectWith: '.connectedSortable',
                    }).disableSelection();
                  });
                    
                  let docShow = 0;
                  $('#sortable1 li').each(function() {
                      if($(this).attr('roleId') == 3)
                            docShow = 1;
                  });
                  if($('#doctor-details').find('input').val() && docShow)
                        $('#doctor-details').show();
                  else
                      $('#doctor-details').hide();
                  
                </script>";

}



$sex = $userObj->getSex() ? "<option value='0'>זכר</option><option value='1' selected>נקבה</option>" : "<option value='0' selected>זכר</option><option value='1'>נקבה</option>";
$history = $userObj->getHaveHistory() ? "checked" : "";
$pageTemplate .=  /** @lang HTML */
    <<<PageBody

<style>
.register-form label{margin-bottom: 0}
  #sortable1, #sortable2 {
    border: 1px solid rgba(0,0,0,0.3);
    border-radius: 10px;
    width: 150px;
    min-height: 20px;
    list-style-type: none;
    margin: auto;
    padding: 3% 5%;
  }
  #sortable1 li, #sortable2 li {
    border-radius: 5px;
    border: 1px solid rgba(0,0,0,0.3);
    background: rgba(255,255,255,0.9);
    margin: 5% auto;
    padding: 5px;
    font-size: 1.2em;
    width: 120px;
    cursor: grab;
  }

</style>
<div class="container mt-5 register-form">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>עריכת פרופיל</h2>
            <hr>
        </div>
    </div>


    <div class="row justify-content-center">
        <div class="col-10">
            {$errorMsg}
            <form class="form-row" method="post" id="msform" enctype="multipart/form-data">
                
                <div class="form-group col-sm-6 col-12">
                   <label>
                     <p class="label-txt">שם פרטי</p>
                     <input type="text" class="input form-control" value="{$userObj->getFirstName()}" name="firstName" required>
                     <div class="line-box">
                       <div class="line"></div>
                     </div>
                     <span class="text-danger"></span>
                   </label>
                </div>
        
                <div class="form-group col-sm-6 col-12">
                   <label>
                     <p class="label-txt">שם משפחה</p>
                     <input type="text" class="input form-control" value="{$userObj->getLastName()}" name="lastName" required>
                     <div class="line-box">
                       <div class="line"></div>
                     </div>
                     <span class="text-danger"></span>
                   </label>
                </div>
                
                <div class="form-group col-sm-6 col-12">
                   <label>
                     <p class="label-txt">תעודת זהות</p>
                     <input type="text" class="input form-control" value="{$userObj->getPersonId()}" name="personId" required>
                     <div class="line-box">
                       <div class="line"></div>
                     </div>
                     <span class="text-danger"></span>
                   </label>
                </div>
                
                <div class="form-group col-sm-6 col-12">
                   <label>
                     <p class="label-txt">דואר אלקטרוני</p>
                     <input type="email" class="input form-control" value="{$userObj->getEmail()}" name="email" required>
                     <div class="line-box">
                       <div class="line"></div>
                     </div>
                     <span class="text-danger"></span>
                   </label>
                </div>
                
                <div class="form-group col-sm-6 col-12">
                   <label>
                     <p class="label-txt">מספר טלפון</p>
                     <input type="text" class="input form-control" value="{$userObj->getPhoneNumber()}" name="phoneNumber" required>
                     <div class="line-box">
                       <div class="line"></div>
                     </div>
                     <span class="text-danger"></span>
                   </label>
                </div>
                
                <div class="form-group col-sm-6 col-12">
                    <label>
                        <p class="label-txt">כתובת מגורים</p>
                        <input type="text" class="input form-control" value="{$userObj->getAddress()->getAddress()}" name="address" required>
                        <div class="line-box">
                          <div class="line"></div>
                        </div>
                        <span class="text-danger"></span>
                  </label>
                </div>
        
                <div class="form-group col-sm-6 col-12 ">
                    <label>
                        <p class="label-txt">עיר</p>
                        <input id="cities" type="text" class="input form-control" value="{$userObj->getAddress()->getCity()}" name="city" required>
                        <input type="hidden"name="cityId" value="{$userObj->getAddress()->getCityId()}">
                        <div class="line-box">
                          <div class="line"></div>
                        </div>
                        <span class="text-danger"></span>
                  </label>
                </div>
                
                <div class="form-group col-sm-6 col-12">
                    <label>
                        <p class="label-txt">תאריך לידה</p>
                        <input id="datepicker" type="text" class="input form-control" value="{$userObj->getBirthDate()->format('d/m/Y')}" name="birthdate" required>
                        <div class="line-box">
                          <div class="line"></div>
                        </div>
                        <span class="text-danger"></span>
                  </label>
                </div>
                
                <div class="form-group col-sm-12 col-12">
                    <label>
                        <p class="label-txt">מין</p>
                        <select id="register-sex" class="custom-select form-control" name="sex" required>
                            {$sex}
                        </select>
                        <div class="line-box">
                          <div class="line"></div>
                        </div>
                        <span class="text-danger"></span>
                  </label>
                </div>
                
                <div class="custom-control custom-checkbox col-12">
                    <input type="checkbox" {$history} class="custom-control-input" id="haveHistoryCheckBox" name="haveHistory">
                    <label class="custom-control-label" for="haveHistoryCheckBox">האם הינך או אחד מקרובי משפחתך חלה פעם בסרטן העור?</label>
                    <span class="text-danger"></span>
                </div>
                
                <div class="custom-control custom-checkbox col-12">
                    <input type="checkbox" class="custom-control-input" id="changePassword" name="changePassword">
                    <label class="custom-control-label" for="changePassword">שינוי סיסמה</label>
                    <span class="text-danger"></span>
                </div>
                
                {$adminOldPassword}
                
                <div class="form-group col-6">
                    <label>
                        <p class="label-txt">סיסמה חדשה</p>
                        <input type="password" id="register-password" class="input form-control" name="password" required>
                        <div class="line-box">
                        <div class="line"></div>
                        </div>
                        <span class="text-danger"></span>
                    </label>
                </div>
                    
                    
                <div class="form-group col-6">
                    <label>
                        <p class="label-txt">חזור על הסיסמה</p>
                        <input type="password" class="input form-control" name="rePassword" required>
                        <div class="line-box">
                            <div class="line"></div>
                        </div>
                        <span class="text-danger"></span>
                    </label>
                </div>
                
                
                {$adminChangeRole}
                    
                
                {$csrf}
                <div class="form-group col-md-12 justify-content-center">
                    <button type="submit" name="submit" class="btn btn-block btn-secondary">עדכן פרטים</button>
                </div>
                
            </form>
        </div>
     </div>

</div>


{$adminJs}

<script>
    $( document ).ready(function() {
        $('form input:password').parent().parent().hide();
        
        $('#changePassword').click(function() {
            if( $('#changePassword:checkbox:checked').length > 0)
                $('form input:password').parent().parent().show(500);
            else
                $('form input:password').parent().parent().hide(500);
        });
        
    });
</script>

<script>
    $( document ).ready(function() {
        $('form input:password').parent().parent().hide();
        
        $('#changePassword').click(function() {
            if( $('#changePassword:checkbox:checked').length > 0)
                $('form input:password').parent().parent().show(500);
            else
                $('form input:password').parent().parent().hide(500);
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
$( function() {
    $("#cities").autocomplete({
      source: function(request, response ) {
        $.ajax( {
          type: "POST",
          url: "../../core/services/getCities.php",
          dataType: "json",
          data: {
            term: request.term
          },
          success: function( data ) {
            response( data );
          }
        } );
      },
      minLength: 2,
      select: function( event, ui ) {
        event.preventDefault();
        $("#cities").val(ui.item.label);
        $("input[name=cityId]").val(ui.item.value);
      }
    } );
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
        
        $("form").validate({
            rules: {
                firstName: {
                    required: true,
                    minlength: 2
                },
                lastName: {
                    required: true,
                    minlength: 2
                },
                oldPassword: {
                    required: true,
                    minlength: 8,
                    passCheck: true
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
                    rangelength: [5, 9],
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
                doctorLicenseNumber: {
                    required: true,
                    number: true,
                    minlength: 4
                },
                doctorTitle: {
                    required: true,
                    minlength: 2
                },
                doctorAbout: {
                    required: true,
                    minlength: 2
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
                oldPassword: {
                    required: 'אנא הכנס סיסמה',
                    minlength: 'הסיסמה חייבת להכיל לפחות 8 תווים',
                    passCheck: 'הסיסמה חייבת להכיל לפחות אות קטנה, אות גדולה ומספר'
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
                    rangelength: 'המספר חייב להיות בין 5 ל-9 ספרות',
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
                doctorLicenseNumber: {
                    required: "חובה לציין מספר רישיון",
                    number: "רישיון מכיל ספרוות בלבד",
                    minlength: "רישיון חייב להכיל לפחות 4 ספרות"
                },
                doctorTitle: {
                    required: "חובה לציין כותרת",
                    minlength: "כותרת חייבת להכיל 2 אותיות לפחות"
                },
                doctorAbout: {
                    required: "חובה לציין תיאור",
                    minlength: "התיאור חייב להכיל 2 אותיות לפחות"
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


echo $pageTemplate;
include "../core/templates/footer.php";
