<?php

/**
 * Модуль стратичных страниц
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.pages
 * @uses WebModule
 */
class PagesModule extends WebModule {

    public $icon = 'flaticon-edit-2';

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*'
        ));
    }

    public function afterInstall() {
        Yii::app()->database->import($this->id);
        return parent::afterInstall();
    }

    public function afterUninstall() {
        //Удаляем таблицу модуля
        Yii::app()->db->createCommand()->dropTable(Page::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(PageTranslate::model()->tableName());
        return parent::afterUninstall();
    }

    public function getRules() {
        return array(
            'page/<url>' => 'pages/default/index',
            'html/<page>' => 'pages/default/html',
        );
    }

    public function getAdminMenu() {
        return array(
            'modules' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'icon' => $this->icon,
                    ),
                ),
            ),
        );
    }

    public function getAddonsArray() {
        return array(
            'mainButtons' => array(
                array(
                    'label' => Yii::t('PagesModule.default', 'CREATE'),
                    'url' => array('/admin/pages/default/create'),
                    'icon' => $this->icon
                )
            )
        );
    }

}
