<?php

class OpenWeatherMapWidget extends BlockWidget {

    public $alias = 'ext.blocks.openweathermap';
    public $assetsUrl;
    public $errors = true;

    public function getTitle() {
        return Yii::t('default', 'OpenWeatherMapWidget');
    }

    public function init() {
        $this->setId('openweathermap-widget');
        $this->publishAssets();
        parent::init();
    }

    public function run() {
        $result = Yii::app()->cache->get(__CLASS__);
        if ($result === false) {
            if (Yii::app()->hasComponent('curl')) {
                $curl = Yii::app()->curl;

                $curl->options = array(
                    'timeout' => 320,
                    'setOptions' => array(
                        CURLOPT_HEADER => false
                    ),
                );

                $connect = $curl->run('http://api.openweathermap.org/data/2.5/weather?lat=' . $this->config['lat'] . '&lon=' . $this->config['lon'] . '&units=' . $this->config['units'] . '&cnt=10&lang=' . Yii::app()->language . '&APPID=' . $this->config['apikey']);
                if (!$connect->hasErrors()) {
                    $result = CJSON::decode($connect->getData());
                } else {
                    $result = $connect->getErrors();
                }

                Yii::app()->cache->set(__CLASS__, $result, 3600 / 2); //3600 - час
            } else {
                throw new Exception('error curl component');
            }
        }
        $this->render($this->skin, array(
            'result' => $result,
          //  'connect' => $connect
        ));
    }

    public function publishAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $this->assetsUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        if (is_dir($assets)) {
            Yii::app()->clientScript->registerCssFile($this->assetsUrl . "/css/weather.css");
        } else {
            throw new Exception(Yii::t('app', 'ERROR_ASSETS_PATH', array('{class}' => __CLASS__)));
        }
    }

    public function degToCompass($num) {
        $val = floor(($num / 22.5) + .5);
        $arr = array("N", "NNE", "NE", "ENE", "E", "ESE", "SE", "SSE", "S", "SSW", "SW", "WSW", "W", "WNW", "NW", "NNW");
        return Yii::t('OpenWeatherMapWidget.default', $arr[($val % 16)]);
    }

    public function degToCompassImage($num) {
        $val = floor(($num / 22.5) + .5);
        $arr = array("wind1", "wind2", "wind8", "wind2", "wind7", "ESE", "SE", "SSE", "wind5", "wind4", "SW", "WSW", "wind3", "WNW", "wind8", "NNW");
        return '<div class="wind ' . $arr[($val % 16)] . '"></div>';
    }

    public function getDeg() {
        if ($this->config['units'] == 'metric') {
            return '&deg;C';
        } else {
            return '&deg;F';
        }
    }

}