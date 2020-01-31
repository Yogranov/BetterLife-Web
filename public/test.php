<?php
require_once "core/templates/header.php";
use BetterLife\System\CSRF;


$csrf = CSRF::getInputToken('test');


if (!empty($_GET['a'])) {
    echo '<pre>' . print_r($_POST, true) . '</pre>';
    echo 'Verification has been : ' . (CSRF::verifyToken('test', true) ? 'successful' : 'unsuccessful');
}


$pageBody = /** @lang HTML */
    <<<PageBody


<div class="container register-form">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>הרשמה</h2>
            <hr>
        </div>
    </div>
    
    
    <form action="?a=submit" method="POST">
		{$csrf}
		<input type="text" name="name" placeholder="Test Input"><br>
		<button>Submit!</button>
	</form>
  
  
</div>


PageBody;


echo $pageBody;
include "core/templates/footer.php";