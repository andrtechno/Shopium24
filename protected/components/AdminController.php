<?php

/**
 * Базовый класс для админ контроллеров.
 * 
 * @uses Controller
 * @author CORNER CMS development team <dev@corner-cms.com>
 * @package components
 */
Yii::import('mod.core.CoreModule');

class AdminController extends Controller {

    public $isAdminController = true;

    /**
     *
     * @var string 
     */
    public $layout = 'mod.admin.views.layouts.main';

    /**
     *
     * @var array 
     */
    public $menu = array();
    public $pageName;

    /**
     * Отоброжение кнопкок.
     * @var boolean 
     */
    public $topButtons = null;

    /**
     *
     * @var array 
     */
    protected $_addonsMenu = array();

    /**
     *
     * @var array 
     */
    protected $_sidebarWidgets = array();

    public function init() {
        Yii::app()->user->loginUrl = array('/admin/auth');
        $this->module->initAdmin();
        parent::init();
    }

    public function filters() {
        return array('rights');
    }

    /**
     * @param CAction $action
     * @return bool
     */
    public function beforeAction($action) {
        // Allow only authorized users access
        if (Yii::app()->user->isGuest && get_class($this) !== 'AuthController') {
            Yii::app()->request->redirect($this->createUrl('/admin/auth'));
        }


        Yii::import('mod.core.components.yandexTranslate');
        Yii::app()->clientScript->registerScript('commonjs', '
            var translate_object_url = "' . Yii::app()->settings->get('app', 'translate_object_url') . '";
            var yandex_translate_apikey = "' . yandexTranslate::API_KEY . '";
            common.langauge="' . Yii::app()->language . '";
            common.token="' . Yii::app()->request->csrfToken . '";
            common.isDashboard=true;
            common.message=' . CJavaScript::encode($this->commonJsMessages), CClientScript::POS_HEAD);

        return true;
    }

    /**
     * action
     */
    public function actionCreate() {
        $this->actionUpdate(true);
    }

    public function actionRemoveFile($path, $filename) {
        if (isset($path) && isset($filename)) {
            if (file_exists(Yii::getPathOfAlias($path) . DS . $filename)) {
                $this->setFlashMessage(Yii::t('app', 'FILE_DELETE_SUCCESS'));
                //unlink($filepath);
            }
        }
    
    }

    /**
     * Action воостановление настроек по умолчанию
     * @param object $model
     */
    public function actionResetSettings($model, $ref = false) {
        if (isset($model)) {
            $mdl = new $model;
            Yii::app()->settings->set($mdl->getModuleId(), $mdl::defaultSettings());
            $this->setFlashMessage(Yii::t('app', 'SUCCESS_RESET_SETTINGS'));
            if ($ref) {
                $this->redirect(array($ref));
            } else {
                $this->redirect(array('/admin/' . $mdl->getModuleId() . '/settings'));
            }
        }
    }

    /**
     * @return string admin/<module>/<controller>/<action>
     */
    public function getUniqueId() {
        $ex = explode('/', $this->id);
        return $this->module ? $ex[0] . '/' . $this->module->getId() . '/' . $ex[1] : $this->id;
    }

}
