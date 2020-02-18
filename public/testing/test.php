<?php
require_once "../core/templates/header.php";

\BetterLife\System\Services::dump(\BetterLife\User\User::GetUserFromSession()->getRoles());

$pageTemplate .= <<<PageBody

PageBody;

echo $pageTemplate;
include "../core/templates/footer.php";
