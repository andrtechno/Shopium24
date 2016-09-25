<?php

class Payments extends ActiveRecord {


    /**
     * Returns the static model of the specified AR class.
     * @return UserFriends the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{payments}}';
    }

    public function relations() {
        return array(

        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name', 'required'),
            array('name', 'safe', 'on' => 'search'),
        );
    }

    public function getPaymentSystemClass($system=null) {
      //  if ($this->payment_system) {
            $manager = new PaymentSystemManager;
            return $manager->getSystemClass('privat24');
        //}
    }

    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('name', $this->name, true);
        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}
