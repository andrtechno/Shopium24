

<div class="sign-in-page">
    <div class="col-md-7 col-sm-7 sign-in">
        <div class="h3"><?= $this->pageName; ?></div>
        <p class="text-muted">Заполните пожалуйста поля ниже</p>

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'user-register-form',
            'enableAjaxValidation' => true, // Disabled to prevent ajax calls for every field update
            'enableClientValidation' => false,
            'clientOptions' => array(
                'validateOnSubmit' => true,
                'validateOnChange' => true,
                'errorCssClass' => 'has-error',
                'successCssClass' => 'has-success',
            ),
            'htmlOptions' => array('class' => 'form-horizontal')
                ));
        ?>

        <?php
        echo $form->errorSummary($user, '<i class="fa fa-warning fa-3x"></i>', null, array('class' => 'errorSummary alert alert-danger'));
        ?>
        <div class="form-group ">
            <div class="col-sm-5"><?= $form->labelEx($user, 'login', array('class' => 'control-label')); ?></div>
            <div class="col-sm-7">
                <?= $form->textField($user, 'login', array('class' => 'form-control')); ?>
                <?= $form->error($user, 'login'); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-5"><?= $form->labelEx($user, 'plan'); ?></div>
            <div class="col-sm-7">
                <div class="input-group">
                    <?= $form->dropdownlist($user, 'plan', CHtml::listData(Plans::model()->findAll(), 'name', 'name'), array('class' => 'form-control')); ?>
                    <div class="input-group-addon">
                        <?php $this->widget('mod.users.widgets.design.SelectDesginWidget'); ?>
                    </div>
                </div>

                <?= $form->error($user, 'plan'); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-5"><?= $form->labelEx($user, 'subdomain', array('class' => 'control-label')); ?></div>
            <div class="col-sm-7"><div class="input-group">
                    <div class="input-group-addon">www</div>
                    <?= $form->textField($user, 'subdomain', array('class' => 'form-control')); ?>
                    <div class="input-group-addon"><?= Yii::app()->params['baseDomain'] ?></div>
                </div>
                <?= $form->error($user, 'subdomain'); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-5"><?= $form->labelEx($user, 'username', array('class' => 'control-label')); ?></div>
            <div class="col-sm-7">

                <?= $form->textField($user, 'username', array('class' => 'form-control')); ?>


                <?= $form->error($user, 'username'); ?>
            </div>
        </div>
        <div class="form-group">

            <div class="col-sm-5"><?= $form->labelEx($user, 'password', array('class' => 'control-label')); ?></div>
            <div class="col-sm-7">

                <?= $form->passwordField($user, 'password', array('class' => 'form-control')); ?>

                <?= $form->error($user, 'password'); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-5"><?= $form->labelEx($user, 'confirm_password', array('class' => 'control-label')); ?></div>
            <div class="col-sm-7">

                <?= $form->passwordField($user, 'confirm_password', array('class' => 'form-control')); ?>

                <?= $form->error($user, 'confirm_password'); ?>
            </div>
        </div>
        <?php if (CCaptcha::checkRequirements() && false) { ?>
            <div class="form-group">
                <?= $form->labelEx($user, 'verifyCode', array('class' => 'info-title')); ?>
                <?php
                $this->widget('CCaptcha', array(
                    'clickableImage' => false,
                    'showRefreshButton' => true,
                    'buttonLabel' => '',
                    'buttonOptions' => array(
                        'class' => 'refresh_captcha icon-loop-2'
                    )
                ));
                ?>
                <? //= $form->textField($user, 'verifyCode', array('style' => 'width:150px', 'class' => 'form-control unicase-form-control text-input', 'placeholder' => $user->getAttributeLabel('verifyCode'))); ?>
            </div>
        <?php } ?>

        <div class="form-group">
            <div class="col-sm-4"></div>
            <div class="col-sm-8"><?= $form->checkBox($user, 'terms', array('class' => '')); ?>
                <?= $form->error($user, 'terms'); ?>
                <?= $form->labelEx($user, 'terms', array()); ?>
            </div>
        </div>
        <div class="text-center">
            <?= Html::submitButton(Yii::t('UsersModule.default', 'BTN_REGISTER'), array('class' => 'btn btn-lg btn-primary')); ?>
        </div>
        <?php $this->endWidget(); ?>
    </div>

    <div class="col-md-4 col-sm-5 col-lg-offset-1 create-new-account">
        <div class="h3">Вход</div>
        <p class="text-muted">Здравствуйте, войдите в свой аккаунт</p>
        <?php
        Yii::import('mod.users.forms.UserLoginForm');
        $this->renderPartial('login', array('model' => new UserLoginForm()));
        ?>

    </div>	
</div>



