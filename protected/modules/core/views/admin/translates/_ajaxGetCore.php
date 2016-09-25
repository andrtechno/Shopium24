<div class="col-md-3">
    <?php echo CHtml::dropDownList('locale', '', $tree, array('empty' => '--- Выбор ---',  'class'=>'form-control', 'onchange' => 'ajaxTranslate("#localeID","ajaxGetLocaleFile"); return false;')); ?>
</div>
<div id="localeID"></div>
