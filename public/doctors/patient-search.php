<?php
require_once "../core/templates/header.php";
use BetterLife\System\Services;
use BetterLife\BetterLife;
use BetterLife\User\User;

$csrf = \BetterLife\System\CSRF::formField();


$firstName = isset($_GET["firstName"]) ? htmlspecialchars(trim($_GET["firstName"])) : "";
$lastName = isset($_GET["lastName"]) ? htmlspecialchars(trim($_GET["lastName"])) : "";
$personId = isset($_GET["personId"]) ? htmlspecialchars(trim($_GET["personId"])) : "";
$phoneNumber = isset($_GET["phoneNumber"]) ? htmlspecialchars(trim($_GET["phoneNumber"])) : "";


$errorMsg = "";
$resultsTable = "";
$errors = array();

if(isset($_GET["submit"])) {
    if(strlen($firstName) > 1 || strlen($lastName) > 1 || strlen($personId) > 1 || strlen($phoneNumber) > 1) {
        $data = BetterLife::GetDB()->where("FirstName", "%" . $firstName . "%", 'like')
            ->Where("LastName", "%" . $lastName . "%", 'like')
            ->Where("PersonId", "%" . '308551514' . "%", 'like')
            ->Where("PhoneNumber", "%" . $phoneNumber . "%", 'like')
            ->Where("Roles", "%2%", 'like')
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

        }
    }





}


$pageTemplate .= <<<PageBody
<div class="container mt-5">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>חיפוש מטופלים</h2>
            <hr>
        </div>
    </div>

    <div class="col-12" data-aos="zoom-in-down">
        <form  id="form-form" method="GET">
            <div class="form-row">
            
                <div class="form-group col-md-6">
                    <label for="form-firstName">שם פרטי</label>
                    <input id="form-firstName" value="{$firstName}" name="firstName" type="text" class="form-control">
                    <span class="text-danger"></span>
                </div>
                
                <div class="form-group col-md-6">
                    <label for="form-lastName">שם משפחה</label>
                    <input id="form-lastName" value="{$lastName}" name="lastName" type="text" class="form-control">
                    <span class="text-danger"></span>
                </div>
                
                <div class="form-group col-md-6">
                    <label for="form-phone">מספר טלפון</label>
                    <input id="form-phone" name="phoneNumber" value="{$phoneNumber}" type="text" class="form-control">
                    <span class="text-danger"></span>
                </div>
                
                <div class="form-group col-md-6">
                    <label for="form-personId">תעודת זהות</label>
                    <input id="form-personId" name="personId" value="{$personId}" type="text" class="form-control">
                    <span class="text-danger"></span>
                </div>

                <div class="form-group col-md-12">
                    <button type="submit" name="submit" class="btn btn-block btn-secondary mb-5 custom-button" style="width: 80%; margin: auto">שלח טופס</button>
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
