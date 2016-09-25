<?php

class GroupsController extends AdminController {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'sortable' => array(
                'class' => 'ext.sortable.SortableAction',
                'model' => PlansOptionsGroups::model(),
            )
        );
    }

    /**
     * Display discounts list
     */
    public function actionIndex() {
        $this->pageName = Yii::t('PlansModule.default', 'OPTIONS_GROUP');
        $this->breadcrumbs = array(
            $this->module->name => $this->module->adminHomeUrl,
            $this->pageName
        );

        $model = new PlansOptionsGroups('search');

        if (!empty($_GET['PlansOptionsGroups']))
            $model->attributes = $_GET['PlansOptionsGroups'];

        $dataProvider = $model->search();

        $this->render('index', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }


    /**
     * Update discount
     * @param bool $new
     * @throws CHttpException
     */
    public function actionUpdate($new = false) {
        if ($new === true)
            $model = new PlansOptionsGroups;
        else
            $model = PlansOptionsGroups::model()->findByPk($_GET['id']);

        if (!$model)
            throw new CHttpException(404, Yii::t('PlansModule.admin', 'NO_FOUND_DISCOUNT'));

        $this->pageName = ($model->isNewRecord) ? Yii::t('PlansModule.admin', 'Создание группы') :
                Yii::t('PlansModule.admin', 'Редактирование группы');


        $this->breadcrumbs = array(
            $this->module->name => $this->module->adminHomeUrl,
            Yii::t('PlansModule.default', 'OPTIONS_GROUP') => $this->createUrl('index'),
            $this->pageName
        );

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = Yii::app()->request->getPost('PlansOptionsGroups');
            if ($model->validate()) {
                $model->save();
              //  $this->saveOptions($model);
                $this->redirect(array('index'));
            }
        }

        $this->render('update', array('model' => $model));
    }


}