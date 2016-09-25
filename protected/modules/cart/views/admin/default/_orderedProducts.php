<?php
$this->widget('ext.fancybox.Fancybox', array('target' => 'td.image a'));

Yii::app()->clientScript->registerScript('qustioni18n', '
	var deleteQuestion = "' . Yii::t('CartModule.admin', 'Вы действительно удалить запись?') . '";
	var productSuccessAddedToOrder = "' . Yii::t('CartModule.admin', 'Продукт успешно добавлен к заказу.') . '";
', CClientScript::POS_BEGIN);

$this->widget('ext.adminList.GridView', array(
    'id' => 'orderedProducts',
    'enableHeader' => true,
    'name' => Yii::t('CartModule.admin', 'Продукты'),
    'headerButtons' => array(
        array(
            'label' =>  Yii::t('CartModule.admin', 'CREATE_PRODUCT'),
            'url' => 'javascript:openAddProductDialog(' . $model->id . ');',
            'htmlOptions' => array('class' => 'buttonH bBlue')
        )
    ),
    'enableSorting' => false,
    'enablePagination' => false,
    'dataProvider' => $model->getOrderedProducts(),
    'selectableRows' => 0,
    'template' => '{items}',
));
?>

<script type="text/javascript">
    var orderTotalPrice = '<?php echo $model->total_price ?>';
    $(function(){
        var total_pcs = function() {
            var sum = 0;
            $('.quantity').each(function(key,index) {
                sum += Number($(this).text());
            });
            return sum;
        };
        $('#total_pcs').text(total_pcs);
    });
</script>


<div class="body">
    <ul class="wInvoice">
        <li>
            <h4 class="green"><?php echo ShopProduct::formatPrice($model->full_price) ?> <?php echo Yii::app()->currency->main->symbol ?></h4>
            <span><?php echo Yii::t('CartModule.admin', 'FOR_PAYMENT') ?></span>
        </li>
    </ul>
    <div class="clear"></div>
</div>
