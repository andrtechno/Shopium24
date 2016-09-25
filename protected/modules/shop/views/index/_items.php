


<div class="product_block">

    <div class="name">
        <?php echo CHtml::encode($data->name) ?>
    </div>
    <div class="price">

        <?php echo $data->priceRange() ?>
    </div>
    <div class="actions">
        <?php
        echo Html::form(array('/cart/add'), 'post', array(
            //'class' => 'product-form2',
            'id' => 'form-add-cart-' . $data->id
        ));
        echo Html::hiddenField('product_id', $data->id);
        echo Html::hiddenField('product_price', $data->price);

        echo Html::hiddenField('currency_rate', Yii::app()->currency->active->rate);

        echo Html::textField('quantity', 1, array('class' => 'spinner btn-group form-control'));



        if ($data->isAvailable) {
            echo Html::link('<i class="fa fa-shopping-cart"></i>' . Yii::t('CartModule.default', 'BUY'), 'javascript:cart.add("#form-add-cart-' . $data->id . '")', array('class' => 'btn btn-primary'));
        } else {
            echo Html::link(Yii::t('CartModule.default', 'NOT_AVAILABLE'), 'javascript:cart.notifier(' . $data->id . ');');
        }
        ?>

        <?php echo Html::endForm() ?>
    </div>
</div>