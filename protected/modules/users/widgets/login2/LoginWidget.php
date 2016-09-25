<?php

class LoginWidget extends CWidget {

    public $icon_register;
    public $icon_login;
    public $username;
    public $enableItems = false;
    public $items = array(); // from class CMenu;

    public function init() {
        $this->username = !empty(Yii::app()->user->email) ? Yii::app()->user->email : Yii::app()->user->username;
        parent::init();
        if (Yii::app()->user->isGuest)
            $this->registerScripts();

        $this->getItemsList();
    }

    public function run() {
        //return array('dasdas');
        $this->render($this->skin);
    }

    protected function registerScripts() {
        $assets = Yii::app()->assetManager->publish(dirname(__FILE__) . DS . 'assets', false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        // $cs->registerCssFile($assets . '/css/login.css');
        if (Yii::app()->user->isGuest)
            $cs->registerScriptFile($assets . '/js/login.js?' . time());
    }

    private function getItemsList() {
        if ($this->enableItems) {
            if (Yii::app()->user->isGuest) {
                $this->items[] = array(
                    'label' => Yii::t('UsersModule.default', 'ENTER'),
                    'url' => Yii::app()->createUrl('/users/login/'),
                    'linkOptions' => array(
                        'ajax' => array('update' => '#exampleModal'),
                        'id' => 'login-btn',
                        'onclick' => '$("#exampleModal").arcticmodal();',
                    ),
                );
                $this->items[] = array(
                    'label' => Yii::t('UsersModule.default', 'REGISTRATION'),
                    'url' => 'http'
                );
            } else {
                $this->items[] = array(
                    'label' => $this->username.' <span class="caret"></span>',
                    'url' => 'javascript:void(0)',
                    'encodeLabel' => false,
                    'itemOptions' => array('class' => 'dropdown'),
                    'linkOptions' => array(
                        'class' => 'dropdown-toggle',
                        'data-toggle' => 'dropdown',
                        'aria-haspopup' => 'true',
                        'aria-expanded' => 'false',
                    ),
                    'items' => array(
                        array(
                            'label' => Yii::t('default', 'PROFILE'),
                            'url' => array('/users/profile/')
                        ),
                        array(
                            'label' => Yii::t('app', 'LOGOUT'),
                            'url' => Yii::app()->createUrl('/users/logout/')
                        )
                    )
                );
            }
            return $this->items;
        }
    }

}

?>
