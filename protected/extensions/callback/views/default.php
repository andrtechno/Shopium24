
<?php

//index.php?r=main/ajax/widget.actionCallback
echo CHtml::ajaxLink(Yii::t('CallbackWidget.default', 'BUTTON'), array('/ajax/callback.action'), array(
    'type' => 'GET',
    'beforeSend' => "function(){
              //$.jGrowl('Загрузка...');
              $('body').append('<div id=\"callback-dialog\"></div>');
              }
    ",
    'success' => "function( data ){
        var result = data;
        $('#callback-dialog').dialog({
        model:true,
        responsive: true,
        resizable: false,
        height: 'auto',
        minHeight: 95,
        title:'" . Yii::t('CallbackWidget.default', 'TITLE') . "',
        width: 400,
        open:function(){
            $('#callback-form').keypress(function(e) {
                if (e.keyCode == $.ui.keyCode.ENTER) {
                      $('#callback-form').submit();
                }
            });
            $('.ui-widget-overlay').bind('click', function() {
                $('#callback-dialog').dialog('close');
            });
                $('.ui-dialog :button').blur();
        },
        close:function(){
            $('#callback-dialog').remove();
            $('#jGrowl').jGrowl('shutdown').remove();
            $('a.btn-callback').removeClass(':focus');        
        },
        buttons: [
            {
                text: '" . Yii::t('CallbackWidget.default', 'BUTTON_SEND') . "',
                'class':'btn btn-danger btn-callback wait',
                click: function() {
                  //  $('.btn-callback').hide();
                    callbackSend();
                }
            }
        ]
        
        });

        $('#callback-dialog').html(result);

        $('.ui-dialog').position({
                  my: 'center',
                  at: 'center',
                  of: window,
                  collision: 'fit'
            });



       /* var topNum = $('.ui-dialog').css('top');
        topNum.replace(/px/g,'');
        var h = $('.ui-dialog').height();
        $('.ui-dialog').css({top:(parseInt(topNum) - Math.round(parseInt(h/2)))});
           */


        }",
    // 'data' => array('val1' => '1', 'val2' => '2'), // посылаем значения
    'cache' => 'false' // если нужно можно закэшировать
        ), array(// самое интересное
    // 'href' => Yii::app()->createUrl('ajax/new_link222'), // подменяет ссылку на другую
    'class' => "btn btn-danger btn-callback" // добавляем какой-нить класс для оформления
        )
);