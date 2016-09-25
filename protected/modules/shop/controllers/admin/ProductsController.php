<?php

/**
 * Manage products save_fields_on_create
 */
class ProductsController extends AdminController {

    public function actions() {
        return array(
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
            'order' => array(
                'class' => 'ext.adminList.actions.SortingAction',
            ),
        );
    }

    /**
     * Display list of products
     */
    public function actionIndex() {

        //$this->topButtons = Html::link(Yii::t('core', 'CREATE', 0), $this->createUrl('create'), array('title' => Yii::t('admin', 'Create', 1), 'class' => 'buttonS bGreen'));
        $this->pageName = Yii::t('ShopModule.admin', 'PRODUCTS');

        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );

        Yii::app()->clientScript->registerScriptFile($this->module->assetsUrl . '/admin/products.index.js', CClientScript::POS_END);

        $url = $this->createUrl('create');


        $this->topButtons = array(array(
                'label' => Yii::t('ShopModule.admin', 'CREATE_PRODUCT'),
                'url' => $url,
                'htmlOptions' => array('class' => 'buttonS bGreen')
                ));

        $model = new ShopProduct('search');

        if (!empty($_GET['ShopProduct']))
            $model->attributes = $_GET['ShopProduct'];

        // Pass additional params to search method.
        $params = array(
            'category' => Yii::app()->request->getParam('category', null)
        );

        $dataProvider = $model->search($params);
        //  $model->unsetAttributes();
        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Create/update product
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        $this->topButtons = false;
        $config = Yii::app()->settings->get('shop');
        if ($new === true) {
            $model = new ShopProduct();
        } else {
            $model = ShopProduct::model()->language(Yii::app()->language->active)->findByPk($_GET['id']);
        }
        if (!$model)
            throw new CHttpException(404, Yii::t('ShopModule.admin', 'NO_FOUND_PRODUCT'));


        // Apply use_configurations, configurable_attributes, type_id
        if (isset($_GET['ShopProduct']))
            $model->attributes = $_GET['ShopProduct'];




        $title = ($model->isNewRecord) ? Yii::t('ShopModule.admin', 'CREATE_PRODUCT') :
                Yii::t('ShopModule.admin', 'UPDATE_PRODUCT');

        //   if ($model->type)
        //     $title .= ' "' . Html::encode($model->type->name) . '"';

        $this->pageName = $title;



        $this->breadcrumbs = array(
            Yii::t('ShopModule.default', 'MODULE_NAME') => array('/admin/shop'),
            Yii::t('ShopModule.admin', 'PRODUCTS') => $this->createUrl('index'),
            $this->pageName
        );


        // Set main category id to have categories drop-down selected value
        if ($model->mainCategory)
            $model->main_category_id = $model->mainCategory->id;



        $form = new TabForm($model->getForm(), $model);
        //  $form->positionTabs = 'vertical';
        // Set additional tabs



        /*  if (Yii::app()->getModule('shop')->relatedProducts)
          $form->additionalTabs[$model->t('TAB_REL')] = array('content' => $this->renderPartial('_relatedProducts', array('exclude' => $model->id, 'product' => $model), true));
         */
        //if (Yii::app()->getModule('shop')->variations)
        /* $form->additionalTabs[Yii::t('ShopModule.admin', 'UPDATE_PRODUCT_TAB_VARIANTS')] = array(
          'content' => $this->renderPartial('_variations', array('model' => $model), true)
          ); */
        // if($this->isInstallModule('comments')){
        //      $form->additionalTabs['icon-comment'] = array(
        //         'content' => $this->renderPartial('_comments', array('model' => $model), true)
        //         );
        // }



        /*    $form->additionalTabs = array(
          'icon-folder-open' => array(
          'content' => $this->renderPartial('_tree', array('model' => $model), true)
          ),
          'icon-copy-3' => array(
          'content' => $this->renderPartial('_relatedProducts', array('exclude' => $model->id, 'product' => $model), true)
          ),
          'icon-images' => array(
          'content' => $this->renderPartial('_images', array('model' => $model, 'uploadModel' => $uploadModel), true)
          ),
          'icon-paragraph-justify' => array(
          'content' => $this->renderPartial('_attributes', array('model' => $model), true),
          'visible'=>false
          ),
          Yii::t('ShopModule.admin', 'Варианты') => array(
          'content' => $this->renderPartial('_variations', array('model' => $model), true)
          ),
          'icon-comment' => array(
          'content' => $this->renderPartial('_comments', array('model' => $model), true)
          ),
          ); */


