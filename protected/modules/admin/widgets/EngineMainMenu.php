<?php

class EngineMainMenu extends CWidget {

    private $_items;
    public $type = 'top';

    const CACHE_ID = 'EngineMainMenu';

    public function init() {
        // Minimum configuration
        $this->_items = array(
            'system' => array(
                'label' => Yii::t('app', 'SYSTEM'),
                'icon' => 'flaticon-wrench'
            ),
            'modules' => array(
                'label' => Yii::t('app', 'MODULES'),
                'icon' => 'flaticon-menu'
            ),
        );
    }

    /**
     * Render menu
     */
    public function run() {
        $cacheID = self::CACHE_ID . '-' . Yii::app()->language;
        $items = Yii::app()->cache->get($cacheID);
        if ($items === false) {
            $found = $this->findMenu();
            $items = CMap::mergeArray($this->_items, $found);
            Yii::app()->cache->set($cacheID, $items, 3600 * 12);
        }


        $options = array(
            'htmlOptions' => array('class' => 'nav navbar-nav', 'id' => 'navigation'),
            'items' => $items,
            'cssFile' => false
        );

        $this->widget('ext.mbmenu.AdminMenu', $options);
    }

    /**
     * Find and load module menu.
     */
    public function findMenu($mod = false) {
        $result = array();
        $installedModules = ModulesModel::getInstalled();
        foreach ($installedModules as $module) {
            Yii::import('mod.' . $module->name . '.' . ucfirst($module->name) . 'Module');
            //if (method_exists($class, 'getAdminMenu')) {
            //    $result = CMap::mergeArray($result, $class::getAdminMenu());
            //}
            if(isset(Yii::app()->getModule($module->name)->adminMenu)){
            $result = CMap::mergeArray($result, Yii::app()->getModule($module->name)->getAdminMenu());
            }
        }
        return ($mod) ? $result[$mod] : $result;
    }

}