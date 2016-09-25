<?php


class SelectInput extends CInputWidget {

    public $options = array();
    public $data = array();
    public function run() {

        list($name, $id) = $this->resolveNameID();
        $config = CJavaScript::encode($this->options);
        $cs = Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__ . '#' . $id, "$('#$id').chosen($config);",  CClientScript::POS_END);
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

        $cs->registerScriptFile($baseUrl . "/js/chosen.jquery.min.js");

        
       // $cs->registerCssFile($baseUrl . "/css/chosen.css");
        $cs->registerCssFile($baseUrl . "/css/april.css");
    }

}
