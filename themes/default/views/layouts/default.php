<?php $this->renderPartial('//layouts/partials/_cs'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <?php if (Yii::app()->hasModule('seo')) { ?>
            <?php Yii::app()->seo->run(); ?>
        <?php } else { ?>
            <title><?= Html::encode($this->pageTitle) ?></title>
        <?php } ?>
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <meta name="robots" content="all">
    </head>
    <body>
        <?php $this->renderPartial('//layouts/partials/header'); ?>
        <div class="container-fluid container-breadcrumb">
         
                <div class="container">
                       <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <?php
                    $this->widget('Breadcrumbs', array(
                        'links' => $this->breadcrumbs,
                        'htmlOptions' => array('class' => 'breadcrumb')
                    ));
                    ?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h1 class="text-right"><?= $this->pageName ?></h1>
                </div>
  </div>
            </div>
        </div>
        <div class="container-fluid bg-white" style="margin-bottom:100px">
            <div class="row">
                <div class="container">
                    <div class="row">
                        <?= $content ?>
                    </div>
                </div>

            </div>
        </div>
        <?php $this->renderPartial('//layouts/partials/footer'); ?>

    </body>
</html>