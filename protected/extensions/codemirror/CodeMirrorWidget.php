<?php

class CodeMirrorWidget extends CWidget {
    public $target;
    public $mode;
    public $config = array();

    public function init() {

        $this->registerAssets();
    }

    public function run() {
        $config = CJavaScript::encode($this->config);
          Yii::app()->clientScript->registerScript($this->getId(), "
              
      var editor = CodeMirror.fromTextArea(document.getElementById('{$this->target}'), {
          mode: {name: 'javascript', globalVars: true},
         lineNumbers: true,
        //autoCloseTags: true
      });

                  ");
    }

    public function registerAssets() {
        $assets = dirname(__FILE__) . '/assets';
        $min = (YII_DEBUG) ? '' : '.min';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        if (is_dir($assets)) {
            $cs = Yii::app()->clientScript;
            $cs->registerScriptFile($baseUrl . "/js/codemirror.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/php/php.js", CClientScript::POS_HEAD);
            $cs->registerCssFile($baseUrl . '/css/codemirror.css');
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }
    
    
    
    public static function registerAssets2($mode) {
        $assets = dirname(__FILE__) . '/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets, false, -1, YII_DEBUG);
        if (is_dir($assets)) {
            $cs = Yii::app()->clientScript;
            $cs->registerScriptFile($baseUrl . "/js/codemirror.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/addon/edit/matchbrackets.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/php/php.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/css/css.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/xml/xml.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/htmlmixed/htmlmixed.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/javascript/javascript.js", CClientScript::POS_HEAD);
            $cs->registerScriptFile($baseUrl . "/mode/clike/clike.js", CClientScript::POS_HEAD);
            $cs->registerCssFile($baseUrl . '/css/codemirror.css');
            

        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}
