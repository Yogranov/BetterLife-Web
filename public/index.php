<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';
?>
<html lang="he" xmlns="http://www.w3.org/1999/html">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
    <link rel="stylesheet" href="system/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="system/aos/aos.css">
    <link rel="stylesheet" href="system/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="core/css/main.css">
    <link rel="stylesheet" href="core/css/homepage.css">

    <title>BetterLife</title>
    <link rel="icon" href="media/favicon.png">



</head>
<body>
<nav class="navbar navbar-expand-lg px-lg-5 flex-row-reverse mt-5 navbar-light">
    <a class="navbar-brand pl-lg-3" href="#">
        <img src="media/logos/BetterLifeLogo.png" alt="logo">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="#">כניסה</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">כתבות</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">אודות</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">צור קשר</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <div class="container">
        <div class="row title-container">
            <div class="col-md-6 col-12 title">
                מהיר,<br>
                מדויק,<br>
                מציל חיים!<br>
            </div>
            <div class="col-md-6 col-12">
                <img class="img-fluid" src="media/logos/BetterLifeBigLogo.png" style="margin: auto">
            </div>
        </div>
    </div>
</div>

<div class="container-fluid simple-stepts">
    <div class="container">
        <div class="row ">
            <div class="col-12">
                <h2>פשוט!</h2>
            </div>
            <div class="col-md-3 col-6">
                <h3>שלב א'</h3>
                <hr>
                <h3>התחברות</h3>
                <p>
                    מילוי מספר פרטים אישיים,
                    ונכנסת למערכת. מרגע זה
                    .נשאר רק לעבור לשלב ב
                </p>
            </div>
            <div class="col-md-3 col-6">
                <h3>שלב ב'</h3>
                <hr>
                <h3>מילוי פרטים</h3>
                <p>
                    הוספת שומה חדשה לפרופיל,
                    מילוי פרטים כגון גודל ומיקום
                    .וממשיכים לשלב ג
                </p>
            </div>
            <div class="col-md-3 col-6">
                <h3>שלב ג'</h3>
                <hr>
                <h3>צילום</h3>
                <p>
                    בסך הכל לפתוח את המצלמה
                    לצלם את השומה על פי ההוראות
                    .ולהעלות לאתר
                </p>
            </div>
            <div class="col-md-3 col-6">
                <h3>שלב ד'</h3>
                <hr>
                <h3>תוצאות</h3>
                <p>
                    מיד לאחר הצילום המערכת
                    תעדכן בעזרת בינה מלאכותית
                    .מה הערכה שלה לגבי השומה
                    בשלב זה השומה תעבור לבדיקה
                    .שתתבצע על ידי אחד מהרופאים
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid info">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>מידע</h2>
            </div>
            <div class="col-md-6 col-12">
                <img class="img-fluid" src="media/temp/sun-protection.jpg">
                <h3>התגוננות בפני קרינת השמש</h3>
                <p>
                    ד"ר אלון אלבר מסביר כיצד יש לנהוג כשיוצאים מהבית.
                </p>
            </div>
            <div class="col-md-6 col-12">
                <img class="img-fluid" src="media/temp/down-graph.jpg">
                <h3>אחוז החולים בישראל יורד!</h3>
                <p>
                    שנת 2019 נגמרה ובישראל נרשמה ירידה משמעותית בחולי סרטן העור.
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid team">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2>הצוות</h2>
            </div>
            <div class="col-md-4 col-12">
                <img class="img-fluid" src="media/team/yogev.jpg">
                <h4>יוגב אגרנוב <i>מנכל</i></h4>
                <p>
                    סטודנט שנה ג' להנדסאי תוכנה,<br>
                    מייסד ומנכ"ל של חברת BetterLife.
                </p>
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-3">
                        <a class="fab fa-github" style="color: #323232;"></a>
                    </div>
                    <div class="col-2">
                        <a class="fab fa-linkedin" style="color: #076BA5;"></a>
                    </div>
                    <div class="col-3">
                        <a class="fab fa-facebook-square" style="color:#385599"></a>
                    </div>
                    <div class="col-2"></div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <img class="img-fluid" src="media/team/yogev.jpg">
                <h4>יוגב אגרנוב <i>מנכל</i></h4>
                <p>
                    סטודנט שנה ג' להנדסאי תוכנה,<br>
                    מייסד ומנכ"ל של חברת BetterLife.
                </p>
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-3">
                        <a class="fab fa-github" style="color: #323232;"></a>
                    </div>
                    <div class="col-2">
                        <a class="fab fa-linkedin" style="color: #076BA5;"></a>
                    </div>
                    <div class="col-3">
                        <a class="fab fa-facebook-square" style="color:#385599"></a>
                    </div>
                    <div class="col-2"></div>
                </div>
            </div>
            <div class="col-md-4 col-12">
                <img class="img-fluid" src="media/team/yogev.jpg">
                <h4>יוגב אגרנוב <i>מנכל</i></h4>
                <p>
                    סטודנט שנה ג' להנדסאי תוכנה,<br>
                    מייסד ומנכ"ל של חברת BetterLife.
                </p>
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-3">
                        <a class="fab fa-github" style="color: #323232;"></a>
                    </div>
                    <div class="col-2">
                        <a class="fab fa-linkedin" style="color: #076BA5;"></a>
                    </div>
                    <div class="col-3">
                        <a class="fab fa-facebook-square" style="color:#385599"></a>
                    </div>
                    <div class="col-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>



 <?php
include "core/templates/footer.php";
