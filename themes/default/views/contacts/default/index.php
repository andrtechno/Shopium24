<div class="col-md-6 contact-info">
    <div class="contact-title">
        <h4>Информация</h4>
    </div>
    <div class="clearfix address">
        <span class="contact-i"><i class="fa fa-map-marker"></i></span>
        <span class="contact-span"><?php //echo Yii::app()->contact->list[0]->office->address;   ?></span>
    </div>
    <div class="clearfix phone-no">
        <span class="contact-i"><i class="fa fa-mobile"></i></span>
        <span class="contact-span"><?php //echo Yii::app()->contact->list[0]->phones;   ?></span>
    </div>
    <div class="clearfix email">
        <span class="contact-i"><i class="fa fa-envelope"></i></span>
        <span class="contact-span"><?php //echo Yii::app()->contact->list[0]->email;   ?></span>
    </div>
</div>

<div class="col-md-6 contact-form">



    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'contact_form',
        'enableAjaxValidation' => false, // Disabled to prevent ajax calls for every field update
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => true,
            'errorCssClass' => 'has-error',
            'successCssClass' => 'has-success',
        ),
        'htmlOptions' => array('name' => 'contact_form', 'class' => 'form-vertical')
    ));
    ?>
    <div class="col-md-12">
        <?php
        //echo Html::errorSummary($model, '<i class="flaticon-warning"></i>', null, array('class' => 'errorSummary alert alert-danger'));
        ?>
    </div>
    <div class="col-md-12">
        <?php
        if (Yii::app()->user->hasFlash('success'))
            Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
        // echo Html::errorSummary($model, '<i class="fa fa-warning fa-3x"></i>', null, array('class' => 'errorSummary alert alert-danger'));
        ?>
    </div>


    <div class="contact-title">
        <h4>Обратная связь</h4>
    </div>

    <div class="form-group form-group-auto">
        <?= $form->labelEx($model, 'name', array('class' => '')); ?>
        <?= $form->textField($model, 'name', array('class' => 'form-control')); ?>
        <?= $form->error($model, 'name'); ?>
    </div>


    <div class="form-group form-group-auto">
        <?= $form->labelEx($model, 'email', array('class' => '')); ?>
        <?= $form->textField($model, 'email', array('class' => 'form-control')); ?>
        <?= $form->error($model, 'email'); ?>
    </div>



    <div class="form-group form-group-auto">
        <?= $form->labelEx($model, 'msg', array('class' => '')); ?>
        <?= $form->textArea($model, 'msg', array('class' => 'form-control')); ?>
        <?= $form->error($model, 'msg'); ?>
    </div>
    <?php if (Yii::app()->settings->get('contacts', 'enable_captcha')) { ?>
        <div class="col-md-2">
            <?php
            $this->widget('CCaptcha', array(
                'imageOptions' => array('class' => 'captcha'),
                'clickableImage' => true,
                'showRefreshButton' => false,
            ))
            ?>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <?= Html::activeLabelEx($model, 'verifyCode', array('class' => '')) ?>
                <?php echo Html::activeTextField($model, 'verifyCode', array('class' => 'form-control')) ?>
                <?= $form->error($model, 'verifyCode'); ?>
            </div>
        </div>
    <?php } ?>
    <div class="form-group text-center">

        <?= Html::submitButton(Yii::t('app', 'SEND_MSG'), array('class' => 'btn btn-success btn-lg')); ?>
    </div>



    <?php $this->endWidget(); ?>

</div>






