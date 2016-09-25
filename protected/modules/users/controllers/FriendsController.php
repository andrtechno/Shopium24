<?php

/**
 * Контроллер закладок пользователей.
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users.controllers
 * @uses Controller
 */
class FriendsController extends Controller {

    public function actionIndex() {
        $user = User::model()->findByPk(Yii::app()->user->id);
        $friends = $user->getFriends();

        $condition = 'user_id = :uid OR friend_id = :uid AND user_id!="1"';
        //  return UserFriends::model()->findAll($condition, array(':uid' => $this->id));
        $criteria = new CDbCriteria;
        $criteria->condition = $condition;
        $criteria->with = array('invited', 'inviter');
        $criteria->params = array(
            ':uid' => Yii::app()->user->id,
        );
        $dp = new ActiveDataProvider('UserFriends', array(
                    'criteria' => $criteria,
                ));

        $this->render('index', array(
            'dataProvider' => $dp,
            'friends' => $friends,
        ));
    }

}
