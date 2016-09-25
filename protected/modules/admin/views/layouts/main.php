<?php
$assetsUrl = Yii::app()->getModule('admin')->assetsUrl;
$asm = $this->module->adminSidebarMenu;

$this->renderPartial('mod.admin.views.layouts.inc._cs', array(
    'assetsUrl' => $assetsUrl,
    'baseAssetsUrl' => $this->baseAssetsUrl
));
?>
<!DOCTYPE html>
<html lang="<?= Yii::app()->language ?>">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title><?= Yii::t('app', 'ADMIN_PANEL', array('{sitename}' => Yii::app()->settings->get('app', 'site_name'))) ?></title>
        <link rel="shortcut icon" href="<?= $assetsUrl; ?>/images/favicon.ico" type="image/x-icon">
    </head>
    <body class="no-radius">
        <nav class="navbar navbar-inverse navbar-fixed-top">
            <div class="container-fluid" style="position: relative;">
                <div class="navbar-header">

                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <a class="navbar-brand" href="/admin"><span></span></a>


                </div>
                <div id="navbar" class="navbar-collapse collapse">
                    <?php $this->widget('ext.mbmenu.AdminMenu'); ?>
                </div>
                <ul class="navbar-right">
                    <li><?= Html::link('<i class="flaticon-home"></i>', '/', array('target' => '_blank')) ?></li>
                    <li><?= Html::link('<i class="flaticon-locked-2"></i> ', array('/users/logout')) ?></li>
                    <?php if (Yii::app()->settings->get('app', 'multi_language')) { ?>
                        <li><?php $this->widget('ext.blocks.chooseLanguage.ChooseLanguage', array('skin' => 'dropdown')); ?></li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
        <?php
        $class = '';
        $class .= (!$asm) ? ' full-page' : '';
        if (isset($_COOKIE['wrapper'])) {
            $class .= ($_COOKIE['wrapper'] == 'true') ? ' active' : '';
        }
        ?>
        <div id="wrapper" class="<?= $class ?>">
            <?php if ($asm) { ?>
                <div id="sidebar-wrapper">

                    <?php
                    $this->widget('mod.admin.components.AdminModuleMenu', array(
                        'htmlOptions' => array('class' => 'sidebar-nav', 'id' => 'menu'),
                        'activeCssClass' => 'active',
                        'lastItemCssClass' => '',
                        'items' => CMap::mergeArray(array(array(
                                'label' => '',
                                'url' => '#',
                                'encodeLabel' => false,
                                'icon' => 'flaticon-menu',
                                'linkOptions' => array('id' => 'menu-toggle')
                            )), $asm)
                    ));
                    ?>

                </div>
            <?php } ?>

            <div id="page-content-wrapper">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 module-header pdd">
                            <div class="pull-left">
                                <h1>
                                    <i class="<?= $this->module->icon ?>"></i>
                                    <?= Html::encode($this->pageName) ?>
                                </h1>
                            </div>

                            <div class="pull-right">
                                <?php $this->renderPartial('mod.admin.views.layouts.inc._topButtons', array()); ?>
                            </div>

                        </div>
                        <div class="clearfix"></div>
                        <?php $this->renderPartial('mod.admin.views.layouts.inc._breadcrumbs', array()); ?>

                        <div class="pdd">
                            <div class="row">
                                <div class="col-lg-12">
                                    <?php
                                    if (Yii::app()->user->hasFlash('error')) {
                                        Yii::app()->tpl->alert('danger', Yii::app()->user->getFlash('error'), false);
                                    }
                                    if (Yii::app()->user->hasFlash('success')) {
                                        Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'), false);
                                    }
                                    ?>
                                    <?= $content ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <?php
        if (($messages = Yii::app()->user->getFlash('messages'))) {
            echo '<script type="text/javascript">';
            foreach ($messages as $m) {
                echo "common.notify('" . $m . "', 'success');";
                // echo "$.jGrowl('" . $m . "',{position:'bottom-left'});";
            }
            echo '</script>';
        }

        if (($messages = Yii::app()->user->getFlash('notify'))) {
            echo '<script type="text/javascript">';
            foreach ($messages as $type => $text) {
                echo "common.notify('{$text}', '{$type}');";
            }
            echo '</script>';
        }
        ?>
        <footer class="footer">
            <p class="col-xs-12">
                {copyright}
                <br/>
                <?php
                if (YII_DEBUG && false)
                    echo $this->getPageGen();
                ?>
            </p>
        </footer>
    </body>
</html>
