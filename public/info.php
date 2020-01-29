<?php
require_once "core/templates/header.php";


$pageBody = <<<PageBody
<style>
span{
    padding-right: 5%;
}
</style>
<div class="container info-page">
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
                <h3>כיצד להתלבש נכון כשיוצאים</h3>
                <h5>מאת: ד"ר אלכס שפירא</h5>
                <p class="pb-3">
                    בגדים הם דבר שכדאי לשים לב אליו תמיד, ובמיוחד בשמש. בזמן שהייה בשמש, בטיול, במשחקי ספורט, במשחקים אחרים, או סתם כשנמצאים מחוץ לבית, אל תשכחו להתלבש בהתאם - חולצה המכסה את הכתפיים, בעלת שרוולים, ומכנסיים ארוכים. גם על חוף הים או בבריכה אל תשארו חשופים לגמרי לשמש!
                    כשנכנסים למים, כדאי מאוד להיכנס עם בגד גוף או עם חולצה (מומלץ שהחולצה תהיה מכותנה והדוקה לגוף), זה גם קריר וגם מגן.
                </p>
            </div>
            
            <div style="position: absolute; bottom:0; width: 95%; text-align: left; padding-left: 5%;">
                <span>12 <i class="far fa-comment" style="color: darkgoldenrod"></i></span>   
                <span>60 <i class="far fa-heart fa-lg" style="color: darkred"></i></span>
                <span>120 <i class="far fa-eye" style="color:darkblue;"></i></span>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-md-6 col-12">
            <img class="img-fluid" src="media/temp/sun-protection-cream.jpg">
        </div>
        <div class="col-md-6 col-12">
            <div class="mt-3">
                <h3>התגוננות מפני השמש</h3>
                <h5>מאת: ד"ר גלינה רובילסקי</h5>
                <p class="pb-3">
                    תכשיר עם מסנן קרינה מכיל חומרים הסופגים חלק מקרני השמש, או דוחים את הקרניים לפני שהן פוגעות בעור, וכך מונעים פגיעות בעור כמו כוויות, נמשים, כתמים ואפילו סרטן העור.
                    יחד עם זאת, חשוב לזכור, שהתכשירים עם מקדמי ההגנה אינם חוסמים לחלוטין את קרינת השמש, ולכן צריך להשתמש בהם יחד עם אמצעי ההגנה הנוספים: כובע, משקפי שמש, ביגוד מתאים, שהייה בצל, ושהייה בשעות הבטוחות: לפני עשר בבוקר ואחרי ארבע אחר-הצהריים.
                </p>
            </div>
            
            <div style="position: absolute; bottom:0; width: 95%; text-align: left; padding-left: 5%;">
                <span>1 <i class="far fa-comment" style="color: darkgoldenrod"></i></span>   
                <span>33 <i class="far fa-heart fa-lg" style="color: darkred"></i></span>
                <span>420 <i class="far fa-eye" style="color:darkblue;"></i></span>
            </div>
        </div>
    </div>
    
    <div class="row mb-5">
        <div class="col-md-6 col-12">
            <img class="img-fluid" src="media/temp/sun-protection.jpg">
        </div>
        <div class="col-md-6 col-12">
            <div class="mt-3">
                <h3>כיצד להשתמש במסנן קרינה</h3>
                <h5>מאת: ד"ר אבי יצחק</h5>
                <p class="pb-3">
                    ש למרוח את כל אזורי הגוף החשופים לשמש, כולל שפתיים, ואוזניים, אך להיזהר שהתכשיר לא יבוא במגע עם העיניים. חשוב למרוח שכבה אחת, לחכות שתיספג בעור, ואז למרוח שכבה נוספת.
                    יש למרוח את התכשיר לפני כל יציאה לשמש, גם כשיוצאים לבית הספר, למגרש הספורט, לטיול וכדי, ולא רק כשהולכים לים או לבריכה. צריך לחדש את מריחת התכשיר כל שעתיים.
                    למי שנמצא זמן ממושך במים, מומלץ להשתמש בתכשיר שעמיד במים. בכל מקרה, אחרי שהייה במים ואחרי שמזיעים, צריך להתמרח שוב.
                </p>
            </div>
            
            <div style="position: absolute; bottom:0; width: 95%; text-align: left; padding-left: 5%;">
                <span>4 <i class="far fa-comment" style="color: darkgoldenrod"></i></span>   
                <span>20 <i class="far fa-heart fa-lg" style="color: darkred"></i></span>
                <span>93 <i class="far fa-eye" style="color:darkblue;"></i></span>
            </div>
        </div>
    </div>
        
</div>
PageBody;


echo $pageBody;
include "core/templates/footer.php";
