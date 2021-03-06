<?php

/**
 * ActiveRecord class
 * 
 */
Yii::import('app.traits.ModelTranslate');

class ActiveRecord extends CActiveRecord {

    use ModelTranslate;

    const MODULE_ID = null;

    public $timeline = false;
    protected $_attrLabels = array();
    public $maxOrdern;
    public $hidden_delete = array();

    const route_update = 'update';
    const route_delete = 'delete';
    const route_switch = 'switch';
    const route_create = 'create';
    const route = null;

    /**
     * Special for widget ext.admin.frontControl
     * @return string
     */
    public function getCreateUrl() {
        if (static::route) {
            return Yii::app()->createUrl(static::route . '/' . static::route_create);
        } else {
            throw new Exception(Yii::t('exception', 'NOTFOUND_CONST_AR', array(
                '{param}' => 'route_create',
            )));
        }
    }

    /**
     * Special for widget ext.admin.frontControl
     * @return string
     */
    public function getDeleteUrl() {
        if (static::route) {
            return Yii::app()->createUrl(static::route . '/' . static::route_delete, array(
                        'model' => get_class($this),
                        'id' => $this->id
            ));
        } else {
            throw new Exception(Yii::t('exception', 'NOTFOUND_CONST_AR', array(
                '{param}' => 'route_delete',
                '{model}' => get_class($this)
            )));
        }
    }

    /**
     * Special for widget ext.admin.frontControl
     * @return string
     */
    public function getUpdateUrl() {
        if (static::route) {
            return Yii::app()->createUrl(static::route . '/' . static::route_update, array(
                        'id' => $this->id
            ));
        } else {
            throw new Exception(Yii::t('exception', 'NOTFOUND_CONST_AR', array(
                '{param}' => 'route_update',
                '{model}' => get_class($this)
            )));
        }
    }

    /**
     * Special for widget ext.admin.frontControl
     * @return string
     */
    public function getSwitchUrl() {
        if (static::route) {
            return Yii::app()->createUrl(static::route . '/' . static::route_switch, array(
                        'model' => get_class($this),
                        'switch' => 0,
                        'id' => $this->id
            ));
        } else {
            throw new Exception(Yii::t('exception', 'NOTFOUND_CONST_AR', array(
                '{param}' => 'route_switch',
                '{model}' => get_class($this)
            )));
        }
    }

    public function uploadFile($attr, $dir, $old_image = null) {
        $file = CUploadedFile::getInstance($this, $attr);
        $path = Yii::getPathOfAlias($dir) . DS;
        //TODO добавить проверку на наличие папки.
        if (isset($file)) {
            if ($old_image && file_exists($path . $old_image))
                unlink($path . $old_image);
            $newname = CMS::gen(10) . "." . $file->extensionName;
            if (in_array($file->extensionName, array('jpg', 'jpeg', 'png', 'gif'))) { //Загрузка для изображений
                $img = Yii::app()->img;
                $img->load($file->tempName);
                $img->save($path . $newname);
            } else {

                $file->saveAs($path . $newname);
            }

            $this->$attr = (string) $newname;
        } else {

            $this->$attr = (string) $old_image;
        }
    }

    public function getColumnSearch($array = array()) {
        $col = $this->gridColumns;
        $result = array();
        if (isset($col['DEFAULT_COLUMNS'])) {
            foreach ($col['DEFAULT_COLUMNS'] as $t) {
                $result[] = $t;
            }
        }
        foreach ($array as $key => $s) {
            $result[] = $col[$key];
        }

        if (isset($col['DEFAULT_CONTROL']))
            $result[] = $col['DEFAULT_CONTROL'];

        return $result;
    }

    public function init() {
        Yii::import('app.managers.CManagerModelEvent');
        CManagerModelEvent::attachEvents($this);
    }

    protected function isEditMode() {

        if (($_SESSION['edit_mode'] == 1) && Yii::app()->controller instanceof Controller) {
            return true;
        } else {
            return false;
        }
    }

    public function save($mSuccess = true, $mError = true, $runValidation = true, $attributes = null) {
        if (parent::save($runValidation, $attributes)) {
            if ($mSuccess) {
                $message = Yii::t('app', ($this->isNewRecord) ? 'SUCCESS_CREATE' : 'SUCCESS_UPDATE');

                if ($this->isEditMode()) {
                    echo CJSON::encode(array(
                        'message' => $message,
                        //'valid' => true,
                        'is' => Yii::app()->controller->isAdminController,
                        'em' => Yii::app()->controller->edit_mode,
                        'data' => $this->attributes
                    ));
                    Yii::app()->end();
                } else {
                    Yii::app()->controller->setNotify($message, 'success');
                }
            }
            //if($this->timeline){
            //    Yii::app()->timeline->set('UPDATE_RECORD',array(
            //         '{model}'=>  get_class($this)
            //         ));
            //  }
            //print_r($this);
            return true;
        } else {
            if ($mError) {
                Yii::app()->controller->setNotify(Yii::t('app', ($this->isNewRecord) ? 'ERROR_CREATE' : 'ERROR_UPDATE'), 'danger');
                // Yii::app()->controller->setFlashMessage(Yii::t('app', ($this->isNewRecord) ? 'ERROR_CREATE' : 'ERROR_UPDATE'));
            }
            return false;
        }
    }

