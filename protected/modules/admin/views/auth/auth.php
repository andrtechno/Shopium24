<script>
    $(function () {
        var h = $('.panel').height();
        var dh = $(window).height();
        $('#loginbox').css({'margin-top': (dh / 2) - h});
        $(window).resize(function () {
            var h = $('.panel').height();
            var dh = $(window).height();
            $('#loginbox').css({'margin-top': (dh / 2) - h});
        });
        $('.auth-logo').hover(function () {
            // $(this).removeClass('zoomInDown').addClass('swing'); 
        }, function () {
            //  $(this).removeClass('swing'); 
        });
    });

</script>

<div class="container">

    <div id="loginbox" style="margin-top:80px;" class="animated <?php if (!Yii::app()->user->hasFlash('error')) { ?>bounceInDown<?php } ?> col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2">                    

        <div class="text-center auth-logo animated zoomInDown2 ">
            <a href="//corner-cms.com" target="_blank"><img src="http://corner-cms.com/logo.php?size=200x100&color=777&type=logo_cms" alt="" /></a>
            <div class="auth-logo-hint"><?= Yii::t('app', 'CMS') ?></div>
        </div>
        <div class="panel panel-default" >
            <div class="panel-heading">
                <div class="panel-title text-center">Войти в админ-панель</div>
            </div>     
            <div style="padding-top:15px" class="panel-body">



                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'login-form',
                    'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
                    'enableClientValidation' => false,
                    'clientOptions' => array(
                        'validateOnType' => false,
                        'validateOnSubmit' => false,
                        'validateOnChange' => false,
                        'errorCssClass' => 'has-error',
                        'successCssClass' => 'has-success',
                    ),
                        //   'htmlOptions' => array('class' => 'form-horizontal')
                ));

                if (Yii::app()->user->hasFlash('error')) {
                    Yii::app()->tpl->alert('danger', Yii::app()->user->getFlash('error'), false);
                }
                ?>

                <div class="form-group col-xs-12">
                    <?= $form->textField($model, 'login', array('placeholder' => Yii::t('app', 'LOGIN'), 'class' => 'form-control')); ?>  
                    <?= $form->error($model, 'login'); ?>
                </div>
                <div class="form-group col-xs-12">
                    <?= $form->passwordField($model, 'password', array('placeholder' => Yii::t('app', 'PASSWORD'), 'class' => 'form-control')); ?>
                    <?= $form->error($model, 'password'); ?>
                </div>

                <div class="form-group col-xs-12">
                    <div class="row">
                        <div class="checkbox col-xs-7 col-sm-6">
                            <?= Html::label(Html::activeCheckBox($model, 'rememberMe') . Yii::t('app', 'REMEMBER_ME'), Html::activeId($model, 'rememberMe')) ?>
                        </div>
                        <div class="col-xs-5 col-sm-6 controls text-right"><?= Html::submitButton(Yii::t('app', 'ENTER'), array('class' => 'btn btn-success')); ?></div>
                    </div>
                </div>
                <?php $this->endWidget(); ?>  
            </div>                       
        </div> 
        <div class="text-center copyright">{copyright}</div>
    </div>
</div>


