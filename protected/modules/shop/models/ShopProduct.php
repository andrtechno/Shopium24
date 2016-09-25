<?php

//Yii::import('mod.shop.ShopModule');
Yii::import('mod.shop.models.ShopProductTranslate');
Yii::import('mod.shop.models.ShopProductCategoryRef');
;

/**
 * This is the model class for table "ShopProduct".
 *
 * The followings are the available columns in table 'ShopProduct':
 * @property integer $id

 * @property string $name
 * @property float $price Product price. For configurable product its min_price
 * @property float $max_price for configurable products. Used in ShopProduct::priceRange to display prices on category view
 * @property boolean $switch
 * @property string $short_description
 * @property string $full_description
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property string $quantity
 * @property string $availability
 * @property string $date_create
 * @property string $date_update
 * @property integer $votes 
 * @property integer $rating
 * @property integer $score
 * @property string $discount
 * @method ShopProduct active() Find Only active products
 * @method ShopProduct newest() Order products by creating date
 * @method ShopProduct byViews() Order by views count
 * @method ShopProduct byAddedToCart() Order by views count
 */
class ShopProduct extends ActiveRecord {

    /**
     * @var null Id if product to exclude from search
     */
    public $exclude = null;



    /**
     * @var string
     */
    public $translateModelName = 'ShopProductTranslate';

    /**
     * Multilingual attrs
     */
    public $name;
    public $short_description;
    public $full_description;
    public $seo_title;
    public $seo_description;
    public $seo_keywords;

    public $quantity = 1; //default value 
    /**
     * @var float min/max price
     */
    public $aggregation_price;

    /**
     * @var integer used only to render admin form
     */
    public $main_category_id;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return ShopProduct the static model class
     */
    protected $_MODULENAME = 'shop';
    public $route_update = '/shop/admin/products/update';
    public $route_switch = '/shop/admin/products/switch';
    public $route_delete = '/shop/admin/products/delete';

    public function init() {
        parent::init();
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }



