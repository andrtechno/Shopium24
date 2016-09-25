<?php

class SecurityController extends AdminController {

    public function actionIndex() {
        $this->topButtons = false;
        $model = new SettingsSecurityForm;
        $this->pageName = Yii::t('app', 'SECURITY');
        $this->breadcrumbs = array(Yii::t('app', 'SYSTEM') => array('admin/default'), $this->pageName);
        if (isset($_POST['SettingsSecurityForm'])) {
            $post = $_POST['SettingsSecurityForm'];
            $model->attributes = $post;
            $model->backup_time_cache = CMS::time() - $post['backup_time'] * 60;
            if ($model->validate()) {
                $model->backup_time = $post['backup_time'] * 60;
                $model->save();
                $this->refresh();
            }
        }
        $this->render('index', array('model' => $model));
    }

    public function actionBanlist() {
        $model = new BannedIPModel('search');
        $this->pageName = Yii::t('CoreModule.admin', 'BANNED_IP');
        $this->breadcrumbs = array(
            Yii::t('app', 'SYSTEM') => array('/admin/core'),
            Yii::t('app', 'SECURITY') => array('/admin/core/security'),
            $this->pageName
        );

        $model->unsetAttributes();  // clear any default values    
        if (isset($_GET['BannedIPModel'])) {
            $model->attributes = $_GET['BannedIPModel'];
        }
        $this->render('banlist', array('model' => $model));
    }

    public function actionUpdate($new = false) {
        $model = ($new === true) ? new BannedIPModel : BannedIPModel::model()->findByPk($_GET['id']);
        if (isset($model)) {
            $this->pageName = Yii::t('CoreModule.admin', 'BANNED_IP');
            $this->breadcrumbs = array(
                Yii::t('app', 'SYSTEM') => array('/admin/core'),
                Yii::t('app', 'SECURITY') => array('/admin/core/security'),
                $this->pageName => array('/admin/core/security/banlist'),
                ($new === true) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'UPDATE', 1)
            );
            if (isset($_POST['BannedIPModel'])) {
                $model->attributes = $_POST['BannedIPModel'];
                if ($model->validate()) {
                    $model->save();
                    $this->redirect(array('banlist'));
                }
            }
            $this->render('update', array('model' => $model));
        } else {
            throw new CHttpException(404);
        }
    }

    public function actionLogs() {
        $this->pageName = Yii::t('CoreModule.admin', 'LOGS');
        $this->breadcrumbs = array(
            Yii::t('app', 'SYSTEM') => array('/admin/core'),
            Yii::t('app', 'SECURITY') => array('/admin/core/security'),
            $this->pageName
        );
        $this->render('logs', array('model' => $model));
    }

    public function actionClear() {
        $logFile = Yii::getPathOfAlias('application.runtime') . DS . 'application.log';
        if (file_exists($logFile)) {
            unlink($logFile);
            Yii::app()->user->setFlash('success', Yii::t('CoreModule.admin', 'SUCCESS_LOGS_CLEAR'));
        }
        $this->redirect(array('/admin/core/security/logs'));
    }

    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('CoreModule.admin', 'BANNED_IP'),
                'url' => array('/admin/core/security/banlist'),
                'icon' => 'flaticon-lock',
                'visible' => Yii::app()->user->isSuperuser
            ),
            array(
                'label' => Yii::t('CoreModule.admin', 'LOGS'),
                'url' => array('/admin/core/security/logs'),
                'icon' => 'flaticon-list',
                'visible' => Yii::app()->user->isSuperuser
            ),
        );
    }

}
