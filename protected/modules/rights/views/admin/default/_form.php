<div class="form">

    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'htmlOptions' => array('class' => 'form-horizontal')
    ));
    ?>

    <div class="form-group">
        <div class="col-sm-4">
            <?php
            $this->widget('ext.bootstrap.selectinput.SelectInput', array(
                'model' => $model,
                'attribute' => 'itemname',
                'data' => $itemnameSelectOptions
            ));
            ?>
        </div>
        <div class="col-sm-8"><?= $form->error($model, 'itemname'); ?></div>
    </div>

    <div class="form-group text-center">
<?php echo Html::submitButton(Rights::t('default', 'Назначать'), array('class' => 'btn btn-success')); ?>
    </div>

<?php $this->endWidget(); ?>

</div>