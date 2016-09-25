<?php

class AjaxController extends AdminController {

    public function actions() {
        return array(
            'widget.' => 'ext.adminList.EditGridColumnsWidget',
        );
    }

    public function actionGeo($ip) {
        // die($ip);
        $city = CMS::getCityNameByIp($ip);
        $this->render('_geo', array(
            'city' => $city,
            'ip' => $ip
        ));
    }

    public function actionCounters() {
        Yii::import('mod.cart.models.Order');
        echo CJSON::encode(array(
            // 'comments' => (int) Comment::model()->waiting()->count(),
            'orders' => Order::model()->new()->count(),
        ));
    }

    /**
     * Экшен для CEditableColumn
     * @throws CHttpException
     */
    public function actionUpdateGridRow() {
        if (Yii::app()->request->isAjaxRequest) {
            $response = array();
            $modelClass = $_POST['modelClass'];
            $id = intval($_POST['pk']);
            $field = $_POST['field'];
            $q = $_POST['q'];
            $model = $modelClass::model()->findByPk($id);
            $model->$field = $q;
            if ($model->validate()) {
                $model->save(false, false);
                $response['message'] = Yii::t('app', 'SUCCESS_UPDATE');
                $response['value'] = $q;
            } else {
                $response['message'] = 'error validate';
            }
            echo CJSON::encode($response);
            Yii::app()->end();
        } else {
            throw new CHttpException(403, 'no ajax');
        }
    }

    public function actionDeleteFile() {
        $dir = $_POST['aliasDir'];
        $filename = $_POST['filename'];
        $model = $_POST['modelClass'];
        $record_id = $_POST['id'];
        $attr = $_POST['attribute'];
        $path = Yii::getPathOfAlias($dir);
        if (file_exists($path . DIRECTORY_SEPARATOR . $filename)) {
            unlink($path . DIRECTORY_SEPARATOR . $filename);
            $m = $model::model()->findByPk($record_id);
            $m->$attr = '';
            $m->save(false, false, false);
            echo CJSON::encode(array(
                'response' => 'success',
                'message' => Yii::t('app', 'FILE_SUCCESS_DELETE')
                    )
            );
        } else {
            echo CJSON::encode(array(
                'response' => 'error',
                'message' => Yii::t('app', 'ERR_FILE_NOT_FOUND')
                    )
            );
        }
    }

    public function actionCheckalias() {
        $model = $_POST['model'];
        $url = $_POST['alias'];
        $isNew = $_POST['isNew'];

        if (file_exists('mod.' . strtolower($model) . '.models.' . $model)) {
            Yii::import('mod.' . strtolower($model) . '.models.' . $model);
        }
        $criteria = new CDbCriteria();
        if (!empty($isNew)) {
            $criteria->condition = '`t`.`seo_alias`="' . $url . '" AND `t`.`id`!=' . $isNew;
        } else {
            $criteria->condition = '`t`.`seo_alias`="' . $url . '"';
        }
        $check = $model::model()->find($criteria);

        if (isset($check))
            echo CJSON::encode(array('result' => true));
        else
            echo CJSON::encode(array('result' => false));
        die;
    }

    public function actionGetStats() {
        $n = Stats::model()->findAll();
        echo CJSON::encode(array(
            'hits' => (int) count($n),
            'hosts' => (int) count($n),
        ));
    }

    public function actionAutocomplete() {
        $model = $_GET['modelClass'];
        $string = $_GET['string'];
        $field = $_GET['field'];
        $criteria = new CDbCriteria;
        $criteria->addSearchCondition('t.' . $field, $string);
        $results = $model::model()->findAll($criteria);

        $json = array();
        foreach ($results as $item) {
            $json[] = array(
                'label' => $item->title,
                'value' => $item->title,
                'test' => 'test.param'
            );
        }
        echo CJSON::encode($json);
    }

    public function actionSendMailForm() {
        Yii::import('core.models.MailForm');
        $model = new MailForm;
        $model->toemail = $_GET['mail'];
        $form = new CMSForm($model->config, $model);
        $this->renderPartial('_sendMailForm', array('form' => $form));
    }

}
