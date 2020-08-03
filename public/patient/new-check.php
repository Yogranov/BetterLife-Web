<?php
require_once "../core/templates/header.php";
use BetterLife\System\Services;
use BetterLife\System\SystemConstant;
use BetterLife\BetterLife;
use BetterLife\System\CSRF;
use BetterLife\User\User;
use BetterLife\Mole\Mole;

$csrf = CSRF::formField();

$linkDec = explode('_', base64_decode($_GET["mole"]));

try {
    $mole = Mole::getById($linkDec[0]);
    $userObj = User::GetUserFromSession();
} catch (\Throwable $e) {
    Services::flashUser("לא נמצאה שומה");
}

if(($mole->getUserId() !== $userObj->getId()) || ($mole->getCreateTime()->format("U") !== $linkDec[1]))
    Services::flashUser("לא קיימת הרשאה");


$errorMsg = "";
$errors = array();

if(isset($_POST["submit"])) {
    $color = htmlspecialchars(trim($_POST["color"]));
    $size = htmlspecialchars(trim($_POST["size"]));

    if (empty($color))
        array_push($errors, "לא הוזן צבע");

    if (empty($size))
        array_push($errors, "לא הוזן גודל");

    if (!isset($_FILES["image"]))
        array_push($errors, "לא הוזנה תמונה");


    if($_FILES['image']['type'] != "image/jpeg")
        array_push($errors, "סוג הקובץ אינו נתמך");

    if(empty($errors)) {

        $dateTime = new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));

        $imgName = $mole->getId() . "_" . (count($mole->getDetails()) + 1);

        $moleDetailsData = array(
            "MoleId" => $mole->getId(),
            "ImgUrl" => $imgName,
            "ImgFigureUrl" => $imgName,
            "ImgSurfaceUrl" => $imgName,
            "Size" => $size,
            "Color" => $color,
            "CreateTime" => $dateTime->format("Y-m-d H:i:s"),
            "RiskLevel" => 1
        );

        BetterLife::GetDB()->insert("moleDetails", $moleDetailsData);

        $uploaddir = '/home/goru/public_html/betterlife/media/moles/regular/';
        $uploadfile = $uploaddir . $imgName . ".jpg";

        $path = $_FILES['image']['tmp_name'];

        $img = new Imagick($path);
        $img->resizeImage(SystemConstant::MOLE_IMAGE_WIDTH, SystemConstant::MOLE_IMAGE_HEIGHT, 1,false);
        $img->writeImage($path);

        $data = file_get_contents($path);
        $base64 =  base64_encode($data);

        Services::sendPostRequest('http://frizen700.ddns.net:587/saveToDir', array("moleImage" => $base64, "name" => $imgName));
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile);

        Services::redirectUser("medical-profile.php");

    } else {
        $errorMsg = "<div class='col-12 text-center'><h4>נא לתקן את השגיאות הבאות: </h4>";
        $errorMsg .= "<ul class='list-group list-group-flush mb-3 ml-5'>";
        foreach ($errors as $error)
            $errorMsg .= "<li class='list-group-item' style='background: none; border-top: none'>$error</li>";
        $errorMsg .= "</ul></div>";
    }

}



$pageTemplate .= <<<PageBody

<div id="loading-overlay" style="display: none">
    <div id="loading-svg"></div>
    <div id="loading-text">מעבד...</div>
</div>

<div class="container mt-5">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>בדיקת נוספת</h2>
            <hr>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-6 col-12" data-aos="zoom-in-down">
        {$errorMsg}
            <form  id="contact-form" method="post" enctype="multipart/form-data">
                <div class="form-row">
                    
                    <div class="form-group col-md-12">
                        <label for="contact-phone">גודל (במ"מ)</label>
                        <input id="contact-phone" name="size" type="number" class="form-control" required>
                        <span class="text-danger"></span>
                    </div>
                    
                    <div class="form-group col-md-12">
                        <label for="contact-email">צבע</label>
                        <input id="contact-email" name="color" type="text" class="form-control" required>
                        <span class="text-danger"></span>
                    </div>
                    
                    <div class="form-group col-md-12">
                        <label for="contact-content">תמונה</label>
                        <input id="image-selector" name="image" type="file" class="form-control" required>
                        <span class="text-danger"></span>
                    </div>
                    {$csrf}
                    <div class="form-group col-md-12">
                        <button name="submit" onclick="loading()" type="submit" class="btn btn-block btn-secondary mb-5 custom-button" style="width: 80%; margin: auto">שלח טופס</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-md-6 col-12">
            <div class="row">
                <div class="col-12">
                    <img class="img-fluid round-shadow" id="selected-image" src="../media/random/correct-pic.jpg" />
                </div>
            </div>

        </div>
    </div>
</div>




<script>
$.validator.addMethod('filesize', function(value, element, param) {
    return this.optional(element) || (element.files[0].size <= param) 
});

$(document).ready(function() {
        $("#contact-form").validate({
            rules: {
                size: {
                    required: true,
                    rangelength: [1, 3]
                },
                color: {
                    required: true,
                    rangelength: [2, 20]
                },
                image: {
                    required: true,
                    filesize: 5000000,
                    extension: "jpg|jpeg"
                }
            },
            messages: {
                size: {
                    required: "חובה להזין גודל",
                    rangelength: "ניתן להזין עד 3 ספרות"
                },
                color: {
                    required: "יש להזין מיקום",
                    rangelength: "ניתן לכתוב 2-20 תווים"
                },
                image: {
                    required: "יש להזין תמונה",
                    filesize: "גודל מקסימלי 5MB",
                    extension: "תמונות בפורמט jpg / jpeg בלבד"
                }
            },
            highlight: function(element) {
              $(element).removeClass('is-valid').addClass('is-invalid');
            },
            unhighlight: function(element) {
              $(element).removeClass('is-invalid').addClass('is-valid');
            },
            errorPlacement: function(error, element) {
                error.appendTo( element.parent("div").children("span"))
            }});
    });

</script>

<script>
    $("#image-selector").change(function() {
      let reader = new FileReader();
      reader.onload = function(e) {
        let dataURL = reader.result;
        $("#selected-image").attr("src", dataURL);
      };
      reader.readAsDataURL($("#image-selector")[0].files[0]);
    });
</script>


<script>
function loading() {
    if ($('#contact-form').valid()) {
        $("#loading-overlay").css("display", "block");
    }
}
</script>
PageBody;


echo $pageTemplate;
include "../core/templates/footer.php";