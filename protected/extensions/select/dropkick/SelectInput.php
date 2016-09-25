<?php

/**
 * API
 * http://www.jqueryscript.net/form/Easy-jQuery-Based-Drop-Down-Select-List-DropKick.html
 */
class SelectInput extends CInputWidget {

    public $options = array();
    public $data = array();
    public function run() {

        list($name, $id) = $this->resolveNameID();
        $config = CJavaScript::encode($this->options);
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $id, "$('#$id').dropkick($config);",  CClientScript::POS_END);
        self::registerScript();

        if (isset($this->htmlOptions['id']))
            $id = $this->htmlOptions['id'];
        else
            $this->htmlOptions['id'] = $id;
        if (isset($this->htmlOptions['name']))
            $name = $this->htmlOptions['name'];

        if ($this->hasModel())
            echo Html::activeDropDownList($this->model, $this->attribute, $this->data, $this->htmlOptions);
        else
            echo Html::dropdownlist($name, $this->value, $this->data, $this->htmlOptions);
        
    }

    public static function registerScript() {

      //  if ($this->hasModel())
      //      list($name, $id) = $this->resolveNameID();

        $dir = dirname(__FILE__) . DS . 'assets';
        $baseUrl = Yii::app()->getAssetManager()->publish($dir, false, -1, YII_DEBUG);
        $min = YII_DEBUG ? '' : '.min';
        $cs = Yii::app()->getClientScript();

        $cs->registerScriptFile($baseUrl . "/js/jquery.dropkick-min.js");
       // $cs->registerScriptFile($baseUrl . "/js/dropkick.js");
        
        $cs->registerCssFile($baseUrl . "/css/dropkick.css");
        $cs->registerCssFile($baseUrl . "/css/example.css");
    }

}
