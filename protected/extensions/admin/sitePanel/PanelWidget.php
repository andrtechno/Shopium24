<?php

/**
 * PanelWidget 
 * 
 * @property array $menu Массив меню
 * @uses CWidget
 * @access superuser aka Admin
 */
class PanelWidget extends CWidget {

    public $pos = 'top'; //default is top
    public $menu = array();
    private $posArray = array('top', 'bottom', 'left', 'right');
    public $posTooltip;

    public function init() {
        if ($this->pos == 'top') {
            $this->posTooltip = 'bottom';
        } elseif ($this->pos == 'bottom') {
            $this->posTooltip = 'top';
        }
        if (Yii::app()->user->isSuperuser)
            $this->registerScripts();
    }

    public function run() {
        if (Yii::app()->user->isSuperuser) {
            Yii::import('mod.admin.widgets.EngineMainMenu');
            $modules2 = new EngineMainMenu;
            $modules = $modules2->findMenu();
            $this->menu = array(
                array(
                    'label' => 'Система',
                    'url' => 'javascript:void(0)',
                    'icon' => 'icon-wrench',
                    'items' => Yii::app()->getModule('app')->adminMenu['system']['items']
                ),
                array(
                    'label' => 'Модули',
                    'url' => 'javascript:void(0)',
                    'icon' => 'flaticon-menu',
                    'items' => $modules['modules']['items']
                ),
                $modules['orders'],
                $modules['shop']
            );
            $this->render($this->getMySkin(), array('menu' => $this->menu));
        }
    }

    private function getMySkin() {
        if ($this->pos == 'right' || $this->pos == 'left') {
            return $this->skin . '_left-right';
        } else {
            return $this->skin;
        }
    }

    private function registerScripts() {
        $assetsUrl = Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG
        );
        $cs = Yii::app()->clientScript;

        $cs->registerScriptFile($assetsUrl . '/admin_panel.js');
        $cs->registerScriptFile($assetsUrl . "/bootstrap-toggle.js");
        $cs->registerScriptFile($assetsUrl . "/jquery.session.js");
        $cs->registerCssFile($assetsUrl . "/ap-bootstrap.css");
        $cs->registerCssFile($assetsUrl . "/bootstrap-toggle.css");
        $cs->registerCssFile($assetsUrl . '/admin_panel.css');
        $cs->registerCoreScript('cookie');

        if (Yii::app()->controller->getEdit_mode()) {
            $this->widget('ext.tinymce.TinymceWidget');
            $cs->registerScriptFile($assetsUrl . '/edit_mode.js', CClientScript::POS_BEGIN);
            $cs->registerCssFile($assetsUrl . '/edit_mode.css');
            $cs->registerScript('dsasda', "
                function tinymce_ajax(obj){
    var form = obj.formElement;
    var str = $(form).serialize();
    str+='&edit_mode=1&redirect=0';

    $.ajax({
        type:$(form).attr('method'),
        url:$(form).attr('action'),
        data:str,
        dataType:'json',
        beforeSend:function(){
            progressState(obj,true);
        },
        success: function(response){
            if(response.errors !== undefined){
                $.each(response.errors, function (key, data) {
                    $.jGrowl(data);
                });
            }else{
                 $.jGrowl(response.message);
            }
           
            progressState(obj,false);
        },
        error:function(jqXHR, textStatus, errorThrown){
            console.log(textStatus);
            console.log(jqXHR);
            $.jGrowl('Ошабка: ');
            progressState(obj,false);
        }
    });
}
/*preloader tinymse*/
function progressState(obj,bool){
     obj.setProgressState(bool);
}
tinymce.init({
    selector: '.edit_mode_title',
    language : common.language,
    inline: true,
    width : 100,
    plugins: 'save',
    toolbar: 'save undo redo',
    menubar: false,
    toolbar_items_size: 'small',
    save_enablewhendirty: true,
    save_onsavecallback: function() {
        console.log(this);
        tinymce_ajax(this);
    },
    //content_css : '" . Yii::app()->controller->getAssetsUrl() . "/css/tinymce.css'
});

tinymce.init({
    selector: '.edit_mode_text',
    language : common.language,
    inline: true,
    width : 200,
    plugins: 'save',
    toolbar: 'save undo redo | styleselect',
    menubar: false,
    toolbar_items_size: 'small',
    save_onsavecallback: function() {
        console.log(this);
        tinymce_ajax(this);
    },
   // content_css : '" . Yii::app()->controller->getAssetsUrl() . "/css/tinymce.css'

});


                    ");
        }
    }

}

?>
