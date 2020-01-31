<?php
require_once '../../vendor/autoload.php';

use BetterLife\BetterLife;
BetterLife::GetDB()->delete("loginAttempts");