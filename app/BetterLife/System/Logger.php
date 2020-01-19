<?php


namespace BetterLife\System;


use BetterLife\BetterLife;

class Logger {

    const tableName = "logs";


    private $message;
    private $status;
    private $timestamp;


    public function __construct($message = "", ...$vars) {
        $this->timestamp = new \DateTime('now', new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));

        for ($i=0; $i < count($vars); $i++)
            $message = str_replace("{".$i."}", $vars[$i], $message);

        $this->message = $message;
    }

    private function checkFields() {
        if(empty($this->message) || empty($this->status) || empty($this->timestamp))
            throw new \Exception("One of the attributes are empty!");
    }


    public function writeToDb() {
        $this->checkFields();
        BetterLife::GetDB()->insert(self::tableName, ["Status" => $this->status, "Log" => $this->message, "Timestamp" => $this->timestamp]);
    }

    public function writeToFile(){
        $this->checkFields();
        $messageFile = fopen(SystemConstant::LOG_PATH . "_" . $this->timestamp->format("Y-m") . ".txt", "a");
        fwrite($messageFile,$this->timestamp->format("Y-m-d H:i:s") . ": " . $this->status . " - " . $this->message . "<br>" . "\n");
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