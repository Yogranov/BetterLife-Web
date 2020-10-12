<?php
require_once '/home/goru/public_html/betterlife/vendor/autoload.php';
use BetterLife\BetterLife;
use BetterLife\System\Services;
use BetterLife\System\SystemConstant;


$token = SystemConstant::PYTHON_SERVER_TOKEN;

if(isset($_POST["switch"]) && $_POST['Token'] == $token){
    $switch = $_POST["switch"];

    if($switch === "Predict") {
        $pred = $_POST["pred"];
        $moleId = $_POST["moleId"];
        $moleDetailsId = $_POST["moleDetailsId"];
        $pred = json_decode($pred);

        $benignPred = $pred[0][0];
        $benignPred = substr($benignPred, 0, 4);

        $malignantPred = $pred[0][1];
        $malignantPred = substr($malignantPred, 0, 4);


        BetterLife::GetDB()->where("MoleId", $moleId)->where("ImgUrl", $moleId . "_" . $moleDetailsId)->update("moleDetails", ["BenignPred" => $benignPred, "MalignantPred" => $malignantPred], 1);

        /* should be more effective - consider to user subqueries instead of build those objects */
        $moleObj = \BetterLife\Mole\Mole::getById($moleId);
        $userObj = \BetterLife\User\User::getById($moleObj->getUserId());
        $emailContent = \BetterLife\System\EmailsConstant::Mole_checked_by_ai;
        Services::setPlaceHolder($emailContent, "userName", $userObj->getFirstName());
        $userObj->sendEmail($emailContent,"עדכון");
        return 0;
    }

    if($switch === "Figure") {
        $name = $_POST["name"];
        $output_file = "/home/goru/public_html/betterlife/media/moles/figure/" . $name . ".jpg";
        $ifp = fopen( $output_file, 'wb' );

        fwrite( $ifp, base64_decode($_POST["image"]));

        fclose( $ifp );

        return $output_file;
    }

    if($switch === "Surface") {
        $name = $_POST["name"];
        $output_file = "/home/goru/public_html/betterlife/media/moles/surface/" . $name . ".jpg";
        $ifp = fopen( $output_file, 'wb' );

        fwrite( $ifp, base64_decode($_POST["image"]));

        fclose( $ifp );

        return $output_file;
    }

    if($switch === "Test") {
        echo "php test started! \n";


        echo "php test ended!\n";
    }


}