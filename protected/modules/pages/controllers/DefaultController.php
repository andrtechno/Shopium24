<?php

/**
 * Контроллер статичных страниц
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.pages.controllers
 * @uses Controller
 */
class DefaultController extends Controller {

    public function actionIndex($url) {
        $this->dataModel = Page::model()
                ->published()
                ->withUrl($url)
                ->find(array(
            'limit' => 1
                ));
        if (!$this->dataModel)
            throw new CHttpException(404);


        $this->breadcrumbs = array($this->dataModel->title);
        $this->dataModel->saveCounters(array('views' => 1));
        $this->render('view', array(
            'model' => $this->dataModel,
        ));
    }
    public function actionHtml($page) {

        /* $this->pageTitle = ($model->seo_title) ? $model->seo_title : $model->title;
          $this->pageKeywords = $model->seo_keywords;
          $this->pageDescription = $model->seo_description;

          $this->breadcrumbs = array($model->title); */
        $fullPath = Yii::getPathOfAlias('mod.pages.views.default.html');
        if (file_exists($fullPath . DS . $page . '.php')) {
            $this->render('html//' . $page, array());
        } else {
            throw new CHttpException(404);
        }
    }
}