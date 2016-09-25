<?php

class CoreModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
    }

    public function getAdminMenu() {
        $c = Yii::app()->controller->id;
        return array(
            'system' => array(
                //  'visible'=>  Yii::app()->user->isSuperuser,
                'items' => array(
                    array(
                        'label' => Yii::t('app', 'MODULES'),
                        'url' => array('/admin/core/modules'),
                        'icon' => 'flaticon-data2',
                        'active' => self::activeMenu('core', 'admin/modules'),
                    ),
                    array(
                        'label' => Yii::t('app', 'LANGUAGES'),
                        'url' => array('/admin/core/languages'),
                        'icon' => 'flaticon-lang',
                        'active' => self::activeMenu('core', 'admin/languages'),
                    ),
                    array(
                        'label' => Yii::t('app', 'SETTINGS'),
                        'url' => array('/admin/core/settings'),
                        'icon' => 'flaticon-settings',
                        'active' => self::activeMenu('core', 'admin/settings'),
                    ),
                    array(
                        'label' => Yii::t('app', 'CATEGORIES'),
                        'url' => array('/admin/core/categories'),
                        'icon' => 'flaticon-books',
                        'active' => self::activeMenu('core', 'admin/categories'),
                    ),
                    array(
                        'label' => Yii::t('app', 'ENGINE_MENU'),
                        'url' => array('/admin/core/menu'),
                        'icon' => 'flaticon-menu',
                        'active' => self::activeMenu('core', 'admin/menu'),
                    ),
                    array(
                        'label' => Yii::t('app', 'WIDGETS'),
                        'url' => array('/admin/core/widgets'),
                        'icon' => 'flaticon-chip',
                        'active' => self::activeMenu('core', 'admin/widgets'),
                    ),
                    array(
                        'label' => Yii::t('app', 'BLOCKS'),
                        'url' => array('/admin/core/blocks'),
                        'icon' => 'flaticon-cubes',
                        'active' => self::activeMenu('core', 'admin/blocks'),
                    ),
                    array(
                        'label' => Yii::t('app', 'TEMPLATE'),
                        'url' => array('/admin/core/template'),
                        'active' => self::activeMenu('core', 'admin/template'),
                        'icon' => 'flaticon-templete',
                        'visible' => false,
                    ),
                    array(
                        'label' => Yii::t('app', 'DATABASE'),
                        'url' => array('/admin/core/database'),
                        'icon' => 'flaticon-database',
                        'active' => self::activeMenu('core', 'admin/database'),
                    ),
                    array(
                        'label' => Yii::t('app', 'SECURITY'),
                        'url' => array('/admin/core/security'),
                        'icon' => 'flaticon-security',
                        'active' => self::activeMenu('core', 'admin/security'),
                    ),
                    array(
                        'label' => Yii::t('app', 'SVC'),
                        'url' => array('/admin/core/service'),
                        'icon' => 'flaticon-operator-2',
                        'active' => self::activeMenu('core', 'admin/service'),
                       // 'visible' => true
                    ),
                ),
            )
        );
    }

    public function getAdminSidebarMenu() {
        return $this->adminMenu['system']['items'];
    }

    public function getName() {
        return Yii::t('app', 'SYSTEM');
    }

    public function getDescription() {
        return Yii::t('app', 'SYSTEM');
    }

}
