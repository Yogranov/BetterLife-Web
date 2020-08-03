<?php

use BetterLife\BetterLife;
use BetterLife\User\User;

require_once "../core/templates/header.php";

$logRows = "";
$logDB = BetterLife::GetDB()->orderBy('Id', 'DESC')->get("logs");
foreach ($logDB as $key => $log) {
    if(is_null($log["UserId"])) {
        $user = "מערכת";
    } else {
        $userDb = BetterLife::GetDB()->where('Id', $log["UserId"])->getOne(User::TABLE_NAME);
        $user = $userDb['FirstName'] . ' ' . $userDb['LastName'];
    }


    $logTime = new \DateTime($log["Timestamp"]);
    $logRows .= "<tr>
                    <td>
                        {$key}
                    </td>
                    <td>
                        {$user}
                    </td>
                    <td>
                        {$log["Status"]}
                    </td>
                    <td>
                        {$log["Log"]}
                    </td>
                    <td>
                        {$logTime->format("d/m/y H:i")}
                    </td>
                </tr>";
}


$pageTemplate .= <<<PageBody
<div class="container mt-5">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>לוג</h2>
            <hr>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <table id="log-table" class='table table-striped table-bordered'>
                <thead>
                  <tr>
                    <th>מספר לוג</th>
                    <th>משתמש</th>
                    <th>סטאטוס</th>
                    <th>לוג</th>
                    <th>חותמת זמן</th>
                  </tr>
                </thead>
                <tbody>
                    {$logRows}
                </tbody>
            </table>
        </div>
    </div>



</div>
PageBody;


echo $pageTemplate;
include "../core/templates/footer.php";
