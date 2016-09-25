
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'user-change_password-form',
    'htmlOptions' => array('class' => 'form-horizontal'))
);

if ($changePasswordForm->hasErrors())
    Yii::app()->tpl->alert('danger', Html::errorSummary($changePasswordForm));
?>


<div class="form-group">
    <div class="col-sm-3">
        <?= $form->label($changePasswordForm, 'current_password', array('class' => 'control-label')); ?>
    </div>
    <div class="col-sm-9">
        <?= $form->passwordField($changePasswordForm, 'current_password', array('class' => 'form-control')); ?>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-3">
        <?= $form->label($changePasswordForm, 'new_password', array('class' => 'control-label')); ?>
    </div>
    <div class="col-sm-9">
        <?= $form->passwordField($changePasswordForm, 'new_password', array('class' => 'form-control')); ?>
    </div>
</div>

<div class="form-group">
    <div class="col-sm-3">
        <?= $form->label($changePasswordForm, 'new_repeat_password', array('class' => 'control-label')); ?>
    </div>
    <div class="col-sm-9">
        <?= $form->passwordField($changePasswordForm, 'new_repeat_password', array('class' => 'form-control')); ?>
    </div>
</div>
<div class="text-center">
    <?= Html::submitButton(Yii::t('app', 'CHANGE'), array('class' => 'btn btn-primary')); ?>
</div>
<?php $this->endWidget(); ?> 
