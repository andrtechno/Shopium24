<?php

class PlansModule extends WebModule {

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));

        $this->setIcon('flaticon-add');
    }

    public function afterInstall() {
        Yii::app()->database->import($this->id);
        return parent::afterInstall();
    }

    public function afterUninstall() {
        $db = Yii::app()->db;
        $db->createCommand()->dropTable(Plans::model()->tableName());
        $db->createCommand()->dropTable(PlansOptions::model()->tableName());
        $db->createCommand()->dropTable(PlansOptionsGroups::model()->tableName());
        $db->createCommand()->dropTable(PlansOptionsRel::model()->tableName());
        return parent::afterUninstall();
    }

    public function getRules() {
        return array(
            'plans' => 'plans/default/index',
        );
    }

    public function getAdminMenu() {
        $c = Yii::app()->controller->module->id;
        return array(
            'plans' => array(
                'label' => $this->name,
                'visible' => Yii::app()->user->isSuperuser,
                'icon' => $this->icon,
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'active' => ($c == 'plans') ? true : false,
                        'icon' => 'icon-percent'
                    ),
                    array(
                        'label' => Yii::t('PlansModule.default', 'OPTIONS'),
                        'url' => array('/admin/plans/options'),
                        'active' => ($c == 'options') ? true : false,
                        'icon' => 'icon-percent'
                    ),
                    array(
                        'label' => Yii::t('PlansModule.default', 'OPTIONS_GROUP'),
                        'url' => array('/admin/plans/groups'),
                        'active' => ($c == 'groups') ? true : false,
                        'icon' => 'icon-percent'
                    ),
                ),
            ),
        );
    }

    public function getAdminSidebarMenu() {
        Yii::import('mod.admin.widgets.EngineMainMenu');
        $mod = new EngineMainMenu();
        $items = $mod->findMenu($this->id);
        return $items['items'];
    }

}
