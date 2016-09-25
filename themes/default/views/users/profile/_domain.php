<div class="alert alert-info">
    Вы можете зарегистрировать и проверить наличие вашего будущего домен у нашего <a href="/html/domain">партнера</a>
</div>
<div class="alert alert-warning">
    Ваш домен должен находится на NS серверх указаных выше. (demo)
</div>
<ul class="list-group">
    <li class="list-group-item">NS сервера 

        <div class="pull-right">
            <div><span class="label label-success">ns1.fastdns.hosting</span></div>
            <div><span class="label label-success">ns2.fastdns.hosting</span></div>
            <div><span class="label label-success">ns3.fastdns.hosting</span></div>
        </div>
        <div class="clearfix"></div>
    </li>
</ul>


<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'user-profile-domain-form',
    'enableAjaxValidation' => true, // Disabled to prevent ajax calls for every field update
    'enableClientValidation' => false,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'errorCssClass' => 'has-error',
        'successCssClass' => 'has-success',
    ),
    'htmlOptions' => array('class' => '')
        ));
?>

<div class="form-group">
    <?= $form->labelEx($user, 'domain', array('class' => 'control-label')); ?>
    <?= $form->textField($user, 'domain', array('class' => 'form-control')); ?>
    <?= $form->error($user, 'domain'); ?>
</div>

<div class="text-center">
    <?= Html::submitButton(Yii::t('app', 'SAVE'), array('class' => 'btn btn-primary')); ?>
</div>

<?php $this->endWidget(); ?>