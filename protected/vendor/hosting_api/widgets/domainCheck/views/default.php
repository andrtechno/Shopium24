

<p class="help-block"><?= Yii::t('DomainCheckWidget.default', 'TEXT'); ?></p>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'domainCheck-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form-horizontal',
    //  'onsubmit' => "return false;", /* Disable normal form submit */
    //  'onkeypress' => " if(event.keyCode == 13){ callbackSend(); } " /* Do ajax call when user presses enter key */
    ),
        ));

if ($model->hasErrors())
    Yii::app()->tpl->alert('danger', $form->error($model, 'name'));
?>
<div class="form-group">
    <div class="col-sm-6">
        <?= $form->label($model, 'name', array('class' => 'sr-only')); ?>
        <?php $this->widget('ext.TagInput',array(
            'model'=>$model,
            'attribute'=>'name',
            'htmlOptions'=>array('style'=>'width:150px'),
            'options'=>array('defaultText'=>$model->getAttributeLabel('name'))
        ));?>
        <?php //echo $form->textField($model, 'name', array('class' => 'form-control', 'placeholder' => 'название домена')); ?>
    </div>
    <div class="form-group">
        <div class="col-sm-6">
            <?= $form->dropDownList($model, 'domain', $selectOptions, array('class' => 'form-control')); ?>
            <?php echo Html::submitButton(Yii::t('DomainCheckWidget.default', 'BUTTON'),array('class'=>'btn btn-info')); ?>
        </div>
    </div>
</div>






<?php $this->endWidget(); ?>
<?php if($checkData){ ?>
<table class="table table-bordered table-striped">
    <tr>
        <th class="text-center">Домен</th>
        <th class="text-center">Состояние</th>
        <th class="text-center">Домен</th>
        <th class="text-center">Комментарий</th>
    </tr>
<?php

foreach($checkData as $d=>$result){ ?>

    

        <tr>
            <td><?=$d?></td>
            <td class="text-center"><?php echo $result[0]->available?'<span class="label label-success">доступен</span>':'<span class="label label-danger">не доступен</span>';?></td>
            <td class="text-center"><?php echo ($result[0]->reason)?'<span class="text-danger">'.$result[0]->reason.'<span>':'---';?></td>
            <td class="text-center"><?php echo $this->getReasonCode($result[0]);?></td>
        </tr>

<?php } ?>
            </table>
<?php } ?>