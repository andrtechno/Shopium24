<?php

Yii::import('mod.shop.models.orders.*');

class DefaultController extends AdminController {

    private $_items;
    public $topButtons = false;

    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('app', 'SETTINGS'),
                'url' => 'javascript:void(0)',
                'icon' => 'flaticon-settings',
                'visible' => true,
                //'itemsHtmlOptions' => array('style' => 'width:220px'),
                'items' => array(
                    array(
                        'label' => Yii::t('app', 'DEKSTOP_CREATE'),
                        'url' => $this->createUrl('dekstop/create'),
                        'icon' => 'flaticon-add',
                        'visible' => true,
                    ),
                    array(
                        'label' => Yii::t('app', 'DEKSTOP_SETTINGS'),
                        'url' => $this->createUrl('dekstop/update', array('id' => $_GET['d'])),
                        'icon' => 'flaticon-settings',
                        'visible' => true,
                    ),
                    array(
                        'label' => Yii::t('app', 'DEKSTOP_CREATE_WIDGET'),
                        'url' => $this->createUrl('dekstop/createWidget', array('id' => $_GET['d'])),
                        'linkOptions' => array('id' => 'createWidget'),
                        'icon' => 'flaticon-add',
                        'visible' => true,
                    )
                )
            ),
        );
    }

    public function actionIndex() {
        if (isset($_GET['d'])) {
            $dekstopID = (int) $_GET['d'];
            $dekstop = Dekstop::model()->findByPk($dekstopID);
            if (isset($dekstop)) {
                Yii::app()->session['dekstop_id'] = $dekstopID;
            } else {
                Yii::app()->db->createCommand()->insert(Dekstop::model()->tableName(), array(
                    'name' => 'Рабочий стол',
                    'addons' => 1,
                    'columns' => 2,
                ));
            }
        } else {
            if (isset(Yii::app()->session['dekstop_id'])) {
                $this->redirect(array('/admin/?d=' . Yii::app()->session['dekstop_id']));
            } else {
                $this->redirect(array('/admin/?d=1'));
            }
        }

        $found = $this->findAddons();
        $items = CMap::mergeArray($this->_items, $found);
        $this->pageName = Yii::t('app', 'CMS');
        $this->breadcrumbs = array($this->pageName);
        $this->clearCache();
        $this->clearAssets();
        $this->render('index', array(
            //'ordersDataProvider'=>$this->getOrders(),
            'dekstop' => $dekstop,
            'AddonsItems' => $items
        ));
    }

    protected function findAddons() {
        $result = array();
        $modules = ModulesModel::getInstalled();
        foreach ($modules as $module) {
            $class = Yii::app()->getModule($module->name);
            if(isset($class->addonsArray)){
                $result = CMap::mergeArray($result, $class->addonsArray['mainButtons']);
            }
        }
        return $result;
    }

    /**
     * Get latest orders
     *
     * @return ActiveDataProvider
     */
    public function getOrders() {
        Yii::import('mod.cart.models.Order');
        $cr = new CDbCriteria;
        $orders = Order::model()->search();
        $orders->setPagination(array('pageSize' => 10));
        $orders->setCriteria($cr);
        return $orders;
    }

    /**
     * Get orders date_create today
     *
     * @return ActiveDataProvider
     */
    public function getTodayOrders() {
        Yii::import('mod.cart.models.Order');
        $cr = new CDbCriteria;
        $cr->addCondition('date_create >= "' . date('Y-m-d 00:00:00') . '"');
        $dataProvider = Order::model()->search();
        $dataProvider->setCriteria($cr);
        return $dataProvider;
    }

    /**
     * Sum orders total price
     *
     * @return string
     */
    public function getOrdersTotalPrice() {
        $total = 0;
        foreach ($this->getTodayOrders()->getData() as $order)
            $total += $order->full_price;
        return ShopProduct::formatPrice($total);
    }

    public function clearCache() {
        if (isset($_POST['cache_id'])) {
            //Yii::app()->cache->delete($_POST['cache_id']);
            Yii::app()->cache->flush();
            $this->setFlashMessage(Yii::t('CoreModule.admin', 'SUCCESS_CLR_CACHE'));
            //$this->refresh();
        }
    }

    public function clearAssets() {
        if (isset($_POST['clear_assets'])) {
            FileSystem::fs('assets', Yii::getPathOfAlias('webroot'))->cleardir();
            $this->setFlashMessage(Yii::t('CoreModule.admin', 'SUCCESS_CLR_ASSETS'));
            //$this->refresh();
        }
    }

}
