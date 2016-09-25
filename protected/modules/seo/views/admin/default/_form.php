<?php
$cs = Yii::app()->clientScript;
$baseUrl = $this->module->assetsUrl;

$cs->registerScriptFile($baseUrl . '/js/seo.js');
$cs->registerCssFile($baseUrl . '/img/close.png');
?>


<script>
    $(function () {

        jQuery.fn.exists = function () {
            return this.length > 0;
        }

        $('.addparams').change(function () {
            var val = $('option:selected', this).val();
            var id = $(this).attr('data-id');
            var text = $('option:selected', this).text();
            rowID = text + id;
            rowID = rowID.replace(".", "");
            if (!$('#' + rowID).exists()) {
                $('#container-param-' + id).append('<li class="list-group-item" id="' + rowID + '"><input type="hidden" name="param[' + id + '][' + val + ']" value="{' + text + '}" />{' + text + '} <a href="javascript:void(0);" onclick="removeParam(this);" class="pull-right">Удалить</a></li>');
            } else {
                $.jGrowl('Уже добавлен!');
            }

        });
    });

    function removeParam(that) {
        $(that).parent().remove();
    }
</script>


<?php
Yii::app()->tpl->openWidget(array(
    'title' => '',
));

$form = $this->beginWidget('CActiveForm', array(
    'id' => 'seo-url-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'form-horizontal')
        ));
?>





    <div class="form-group">
        <div class="col-sm-4"><?php echo $form->labelEx($model, 'url', array('class' => 'control-label')); ?></div>
        <div class="col-sm-8">
            <?php echo $form->textField($model, 'url', array('size' => 60, 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'url'); ?>
        </div>
    </div>



<div class="form-group">
        <div class="col-sm-4"><?php echo $form->labelEx($model, 'title', array('class' => 'control-label')); ?></div>
        <div class="col-sm-8">
            <?php echo $form->textField($model, 'title', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'title'); ?>
        </div>
    </div>
<div class="form-group">
        <div class="col-sm-4"><?php echo $form->labelEx($model, 'description', array('class' => 'control-label')); ?></div>
        <div class="col-sm-8">
            <?php echo $form->textArea($model, 'description', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'description'); ?>
        </div>
    </div>
<div class="form-group">
        <div class="col-sm-4"><?php echo $form->labelEx($model, 'keywords', array('class' => 'control-label')); ?></div>
        <div class="col-sm-8">
            <?php echo $form->textArea($model, 'keywords', array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'keywords'); ?>
        </div>
    </div>
<div class="form-group">
    <div class="col-sm-4"></div>
    <div class="col-sm-8"><?php echo CHtml::dropDownList('title_param', "[$model->keywords]param", CHtml::listData($this->getParams(), "value", "name", "group"), array("empty" => "Свойства", 'class' => 'selectpicker addparams', 'data-id' => $model->id)); ?>
        <?php
        echo

        $this->renderPartial('_formMetaParams', array('model' => $model));
        ?></div>
</div>




<div class="form-group text-center">
    <?php echo CHtml::submitButton(Yii::t('app', 'SAVE'), array('class' => 'btn btn-success')); ?>
</div>

<?php
$this->endWidget();
Yii::app()->tpl->closeWidget();
?>


