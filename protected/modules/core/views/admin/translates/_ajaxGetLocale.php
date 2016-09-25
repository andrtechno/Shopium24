
<div class="col-md-3">
    <?php echo CHtml::dropDownList('locale', '', $array, array('empty' => '--- Выбор языка ---',  'class'=>'form-control', 'onchange' => 'ajaxTranslate("#filesID","ajaxGetLocaleFile"); return false;')); ?>
</div>
<div id="filesID"></div>

