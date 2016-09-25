<?php

class IndexController extends Controller {

    public function actionIndex() {

        $this->pageName = 'Продукция';
        $this->breadcrumbs = array($this->pageName);

        $criteria = new CDbCriteria;
        $criteria->with = array('mainCategory');
        //$criteria->compare('mainCategory.name', 2);
        $model = new ActiveDataProvider('ShopProduct', array(
            // Set id to false to not display model name in
            // sort and page params
            'id' => false,
            'criteria' => $criteria,
                // 'pagination' => array(
                //     'pageSize' => $per_page,
                //)
        ));
        $this->render('index', array('model' => $model));
    }

    /**
     * @param $products
     */
    protected function renderBlock($products) {
        foreach ($products as $p)
            $this->renderPartial('_product', array('data' => $p));
    }

    /**
     * @param $limit
     * @return array
     */
    protected function getPopular($limit) {
        return ShopProduct::model()
                        ->active()
                        ->byViews()
                        ->findAll(array('limit' => $limit));
    }

    /**
     * @param $limit
     * @return array
     */
    protected function getByAddedToCart($limit) {
        return ShopProduct::model()
                        ->active()
                        ->byAddedToCart()
                        ->findAll(array('limit' => $limit));
    }

    /**
     * @param $limit
     * @return array
     */
    protected function getNewest($limit) {
        return ShopProduct::model()
                        ->active()
                        ->newest()
                        ->findAll(array('limit' => $limit));
    }

}
