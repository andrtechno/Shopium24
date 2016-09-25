<?php

//http://seegatesite.com/bootstrap/simple_sidebar_menu.html
class AdminModule extends WebModule {

    protected $access = 0;

    public function init() {
        $this->setIcon('flaticon-home');
    }

    public function getName() {
        return Yii::t('app', 'CMS');
    }

    public function getDescription() {
        return Yii::t('app', 'CMS');
    }

    public function getAdminSidebarMenu() {
        $dek = array();
        $dekstops = Dekstop::model()->findAll();
        if (isset($dekstops)) {
            foreach ($dekstops as $dekstop) {
                $dek[] = array(
                    'label' => $dekstop->name,
                    'url' => '/admin/?d=' . $dekstop->id,
                    'icon' => 'flaticon-home',
                    'active' => (Yii::app()->request->getParam('d') == $dekstop->id) ? true : false
                );
            }
        }
        return $dek;
    }

}
