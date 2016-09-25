<?php

/**
 * Модуль пользователей
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users
 * @uses WebModule
 */
class UsersModule extends WebModule {

    public $icon = 'flaticon-user';

    public function init() {
        Yii::trace('Loaded "users" module.');
        $this->setImport(array(
            $this->id . '.models.*',
            $this->id . '.components.*',
        ));
        parent::init();
    }

    /**
     * Init admin-level models, componentes, etc...
     */
    public function initAdmin() {
        Yii::trace('Init users module admin resources.');
        parent::initAdmin();
    }

    public function getRules() {
        return array(
            'users/login' => 'users/login/login',
            'users/register' => 'users/register/register',
            'users/register/captcha/*' => 'users/register/captcha',
            'users/profile' => 'users/profile/index',
            'users/payment' => 'users/payment/index',
            'users/profile/<user_id:([\d]+)>' => 'users/profile/userInfo',
            'users/profile/orders' => 'users/profile/orders',
            //'users/profile/avatar' => 'users/profile/avatar',
            // 'users/profile/saveAvatar' => 'users/profile/saveAvatar',
            // 'users/profile/getAvatars' => 'users/profile/getAvatars',
            'users/logout' => 'users/login/logout',
            'users/remind/activatePassword/<key>' => array('users/remind/activatePassword'),
            'users/ajax/<action>' => 'users/ajax/<action>',
            'users/profile/active/<key>' => array('users/profile/activeUser'),
                //'users/processPayment/*' => 'users/payment/process',
                //  'users/renderConfigurationForm/<system>' => 'users/payment/renderConfigurationForm',
        );
    }

    public function getAdminMenu() {
        $c = Yii::app()->controller->id;
        return array(
            'users' => array(
                'label' => $this->name,
                'url' => $this->adminHomeUrl,
                'icon' => $this->icon,
                'active' => ($c == 'admin/default') ? true : false,
            //   'visible'=> Yii::app()->user->checkAccess('Publisher') || Yii::app()->user->checkAccess('Managers')
            ),
        );
    }

    public function getAdminSidebarMenu() {
        $c = Yii::app()->controller->id;
        return array(
            $this->adminMenu['users'],
            array(
                'label' => Yii::t('app', 'SETTINGS'),
                'url' => array('/admin/users/settings'),
                'active' => ($c == 'admin/settings') ? true : false,
                'icon' => 'flaticon-settings',
            //   'visible'=>  Yii::app()->user->checkAccess('Publisher') || Yii::app()->user->checkAccess('Managers')
            )
        );
    }


}
