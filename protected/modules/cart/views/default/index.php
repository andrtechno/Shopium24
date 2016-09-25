<?php
$cs = Yii::app()->clientScript;
//$cs->registerScriptFile($this->module->assetsUrl . '/cart.js', CClientScript::POS_END);
$cs->registerScript('cart', "
//cart.selectorTotal = '#total';
var orderTotalPrice = '$totalPrice';

", CClientScript::POS_HEAD);
?>
<?php
Yii::import('mod.cart.CartModule');
CartModule::registerAssets();
?>
<script>


    function submitform(){
        if(document.cartform.onsubmit &&
            !document.cartform.onsubmit())
        {
            return;
        }
        document.cartform.submit();
    }
</script>


<?php
if (empty($items)) {
    echo Html::openTag('div', array('id' => 'container-cart', 'class' => 'indent'));
    echo Html::openTag('h1');
    echo Yii::t('CartModule.default', 'CART_EMPTY');
    echo Html::closeTag('h1');
    echo Html::closeTag('div');
    return;
}

?>

<div class="col-xs-12">
<h1><?= $this->pageName ?></h1>

<?php echo Html::form(array('/cart/'), 'post', array('id' => 'cart-form', 'name' => 'cartform')) ?>
<div class="table-responsive">
    <table id="cart-table" class="table table-striped table-condensed" width="100%" border="0" cellspacing="0" cellpadding="5">
        <thead>
            <tr>
                <th>Товар</th>
                <th>Количество</th>
                <th>Сумма</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $index => $product) { ?>
                <?php
                // print_r($product);die;
                $price = ShopProduct::calculatePrices($product['model'], $product['variant_models'], $product['configurable_id']);
                ?>
                <tr id="product-<?= $index ?>">
                    <td>
                        <?= Html::encode($product['model']->name);?>

                        <?php
                        // Price

                        echo Html::openTag('span', array('class' => 'price'));
                        echo ShopProduct::formatPrice(Yii::app()->currency->convert($price));
                        echo ' ' . Yii::app()->currency->active->symbol;
                        //echo ' '.($product['currency_id']) ? Yii::app()->currency->getSymbol($product['currency_id']) : Yii::app()->currency->active->symbol;
                        echo Html::closeTag('span');
                        ?>
                    </td>
                    <td>
                        <?= Html::textField("quantities[$index]", $product['quantity'], array('class' => 'spinner btn-group form-control', 'product_id' => $index)) ?>
                    </td>
                    <td id="price-<?= $index ?>">
                        <?php
                        echo Html::openTag('span', array('class' => 'price cart-total-product', 'id' => 'row-total-price' . $index));
                        echo (Yii::app()->settings->get('shop', 'wholesale')) ? ShopProduct::formatPrice(ShopProduct::formatPrice(Yii::app()->currency->convert($price * $product['model']->pcs * $product['quantity']))) : ShopProduct::formatPrice(Yii::app()->currency->convert($price * $product['quantity']));
                        echo Html::closeTag('span');
                        //echo $convertTotalPrice;// echo ShopProduct::formatPrice(Yii::app()->currency->convert($convertPrice, $product['currency_id']));
                        echo ' ' . Yii::app()->currency->active->symbol;
                        //echo ' '.($product['currency_id'])? Yii::app()->currency->getSymbol($product['currency_id']): Yii::app()->currency->active->symbol;
                        ?>
                    </td>
                    <td style="vertical-align:middle;" width="20px">
                        <?= Html::link('x', array('remove', 'id' => $index), array('class' => 'remove')) ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>


</div>




<div>Сумма заказа</div>
<div><span class="price"><span id="total"><?= ShopProduct::formatPrice($totalPrice) ?></span> <i><?php echo Yii::app()->currency->active->symbol; ?></i></span></div>
<a href="javascript:submitform();" class="btn btn-lg btn-success"><?= Yii::t('CartModule.default', 'BUTTON_CHECKOUT'); ?></a>

<input class="button btn-green" type="hidden" name="create" value="1">
<?php echo Html::endForm() ?>
</div>

