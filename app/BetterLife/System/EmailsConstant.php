<?php
/**
 * Created by PhpStorm.
 * User: Yogev
 * Date: 28-Jan-18
 * Time: 11:17
 */

namespace BetterLife\System;

class EmailsConstant {


    const emailConfirm = <<<EmailConfirm
<h3>שלום {firstName} </h3>
<p>על מנת להשלים את פעולת הרישום ולהתחיל להינות מכלל השירותים, נא ללחוץ על הלינק מטה</p>
{emailUrl}
EmailConfirm;





    const forgotPassword = <<<ForgotPassword
שלום {userName} <br>
התקבלה בקשה לאיפוס סיסמתך באתר עמותת בוגרי רימון. <br>
לחץ על מנת לבצע איפוס לסיסמה: <a href="https://845.co.il/reset-password.php?reset={forgotLink}">לחץ כאן!</a>
ForgotPassword;


    const Contact_us = <<<Contact_us
<p style="direction: rtl; font-size: 16px">
    <b>שם:</b> {Name} <br>
    <b>דואר אלקטרוני:</b> {Email} <br>
    <b>מספר פלאפון:</b> {PhoneNumber} <br>
    <b>כתובת אי פי:</b> {IpAddress} <br>
    <b>נושא: </b> {Subject} <br>
    <b>תוכן הפנייה:</b> {TextArea} <br>
</p>
Contact_us;


    const User_approve = <<<User_approve
<p style="direction: rtl; font-size: 16px">
    שלום {userName}!<br>
    אנו שמחים שהצטרפת ל-betterlife! <br>
    מרגע זה תוכל להתחיל להשתמש במערכת ולהישאר בריא<br>
    אז למה לא להתחיל להנות כבר מעכשיו? <br>
    <a href="https://betterlife.845.co.il">מעבר לאתר</a>
    <br><br>
    בברכה, <br>
    צוות האתר.
</p>
User_approve;

}