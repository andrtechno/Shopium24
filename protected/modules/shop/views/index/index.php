
    <?php
Yii::import('mod.cart.CartModule');
CartModule::registerAssets();
$cs = Yii::app()->clientScript;

$cs->registerScript('app2', "
cart.spinnerRecount = false;

    
", CClientScript::POS_HEAD);

?>
<?php

             $this->widget('zii.widgets.CListView', array(//zii.widgets.CListView
                    'id' => 'shop-products',
                    'dataProvider' => $model,
                    'cssFile'=>false,
                    'ajaxUpdate' => true, //$ajaxUpdate
                   // 'itemsCssClass' => 'items clearfix',
                   // 'template' => '{items} {pager}',
                    //'enableHistory' => true,
                    'itemView' => '_items',
                   // 'sortableAttributes' => array(
                   //     'name', 'price'
                   // ),
                    /*'pager' => array(
                        'class'=>'LinkPager',
                        'header' => '',
                        'nextPageLabel' => 'Следующая »',
                        'prevPageLabel' => '« Предыдущая',
                        'firstPageLabel' => '«',
                        'lastPageLabel' => '»',
                    )*/
                ));
             

?>
    
