<?php

/**
 * <b>Example of use:</b>
 * 
 * <code>
 * 
 * </code>
 * 
 * @package widgets.other
 * @uses CComponent
 */
class Jgrowl extends CComponent {

    public static function register() {
        $assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.jgrowl.assets'), false, -1, YII_DEBUG);
        $cs = Yii::app()->clientScript;
        $min = YII_DEBUG ? '' : '.min';
        $cs->registerCssFile($assetsUrl . "/jquery.jgrowl{$min}.css");
                if(Yii::app()->controller instanceof AdminController){
                    $cs->registerCssFile($assetsUrl . "/dashboard.css");
                }
        $cs->registerScript('jGrowl', "$.jGrowl.defaults.themeState = '';", CClientScript::POS_HEAD);
        $cs->registerScriptFile($assetsUrl . "/jquery.jgrowl{$min}.js", CClientScript::POS_HEAD);
    }

}
