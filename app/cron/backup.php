<?php

use BetterLife\System\Credential;
use BetterLife\System\SystemConstant;

$returnVar = NULL;
$output  = NULL;
$SqlCredential = Credential::GetCredential('sql_' . SystemConstant::MYSQL_SERVER . '_' . SystemConstant::MYSQL_SERVER_PORT . '_' . SystemConstant::MYSQL_DATABASE);

$filename = "backup-" . date('Y-m-d') . ".gz";
$command = "mysqldump --user=" . $SqlCredential->GetUsername() ." --password='" . $SqlCredential->GetPassword() . "' --host=" . SystemConstant::MYSQL_SERVER . " " . SystemConstant::MYSQL_DATABASE . "  | gzip > " . "../DbBackup/" . $filename;
exec($command, $output, $returnVar);

$deleteOldFiles = "find " . "../DbBackup/ -type f -mtime +" . \BetterLife\System\SystemConstant::DB_BACKUP_DAYS . " -exec rm -f {} \;";
exec($deleteOldFiles, $output, $returnVar);