

<div class="row">

    <div class="col-md-7">

        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'user-profile-form',
            'enableAjaxValidation' => false,
            'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal')
                ));

        if ($user->hasErrors())
            echo Html::errorSummary($user, '<i class="fa fa-warning fa-3x"></i>', null, array('class' => 'errorSummary alert alert-danger'));
        ?>

        <div class="form-group">
            <div class="col-sm-3">
                <?= $form->labelEx($user, 'username', array('class' => 'control-label')); ?>
            </div>
            <div class="col-sm-9">
                <?= $form->textField($user, 'username', array('class' => 'form-control')); ?>
                <?= $form->error($user, 'username'); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3">
                <?= $form->labelEx($user, 'phone', array('class' => 'control-label')); ?>
            </div>
            <div class="col-sm-9">
                <?= $form->textField($user, 'phone', array('class' => 'form-control')); ?>
                <?= $form->error($user, 'phone'); ?>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3">
                <?= $form->labelEx($user, 'address', array('class' => 'control-label')); ?>
            </div>
            <div class="col-sm-9">
                <?= $form->textField($user, 'address', array('class' => 'form-control')); ?>
                <?= $form->error($user, 'address'); ?>
            </div>
        </div>
        <?php if (Yii::app()->hasModule('delivery')) { ?>
            <div class="form-group">
                <?= $form->labelEx($user, 'subscribe', array('class' => 'control-label')); ?>
                <?= $form->checkBox($user, 'subscribe', array('class' => '')); ?>
            </div>
        <?php } ?>
        <div class="form-group">
            <div class="col-sm-3">
                <?= $form->labelEx($user, 'gender', array('class' => 'control-label')); ?>
            </div>
            <div class="col-sm-9">
                <?= $form->dropDownList($user, 'gender', $user->getSelectGender(), array('class' => 'form-control')); ?>
            </div>
        </div>

        <?php if (Yii::app()->settings->get('users', 'upload_avatar') && false) { ?>
            <div class="form-group">
                <?= $form->labelEx($user, 'avatar', array('class' => 'control-label')); ?>

                <span class="btn btn-default btn-file">
                    Выбрать
                    <?= $form->fileField($user, 'avatar', array('class' => 'form-control file-caption')); ?>
                </span>
            </div>

        <?php } ?>
        <div class="text-center">
            <?= Html::submitButton(Yii::t('app', 'SAVE'), array('class' => 'btn btn-primary')); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div>
    <div class="col-md-5">

        <div class="btn-group" style="display: none">
            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                Загрузить <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu" style="display: none">
                <li><a href="#"><?= Yii::t('UsersModule.default', 'LOAD_AVA_PC') ?></a></li>
                <li><a href="#"><?= Yii::t('UsersModule.default', 'LOAD_AVA_GAL') ?></a></li>

            </ul>
        </div>


        <?php if ($user->shop) { ?>
            <ul class="list-group">
                <?php foreach ($user->shop as $shop) { ?>
                    <li class="list-group-item">Ваш сайт: <span class="pull-right"><?= Html::link($shop->getSubdomainFull(), $shop->getSubdomainUrl(), array('traget' => '_blank')); ?></span></li>
                    <li class="list-group-item">Окончание периода: <span class="pull-right"><?= CMS::date(date('Y-m-d H:i:s', strtotime($shop->expired))) ?></span></li>
                    <li class="list-group-item">Тарифный план: <span class="label label-primary pull-right"><?= $shop->plan ?></span></li>
                    <li class="list-group-item">Дата создание магазина: <span class="pull-right"><?= CMS::date($shop->date_create); ?></span></li>
                <?php } ?>
            </ul>
        <?php }
        ?>
    </div>
</div>



<script>
    $(function() {
        $(document).on('change', '.btn-file :file', function() {
            var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
            input.trigger('fileselect', [numFiles, label]);
        });
        $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
            console.log(numFiles);
            console.log(label);
        });
    });
</script>






