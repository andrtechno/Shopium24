<?php
$min = YII_DEBUG ? '' : '.min';
$adminAssetsUrl = Yii::app()->getModule('admin')->assetsUrl;
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerScriptFile($this->baseAssetsUrl . "/js/common.js");
$cs->registerCssFile($this->baseAssetsUrl . "/css/bootstrap{$min}.css");
$cs->registerCssFile($this->baseAssetsUrl . "/css/bootstrap-theme{$min}.css");
$cs->registerCssFile($this->baseAssetsUrl . '/css/flaticon.css');
$cs->registerCssFile($adminAssetsUrl . '/css/dashboard.css');
$cs->registerCssFile($adminAssetsUrl . '/css/login.css');

?>
<!doctype html> 
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::app()->charset ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
        <title><?= Yii::t('app', 'ADMIN_PANEL', array('{sitename}' => Yii::app()->settings->get('app', 'site_name'))) ?></title>
    </head>
    <body class="no-radius">
        <?php echo $content; ?>
    </body>
</html>
