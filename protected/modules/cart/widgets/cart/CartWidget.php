<?php


class CartWidget extends Widget {


    public $registerFile = array(
       // 'cartWidget.css',
            //'cartWidget.js',
    );

    public function init() {
        $this->assetsPath = dirname(__FILE__) . '/assets';
        parent::init();
    }

    public function run() {
        $cart = Yii::app()->cart;
        $currency = Yii::app()->currency->active;
        $items=$cart->getDataWithModels();
        $total = ShopProduct::formatPrice(Yii::app()->currency->convert($cart->getTotalPrice()));
        if($this->skin=='bootstrap'){
            //if (!Yii::app()->request->isAjaxRequest)
                //echo Html::tag('li', array('id' => 'cart','class'=>'dropdown'));
             $this->render($this->skin, array(
                 'count'=>$cart->countItems(),
                 'currency'=>$currency,
                 'total'=>$total,
                 'items'=>$items
                 ));
            //if (!Yii::app()->request->isAjaxRequest)
                //echo Html::closeTag('li');
        }else{
            if (!Yii::app()->request->isAjaxRequest)
                echo Html::tag('div', array('id' => 'cart'));
            $this->render($this->skin, array());
            if (!Yii::app()->request->isAjaxRequest)
                echo Html::closeTag('div');
        }
    }

}
