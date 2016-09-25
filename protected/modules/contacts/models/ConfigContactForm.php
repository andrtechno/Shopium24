<?php

class ConfigContactForm extends FormSettingsModel {

    public $address;
    public $tempMessage;
    public $phone;
    public $skype;
    public $form_emails;
    public $enable_captcha;

    public static function defaultSettings() {
        return array(
            'form_emails' => Yii::app()->settings->get('app','admin_email'),
            'tempMessage' => '<p>{if*sender_name*}Имя отправителя: <strong>{*sender_name*}</strong>{endif}</p>
<p>{if*sender_email*}Email отправитиля: <strong>{*sender_email*}</strong>{endif}</p>
<p>{if*sender_phone*}Телефон: <strong>{*sender_phone*}</strong>{endif}</p>
<p>{if*sender_message*}</p>
<p>==============================</p>
<p><strong>Сообщение:</strong></p>
<p>{*sender_message*}</p>
<p>{endif}</p>
<p>&nbsp;</p>
<p>______________________________</p>
<p><strong>IP-адрес:</strong> {*ip*} {if*ip_country*}({*ip_country*}){endif}</p>
<p>&nbsp;</p>
<p>{*browser_string*}</p>',
            'address' => '',
            'phone' => '',
            'skype' => '',
            'enable_captcha' => 0
        );
    }

    public function getForm() {
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        Yii::import('ext.TagInput');
        return new TabForm(array(
            'attributes' => array(
                'id' => __CLASS__,
                'class' => 'form-horizontal',
            ),
            'showErrorSummary' => true,
            'elements' => array(
                'general' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_GENERAL'),
                    'elements' => array(
                        'skype' => array('type' => 'text'),
                        'phone' => array('type' => 'text'),
                        'address' => array('type' => 'text'),
                    ),
                ),
                'form_feedback' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_FB'),
                    'elements' => array(
                        'form_emails' => array('type' => 'TagInput'),
                        'tempMessage' => array('type' => 'textarea', 'class' => 'editor'),
                        'enable_captcha' => array('type' => 'checkbox'),
                    ),
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'label' => Yii::t('app', 'SAVE'),
                    'class' => 'btn btn-success',
                )
            )
                ), $this);
    }

    public function rules() {
        return array(
            array('enable_captcha', 'boolean'),
            array('form_emails, tempMessage', 'required'),
            array('tempMessage, address, phone, skype', 'type', 'type' => 'string'),
        );
    }

}
