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

    <script src="system/fontawesome/js/all.min.js"></script>
    <script src="system/jquery/jquery-3.4.1.min.js"></script>
    <script src="system/bootstrap/js/bootstrap.min.js"></script>
    <script src="system/aos/aos.js"></script>


</head>
<body>
<nav data-aos="fade-down" class="navbar navbar-expand-lg px-lg-5 flex-row-reverse">
    <a class="navbar-brand pl-lg-3" href="#">
        <img src="media/logos/BetterLifeLogoWhite.png" alt="logo">
    </a>
    <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="login.php">כניסה</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="articles/articles.php">מידע</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="doctors.php">הרופאים</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about-us.php">אודות</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contact-us.php">צור קשר</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <div class="container">
        <div class="row title-container">
            <div data-aos="fade-left" class="col-md-6 col-12 title">
                מהיר,<br>
                מדויק,<br>
                מציל חיים!<br>
            </div>
            <div class="col-md-6 col-12">
                <img class="img-fluid login-img" src="media/homepage/medRobot.png">
            </div>
        </div>
    </div>
</div>

<div class="container-fluid simple-stepts">
    <div class="container">
        <div class="row ">
            <div data-aos="fade-up" class="col-12">
                <h2>פשוט!</h2>
            </div>
            <div data-aos="flip-left" class="col-md-4 col-12">
                <h3>שלב א'</h3>
                <hr>
                <h4><i class="fas fa-sign-in-alt"></i> התחברות</h4>
                <p>
                    השלב הראשון הינו התחברות למערכת, במידה ואתה לא רשום תוכל להירשם כאן בכמה דקות בלבד. מיד עם סיום הכניסה ניתן לגשת לדף האישי ושם לבדוק את המצב הרפואי האישי.
                </p>
            </div>
            <div data-aos="flip-left" class="col-md-4 col-12">
                <h3>שלב ב'</h3>
                <hr>
                <h4><i class="fas fa-keyboard"></i> מילוי פרטים</h4>
                <p>
                    בשלב השני עלינו להכנס לפרופיל האישי ולהוסיף שומה חדשה לבדיקה. כשנגיע לטופס עלינו למלא מספר פרטים ולהעלות תמונה ברורה של השומה, כל ההוראות לצילום נמצאות בטופס.
                </p>
            </div>
            <div data-aos="flip-left" class="col-md-4 col-12">
                <h3>שלב ג'</h3>
                <hr>
                <h4><i class="fas fa-poll"></i> תוצאות</h4>
                <p>
                    זהו! סיימנו, עכשיו עלינו להמתין בסבלנות שהמערכת תבצע ניתוח לתמונה. אל דאגה, תקבלו התראה מיד כשיגיעו התוצאות. בנוסף לכך בזמן הקרוב אחד מצוות הרופאים שלנו יבצע בדיקה על בסיס התמונה שצילמתם ויעלה לפרופיל שלכם את חוות הדעת שלו.
                </p>
            </div>
        </div>
        <div data-aos="zoom-in-up" class="row mt-5">
            <div class="col-7 doctorPhone">
                <img class="img-fluid" src="media/homepage/horazionPhone.png">
            </div>
            <div class="offset-md-5">
            </div>
        </div>
    </div>
</div>

<div class="container-fluid info">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <div data-aos="flip-right" class="col-md-4 col-12">
                    <h2>כתבות אחרונות</h2>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="row">
                    <div data-aos="flip-right" class="col-md-6 col-12">
                        <img class="img-fluid" src="media/articles/2.jpg">
                        <h4>ירידה באחוז חולי סרטן העור</h4>
                    </div>
                    <div data-aos="flip-right" class="col-md-6 col-12">
                        <img class="img-fluid" src="media/articles/3.jpg">
                        <h4>התגוננות בפני קרינת השמש</h4>
                    </div>
                    <div data-aos="flip-right" class="col-md-6 col-12">
                        <img class="img-fluid" src="media/articles/4.jpg">
                        <h4>תכשיר חדש מבטיח הגנה מושלמת</h4>
                    </div>
                    <div data-aos="flip-right" class="col-md-6 col-12">
                        <img class="img-fluid" src="media/articles/1.jpg">
                        <h4>הוראות כיצד יש להלבש ביום שמשי</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row team-photo">
            <div class="offset-md-5">
            </div>
            <div data-aos="zoom-in-up" class="col-4 ">
                <img class="img-fluid" src="media/homepage/team.png">
            </div>
            <div class="offset-md-3">
            </div>
        </div>
    </div>
</div>

<div class="container-fluid team">
    <div class="container">
        <div class="row">
            <div class="col-md-5 col-12" data-aos="zoom-in-down">
                <h2>הצוות</h2>
            </div>
            <div class="col-md-7 col-12">
                <div class="row">
                    <div class="col-md-6 col-12" data-aos="zoom-in-up">
                        <img class="img-fluid" src="media/random/yogev.jpg">
                        <h4>יוגב אגרנוב <i>מנכל</i></h4>
                        <p>
                            סטודנט שנה ג' להנדסאי תוכנה,<br>
                            מייסד ומנכ"ל של חברת BetterLife.
                        </p>
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-3">
                                <a class="fab fa-github fa-2x" style="color: #323232;"></a>
                            </div>
                            <div class="col-2">
                                <a class="fab fa-linkedin fa-2x" style="color: #076BA5;"></a>
                            </div>
                            <div class="col-3">
                                <a class="fab fa-facebook-square fa-2x" style="color:#385599"></a>
                            </div>
                            <div class="col-2"></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-12" data-aos="zoom-in-up">
                        <img class="img-fluid" src="media/random/yogev.jpg">
                        <h4>יוגב אגרנוב <i>מנכל</i></h4>
                        <p>
                            סטודנט שנה ג' להנדסאי תוכנה,<br>
                            מייסד ומנכ"ל של חברת BetterLife.
                        </p>
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-3">
                                <a class="fab fa-github fa-2x" style="color: #323232;"></a>
                            </div>
                            <div class="col-2">
                                <a class="fab fa-linkedin fa-2x" style="color: #076BA5;"></a>
                            </div>
                            <div class="col-3">
                                <a class="fab fa-facebook-square fa-2x" style="color:#385599"></a>
                            </div>
                            <div class="col-2"></div>
                        </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>


 <?php
include "core/templates/footer.php";
