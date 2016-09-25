<?php

class SettingsController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('core','SETTINGS');
        $this->breadcrumbs = array($this->pageName);
        $model = new SettingsCartForm;
        $this->topButtons = array(
            array('label' => Yii::t('core', 'RESET_SETTINGS'),
                'url' => $this->createUrl('resetSettings', array(
                    'model' => get_class($model),
                )),
                'htmlOptions' => array('class' => 'buttonS bDefault')
            )
        );
        if (isset($_POST['SettingsCartForm'])) {
            $model->attributes = $_POST['SettingsCartForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

    public function actionManual(){
         $this->render('manual');
    }
}
