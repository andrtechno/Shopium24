<?php

Yii::import('users.forms.UserLoginForm');

class AuthController extends AdminController {

    public $layout = 'login';

    public function allowedActions() {
        return 'index, logout';
    }

    public function actionIndex() {
        if (!Yii::app()->user->isGuest)
            $this->redirect('/admin');

        $model = new UserLoginForm;
        if (isset($_POST['UserLoginForm'])) {
            $model->attributes = $_POST['UserLoginForm'];
            if ($model->validate(false)) {
                $duration = ($model->rememberMe) ? Yii::app()->settings->get('app', 'cookie_time') : 0;
                if (Yii::app()->user->login($model->getIdentity(), $duration)) {
                    Yii::app()->timeline->set(Yii::t('timeline', 'LOGIN'));
                    $this->setFlashMessage(Yii::t('app', 'WELCOME', array('{USER_NAME}' => Yii::app()->user->getName())));
                    $this->redirect($this->createUrl('/admin'));
                } else {
                    Yii::app()->user->setFlash('error', Yii::t('UsersModule.default', 'INCORRECT_LOGIN_OR_PASS'));
                }
            } else {
                Yii::app()->timeline->set(Yii::t('timeline', 'ERROR_AUTH', array(
                            '{login}' => $model->login
                )));
                Yii::app()->user->setFlash('error', Yii::t('UsersModule.default', 'INCORRECT_LOGIN_OR_PASS'));
                $this->redirect($this->createUrl('/admin/auth'));
            }
        }
        $this->render('auth', array('model' => $model));
    }

}
