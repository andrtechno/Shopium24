<?php

class BlocksModel extends ActiveRecord {

    protected $_mod = false;
    protected $_allPositions = array();
    public $translateModelName = 'BlocksModelTranslate';

    public $name;
    public $content;

    const MODULE_ID = 'core';

    public function getPaymentSystemsArray() {
        Yii::import('app.blocks_settings.*');
        $result = array();
        $systems = new WidgetSystemManager;
        foreach ($systems->getSystems() as $system) {
            $result[(string) $system->id] = $system->name;
        }
        return $result;
    }

    public function getForm() {
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        Yii::import('ext.bootstrap.selectinput.SelectInput');
        return new CMSForm(array(
                    'attributes' => array(
                        'id' => __CLASS__,
                        'class' => 'form-horizontal',
                    ),
                    'showErrorSummary' => true,
                    'elements' => array(
                        'name' => array('type' => 'text'),
                        'content' => array('type' => 'textarea', 'class' => 'editor'),
                        'widget' => array(
                            'type' => 'SelectInput',
                            'data' => Yii::app()->widgets->getData(),
                            'htmlOptions'=>array('empty' => Yii::t('app', 'EMPTY_DROPDOWNLIST', 1)),
                            'hint' => self::t('HINT_WIDGET')
                        ),
                        '<div id="payment_configuration"></div>',
                        'modules' => array(
                            'type' => 'checkboxlist',
                            'items' => ModulesModel::getModules()
                        ),
                        'access' => array(
                            'type' => 'SelectInput',
                            'data' => Yii::app()->access->dataList(),
                            'htmlOptions'=>array('empty' => Yii::t('app', 'EMPTY_DROPDOWNLIST', 1)),
                        ),
                        'position' => array(
                            'type' => 'SelectInput',
                            'data' => $this->allPositions,
                            'htmlOptions'=>array('empty' => Yii::t('app', 'EMPTY_DROPDOWNLIST', 1)),
                        ),
                        'expire' => array('type' => 'text'),
                        'action' => array(
                            'type' => 'SelectInput',
                            'htmlOptions'=>array('empty' => Yii::t('app', 'EMPTY_DROPDOWNLIST', 1)),
                            'data' => array('update' => Yii::t('app', 'OFF', 1), 'delete' => Yii::t('app', 'DELETE')),
                            'hint' => self::t('HINT_ACTION')
                        ),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'btn btn-success',
                            'label' => Yii::t('app', 'SAVE')
                        )
                    )
                        ), $this);
    }


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return '{{blocks}}';
    }
    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'translate' => array(self::HAS_ONE, $this->translateModelName, 'object_id'),
        );
    }

    /**
     * @return array
     */
    public function behaviors() {
        $a = array();
        $a['TranslateBehavior'] = array(
            'class' => 'app.behaviors.TranslateBehavior',
            'translateAttributes' => array(
                'name',
                'content',
            ),
        );
        return $a;
    }
    
    public function rules() {
        return array(
            array('name, modules, access, position', 'required'),
            array('switch', 'numerical', 'integerOnly' => true),
            array('content', 'type', 'type' => 'string'),
            array('modules, widget, expire, action', 'length', 'max' => 250),
            array('name, content, position, access, modules', 'safe', 'on' => 'search'),
        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        $criteria->with = array('translate');
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.switch', $this->switch);
        $criteria->compare('translate.name', $this->name, true);
        $criteria->compare('translate.content', $this->content, true);
        $criteria->compare('t.position', $this->position, true);
        $criteria->compare('t.access', $this->access, true);
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    protected function getAllPositions() {
        return array(
            'left' => self::t('BLOCK_POS_LEFT'),
            'right' => self::t('BLOCK_POS_RIGHT'),
            'fly' => self::t('BLOCK_POS_FLY'),
        );
    }

    public function showPosition($name) {
        return $this->allPositions[$name];
    }

    public function afterFind() {
        if ($this->expire > 0) {
            if ($this->action && $this->expire < time()) {
                if ($this->action == "update") {
                    Yii::app()->db->createCommand()->update('{{blocks}}', array('switch' => 0, 'expire' => 0), 'id=:id', array(':id' => (int) $this->id));
                } elseif ($this->action == "delete") {
                    Yii::app()->db->createCommand()->delete('{{blocks}}', 'id=:id', array(':id' => (int) $this->id));
                }
            }
        }
        parent::afterFind();
    }

}