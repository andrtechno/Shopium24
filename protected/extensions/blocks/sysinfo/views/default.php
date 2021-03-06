<table class="table table-striped">
    <tr>
        <td width="50%">CMS version / Framework: <span class="pull-right"><?= $cms_ver; ?> <?= $yii_ver; ?></span></td>
        <td width="50%">PDO extension: <span class="pull-right"><?= $pdo ?></td>
    </tr>
    <tr>
        <td>PHP version <span class="pull-right"><?= $php ?></span></td>
        <td>OS: <span class="pull-right"><?= $os ?></span></td>
    </tr>
    <tr>
        <td>Upload_max_filesize <span class="pull-right"><?= $u_max ?></span></td>
        <td>Register_globals <span class="pull-right"><?= $globals ?></span></td>
    </tr>
    <tr>
        <td>Memory_limit <span class="pull-right"><?= $m_max ?></span></td>
        <td>Magic_quotes_gpc <span class="pull-right"><?= $magic_quotes ?></span></td>
    </tr>
    <tr>
        <td>Post_max_size <span class="pull-right"><?= $p_max ?></span></td>
        <td>Libery GD <span class="pull-right"><?= $gd ?></span></td>
    </tr>

    <tr>
        <td>Backup dir size <span class="pull-right"><?= $backup_dir_size ?></span></td>
        <td>Uplaods dir size <span class="pull-right"><?= $uploads_dir_size ?></span></td>
    </tr>
    <tr>
        <td>Assets dir size <span class="pull-right"><?= $assets_dir_size ?></span></td>
        <td>Cache dir size <span class="pull-right"><?= $cache_dir_size ?></span></td>
    </tr>
    <tr>
        <td>System TimeZone <span class="pull-right"><?= $timezone ?></span></td>
        <td></td>
    </tr>
</table>




<?= Html::form('', 'post', array('class' => 'form-horizontal')) ?>
<div class="form-group">
    <div class="col-sm-3">
        <label class="control-label">Кэш:</label>
    </div>
    <div class="col-sm-5">

        <?php
        $this->widget('ext.bootstrap.selectinput.SelectInput', array(
            'name' => 'cache_id',
            'value' => '',
            'data' => array(
                'cached_settings' => 'settings',
                'cached_widgets' => 'cached_widgets',
                'url_manager_urls' => 'url_manager_urls'
            ),
            'htmlOptions' => array(
                'empty' => Yii::t('app', 'EMPTY_DROPDOWNLIST', 1)
            )
        ));
        ?>


    </div>
    <div class="col-sm-4">
        <input class="btn btn-sm btn-success pull-right" type="submit" value="<?= Yii::t('app', 'CLEAR'); ?>"> 
    </div>

</div>

<div class="form-group">
    <div class="col-sm-4"><label class="control-label">Активы (/assets):</label></div>
    <div class="col-sm-8">
        <?= Html::hiddenField('clear_assets', 1, array('class' => 'form-control')); ?>
        <input class="btn btn-sm btn-success pull-right" style="margin-left:10px;" type="submit" value="<?= Yii::t('app', 'CLEAR'); ?>"> 
    </div>

</div>
<?= Html::endForm(); ?>
