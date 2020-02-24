<?php
require_once "../core/templates/header.php";

use BetterLife\System\SystemConstant;
use BetterLife\User\User;
use BetterLife\User\Session;
use BetterLife\System\Services;
use BetterLife\User\Role;
use BetterLife\Mole\RiskLevel;
use BetterLife\User\Doctor;


if(!Session::checkUserSession())
    Services::RedirectHome();


if(isset($_GET["UserId"]) && User::GetUserFromSession()->checkRole(5)){
    $userObj = User::getById($_GET["UserId"]);
    $editButton = "<a class='btn btn-secondary' href='edit-profile.php?UserId={$userObj->getId()}'>עריכה</a>";
}
else {
    $userObj = User::GetUserFromSession();
    $editButton = "<a class='btn btn-secondary' href='edit-profile.php'>עריכה</a>";
}



$roles = "";
foreach ($userObj->getRoles() as $role)
    $roles .= "<li style='list-style: none'> - {$role->getName()}</li>";

$lastMoleCheck = "";
$jsData = "";
$sysInfo = "";

if($userObj->checkRole(2) && $userObj->getMoles()) {
    $moles = count($userObj->getMoles());

    $lastMoleCheck = array_reverse($userObj->getMoles())[0]->getCreateTime()->diff(new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE)))->d;

    $pieDataTmp = array();
    foreach ($userObj->getMoles() as $mole) {
        $risk = $mole->getLastDetails()->getRiskLevel();

        if(array_key_exists($risk->getName(), $pieDataTmp))
            $pieDataTmp[$risk->getName()]++;
        else {
            $pieDataTmp[$risk->getName()] = 1;
        }
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


    $jsData = "data: {
                    labels: {$pieLabels},
                    datasets: [{
                        data: {$pieData},
                        labels: {$pieData},
                        backgroundColor: {$pieColors}
                    }]
                }";

    $sysInfo .= "<tr>
                    <td>בעל היסטוריה של סרטן העור?</td>
                    <td>{$userObj->getHistoryString()}</td>
                </tr>
                <tr>
                    <td>כמות השומות שנבדקו</td>
                    <td>{$moles}</td>
                </tr>
                <tr>
                    <td>בדיקה אחרונה</td>
                    <td>לפני {$lastMoleCheck} ימים</td>
                </tr>";

}

if($userObj->checkRole(3)) {
    $doctorObj = Doctor::getById($userObj->getId());
    $sysInfo .= "
                <tr>
                    <td>כמות השומות שנבדקו</td>
                    <td>{$doctorObj->countDiagnosis()}</td>
                </tr>";
    if($doctorObj->countDiagnosis() > 0)
        $sysInfo .= "
                <tr>
                    <td>בדיקה אחרונה</td>
                    <td>לפני {$doctorObj->lastMole()->getCreateTime()->diff(new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE)))->d} ימים</td>
                </tr>";
}




$pageTemplate .= <<<PageBody
<style>
table {
    background: #fbfbff;
    box-shadow: rgba(0,0,0,0.2) 2px 2px 6px;
    border-collapse: collapse;
    overflow: hidden;
}

tr {
    border: 1px solid rgba(0,0,0,0.3) !important;
}

tr:nth-child(even){
    background-color: #f2f2f2;
}

td {
    padding: 15px 5% !important;
}

</style>
<div class="container mt-5">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>פרופיל אישי</h2>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-12 text-left">
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
                                <td>{$userObj->getRegisterTime()->format("d/m/y h:i ")}</td>
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

            
        <div class="col-md-12 col-12 mt-3 mb-3">
            <h4>סטטיסטיקות</h4>
            <div class="row">
                <div class="col-sm-6 col-12">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
var ctx = document.getElementById('myChart');
var myChart = new Chart(ctx, {
    type: 'pie',
    {$jsData}
    
});

</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

PageBody;


echo $pageTemplate;
include "../core/templates/footer.php";
