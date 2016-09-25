<?php

/**
 * Display product view page.
 */
class ProductController extends Controller {

    public function getConfig() {
        return Yii::app()->settings->get('shop');
    }

    /**
     * @var ShopProduct
     */
    public $model;

    /**
     * Display product
     * @param string $url product url
     */
    public function actionView($seo_alias) {
        $this->_loadModel($seo_alias);

        $this->registerSessionViews($this->model->id);



        Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/product.view.js', CClientScript::POS_END);

        if ($this->model->mainCategory) {
            $ancestors = $this->model->mainCategory->excludeRoot()->ancestors()->findAll();
            $this->breadcrumbs = array(Yii::t('ShopModule.default', 'BC_SHOP') => '/shop');
            foreach ($ancestors as $c) {
                $this->breadcrumbs[$c->name] = $c->getViewUrl();
            }
            // 
            // Do not add root category to breadcrumbs
            if ($this->model->mainCategory->id != 1) {
                //$bc[$this->model->mainCategory->name]=$this->model->mainCategory->getViewUrl();

                $this->breadcrumbs[$this->model->mainCategory->name] = $this->model->mainCategory->getViewUrl();
            }
            $this->breadcrumbs[] = $this->model->name;
        }

        $this->pageKeywords = $this->model->keywords();
        $this->pageDescription = $this->model->description();
        $this->pageTitle = $this->model->title();
        $this->render('view', array('model' => $this->model));
    }

    public function registerSessionViews($id = null) {
        $session = Yii::app()->session->get('views');
        //unset(Yii::app()->session['views']);
        if (empty($session)) {
            // Yii::app()->session->add('views', array());
            Yii::app()->session['views'] = array();
        }

        if (isset($session)) {
            if (!in_array($id, $session)) {
                array_push($_SESSION['views'], $id);
            }
        }
    }

    /**
     * Load ShopProduct model by url
     * @param $url
     * @return ShopProduct
     * @throws CHttpException
     */
    protected function _loadModel($seo_alias) {
        $this->model = ShopProduct::model()
                ->active()
                ->withUrl($seo_alias)
                ->find();

        if (!$this->model)
            throw new CHttpException(404, Yii::t('ShopModule.default', 'ERROR_PRODUCT_NOTFOUND'));


        return $this->model;
    }


}