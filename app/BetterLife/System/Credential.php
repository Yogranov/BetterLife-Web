<?php


namespace BetterLife\System;


class Credential
{
    const CREDENTIALS_FILES_ABSOLUTE_PATH = SystemConstant::SYSTEM_LOCAL_ABSOLUTE_PATH."/CredentialFiles";

    private $fileName;
    private $username;
    private $password;

    public function __construct($fileName, $username, $password) {
        $this->fileName = $fileName;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function GetFileName() {
        return $this->fileName;
    }

    /**
     * @return mixed
     */
    public function GetUsername() {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function GetPassword() {
        return $this->password;
    }

    /**
     * @param $Name
     * @param string $credentialFileLoc
     * @return bool|Credential
     */
    public static function GetCredential($Name, string $credentialFileLoc = self::CREDENTIALS_FILES_ABSOLUTE_PATH)
    {
        try {
            $CredentialData = self::getCredentialFile($Name, $credentialFileLoc);
            if ($CredentialData !== false)
            {
                $credential = new Credential($Name, $CredentialData->username, $CredentialData->password);
                return $credential;
            }
        }
        catch (Throwable $e) {
            Services::dump($e->getMessage());
        }
        return False;
    }

    /**
     * @param $filename
     * @param string $credentialFileLoc
     * @return SimpleXMLElement
     * @throws Exception
     */
    private static function getCredentialFile($filename, $credentialFileLoc = self::CREDENTIALS_FILES_ABSOLUTE_PATH)
    {
        $approvedExtFunction = array("xml" => "simplexml_load_file");

        // Check if approved extention //
        unset($fileExtention);
        if (strpos($filename, ".") !== false) {
            $file_parts = pathinfo($filename);
            $fileExtention = $file_parts['extension'];
            if (!array_key_exists($fileExtention, $approvedExtFunction)) {
                throw new \Exception("un-supported credential file extention (".$file_parts['extension'].")!");
            }
        }
        else {
            $fileExtention = "xml";
            $filename .= ".".$fileExtention;
        }

        //Get and read file Data
        unset($ret);
        $loginFile = $credentialFileLoc.'/'.$filename;
        if (is_file($loginFile) && file_exists($loginFile) && is_readable($loginFile)) {
            $ret = call_user_func($approvedExtFunction[$fileExtention], $loginFile);

            if (isset($ret) && !empty($ret))
                return $ret;
        }

        throw new \Exception("unable to resolve filename '".$filename."' data!");
    }
}