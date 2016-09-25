<?php

class UserHosting extends ActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user_hosting}}';
    }

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('db_pass, db_login', 'length', 'max' => 255),
            array('user_id', 'numerical', 'integerOnly' => true),
            array('db_login, db_login, user_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'user' => array(self::HAS_ONE, 'User', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'USERID',
            'db_login' => 'DB login',
            'db_pass' => 'DB PASS',
        );
    }

    public function search() {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('db_pass', $this->db_pass, true);
        $criteria->compare('db_login', $this->db_login, true);

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                    'pagination' => array(
                        'pageSize' => 2,
                        'pageVar' => 'page',
                    ),
                ));
    }

}
