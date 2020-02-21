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
$haveHistory = $patient->getHaveHistory() ? "בעל היסטוריה של סרטן העור" : "";

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
        <div class="col-12">
            <h4>פרטי המטופל</h4>
        </div>
        <div class="col-12 text-center">
            <div class="row">
                <div class="col-4">
                    <span>תעודת זהות:</span>{$patient->getPersonId()}
                </div>
                <div class="col-4">
                    <span>שם פרטי: </span> {$patient->getFirstName()}
                </div>
                <div class="col-4">
                    <span>שם משפחה: </span> {$patient->getLastName()}
                </div>
                <div class="col-4">
                    <span>מין: </span> {$sex}
                </div>
                <div class="col-4">
                    <span>גיל: </span> {$patient->getAge()}
                </div>
                <div class="col-4">
                    <span>טלפון: </span> {$patient->getPhoneNumber()}
                </div>
                <div class="col-4">
                    <span>כתובת: </span> {$patient->getAddress()->getAddress()}, {$patient->getAddress()->getCity()}
                </div>
                <div class="col-4">
                    <span>{$haveHistory}<strong></strong></span>
                </div>
            </div>
           
        </div>
        
    </div>
    
    
    <div class="row">
        <div class="col-12">
            <h4>פרטי השומה</h4>
        </div>
        <div class="col-12 text-center">
            <div class="row">
                <div class="col-4">
                    <span>תאריך צילום:</span> {$moleDetails->getCreateTime()->format("d/m/y")}
                </div>
                <div class="col-4">
                    <span>מיקום:</span> {$moleDetails->getMoleLocation()}
                </div>
                <div class="col-4">
                    <span>דרגת סיכון משוערת:</span> {$moleDetails->getMalignantPred()}%
                </div>
                <div class="col-4">
                    <span>גודל:</span> {$moleDetails->getSize()}
                </div>
                <div class="col-4">
                    <span>צבע:</span> {$moleDetails->getColor()}
                </div>
            </div>
        </div>       
    </div>
    
    <div class="row img-row">
        <div class="col-md-4 col-12">
            <h6 class="text-center">תמונת מקור</h6>
            <img class="img-fluid round-shadow" src="../../core/services/imageHandle.php?Method=DoctorMole&UserId={$userObj->getId()}&Token={$userObj->getToken()}&image={$moleDetails->getImgUrl()}&dir=regular">
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
                    {$csrf}
                    <div class="form-group col-12">
                        <button type="submit" name="submit" class="btn btn-block btn-secondary mb-5 custom-button" style="width: 80%; margin: auto">שלח אבחנה</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
</div>
PageBody;


echo $pageTemplate;
include "../core/templates/footer.php";
