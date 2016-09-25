<?php

class SettingsSeoForm extends FormSettingsModel {

    public $googleanalytics_id;
    public $yandexmetrika_id;
    public $yandexmetrika_clickmap;
    public $yandexmetrika_trackLinks;
    public $yandexmetrika_webvisor;

    public static function defaultSettings() {
        return array(
            'googleanalytics_id' => null,
            'yandexmetrika_id' => null,
            'yandexmetrika_clickmap' => true,
            'yandexmetrika_trackLinks' => true,
            'yandexmetrika_webvisor' => true,
        );
    }

    public function getForm() {
        return new TabForm(array(
            'attributes' => array(
                'id' => __CLASS__,
                'class' => 'form-horizontal',
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'googleanalytics' => array(
                    'type' => 'form',
                    'title' => Yii::t('app', 'Google Analytics'),
                    'elements' => array(
                        'googleanalytics_id' => array('type' => 'text', 'hint' => 'UA-12345678-9'),
                    )
                ),
                'googletagmanager' => array(
                    'type' => 'form',
                    'title' => Yii::t('app', 'Google Tag Manager'),
                    'elements' => array(
                        'googleanalytics_id' => array('type' => 'text', 'hint' => 'UA-12345678-9'),
                    )
                ),
                'yandexmetrika' => array(
                    'type' => 'form',
                    'title' => Yii::t('app', 'Yandex Metrika'),
                    'elements' => array(
                        'yandexmetrika_id' => array('type' => 'text'),
                        'yandexmetrika_clickmap' => array('type' => 'checkbox', 'labelHint' => self::t('YANDEXMETRIKA_CLICKMAP_HINT')),
                        'yandexmetrika_trackLinks' => array('type' => 'checkbox', 'labelHint' => self::t('YANDEXMETRIKA_TRACKLINKS_HINT')),
                        'yandexmetrika_webvisor' => array('type' => 'checkbox', 'labelHint' => self::t('YANDEXMETRIKA_WEBVISOR_HINT')),
                    )
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

    public function rules() {
        return array(
            array('yandexmetrika_clickmap, yandexmetrika_trackLinks, yandexmetrika_webvisor', 'boolean'),
            array('googleanalytics_id', 'type', 'type' => 'string'),
            array('yandexmetrika_id', 'numerical', 'integerOnly' => true),
            array('googleanalytics_id', 'length', 'max' => 13, 'min' => 13),
        );
    }

    

    
}
