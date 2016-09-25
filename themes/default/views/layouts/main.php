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
        <?php $this->renderPartial('//layouts/partials/gallery'); ?>
        <?= $content ?>
        <?php $this->renderPartial('//layouts/partials/footer'); ?>
    </body>
</html>

