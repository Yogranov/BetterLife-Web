<?php


namespace BetterLife\System;


class Services
{
    /**
     * @param $var
     * @param bool $echo
     * @return mixed|string
     */
    public static function dump($var, $echo = TRUE) {
        ob_start();
        var_dump($var);

        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", ob_get_clean());

        if(PHP_SAPI == "cli") {
            $output = PHP_EOL . PHP_EOL . $output . PHP_EOL;
        }
        else {
            $output = "<pre dir='ltr'>" . htmlspecialchars($output, ENT_QUOTES, "utf-8") . "</pre>";
        }

        if($echo) echo($output);

        return $output;
    }

    /**
     * @return bool
     */
    public static function isMobile() {
        if (class_exists("Mobile_Detect")) {
            //using mobile detect class
            $mobileDetect = new Mobile_Detect();
            return $mobileDetect->isMobile();
        }
        else {
            //inner check
            if (preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]))
                return true;
        }
        return false;
    }

    public static function RedirectHome(){
        header("Location: " . SystemConstant::SYSTEM_DOMAIN);
    }

    public static function flashUser(string $message) {
        $_SESSION[SystemConstant::FLASH_MESSAGE] = $message;
        header('Location: ' . SystemConstant::SYSTEM_FLASH);
        exit();
    }

    public static function redirectUser(string  $location) {
        header('Location: ' . $location);
        exit();
    }


    /**
     * @param $password
     * @return array
     */
    public static function PasswordStrengthCheck($password){
        $errors = array();
        $errors_init = $errors;

        if (strlen($password) < 8)
            array_push($errors,"Password too short!");

        if (!preg_match("#[0-9]+#", $password))
            array_push($errors,"Password must include at least one number!");

        if (!preg_match("#[a-z]+#", $password))
            array_push($errors,"Password must include at least one letter!");

        if (!preg_match("#[A-Z]+#", $password))
            array_push($errors,"Password must include at least one capital letter!");

        return $errors;
    }

    public static function GenerateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function validateID(string $str)  {
        $count = 0;
        //Validate the ID number
        for($i=0; $i<8; $i++)  {
            $char = mb_substr($str, $i, 1);
            $incNum = intval($char);
            $incNum*=($i%2)+1;
            if($incNum > 9)
                $incNum-=9;
            $count+= $incNum;
        }

        if($count%10==0)
            return true;
        else
            return false;
    }

    public static function validatePhoneNumber(string $number) {

        if((strlen($number) != 10))
            return false;

        if(!preg_match("/^05\d([-]{0,1})\d{7}$/", $number))
            return false;

        return true;
    }

    public static function getClientIp() {
        $ipAddress = '';

        if (getenv('HTTP_CLIENT_IP'))
            $ipAddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipAddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipAddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipAddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
            $ipAddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipAddress = getenv('REMOTE_ADDR');
        else
            $ipAddress = false;

        return $ipAddress;
    }

    public static function sendPostRequest($url, $postVars = array()){
        $postStr = http_build_query($postVars);
        $options = array(
            'http' =>
                array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postStr
                )
        );
        $streamContext  = stream_context_create($options);

        $result = file_get_contents($url, false, $streamContext);
        if($result === false){
            $error = error_get_last();
            throw new \Exception('POST request failed: ' . $error['message']);
        }
        return $result;
    }


    /**
     * @param $array
     * @param $column
     * @return array
     */
    public static function ArrayColumn($array, $column) {
        if(!function_exists("array_column")) {
            return array_map(function($element) use($column){return $element[$column];}, $array);
        }
        else {
            return array_column($array, $column);
        }
    }

    /**
     * @param $total
     * @param bool $ArrayImplode
     * @return array|bool|string
     */
    public static function RandChars($total, $ArrayImplode=true) {
        if (is_numeric($total) && $total > 0) {
            $ret=Array();
            for($i=0; $i<$total; $i++) {
                $rand_int=rand(35,126);
                $ret[]=chr($rand_int);
            }

            if (is_array($ret) && count($ret)>0) {
                $ArrayImplode ? $ret=implode('',$ret) : false;
                return $ret;
            }
        }

        return false;
    }

    /**
     * @param string|null $multi
     * @param string $delimiter
     * @return array
     */
    public static function MultiToArray(string $multi = null, $delimiter = ",") {
        if (empty($multi) || $multi == "")
            return array();

        return @explode($delimiter, $multi);
    }

    /**
     * @param array $array
     * @param string $delimiter
     * @return string
     */
    public static function ArrayToMulti(array $array, $delimiter = ",") {
        if(empty($array) || count($array) == 0)
            return "";

        return @implode($delimiter,$array);
    }

    /**
     * @param \DateTime $date1
     * @param string|\DateTime $date2
     * @param string $format
     * @return string
     */
    public static function DateDiff(DateTime $date1, $date2 = "now", string $format = "%y שנים ו-%m חודשים") {
        if (!($date2 instanceof \DateTime))
            $date2 = new \DateTime($date2);

        if (empty($format))
            $format = "%y שנים ו-%m חודשים";

        return $date1->diff($date2)->format($format);
    }

    /**
     * @param string $format
     * @return string
     */
    public static function DateNow($format = "DATE_W3C") {
        $now = new \DateTime();
        return $now->format($format);
    }

    /**
     * @param $str
     * @param $name
     * @param $value
     */
    public static function setPlaceHolder(&$str, $name, $value) {
        $str = str_replace("{".$name."}", $value, $str);
    }

    /**
     * @param string $Directory
     * @return mixed
     */
    public static function GetBaseDir($Directory='[a-z]+.php')
    {
        //var_dump($_SERVER);
        $Directory=str_replace('.','\.',$Directory);
        $Directory=str_replace('/','\/',$Directory);
        $pattern = '/'.$Directory.'[a-z0-9\/\.]*/i';
        $url=$_SERVER['SCRIPT_FILENAME'];
        $baseDir=@preg_replace($pattern,'',$url) . $Directory . '/';
        //var_dump($baseDir);

        return $baseDir;
    }

    /**
     * @param $dataToCheck
     * @return bool
     */
    public static function is_serialized($dataToCheck) {
        $data = @unserialize($dataToCheck);
        if ($dataToCheck === 'b:0;' || $data !== false) {
            return true;
        } else {
            return false;
        }
    }
}