        if (isset($_GET['ShopProduct']['main_category_id']))
            $model->main_category_id = $_GET['ShopProduct']['main_category_id'];
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['ShopProduct'];

            if ($model->validate()) {

                $mainCategoryId = 1;
                if (isset($_POST['ShopProduct']['main_category_id']))
                    $mainCategoryId = $_POST['ShopProduct']['main_category_id'];

                $model->setCategories(Yii::app()->request->getPost('categories', array()),$mainCategoryId); //, $mainCategoryId


                $model->save();
                // Process categories


                $this->redirect(array('index'));
            } else {
                $this->setFlashMessage(Yii::t('ShopModule.admin', 'ERR_PRODUCT_TYPE'));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'form' => $form,
        ));
    }

    /**
     * Save model attributes
     * @param ShopProduct $model
     * @return boolean
     */
    protected function processAttributes(ShopProduct $model) {
        $attributes = new CMap(Yii::app()->request->getPost('ShopAttribute', array()));
        if (empty($attributes))
            return false;

        $deleteModel = ShopProduct::model()->findByPk($model->id);
        $deleteModel->deleteEavAttributes(array(), true);

        // Delete empty values
        foreach ($attributes as $key => $val) {
            if (is_string($val) && $val === '')
                $attributes->remove($key);
        }

        return $model->setEavAttributes($attributes->toArray(), true);
    }

    /**
     * Save product variants
     * @param ShopProduct $model
     */
    protected function processVariants(ShopProduct $model) {
        $dontDelete = array();

        if (!empty($_POST['variants'])) {
            foreach ($_POST['variants'] as $attribute_id => $values) {
                $i = 0;
                foreach ($values['option_id'] as $option_id) {
                    // Try to load variant from DB
                    $variant = ShopProductVariant::model()->findByAttributes(array(
                        'product_id' => $model->id,
                        'attribute_id' => $attribute_id,
                        'option_id' => $option_id
                            ));
                    // If not - create new.
                    if (!$variant)
                        $variant = new ShopProductVariant();

                    $variant->setAttributes(array(
                        'attribute_id' => $attribute_id,
                        'option_id' => $option_id,
                        'product_id' => $model->id,
                        'price' => $values['price'][$i],
                        'price_type' => $values['price_type'][$i],
                        'sku' => $values['sku'][$i],
                            ), false);

                    $variant->save(false, false, false);
                    array_push($dontDelete, $variant->id);
                    $i++;
                }
            }
        }

        if (!empty($dontDelete)) {
            $cr = new CDbCriteria;
            $cr->addNotInCondition('id', $dontDelete);
            $cr->addCondition('product_id=' . $model->id);
            ShopProductVariant::model()->deleteAll($cr);
        }else
            ShopProductVariant::model()->deleteAllByAttributes(array('product_id' => $model->id));
    }

    /**
     * Create gridview for "Related Products" tab
     * @param int $exclude Product id to exclude from list
     */
    public function actionApplyProductsFilter($exclude = 0) {
        $model = new ShopProduct('search');
        $model->exclude = $exclude;

        if (!empty($_GET['RelatedProducts']))
            $model->attributes = $_GET['RelatedProducts'];

        $this->renderPartial('_relatedProducts', array(
            'model' => $model,
            'exclude' => $exclude,
        ));
    }

    /**
     * Mass product update switch
     */
    public function actionUpdateIsActive() {
        $ids = Yii::app()->request->getPost('ids');
        $switch = (int) Yii::app()->request->getPost('switch');
        $models = ShopProduct::model()->findAllByPk($ids);
        foreach ($models as $product) {
            if (in_array($switch, array(0, 1))) {
                $product->switch = $switch;
                $product->save(false, false);
            }
        }
        echo Yii::t('core', 'SUCCESS_UPDATE');
    }

    /**
     * Delete products
     * @param array $id
     */
    public function actionDelete($id = array()) {
        if (Yii::app()->request->isPostRequest) {
            $model = ShopProduct::model()->findAllByPk($_REQUEST['id']);

            if (!empty($model)) {
                foreach ($model as $page)
                    $page->delete();
            }

            if (!Yii::app()->request->isAjaxRequest)
                $this->redirect('index');
        }
    }

}
