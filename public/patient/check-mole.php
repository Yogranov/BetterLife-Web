<?php

require_once "../core/templates/header.php";
$csrf = BetterLife\System\CSRF::formField();



$pageBody = <<<PageBody
<script src="jquery-3.4.1.min.js"></script>

<div class="container mt-5" style="direction: ltr">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>בדיקת שומה</h2>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-12" data-aos="zoom-in-down">
            <h5 class="text-center mb-5"><i class="fas fa-file-signature" style="color: #4e4e4e;"></i> טופס פנייה</h5>
                <div class="form-row">
                
                    <div class="form-group col-md-6">
                        <label for="contact-name">טעינת תמונה</label>
                        <input id="image-selector" name="name" type="file" class="form-control" placeholder='ישראל ישראלי'>
                    </div>
                    
                    <div class="form-group col-md-6">
                        <p style="font-weight: bold">Predictions</p>
                        <p>Benign: <span id="Benign-prediction"></span></p>
                        <p>Malignant: <span id="Malignant-prediction"></span></p>
                        <img id="selected-image" src="" />
                    </div>
                   {$csrf}
                    <div class="form-group col-md-12">
                        <button id="predict-button" type="submit" class="btn btn-block btn-secondary mb-5 custom-button" style="width: 80%; margin: auto">שלח טופס</button>
                    </div>
                </div>
            </div>

    </div>

<script>
    let base64Image;
    $("#image-selector").change(function() {
      let reader = new FileReader();
      reader.onload = function(e) {
        let dataURL = reader.result;
        $("#selected-image").attr("src", dataURL);
        base64Image = dataURL.replace("data:image/png;base64,","");
        console.log(base64Image);
      };
      reader.readAsDataURL($("#image-selector")[0].files[0]);
      $("#Benign-prediction").text("");
      $("#Malignant-prediction").text("");
    });

    $("#predict-button").click(function(event) {
      let message = {
        image: base64Image
      };
      console.log(message);
      $.post("http://109.186.80.210:5000/predict", JSON.stringify(message), function(response) {
        console.log(response);
        $("#Benign-prediction").text((response.prediction.Benign.toFixed(2)*100) + "%");
        $("#Malignant-prediction").text((response.prediction.Malignant.toFixed(2)*100) + "%");

      })
    })
</script>

</div>
PageBody;


echo $pageBody;
include "../core/templates/footer.php";
