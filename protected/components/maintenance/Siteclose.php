<?php

class Siteclose extends CComponent {


    //public $users = array('admin',);
    public $roles = array('admin',);
    //public $ips = array(); //allowed IP
    public $urls = array('admin/auth', 'users/login', 'admin', 'license', 'license/auth');

    public function init() {
        $config = Yii::app()->settings->get('app');
        if ($config['site_close']) {
            Yii::import('mod.users.UsersModule');
            $users = explode(',', $config['site_close_allowed_users']);
            $ips = explode(',', $config['site_close_allowed_ip']);
            $disable = in_array(Yii::app()->user->login, $users);
            foreach ($this->roles as $role) {
                $disable = $disable || Yii::app()->user->checkAccess($role);
            }
          //  echo Yii::app()->request->getPathInfo();
            $disable = $disable || in_array(Yii::app()->request->getPathInfo(), $this->urls);
            $disable = $disable || in_array(CMS::getip(), $ips); //check "allowed IP"

            if (!$disable) {

                Yii::app()->controllerMap['siteclose'] = 'app.maintenance.MaintenanceController';
                Yii::app()->catchAllRequest = array('siteclose/siteClose');
            }
        }
    }

}
