<?php

/**
 * languages https://www.tinymce.com/download/language-packages/
 */
class TinymceWidget extends CWidget {

    protected $assetsPath;
    protected $assetsUrl;

    public function init() {
        if ($this->assetsPath === null) {
            $this->assetsPath = dirname(__FILE__) . DS . 'assets';
        }
        if ($this->assetsUrl === null) {
            $this->assetsUrl = Yii::app()->assetManager->publish($this->assetsPath, false, -1, YII_DEBUG);
        }
        $this->registerScript();
    }

    protected function registerScript() {
        $cs = Yii::app()->clientScript;
        $defaultOptions = array(
            'selector' => ".editor",
            'language' => Yii::app()->language,
            'contextmenu' => "link image inserttable | cell row column deletetable",
            'plugins' => array(
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen textcolor codemirror",
                "insertdatetime media table contextmenu paste moxiemanager pagebreak",
            ),
            'toolbar' => "insertfile undo redo | styleselect fontsizeselect | forecolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | pagebreak",
            'relative_urls' => false,
            'codemirror' => array(
                'indentOnInit' => true, // Whether or not to indent code on init.
                'fullscreen' => false, // Default setting is false
                'path' => $this->assetsUrl . '/plugins/codemirror/CodeMirror', // Path to CodeMirror distribution
                'config' => array(// CodeMirror config object
                    'mode' => 'application/x-httpd-php',
                    'lineNumbers' => false
                ),
                'width' => 800, // Default value is 800
                'height' => 600, // Default value is 550
                'jsFiles' => array(// Additional JS files to load
                    'mode/clike/clike.js',
                    'mode/php/php.js'
                )
            ),
            'table_class_list' => array(
                array('title' => 'None', 'value' => ''),
                array('title' => 'Striped', 'value' => 'table table-striped'),
                array('title' => 'Bordered', 'value' => 'table table-bordered'),
                array('title' => 'Bordered & Striped', 'value' => 'table table-bordered table-striped'),
                array('title' => 'Hover', 'value' => 'table table-hover'),
                array('title' => 'Condensed', 'value' => 'table table-condensed'),
            ),
            'image_title' => true,
            'image_class_list' => array(
                array('title' => 'None', 'value' => ''),
                array('title' => 'Rounded', 'value' => 'img-rounded'),
                array('title' => 'Rounded & Responsive', 'value' => 'img-rounded img-responsive'),
                array('title' => 'Circle', 'value' => 'img-circle'),
                array('title' => 'Circle & Responsive', 'value' => 'img-circle img-responsive'),
                array('title' => 'Thumbnail', 'value' => 'img-thumbnail'),
                array('title' => 'Thumbnail & Responsive', 'value' => 'img-thumbnail img-responsive'),
                array('title' => 'Responsive', 'value' => 'img-responsive'),
            )
        );
        if (file_exists(Yii::getPathOfAlias("current_theme.assets.css") . DS . 'tinymce.css')) {
            $defaultOptions['content_css'] = Yii::app()->controller->getAssetsUrl() . '/css/tinymce.css';
        }

        if (is_dir($this->assetsPath)) {
            $cs->registerScriptFile($this->assetsUrl . '/tinymce.min.js', CClientScript::POS_HEAD);
            $cs->registerScript('tinymce', 'tinymce.init(' . CJSON::encode($defaultOptions) . ');', CClientScript::POS_HEAD);
        } else {
            throw new Exception(__CLASS__ . ' - Error: Couldn\'t find assets to publish.');
        }
    }

}
