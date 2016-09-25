<?php


class UserGroup extends ActiveRecord {

    public $new_password;
    public $verifyCode;
    public $_config;
    protected $_avatarPath;

    public function getConfig() {
        Yii::import('zii.widgets.jui.CJuiDatePicker');
        return array('id' => 'userUpdateForm',
            'enctype' => 'multipart/form-data',
            'showErrorSummary' => true,
            'elements' => array(
                'username' => array('type' => 'text'),
                'email' => array('type' => 'text'),
                'date_registration' => array(
                    'type' => 'CJuiDatePicker',
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd ' . date('H:i:s'),
                    ),
                ),
                'last_login' => array(
                    'type' => 'CJuiDatePicker',
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd ' . date('H:i:s'),
                    ),
                ),
                'date_birthday' => array(
                    'type' => 'CJuiDatePicker',
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd',
                    ),
                ),
                'gender' => array(
                    'type' => 'dropdownlist',
                    'items' => self::getSelectGender()
                ),
                'avatar' => array('type' => 'text'),
                'login_ip' => array('type' => 'text'),
                'new_password' => array('type' => 'password'),
                'banned' => array('type' => 'checkbox'),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'label' => ($this->isNewRecord) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'SAVE')
                )
            )
        );
    }


    /**
     * Returns the static model of the specified AR class.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_group}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {

        return array(
            array('group_name, group_access', 'required'),

            array('id, username, email, date_registration, last_login, banned, avatar', 'safe', 'on' => 'search'),
        );
    }



    public function attributeLabels() {
        $this->_attrLabels = array(
            'username' => Yii::t('usersModule.site', 'LOGIN'),
            'password' => Yii::t('usersModule.site', 'PASSWORD'),
            'email' => Yii::t('app', 'Email'),
            'avatar' => Yii::t('usersModule.core', 'Avatar'),
            'date_registration' => Yii::t('usersModule.core', 'Дата регистрации'),
            'last_login' => Yii::t('usersModule.core', 'Последний вход'),
            'login_ip' => Yii::t('usersModule.core', 'IP Адрес'),
            'new_password' => Yii::t('usersModule.site', 'NEW_PASSWORD'),
            'banned' => Yii::t('usersModule.core', 'Бан'),
            'gender' => 'Пол',
            'subscribe' => 'Подписатся на рассылку',
            'date_birthday' => 'Дата рождения',
        );
        return CMap::mergeArray($this->_attrLabels, parent::attributeLabels());
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('date_registration', $this->date_registration, true);
        $criteria->compare('avatar', $this->avatar, true);
        $criteria->compare('last_login', $this->last_login);
        $criteria->compare('banned', $this->banned);

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }


}
