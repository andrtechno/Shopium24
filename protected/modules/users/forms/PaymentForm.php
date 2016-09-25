<?php

class PaymentForm extends FormModel {

    const MODULE_ID = 'users';
    public $months;
    public $system;

    public function rules() {
        return array(
            array('months,system', 'required'),
            array('months', 'numerical', 'integerOnly' => true),
                 array('months', 'length', 'min' => 1, 'max' => 2),
        );
    }

}
