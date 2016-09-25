

<?php

$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'ordersListGrid',
    'itemsCssClass' => 'table table-striped table-bordered',
    // 'htmlOptions'=>array('class'=>'dasdsa'),
    'dataProvider' => $orders->search(),
    'template' => '{items}',
    'filter' => $orders,
    'columns' => array(
        array(
            'name' => 'id',
            //'filter' => Html::listData(OrderStatus::model()->orderByPosition()->findAll(), 'id', 'name'),
            'value' => '$data->id',
            'htmlOptions'=>array('class'=>'text-center')
        ),
        array(
            'name' => 'paid',
            'type'=>'html',
            //'filter' => Html::listData(OrderStatus::model()->orderByPosition()->findAll(), 'id', 'name'),
            'value' => '$data->paid ? "<span class=\"label label-success\">".Yii::t("core", "YES")."</span>" : "<span class=\"label label-danger\">".Yii::t("core", "NO")."</span>"',
            'htmlOptions'=>array('class'=>'text-center')
        ),
        array(
            'name' => 'status_id',
            'filter' => Html::listData(OrderStatus::model()->orderByPosition()->findAll(), 'id', 'name'),
            'value' => '$data->status_name',
            'htmlOptions'=>array('class'=>'text-center')
        ),
        array(
            'type' => 'html',
            'name' => 'full_price',
            'value' => 'ShopProduct::formatPrice($data->full_price)',
            'htmlOptions'=>array('class'=>'text-center')
        ),
        array(
            // 'name' => 'id',
            'header' => 'Опции',
            'type' => 'html',
            'htmlOptions'=>array('class'=>'text-center'),
            //'filter' => Html::listData(OrderStatus::model()->orderByPosition()->findAll(), 'id', 'name'),
            'value' => 'Html::link("Подробней",array("/cart/default/view/","secret_key"=>$data->secret_key))'
        ),
    ),
));
?>
