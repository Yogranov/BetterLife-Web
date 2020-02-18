<?php
require_once "../core/templates/header.php";
use BetterLife\BetterLife;
use BetterLife\User\User;
use BetterLife\System\SystemConstant;
use BetterLife\Article\Article;
use BetterLife\System\Services;

$csrf = \BetterLife\System\CSRF::formField();

BetterLife::GetPermissions([4,5]);
$user = User::GetUserFromSession();

$errorMsg = "";
$errors = array();
if(isset($_POST["submit"])) {
    $title = htmlspecialchars(trim($_POST["title"]));
    $content = htmlspecialchars(trim($_POST["content"]));
    if (empty($title))
        array_push($errors, "לא הוזנה כותרת");
    

    if (!isset($_FILES["image"]))
        array_push($errors, "לא הוזנה תמונה");


    if($_FILES['image']['type'] != "image/jpeg")
        array_push($errors, "סוג הקובץ אינו נתמך");

    if(empty($errors)) {

        $dateTime = new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));

        $articleData = array(
            "Title" => $title,
            "ImgUrl" => BetterLife::GetDB()->orderBy("Id","DESC")->getOne(Article::TABLE_NAME, "Id")["Id"]+1 . ".jpg",
            "Content" => $content,
            "Creator" => $user->getId(),
            "Publish" => (isset($_POST["unPublish"]) && $_POST["unPublish"] == "on") ? 0 : 1,
            "Likes" => "",
            "Views" => 0,
            "CreateTime" => $dateTime->format("Y-m-d H:i:s")
        );

        BetterLife::GetDB()->insert(Article::TABLE_NAME, $articleData);
        $imgName = $articleData["ImgUrl"];
        $uploaddir = '/home/goru/public_html/betterlife/public/media/articles/';
        $uploadfile = $uploaddir . $imgName;

        $path = $_FILES['image']['tmp_name'];

        $img = new Imagick($path);
        $img->resizeImage(450, 350, 1,false);
        $img->writeImage($path);

        $data = file_get_contents($path);

        move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile);

        Services::redirectUser("articles.php");

    } else {
        $errorMsg = "<div class='col-12 text-center'><h4>נא לתקן את השגיאות הבאות: </h4>";
        $errorMsg .= "<ul class='list-group list-group-flush mb-3 ml-5'>";
        foreach ($errors as $error)
            $errorMsg .= "<li class='list-group-item' style='background: none; border-top: none'>$error</li>";
        $errorMsg .= "</ul></div>";
    }

}

$pageTemplate .= <<<PageBody
<style>.custom-control-label::before ,.custom-control-label::after {right: -16%;}.custom-control-label {right: 20%;}iframe {border-radius: 0 !important;}input[type=text], input[type=file]{background: #f9f9f9}</style>
<div class="container">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>מאמר חדש</h2>
            <hr>
        </div>
    </div>
    
     <div class="row mb-5">
        <div class="col-12" data-aos="zoom-in-down">
        {$errorMsg}
            <form method="post" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="form-group col-12">
                        <label for="contact-name">כותרת</label>
                        <input name="title" type="text" class="form-control" placeholder='כותרת המאמר' required>
                        <span class="text-danger"></span>
                    </div>
                    
                    <div class="form-group col-md-12">
                        <label for="contact-content">תמונת נושא</label>
                        <input name="image" type="file" class="form-control" required>
                        <span class="text-danger"></span>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="unPublish">
                        <label class="custom-control-label" for="customCheck1">צור מאמר ללא פירסום</label>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="contact-content">תוכן המאמר</label>
                        <textarea class="form-control editarea" name="content" type="textarea" id="contact-content"></textarea>
                        <span class="text-danger"></span>
                    </div>
                    {$csrf}
                    <div class="form-group col-12">
                        <button type="submit" name="submit" class="btn btn-block btn-secondary mb-5 custom-button" style="width: 80%; margin: auto">פרסם מאמר</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$.validator.addMethod('filesize', function(value, element, param) {
    return this.optional(element) || (element.files[0].size <= param) 
});

$(document).ready(function() {
        $("form").validate({
            rules: {
                title: {
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
                title: {
                    required: "יש להזין כותרת",
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

PageBody;


echo $pageTemplate;
include "../core/templates/footer.php";
