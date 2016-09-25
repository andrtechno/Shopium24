<?php
$config = Yii::app()->settings->get('shop');
if (Yii::app()->user->hasFlash('success')) {
    Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
}
if (Yii::app()->user->hasFlash('success_register')) {
    Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success_register'));
}


?>
<div class="col-xs-12">
<h1><?= $this->pageName; ?></h1>
<table width="100%" border="0" class="table table-striped table-condensed">
    <tr>
        <th align="center"><?= Yii::t('CartModule.default', 'TABLE_NAME') ?></th>
        <th align="center"><?= Yii::t('CartModule.default', 'TABLE_NUM') ?></th>
        <th align="center"><?= Yii::t('CartModule.default', 'TABLE_SUM') ?></th>
    </tr>
    <?php foreach ($model->getOrderedProducts()->getData() as $product) { //$model->getOrderedProducts()->getData()  ?> 
        <tr>

            <td>
                <?= Html::openTag('h3') ?>
                <?= $product->getRenderFullName(false); ?>
                <?= Html::closeTag('h3') ?>
                <?= Html::openTag('span', array('class' => 'price')) ?>
                <?= ShopProduct::formatPrice(Yii::app()->currency->convert($product->price)) ?>
                <?= Yii::app()->currency->active->symbol; ?>
                <?= Html::closeTag('span') ?> 
            </td>
            <td align="center">
                <?= $product->quantity ?>
            </td>
            <td align="center">
                <?php
                echo ShopProduct::formatPrice(Yii::app()->currency->convert($product->price * $product->quantity));
                ?>
                <?= Yii::app()->currency->active->symbol; ?>
            </td>
        </tr>
    <?php } ?>
</table>


<div class="row">
    <?php if(!$model->paid){ ?>
    <div class="col-md-8">
        <div class="panel panel-info">
            <div class="panel-heading"><h4><?= Yii::t('CartModule.default', 'PAYMENT_METHODS') ?></h4></div>
            <div class="panel-body">


                <?php
                
                foreach (ShopPaymentMethod::model()->findAll() as $payment) {
                    echo $payment->name;
                    echo $payment->renderPaymentForm($model);
                }
                
                ?>

                <?= Yii::t('CartModule.default', 'TOTAL_PAY') ?>:
                <span class="label label-success"><?= ShopProduct::formatPrice(Yii::app()->currency->convert($model->full_price)) ?></span> 
                <?= Yii::app()->currency->active->symbol ?>
            </div>
        </div>
    </div>
    <?php } ?>

    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"><h4><?= Yii::t('CartModule.default', 'Состояние заказа') ?><span class="label fr label-default" style=""><?= $model->status_name ?></span></h4><div class="clear"></div></div>
            <div class="panel-body">
                <?php if ($model->paid) { ?>
                    <?= Yii::t('CartModule.Order', 'PAID') ?>: <span class="label label-success"><?= Yii::t('core', 'YES') ?></span>
                <?php } else { ?>
                    <?= Yii::t('CartModule.Order', 'PAID') ?>: <span class="label label-default"><?= Yii::t('core', 'NO') ?></span>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
</div>
