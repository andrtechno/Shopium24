
<div class="widget fluid">
    <div class="whead"><h6><?php echo Yii::t('UsersModule.default', 'MODULE_NAME') ?></h6><div class="clear"></div></div>

    <?php
    $this->widget('ext.adminList.GridView', array(
        'dataProvider' => $dataProvider,
        'selectableRows' => false,
        'columns' => array(
            array(
                'type' => 'raw',
                'name' => 'from_user',
                'value' => '$data->userFrom->username'
            ),
            array(
                'name' => 'to_user',
                'type' => 'raw',
                'value' => '$data->userTo->username',
            ),
            'text',
            array(
                'type' => 'raw',
                'name' => 'date_create',
                'value' => 'CMS::date("$data->date_create")'
            ),
            array(
                'class' => 'ButtonColumn',
                'header' => Yii::t('app', 'OPTIONS'),
                'template' => '{update}{delete}',
            ),
        ),
    ));
    ?>
</div>
