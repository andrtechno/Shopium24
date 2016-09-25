<?php
$ip = CMS::ip($data->ip_address);
$userName = ($data->user_login) ? $data->user_login : Yii::t('app', 'CHECKUSER', 0);

$browser = Yii::app()->browser->getName($data->user_agent);
$platform = Yii::app()->browser->getPlatform($data->user_agent);
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title" data-toggle="collapse" data-parent="#accordion" href="#collapse-user-<?= $index ?>">
            <?php echo Html::image($data->user_avatar, $userName, array('class' => 'img-thumbnail2', 'width' => 25)); ?>
            <?php echo $userName; ?>
            <span class="label label-default pull-right"><?= Yii::t('app', 'CHECKUSER', (int) $data->user_type); ?></span>
        </div>
    </div>
    <div id="collapse-user-<?= $index ?>" class="panel-collapse collapse">
        <ul class="list-group">
            <li class="list-group-item">
                <?php if ($data->iconBrowser) { ?>
                    <i class="flaticon-<?= $data->iconBrowser ?>"></i> <?= $data->browserName; ?>
                <?php } else { ?>
                    <b>Браузер:</b> <?= $data->browserName; ?> 
                <?php } ?>
                <span class="label label-default"><?= $data->browserVersion ?></span>
            </li>
            <li class="list-group-item">
                <?php if ($data->iconPlatform) { ?>
                    <i class="flaticon-<?= $data->iconPlatform ?>"></i> <?= $data->platformName; ?>
                <?php } else { ?>
                    <b>Платформа:</b> <?= $data->platformName; ?>
                <?php } ?>

            </li>
            <li class="list-group-item">
                <b>IP:</b> <?= $ip; ?>
            </li>
            <li class="list-group-item">
                <b><?= Yii::t('default', 'ONLINE') ?>:</b> <?= $data->onlineTime ?>
            </li>
            <li class="list-group-item">
                <b>Страница:</b> <?= Html::link($data->current_url, $data->current_url, array('target' => '_blank')) ?>
            </li>
        </ul>
    </div>
</div>