    public function validate($attributes = null, $clearErrors = true) {
        if (parent::validate($attributes, $clearErrors)) {
            return true;
        } else {
            $message = Yii::t('app', 'ERROR_VALIDATE');
            if ($this->isEditMode() && false) {
                echo CJSON::encode(array(
                    'message' => $message,
                    // 'valid' => false,
                    'errors' => $this->getErrors()
                ));
                Yii::app()->end();
            } else {
                Yii::app()->controller->setNotify($message, 'danger');
                // Yii::app()->controller->setFlashMessage($message);
            }
            return false;
        }
    }

    public function attributeLabels() {
        $lang = Yii::app()->languageManager->active->code;
        $model = get_class($this);
        $filePath = Yii::getPathOfAlias('mod.' . static::MODULE_ID . '.messages.' . $lang) . DS . $model . '.php';
        foreach ($this->behaviors() as $key => $b) {
            if (isset($b['translateAttributes'])) {
                foreach ($b['translateAttributes'] as $attr) {
                    $this->_attrLabels[$attr] = self::t(strtoupper($attr));
                }
            }
        }
        foreach ($this->attributes as $attr => $val) {
            $this->_attrLabels[$attr] = self::t(strtoupper($attr));
        }
        if (!file_exists($filePath)) {
            Yii::app()->user->setFlash('warning', 'Модель "' . $model . '", не может найти файл переводов: <b>' . $filePath . '</b> ');
        }
        return $this->_attrLabels;
    }

    public function beforeSave() {
        if (parent::beforeSave()) {
            //create
            if ($this->isNewRecord) {
                if (isset($this->tableSchema->columns['ip_create'])) {
                    //Текущий IP адресс, автора добавление
                    $this->ip_create = Yii::app()->request->userHostAddress;
                }
                if (isset($this->tableSchema->columns['user_id'])) {
                    $this->user_id = (Yii::app()->user->isGuest) ? 0 : Yii::app()->user->id;
                }
                if (isset($this->tableSchema->columns['user_agent'])) {
                    $this->user_agent = Yii::app()->request->userAgent;
                }
                if (isset($this->tableSchema->columns['date_create'])) {
                    $this->date_create = date('Y-m-d H:i:s');
                }
                if (isset($this->tableSchema->columns['ordern'])) {
                    if (!isset($this->ordern)) {
                        $row = $this->model()->find(array('select' => 'max(ordern) AS maxOrdern'));
                        $this->ordern = $row['maxOrdern'] + 1;
                    }
                }
                //update
            } else {
                if (isset($this->tableSchema->columns['date_update'])) {
                    $this->date_update = date('Y-m-d H:i:s');
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function getNextOrPrev($nextOrPrev, $cid, $modelParams = array()) {
        $model = $this;
        $records = NULL;

        if ($nextOrPrev == "prev")
            $order = "id ASC";
        if ($nextOrPrev == "next")
            $order = "id DESC";

        if (!isset($modelParams['select']))
            $modelParams['select'] = '*';

        $modelParams['condition'] = '`t`.`switch`=1';
        $modelParams['params'] = array(':cid' => $cid);
        $modelParams['order'] = $order;

        $records = $model::model()->findAll($modelParams);

        foreach ($records as $i => $r)
            if ($r->id == $this->id)
                return $records[$i + 1] ? $records[$i + 1] : NULL;

        return NULL;
    }

    /* THIS RULES FOR BEHAVIORS NO USE THIS IS EXAMPLE! */

    public function rules2() {
        $rules = array(/* your rules */);

        //add all rules from attached behaviors
        $behaviors = $this->behaviors();
        foreach ($behaviors as $key => $behavior) {
            if (method_exists($this->{$key}, 'rules'))
                $rules += $this->{$key}->rules();
        }
        return $rules;
    }

    /**
     * Default model scopes.
     * 
     * published
     * @return array
     */
    public function scopes() {
        $alias = $this->getTableAlias(true);
        $scopes = array();
        if (isset($this->tableSchema->columns['switch'])) {
            $scopes['published'] = array('condition' => $alias . '.switch = 1');
        }
        $scopes['random'] = array('order' => 'RAND()');
        return $scopes;
    }

    /**
     * Разделение текста на страницы
     * @param string $attr
     * @return string
     */
    public function pageBreak($attr = false) {
        if ($attr) {
            $pagerClass = new LinkPager;

            $pag = intval($_GET['pb']);
            $conpag = explode("<!-- pagebreak -->", $this->$attr);
            $pageno = count($conpag);
            $pag = ($pag == "" || $pag < 1) ? 1 : $pag;
            if ($pag > $pageno)
                $pag = $pageno;
            $arrayelement = (int) $pag;
            $arrayelement--;
            $content = $conpag[$arrayelement];
            $content .= $pagerClass->num_pages($this->getUrl(), $pageno);
            return $content;
        }
    }

    public $behaviors = array();

    public function behaviors() {
        if (isset($this->tableSchema->columns['ordern'])) {
            $this->behaviors['sortable'] = array(
                'class' => 'ext.sortable.SortableBehavior',
            );
        }
        return $this->behaviors;
    }

    //TODO no function
    public function setBehaviors($array) {
        return false;
    }
    

}
