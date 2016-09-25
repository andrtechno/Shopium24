<?php

abstract class AbstractDbAR extends CActiveRecord {

    public function getDbConnection() {
        return Yii::app()->dbUser;
    }

}