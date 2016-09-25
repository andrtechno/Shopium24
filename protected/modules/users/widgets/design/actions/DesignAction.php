<?php

/**
 * @author Andrew (panix) S. <andrew.panix@gmail.com>
 * 
 * @param array $receiverMail Массив получатилей.
 * @uses CAction
 * @uses CallbackForm Модель формы
 */

Yii::import('mod.users.widgets.design.DesignForm');
class DesignAction extends CAction {

    public function run() {
        if (Yii::app()->request->isAjaxRequest) {
            
            $model  = new DesignForm;
            if(isset($_POST['DesignForm'])){
                $model->attributes=$_POST['DesignForm'];
                if($model->validate()){
                    die('OK!');
                }
            }
            
            
            if (isset($_POST['theme'])) {
                $colors = $this->getThemeColor($_POST['theme']);
            }else{
                $colors = null;
            }
            $this->controller->render('mod.users.widgets.design.views._action', array(
                'colors' => $colors,
                'model'=>$model
            ));
        } else {
            throw new CHttpException(403);
        }
    }

    protected function getThemeColor($themeName) {
        $tc = $this->getThemeColors();
        return $tc[$themeName];
    }

    protected function getThemeColors() {
        return array(
            'classic' => array('blue', 'gray'),
            'default' => array('blue', 'gray', 'red', 'green')
        );
    }
}