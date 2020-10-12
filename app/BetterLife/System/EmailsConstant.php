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

    const contactUs = <<<ContactUs
<h3>פנייה חדשה</h3>
<p>
    מאת: {name} <br>
    טלפון: {phone} <br>
    דוא"ל: {email} <br>
    נושא: {subject} <br>
    תוכן הפנייה: <br>
    {content} <br>
</p>
ContactUs;

    const checkingReminder = <<<checkingReminder
<h3>שלום {firstName} </h3>
<p>לא בוצעה אף בדיקה בחצי שנה האחרונה, אנו ממליצים לבצע מעקב ממושך על מנת להבטיח שמירה על הבריאות</p>
<br>
<p>בברכה, <br>
צוות האתר</p>
checkingReminder;


    const forgotPassword = <<<ForgotPassword
שלום {userName} <br>
התקבלה בקשה לאיפוס סיסמתך באתר betterlife. <br>
לחץ על מנת לבצע איפוס לסיסמה: <a href="https://betterlife.845.co.il/reset-password.php?reset={forgotLink}">לחץ כאן!</a>
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

    const Mole_checked_by_doctor = <<<Mole_checked_by_doctor
<p style="direction: rtl; font-size: 16px">
    שלום {userName}!<br>
    <p>
        אנו רוצים לידע אותך כי השומה שהעלת לבדיקה נבדקה על ידי רופא.
    </p>
    <br><br>
    בברכה, <br>
    צוות האתר.
</p>
Mole_checked_by_doctor;

    const Mole_checked_by_ai = <<<Mole_checked_by_ai
<p style="direction: rtl; font-size: 16px">
    שלום {userName}!<br>
    <p>
        אנו רוצים לידע אותך כי הסתיימה בדיקת המחשב עבור השומה שהועלתה למערכת.
    </p>
    <br><br>
    בברכה, <br>
    צוות האתר.
</p>
Mole_checked_by_ai;

}