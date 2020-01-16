<?php
/**
 * Created by PhpStorm.
 * User: Yogev
 * Date: 28-Jan-18
 * Time: 11:17
 */

namespace BetterLife\System;

class EmailsConstant
{

    const forgotPassword = <<<ForgotPassword
שלום {userName} <br>
התקבלה בקשה לאיפוס סיסמתך באתר עמותת בוגרי רימון. <br>
לחץ על מנת לבצע איפוס לסיסמה: <a href="https://845.co.il/reset-password.php?reset={forgotLink}">לחץ כאן!</a>
ForgotPassword;

    const userChangingEmail = <<<UserChangingEmail
שלום מנהל, <br>
שים לב! <br>
המשתמש {userName} {userId} החליף סיסמה, נא לוודא שלא מדובר בפריצה למערכת.
UserChangingEmail;

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

    const Email_master = <<<Email_master
<p style="direction: rtl; font-size: 16px">
    נרשם חדש בעמותה! <br>
    נא להכנס ולוודא פרטים לפני אישור. <br>
    <a href="https://845.co.il/manage/confirm-users.php" target="_blank">לחץ כאן לדף אישור משתמשים</a>
</p>
Email_master;

    const User_approve = <<<User_approve
<p style="direction: rtl; font-size: 16px">
    שלום {userName}!<br>
    אנו שמחים שהצטרפת למשפחה! <br>
    מרגע זה ניתן להתחיל להנות מתוכן יעודי לחברי העמותה בלבד!<br>
    אז למה לא להתחיל להנות כבר מעכשיו? <br>
    <a href="https://845.co.il">מעבר לאתר</a>
    <br><br>
    בברכה, <br>
    צוות העמותה.
</p>
User_approve;

}