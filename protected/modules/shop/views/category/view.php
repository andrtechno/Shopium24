
    <?php
Yii::import('mod.cart.CartModule');
CartModule::registerAssets();
$cs = Yii::app()->clientScript;

$cs->registerScript('app1', "
cart.spinnerRecount = false;

    
", CClientScript::POS_HEAD);


$cs->registerScript('category', "
var categoryFullUrl = '" . $this->model->full_path . "';
    
", CClientScript::POS_HEAD);

?>

<div class="row">

    <div class="col-xs-12">


                <h1><?php echo Html::encode($this->model->name); ?></h1>

                <?php

  
                
                $this->widget('zii.widgets.CListView', array(//zii.widgets.CListView
                    'id' => 'shop-products',
                    'dataProvider' => $provider,
                    'cssFile'=>false,
                    'ajaxUpdate' => true, //$ajaxUpdate
                    'itemsCssClass' => 'items clearfix ',
                    'htmlOptions'=>array('class'=>'table table-bordered table-striped'),
                    'tagName'=>'table',
                   
                    'template' => '{items} {pager}',
                    //'enableHistory' => true,
                    'itemView' => '_list',
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
 


    </div> 
</div>









