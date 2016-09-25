<?php

//Yii::import('web.widgets.CWidget');

/**
 * Display vertical tabs in sidebar
 */
class AdminTabs extends CWidget {

    public $tabs = array();
    public $positionTabs;
    public function run() {
        $positon =($this->positionTabs=='vertical')?'vertical':'static';
        $liContent = '';
        $tabContent = '';
        $cs = Yii::app()->getClientScript();
        
        

                    
        $cs->registerScript('tabs-admin', "
            var url = document.location.toString();
            console.log(url.match('#'));
                         console.log(location.hash);
                         if(location.hash){
                            $('#redirect_hash').val(location.hash);
                         }
            if (url.match('#')) {
                $('#tabs-form a[href=\"' + location.hash + '\"]').tab('show');
                // Change hash for page-reload
             
                $('#tabs-form a').on('shown.bs.tab', function (e) {
                    window.location.hash = e.target.hash;
                    $('#redirect_hash').val(e.target.hash);
                })
            }else{
                $('#tabs-form a:first').tab('show');
                history.pushState({}, '', $('#tabs-form a:first').attr('href'));
                $('#redirect_hash').val($('#tabs-form a:first').attr('href'));
            }

            $('#tabs-form a').click(function (e) {
                e.preventDefault();
               if(url.match('#') === null){
                history.pushState({}, '', $(this).attr('href'));
                $('#redirect_hash').val($(this).attr('href'));

}

                $(this).tab('show');
            });
", CClientScript::POS_READY);
        $n = 0;

        foreach ($this->tabs as $title => $content) {
            $tabContent .= CHtml::openTag('div', array(
                        'id' => 'tab_' . $n,
                        'class' => "tab-pane tabs-pan-{$positon}",
            ));

            $tabContent .= (is_array($content)) ? $content['content'] : $content;
            $tabContent .= CHtml::closeTag('div');
            $title = (preg_match('#^(icon-)#ui', $title)) ? '<i class="icon-medium ' . $title . '"></i>' : $title;
            $liContent .= '<li><a href="#tab_' . $n . '">' . $title . '</a></li>';
            $n++;
        }

        echo CHtml::openTag('div', array('class' => 'clearfix tab-content'));
        echo '<ul class="nav nav-tabs nav-tabs-'.$positon.'" id="tabs-form">' . $liContent . '</ul>' . $tabContent;
        echo CHtml::closeTag('div');
    }

    public function runJqueryNOUSE() {
        $liContent = '';
        $tabContent = '';
        $cs = Yii::app()->getClientScript();
        $cs->registerScript('tabs', '$(".tabs-form").tabs();', CClientScript::POS_READY);
        $n = 0;

        foreach ($this->tabs as $title => $content) {
            $tabContent .= CHtml::openTag('div', array(
                        'id' => 'tab_' . $n,
                        'class' => 'tabz',
            ));

            $tabContent .= (is_array($content)) ? $content['content'] : $content;
            $tabContent .= CHtml::closeTag('div');
            $title = (preg_match('#^(icon-)#ui', $title)) ? '<span class="icon-medium ' . $title . ' noBold"></span>' : $title;
            $liContent .= '<li><a href="#tab_' . $n . '">' . $title . '</a></li>';
            $n++;
        }

        echo CHtml::openTag('div', array('class' => 'tabs-form'));
        echo '<ul class="tabs">' . $liContent . '</ul>' . $tabContent;
        echo CHtml::closeTag('div');
    }

}
