<?php
use BetterLife\System\Services;
use BetterLife\Mole\Mole;
use BetterLife\User\User;
use BetterLife\BetterLife;

require_once "../core/templates/header.php";

BetterLife::GetPermissions(2);

$linkDec = explode('_', base64_decode($_GET["mole"]));

try {
    $mole = Mole::getById($linkDec[0]);
    $userObj = User::GetUserFromSession();
} catch (\Throwable $e) {
    Services::flashUser("לא נמצאה שומה");
}

if(($mole->getUserId() !== $userObj->getId()) || ($mole->getCreateTime()->format("U") !== $linkDec[1]))
    Services::flashUser("לא קיימת הרשאה");


$rows = "";
foreach (array_reverse($mole->getDetails()) as $key => $detail) {

    $imgEnc = base64_encode($userObj->getId() . "-" . $detail->getImgUrl());

    if($detail->getDoctorId() != null) {
        $doctor = User::getById($detail->getDoctorId());
        $doctorFullName = 'ד"ר, ' . $doctor->getFullName();
    }
    else {
        $doctor = "";
        $doctorFullName = "";
    }

    if($detail->getDiagnosisCreateTime() != NULL)
        $diagTime = $detail->getDiagnosisCreateTime()->format("d/m/y");
    else
        $diagTime = NULL;

    $bgColor = ($key % 2) ? "row-background-gray pt-3 pb-3" : "";

    if($key == 0 && count($mole->getDetails()) > 1)
        $history = <<<history
                    <div class="history-title">
                        <div class="container">
                            <h2>היסטוריה</h2>
                        </div>
                    </div>
        history;
    else
        $history = "";

    $tmp = <<<tmp
    <div class="container-fluid mt-5 {$bgColor}">
        <div class="container">
            <div class="row">

                <div class="col-md-6 col-12">
                    <div class="row">
                        <div class="col-12">
                            <h3><i class="fas fa-info-circle" style="color: #637eb4"></i></h3>
                        </div>
                        <div class="col-12">
                            <h5 class="mole-bold">פרטים:</h5>

                            <ul class="list-group">
                                <li class="list-group-item border-0"><span>- תאריך: </span>{$detail->getCreateTime()->format("d/m/y H:m")}</li>
                                <li class="list-group-item border-0"><span>- גודל: </span> {$detail->getSize()}</li>
                                <li class="list-group-item border-0"><span>- צבע: </span>{$detail->getColor()}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="row">
                        <div class="col-12">
                            <h3><i class="fas fa-cogs" style="color: #637eb4"></i></h3>
                            <h5 class="mole-bold">ניתוח A.I:</h5>
                        </div>
                        <div class="col-12 text-center">
                            <h6><span>רמת סיכון: </span></h6>
                        </div>
                        <div class="col-12" style="direction: ltr">
                           <div class="progress">
                              <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="{$detail->getMalignantPred()}" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                           </div>
                        </div>
                        <div class="col-12 mt-4">
                            <span id="our-recommendation" style="font-weight: bold"></span>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="row">
                <div class="col-12">
                    <h5 class="mole-bold"><i class="fas fa-stethoscope"  style="color: #637eb4"></i> אבחנה:</h5>
                </div>
                                           
                <div class="col-12">
                    <p>
                        {$detail->getDiagnosis()}
                    </p>
                </div>
                
                <div class="col-12">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="mole-bold"><span>רמת סכנה: </span>{$detail->getRiskLevel()->getName()}</h6>
                        </div>
                        <div class="col-6 text-left">
                            <div style="color: #4e4e4e; font-size: 12px">{$diagTime} {$doctorFullName}</div>
                        </div> 
                    </div>
                </div>
            </div>

            <div class="row img-row">
                <div class="col-md-4 col-12">
                    <h6 class="text-center">תמונת מקור</h6>
                    <img class="img-fluid round-shadow" src="../../core/services/imageHandle.php?Method=Patient&UserId={$userObj->getId()}&Token={$userObj->getToken()}&MoleId={$detail->getImgUrl()}&Dir=regular">
                </div>
                <div class="col-md-4 col-12">
                    <h6 class="text-center">חתימת גוונים</h6>
                    <img class="img-fluid round-shadow" src="../../core/services/imageHandle.php?Method=Patient&UserId={$userObj->getId()}&Token={$userObj->getToken()}&MoleId={$detail->getImgUrl()}&Dir=figure">
                </div>
                <div class="col-md-4 col-12">
                    <h6 class="text-center">שטח פנים</h6>
                    <img class="img-fluid round-shadow" src="../../core/services/imageHandle.php?Method=Patient&UserId={$userObj->getId()}&Token={$userObj->getToken()}&MoleId={$detail->getImgUrl()}&Dir=surface">
                </div>
            </div>
        
        </div>
    </div>

    {$history}
tmp;

    $rows .= $tmp;
}

$linkEnc = base64_encode($mole->getId() . "_" . $mole->getCreateTime()->format("U"));

$pageTemplate .= /** @lang HTML */
    <<<PageBody
<div class="mole-page">
    <div class="container">
        <div class="row mb-2">
            <div class="col-12 text-center page-title" data-aos="zoom-in">
                <h2>שומה #1</h2>
                <hr>
                <h6>{$mole->getLocation()} - {$mole->getCreateTime()->format("d/m/y")}</h6>
            </div>
        </div>
        <div class="row" style="direction: ltr">
            <div class="col-2">
                <a href="new-check.php?mole={$linkEnc}" class="btn btn-secondary">בדיקה נוספת</a>
            </div>
        </div>
    </div>
    
    {$rows}
    

</div>


<script>
$(document).ready(function() {
    $('.progress-bar').each(function(i, obj) {
        predict = $(this).attr("aria-valuenow");
        $(this).text(predict + "%");
        $(this).attr("aria-valuenow", predict);
        $(this).css('width', predict + '%');
        
        if(predict < 30)
            $(this).addClass("bg-success");
        else if(predict >= 30 && predict <= 70)
            $(this).addClass("bg-warning");
        else 
            $(this).addClass("bg-danger");
    });
    
    $('.container-fluid').each(function() {
        if(predict < 20)
            $(this).find('#our-recommendation').text("הכל נראה בסדר");
        else if(predict >= 21 && predict <= 30)
            $(this).find('#our-recommendation').text("אין חשש אך מומלץ להמשיך מעקב");
        else if(predict >= 41 && predict <= 60)
            $(this).find('#our-recommendation').text("למערכת היה קשה לזהות, נא להמתין לאבחון הרופא");
        else if(predict >= 61 && predict <= 79)
            $(this).find('#our-recommendation').text("קיים חשש, מומלץ לקבוע בהקדם");
        else 
            $(this).find('#our-recommendation').text("קיים חשש מיידי, נא לפנות לרופא בהקדם");
    });
});


    
</script>
PageBody;


echo $pageTemplate;
include "../core/templates/footer.php";
