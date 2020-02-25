<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "core/templates/header.php";


use BetterLife\System\Services;
use BetterLife\System\SystemConstant;
use BetterLife\User\Role;
use BetterLife\User\User;
use BetterLife\User\Session;
use BetterLife\Article\Article;

$menu = '<ul class="mr-auto navbar-nav">
            <a class="nav-link" href="login.php">כניסה</a>    
         </ul>';


if(Session::checkUserSession()){
    $userObj = User::GetUserFromSession();

    if(!$userObj->checkNewUser()) {
        $menu = MemberMenu;
        Services::setPlaceHolder($menu, "userFirstName", $userObj->getFirstName());
        $tmpSubMenu = "";
        foreach ($userObj->getRoles() as $role) {
            switch ($role->getId()){
                case Role::PATIENT_ID:
                    $tmpSubMenu .= PatientMenu;
                    break;

                case Role::DOCTOR_ID:
                    $tmpSubMenu .= DoctorMenu;
                    break;

                case Role::CONTENT_WRITER:
                    $tmpSubMenu .= ContentWriterMenu;
                    break;

                case Role::ADMIN_ID:
                    $tmpSubMenu .= AdminMenu;
                    break;

                default:
                    $tmpSubMenu .= "";
                    break;
            }
        }
        Services::setPlaceHolder($menu, "MemberMenu", $tmpSubMenu);
    } else {
        unset($_SESSION[SystemConstant::USER_SESSION_NAME]);
        Services::flashUser("משתמש לא מאומת");
    }
}


$lastArticles = Article::getLastArticles(4);
$articlesRow = "";
foreach ($lastArticles as $article) {

    $articlesRow .= <<<tmp
            <div data-aos="flip-right" class="col-md-6 col-12">
                <a style="color: #4e4e4e" href="articles/article.php?Article={$article->getId()}" class="hover-fade"><img class="img-fluid" src="media/articles/{$article->getImgUrl()}">
                <h4>{$article->getTitle()}</h4></a>
            </div>
tmp;

}


$homepage = <<<homepage

    <link rel="stylesheet" href="core/css/homepage.css">

<nav data-aos="fade-down" class="navbar navbar-expand-lg px-lg-5 flex-row-reverse">
    <a class="navbar-brand pl-lg-3" href="#">
        <img src="media/logos/BetterLifeLogoWhite.png" alt="logo">
    </a>
    <button class="navbar-toggler navbar-dark" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="articles/articles.php">מידע</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="doctors.php">הרופאים</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="https://betterlife.845.co.il/do-it-yourself.php">בדיקה עצמאית</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about-us.php">אודות</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contact-us.php">צור קשר</a>
            </li>
        </ul>
                {profile-menu}
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

                    {$articlesRow}
                    
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
                                <a class="fab fa-github fa-2x hover-fade" style="color: #323232;"></a>
                            </div>
                            <div class="col-2">
                                <a class="fab fa-linkedin fa-2x hover-fade" style="color: #076BA5;"></a>
                            </div>
                            <div class="col-3">
                                <a class="fab fa-facebook-square fa-2x hover-fade" style="color:#385599"></a>
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
                                <a class="fab fa-github fa-2x hover-fade" style="color: #323232;"></a>
                            </div>
                            <div class="col-2">
                                <a class="fab fa-linkedin fa-2x hover-fade" style="color: #076BA5;"></a>
                            </div>
                            <div class="col-3">
                                <a class="fab fa-facebook-square fa-2x hover-fade" style="color:#385599"></a>
                            </div>
                            <div class="col-2"></div>
                        </div>
                </div>
            </div>

        </div>
    </div>
</div>
</div>
homepage;



Services::setPlaceHolder($homepage, "profile-menu", $menu);
echo  $homepage;
include "core/templates/footer.php";
