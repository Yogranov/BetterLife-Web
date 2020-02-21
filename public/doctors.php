<?php
require_once "core/templates/header.php";
use BetterLife\User\Doctor;

$doctors = Doctor::getAllDoctors();
$rows = "";
foreach ($doctors as $doctor)
    $rows .= <<<Rows
        <div class="col-md-4 col-12 text-center" data-aos="zoom-in-up">
            <img class="img-fluid" src="core/services/imageHandle.php?Method=DoctorsPage&image={$doctor->getImgUrl()}">
            <div class="mt-2 text-right doctor-details">
                <h5>ד"ר {$doctor->getFullName()}</h5>
                <h6>{$doctor->getTitle()}</h6>
                <p>{$doctor->getAbout()}</p>
            </div>
        </div>
Rows;

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