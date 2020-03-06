<?php
require_once "core/templates/header.php";
use BetterLife\User\Doctor;
use BetterLife\System\Services;
use BetterLife\User\Session;

$doctors = Doctor::getAllDoctors();
$rows = "";
foreach ($doctors as $doctor) {
    if(Session::checkUserSession()) {
        $linkToken = base64_encode($doctor->getId() . '-' . Services::GenerateRandomString(20));
        $messageLink = "<p><a class='hover-fade' style='color: rgba(163,79,88,0.58); position: absolute; bottom: -5%' target='_blank' href='user/new-conversation.php?Token={$linkToken}'><i class='far fa-comment-dots fa-2x'></i> הודעה פרטית</a></p>";
    } else
        $messageLink = "";

    $rows .= <<<Rows
        <div class="col-md-4 col-12 text-center">
            <img class="img-fluid" src="core/services/imageHandle.php?Method=DoctorsPage&image={$doctor->getImgUrl()}">
            <div class="mt-2 text-right doctor-details">
                <h5>ד"ר {$doctor->getFullName()}</h5>
                <h6>{$doctor->getTitle()}</h6>
                <p>{$doctor->getAbout()}</p>
                {$messageLink}
            </div>
        </div>
Rows;
}


$pageTemplate .= <<<PageBody
<div class="container">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>צוות הרופאים</h2>
            <hr>
        </div>
    </div>
    
    <div class="row doctors">
        {$rows}
    </div>
</div>
PageBody;


echo $pageTemplate;
include "core/templates/footer.php";