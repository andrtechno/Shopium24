<?php
Yii::import('mod.users.UsersModule');
if (Yii::app()->user->isGuest) {


    $iconLogin = (isset($this->icon_login)) ? '<i class="' . $this->icon_login . '"></i>' : '';
    echo Html::openTag('li');
    echo Html::ajaxLink($iconLogin . Yii::t('UsersModule.default', 'ENTER'), Yii::app()->createUrl('/users/login'), array(
        'type' => 'GET',
        'success' => "function( data ){
        var result = data;
        $('#login-form').dialog({
        model:true,
        // autoOpen: false,
        height: 'auto',
        title:'Авторизация',
        width: 420,
        modal: true,
        responsive: true,
        resizable: false,
        draggable: false,
        open:function(){
            $('#login-form').keypress(function(e) {
                if (e.keyCode == $.ui.keyCode.ENTER) {
                      login();
                }
            });
            $('.ui-widget-overlay').bind('click', function() {
                $('#login-form').dialog('close');
            });
            $('.ui-dialog :button').blur();
        },
        close:function(){
            $('#login-form').dialog('close');
        },
            buttons: [
            {
                text: '" . Yii::t('UsersModule.default', 'BTN_LOGIN') . "',
                'class':'btn btn-success',
                click: function() {
                    login();
                }
            }

            ]
        
        });
        $('#login-form').html(result); 
                $('.ui-dialog').position({
                  my: 'center',
                  at: 'center',
                  of: window,
                  collision: 'fit'
            });
        }",
        // 'data' => array('val1' => '1', 'val2' => '2'), // посылаем значения
        'cache' => 'false' // если нужно можно закэшировать
            ), array(// самое интересное
        // 'href' => Yii::app()->createUrl('ajax/new_link222'), // подменяет ссылку на другую
        'class' => 'top-signin', // добавляем какой-нить класс для оформления
                'id'=>'user-signin'
            )
    );
    echo Html::closeTag('li');
    if (Yii::app()->settings->get('users', 'registration')) {
        echo Html::openTag('li',array('class'=>'hidden-xs'));
        $iconReg = (isset($this->icon_register)) ? '<i class="' . $this->icon_register . '"></i>' : '';
        echo Html::link($iconReg . Yii::t('UsersModule.default', 'REGISTRATION'), array('/users/register'), array('class' => ''));
        echo Html::closeTag('li');
    }
} else {
    ?>
    <li class="dropdown dropdown-small">
        <a href="#" class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown"><i class="flaticon-user"></i> <?= $this->username ?> <span class="caret"></span></a>
        <ul class="dropdown-menu">
            <li><?= Html::link('<i class="flaticon-user"></i> ' . Yii::t('default', 'PROFILE'), array('/users/profile/')); ?></li>
            <?php
            if (Yii::app()->user->isSuperuser) {
                echo Html::openTag('li');
                echo Html::link('<i class="icon-wrench"></i> ' . Yii::t('app', 'ADMIN_PANEL'), array('/admin/'));
                echo Html::closeTag('li');
            }
            ?>
            <li><?= Html::link('<i class="flaticon-logout"></i> ' . Yii::t('app', 'LOGOUT'), Yii::app()->createUrl('/users/logout/')); ?></li>
        </ul>
    </li>
    <?php
}
?>

