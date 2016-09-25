<?php

class DefaultController extends Controller {

    public $layout = '//layouts/main';

    public function actionIndex() {
        $this->breadcrumbs = array(Yii::t('zii', 'Home'));
        $this->render('index', array());
    }

    public function actionError() {
        $error = Yii::app()->errorHandler->error;
        $this->layout = '//layouts/error';
        if ($error) {
            $this->pageTitle = Yii::t('site', '_ERROR') . ' ' . $error['code'];
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', array('error' => $error));
            }
        }
    }

}