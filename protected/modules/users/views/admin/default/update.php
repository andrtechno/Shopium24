<?php
Yii::import('app.addons.Browser');
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
    'htmlOptions' => array('class' => 'fluid')
));
if ($model->isService) {
    echo CHtml::openTag('div', array('class' => 'borderB'));
    Yii::app()->tpl->alert('info', Yii::t('UsersModule.site', 'USER_IS_SERVICE', array('{SERVICE}' => $model->service)));
    echo CHtml::closeTag('div');
}
echo $model->getForm();
Yii::app()->tpl->closeWidget();
?>
<script type="text/javascript" src="/plugins/webcam/webcam.js"></script>
<a href="javascript:void(0)" onClick="get_user_avatars('collection')">Загрузить с галереи</a>
<a href="javascript:openWebCam()"><span class="icon-medium icon-camera"></span>Сделать снимок при помощи веб-камеры</a>
<div id="dialog-webcam" class="tab_content">
    <div id="webcam-response"></div>
    <div id="webcam-left">
        <div id="camera" style="width:240px; height:240px;"></div>
        <a id="button-snap" href="javascript:snapPhoto()" class="buttonS bGreen hidden">Сделать снимок</a>
    </div>
    <div id="webcam-right">
        <div id="camera_snap" class="hidden"></div>
        <a id="button-save" href="javascript:savePhoto()" class="buttonS bGreen hidden">Сохранить</a>
    </div>
    <div class="clear"></div>
</div>
<?php

  $browser = new Browser();
  ?>
<script language="JavaScript">
            var isBrowser = '<?php echo ($browser->getBrowser() == Browser::BROWSER_FIREFOX)? 'firefox':''; ?>';
        if(isBrowser=='firefox'){
            var ff = false;
        }else{
            var ff = true;
        }
    function initWebCam(){
                Webcam.set({
                    width: 320,
                    height: 240,
                    // dest_width: 540,
                    // dest_height: 380,
                    image_format: 'jpeg',
                    jpeg_quality: 100,
                    crop_width:240,
                    crop_height:240,
                    force_flash: (navigator.userAgent.match(/firefox/i)?false:true)
                });
                Webcam.attach('#camera');
                
                Webcam.on('error', function(err) {
                    $('#webcam-response').html('<a href="javascript:initWebCam();">Еще рах</a>');
                    console.log(err);
                     $.jGrowl(err);
                });
                Webcam.on('uploadProgress', function(progress) {
                    $('#webcam-response').html('Загрузка изображение...');
                    console.log(progress);
                });
                Webcam.on('load', function() {
                    console.log('Webcam success load.');
                    $('#button-snap').removeClass('hidden');
                    $('#camera_snap').removeClass('hidden');
                    $('#webcam-response').html('<a href="javascript:initWebCam();">Еще рах</a>');
        
                });
    }
    function openWebCam(){

        $('#dialog-webcam').dialog({
            modal: true,
            resizable: false,
            width:540,
            open:function(){
                initWebCam();
            },
            close: function (event, ui) {
                Webcam.reset();
            },
            buttons:[{
                    text:'Отмена',
                    click:function(){
                        $(this).dialog('close');
                    }
                }]
                        
        });
    }
    //Сделать снимок
    function snapPhoto(){
        Webcam.snap( function(data_uri) {
            $('#camera_snap').html($('<img/>',{'alt':'','src':data_uri}));
            $('#button-save').removeClass('hidden');
        });
    }
    //Сохранить снимок
    function savePhoto(){
        var data_uri = $('#camera_snap img').attr('src');
        Webcam.upload( data_uri, '/myscript.php', function(code, text) {
            if(code=='200'){
                //$('#webcam-response').html('Готово...');
                $.jGrowl('Изображение загрунено..');
            }else{
                $('#webcam-response').html(text); 
            }

        } );
    }
    

</script>

<script type="text/javascript">

    function get_user_avatars(coll){
    
        $('body').append('<div id="dialog"></div>');
        $('#dialog').dialog({
            modal: true,
            resizable: false,
            width:'50%',
            open:function(){
                var that = this;
                $.ajax({
                    type:"POST",
                    data: {collection:coll},
                    url: '/users/profile/getAvatars',
                    success: function(result){
                        $(that).html(result);
                    }
                });
                
            },
            close: function (event, ui) {
                $(this).remove();
            },
            buttons:[{
                    text:'Сохранить',
                    click:function(){

                        var that = this;
                      
                        var image = $('#selected_avatar').val();
                        $.ajax({
                            type:"POST",
                            data: {img:image},
                            url: '/users/profile/saveAvatar',
                            success: function(result){
                                $.jGrowl('Аватар успешно сохранане', {});
                                $(that).dialog('close');
                            }
                        });

                    }
                },{
                    text:'Отмена',
                    click:function(){
                        $(this).dialog('close');
                    }
                }]
        });
    }
</script>