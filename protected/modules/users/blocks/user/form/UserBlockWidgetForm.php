<?php

class UserBlockWidgetForm extends WidgetFormModel {

    public $show_online;

    public function rules() {
        return array(
            array('show_online', 'type'),
            array('show_online', 'boolean')
        );
    }

    public function getFormConfigArray() {
        return array(
            'type' => 'form',
            'attributes' => array(
                'class' => 'form-horizontal',
                'id' => __CLASS__
            ),
            'elements' => array(
                'show_online' => array(
                    'label' => Yii::t('app', 'Показвать кто онлайн'),
                    'type' => 'checkbox',
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
        );
    }

}
