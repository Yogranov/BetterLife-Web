<?php
/**
 * Created by PhpStorm.
 * User: Yogev
 * Date: 21-Jan-18
 * Time: 10:21
 */

namespace BetterLife\Enum;


class EUserRoles extends \Enum {

    const NewUser = array(1, "נרשם חדש");
    const Patient = array(2, "מטופל");
    const Doctor = array(3, "רופא");
    const Admin = array(4, "מנהל");

}