<?php

/**
 * @uses AdminController 
 */
class DefaultController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'SYSTEM');
        $this->breadcrumbs = array(
            $this->pageName
        );
        $menu = $this->module->adminMenu;
        $this->render('index', array('menu' => $menu));
    }

}
