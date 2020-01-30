<?php
require_once "core/templates/header.php";

$pageBody = <<<PageBody
<style>body {background-color: #f5f5f5;}</style>
<div class="container">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>צוות הרופאים</h2>
            <hr>
        </div>
    </div>
    
    <div class="row doctors">
    
        <div class="col-md-4 col-12" data-aos="zoom-in-up">
            <img class="img-fluid" src="core/services/imageHandle.php?image=1">
            <div class="mt-2">
                <h5>ד"ר מיכאל ג'רי</h5>
                <h6>מנהל מחלקת סרטון עור באיכילוב</h6>
                <p>ד"ר ג'רי בן 34, מנהל מחלקת סרטן העור בבית החולים איכילוב שבתל אביב. ד"ר ג'רי הינו אחד מחוקרי סרטון העור הגדולים בישראל.</p>
            </div>
        </div>
        
        <div class="col-md-4 col-12" data-aos="zoom-in-up">
            <img class="img-fluid" src="core/services/imageHandle.php?image=2">
            <div class="mt-2">
                <h5>ד"ר תכלת שהם </h5>
                <h6>רופאת עור בכירה</h6>
                <p>ד"ר תכלת, בת 31 וכבר רופאת עור בחירה. ד"ר שהם הינה רופאה פרטית מהמבוקשות בשוק.</p>
            </div>
        </div>
        
        <div class="col-md-4 col-12" data-aos="zoom-in-up">
            <img class="img-fluid" src="core/services/imageHandle.php?image=3">
            <div class="mt-2">
                <h5>ד"ר שי מורן </h5>
                <h6>חבר דריקטוריון בעמותה למלחמה בסרטן</h6>
                <p>ד"ר שי מורן הינו אחד ממובילי המאבק למלחמה בסרטן בישראל. ד"ר מורן נודע בזכות תרומתו למלחמה בסרטן בישראל.</p>
            </div>
        </div>
        
        <div class="col-md-4 col-12" data-aos="zoom-in-up">
            <img class="img-fluid" src="core/services/imageHandle.php?image=4">
            <div class="mt-2">
                <h5>ד"ר שרון זיו </h5>
                <h6>מומחא לרפואת העור בקפלן</h6>
                <p>ד"ר שרון זיו, סיים לימודיו בהצטיינות בבית החולים תל השומר, משם עבר לבית החולים קפלן לאחר שבית החולים לא הצליח להתמודד מול החולים הרבים..</p>
            </div>
        </div>
        
        <div class="col-md-4 col-12" data-aos="zoom-in-up">
            <img class="img-fluid" src="core/services/imageHandle.php?image=5">
            <div class="mt-2">
                <h5>פרופסור שמעון רביב </h5>
                <h6>בעל מחקר חדיש בתחום סרטן העור</h6>
                <p>פרופסור שמעון רביב הינו אחד מגדולי הרופאים שידעה מדינת ישראל. פרופסור רביב הביא לשינוי חיובי במספר המחלימים מהמחלה.</p>
            </div>
        </div>
        
        <div class="col-md-4 col-12" data-aos="zoom-in-up">
            <img class="img-fluid" src="core/services/imageHandle.php?image=6">
            <div class="mt-2">
                <h5>ד"ר עומר אברהם </h5>
                <h6>רופא עור פרטי</h6>
                <p>ד"ר עומר אברהם סיים לימודיו והתמחות בחקר סרטן העור, לאחר שעבד במגזר הציבורי מספר שנים החליט לפתוח מרפאה משלו.</p>
            </div>
        </div>
        
        <div class="col-md-4 col-12" data-aos="zoom-in-up">
            <img class="img-fluid" src="core/services/imageHandle.php?image=7">
            <div class="mt-2">
                <h5>ד"ר שקד ניומן</h5>
                <h6>רופא עור באסף הרופא</h6>
                <p>ד"ר שקד ניומן התחיל כפרמדיק בשירותו הצבאי, לאחר השירות חש כי לא תרם מספיק והחליט ללמוד רפואה. היום ד"ר ניומן מתמחה במסלול לחקר סרטן העור.</p>
            </div>
        </div>
        
        <div class="col-md-4 col-12" data-aos="zoom-in-up">
            <img class="img-fluid" src="core/services/imageHandle.php?image=8">
            <div class="mt-2">
                <h5>ד"ר עפרי חדד </h5>
                <h6>רופאת עור ומשפחה</h6>
                <p>ד"ר עפרי חדד הינה אחת מרופאות המשפחה היחידות שמתפקדת גם כרופאת עור. ד"ר חדד בעלת תואר שני ברפואה משלימה.</p>
            </div>
        </div>
        
        <div class="col-md-4 col-12" data-aos="zoom-in-up">
            <img class="img-fluid" src="core/services/imageHandle.php?image=9">
            <div class="mt-2">
                <h5>ד"ר ארביב דקל </h5>
                <h6>כירורג בבית החולים הדסה עין כרם</h6>
                <p>ד"ר ארביב דקל רופא עור מומחא וכירורג ממעלה ראשונה. רוב התעסוקה של ד"ר דקל הינה ניתוכים עבור חולים במצבים מסובכים.</p>
            </div>
        </div>
        
    </div>
</div>
PageBody;

echo $pageBody;
include "core/templates/footer.php";