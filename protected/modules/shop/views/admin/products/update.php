<?php
if ($this->isAjax) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}

if (!$model->isNewRecord && Yii::app()->settings->get('shop','auto_gen_url')){
    Yii::app()->tpl->alert('warning',Yii::t('ShopModule.admin','ENABLE_AUTOURL_MODE'));
}



?>
<div class="widget fluid ">
    <div class="whead ">
        <h6><?php echo $this->pageName ?></h6>
        <div class="clear"></div>

    </div>

<?php

    echo $form->tabs();
?>
  
</div>
<script type="text/javascript">init_translitter('ShopProduct','<?= $model->primaryKey; ?>');</script>

<?php
if ($this->isAjax) echo Html::closeTag('div');