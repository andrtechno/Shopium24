
<div class="col-md-3">
    <?php echo CHtml::dropDownList('file', '', $tree, array('empty' => '--- Выбор файла перевода ---',  'class'=>'form-control', 'onchange'=>'ajaxTranslate("#translateContainer","ajaxOpen"); return false;')); ?>
</div>
