<?php

/**
 * @author Troy <troytft@gmail.com>
 */
class SortableColumn extends CDataColumn {

    public $name = 'ordern';
    public $value = '';
    public $url = null;
    public $filter = false;
    private $_assetsPath;

    public function init() {
        if ($this->url == null)
            $this->url = '/' . preg_replace('#' . Yii::app()->controller->action->id . '$#', 'sortable', Yii::app()->controller->route);

        $this->registerScripts();
        parent::init();
    }

    public function registerScripts() {
        $name = "sortable_" . Yii::app()->controller->route;
        $id = $this->grid->getId();
        $this->_assetsPath = Yii::app()->assetManager->publish(dirname(__FILE__) . '/assets', false, -1, YII_DEBUG);
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($this->_assetsPath . '/styles.css');
        $cs->registerCoreScript('jquery.ui');
        $cs->registerScript("sortable-grid-{$id}", "
            $('#{$id} tbody').sortable({
                connectWith: '.sortable-clipboard-area',
                axis : 'y',
                handle: '.sortable-column-handler',
                helper: function(event, ui){
                    ui.children().each(function() {
                        $(this).width($(this).width());
                    });
                    return ui;
                },
                update : function (event, ui) {
                    var ids = [];
                    $('#{$id} .sortable-column').each(function(i) {
                        ids[i] = $(this).data('id');
                    });
                    var clipboard = [];
                    $('.sortable-clipboard-area .sortable-column').each(function(i) {
                        clipboard[i] = $(this).data('id');
                    });
                    $.ajax({
                        url : '{$this->url}',
                        type : 'POST',
                        data : ({'ids' : ids, 'clipboard' : clipboard, 'name' : '{$name}'}),
                        success:function(){
                            common.notify('Success!','success');
                        }
                    });

                }
            });", CClientScript::POS_READY);
    }

    protected function renderDataCellContent($row, $data) {
        echo Html::image($this->_assetsPath . '/sort.png', '', array(
            'class' => 'sortable-column-handler',
            'style' => 'cursor: move;',
            ));
    }

    protected function renderHeaderCellContent() {
        echo Html::image($this->_assetsPath . '/sort_both.png', '');
    }

    public function renderDataCell($row) {
        $data = $this->grid->dataProvider->data[$row];
        $options = $this->htmlOptions;
        $options['class'] = 'sortable-column';
        $options['data-id'] = $data->primaryKey;
        echo Html::openTag('td', $options);
        $this->renderDataCellContent($row, $data);
        echo Html::closeTag('td');
    }

}
