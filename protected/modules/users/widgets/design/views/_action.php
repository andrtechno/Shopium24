<?php
$selected = isset($_POST['theme']) ? $_POST['theme'] : 'default';
?>



<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'user-design-form',
    'enableAjaxValidation' => true, // Disabled to prevent ajax calls for every field update
    'enableClientValidation' => false,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'errorCssClass' => 'has-error',
        'successCssClass' => 'has-success',
    ),
    'htmlOptions' => array('class' => 'form-horizontal', 'role' => 'form')
        ));
?>

<div class="form-group">
    <div class="col-sm-4">Шаблон</div>
    <div class="col-sm-8">
        <?php
        echo Html::dropDownList('theme', $selected, array(
            'default' => 'Default',
            'classic' => 'Classic'
                ), array('onChange' => 'get_theme_color(this)', 'empty' => '----', 'class' => 'form-control'));
        ?>
    </div>
</div>
<?php if (isset($colors)) { ?>
    <div class="form-group">
        <div class="col-sm-4">Цвет</div>
        <div class="col-sm-8">
            <?php
            echo Html::dropDownList('theme_color', null, $colors, array(
                'onChange' => 'get_theme_preview(this)',
                'empty' => '----',
                'class' => 'form-control'
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <div id="preview_image" class="text-center"></div>
    </div>
<?php } ?>
<div class="text-center">
    <?= Html::submitButton(Yii::t('UsersModule.default', 'OK'), array('class' => 'btn btn-lg btn-primary')); ?>
</div>
<?php $this->endWidget(); ?>

