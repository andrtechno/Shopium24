<?php
$ip = Yii::app()->request->getUserHostAddress();
$geoip = Yii::app()->geoip;
$code = $geoip->lookupCountryCode($ip);
$name = $geoip->lookupCountryName($ip);
?>

<table class="table table-striped">
    <tr>
        <td width="50%"><i class="flaticon-user"></i> <?= Yii::app()->user->getName() . ' ' . CMS::ip($ip); ?></td>
        <td width="50%"><i class="flaticon-<?= $browserIcon ?>"></i>  <?= $browser->getBrowser() ?> <span class="pull-right label label-default"><?= $browser->getVersion() ?></span></td>
    </tr>
    <tr>
        <td><i class="flaticon-ip4"></i> <?= $ip . ' ' . $name . '(' . $code . ')'; ?></td>
        <td><i class="flaticon-<?= $platformIcon ?>"></i> <?= $browser->getPlatform() ?></td>
    </tr>
    <tr>
        <td><?=Yii::t('UsersModule.User','TIMEZONE')?> <span class="pull-right label label-default"><?= Yii::app()->controller->timezone ?></span></td>
        <td><?=Yii::t('UsersModule.User','LAST_LOGIN')?> <span class="pull-right label label-default"><?= Yii::app()->user->last_login ?></span></td>
    </tr>

</table>
