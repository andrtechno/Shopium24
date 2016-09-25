<?php

//Timezone
date_default_timezone_set("UTC");

defined('DS') or define('DS', DIRECTORY_SEPARATOR);
$webRoot = dirname(__FILE__);


if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
    $yii = $webRoot.'/../../framework/yii.php';
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    $config = $webRoot . '/protected/config/dev.php';
} else {
    $yii = $webRoot.'/../../framework/yiilite.php';
    $config = $webRoot . '/protected/config/main.php';
}
if(!file_exists($yii))
  die('Please install the framework in the root directory.');

require_once $yii;
require_once 'protected/components/Application.php';

// Create application
Yii::createApplication('Application', $config)->run();

