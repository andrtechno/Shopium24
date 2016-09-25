<?php

class CartModule extends WebModule {

    public $tpl_keys = array(
        '%ORDER_ID%',
        '%ORDER_KEY%',
        '%ORDER_DELIVERY_NAME%',
        '%ORDER_PAYMENT_NAME%',
        '%TOTAL_PRICE%',
        '%CURRENT_CURRENCY%',
        '%FOR_PAYMENY%',
        '%LIST%',
        '%LINK_TO_ORDER%',
    );

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
                // $this->id . '.components.payment.*'
        ));

        // self::registerAssets();
    }

    public function afterInstall() {
        Yii::app()->settings->set($this->id, SettingsCartForm::defaultSettings());
        Yii::app()->database->import($this->id);
        Yii::app()->intallComponent('cart', 'mod.cart.components.Cart');
        return parent::afterInstall();
    }

    public function afterUninstall() {
        Yii::app()->settings->clear($this->id);
        Yii::app()->unintallComponent('cart');
        $db = Yii::app()->db;
        $tablesArray = array(
            Order::model()->tableName(),
            OrderProduct::model()->tableName(),
            OrderStatus::model()->tableName(),
            ShopPaymentMethod::model()->tableName(),
            ShopPaymentMethodTranslate::model()->tableName(),
        );
        foreach ($tablesArray as $table) {
            $db->createCommand()->dropTable($table);
        }
        return parent::afterInstall();
    }

    public function getAddonsArray() {
        return array(
            'mainButtons' => array(
                array(
                    'label' => Yii::t('CartModule.admin', 'ORDER', 0),
                    'url' => '/admin/cart',
                    'icon' => 'icon-cart-3',
                    'count' => Order::model()->new()->count()
                )
            )
        );
    }

    public function getRules() {
        return array(
            'cart' => 'cart/default/index',
            'cart/add' => 'cart/default/add',
            'cart/remove/<id:(\d+)>' => 'cart/default/remove',
            'cart/clear' => 'cart/default/clear',
            'cart/renderSmallCart' => 'cart/default/renderSmallCart',
            'cart/payment' => 'cart/default/payment',
            'cart/recount' => 'cart/default/recount',
            'cart/view/<secret_key>' => 'cart/default/view',
            'cart/processPayment/*' => 'cart/payment/process',
            'notify' => array('cart/notify/index'),
        );
    }

    public function getAdminMenu() {
        $c = Yii::app()->controller->id;
        return array(
            'orders' => array(
                'label' => Yii::t('CartModule.admin', 'ORDER', 0),
                'url' => array('/admin/cart'),
                'icon' => 'icon-cart-3',
                'itemOptions' => array('class' => 'hasRedCircle circle-orders'),
                'visible' => Yii::app()->user->isSuperuser,
                'items' => array(
                    array(
                        'label' => Yii::t('CartModule.admin', 'STATUSES'),
                        'url' => Yii::app()->createUrl('/admin/cart/statuses'),
                        'icon' => 'icon-stats',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                    array(
                        'label' => Yii::t('CartModule.admin', 'PAYMENTS'),
                        'url' => Yii::app()->createUrl('cart/admin/paymentMethod'),
                        'active' => ($c == 'admin/paymentMethod') ? true : false,
                        'icon' => 'icon-credit-card',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                    array(
                        'label' => Yii::t('core', 'SETTINGS'),
                        'url' => Yii::app()->createUrl('/admin/cart/settings'),
                        'icon' => 'icon-settings',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                )
            ),
            'shop' => array(
                'items' => array(
                    array(
                        'label' => Yii::t('CartModule.admin', 'PAYMENTS'),
                        'url' => Yii::app()->createUrl('cart/admin/paymentMethod'),
                        'active' => ($c == 'admin/paymentMethod') ? true : false,
                        'icon' => 'icon-credit-card',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                )
            )
        );
    }

    public function getAdminSidebarMenu() {
        Yii::import('mod.admin.widgets.EngineMainMenu');
        $mod = new EngineMainMenu;
        $items = $mod->findMenu('shop');
        return $items['items'];
    }

    public static function registerAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        if (is_dir($assets)) {
            $cs->registerScriptFile($baseUrl . '/cart.js', CClientScript::POS_HEAD);
            $cs->registerCssFile($baseUrl . '/style.css');
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}
