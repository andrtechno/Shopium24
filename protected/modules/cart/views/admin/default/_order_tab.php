<style type="text/css">
    div.userData input[type=text] {
        width: 385px;
    }
    div.userData textarea {
        width: 385px;
    }
    #orderedProducts {
        padding: 0 0 5px 0;
    }
    .ui-dialog .ui-dialog-content {
        padding: 0;
    }
    #dialog-modal .grid-view {
        padding: 0;
    }
    #orderSummaryTable tr td {
        padding: 3px;
    }
</style>

<div class="fluid">
    <?php
    if ($model->isNewRecord)
        $action = 'create';
    else
        $action = 'update';
    echo CHtml::form($this->createUrl($action, array('id' => $model->id)), 'post', array('id' => 'orderUpdateForm'));

    if ($model->hasErrors())
        echo CHtml::errorSummary($model);
    ?>



                <div class="formRow">
                    <div class="grid4"><?php echo CHtml::activeLabel($model, 'status_id'); ?></div>
                    <div class="grid8"><?php echo CHtml::activeDropDownList($model, 'status_id', CHtml::listData($statuses, 'id', 'name')); ?></div>
                    <div class="clear"></div>
                </div>

    <div class="formRow">
                    <div class="grid4"><?php echo CHtml::activeLabel($model, 'payment_id'); ?></div>
                    <div class="grid8"><?php echo CHtml::activeDropDownList($model, 'payment_id', CHtml::listData($paymentMethods, 'id', 'name')); ?></div>
                    <div class="clear"></div>
                </div>
                <div class="formRow">
                    <div class="grid4"><?php echo CHtml::activeLabel($model, 'paid'); ?></div>
                    <div class="grid8"><?php echo CHtml::activeCheckBox($model, 'paid'); ?></div>
                    <div class="clear"></div>
                </div>


    <div class="formRow buttons textC noBorderB">
<?php echo CHtml::submitButton(($model->isNewRecord) ? Yii::t('core', 'CREATE', 1) : Yii::t('core', 'SAVE'),array('class'=>'buttonS bGreen')); ?>

                </div>


<?php echo CHtml::endForm(); ?>
</div>