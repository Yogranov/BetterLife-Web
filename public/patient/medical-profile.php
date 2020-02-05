<?php
require_once "../core/templates/header.php";



$pageBody = <<<PageBody

<div class="container">
    <div class="row mb-2">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>פרופיל רפואי</h2>
            <hr>
        </div>
    </div>
</div>

<div class="container-fluid medical-profile-row">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-12">
                <table class="table">
                    <tbody>
                        <tr>
                            <td class="border-0">מספר:</td>
                            <td class="border-0">#1</td>
                        </tr>
                        <tr>
                            <td>מיקום:</td>
                            <td>בטן תחתונה</td>
                        </tr>
                        <tr>
                            <td>דרגת סיכון:</td>
                            <td>אין סיכון</td>
                        </tr>
                        <tr>
                            <td>גודל (מ"מ):</td>
                            <td>30</td>
                        </tr>
                        <tr>
                            <td>עדכון אחרון:</td>
                            <td>15/03/19</td>
                        </tr>
                        <tr>
                            <td>רופא מאבחן:</td>
                            <td>ד"ר אלירן חג'ג</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-8 col-12">
                <div class="row">
                    <div class="col-md-6 col-12 text-left d-flex align-items-end flex-column">
                        <h6>נוצר: 15/03/19</h6>
                        <button class="mt-auto btn btn-success pt-2">פרטים נוספים</button>
                    </div>
                    <div class="col-md-6 col-12">
                        <img class="img-fluid round-shadow" src="imageHandle.php?image=1&dir=regular">
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid medical-profile-row row-background-gray">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-4 col-12">
            
                <table class="table">
                    <tbody>
                        <tr>
                            <td class="border-0">מספר:</td>
                            <td class="border-0">#1</td>
                        </tr>
                        <tr>
                            <td>מיקום:</td>
                            <td>בטן תחתונה</td>
                        </tr>
                        <tr>
                            <td>דרגת סיכון:</td>
                            <td>אין סיכון</td>
                        </tr>
                        <tr>
                            <td>גודל (מ"מ):</td>
                            <td>30</td>
                        </tr>
                        <tr>
                            <td>עדכון אחרון:</td>
                            <td>15/03/19</td>
                        </tr>
                        <tr>
                            <td>רופא מאבחן:</td>
                            <td>ד"ר אלירן חג'ג</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-8 col-12">
                <div class="row">
                    <div class="col-md-6 col-12 text-left d-flex align-items-end flex-column">
                        <h6>נוצר: 15/03/19</h6>
                        <button class="mt-auto btn btn-success pt-2">פרטים נוספים</button>
                    </div>
                    <div class="col-md-6 col-12">
                        <img class="img-fluid round-shadow" src="imageHandle.php?image=1&dir=regular">
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

 
PageBody;


echo $pageBody;
include "../core/templates/footer.php";
