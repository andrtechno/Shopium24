<?php

class ShopModule extends WebModule {

    public function init() {
        $this->configure(array(
            'supplier' => false, //Поставщики (true / false)
            'relatedProducts' => true, //Связанные товары (true / false)
            'variations' => true, //Варианты товаров (true / false)
            'accept_currency' => true, //Привязывать товар к валюте (true / false)
        ));
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));

       // self::registerAssets();

    }

    public static function registerAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        if (is_dir($assets)) {
            if (Yii::app()->settings->get('shop', 'ajax_mode')) {
                $cs->registerScriptFile($baseUrl . '/ajax_mode.js', CClientScript::POS_HEAD);
            } else {
                $cs->registerScriptFile($baseUrl . '/other.js', CClientScript::POS_HEAD);
            }
           // $cs->registerScriptFile($baseUrl . '/common.js', CClientScript::POS_HEAD);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

    public function afterInstall() {
        Yii::app()->settings->set('shop', SettingsShopForm::defaultSettings());
        Yii::app()->database->import($this->id);
       /* Yii::app()->widgets->set('system', array(
            'alias_wgt' => 'mod.shop.blocks.popular.PopularBlock',
            'name' => 'Поп товары'
        ));
        Yii::app()->widgets->set('module', array(
            'alias_wgt' => 'mod.shop.blocks.search.SearchWidget',
            'name' => 'Поиск товаров'
        ));*/
        CFileHelper::createDirectory(Yii::getPathOfAlias('webroot.uploads.product'), 0777);
        CFileHelper::createDirectory(Yii::getPathOfAlias('webroot.uploads.categories'), 0777);
        Yii::app()->intallComponent('currency', 'mod.shop.components.CurrencyManager');
        return parent::afterInstall();
    }

    public function afterUninstall() {
        Yii::app()->settings->clear('shop');
        Yii::app()->unintallComponent('currency');
        $db = Yii::app()->db;
        $tablesArray = array(
            ShopCategory::model()->tableName(),
            ShopCategoryTranslate::model()->tableName(),
            ShopCurrency::model()->tableName(),
            ShopProduct::model()->tableName(),
            ShopProductCategoryRef::model()->tableName(),
            ShopProductTranslate::model()->tableName(),
        );
        foreach ($tablesArray as $table) {
            $db->createCommand()->dropTable($table);
        }
        CFileHelper::removeDirectory(Yii::getPathOfAlias('webroot.uploads.product'), array('traverseSymlinks' => true));
        CFileHelper::removeDirectory(Yii::getPathOfAlias('webroot.uploads.categories'), array('traverseSymlinks' => true));
        return parent::afterUninstall();
    }

    public static function getAddonsArray() {
        $a = array();
        $a[] = array(
            'label' => Yii::t('ShopModule.admin', 'Добавить товар'),
            'url' => '/admin/shop/products/create',
            'icon' => 'icon-coin',
        );
        return array(
            'mainButtons' => $a
        );
    }

    public function getRules() {
        return array(
            '/shop' => array('shop/index/index'),
            'product/<seo_alias>' => array('shop/product/view'),
            'product/captcha' => array('shop/product/captcha'),
            'shop/ajax/activateCurrency/<id>' => array('shop/ajax/activateCurrency'),
            'shop/ajax/rateProduct/<id>' => array('shop/ajax/rateProduct'),
            'shop/index/renderProductsBlock/<scope>' => array('shop/index/renderProductsBlock'),
            'shop/test' => array('shop/category/test'),
            array(
                'class' => 'mod.shop.components.ShopCategoryUrlRule'
            ),
            'products/search/*' => array('shop/category/search'),
 
        );
    }

    public function getAdminMenu() {
        $c = Yii::app()->controller->id;
        return array(
            'shop' => array(
                'label' => Yii::t('ShopModule.default', 'MODULE_NAME'),
                'visible' => Yii::app()->user->isSuperuser,
                'icon' => 'icon-coin',
                'items' => array(
                    array(
                        'label' => Yii::t('ShopModule.admin', 'PRODUCTS'),
                        'url' => Yii::app()->createUrl('shop/admin/products'),
                        'active' => ($c == 'admin/products') ? true : false,
                        'icon' => 'icon-cart-3',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                    array(
                        'label' => Yii::t('ShopModule.admin', 'CATEGORIES'),
                        'url' => Yii::app()->createUrl('shop/admin/category/create'),
                        'active' => ($c == 'admin/category') ? true : false,
                        'icon' => 'icon-folder-open',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                    array(
                        'label' => Yii::t('ShopModule.admin', 'CURRENCY'),
                        'url' => Yii::app()->createUrl('shop/admin/currency'),
                        'active' => ($c == 'admin/currency') ? true : false,
                        'icon' => 'icon-coin',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                    array(
                        'label' => Yii::t('core', 'SETTINGS'),
                        'url' => Yii::app()->createUrl('shop/admin/settings'),
                        'active' => ($c == 'admin/settings') ? true : false,
                        'icon' => 'icon-settings',
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                ),
            ),
        );
    }

    public function getAdminSidebarMenu() {
        Yii::import('mod.admin.widgets.EngineMainMenu');
        $mod = new EngineMainMenu;
        $items=$mod->findMenu('shop');
        return $items['items'];
    }


}
