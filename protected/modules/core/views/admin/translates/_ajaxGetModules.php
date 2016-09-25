<div class="col-md-3">
    <?php echo CHtml::dropDownList('module', '', $tree, array('empty' => '--- Выбор модуля ---',  'class'=>'form-control', 'onchange' => 'ajaxTranslate("#localeID","ajaxGetLocale"); return false;')); ?>
</div>
<div id="localeID"></div>
