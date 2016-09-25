<?php

/**
 * 
 * The CORNER CMS License
 *
 * Copyright (c) 2014-2016 CORNER CMS development team <dev@corner-cms.com>.
 *
 * Website: http://corner-cms.com
 * E-mail: support@corner-cms.com
 * 
 * Class BlockWidget
 * 
 * @version 1.0
 * @author CORNER CMS development team <dev@corner-cms.com>
 * 
 * @property object $cs clientScript
 * @property string $assetsUrl assetManager
 * @property string $assetsPath assets dir path
 * @property array $registerFile array widget js,css scripts
 * @property array $registerCoreFile array of core js scripts
 * 
 * @property string $this->name Название виджета
 * @property string $this->title Заголовок виджета
 * @property string $this->baseDir Абсолютный путь папки виджета.
 * @property array $this->config Настройки виджета.
 */
class BlockWidget extends CWidget {

    private $cs;
    public $assetsUrl;
    public $assetsPath;
    public $registerFile = array();
    public $registerCoreFile = array();
    private $_title;

    public function init() {

        if (file_exists($this->getBaseDir() . DS . 'assets')) {
            $this->assetsPath = $this->getBaseDir() . DS . 'assets';
            $this->cs = Yii::app()->clientScript;
            $this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
            $this->registerAssets();
        }
        // echo $this->getViewPath(false);
    }

    public function getBaseDir() {
        $class = new ReflectionClass($this);
        return dirname($class->getFileName());
    }

    public function getTitle() {
        if (file_exists($this->getBaseDir() . DS . 'messages')) {
            return $this->_title = Yii::t($this->getName() . '.default', 'TITLE');
        } else {
            if ($this->_title !== null) {
                return $this->_title;
            } else {
                return $this->_title = $this->getName();
            }
        }
    }

    public function setTitle($value) {
        $this->_title = $value;
    }

    public function getConfig() {
        return Yii::app()->settings->get($this->getName());
    }

    public function getName() {
        return get_class($this);
    }

    protected function registerAssets() {
        foreach ($this->registerFile as $file) {
            if (preg_match('/[-\w]+\.js/', $file)) {
                $this->cs->registerScriptFile($this->assetsUrl . '/js/' . $file);
            } else {
                $this->cs->registerCssFile($this->assetsUrl . '/css/' . $file);
            }
        }
        foreach ($this->registerCoreFile as $file) {
            $this->cs->registerCoreScript($file);
        }
    }

}

?>
