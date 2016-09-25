<?php

/**
 * @uses AdminController 
 */
class ThemeSettingsController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'SETTINGS');
        $this->breadcrumbs = array(
            $this->pageName
        );
       Yii::import('current_theme.settings.*');
       $model = new ThemeForm;
        if (isset($_POST['ThemeForm'])) {
            $model->attributes = $_POST['ThemeForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }

        

           
        $this->render('index', array('model' => $model));
    }

}
