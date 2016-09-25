<?php



/**
 * This is the model class for table "ShopPaymentMethod".
 *
 * The followings are the available columns in table 'ShopPaymentMethod':
 * @property integer $id
 * @property integer $currency_id
 * @property string $name
 * @property string $description
 * @property string $payment_system
 * @property integer $switch
 * @property integer $ordern
 */
class PaymentMethod extends ActiveRecord {

    protected $_MODULENAME = 'users';


    public function getGridColumns() {
        return array(
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->name), array("/shop/admin/paymentMethod/update", "id"=>$data->id))',
            ),
            array(
                'name' => 'switch',
                'filter' => array(1 => Yii::t('app', 'YES'), 0 => Yii::t('app', 'NO')),
                'value' => '$data->switch ? Yii::t("app", "YES") : Yii::t("app", "NO")'
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{update}{delete}',
            ),
        );
    }

    public function getForm() {
        return new CMSForm(array('id' => __CLASS__,
                    'attributes' => array(
                        'class' => 'form-horizontal'
                    ),
                    'elements' => array(
                        'name' => array(
                            'type' => 'text',
                        ),
                        'switch' => array(
                            'type' => 'dropdownlist',
                            'items' => array(
                                1 => Yii::t('app', 'YES'),
                                0 => Yii::t('app', 'NO')
                            ),
                        ),
                        'description' => array(
                            'type' => 'textarea',
                        ),
                        'currency_id' => array(
                            'type' => 'dropdownlist',
                            'items' => Html::listData(ShopCurrency::model()->findAll(), 'id', 'name'),
                        ),
                        'payment_system' => array(
                            'type' => 'dropdownlist',
                            'empty' => '---',
                            'items' => $this->getPaymentSystemsArray(),
                            'rel' => $this->id,
                        ),
                        '<div id="payment_configuration"></div>',
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'btn btn-success',
                            'label' => ($this->isNewRecord) ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                        )
                    )
                        ), $this);
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopPaymentMethod the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{payment_method}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(

            array('switch, ordern', 'numerical', 'integerOnly' => true),

            array('description', 'safe'),
            array('payment_system', 'safe'),
            // Search
            array('id,switch', 'safe', 'on' => 'search'),
        );
    }




    /**
     * @return array
     */
    public function scopes() {
        $alias = $this->getTableAlias();
        return array(
            'active' => array('order' => $alias . '.switch=1'),
            'orderByPosition' => array('order' => $alias . '.ordern ASC'),
            'orderByPositionDesc' => array('order' => $alias . '.ordern DESC'),

        );
    }

    /**
     * @return array of available payment systems. e.g array(id=>name)
     */
    public function getPaymentSystemsArray() {
        // Yii::import('application.modules.shop.components.payment.PaymentSystemManager');
        $result = array();

        $systems = new PaymentSystemManager;

        foreach ($systems->getSystems() as $system) {
            $result[(string) $system->id] = $system->name;
        }

        return $result;
    }

    /**
     * Renders form display on the order view page
     */
    public function renderPaymentForm() { //Order $order
        if ($this->payment_system) {
            $manager = new PaymentSystemManager;
            $system = $manager->getSystemClass($this->payment_system);
            print_r($system);
            die;
            return $system->renderPaymentForm($this); //$order
        }
    }

    /**
     * @return null|BasePaymentSystem
     */
    public function getPaymentSystemClass() {
        if ($this->payment_system) {
            $manager = new PaymentSystemManager;
            return $manager->getSystemClass($this->payment_system);
        }
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;



        $criteria->compare('id', $this->id);

        $criteria->compare('switch', $this->switch);

        $sort = new CSort;
        $sort->defaultOrder = $this->getTableAlias() . '.ordern ASC';

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => $sort
                ));
    }

}