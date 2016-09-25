<?php

/**
 * This is the model class for table "Order".
 *
 * The followings are the available columns in table 'Order':
 * @property integer $id
 * @property integer $user_id
 * @property string $secret_key

 * @property integer $payment_id

 * @property float $total_price Sum of ordered products
 * @property float $full_price Total price + delivery price
 * @property integer $status_id
 * @property integer $paid

 * @property string $ip_address
 * @property string $date_create
 * @property string $date_update
 */
class Order extends ActiveRecord {

    const MODULE_ID = 'cart';


    public function getGridColumns() {
        Yii::import('mod.shop.components.SProductsPreviewColumn');
        return array(
            array(
                'class' => 'ProductsPreviewColumn'
            ),
            array(
                'name' => 'full_price',
                'header' => Yii::t('CartModule.Order', 'FULL_PRICE'),
                'value' => 'ShopProduct::formatPrice($data->full_price)',
            ),
            array(
                'name' => 'date_create',
                // 'filter' => Html::listData(ShopDeliveryMethod::model()->orderByPosition()->findAll(), 'id', 'name'),
                'value' => 'CMS::date($data->date_create)'
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{update}{delete}',
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'CheckBoxColumn')
            ),
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Order the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{order}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
           // array('payment_id', 'validatePayment'),
            array('status_id', 'validateStatus'),
            array('paid', 'boolean'),
            // Search
            array('id, user_id, total_price, status_id, paid, ip_address, date_create, date_update', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array
     */
    public function relations() {
        return array(
            'products' => array(self::HAS_MANY, 'OrderProduct', 'order_id'),
            'status' => array(self::BELONGS_TO, 'OrderStatus', 'status_id'),
            'product' => array(self::BELONGS_TO, 'OrderProduct', 'id'),
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
                //  'paymentMethod' => array(self::BELONGS_TO, 'ShopPaymentMethod', 'payment_id'),
                // 'paymentMethods' => array(self::HAS_MANY, 'ShopPaymentMethod', array('payment_id' => 'id'), 'through' => 'categorization', 'order' => 'paymentMethods.ordern'),
        );
    }

    /**
     * @return array
     */
    public function scopes() {
        $alias = $this->getTableAlias(true);
        return array(
            'new' => array('condition' => $alias . '.status_id=1'),
        );
    }

    public function validatePayment() {
        if (ShopPaymentMethod::model()->countByAttributes(array('id' => $this->payment_id)) == 0)
            $this->addError('payment_id', Yii::t('CartModule.core', 'Необходимо выбрать способ оплаты.'));
    }

    /**
     * Check if status exists
     */
    public function validateStatus() {
        if ($this->status_id && OrderStatus::model()->countByAttributes(array('id' => $this->status_id)) == 0)
            $this->addError('status_id', Yii::t('CartModule.core', 'Ошибка проверки статуса.'));
    }

    /**
     * @return bool
     */
    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->secret_key = $this->createSecretKey();
            $this->ip_address = Yii::app()->request->userHostAddress;


            if (!Yii::app()->user->isGuest)
                $this->user_id = Yii::app()->user->id;
        }

        // Set `New` status
        if (!$this->status_id)
            $this->status_id = 1;
        if (parent::beforeSave()) {

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function afterDelete() {
        foreach ($this->products as $ordered_product)
            $ordered_product->delete();

        return parent::afterDelete();
    }

    /**
     * Create unique key to view orders
     * @param int $size
     * @return string
     */
    public function createSecretKey($size = 10) {

        $result = '';
        $chars = '1234567890qweasdzxcrtyfghvbnuioplkjnm';
        while (mb_strlen($result, 'utf8') < $size) {
            $result .= mb_substr($chars, rand(0, mb_strlen($chars, 'utf8')), 1);
        }

        if (Order::model()->countByAttributes(array('secret_key' => $result)) > 0)
            $this->createSecretKey($size);

        return $result;
    }

    /**
     * Update total
     */
    public function updateTotalPrice() {
        $this->total_price = 0;
        $products = OrderProduct::model()->findAllByAttributes(array('order_id' => $this->id));

        foreach ($products as $p) {
            //if($p->currency_id){
            // $currency = ShopCurrency::model()->findByPk($p->currency_id);
            // $this->total_price += $p->price * $currency->rate * $p->quantity;
            // }else{
            $curr_rate = Yii::app()->currency->active->rate;

            $this->total_price += $p->price * $curr_rate * $p->quantity;


            //  }
        }
        $this->save(false, false, false);
    }

    /**
     * Обновляем продолжительность магазина пользователя.
     */
    public function updateExpiredUser() {
        if ($this->paid) {
            if ($this->user_id == Yii::app()->user->id) {
                foreach ($this->products as $product) {
                    if ($product->prd->mainCategory == 1) {
                        $user = $this->user;
                        $user->expired = $user->getExpiredByMonth($product->quantity);
                        $user->save(false, false, false);
                        Yii::log('save user', 'info', 'application');
                    }
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function getStatus_name() {
        if ($this->status)
            return $this->status->name;
    }

    public function getStatus_color() {
        if ($this->status)
            return $this->status->color;
    }

    public function getPayment_name() {
        $model = ShopPaymentMethod::model()->findByPk($this->payment_id);
        if ($model)
            return $model->name;
    }

    /**
     * @return mixed
     */
    public function getFull_price() {
        if (!$this->isNewRecord) {
            $result = $this->total_price;
            return $result;
        }
    }

    /**
     * Add product to existing order
     *
     * @param ShopProduct $product
     * @param integer $quantity
     * @param float $price
     */
    public function addProduct($product, $quantity, $price) {

        if (!$this->isNewRecord) {
            $ordered_product = new OrderProduct;
            $ordered_product->order_id = $this->id;
            $ordered_product->product_id = $product->id;
            $ordered_product->currency_id = $product->currency_id;
            $ordered_product->name = $product->name;
            $ordered_product->quantity = $quantity;
            $ordered_product->price = $price;
            print_r($ordered_product->getErrors());
            die;
            $ordered_product->save();

            // Raise event
            $event = new CModelEvent($this, array(
                        'product_model' => $product,
                        'ordered_product' => $ordered_product,
                        'quantity' => $quantity
                    ));
            $this->onProductAdded($event);
        }
    }

    /**
     * Delete ordered product from order
     *
     * @param $id
     */
    public function deleteProduct($id) {

        $model = OrderProduct::model()->findByPk($id);

        if ($model) {
            $model->delete();

            $event = new CModelEvent($this, array(
                        'ordered_product' => $model
                    ));
            $this->onProductDeleted($event);
        }
    }

    /**
     * @param $event
     */
    public function onProductAdded($event) {
        $this->raiseEvent('onProductAdded', $event);
    }

    /**
     * @param $event
     */
    public function onProductDeleted($event) {
        $this->raiseEvent('onProductDeleted', $event);
    }

    /**
     * @param $event
     */
    public function onProductQuantityChanged($event) {
        $this->raiseEvent('onProductQuantityChanged', $event);
    }

    /**
     * @return ActiveDataProvider
     */
    public function getOrderedProducts() {

        $products = new OrderProduct;
        $products->order_id = $this->id;

        return $products->search();
    }

    /**
     * @param array $data
     */
    public function setProductQuantities(array $data) {
        foreach ($this->products as $product) {
            if (isset($data[$product->id])) {
                if ((int) $product->quantity !== (int) $data[$product->id]) {
                    $event = new CModelEvent($this, array(
                                'ordered_product' => $product,
                                'new_quantity' => (int) $data[$product->id]
                            ));
                    $this->onProductQuantityChanged($event);
                }

                $product->quantity = (int) $data[$product->id];
                $product->save(false, false);
            }
        }
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('total_price', $this->total_price);
        $criteria->compare('status_id', $this->status_id);
        $criteria->compare('paid', $this->paid);

        $criteria->compare('ip_address', $this->ip_address, true);
        $criteria->compare('date_create', $this->date_create, true);
        $criteria->compare('date_update', $this->date_update, true);

        $sort = new CSort;
        $sort->defaultOrder = $this->getTableAlias() . '.date_create DESC';
        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort
                ));
    }

    /**
     * Load history
     *
     * @return array
     */
    public function getHistory() {
        $cr = new CDbCriteria;
        $cr->order = 'date_create ASC';

        return OrderHistory::model()->findAllByAttributes(array('order_id' => $this->id), $cr);
    }

}