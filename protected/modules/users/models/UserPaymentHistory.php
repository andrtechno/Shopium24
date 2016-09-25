<?php

class UserPaymentHistory extends ActiveRecord {


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
        return '{{user_payment_history}}';
    }

    public function relations() {
        return array(

            'payment' => array(self::BELONGS_TO, 'Payments', 'payment_id'),

        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('user_id, payment_id, name', 'required'),
            array('user_id, payment_id, name, date_create', 'safe', 'on' => 'search'),
        );
    }

    public function scopes() {
        return array(
            'subscribe' => array(
                'condition' => '`t`.`subscribe`=:subs AND `t`.`active`=:act',
                'params' => array(':subs' => 1, ':act' => 1)
            )
        );
    }

    public function getStatusByName() {
        if ($this->status == 1) {
            return 'ok';
        } elseif ($this->status == 2) {
            return 'error';
        } else {
            return 'wait';
        }
    }
    public function getStatusByCssClass() {
        if ($this->status == 1) {
            return 'success';
        } elseif ($this->status == 2) {
            return 'danger';
        } else {
            return 'default';
        }
    }
    
    public function getStatusByHtml(){
        return Html::tag('span',array('class'=>'label label-lg label-'.$this->getStatusByCssClass()),$this->getStatusByName(),true);
    }
    
    public function search() {
        $criteria = new CDbCriteria;
        //$criteria->condition='`user_id`='.Yii::app()->user->id;
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('payment_id', $this->payment_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('date_create', $this->date_create, true);


        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}
