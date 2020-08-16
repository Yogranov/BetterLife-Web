<?php
require_once "../core/templates/header.php";
use BetterLife\User\Role;
use BetterLife\System\Services;
use BetterLife\BetterLife;
use BetterLife\User\User;

$csrf = \BetterLife\System\CSRF::formField();
$roles = Role::getAll();

$rolesField = "";
foreach ($roles as $role)
    $rolesField .= "<option value='{$role->getId()}'>{$role->getName()}</option>";

$firstName = isset($_GET["firstName"]) ? htmlspecialchars(trim($_GET["firstName"])) : "";
$lastName = isset($_GET["lastName"]) ? htmlspecialchars(trim($_GET["lastName"])) : "";
$email = isset($_GET["email"]) ? htmlspecialchars(trim($_GET["email"])) : "";
$personId = isset($_GET["personId"]) ? htmlspecialchars(trim($_GET["personId"])) : "";
$phoneNumber = isset($_GET["phoneNumber"]) ? htmlspecialchars(trim($_GET["phoneNumber"])) : "";
$city = isset($_GET["cityId"]) ?  $_GET["cityId"] : "";
$cityName = isset($_GET["city"]) ?  $_GET["city"] : "";
$sex = isset($_GET["sex"]) ? $_GET["sex"] : "";
$role = isset($_GET["role"]) ? $_GET["role"] : "";


$errorMsg = "";
$resultsTable = "";
$errors = array();
if(isset($_GET["submit"])) {
    if(strlen($firstName) > 1 || strlen($lastName) > 1 || strlen($email) > 1 || strlen($personId) > 1 || strlen($phoneNumber) > 1 || $city != "" || $sex != "" || $role != "") {
        $data = BetterLife::GetDB()->where("FirstName", "%" . $firstName . "%", 'like')
                                    ->Where("LastName", "%" . $lastName . "%", 'like')
                                    ->Where("Email", "%" . $email . "%", 'like')
                                    ->Where("PersonId", "%" . $personId . "%", 'like')
                                    ->Where("PhoneNumber", "%" . $phoneNumber . "%", 'like')
                                    ->Where("City", "%" . $city . "%", 'like')
                                    ->Where("Sex", "%" . $sex . "%", 'like')
                                    ->Where("Roles", "%" . $role . "%", 'like')
                                    ->get(User::TABLE_NAME, null, "Id");
        if(!empty($data)) {
            $tableRow = "";
            foreach ($data as $user) {
                $userRowObj = User::getById($user["Id"]);
                $tableRow .= "
                <tr onclick=\"document.location = '../../user/profile.php?UserId={$userRowObj->getId()}';\" style='cursor: pointer'>
                                <td>{$userRowObj->getId()}</td>
                                <td>{$userRowObj->getFirstName()}</td>
                                <td>{$userRowObj->getLastName()}</td>
                                <td>{$userRowObj->getPhoneNumber()}</td>
                                <td>";
                foreach ($userRowObj->getRoles() as $userRole)
                    $tableRow .= "{$userRole->getName()}, ";

                $tableRow[strlen($tableRow)-2] = " ";
                $tableRow .="</td></tr>";
            }

            $resultsTable = <<<resultTable
    <div class="row">
        <div class="col-12">
            <div class="row rimon-table">
                <div class="col-12">
                    <h3>תוצאות החיפוש</h3>
                    <table class="table">
                        <thead>
                          <tr>
                            <th>סידורי</th>
                            <th>שם פרטי</th>
                            <th>שם משפחה</th>
                            <th>פלאפון</th>
                            <th>תפקידים</th>
                          </tr>
                        </thead>
                        <tbody>
                            {$tableRow}
                        </tbody>
                      </table>
                </div>
            </div>
        
        
        </div>
    </div>
resultTable;
        } else
            $errorMsg = "<div class='text-center' data-aos='zoom-in'><h4>לא נמצאה רשומה מתאימה</h4></div>";
    } else
        $errorMsg = "<div class='text-center'  data-aos='zoom-in'><h4>נא להזין לפחות שדה אחד</h4></div>";

}


$pageTemplate .= <<<PageBody
<div class="container mt-5">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>חיפוש משתמשים</h2>
            <hr>
        </div>
    </div>
    {$errorMsg}

    <div class="col-12" data-aos="zoom-in-down">
        <form  id="form-form" method="GET">
            <div class="form-row">
                
                <div class="col-12 mt-5">
                    <h4>מידע אישי</h4>
                </div>
                <div class="col-12 mb-3">
                    <hr>
                </div>
                
                <div class="form-group col-12 col-md-6">
                    <label for="form-firstName">שם פרטי</label>
                    <input id="form-firstName" value="{$firstName}" name="firstName" type="text" class="form-control">
                    <span class="text-danger"></span>
                </div>
                
                <div class="form-group col-12 col-md-6">
                    <label for="form-lastName">שם משפחה</label>
                    <input id="form-lastName" value="{$lastName}" name="lastName" type="text" class="form-control">
                    <span class="text-danger"></span>
                </div>
                
                <div class="form-group col-12 col-md-6">
                    <label for="form-phone">מספר טלפון</label>
                    <input id="form-phone" name="phoneNumber" value="{$phoneNumber}" type="text" class="form-control">
                    <span class="text-danger"></span>
                </div>
                
                <div class="form-group col-12 col-md-6">
                    <label for="form-personId">תעודת זהות</label>
                    <input id="form-personId" name="personId" value="{$personId}" type="text" class="form-control">
                    <span class="text-danger"></span>
                </div>
                
                
                
                <div class="col-12 mt-5">
                    <h4>דרכי התקשרות</h4>
                </div>
                <div class="col-12 mb-3">
                    <hr>
                </div>
                
                <div class="form-group col-12 col-md-6">
                    <label for="form-email">דואר אלקטרוני</label>
                    <input id="form-email" name="email" value="{$email}" type="email" class="form-control">
                    <span class="text-danger"></span>
                </div>
                
                <div class="form-group col-12 col-md-6">
                    <label for="form-city">עיר</label>
                    <input id="form-city" type="text" value="{$cityName}" class="input form-control" name="city">
                    <input type="hidden" value="{$city}" name="cityId">
                    <div class="line-box">
                      <div class="line"></div>
                    </div>
                    <span class="text-danger"></span>
                </div>
                
                <div class="form-group col-12 col-md-6">
                    <label for="form-role">תפקיד</label>
                    <select id="form-role" class="custom-select form-control" name="role">
                        <option value="-1" hidden selected disabled>נא לבחור מהרשימה</option>
                        {$rolesField}
                    </select>
                    <span class="text-danger"></span>
                </div>
                
                <div class="form-group col-12 col-md-6">
                    <label for="form-sex">מין</label>
                    <select id="form-sex" class="custom-select form-control" name="sex">
                        <option value="-1" hidden selected disabled>נא לבחור מהרשימה</option>
                        <option value="0">זכר</option>
                        <option value="1">נקבה</option>
                    </select>
                    <span class="text-danger"></span>
                </div>
                
                <div class="form-group col-md-12 mt-5">
                    <button type="submit" name="submit" class="btn btn-block btn-secondary mb-5 custom-button" style="width: 80%; margin: auto">חיפוש</button>
                </div>
                
            </div>
        </form>
    </div>

    {$resultsTable}

</div>

<script>
$( function() {
    $("#form-city").autocomplete({
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
        $("#form-city").val(ui.item.label);
        $("input[name=cityId]").val(ui.item.value);
      }
    } );
 });
</script>
PageBody;


echo $pageTemplate;
include "../core/templates/footer.php";
