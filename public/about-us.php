<?php
require_once "core/templates/header.php";


$pageTemplate .= <<<PageBody
<div class="container mt-5">
    <div class="row mb-5">
        <div class="col-12 text-center page-title" data-aos="zoom-in">
            <h2>אודותינו</h2>
            <hr>
        </div>
    </div>

    <div class="row">
        <div class="col-md-7">
            <div class="row text-right justify-content-center" data-aos="zoom-in-up">
                <div class="col-sm-10 offset-sm">
                    <h3 class="mr-3">עלינו</h3>
                    <p class="drop-cap">
                        חברת BetterLife נוסדה בשנת 2019 על ידי יוגב אגרנוב, סטודנט להנדסת תוכנה במסלול להנדסאים. החברה חרטה על דיגלה להביא לשינוי במספר חולי סרטן העור בישראל ובעולם.
                        על מנת להפחית את שיעור סרטון העור פיתחה החברה מערכת מבוססת בינה מלאכותית המסוגלת לנתח תמונה של שומה ולהבחין בין שומה סרטנית לשומה שפירה ולא מזיקה.
                        בנוסף לבינה מלאכותית, betterLife מעסיקה צוות רופאי עור שעוברים על כל התמונות שצולמו על ידי המשתמשים ומאבחנים את השומה, בנוסף לבינה המלאכותית.
                    </p>
                </div>
            </div>

            <div class="row text-right justify-content-center mt-5" data-aos="zoom-in-down">
                <div class="col-sm-10 offset-sm">
                    <h3 class="mr-3">החזון</h3>
                    <p class="drop-cap">
                        סרטן העור הינו אחד מסוגי המחלות שניתן לגלות בשלב יחסית מוקדם, אחוזי ההחלמה כאשר ישנו זיהוי מוקדם מגיעים כמעט ל100%! אנו רואים את העתיד ורוד ונקי מסרטן העור,
                        בזכות גישה נוחה לבדיקה ומעקב קבוע אנחנו מאמינים שנוכל להביא שינוי משמעותי במספר החולים קיום. מעבר לבדיקה על ידי בינה מלאכותית, הרשומים מקבלים בדיקת רופא עור במכשיר הסלולרי, מה שמאפשר מעקב צמוד וקבוע לכל השומות.
                        אין יותר תירוצים של תורים ארוכים, כנסו למערכת ותיבדקו!
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-5 col-12">
            <img class="img-fluid" src="media/random/about-logo.png" data-aos="zoom-in-down">
        </div>

    </div>



</div>
PageBody;


echo $pageTemplate;
include "core/templates/footer.php";
