<?php
require_once "../core/templates/header.php";
use BetterLife\Article\Article;
use BetterLife\System\SystemConstant;
use BetterLife\User\User;
use BetterLife\User\Session;
use BetterLife\System\Services;
use BetterLife\User\Role;
use BetterLife\Mole\RiskLevel;
use BetterLife\User\Doctor;
use BetterLife\BetterLife;

if(!Session::checkUserSession())
    Services::RedirectHome();


if(isset($_GET["UserId"]) && User::GetUserFromSession()->checkRole(5)){
    $userObj = User::getById($_GET["UserId"]);
    $editButton = "<a class='btn btn-secondary' href='edit-profile.php?UserId={$userObj->getId()}'>עריכה</a>";

    $adminObj = User::getById($_SESSION[SystemConstant::USER_SESSION_NAME]);
    $admin = true;
}
else {
    $userObj = User::GetUserFromSession();
    $editButton = "<a class='btn btn-secondary' href='edit-profile.php'>עריכה</a>";
    $admin = false;
}



$roles = "";
foreach ($userObj->getRoles() as $role)
    $roles .= "<li style='list-style: none'> - {$role->getName()}</li>";

$lastMoleCheck = "";
$sysInfo = "";
$pieChartPatient = "";
$pieChartPatientRow = "";

if($userObj->checkRole(2) && $userObj->getMoles()) {
    $moles = count($userObj->getMoles());

    $lastMoleCheck = array_reverse($userObj->getMoles())[0]->getCreateTime()->diff(new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE)))->d;
    $pieDataTmp = array();
    foreach ($userObj->getMoles() as $mole) {
        $risk = $mole->getLastDetails()->getRiskLevel();
        if(array_key_exists($risk->getName(), $pieDataTmp))
            $pieDataTmp[$risk->getName()]++;
        else
            $pieDataTmp[$risk->getName()] = 1;

    }

    $pieLabels = "[";
    $pieColors = "[";
    foreach ($pieDataTmp as $key => $tmp){
        $pieLabels .= "'{$key}',";

        if($key == RiskLevel::DOESNT_CHECKED)
            $pieColors .= "'rgba(133, 193, 233, 0.4)',";

        if($key == RiskLevel::NO_DANGER)
            $pieColors .= "'rgba(46, 204, 113, 0.4)',";

        if($key == RiskLevel::SUSPICIOUS)
            $pieColors .= "'rgba(175, 122, 197, 0.4)',";

        if($key == RiskLevel::DANGER)
            $pieColors .= "'rgba(236, 112, 99, 0.4)',";

        if($key == RiskLevel::IMMEDIATELY_DANGER)
            $pieColors .= "'rgba(146, 43, 33, 0.4)',";

    }
    $pieLabels[strlen($pieLabels)-1] = "]";
    $pieColors[strlen($pieColors)-1] = "]";

    $pieData = "[";
    foreach ($pieDataTmp as $tmp)
        $pieData .= "{$tmp},";
    $pieData[strlen($pieData)-1]= "]";

    $pieChartPatient = "<script>
                            new Chart($('#myChart'), {
                                type: 'pie',
                                data: {
                                    labels: {$pieLabels},
                                    datasets: [{
                                        data: {$pieData},
                                        labels: {$pieData},
                                        backgroundColor: {$pieColors}
                                    }]
                                },
                                options: {
                                    title: {
                                        display: true,
                                        text: 'סיכום איבחון רופאים'
                                    }
                                }
                            });
                            </script>";

    $pieChartPatientRow = "<div class='col-md-12 col-12 mt-3 mb-3'>
                                <h4>סטטיסטיקות</h4>
                                <div class='row'>
                                    <div class='col-2'></div>
                                    <div class='col-sm-8 col-12'>
                                        <canvas id='myChart'></canvas>
                                    </div>
                                    <div class='col-2'></div>
                                </div>
                            </div>";

    $sysInfo .= "<tr>
                    <td>בעל היסטוריה של סרטן העור?</td>
                    <td>{$userObj->getHistoryString()}</td>
                </tr>
                <tr>
                    <td>כמות השומות שנבדקו:</td>
                    <td>{$moles}</td>
                </tr>
                <tr>
                    <td>בדיקה אחרונה:</td>
                    <td>לפני {$lastMoleCheck} ימים</td>
                </tr>";

}

