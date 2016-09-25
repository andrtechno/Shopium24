<?php
$assetsUrl = Yii::app()->getModule('admin')->assetsUrl;

$posJsFile = CClientScript::POS_HEAD;

$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
//$cs->registerCoreScript('jquery.ui');
//$cs->scriptMap = array('jquery-ui.css' => false);
$cs->registerCoreScript('cookie');
$cs->registerCoreScript('maskedinput');

// Jquery UI
$cs->registerScriptFile($assetsUrl . "/js/jquery-ui.min.js");
$cs->registerCssFile($assetsUrl . "/css/jquery-ui.min.css");
$cs->registerCssFile($assetsUrl . "/css/jquery-ui.theme.min.css");
$cs->registerCssFile($assetsUrl . "/css/ui.css");

$cs->registerScriptFile($this->baseAssetsUrl . "/js/common.js");
$cs->registerScriptFile($this->baseAssetsUrl . "/js/bootstrap.min.js");
$cs->registerScriptFile($this->baseAssetsUrl . "/js/jquery.dialogOptions.js");



$cs->registerCssFile($this->baseAssetsUrl . "/css/bootstrap.min.css");
$cs->registerCssFile($this->baseAssetsUrl . '/css/bootstrap-theme.min.css');
$cs->registerCssFile($assetsUrl . '/css/dashboard.css');
$cs->registerCssFile($assetsUrl . '/css/breadcrumbs.css');
$cs->registerCssFile($this->baseAssetsUrl . '/css/flaticon.css');
//$cs->registerCssFile($assetsUrl . '/css/bootstrap-select.min.css');
// jGrowl
//Yii::import('ext.jgrowl.Jgrowl');
//Jgrowl::register();
Yii::import('ext.notify.Notify');
Notify::register();

$cs->registerScriptFile($assetsUrl . '/js/init.masks.js');
//$cs->registerScriptFile($assetsUrl . '/js/bootstrap-select.min.js');

$cs->registerScriptFile($assetsUrl . '/js/jquery.collapsible.min.js');
if (Yii::app()->language == Yii::app()->languageManager->default->code) {
    $cs->registerScriptFile($assetsUrl . '/js/translitter.js');
    $cs->registerScriptFile($assetsUrl . '/js/init_translitter.js');
}
$cs->registerScriptFile($assetsUrl . '/js/dashboard.js');
if (!YII_DEBUG && Yii::app()->hasModule('cart'))
    $cs->registerScriptFile($assetsUrl . '/js/counters.js');