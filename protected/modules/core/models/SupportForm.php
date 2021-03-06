<?php

class SupportForm extends CFormModel {

    /**
     * @var text 
     */
    public $text;

    /**
     * @var string 
     */

    public $problem;
    protected $_problems;
    protected $_config;

    public function getConfig() {
        Yii::import('ext.bootstrap.selectinput.SelectInput');
        return array(
            'attributes' => array(
                'id' => __CLASS__,
                'class' => 'form-horizontal',
            ),
            'showErrorSummary' => false,
            'elements' => array(

                'problem' => array(
                    'type' => 'SelectInput',
                    'data' => $this->problems
                ),
                'text' => array(
                    'type' => 'textarea',
                    'hint' => 'Опишите Вашу проблему как можно подробней'
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SEND')
                )
            )
        );
    }

    public function rules() {
        return array(
            array('text, problem', 'required'),
        );
    }

    public function sendMail() {
        $server = Yii::app()->request->serverName;
        $body = '

            <b>Отправитель:</b> ' . Yii::app()->user->login . ' (ID: ' . Yii::app()->user->id . ')<br/>
            <b>Сообщение:</b><br/>============<br/> ' . $this->text;


        $mailer = Yii::app()->mail;
        $mailer->From = Yii::app()->user->email;
        $mailer->FromName = Yii::app()->settings->get('app', 'site_name');
        $mailer->Subject = '('.$server.')'.$this->problems[$this->problem];
        $mailer->Body = $body;

         $mailer->AddAddress('dev@corner-cms.com');
        $mailer->AddReplyTo('noreply@corner-cms.com');
        $mailer->isHtml(true);
        $mailer->Send();
        $mailer->ClearAddresses();
    }

    public function attributeLabels() {
        return array(
            'text' => 'Сообщение',

            'problem' => 'Проблема'
        );
    }

    public function getProblems() {
        return array(
            'errorSite' => 'Ошибка в работе сайта',
            'errorModule' => 'Ошибка в работе модуля',
            'other' => 'Другое',
        );
    }

}
