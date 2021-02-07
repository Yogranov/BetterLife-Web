<?php
require_once "../core/templates/header.php";
use BetterLife\Mole\MoleDetails;
use BetterLife\User\User;
use BetterLife\System\Services;
use BetterLife\BetterLife;
use BetterLife\Mole\RiskLevel;
use BetterLife\System\SystemConstant;


BetterLife::GetPermissions(3);

$csrf = \BetterLife\System\CSRF::formField();
$linkDec = explode('_', base64_decode($_GET["mole"]));

try {
    $moleDetails = new MoleDetails($linkDec[0]);
    $patient = $moleDetails->getPatientObj();
    $userObj = User::GetUserFromSession();
} catch (\Throwable $e) {
    Services::flashUser("לא נמצאה שומה");
}

$errorMsg = "";
$errors = array();

if(isset($_POST["submit"])) {

    $riskLevel = htmlspecialchars(trim($_POST["riskLevel"]));
    $content = htmlspecialchars(trim($_POST["content"]));
    $orgImgBase64 = htmlspecialchars(trim($_POST["orgImgBase64"]));

    if (empty($riskLevel))
        array_push($errors, "לא הוזנה רמת סיכון");

    if (empty($content))
        array_push($errors, "לא הוזן איבחון");

    if(empty($errors)) {
        $dateTime = new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));

        $data = array(
            "DoctorId" => $userObj->getId(),
            "RiskLevel" => $riskLevel,
            "Diagnosis" => $content,
            "DiagnosisCreateTime" => $dateTime->format("Y-m-d H:i:s")
        );

        BetterLife::GetDB()->where(MoleDetails::TABLE_KEY_COLUMN, $moleDetails->getId())->update(MoleDetails::TABLE_NAME, $data);
        $emailContent = \BetterLife\System\EmailsConstant::Mole_checked_by_doctor;
        Services::setPlaceHolder($emailContent, "userName", $patient->getFirstName());
        $patient->sendEmail($emailContent, "עדכון");

        if($riskLevel == '2' || $riskLevel == '5') {
            $type = $riskLevel == '2' ? "benign" : "malignant";
            Services::sendPostRequest('http://frizen700.ddns.net:587/saveDataImg', array("moleImage" => $orgImgBase64, "moleType" => $type));
        }

        Services::redirectUser("moles-list.php");

    } else{
        $errorMsg = "<div class='col-12 text-center'><h4>נא לתקן את השגיאות הבאות: </h4>";
        $errorMsg .= "<ul class='list-group list-group-flush mb-3 ml-5'>";
        foreach ($errors as $error)
            $errorMsg .= "<li class='list-group-item' style='background: none; border-top: none'>$error</li>";
        $errorMsg .= "</ul></div>";
    }

}




$errorMsg = "";
$sex = $patient->getSex() ? "נקבה" : "זכר";
$haveHistory = $patient->getHaveHistory() ? "יש היסטוריה של סרטן העור" : "אין היסטוריה של סרטן העור";
$haveHistoryColor = $patient->getHaveHistory() ? "alert-danger" : "alert-success";

$messageToken = base64_encode($patient->getId() . '-' . Services::GenerateRandomString(10));
$privateMessage = "<a href='../user/new-conversation.php?Token={$messageToken}' class='btn btn-secondary float-left' target='_blank'>הודעה פרטית</a>";

$risks = RiskLevel::getAll();

$options = "";
foreach ($risks as $risk)
    $options .= "<option value='{$risk->getId()}'>{$risk->getName()}</option>";





