
<?php
echo Html::form($this->createUrl('/users/register'), 'post', array('id' => 'user-login-form', 'class' => 'register-form outer-top-xs'));
echo Html::errorSummary($model);
?>
<?php
echo Html::errorSummary($model, '<i class="fa fa-warning fa-3x"></i>', null, array('class' => 'errorSummary alert alert-danger'));
?>
<div class="form-group form-group-auto">
    <?= Html::activeLabelEx($model, 'login', array('class' => '')); ?>
    <?= Html::activeTextField($model, 'login', array('class' => 'form-control')); ?>
</div>
<div class="form-group form-group-auto">
    <?= Html::activeLabelEx($model, 'password', array('class' => '')); ?>
    <?= Html::activePasswordField($model, 'password', array('class' => 'form-control')); ?>
</div>
<div class="">
    <label>
        <?= Html::activeCheckBox($model, 'rememberMe', array('class' => '')); ?> <?= Yii::t('UsersModule.default', 'REMEMBER_ME') ?>
    </label>
    <?= Html::link(Yii::t('UsersModule.default', 'REMIN_PASS'), array('/users/remind'), array('class' => 'forgot-password pull-right')); ?>
</div>
<div class="text-center">
    <?= Html::submitButton(Yii::t('UsersModule.default', 'BTN_LOGIN'), array('class' => 'btn btn-primary')); ?>
</div>
<?= Html::endForm(); ?>

