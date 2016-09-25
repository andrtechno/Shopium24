<?php

/**
 * Plugin option see: https://github.com/kartik-v/bootstrap-fileinput#bootstrap-fileinput
 */
class Fileinput extends CInputWidget {

    public $options = array();
    public $selector=false;



    private $defaultOptions = array(
        'showUpload' => false,
        'showPreview' => false,
            //'showCaption'=>false,
    );

    public function init() {
         parent::init();
        $this->defaultOptions = CMap::mergeArray(array(
                    'language' => Yii::app()->language
                        ), $this->defaultOptions);
       
    }

    public function run() {
        if ($this->hasModel())
            list($name, $id) = $this->resolveNameID();
       
        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;
        if (isset($this->htmlOptions['name']))
            $name = $this->htmlOptions['name'];

        if ($this->hasModel())
            echo Html::activeFileField($this->model, $this->attribute, $this->htmlOptions);
        else
            echo Html::fileField($name, $this->value, $this->htmlOptions);
         $this->registerScript();
    }

    protected function registerScript() {
        $lang = Yii::app()->language;
         if ($this->hasModel())
            list($name, $id) = $this->resolveNameID();
         
        $dir = dirname(__FILE__) . DS . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($dir, false, -1, YII_DEBUG);
        $min = YII_DEBUG ? '' : '.min';
        $cs = Yii::app()->getClientScript();

        $cs->registerScriptFile($baseUrl . "/js/fileinput{$min}.js");
        if ($lang != 'en')
            $cs->registerScriptFile($baseUrl . "/js/fileinput_locale_{$lang}.js");
        $cs->registerCssFile($baseUrl . "/css/fileinput{$min}.css");
        $config = CJavaScript::encode(CMap::mergeArray($this->defaultOptions, $this->options));
        
        $id = ($this->selector) ?$this->selector : $id;
        $cs->registerScript(__CLASS__ . '#' . $id, "$('#$id').fileinput($config);");
    }

}