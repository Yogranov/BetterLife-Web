<?php
require_once "core/templates/header.php";
use BetterLife\System\Services;
use BetterLife\System\SystemConstant;

if(empty($_SESSION["Flash_Message"]))
    Services::RedirectHome();

$flashText = $_SESSION[SystemConstant::FLASH_MESSAGE];


$pageBody = <<<PageBody
<div class="container mt-5">

    <div class="row mt-5">
       <div class="col-12 text-center mt-5"><h2>{$flashText}</h2></div>
    </div>
        
</div>
<script>
    window.setTimeout(function(){
        window.location.href = "index.php";
    }, 5000);
</script>
PageBody;

unset($_SESSION["FlashText"]);

echo $pageBody;
