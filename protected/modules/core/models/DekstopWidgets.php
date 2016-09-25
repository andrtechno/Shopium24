<?php

class DekstopWidgets extends ActiveRecord {

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
                'dekstop_id' => array('type' => 'hidden', 'value' => $_GET['id']),
                'widget_id' => array(
                    'type' => 'SelectInput',
                    'data' => Yii::app()->widgets->getData(),
                    'htmlOptions' => array('empty' => Yii::t('app', 'Выберите виджет')),
                ),
                'column' => array(
                    'type' => 'SelectInput',
                    'data' => array(1 => 1, 2 => 2, 3 => 3),
                ),
            ),
                ), $this);
    }

    public function tableName() {
        return '{{dekstop_widgets}}';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

   /* public function relations() {
        return array(
            'wgt' => array(self::BELONGS_TO, 'WidgetsModel', 'id'),
            'wgt2' => array(self::BELONGS_TO, 'WidgetsModel', 'widget_id'),
        );
    }*/

    public function rules() {
        return array(
            // array('id', 'UniqueAttributesValidator', 'with' => 'widget_id', 'message' => Yii::t('site', 'ALREADY_REQUEST_USER')),
            array('dekstop_id, column', 'numerical', 'integerOnly' => true),
            array('widget_id', 'length', 'max' => 255),


        );
    }

    public function search() {
        $criteria = new CDbCriteria;
        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

}
