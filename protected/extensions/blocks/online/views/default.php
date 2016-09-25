
<ul class="list-group">

    <li class="list-group-item">
        <div class="row">
            <div class="col-xs-6"><?= Yii::t('app', 'CHECKUSERS', 0) ?>: <b><?= $online['totals']['guest']; ?></b></div>
            <div class="col-xs-6"><?= Yii::t('app', 'CHECKUSERS', 2) ?>: <b><?= $online['totals']['users']; ?></b></div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="row">
            <div class="col-xs-6"><?= Yii::t('app', 'CHECKUSERS', 3) ?>: <b><?= $online['totals']['admin']; ?></b></div>
            <div class="col-xs-6"><?= Yii::t('app', 'CHECKUSERS', 1) ?>: <b><?= $online['totals']['bot']; ?></b></div>
        </div>
    </li>


    <li class="list-group-item"><?= Yii::t('app', 'TOTAL') ?>: <b class="badge" style="float:none;"><?= $online['totals']['all']; ?></b></li>
</ul>





<div id="accordion" class="panel-group">
    <?php
    $this->widget('ListView', array(
        'dataProvider' => $model->search(),
        'itemView' => '_view',
        'template' => "{items}\n{pager}",
    ));
    ?>
</div>

