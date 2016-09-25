<?php

/**
 * Display category products
 * TODO: Add default sorting by rating, etc...
 *
 * @property $activeAttributes

 */
class CategoryController extends Controller {

    public function getConfig() {
        return Yii::app()->settings->get('shop');
    }

    /**
     * @var ShopProduct
     */
    public $query;

    /**
     * @var ShopCategory
     */
    public $model;


    /**
     * @var array
     */
    public $allowedPageLimit = array();

    /**
     * Current query clone to use in min/max price queries
     * @var CDbCriteria
     */
    public $currentQuery;

    /**
     * @var ActiveDataProvider
     */
    public $provider;



    /**
     * Load category model by url
     *
     * @param $action
     * @return bool
     */
    public function beforeAction($action) {
        $this->allowedPageLimit = explode(',', Yii::app()->settings->get('shop', 'per_page'));

        if (Yii::app()->request->getPost('min_price') || Yii::app()->request->getPost('max_price')) {
            $data = array();
            if (Yii::app()->request->getPost('min_price'))
                $data['min_price'] = (int) Yii::app()->request->getPost('min_price');
            if (Yii::app()->request->getPost('max_price'))
                $data['max_price'] = (int) Yii::app()->request->getPost('max_price');

            if ($this->action->id === 'search') {
                $this->redirect(Yii::app()->request->addUrlParam('/shop/category/search', $data));
            } else {
                if (!Yii::app()->request->isAjaxRequest)
                    $this->redirect(Yii::app()->request->addUrlParam('/shop/category/view', $data));
            }
        }

        return true;
    }



    /**
     * Display category products
     */
    public function actionView() {
        $this->model = $this->_loadModel(Yii::app()->request->getQuery('seo_alias'));
        $this->doSearch($this->model, 'view');
    }

    /**
     * Search products
     */
    public function actionSearch() {
        if (Yii::app()->request->isPostRequest)
            $this->redirect(Yii::app()->request->addUrlParam('/shop/category/search', array('q' => Yii::app()->request->getPost('q'))));
        $q = Yii::app()->request->getQuery('q');
        if (!$q)
            $this->render('search');
        $this->doSearch($q, 'search');
    }

    /**
     * Search products
     * @param $data ShopCategory|string
     * @param string $view
     */
    public function doSearch($data, $view) {
        $this->query = new ShopProduct(null);

        $this->query->attachBehaviors($this->query->behaviors());
        //$this->query->applyAttributes($this->activeAttributes)->active();

        if ($data instanceof ShopCategory) {

            if(Yii::app()->user->plan == $this->model->seo_alias){
                $this->query->applyCategories($this->model);
            }else{
                throw new CHttpException(403);
            }
            
        } else {
            $cr = new CDbCriteria;
            $cr->with = array(
                'translate' => array('together' => true),
            );
            $cr->addSearchCondition('translate.name', $data);
            $this->query->getDbCriteria()->mergeWith($cr);
        }


        // Create clone of the current query to use later to get min and max prices.
        $this->currentQuery = clone $this->query->getDbCriteria();

        // Filter products by price range if we have min_price or max_price in request
     //   $this->applyPricesFilter();

        $per_page = $this->allowedPageLimit[0];
        if (isset($_GET['per_page']) && in_array((int) $_GET['per_page'], $this->allowedPageLimit))
            $per_page = (int) $_GET['per_page'];

        $this->provider = new ActiveDataProvider($this->query, array(
                    // Set id to false to not display model name in
                    // sort and page params
                    'id' => false,
                    'pagination' => array(
                        'pageSize' => $per_page,
                    )
                ));

        $this->provider->sort = ShopProduct::getCSort();
        if ($view != 'search') {

            $this->pageKeywords = $this->model->keywords();
            $this->pageDescription = $this->model->description();
            $this->pageTitle = $this->model->title();
// Create breadcrumbs
            $ancestors = $this->model->cache($this->cacheTime)->excludeRoot()->ancestors()->findAll();
            $this->breadcrumbs = array(Yii::t('ShopModule.default', 'BC_SHOP') => '/shop');
            foreach ($ancestors as $c)
                $this->breadcrumbs[$c->name] = $c->getViewUrl();

            $this->breadcrumbs[] = $this->model->name;
        }

        $this->render($view, array(
            'provider' => $this->provider,
            'itemView' => (isset($_GET['view']) && $_GET['view'] === 'list') ? '_list' : '_grid'
        ));
    }





    /**
     * Load category by url
     * @param $url
     * @return ShopCategory
     * @throws CHttpException
     */
    public function _loadModel($url) {
        // Find category
        $model = ShopCategory::model()
                ->cache($this->cacheTime)
                ->excludeRoot()
                ->withFullPath($url)
                ->find();

        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.default', 'NOFIND_CATEGORY'));

        return $model;
    }

}
