<?php

class MaintenanceController extends ExtController {

    public $layouts = 'app.maintenance.layouts.default';

    public function actionBannedip() {
        $component = Yii::app()->getComponent('ipblocker');
        $c = $component->params;

        $result = array();
        $result['content'] = Yii::t('app', 'IP_BANNED_MESSAGE', array(
                    '{banned_time}' => $c['banned_time'],
                    '{left_time}' => $c['left_time'],
                    '{reason}' => $c['reason'],
        ));
        $result['title'] = Yii::t('app', 'IP_BANNED_TITLE', array('{ip}' => $component->userIP));
        $this->renderPartial($this->layouts, $result, false, true);
    }

    public function actionSiteClose() {
        $result = array();
        $result['content'] = Yii::app()->settings->get('app', 'site_close_text');
        $result['title'] = Yii::app()->settings->get('app', 'site_name');
        $this->renderPartial($this->layouts, $result, false, true);
    }
    
    public function actionLicense() {
        $result = array();

        $result['content'] = $_GET[0]['message'];
        $result['title'] = Yii::app()->settings->get('app', 'sss');
        $this->renderPartial('app.maintenance.layouts.alert', $result, false, true);
    }

}
