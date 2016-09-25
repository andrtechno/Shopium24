<?php

class Dekstop extends ActiveRecord {

    const MODULE_ID = 'core';

    public function getForm() {
        Yii::import('ext.bootstrap.selectinput.SelectInput');
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__,
                'class' => 'form-horizontal',
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'addons' => array('type' => 'checkbox'),
                'name' => array('type' => 'text'),
                'columns' => array(
                    'type' => 'SelectInput',
                    'data' => array(1 => 1, 2 => 2, 3 => 3),
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

    public function tableName() {
        return '{{dekstop}}';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('name, columns', 'length', 'max' => 255),
            array('columns', 'numerical', 'integerOnly' => true),
            array('addons', 'boolean'),
            array('name, columns', 'safe', 'on' => 'search'),
        );
    }

}
