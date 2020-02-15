<?php
require_once "../core/templates/header.php";
use BetterLife\User\User;
use BetterLife\BetterLife;
use BetterLife\System\SystemConstant;


BetterLife::GetPermissions(2);

$user = User::GetUserFromSession();
$moles = $user->getMoles();

$imgPath = SystemConstant::MOLE_IMAGES_PATH;

$rows = "";

if(empty($moles))
    $rows = "
        <div class='row align-items-center text-center' style='height: 50%'>
            <div class='col-12'>
                <h2 >לא נרשמו שומות לבדיקה</h2>
            </div>
        </div>
";
else
    foreach ($moles as $mole) {
        $linkEnc = base64_encode($mole->getId() . "_" . $mole->getCreateTime()->format("U"));
        $imgEnc = base64_encode($user->getId() . "-" . $mole->getLastDetails()->getImgUrl());

        if(!file_exists($imgPath . 'figure/' .  $mole->getLastDetails()->getImgUrl() . '.jpg')
        || !file_exists($imgPath . 'surface/' .  $mole->getLastDetails()->getImgUrl() . '.jpg'))
            $moreInfoButton = '<a href="#" class="mt-auto btn btn-success pt-2 disabled">ממתין לבדיקה</a>';
        else
            $moreInfoButton = "<a href='https://betterlife.845.co.il/patient/mole.php?mole={$linkEnc}' class='mt-auto btn btn-success pt-2'>פרטים נוספים</a>";

        $tmp = <<<tmp
<div class="container-fluid medical-profile-row">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-12">
                <table class="table">
                    <tbody>
                        <tr>
                            <td class="border-0">מספר:</td>
                            <td class="border-0">#{$mole->getId()}</td>
                        </tr>
                        <tr>
                            <td>מיקום:</td>
                            <td>{$mole->getLocation()}</td>
                        </tr>
                        <tr>
                            <td>דרגת סיכון:</td>
                            <td>{$mole->getLastDetails()->getRiskLevel()->getName()}</td>
                        </tr>
                        <tr>
                            <td>גודל (מ"מ):</td>
                            <td>{$mole->getLastDetails()->getSize()}</td>
                        </tr>
                        <tr>
                            <td>עדכון אחרון:</td>
                            <td>{$mole->getLastDetails()->getCreateTime()->format("d/m/y")}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-8 col-12">
                <div class="row">
                    <div class="col-md-6 col-12 text-left d-flex align-items-end flex-column">
                        <h6><span>נוצר: </span>{$mole->getCreateTime()->format("d/m/y")}</h6>
                        {$moreInfoButton}
                    </div>
                    <div class="col-md-6 col-12">
                        <img class="img-fluid round-shadow" src="imageHandle.php?image={$imgEnc}&dir=regular">
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>


tmp;
$rows .=$tmp;
}


$pageBody = <<<PageBody

<div class="container">
    <div class="row mb-2">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>פרופיל רפואי</h2>
            <hr>
            <a href="add-mole.php" class="btn btn-secondary">בדיקת שומה חדשה</a>
        </div>
    </div>
</div>


{$rows}
 
PageBody;


echo $pageBody;
include "../core/templates/footer.php";