    public function getGridColumns() {
        return array(
            array(
                'type' => 'raw',
                'header' => 'Категория/и',
                'name' => 'main_category_id',
                'htmlOptions' => array('style' => 'width:100px'),
                'value' => '$data->getCategories()',
                'filter' => false
            ),
            array(
                'name' => 'switch',
                'filter' => array(1 => Yii::t('core', 'Показанные'), 0 => Yii::t('core', 'Скрытые')),
                'value' => '$data->switch ? Yii::t("core", "Показан") : Yii::t("core", "Скрыт")'
            ),
            array('name' => 'price', 'value' => '$data->price'),

            array('name' => 'date_create', 'value' => 'CMS::date($data->date_create)'),
            array('name' => 'date_update', 'value' => '$data->date_update'),
            array('name' => 'quantity', 'value' => '$data->quantity'),
            array(
                'name' => 'name',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->name), array("/shop/admin/products/update", "id"=>$data->id))',
                'htmlOptions' => array('class' => 'textL')
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'group' => false,
                'template' => '{switch}{update}{delete}',
            ),
            'DEFAULT_COLUMNS' => array(
                array('class' => 'CheckBoxColumn'),
                array('class' => 'HandleColumn')
            ),
        );
    }

    public function getForm() {
       // Yii::import('zii.widgets.jui.CJuiDatePicker');
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        return array('id' => __CLASS__,
            'showErrorSummary' => true,
           // 'enctype' => 'multipart/form-data',
            'elements' => array(
                'content' => array(
                    'type' => 'form',
                    'title' => $this->t('TAB_GENERAL'),
                    'elements' => array(
                        'name' => array(
                            'type' => 'text',
                           
                        ),
                        'price' => array(
                            'type' => 'text',
                            'afterField' => '<span class="fieldIcon icon-coin"></span>'
                        ),



                        'currency_id' => array(
                            'type' => 'dropdownlist',
                            'items' => Html::listData(ShopCurrency::model()->findAll(array('condition' => '`t`.`default`=:int', 'params' => array(':int' => 0))), 'id', 'name'),
                            'empty' => '&mdash; Не привязывать &mdash;',
                            'visible' => Yii::app()->controller->module->accept_currency
                        ),
                        'main_category_id' => array(
                            'type' => 'dropdownlist',
                            'items' => ShopCategory::flatTree(),
                            'empty' => '---',
                        ),
                        //'dasdsa',
                        'switch' => array(
                            'type' => 'dropdownlist',
                            'items' => array(
                                1 => Yii::t('core', 'YES'),
                                0 => Yii::t('core', 'NO')
                            ),
                            'hint' => $this->t('HINT_SWITCH'),
                        ),

                        'short_description' => array(
                            'type' => 'textarea',
                            'class' => 'editor',
                            'hint' => (Yii::app()->settings->get('shop', 'auto_fill_short_desc')) ? Yii::t('ShopModule.admin', 'MODE_ENABLE', array(
                                        '{mode}' => Yii::t('ShopModule.SettingsShopForm', 'AUTO_FILL_SHORT_DESC')
                                    )) : null
                        ),

                    ),
                ),
                'warehouse' => array(
                    'type' => 'form',
                    'title' => $this->t('TAB_WAREHOUSE'), //'icon-drawer-3',
                    'elements' => array(
                        'quantity' => array(
                            'type' => 'text',
                        ),
                        'discount' => array(
                            'type' => 'text',
                            'hint' => $this->t('HINT_DISCOUNT'),
                        ),

                        'availability' => array(
                            'type' => 'dropdownlist',
                            'items' => self::getAvailabilityItems()
                        ),
                    ),
                ),
                'seo' => array(
                    'type' => 'form',
                    'title' => $this->t('TAB_SEO'), //'icon-globe',
                    'elements' => array(
                        'seo_title' => array(
                            'type' => 'text',
                        ),
                        'seo_keywords' => array(
                            'type' => 'textarea',
                        ),
                        'seo_description' => array(
                            'type' => 'textarea',
                        ),
                    ),
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'buttonS bGreen',
                    'label' => ($this->isNewRecord) ? Yii::t('core', 'CREATE', 0) : Yii::t('core', 'SAVE')
                )
            )
        );
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{shop_product}}';
    }

    public function scopes() {
        $alias = $this->getTableAlias(true);
        return array(
            'active' => array(
                'condition' => $alias . '.switch=1',
            ),
            'newToDay' => array(
                'condition' => $alias . '.date_create BETWEEN :fr AND :to AND ' . $alias . '.switch=1',
                'params' => array(
                    ':fr' => date('Y-m-d H:i:s', strtotime(date('Y-m-d'))),
                    ':to' => date('Y-m-d H:i:s', strtotime(date('Y-m-d')) + 86400)
                )
            ),
            'newest' => array('order' => $alias . '.date_create DESC'),
            'byViews' => array('order' => $alias . '.views DESC'),
            'byAddedToCart' => array('order' => $alias . '.added_to_cart_count DESC'),
        );
    }

    public static function getCSort() {
        $sort = new CSort;
        $sort->defaultOrder = 't.date_create DESC';
        $sort->attributes = array(
            '*',
            'name' => array(
                'asc' => 'translate.name',
                'desc' => 'translate.name DESC',
            ),
        );

        return $sort;
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('price', 'commaToDot'),
            array('price, main_category_id, currency_id, ordern', 'numerical'),
            array('switch', 'boolean'),
            array('quantity, availability', 'numerical', 'integerOnly' => true),
            array('name, price', 'required'),
            array('date_create', 'date', 'format' => 'yyyy-M-d H:m:s'),
            array('name, seo_title, seo_keywords, seo_description', 'length', 'max' => 255),
            array('short_description, full_description, discount', 'type', 'type' => 'string'),
            // Search
            array('id, name, switch, price, short_description, full_description, date_create, date_update, ordern', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'currency' => array(self::BELONGS_TO, 'ShopCurrency', 'currency_id'),
          

            'categorization' => array(self::HAS_MANY, 'ShopProductCategoryRef', 'product'),
            'categories' => array(self::HAS_MANY, 'ShopCategory', array('category' => 'id'), 'through' => 'categorization'),
            'mainCategory' => array(self::HAS_ONE, 'ShopCategory', array('category' => 'id'), 'through' => 'categorization', 'condition' => 'categorization.is_main = 1', 'scopes' => 'applyTranslateCriteria'),
            'translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
            // Product variation
            'variants' => array(self::HAS_MANY, 'ShopProductVariant', array('product_id'), 'with' => array('attribute', 'option'), 'order' => 'option.ordern'),
        );
    }


    /**
     * Find product by lbl.
     * Scope.
     * @param tinyint ShopProduct lbl
     * @return ShopProduct
     */
    public function limited($limit = null) {
        $this->getDbCriteria()->mergeWith(array(
            'limit' => $limit,
        ));
        return $this;
    }

    /**
     * Filter products by category
     * Scope
     * @param ShopCategory|string|array $categories to search products
     * @return ShopProduct
     */
    public function applyCategories($categories, $select = 't.*') {
        if ($categories instanceof ShopCategory)
            $categories = array($categories->id);
        else {
            if (!is_array($categories))
                $categories = array($categories);
        }

        $criteria = new CDbCriteria;

        if ($select)
            $criteria->select = $select;
        $criteria->join = 'LEFT JOIN `{{shop_product_category_ref}}` `categorization` ON (`categorization`.`product`=`t`.`id`)';
        $criteria->addInCondition('categorization.category', $categories);
        $this->getDbCriteria()->mergeWith($criteria);

        return $this;
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @param $params
     * @param $additionalCriteria
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($params = array(), $additionalCriteria = null) {
        $criteria = new CDbCriteria;

        $criteria->with = array(
            'categorization',
            'translate',
        );

        if ($additionalCriteria !== null)
            $criteria->mergeWith($additionalCriteria);


        $ids = $this->id;
        // Adds ability to accepts id as "1,2,3" string
        if (false !== strpos($ids, ',')) {
            $ids = explode(',', $this->id);
            $ids = array_map('trim', $ids);
        }

        $criteria->compare('t.id', $ids);
        $criteria->compare('t.ordern', $this->ordern);

        $criteria->compare('translate.name', $this->name, true);
        $criteria->compare('t.price', $this->price);
        $criteria->compare('t.switch', $this->switch);
        $criteria->compare('translate.short_description', $this->short_description, true);
        $criteria->compare('translate.full_description', $this->full_description, true);
        $criteria->compare('t.date_create', $this->date_create, true);
        $criteria->compare('t.date_update', $this->date_update, true);


        if (isset($params['category']) && $params['category']) {
            $criteria->with = array('categorization' => array('together' => true));
            $criteria->compare('categorization.category', $params['category']);
        }
        if (isset($_GET['ShopProduct']['categories']) && $_GET['ShopProduct']['categories']) {
            $criteria->with = array('categorization' => array('together' => true));
            $criteria->compare('categorization.category', $_GET['ShopProduct']['categories']);
        }

        // Id of product to exclude from search
        if ($this->exclude)
            $criteria->compare('t.id !', array(':id' => $this->exclude));
        /* Товары за сегодня */
        if (isset($params['today']) && $params['today'] == true) {
            $today = strtotime(date('Y-m-d'));
            $criteria->addBetweenCondition('t.date_create', date('Y-m-d H:i:s', $today), date('Y-m-d H:i:s', $today + 86400));
        }


        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'sort' => self::getCSort()
                        )
        );
    }

    /**
     * @return array
     */
    public function behaviors() {
        $a = array();

        $a['TranslateBehavior'] = array(
            'class' => 'app.behaviors.TranslateBehavior',
            'relationName' => 'translate',
            'translateAttributes' => array(
                'name',
                'short_description',
                'full_description',
                'seo_title',
                'seo_description',
                'seo_keywords',
            ),
        );
        if (Yii::app()->hasModule('comments')) {
            $a['comments'] = array(
                'class' => 'mod.comments.components.CommentBehavior',
                'model' => 'mod.shop.models.ShopProduct',
                'owner_title' => 'name', // Attribute name to present comment owner in admin panel
            );
        }
        if (Yii::app()->hasModule('discounts')) {
            $a['discounts'] = array(
                'class' => 'mod.discounts.components.DiscountBehavior'
            );
        }
        return $a;
    }


    public function beforeSave() {
        return parent::beforeSave();
    }


    /**
     * Delete related data.
     */
    public function afterDelete() {
        // Delete categorization
        ShopProductCategoryRef::model()->deleteAllByAttributes(array(
            'product' => $this->id
        ));


        return parent::afterDelete();
    }


    /**
     * @return array
     */
    public static function getAvailabilityItems() {
        return array(
            1 => Yii::t('ShopModule.ShopProduct', 'AVAILABILITY_LIST', 1),
            2 => Yii::t('ShopModule.ShopProduct', 'AVAILABILITY_LIST', 2),
        );
    }

    /**
     * @return array
     */
    public static function getProductLabels() {
        return array(
            1 => Yii::t('ShopModule.default', 'PRODUCT_LABEL', 1),
            2 => Yii::t('ShopModule.default', 'PRODUCT_LABEL', 2),
            3 => Yii::t('ShopModule.default', 'PRODUCT_LABEL', 3),
        );
    }

    /**
     * Set product categories and main category
     * @param array $categories ids.
     * @param integer $main_category Main category id.
     */
    public function setCategories(array $categories, $main_category) {
        $dontDelete = array();

        if (!ShopCategory::model()->countByAttributes(array('id' => $main_category)))
            $main_category = 1;

        if (!in_array($main_category, $categories))
            array_push($categories, $main_category);


        foreach ($categories as $c) {
            $count = ShopProductCategoryRef::model()->countByAttributes(array(
                'category' => $c,
                'product' => $this->id,
                    ));

            if ($count == 0) {
                $record = new ShopProductCategoryRef;
                $record->category = (int) $c;
                $record->product = $this->id;
                $record->switch = $this->switch; // new param
                $record->save(false, false, false);
            }

            $dontDelete[] = $c;
        }

        // Clear main category
        ShopProductCategoryRef::model()->updateAll(array(
            'is_main' => 0,
            'switch' => $this->switch
                ), 'product=:p', array(':p' => $this->id));

        // Set main category
        ShopProductCategoryRef::model()->updateAll(array(
            'is_main' => 1,
            'switch' => $this->switch,
                ), 'product=:p AND category=:c', array(':p' => $this->id, ':c' => $main_category));

        // Delete not used relations
        if (sizeof($dontDelete) > 0) {
            $cr = new CDbCriteria;
            $cr->addNotInCondition('category', $dontDelete);

            ShopProductCategoryRef::model()->deleteAllByAttributes(array(
                'product' => $this->id,
                    ), $cr);
        } else {
            // Delete all relations
            ShopProductCategoryRef::model()->deleteAllByAttributes(array(
                'product' => $this->id,
            ));
        }
    }

  

    /**
     * Calculate product price by its variants, configuration and self price
     * @static
     * @param $product
     * @param array $variants
     * @param $configuration
     */
    public static function calculatePrices($product) {
        if (($product instanceof ShopProduct) === false)
            $product = ShopProduct::model()->findByPk($product);


            $result = $product->price;




        return $result;
    }

    /**
     * Apply price format
     * @static
     * @param $price
     * @return string formatted price
     */
    public static function formatPrice($price) {
        $c = Yii::app()->settings->get('shop');
        return iconv("windows-1251", "UTF-8", number_format($price, $c['fp_penny'], chr($c['fp_separator_thousandth']), chr($c['fp_separator_hundredth'])));
    }

    /**
     * Convert to active currency and format price.
     * Display min and max price for configurable products.
     * Used in product listing.
     * @return string
     */
    public function priceRange() {
        if (Yii::app()->hasModule('discounts')) {
            $price = $this->appliedDiscount ? $this->toCurrentCurrency('discountPrice') : Yii::app()->currency->convert($this->price);
        } else {
            $price = Yii::app()->currency->convert($this->price);
        }

        $max_price = Yii::app()->currency->convert($this->max_price);
        // $symbol = Yii::app()->currency->active->symbol;

        // if($this->currency_id){
        //      return self::formatPrice(($price*$this->currency->rate)/$this->currency->rate_old) . ' ' . $symbol;
        //   }else{
        return self::formatPrice($price);
        //  }
    }

    /**
     * Replaces comma to dot
     * @param $attr
     */
    public function commaToDot($attr) {
        $this->$attr = str_replace(',', '.', $this->$attr);
    }

    /**
     * Convert price to current currency
     *
     * @param string $attr
     * @return mixed
     */
    public function toCurrentCurrency($attr = 'price') {
        return Yii::app()->currency->convert($this->$attr);
    }




    /**
     * Check if product is on warehouse.
     *
     * @return bool
     */
    public function getIsAvailable() {
        return $this->availability == 1;
    }






    public function getCategories() {
        $content = array();
        foreach ($this->categories as $c) {
            $content[] = $c->name;
        }
        return implode(', ', $content);
    }

    public function keywords() {
        $config = Yii::app()->settings->get('shop');
        if ($config['auto_gen_meta']) {
            return $this->replaceMeta($config['auto_gen_tpl_keywords']);
        } else {
            return $this->seo_keywords;
        }
    }

    public function description() {
        $config = Yii::app()->settings->get('shop');
        if ($config['auto_gen_meta']) {
            return $this->replaceMeta($config['auto_gen_tpl_description']);
        } else {
            return $this->seo_description;
        }
    }

    public function title() {
        $config = Yii::app()->settings->get('shop');
        if ($config['auto_gen_meta']) {
            return $this->replaceMeta($config['auto_gen_tpl_title']);
        } else {
            return ($this->seo_title) ? $this->seo_title : $this->name;
        }
    }

    public function replaceMeta($text) {
        $replace = array(
            "%PRODUCT_NAME%",
            "%PRODUCT_PRICE%",
            "%PRODUCT_MAIN_CATEGORY%",
            "%CURRENT_CURRENCY%"
        );
        $to = array(
            $this->name,
            $this->price,
            $this->mainCategory->name,
            Yii::app()->currency->active->symbol,
        );
        return CMS::textReplace($text, $replace, $to);
    }

}