<?php

class SeoModule extends WebModule {

   // public $access = 0;

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
        $this->setIcon('flaticon-seo-man');
    }

    public function afterInstall() {
        Yii::app()->database->import($this->id);
        return parent::afterInstall();
    }

    public function afterUninstall() {
        //Удаляем таблицу модуля
        Yii::app()->db->createCommand()->dropTable(Redirects::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(SeoMain::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(SeoParams::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(SeoUrl::model()->tableName());
        return parent::afterUninstall();
    }

    public function getAdminMenu() {
        $c = Yii::app()->controller->id;
        return array(
            'system' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'icon' => $this->icon,
                        'active' => ($c == 'admin/seo') ? true : false,
                    ),
                ),
            )
        );
    }

}
