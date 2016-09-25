<?php


// Render tabs
$tabs = array(
    Yii::t('CartModule.admin', 'ORDER',1) => $this->renderPartial('_order_tab', array(
        'model' => $model,
        'statuses' => $statuses,
        'deliveryMethods' => $deliveryMethods,
        'paymentMethods' => $paymentMethods
            ), true),
);

?>
<div class="fluid">
    <div class="widget grid6">
        <?php
        $this->widget('zii.widgets.jui.CJuiTabs', array(
            'tabs' => $tabs
        ));
        ?>
    </div>
    <div class="grid6">
        <?php if (!$model->isNewRecord) { ?>
            <div id="dialog-modal" style="display: none;" title="<?php echo Yii::t('CartModule.admin', 'CREATE_PRODUCT') ?>">
                <?php
                $this->renderPartial('_addProduct', array(
                    'model' => $model,
                ));
                ?>
            </div>
            <div id="orderedProducts">
                <?php
                if (!$model->isNewRecord) {
                    $this->renderPartial('_orderedProducts', array(
                        'model' => $model,
                    ));
                }
                ?>
            </div>
        <?php } else { ?>
            <?php Yii::app()->tpl->alert('info', Yii::t('CartModule.admin','ALERT_CREATE_PRODUCT'), false); ?>
        <?php } ?>
    </div>
</div>   