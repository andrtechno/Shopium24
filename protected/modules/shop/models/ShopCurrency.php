<?php

/**
 * This is the model class for table "shop_currency".
 *
 * The followings are the available columns in table 'shop_currency':
 * @property integer $id
 * @property string $name
 * @property string $iso
 * @property string $symbol
 * @property float $rate
 * @property integer $main
 * @property integer $default
 */
class ShopCurrency extends ActiveRecord {

    protected $_MODULENAME = 'shop';

    public function getGridColumns() {
        Yii::import('mod.shop.ShopModule');
        return array(
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->name), array("/shop/admin/currency/update", "id"=>$data->id))',
            ),
            array('name' => 'rate', 'value' => '$data->rate'),
            array('name' => 'rate_old', 'value' => '$data->rate_old'),
            array('name' => 'symbol', 'value' => '$data->symbol'),
            array(
                'name' => 'main',
                'filter' => array(1 => Yii::t('core', 'YES'), 0 => Yii::t('core', 'NO')),
                'value' => '$data->main ? Yii::t("core", "YES") : Yii::t("core", "NO")'
            ),
            array(
                'name' => 'default',
                'filter' => array(1 => Yii::t('ShopModule.admin', 'YES'), 0 => Yii::t('core', 'NO')),
                'value' => '$data->default ? Yii::t("core", "YES") : Yii::t("core", "NO")'
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'CheckBoxColumn')
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{update}{delete}',
                'hidden' => array('delete' => array(1, 3))
            ),
        );
    }

    public function getForm() {
        return new CMSForm(array('id' => __CLASS__,
                    'showErrorSummary' => false,
                    'elements' => array(
                        'name' => array('type' => 'text'),
                        'main' => array(
                            'type' => 'dropdownlist',
                            'items' => array(
                                0 => Yii::t('core', 'NO'),
                                1 => Yii::t('core', 'YES')
                            ),
                            'hint' => $this->t('HINT_MAIN')
                        ),
                        'default' => array(
                            'type' => 'dropdownlist',
                            'items' => array(
                                0 => Yii::t('core', 'NO'),
                                1 => Yii::t('core', 'YES')
                            ),
                            'hint' => $this->t('HINT_DEFAULT')
                        ),
                        'iso' => array('type' => 'text'),
                        'symbol' => array('type' => 'text'),
                        'rate' => array(
                            'type' => 'text',
                            'hint' => $this->t('HINT_RATE')
                        ),
                        'rate_old' => array('type' => 'text', 'disabled' => true),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'buttonS bGreen',
                            'label' => ($this->isNewRecord) ? Yii::t('core', 'CREATE', 0) : Yii::t('core', 'SAVE')
                        )
                    )
                        ), $this);
    }

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ShopCurrency the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_currency}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name, iso, symbol, rate', 'required'),
            array('main, default', 'numerical', 'integerOnly' => true),
            //array('rate_old, rate', 'validateRate', 'on'=>'update'),
            array('rate, rate_old', 'numerical'),
            array('name', 'length', 'max' => 255),
            array('iso, symbol', 'length', 'max' => 10),
            array('id, name, iso, symbol, rate, main, default', 'safe', 'on' => 'search'),
        );
    }

    /*  public function validateRate($attr){
      $labels = $this->attributeLabels();
      $check = User::model()->countByAttributes(array(
      $attr => $this->$attr,
      ), 't.id != :id', array(':id' => (int) $this->id));

      if ($check > 0)
      $this->addError($attr, Yii::t('usersModule.site', 'ERROR_ALREADY_USED', array('{attr}' => $labels[$attr])));
      } */

    public function afterSave() {
        //Yii::app()->cache->delete(Yii::app()->currency->cacheKey);

        if ($this->default)
            ShopCurrency::model()->updateAll(array('default' => 0), 'id != :id', array(':id' => $this->id));

        if ($this->main)
            ShopCurrency::model()->updateAll(array('main' => 0), 'id != :id', array(':id' => $this->id));

        parent::afterSave();
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('t.iso', $this->iso, true);
        $criteria->compare('t.symbol', $this->symbol, true);
        $criteria->compare('t.rate', $this->rate);
        $criteria->compare('t.rate_old', $this->rate_old);
        $criteria->compare('t.main', $this->main);
        $criteria->compare('t.default', $this->default);

        return new ActiveDataProvider($this, array('criteria' => $criteria));
    }

}