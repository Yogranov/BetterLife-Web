<?php
require_once "../core/templates/header.php";
use BetterLife\Mole\MoleDetails;
use BetterLife\System\Services;
use BetterLife\User\User;
use BetterLife\System\SystemConstant;

$imgPath = SystemConstant::MOLE_IMAGES_PATH;
$moleDetails = MoleDetails::getAllUncheckMole("MalignantPred", "ASC");
$userObj = User::GetUserFromSession();


$moleRows = "";
if(empty($moleDetails))
    $moleRows = "
        <div class='row align-items-center text-center' style='height: 50%'>
            <div class='col-12'>
                <h2>לא נרשמו שומות לבדיקה</h2>
            </div>
        </div>
";
else
    foreach (array_reverse($moleDetails) as $moleDetail) {
        $linkEnc = base64_encode($moleDetail->getId() . "_" . $moleDetail->getCreateTime()->format("U"));
        if(!file_exists($imgPath . 'figure/' .  $moleDetail->getImgUrl() . '.jpg')
            || !file_exists($imgPath . 'surface/' .  $moleDetail->getImgUrl() . '.jpg'))
            $moreInfoButton = '<a href="#" class="mt-auto btn btn-success pt-2 disabled">בבדיקת מערכת</a>';
        else
            $moreInfoButton = "<a href='https://betterlife.845.co.il/doctors/check-mole.php?mole={$linkEnc}' class='mt-auto btn btn-danger pt-2'>בדיקה</a>";



    $tmp = <<<tmp
<div class="container-fluid medical-profile-row">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-12">
                <table class="table">
                    <tbody>
                        <tr>
                            <td class="border-0">אחוזי סיכון:</td>
                            <td class="border-0">{$moleDetail->getMalignantPred()}%</td>
                        </tr>
                        <tr>
                            <td class="border-0">מטופל:</td>
                            <td class="border-0">{$moleDetail->getPatientObj()->getFullName()}</td>
                        </tr>
                        <tr>
                            <td class="border-0">גיל:</td>
                            <td class="border-0">{$moleDetail->getPatientObj()->getAge()}</td>
                        </tr>
                        <tr>
                            <td>מיקום:</td>
                            <td>{$moleDetail->getMoleLocation()}</td>
                        </tr>
                        <tr>
                            <td>גודל:</td>
                            <td>{$moleDetail->getSize()} מ"מ</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-8 col-12">
                <div class="row">
                    <div class="col-md-6 col-12 text-left d-flex align-items-end flex-column">
                        <h6><span>נוצר: </span>{$moleDetail->getCreateTime()->format("d/m/y")}</h6>
                        {$moreInfoButton}
                    </div>
                    <div class="col-md-6 col-12">
                        <img class="img-fluid round-shadow" src="../../core/services/imageHandle.php?Method=DoctorMole&UserId={$userObj->getId()}&Token={$userObj->getToken()}&image={$moleDetail->getImgUrl()}&dir=regular">
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
tmp;

$moleRows .= $tmp;
}

$pageTemplate .= <<<PageBody
<style>


</style>
<div class="container mt-5">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>שומות לטיפול</h2>
            <hr>
        </div>
    </div>

    {$moleRows}


</div>
PageBody;


echo $pageTemplate;
include "../core/templates/footer.php";
