<?php


if (!isset($result->hasError)) {
    ?>
    <div class="col-sm-6">
        <h1><?= $result['name'] ?>, <?= $result['sys']['country'] ?></h1>
    </div>
    <div class="col-sm-6">
        <h1><img src="<?= $this->assetsUrl ?>/images/<?= $result['weather'][0]['icon'] ?>.png" alt="" /> <?= floor($result['main']['temp']) ?><?=$this->deg?> <small><?= $result['weather'][0]['description'] ?></small></h1>

    </div>
    <table class="table table-striped">
        <?php if ($this->config['enable_wind']) { ?>
            <tr>
                <td><?= Yii::t('OpenWeatherMapWidget.default', 'WIND') ?></td>
                <td><?= $result['wind']['speed']; ?> м/с,
                    <?= $this->degToCompassImage($result['wind']['deg']); ?>
                    <?= $this->degToCompass($result['wind']['deg']); ?>
                </td>
            </tr>
        <?php } ?>
        <?php if ($this->config['enable_pressure']) { ?>
            <tr>
                <td><?= Yii::t('OpenWeatherMapWidget.default', 'PRESSURE') ?></td>
                <td><?= $result['main']['pressure'] ?> гПа</td>
            </tr>
        <?php } ?>
        <?php if ($this->config['enable_humidity']) { ?>
            <tr>
                <td><?= Yii::t('OpenWeatherMapWidget.default', 'HUMIDITY') ?></td>
                <td><?= $result['main']['humidity'] ?>%</td>
            </tr>
        <?php } ?>
        <?php if ($this->config['enable_sunrise']) { ?>
            <tr>
                <td><?= Yii::t('OpenWeatherMapWidget.default', 'SUNRISE') ?></td>
                <td><?= date('H:s', $result['sys']['sunrise']) ?></td>
            </tr>
        <?php } ?>
        <?php if ($this->config['enable_sunset']) { ?>
            <tr>
                <td><?= Yii::t('OpenWeatherMapWidget.default', 'SUNSET') ?></td>
                <td><?= date('H:s', $result['sys']['sunset']) ?></td>
            </tr>
        <?php } ?>
    </table>
<?php } else { ?>
    <div class="alert alert-warning">
        <?php echo $result->message; ?>
    </div>
<?php } ?>
