<?php
require_once "../core/templates/header.php";
use BetterLife\System\CSRF;
use BetterLife\BetterLife;
use BetterLife\System\Services;

if(file_exists('/home/goru/public_html/betterlife/media/moles/regular/1_2.jpg'))
    echo "ok";
else
    echo "Not ok";

$pageBody = /** @lang HTML */
    <<<PageBody



PageBody;


echo $pageBody;
include "../core/templates/footer.php";