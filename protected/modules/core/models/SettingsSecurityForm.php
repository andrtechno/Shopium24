<?php

class SettingsSecurityForm extends FormSettingsModel {

    const NAME = 'security';

    public $backup_db;
    public $backup_time;
    public $backup_time_cache;

    public function getForm() {
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__,
                'class' => 'form-horizontal',
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'backup_db' => array('type' => 'checkbox'),
                'backup_time' => array('type' => 'text', 'value' => $this->backup_time / 60),
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

    public function rules() {
        return array(
            array('backup_db, backup_time, backup_time_cache', 'required'),
            array('backup_db, backup_time, backup_time_cache', 'numerical', 'integerOnly' => true),
        );
    }

}
