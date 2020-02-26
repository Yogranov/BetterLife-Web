<?php
namespace BetterLife\System;

use BetterLife\BetterLife;

class Logger {

    const tableName = "logs";


    private $message;
    private $status;
    private $userId;
    private $timestamp;


    public function __construct($userId = null, $message = "", ...$vars) {
        $this->timestamp = new \DateTime('now', new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));

        for ($i=0; $i < count($vars); $i++)
            $message = str_replace("{".$i."}", $vars[$i], $message);

        $this->userId = $userId;
        $this->message = $message;
    }

    private function checkFields() {
        if(empty($this->message) || empty($this->status) || empty($this->timestamp))
            throw new \Exception("One of the attributes are empty!");
    }


    public function writeToDb() {
        $this->checkFields();
        $data = array(
            "Status" => $this->status,
            "UserId" => $this->userId,
            "Log" => $this->message,
            "Timestamp" => $this->timestamp->format("Y-m-d H:i:s")
        );
        BetterLife::GetDB()->insert(self::tableName, $data);
    }

    public function writeToFile(){
        $this->checkFields();
        $messageFile = fopen(SystemConstant::LOG_PATH . $this->timestamp->format("Y-m") . ".txt", "a");
        fwrite($messageFile,$this->timestamp->format("Y-m-d H:i:s") . ": " . $this->status . "UserId = ". $this->userId ." - " . $this->message . "\n");
        fclose($messageFile);
    }

    public function debug(){
        $this->status = "DEBUG";
    }

    public function info(){
        $this->status = "INFO";
    }

    public function notice(){
        $this->status = "NOTICE";
    }

    public function warning(){
        $this->status = "WARNING";
    }

    public function error(){
        $this->status = "ERROR";
    }

    //todo: add email notifications
    public function critical(){
        $this->status = "CRITICAL";
    }

    public function alert(){
        $this->status = "ALERT";
    }

    public function emergency(){
        $this->status = "EMERGENCY";
    }

}