if($userObj->checkRole(3)) {
    $doctorObj = Doctor::getById($userObj->getId());
    $sysInfo .= "<tr>
                     <td>כמות השומות שנבדקו:</td>
                     <td>{$doctorObj->countDiagnosis()}</td>
                 </tr>";
    if($doctorObj->countDiagnosis() > 0)
        $sysInfo .= "<tr>
                         <td>:אבחון אחרונה</td>
                         <td>לפני {$doctorObj->lastMole()->getCreateTime()->diff(new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE)))->d} ימים</td>
                     </tr>";
}

if($userObj->checkRole(4)){
    $articles = count(BetterLife::GetDB()->where("Creator", $userObj->getId())->get(Article::TABLE_NAME, null, Article::TABLE_KEY_COLUMN));
    $sysInfo .= "<tr>
                     <td>כתבות:</td>
                     <td>{$articles}</td>
                 </tr>";
}

$disableUser = "";
if($admin){
    $enableOrDisable = $userObj->getEnable() ? "disable" : "enable";
    $enableOrDisableColor = $userObj->getEnable() ? "btn-danger" : "btn-success";
    $enableOrDisableText = $userObj->getEnable() ? "השבת חשבון" : "הפעל חשבון";

    $disableUser = "<a class='btn {$enableOrDisableColor}' onclick='enableDisableUser({$userObj->getId()}, \"{$enableOrDisable}\", {$adminObj->getId()}, \"{$adminObj->getToken()}\", $(this))' style='color:white; cursor: pointer'>{$enableOrDisableText}</a>";
    $sysInfo .= "<tr><td>:כניסה אחרונה</td>";
    $sysInfo .= !is_null($userObj->getLastLogin()) ? "<td>{$userObj->getLastLogin()->format("d/m/y H:i")}</td></tr>" : "<td>לא נכנס למערכת</td></tr>";
}


$pageTemplate .= <<<PageBody
<div class="container mt-5">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>פרופיל אישי</h2>
            <hr>
        </div>
    </div>

    <div class="row my-profile">
        <div class="col-12 text-left">
            {$disableUser}
            {$editButton}
        </div>
        
        <div class="col-md-5 col-12" >
            <div class="col-12">
            <h4>פרטים אישיים</h4>
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <td class="border-0"><i class="fas fa-user-alt"></i> שם פרטי:</td>
                        <td class="border-0">{$userObj->getFirstName()}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-users"></i> שם משפחה:</td>
                        <td>{$userObj->getLastName()}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-address-card"></i> תעודת זהות:</td>
                        <td>{$userObj->getPersonId()}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-venus-mars"></i> מין:</td>
                        <td>{$userObj->getSexString()}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-birthday-cake"></i> תאריך לידה</td>
                        <td>{$userObj->getBirthDate()->format("d/m/y")}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="col-2"></div>
        <div class="col-md-5 col-12">
            <div class="col-12">
                <h4>פרטי התקשרות</h4>
            </div>
            <table class="table">
                <tbody>
                    <tr>
                        <td class="border-0"><i class="fas fa-at"></i> דואר אלקטרוני:</td>
                        <td class="border-0">{$userObj->getEmail()}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-mobile-alt"></i> מספר טלפון:</td>
                        <td>{$userObj->getPhoneNumber()}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-map-marker-alt"></i> כתובת:</td>
                        <td>{$userObj->getAddress()->getAddress()}</td>
                    </tr>
                    <tr>
                        <td><i class="fas fa-city"></i> עיר:</td>
                        <td>{$userObj->getAddress()->getCity()}</td>
                    </tr>
                </tbody>
            </table>
        </div>
       
        <div class="col-12">
            <h4>מידע מערכת</h4>
            <div class="row">
                <div class="col-12">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>תאריך רישום:</td>
                                <td>{$userObj->getRegisterTime()->format("d/m/y H:i")}</td>
                            </tr>
                            <tr>
                                <td>תפקידים:</td>
                                <td>
                                    <ul class="list-group list-group-flush" style="padding: 0">
                                        {$roles}
                                    </ul>
                                </td>
                            </tr>
                            {$sysInfo}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {$pieChartPatientRow}
        

    </div>
</div>


{$pieChartPatient}


<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

PageBody;


echo $pageTemplate;
include "../core/templates/footer.php";
