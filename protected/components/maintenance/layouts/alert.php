<?php
$min = YII_DEBUG ? '' : '.min';

$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery');
$cs->registerScriptFile($this->baseAssetsUrl . "/js/bootstrap{$min}.js");
$cs->registerCssFile($this->baseAssetsUrl . "/css/bootstrap{$min}.css");
$cs->registerCssFile($this->baseAssetsUrl . "/css/flaticon.css");
?>
<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= Html::encode(Yii::app()->settings->get('app', 'site_name')) ?></title>
        <style type="text/css">
            body {
                background-color: #eee;
                margin:0;
                padding:0;
            }
            .alert {
                max-width: 500px;
                margin: 0 auto;
                padding: 15px 15px 15px 45px;
                position: relative;
                border-radius: 0;
            }
            .alert-info:before,
            .alert-success:before,
            .alert-danger:before,
            .alert-warning:before{
                font-family: Flaticon;
                font-style: normal;
                line-height: 1;
                left:13px;
                top:13px;
                position: absolute;
                font-size:22px;
            }
            .alert-danger:before{
                content: "\e0d7";
            }
            .alert-warning:before{
                content: "\e0d6";
            }
            .alert-success:before{
                content: "\e01c";
            }
            .alert-info:before{
                content: "\e060";
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="alert alert-danger">
                <?= $content; ?>
            </div>

        </div>
        <script>
            function height() {
                var height = $(window).height();
                var aheight = $('.alert').height();
                $('.alert').css({'margin-top': height / 2 - aheight});
            }
            $(window).ready(function () {
                height();
            });
            $(window).resize(function () {
                height();
            });
        </script>
    </body>
</html>