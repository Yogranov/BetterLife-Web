<?php
require_once "../core/templates/header.php";
use BetterLife\Article\Article;
use BetterLife\User\User;

if(\BetterLife\User\Session::checkUserSession()){
    $userObj = User::GetUserFromSession();
    $checkIfEditor = $userObj->checkRole([4,5]);
}
else
    $checkIfEditor = false;

$articles = $checkIfEditor ? Article::getAllArticles() : Article::getAllActiveArticles();
$newArticle = $checkIfEditor ? "<div class='col-12' style='direction: ltr'><a href='new-article.php' class='btn btn-secondary'>יצירת כתבה חדשה</a></div>" : "";

$rows = "";
foreach (array_reverse($articles) as $article) {
    $shortContent = strlen($article->getContent()) > 900 ? substr(strip_tags($article->getContent()),0,900) . "..." : strip_tags($article->getContent());
    $countComments = count($article->getAllComments());
    if($checkIfEditor) {
        $editButtom = "<a href='edit-article.php?Article={$article->getId()}' class='btn btn-warning mr-3'>עריכה</a>";
        if($article->isPublish())
            $publishButton = "<button onclick='showHideArticle({$article->getId()}, \"hide\", {$userObj->getId()}, \"{$userObj->getToken()}\", $(this))' class='btn btn-danger mr-3'>הסתר</button>";
        else
            $publishButton = "<button onclick='showHideArticle({$article->getId()}, \"show\", {$userObj->getId()}, \"{$userObj->getToken()}\", $(this))' class='btn btn-success mr-3'>הצג</button>";
    } else {
        $editButtom = "";
        $publishButton = "";
    }

    $rows .= <<<Rows
        <div class="row" style="margin-bottom: 20%">
            <div class="col-md-6 col-12">
                <img class="img-fluid" src="../../media/articles/{$article->getImgUrl()}">
            </div>
            <div class="col-md-6 col-12">
                <div class="mt-3">
                    <h3>{$article->getTitle()}</h3>
                    <h5>מאת: {$article->getCreator()->getFullName()}</h5>
                    <p>
                        {$shortContent}
                    </p>
                </div>
                
                <div class="" style="position: absolute; bottom:0; width: 95%; text-align: left; padding-left: 5%;">
                    <span>{$countComments} <i class="far fa-comment" style="color: darkgoldenrod"></i></span>   
                    <span>{$article->getLikes()} <i class="far fa-heart fa-lg" style="color: darkred"></i></span>
                    <span>{$article->getViews()} <i class="far fa-eye" style="color:darkblue;"></i></span>
                </div>
                <div class="col-7" style="position: absolute; bottom: 0;">
                    <a href="article.php?Article={$article->getId()}" class="btn btn-primary">להמשך קריאה</a>
                    {$editButtom}
                    {$publishButton}
                </div>
            </div>
        </div>
Rows;
}




$pageTemplate .= <<<PageBody
<div class="container info-page">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>מידע</h2>
            <hr>
        </div>
        {$newArticle}
    </div>
    
    {$rows}
            
</div>
PageBody;


echo $pageTemplate;
include "../core/templates/footer.php";
