<?php
/**
 * Контроллер групп пользователей (Не реализовано!)
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users.controllers.admin
 * @uses AdminController
 */
class GroupController extends AdminController {

   // public $topButtons = false;

    public function actionIndex() {
        $this->pageName = Yii::t('app','SETTINGS');
        $this->breadcrumbs = array($this->pageName);
        $model = new UserGroup;
        if (isset($_POST['UserGroup'])) {
            $model->attributes = $_POST['UserGroup'];
            if ($model->validate()) {
                $model->save();
               	$this->setFlashMessage(Yii::t('app', 'SUCCESS_SAVE'));
                $this->refresh();
            }
        }
        $form = new CMSForm($model->config, $model);
        $this->render('index', array('form' => $form));
    }

}
