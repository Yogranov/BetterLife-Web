<?php


namespace BetterLife\System;


class Log {
    public static function NewLog(string $text) {
        $time = new \DateTime('now', new \DateTimeZone(SystemConstant::SYSTEM_TIMEZONE));
        $logFile = fopen(__DIR__ . "/../manage/logs/" . SystemConstant::SYSTEM_NAME . "-" . $time->format("Y-m") . ".php", "a");
        fwrite($logFile,$time->format("Y-m-d H:i:s") . ": " . $text . "<br>" . "\n");
        fclose($logFile);
    }
}