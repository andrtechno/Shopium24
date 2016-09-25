<?php

class ModulesController extends AdminController {

    public $topButtons = false;

    public function actions() {
        return array(
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
        );
    }

    public function actionIndex() {
        $model = new ModulesModel('search');
        $this->pageName = Yii::t('app', 'MODULES');
        $this->breadcrumbs = array(Yii::t('app', 'SYSTEM') => array('admin/default'), $this->pageName);
        $mod = new ModulesModel;
        if (count($mod->getAvailable())) {
            $this->topButtons = array(array(
                    'label' => Yii::t('CoreModule.admin', 'INSTALL', array('{n}' => count($mod->getAvailable()), 0)),
                    'url' => $this->createUrl('install'),
                    'htmlOptions' => array('class' => 'btn btn-success')
            ));
        } else {
            $this->topButtons = false;
        }
        $model->unsetAttributes();  // clear any default values    
        if (isset($_GET['ModulesModel'])) {
            $model->attributes = $_GET['ModulesModel'];
        }
        $this->render('index', array('model' => $model));
    }

    public function actionInstall($name = null) {
        // if (!isset($_GET['name']) && count(ModulesModel::getAvailable())) {
        $this->pageName = Yii::t('CoreModule.admin', 'LIST_MODULES');
        $this->breadcrumbs = array(
            Yii::t('app', 'MODULES') => $this->createUrl('index'),
            Yii::t('CoreModule.admin', 'INSTALL', 1),
        );
        $mod = $result = new ModulesModel;
        if ($name) {
            $result = ModulesModel::install($name);
            if ($result) {
                //Yii::app()->cache->clear('EngineMainMenu');
                //Yii::app()->cache->flush();

                $this->redirect(array('index'));
            }
        }
        $this->render('install', array('modules' => $mod->getAvailable()));
        // }else {
        //     $this->redirect(array('index'));
        // }
    }

    public function actionUpdate() {
        $model = ModulesModel::model()->findByPk($_GET['id']);
        $this->pageName = Yii::t('app', 'MODULES');
        $this->breadcrumbs = array(
            $this->pageName => Yii::app()->createUrl('admin/core/modules'),
            Yii::t('app', 'UPDATE', 1)
        );

        if (isset($_POST['ModulesModel'])) {
            $model->attributes = $_POST['ModulesModel'];
            if ($model->validate()) {
                $model->save();
                Yii::app()->cache->delete('EngineMainMenu-' . Yii::app()->language);
                $this->redirect(array('index'));
            }
        }
        $this->render('update', array('model' => $model));
    }

    public function actionDelete() {
        if (Yii::app()->request->isPostRequest) {
            $model = ModulesModel::model()->findByPk($_GET['id']);

            if ($model) {
                $model->delete();
                Yii::app()->cache->flush();
            }

            if (!Yii::app()->request->isAjaxRequest)
                $this->redirect('index');
        }
    }

}