$pageTemplate .= <<<PageBody
<div class="container mt-5">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>איבחון שומה</h2>
            <hr>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 mb-4">
            {$privateMessage}
        </div>
        <div class="col-md-4 col-12">
            <div class="card">
                <h5 class="card-header border border">פרטי המטופל</h5>
                <div class="card-body" style="padding: 0">
                    <ul style="padding: 0" class="list-group">
                        <li class="list-group-item border-top-0"><i class="fas fa-at text-muted"></i><span> תעודת זהות: </span><span class="float-left ml-4"> {$patient->getPersonId()} </span></li>
                        <li class="list-group-item"><i class="fab fa-discord text-muted"></i><span> שם פרטי: </span><span class="float-left ml-4"> {$patient->getFirstName()} </span></li>
                        <li class="list-group-item"><i class="fas fa-mobile-alt text-muted"></i><span> שם משפחה: </span><span class="float-left ml-4"> {$patient->getLastName()} </span></li>
                        <li class="list-group-item"><i class="fas fa-id-card text-muted"></i><span> מין: </span><span class="float-left ml-4"> {$sex} </span></li>
                        <li class="list-group-item"><i class="fas fa-id-card text-muted"></i><span> גיל: </span><span class="float-left ml-4"> {$patient->getAge()} </span></li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-12">
            <div class="card">
                <h5 class="card-header border">דרכי התקשרות</h5>
                <div class="card-body" style="padding: 0">
                    <ul style="padding: 0" class="list-group">
                        <li class="list-group-item"><i class="fas fa-id-card text-muted"></i><span> מספר טלפון: </span><span class="float-left ml-4"> {$patient->getPhoneNumber()} </span></li>
                        <li class="list-group-item"><i class="fas fa-id-card text-muted"></i><span> דוא"ל: </span><span class="float-left ml-4"> {$patient->getEmail()} </span></li>
                        <li class="list-group-item"><i class="fas fa-id-card text-muted"></i><span> כתובת: </span><span class="float-left ml-4"> {$patient->getAddress()->getAddress()}, {$patient->getAddress()->getCity()} </span></li>
                    </ul>

                </div>
            </div>
            
            <div class="card mt-4">
                <h5 class="card-header text-center {$haveHistoryColor}">{$haveHistory}</h5>
                <div class="card-body" style="padding: 0">
                </div>
            </div>
        </div>
        
        <div class="col-md-4 col-12">
            <div class="card">
                <h5 class="card-header border">פרטי השומה</h5>
                <div class="card-body" style="padding: 0">
                    <ul style="padding: 0" class="list-group">
                        <li class="list-group-item border-top-0"><i class="fas fa-at text-muted"></i><span> תאריך צילום: </span><span class="float-left ml-4"> {$moleDetails->getCreateTime()->format("d/m/y")} </span></li>
                        <li class="list-group-item"><i class="fab fa-discord text-muted"></i><span> מיקום: </span><span class="float-left ml-4"> {$moleDetails->getMoleLocation()} </span></li>
                        <li class="list-group-item"><i class="fas fa-mobile-alt text-muted"></i><span> דרגת סיכון משוערת: </span><span class="float-left ml-4"> {$moleDetails->getMalignantPred()} </span></li>
                        <li class="list-group-item"><i class="fas fa-id-card text-muted"></i><span> גודל: </span><span class="float-left ml-4"> {$moleDetails->getSize()} </span></li>
                        <li class="list-group-item"><i class="fas fa-id-card text-muted"></i><span> צבע: </span><span class="float-left ml-4"> {$moleDetails->getColor()} </span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row img-row">
        <div class="col-md-4 col-12">
            <h6 class="text-center">תמונת מקור</h6>
            <img id="original_image" class="img-fluid round-shadow" src="../../core/services/imageHandle.php?Method=DoctorMole&UserId={$userObj->getId()}&Token={$userObj->getToken()}&image={$moleDetails->getImgUrl()}&dir=regular">
        </div>
        <div class="col-md-4 col-12">
            <h6 class="text-center">חתימת גוונים</h6>
            <img class="img-fluid round-shadow" src="../../core/services/imageHandle.php?Method=DoctorMole&UserId={$userObj->getId()}&Token={$userObj->getToken()}&image={$moleDetails->getImgUrl()}&dir=figure">
        </div>
        <div class="col-md-4 col-12">
            <h6 class="text-center">שטח פנים</h6>
            <img class="img-fluid round-shadow" src="../../core/services/imageHandle.php?Method=DoctorMole&UserId={$userObj->getId()}&Token={$userObj->getToken()}&image={$moleDetails->getImgUrl()}&dir=surface">
        </div>
    </div>
    
    <div class="row">
        <div class="col-12" data-aos="zoom-in-down">
        {$errorMsg}
            <form method="post" enctype="multipart/form-data">
                <div class="form-row">
                
                    <div class="form-group col-12">
                        <label for="contact-content">אבחנה כללית</label>
                        <select class="custom-select" name="riskLevel" class="form-control" required>
                          <option value="-1" hidden selected disabled>נא לבחור מהרשימה</option>
                          {$options}
                        </select>
                        <span class="text-danger"></span>
                    </div>
                    
                    <div class="form-group col-md-12">
                        <label for="contact-content">אבחנה</label>
                        <textarea class="form-control" placeholder="תיאור האבחנה" rows="10" name="content" type="textarea" required></textarea>
                        <span class="text-danger"></span>
                    </div>
                    
                    <input id="orgImgBase64" name="orgImgBase64" type="hidden" value="">
                    
                    {$csrf}
                    <div class="form-group col-12">
                        <button type="submit" name="submit" class="btn btn-block btn-secondary mb-5 custom-button" style="width: 80%; margin: auto">שלח אבחנה</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
</div>

<script>
    function toDataURL(url, callback) {
        var xhr = new XMLHttpRequest();
        xhr.onload = function() {
            var reader = new FileReader();
            reader.onloadend = function() {
                callback(reader.result);
            }
            reader.readAsDataURL(xhr.response);
        };
        xhr.open('GET', url);
        xhr.responseType = 'blob';
        xhr.send();
    }
    
    window.addEventListener('load', function () {
        toDataURL('../../core/services/imageHandle.php?Method=DoctorMole&UserId={$userObj->getId()}&Token={$userObj->getToken()}&image={$moleDetails->getImgUrl()}&dir=regular', function(dataUrl) {
            $('#orgImgBase64').val(dataUrl);
        });
    });

</script>
PageBody;


echo $pageTemplate;
include "../core/templates/footer.php";
