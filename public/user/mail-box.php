<?php
require_once "../core/templates/header.php";
use BetterLife\User\Session;
use BetterLife\User\User;
use BetterLife\BetterLife;
use BetterLife\System\Services;
use BetterLife\MailBox\Conversation;


if(Session::checkUserSession())
    $userObj = User::GetUserFromSession();
else
    Services::RedirectHome();

$conDB = BetterLife::GetDB()->where("CreatorId", $userObj->getId())->orWhere("RecipientId", $userObj->getId())->get(Conversation::TABLE_NAME, null, "Id");
$noConsError = count($conDB) != 0 ? "style='display: none'" : "";

$conRows = "";
if(!empty($conDB)) {
    foreach ($conDB as $con) {
        $conObj = new Conversation($con["Id"]);
        $type = $conObj->getCreator() == $userObj ? "sent" : "coming";
        $link = base64_encode($conObj->getId() . '-' . $userObj->getToken());
        $background = $conObj->checkView($userObj->getId()) ? "" : "style='background-color: #FFAEA6'";

        $conRows .= "<a href='conversation.php?Con={$link}' {$background} message-type='{$type}' class='list-group-item'>
                        <span class='name'>{$conObj->getSubject()}</span>
                        <span class='text-muted mr-3' style='font-size: 12px'>עבור: {$conObj->getRecipient()->getFullName()}</span>
                        <span class='badge badge-secondary float-left mt-1'>{$conObj->getCreateTime()->format("d/m/y H:i")}</span>
                        <span class='text-muted  ml-4 float-left mt-1' style='font-size: 11px;'>יוצר: {$conObj->getCreator()->getFullName()}</span> 

                     </a>";
    }


}

$pageTemplate .= <<<PageBody
<style>
body {
background: #f7f7f7;
}
</style>
<div class="container mt-5 mb-5">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>תיבת הודעות</h2>
            <hr>
        </div>
    </div>

    <div class="row">
       
        <div class="col-12 col-sm-2">
            <a href="new-conversation.php" class="btn btn-danger btn-sm btn-block" role="button">שיחה חדשה</a>
            <hr />
            <div>
              <a id="all-con" href="#" class="btn btn-primary mb-2" style="width: 100%" onclick="allConButton()">כל השיחות</a><br>
              <a id="coming-con" href="#" class="btn" style="width: 100%" onclick="comingConButton()">שיחות נכנסות</a>
              <a id="sent-con" href="#" class="btn" style="width: 100%" onclick="sentConButton()">שיחות יוצאות</a>
            </div>
        </div>
        <div class="col-12 col-sm-10" style="margin-bottom: 30px">
            <ul class="nav nav-tabs">
                <li class="nav-item"><span class="nav-link active"><i class="fas fa-inbox"></i> ראשי</span></li>
            </ul>
            
            <div id="message-rows" class="list-group">
                <div id="no-cons" class="text-center mt-3" {$noConsError} >לא קיימות שיחות</div>
                {$conRows}
            </div>
            
            
        </div>
    </div>


</div>
<script> 
    function allConButton() {
      $('#all-con').addClass('btn-primary');
      $('#coming-con').removeClass('btn-primary');
      $('#sent-con').removeClass('btn-primary');
      
      $('#message-rows a').each(function() {
            $(this).show();
      });
      
      if($('#message-rows a:visible').length == 0)
          $('#no-cons').show();
      else 
          $('#no-cons').hide();
      
    }
    
    
    function sentConButton() {
      $('#all-con').removeClass('btn-primary');
      $('#coming-con').removeClass('btn-primary');
      $('#sent-con').addClass('btn-primary');
      
      $('#message-rows a').each(function() {
            if($(this).attr('message-type') == 'sent')
                $(this).show();
            else
                $(this).hide();
      });
      
      if($('#message-rows a:visible').length == 0)
            $('#no-cons').show();
        else 
            $('#no-cons').hide();
    }
    
    function comingConButton() {
      $('#all-con').removeClass('btn-primary');
      $('#coming-con').addClass('btn-primary');
      $('#sent-con').removeClass('btn-primary');
      
      $('#message-rows a').each(function() {
            if($(this).attr('message-type') == 'coming')
                $(this).show();
            else
                $(this).hide();
      });
      
     if($('#message-rows a:visible').length == 0)
        $('#no-cons').show();
    else 
        $('#no-cons').hide();
    }
</script>
PageBody;


echo $pageTemplate;
include "../core/templates/footer.php";
