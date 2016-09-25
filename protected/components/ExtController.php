<?php

class ExtController extends Controller { //CExtController

    protected $_baseAssetsUrl;
    protected $_assetsUrl = false;


    public function getBaseAssetsUrl() {
        if ($this->_baseAssetsUrl === null) {
            $this->_baseAssetsUrl = Yii::app()->assetManager->publish(
                    Yii::getPathOfAlias('app.assets'), false, -1, YII_DEBUG
            );
        }
        return $this->_baseAssetsUrl;
    }

    public function getAssetsUrl() {
        return $this->_assetsUrl;
    }

}
