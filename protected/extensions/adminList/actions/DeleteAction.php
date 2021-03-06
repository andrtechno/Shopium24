<?php

/**
 * Это действие вызывается при adminList виджета для удаление записей или записи.
 * @author CORNER CMS development team <dev@corner-cms.com>
 * @package widgets.adminList.actions
 * @uses CAction
 */
class DeleteAction extends CAction {

    /**
     * @var string 
     */
    public $model;

    /**
     * Запустить действие
     */
    public function run() {
        $json = array();
        if (isset($_REQUEST)) {
            if (Yii::app()->request->isPostRequest) {
                $model = (isset($this->model)) ? call_user_func(array($this->model, 'model')) : call_user_func(array($_REQUEST['model'], 'model'));
                $entry = $model->findAllByPk($_REQUEST['id']);
                if (!empty($entry)) {
                    foreach ($entry as $page) {
                        if (!in_array($page->primaryKey, $model->hidden_delete)) {
                            $page->delete(); //$page->deleteByPk($_REQUEST['id']);
                            $json = array('status' => 'success','message'=>Yii::t('app','SUCCESS_RECORD_DELETE'));
                        } else {
                            $json = array(
                                'status' => 'error',
                                'message' => Yii::t('app','ERROR_RECORD_DELETE')
                            );
                        }

                    }
                    echo CJSON::encode($json);
                    Yii::app()->end();
                }
            }
        }
    }

}
