<?php

class ContactsModule extends WebModule {

    public $icon = 'flaticon-phone-call';

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*'
        ));
    }

    public function afterInstall() {
        Yii::app()->database->import($this->id);
        Yii::app()->settings->set($this->id, ConfigContactForm::defaultSettings());
        return parent::afterInstall();
    }

    public function afterUninstall() {
        Yii::app()->settings->clear($this->id);
        $db = Yii::app()->db->createCommand();
        $db->dropTable(ContactsMaps::model()->tableName());
        $db->dropTable(ContactsMarkers::model()->tableName());
        $db->dropTable(ContactsRouter::model()->tableName());
        $db->dropTable(ContactsRouterTranslate::model()->tableName());
        return parent::afterUninstall();
    }

    public function getRules() {
        return array(
            'contacts' => 'contacts/default/index',
            'contacts/captcha' => 'contacts/default/captcha',
        );
    }

    public function getAdminMenu() {
        return array(
            'modules' => array(
                'items' => array(array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'icon' => $this->icon,
                    ),
                )
            )
        );
    }

    public function getAdminSidebarMenu() {
        $c = Yii::app()->controller->id;
        return array(
            array(
                'label' => $this->name,
                'url' => array('/contacts/admin/default'),
                'active' => $this->adminHomeUrl,
                'icon' => $this->icon,
                'visible' => Yii::app()->user->isSuperuser
            ),
            array(
                'label' => Yii::t('ContactsModule.default', 'MAPS'),
                'url' => array('/contacts/admin/maps/index'),
                'active' => $this->hasActive('admin/maps'),
                'icon' => 'flaticon-map',
                'visible' => Yii::app()->user->isSuperuser
            ),
            array(
                'label' => Yii::t('ContactsModule.default', 'MARKERS'),
                'url' => array('/contacts/admin/markers/index'),
                'active' => $this->hasActive('admin/markers'),
                'icon' => 'flaticon-map-2',
                'visible' => Yii::app()->user->isSuperuser
            ),
        );
    }

}
