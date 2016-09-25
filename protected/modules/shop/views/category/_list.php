<tr>
    <td><?= Html::encode($data->name) ?></td>
    <td><?= $data->priceRange() ?> <sub><?= Yii::app()->currency->active->symbol ?> / мес.</sub></td>
    <td>
        <?php
        echo Html::form(array('/cart/add'), 'post', array('id' => 'form-add-' . $data->id));
        echo Html::hiddenField('product_id', $data->id);
        echo Html::hiddenField('product_price', $data->price);
        echo Html::hiddenField('currency_rate', Yii::app()->currency->active->rate);
        echo Html::hiddenField('currency_id', $data->currency_id);
        echo Html::textField('quantity', 1, array('class' => 'spinner btn-group form-control'));
        echo Html::link(Yii::t('app', 'BUY'), 'javascript:cart.add("#form-add-' . $data->id . '")', array('class' => 'btn btn-success text-uppercase'));
        echo Html::endForm();
        ?>
    </td>
</tr>
