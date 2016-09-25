<?php

class DefaultController extends AdminController {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    /**
     * Display discounts list
     */
    public function actionIndex() {
        $this->pageName = Yii::t('PlansModule.default', 'MODULE_NAME');


        $this->breadcrumbs = array(
            Yii::t('PlansModule.default', 'MODULE_NAME') => array('/admin/shop'),
            $this->pageName
        );

        $model = new Plans('search');

        if (!empty($_GET['Plans']))
            $model->attributes = $_GET['Plans'];

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
            $model = new Plans;
        else
            $model = Plans::model()->findByPk($_GET['id']);

        if (!$model)
            throw new CHttpException(404, Yii::t('PlansModule.admin', 'NO_FOUND_PLAN'));




        $this->pageName = ($model->isNewRecord) ? Yii::t('PlansModule.admin', 'Создание тарифного плана') :
                Yii::t('PlansModule.admin', 'Редактирование плана');


        $this->breadcrumbs = array(
            Yii::t('PlansModule.default', 'MODULE_NAME') => array('/admin/shop'),
            Yii::t('PlansModule.default', 'MODULE_NAME') => $this->createUrl('index'),
            $this->pageName
        );



        $form = new TabForm($model->getForm(), $model);
        $form->additionalTabs[Yii::t('PlansModule.admin', 'Options')] = array('content' => $this->renderPartial('_options', array('model' => $model), true));
        $model->attributes = $_POST['Plans'];
        if (Yii::app()->request->isPostRequest) {
            if ($model->validate()) {
             //     $model->setOptions($_POST['options']);
                $model->save();
               
               // $this->saveOptions($model);
               $this->refresh();
                //$this->redirect(array('index'));
            } else {
                print_r($model->getErrors());
                die;
            }
        }

        $this->render('update', array('model' => $model, 'form' => $form));
    }

    
    
    
    
   
    
    
    
    
    
    
    
    
    
    public function saveOptions(Plans $model) {
        if (isset($_POST['options'])) {
            foreach ($_POST['options'] as $option_id => $option) {
                $opt = new PlansOptionsRel;
                $opt->plan_id = $model->id;
                $opt->option_id = $option_id;
                $opt->value = $option['value'];
                if ($opt->validate()) {
                    $opt->save(false,false);
                } else {
                    die('error saveOptions()');
                }
            }
        }
    }

}
