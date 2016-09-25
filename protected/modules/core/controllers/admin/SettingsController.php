<?php

class SettingsController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $model = new SettingsAppForm;
        $this->pageName = Yii::t('app', 'SETTINGS');
        $this->breadcrumbs = array(Yii::t('app', 'SYSTEM')=>array('admin/default'),Yii::t('app', 'SETTINGS'));
        if (isset($_POST['SettingsAppForm'])) {
            $model->attributes = $_POST['SettingsAppForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }

        $this->render('index', array('model' => $model));
    }

}
