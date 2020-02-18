<?php
require_once "../core/templates/header.php";
use BetterLife\Article\Article;
use BetterLife\BetterLife;
use BetterLife\User\Session;
use BetterLife\User\User;
use BetterLife\System\Services;
use BetterLife\System\SystemConstant;

try {
    $articleObj = Article::getById($_GET["Article"]);
} catch (\Throwable $e) {
    $articleObj = false;
    Services::flashUser("אירע שגיאה, אנא נסה שוב מאוחר יותר");
}

if(!$articleObj || !$articleObj->isPublish())
    Services::flashUser("כתבה לא נמצאה");

$articleObj->increaseViews();


$articleLikeButton = "";
$newCommentForm = "";
$jsScript = "";
$commentsRows = "";


if(Session::checkUserSession()) {
    $userObj = User::GetUserFromSession();
    $csrf = \BetterLife\System\CSRF::formField();

    $newCommentForm = <<<CommentForm
    <div class="col-12"><h3>תגובות</h3></div>
        <div class="col-12">
            <div id="articleCommentsError"></div>
            <div>
                <div class="form-group">
                    <textarea class="form-control" id="articleNewCommentArea" name="new-comment" placeholder="הגב כאן..." rows="4" required></textarea>
                </div>
                {$csrf}
                <div class="form-group" style="direction: ltr">
                    <button class="btn btn-primary mt-2" onclick="addComment({$articleObj->getId()}, {$userObj->getId()}, $('#articleNewCommentArea'), '{$userObj->getToken()}')">שלח תגובה</button>
                </div>
            </div>
        </div>
    </div>
CommentForm;

    $articleLikeButton = <<<articleLikeButton
    <button class="btn btn-success" onclick="likeArticle($(this), {$userObj->getId()}, {$articleObj->getId()}, '{$userObj->getToken()}')">  {$articleObj->getLikes()} <i class="fas fa-thumbs-up" style='margin-bottom: 2px'></i></button>
articleLikeButton;

}








//comments
$comments = $articleObj->getAllComments();
if(!empty($comments)) {

    foreach (array_reverse($comments) as $comment) {
        $sex = ($comment->getCreator()->getSex()) ? "female2.jpg" : "male1.jpg";
        $countLikes = $comment->getLikes();

        if(Session::checkUserSession())
            $likeButton = "<span class='ml-4 like-button like-button-active' onclick=\"addOrRemoveLike($(this), {$userObj->getId()}, {$comment->getId()}, '{$userObj->getToken()}')\"><i class='fas fa-thumbs-up' style='margin-bottom: 2px' ></i> {$countLikes} </span>";
        else
            $likeButton = "<span class='ml-4 like-button'><i class='fas fa-thumbs-up' style='margin-bottom: 2px' ></i> {$countLikes} </span>";

        $commentsRows .= <<<Comments
                <div class="row align-items-center">
                    <div class="col-4 col-md-1">
                        <img class="img-fluid" src="../../media/characters/{$sex}">
                    </div>
                    <div class="col-md-9 col-8">
                        <h6><strong> {$comment->getCreator()->getFullName()}</strong> <i style="font-size: 12px; color: #4e4e4e"> {$comment->getCreateTime()->format("d/m/y h:i")}</i></h6>
                        <p>{$comment->getContent()}</p>
                    </div>
                    <div class="col-md-2 col-12 text-left">
                        {$likeButton}
                    </div>
                    <div class="col-12">
                        <hr>
                    </div>
                </div>
    Comments;
    }
}



$pageTemplate .= <<<PageBody
<div class="container info-page">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>{$articleObj->getTitle()}</h2>
            <hr>
            <h6>{$articleObj->getCreator()->getFullName()} - {$articleObj->getCreateTime()->format("d/m/y")}</h6>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-12">
            <img style="float:left; margin: 0 10px 10px 0" class="img-fluid" src="../media/articles/{$articleObj->getImgUrl()}">
            <p>
                {$articleObj->getContent()}
            </p>
        </div>
        <div class="col-12" style="direction: ltr">
            {$articleLikeButton}
        </div>
    </div>
    
    
    
    <div  class="row mb-5">
        
            {$newCommentForm}
        
        <div id="commentsRow" class="col-12">
        
            {$commentsRows}
            
        </div>
        
    </div>
    
    
    
</div>
PageBody;

echo $pageTemplate;
include "../core/templates/footer.php";
