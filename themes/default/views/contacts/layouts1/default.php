<?php
$min = YII_DEBUG ? '' : '.min';
$cs = Yii::app()->clientScript;
//$cs->registerCoreScript('jquery');
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile("http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700|Roboto:300,400,500,700&subset=latin,cyrillic");
$cs->registerScriptFile($this->baseAssetsUrl . "/js/application.js");
$cs->registerScriptFile($this->assetsUrl . "/js/bootstrap.min.js");
$cs->registerScriptFile($this->assetsUrl . "/js/bootstrap-hover-dropdown.min.js");
$cs->registerScriptFile($this->assetsUrl . "/js/owl.carousel.min.js");
$cs->registerScriptFile($this->assetsUrl . "/js/echo.min.js");
$cs->registerScriptFile($this->assetsUrl . "/js/jquery.easing-1.3.min.js");
$cs->registerScriptFile($this->assetsUrl . "/js/bootstrap-slider.min.js");
$cs->registerScriptFile($this->assetsUrl . "/js/jquery.rateit.min.js");
$cs->registerScriptFile($this->assetsUrl . "/js/lightbox.min.js");
$cs->registerScriptFile($this->assetsUrl . "/js/bootstrap-select.min.js");
$cs->registerScriptFile($this->assetsUrl . "/js/wow.min.js");
$cs->registerScriptFile($this->assetsUrl . "/js/scripts.js");

$cs->registerCssFile($this->assetsUrl . "/css/bootstrap.min.css");
$cs->registerCssFile($this->assetsUrl . "/css/main.css");
$cs->registerCssFile($this->assetsUrl . "/css/blue.css");
$cs->registerCssFile($this->assetsUrl . "/css/owl.carousel.css");
$cs->registerCssFile($this->assetsUrl . "/css/owl.transitions.css");
//$cs->registerCssFile($this->assetsUrl . "/css/lightbox.css");
$cs->registerCssFile($this->assetsUrl . "/css/animate.min.css");
$cs->registerCssFile($this->assetsUrl . "/css/rateit.css");
$cs->registerCssFile($this->assetsUrl . "/css/bootstrap-select.min.css");
$cs->registerCssFile($this->assetsUrl . "/css/font-awesome.min.css");

$cs->registerCssFile($this->assetsUrl . "/css/ui.css");
$cs->registerCssFile($this->assetsUrl . "/css/engine.css");
Yii::import('mod.shop.ShopModule');
Yii::import('mod.cart.CartModule');
CartModule::registerAssets();


Yii::import('ext.jgrowl.Jgrowl');
Jgrowl::register();
/**
 * Global js vars
 */
$config = Yii::app()->settings->get('shop');
$cs->registerScript('app2', "
cart.spinnerRecount = false;
app.language = 'ru';
app.token = '" . Yii::app()->request->csrfToken . "';
app.debug = true;
app.flashMessage = true;

fp_penny = " . $config['fp_penny'] . ";
fp_separator_thousandth = '" . chr($config['fp_separator_thousandth']) . "';
fp_separator_hundredth = '" . chr($config['fp_separator_hundredth']) . "';
    
", CClientScript::POS_HEAD);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <title><?= Html::encode($this->pageTitle) ?></title>
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <meta name="robots" content="all">
    </head>
    <body class="cnt-home">
        <?php $this->renderPartial('//layouts/partials/header'); ?>
        <div class="body-content outer-top-bd">
            <div class="container">
                <div class="row inner-bottom-sm">
                    <div class="contact-page">
                        <?= $content; ?>
                    </div>
                </div>

            </div>
        </div>
        <?php $this->renderPartial('//layouts/partials/footer'); ?>
    </body>
</html>