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


try {
    $articleObj = Article::getById($_GET["Article"]);
} catch (\Throwable $e) {
    $articleObj = false;
    Services::flashUser("אירע שגיאה, אנא נסה שוב מאוחר יותר");
}


$errorMsg = "";
$errors = array();
if(isset($_POST["submit"])) {
    $title = htmlspecialchars(trim($_POST["title"]));
    $content = htmlspecialchars(trim($_POST["content"]));
    if (empty($title))
        array_push($errors, "לא הוזנה כותרת");


    if(empty($errors)) {

        $dateTime = new \DateTime('now',new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));

        $articleData = array(
            "Title" => $title,
            "Content" => $content,
            "Publish" => (isset($_POST["unPublish"]) && $_POST["unPublish"] == "on") ? 0 : 1,
            "LastUpdate" => $dateTime->format("Y-m-d H:i:s")
        );
        BetterLife::GetDB()->where(Article::TABLE_KEY_COLUMN, $articleObj->getId())->update(Article::TABLE_NAME, $articleData);
        if(!$_FILES["image"]["error"] && $_FILES['image']['type'] == "image/jpeg") {
            $imgName = $articleObj->getId() . ".jpg";
            $uploaddir = '/home/goru/public_html/betterlife/public/media/articles/';
            $uploadfile = $uploaddir . $imgName;
            Services::dump($uploadfile);
            $path = $_FILES['image']['tmp_name'];

            $img = new Imagick($path);
            $img->resizeImage(450, 350, 1,false);
            $img->writeImage($path);

            $data = file_get_contents($path);
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile);
        }

        Services::redirectUser("articles.php");

    } else {
        $errorMsg = "<div class='col-12 text-center'><h4>נא לתקן את השגיאות הבאות: </h4>";
        $errorMsg .= "<ul class='list-group list-group-flush mb-3 ml-5'>";
        foreach ($errors as $error)
            $errorMsg .= "<li class='list-group-item' style='background: none; border-top: none'>$error</li>";
        $errorMsg .= "</ul></div>";
    }

}

$checked = $articleObj->isPublish() ? "" : "checked";

$pageTemplate .= <<<PageBody
<style>.custom-control-label::before ,.custom-control-label::after {right: -22%;}.custom-control-label {right: 28%;}iframe {border-radius: 0 !important;}input[type=text], input[type=file]{background: #f9f9f9}</style>
<div class="container">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>עריכת מאמר</h2>
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
                        <input name="title" value="{$articleObj->getTitle()}" type="text" class="form-control" placeholder='כותרת המאמר' required>
                        <span class="text-danger"></span>
                    </div>
                    
                    <div class="form-group col-md-12">
                        <label for="contact-content">החלפת תמונת נושא</label>
                        <input name="image" type="file" class="form-control">
                        <span class="text-danger"></span>
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="unPublish" {$checked}>
                        <label class="custom-control-label" for="customCheck1">להסתיר מאמר</label>
                    </div>
                    <div class="form-group col-md-12">
                        <label for="contact-content">תוכן המאמר</label>
                        <textarea class="form-control editarea" name="content" type="textarea" id="contact-content">{$articleObj->getContent()}</textarea>
                        <span class="text-danger"></span>
                    </div>
                    {$csrf}
                    <div class="form-group col-12">
                        <button type="submit" name="submit" class="btn btn-block btn-secondary mb-5 custom-button" style="width: 80%; margin: auto">עדכן מאמר</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
        $("form").validate({
            rules: {
                title: {
                    required: true,
                    rangelength: [2, 30]
                }
            },
            messages: {
                title: {
                    required: "יש להזין כותרת",
                    rangelength: "ניתן לכתוב 2-30 תווים"
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
