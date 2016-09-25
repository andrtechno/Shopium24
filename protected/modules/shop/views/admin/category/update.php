<?php
if ($this->isAjax) {
    $this->renderPartial('mod.admin.views.layouts._content-top');
    echo Html::openTag('div', array('class' => 'wrapper'));
}
$this->widget('ext.fancybox.Fancybox', array(
    'target' => 'a.overview-image',
    'config' => array(),
));
?>
<div class="fluid">
    <div class="widget fluid tableTabs2 grid7">
        <?php echo $model->getForm()->tabs(); ?> 
    </div>
    <div class="widget grid5">
        <div class="whead ">
            <h6><?php echo $this->pageName ?></h6>
            <div class="clear"></div>
        </div>
        <div class="formRow">
            <?php $this->renderPartial('_categories', array('model' => $model)); ?>
        </div>
    </div>
</div>
<script type="text/javascript">init_translitter('ShopCategory','<?= $model->primaryKey; ?>', false);</script>

<?php
if ($this->isAjax) echo Html::closeTag('div');
