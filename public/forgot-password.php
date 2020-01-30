<?php
require_once "core/templates/header.php";

$pageBody = <<<PageBody
<div class="container register-form">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>איפוס סיסמה</h2>
            <hr>
        </div>
    </div>
   
   
    <div class="row justify-content-center">
        <div class="col-12 col-md-6" >
            <form class="form-row">
                
                <div class="form-group col-12">
                    <label>
                        <p class="label-txt">תעודת זהות</p>
                        <input type="text" class="input form-control" name="personId" required>
                        <div class="line-box">
                            <div class="line"></div>
                        </div>
                        <span class="text-danger"></span>
                    </label>
                </div>
                
                <div class="form-group col-12">
                    <label>
                        <p class="label-txt">דואר אלקטרוני</p>
                        <input type="email" class="input form-control" name="email" required>
                        <div class="line-box">
                            <div class="line"></div>
                        </div>
                        <span class="text-danger"></span>
                    </label>
                </div>
                
                <div class="form-group col-md-12 justify-content-center">
                    <button type="submit" class="btn btn-block btn-secondary">אפס סיסמה</button>
                </div>
  
            </form>
        </div>
     </div>
</div>

<script>
$(document).ready(function(){
  $('.input').focus(function(){
    $(this).parent().find(".label-txt").addClass('label-active');
  });

  $(".input").focusout(function(){
    if ($(this).val() == '') {
      $(this).parent().find(".label-txt").removeClass('label-active');
    };
  });
  });
</script>
PageBody;

echo $pageBody;
include "core/templates/footer.php";