<?php

class UserFriends extends ActiveRecord {
    const MODULE_ID = 'users';
    /**
     * Default value 0, friend not active
     * @var int
     */
    public $status = 0;

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
        return '{{user_friends}}';
    }
    public function relations() {
        return array(
            //'orders' => array(self::HAS_MANY, 'Order', 'user_id'),
            //'ordersCount' => array(self::STAT, 'Order', 'user_id'),
//'user' => array(self::BELONGS_TO, 'User', 'friend_id'),
            //'friend' => array(self::BELONGS_TO, 'User', 'friend_id'),
            'user' => array(self::MANY_MANY, 'User', 'user_friends(user_id, friend_id)'),
              'inviter' => array(self::BELONGS_TO, 'User', 'user_id'),
              'invited' => array(self::BELONGS_TO, 'User', 'friend_id'),

           // 'friends' => array(self::HAS_MANY, 'UserFriends', 'user_id','condition'=>'friends.user_id="1" OR friends.friend_id="1"')
        );
    }
    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('user_id, friend_id', 'required'),
            array('user_id', 'UniqueAttributesValidator', 'with' => 'friend_id', 'message' => Yii::t('UsersModule.site', 'ALREADY_REQUEST_USER')),
            array('user_id, friend_id, date_create, date_update, status', 'safe', 'on' => 'search'),
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
    public static function addFriendButton($friend_id) {
        $selfId = Yii::app()->user->id;
        $check = UserFriends::model()->countByAttributes(array('user_id' => $selfId, 'friend_id' => $friend_id));
        if ($selfId != $friend_id) {
            if ($check == 0) {
                return CHtml::ajaxLink('Добавить в друзья', array('ajax/addFriend'), array(
                            'type' => 'POST',
                            'data' => array('UserFriends[friend_id]' => $friend_id, Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken),
                            'success' => 'function(result){
                        var obj = $.parseJSON(result);
                        //if(obj.errorCode > 1){

                        //}
                        $("#loading_friend").attr("id","add_friend");
                        $.jGrowl(obj.message);
                        $("#add_friend").text("Уже в друзьях");
                        $("#add_friend").attr("id","complate_friend");
                    }',
                            'beforeSend' => 'function(){
                            $("#add_friend").html("loading...").attr("id","loading_friend");
                            }'
                                ), array('id' => 'add_friend', 'class' => 'btn btn-green')
                );
            } else {
                return 'Уже в друзьях';
            }
        } else {
            return 'Себя нельзя добавлять!';
        }
    }

    public static function activeFriendButton($friend_id) {
        $check = UserFriends::model()->countByAttributes(array('user_id' => Yii::app()->user->id, 'friend_id' => $friend_id));
        if ($check != 0) {
            return CHtml::ajaxLink('Принять заявку', array('ajax/activeFriend'), array(
                        'type' => 'POST',
                        'data' => array('UserFriends[friend_id]' => $friend_id, Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken),
                        'success' => 'function(result){
                        var obj = $.parseJSON(result);
                        //if(obj.errorCode > 1){

                        //}
                        $("#loading_friend").attr("id","active_friend");
                        $.jGrowl(obj.message);
                        $("#active_friend").text("Уже в друзьях");
                        $("#active_friend").attr("id","complate_friend");
                    }',
                        'beforeSend' => 'function(){
                            $("#active_friend").html("loading...").attr("id","loading_friend");
                            }'
                            ), array('id' => 'active_friend')
            );
        } else {
            return 'Уже в друзьях';
        }
    }

    public static function deleteFriendButton($friend_id) {
        $check = UserFriends::model()->countByAttributes(array('user_id' => Yii::app()->user->id, 'friend_id' => $friend_id));
        if ($check) {
            return CHtml::ajaxLink('Удалить из друзей', array('ajax/deleteFriend'), array(
                        'type' => 'POST',
                        'data' => array('UserFriends[friend_id]' => $friend_id, Yii::app()->request->csrfTokenName => Yii::app()->request->csrfToken),
                        'success' => 'function(result){
                        var obj = $.parseJSON(result);
                        //if(obj.errorCode > 1){

                        //}
                        $("#loading_friend").attr("id","active_friend");
                        $.jGrowl(obj.message);
                        $("#active_friend").text("Уже в друзьях");
                        $("#active_friend").attr("id","complate_friend");
                    }',
                        'beforeSend' => 'function(){
                            $("#active_friend").html("loading...").attr("id","loading_friend");
                            }'
                            ), array('id' => 'active_friend')
            );
        }
    }

    public function search() {
        $criteria = new CDbCriteria;
        //$criteria->condition='`user_id`='.Yii::app()->user->id;
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('friend_id', $this->friend_id);
        $criteria->compare('date_create', $this->date_create, true);
        $criteria->compare('date_update', $this->date_update, true);
        $criteria->compare('status', $this->status);

        return new ActiveDataProvider($this, array(
                    'criteria' => $criteria,
                ));
    }

}
