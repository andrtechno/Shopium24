<?php

class DomainCheckForm extends FormModel {

    public $domain;
    public $name;

    public function init() {
        parent::init();
    }

    public function rules() {
        return array(
            array('domain, name', 'required'),
            array('name', 'validateCountDomain'),
            // array('name', 'length', 'max' => 20, 'min' => 7),
            //array('phone', 'length'),
           /* array(
                'name',
                'match',
                'not' => true,
                'pattern' => '/^[-\s0-9А-яA-z-]/i',
                'message' => Yii::t('DomainCheckWidget.default', 'ERR_VALID'),
            ),*/
        );
    }

    public function attributeLabels() {
        return array(
            'name' => 'Доменное имя',
        );
    }

    public function validateCountDomain($attr) {
        $array = explode(',', $this->$attr);
        $count = count($array);
        if ($count > 10) {

            $this->addError($attr, "Максимальное количество проверки доменов 10 шт. Вы указали {$count}");
        }
    }

}
