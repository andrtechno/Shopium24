<?php
/**
 * Контроллер настройки пользователей.
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users.controllers.admin
 * @uses AdminController
 */
class SettingsController extends AdminController {

    public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('app','SETTINGS');
        $this->breadcrumbs = array($this->pageName);
        $model = new SettingsUsersForm;
        if (isset($_POST['SettingsUsersForm'])) {
            $model->attributes = $_POST['SettingsUsersForm'];
            if ($model->validate()) {
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

}
