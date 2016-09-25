<?php

date_default_timezone_set("Europe/Kiev");

if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    
//defined('YII_DEBUG') or define('YII_DEBUG',true);
    $yiic = dirname(__FILE__) . '/../../../../framework/yiic.php';
} else {
    $yiic = dirname(__FILE__) . '/../../../framework/yiic.php';
}

$config = dirname(__FILE__) . '/config/console.php';
require_once($yiic);

//Yii::createConsoleApplication($config)->run();

