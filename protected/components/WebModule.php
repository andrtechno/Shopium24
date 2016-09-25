<?php

/**
 * Базовый модуль.
 * 
 * @uses CWebModule
 * @package components
 */
class WebModule extends CWebModule {

    public $_rules = array();
    public $_config = array();
    public $_assetsUrl = null;
    public $baseModel;
    public $license;
    protected $_info = array();
    protected $_adminMenu = array();
    public $sidebar = false;
    protected $adminSidebarMenu;
    public $_icon;

    public function getAdminSidebarMenu() {
        return false;
    }

    public static function hasActive($route) {
        return (Yii::app()->controller->id == $route) ? true : false;
    }

    public static function hasActiveModule($mod) {
        return (Yii::app()->controller->module->id == $mod) ? true : false;
    }

    public static function activeMenu($mod, $route = false) {
        if ($route) {
            
        }
        return (Yii::app()->controller->module->id == $mod && Yii::app()->controller->id == $route) ? true : false;
    }

    public function beforeControllerAction($controller, $action) {

        if ($controller instanceof AdminController) {
            Yii::app()->setComponents(array(
                'errorHandler' => array(
                    'errorAction' => 'admin/errors/index',
                ),
            ));
        }

        if (parent::beforeControllerAction($controller, $action)) {

            // if(Yii::app()->hasComponent('access')){
            if (!Yii::app()->access->check($controller->module->access)) {
                throw new CHttpException(401);
            }
            // }
            return true;
        } else {
            return false;
        }
    }

    public function getRules() {
        return $this->_rules;
    }

    public function initAdmin() {
        $this->setImport(array(
            'admin.models.*',
            'admin.components.*',
            'admin.widgets.*',
        ));
        $this->defaultController = 'admin';
    }

    /**
     * Publish admin stylesheets,images,scripts,etc.. and return assets url
     *
     * @access public
     * @return string Assets url
     */
    public function getAssetsUrl() {
        if ($this->_assetsUrl === null) {
            $this->_assetsUrl = Yii::app()->getAssetManager()->publish(
                    Yii::getPathOfAlias('mod.' . $this->id . '.assets'), false, -1, YII_DEBUG
            );
        }
        return $this->_assetsUrl;
    }

    /**
     * Set assets url
     *
     * @param string $url
     * @access public
     * @return void
     */
    public function setAssetsUrl($url) {
        $this->_assetsUrl = $url;
    }

    /**
     * Method will be called after module installed
     */
    public function afterInstall() {
        Yii::app()->cache->flush();
        Yii::app()->widgets->clear();
        return true;
    }

    /**
     * Method will be called after module removed
     */
    public function afterUninstall() {
        Yii::app()->cache->flush();
        Yii::app()->widgets->clear();
        return true;
    }

    public function __get($name) {
        if (array_key_exists($name, $this->_config))
            return $this->_config[$name];
        else
            return parent::__get($name);
    }

    public function __set($name, $value) {
        try {
            parent::__set($name, $value);
        } catch (CException $e) {
            $this->_config[$name] = $value;
        }
    }

    public function getIcon() {
        return $this->_icon;
    }

    public function setIcon($icon) {
        $this->_icon = $icon;
    }

    public function getAuthor() {
        return 'dev@corner-cms.com';
    }

    public function getName() {
        $name = ucfirst($this->id);
        return Yii::t("{$name}Module.default", 'MODULE_NAME');
    }

    public function getDescription() {
        $name = ucfirst($this->id);
        return Yii::t("{$name}Module.default", 'MODULE_DESC');
    }

    public function getAdminHomeUrl() {
        return Yii::app()->createUrl('/admin/' . $this->id);
    }

}
