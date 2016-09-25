
<?php $form = $this->beginWidget('CActiveForm', array('htmlOptions' => array('form-horizontal'))); ?>
    <div class="form-group">
<div class="col-sm-8">
                <?php
            $this->widget('ext.bootstrap.selectinput.SelectInput', array(
                'model' => $model,
                'attribute' => 'itemname',
                'data' => $itemnameSelectOptions
            ));
            ?>

    <?php echo $form->error($model, 'itemname'); ?>
</div>
        </div>
<div class="form-group text-center">
    <?php echo CHtml::submitButton(Yii::t('app', 'CREATE', 0), array('class' => 'btn btn-success')); ?>
</div>

<?php $this->endWidget(); ?>

