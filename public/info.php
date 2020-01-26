<?php
require_once "core/templates/header.php";

$pageBody = <<<PageBody
<style>
span{
    padding-right: 5%;
}
</style>
<div class="container">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>מידע</h2>
            <hr>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-md-6 col-12">
            <img class="img-fluid" src="media/temp/clothing.jpg">
        </div>
        <div class="col-md-6 col-12">
            <div class="mt-3">
                <h3>התגוננות מפני השמש</h3>
                <h5>מאת: ד"ר אלכס שפירא</h5>
                <p class="pb-3">אחד הצירופים הפחות נכונים בעברית הוא "שיזוף בריא". מומחים ברפואת עור מזהירים: אין דבר כזה שיזוף בריא. השמש היא אחד הגורמים המזיקים ביותר לאיבר הגדול בגופנו- העור, איבר שיש לו זיכרון מצוין ונזקי השמש והשיזוף לא תמיד ניתנים לזיהוי באופן מיידי, אלא רק כעבור שנים, לפעמים מאוחר מדי. נזקי העור מתבטאים במגוון דרכים- החל בצריבה, הופעת פיגמנטציה וקמטוטים, וכלה בגידולים שפירים או ממאירים. מי שהרבה להשתזף בצעירותו, שלא יתפלא אם כעבור עשרים-שלושים שנה, יתגלה אצלו לפתע גידול סרטני, גם אם, במרוצת השנים שחלפו מאז המעיט מאד להשתזף. אנשים נחשפים לסכנת הקרינה בחיי היום יום, גם ללא הליכה מכוונת בשמש לשם שיזוף.. .</p>
            </div>
            
            <div style="position: absolute; bottom:0; width: 100%; text-align: left; padding-left: 10%;">
                <span>2 <i class="far fa-comment"></i></span>   
                <span>50 <i class="far fa-heart fa-lg"></i></span>
                <span>135 <i class="far fa-eye"></i></span>
            </div>
        </div>
    </div>
    
</div>
PageBody;



echo $pageBody;
include "core/templates/footer.php";
