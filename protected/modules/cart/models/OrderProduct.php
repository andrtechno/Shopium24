<?php

/**
 * This is the model class for table "OrderProduct".
 *
 * The followings are the available columns in table 'OrderProduct':
 * @property integer $id
 * @property integer $order_id
 * @property integer $product_id
 * @property string $name
 * @property integer $quantity
 * @property float $price
 */
class OrderProduct extends ActiveRecord {

    protected $_MODULENAME = 'cart';

    public function getGridColumns() {
        return array(
            array(
                'class' => 'SGridIdColumn',
                'name' => 'image',
                'type' => 'html',
                'htmlOptions' => array('class' => 'image'),
                'value' => '(!empty($data->prd->mainImage))?Html::link(Html::image($data->prd->mainImage->getUrl("50x50"),""),$data->prd->mainImage->getUrl("500x500")):"no image"'
            ),
            array(
                'name' => 'renderFullName',
                'type' => 'raw',
                'htmlOptions' => array('class' => 'textL'),
                'header' => Yii::t('CartModule.OrderProduct', 'NAME'),
                'value' => 'Html::link($data->renderFullName,array("/admin/shop/products/update", "id"=>$data->product_id),array("target"=>"_blank"))'
            ),
            array(
// 'class' => 'OrderQuantityColumn',
                'name' => 'quantity',
                'htmlOptions' => array('class' => 'quantity'),
                'header' => Yii::t('CartModule.admin', 'WHOLESALE_LITE',Yii::app()->settings->get('shop', 'wholesale'))
            ),
            array(
                'name' => 'price',
                'value' => 'ShopProduct::formatPrice($data->price)'
            ),

            'DEFAULT_CONTROL' => array(
                'type' => 'raw',
                'htmlOptions' => array('class' => 'tableToolbar'),
                'value' => 'Html::link("<span class=\"icon-trashcan icon-medium\"></span>", "#", array("style"=>"font-weight:bold;", "onclick"=>"deleteOrderedProduct($data->id, $data->order_id, \"' . Yii::app()->request->csrfToken . '\")"))',
            ),
                // 'DEFAULT_COLUMNS' => array(
                //     array('class' => 'CheckBoxColumn')
                // ),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return OrderProduct the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{order_product}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            //array('name', 'type', 'type' => 'string'),
            array('id, order_id, product_id, currency_id, name, quantity, price', 'safe', 'on' => 'search'),
            array('id, order_id, product_id, currency_id, name, quantity, price', 'safe', 'on' => 'search_pdf'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'categorization' => array(self::HAS_MANY, 'ShopProductCategoryRef', 'product'),
            'categories' => array(self::HAS_MANY, 'ShopCategory', array('category' => 'id'), 'through' => 'categorization'),
            'mainCategory' => array(self::HAS_ONE, 'ShopCategory', array('category' => 'id'), 'through' => 'categorization', 'condition' => 'categorization.is_main = 1', 'scopes' => 'applyTranslateCriteria'),
            'order' => array(self::BELONGS_TO, 'Order', 'order_id'),
            //'order2' => array(self::BELONGS_TO, 'Order', 'product_id'),

            'prd' => array(self::BELONGS_TO, 'ShopProduct', 'product_id'),
        );
    }

    /**
     * @return boolean
     */
    public function afterSave() {
        $this->order->updateTotalPrice();

        return parent::afterSave();
    }

    public function afterDelete() {
        if ($this->order) {
            $this->order->updateTotalPrice();
        }

        return parent::afterDelete();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($params = array()) {
        $criteria = new CDbCriteria;


        $criteria->compare('id', $this->id);
        $criteria->compare('order_id', $this->order_id);
        $criteria->compare('product_id', $this->product_id);
        $criteria->compare('currency_id', $this->currency_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('quantity', $this->quantity);
        $criteria->compare('price', $this->price);

        /**
         * Для истории заказов.
         */
        if (isset($params['history'])) {
            $criteria->with = array('order');
            $criteria->addInCondition('`order`.`status_id`', array(3, 4));
        }

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => null
                ));
    }


    public function scopes() {
        $alias = $this->getTableAlias(true);
        return array(
            'ordersMade1' => array(
                //  'condition' => '`order`.`status_id`="3"',
                'with' => array('order')
            ),
            'ordersMade2' => array(
                'condition' => '`order`.`status_id`="4"',
                'with' => array('order')
            ),
        );
    }

    /**
     * Render full name to present product on order view
     *
     * @param bool $appendConfigurableName
     * @return string
     */
    public function getRenderFullName() {
        $result = $this->name;
        return $result;
    }



}