<?php

class DesignForm extends FormModel {

    public $theme;
    public $theme_color;

    public function rules() {
        return array(
            array('theme, theme_color', 'required'),
            array('theme, theme_color', 'length', 'max' => 20, 'min' => 2),
            //array('phone', 'length'),

        );
    }

    public function attributeLabels() {
        return array(
            'theme' => 'Шаблон',
             'theme_color' => 'Цвет',
        );
    }

}
