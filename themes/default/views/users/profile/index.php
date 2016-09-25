<script>
    $(function(){
        var hash = window.location.hash;
        $('#profileTabs2 a[href="' + hash + '"]').tab('show');
        $('#profileTabs2 a').click(function (e) {
            e.preventDefault();
            var pane = $(this);
            var url = pane.attr("href");
            var hash = this.hash;


            var container =pane.attr('data-target');
            if(hash){
                window.location.hash = hash;
                pane.tab('show');
     
            }else{
                if ($(container).is(':empty')){
                    $(container).load(url,function(result){
                        pane.tab('show');
                    });
                }else{
                    pane.tab('show');

                }
            }
        });

    });
</script>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

    <?php
    if (Yii::app()->user->hasFlash('success')) {
        Yii::app()->tpl->alert('success', Yii::app()->user->getFlash('success'));
    }
    if (Yii::app()->user->hasFlash('error')) {
        Yii::app()->tpl->alert('danger', Yii::app()->user->getFlash('error'));
    }
    if (!$user->active) {
        Yii::app()->tpl->alert('warning', 'Ваш аккаунт не актевирован.<br/>На Вашу указанную при регистрации почту было отправлено письмо с инструкций о активации.');
    }
    ?>
    <ul class="nav nav-tabs" role="tablist" id="profileTabs2">
        <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><?= Yii::t('UsersModule.default', 'PROFILE') ?></a></li>
        <li role="presentation"><a href="#changepass" aria-controls="changepass" role="tab" data-toggle="tab"><?= Yii::t('UsersModule.default', 'CHANGE_PASSWORD') ?></a></li>
        <li role="presentation"><a href="#shops" aria-controls="shops" role="tab" data-toggle="tab"><?= Yii::t('UsersModule.default', 'Мой магазин') ?></a></li>
        <?php if ($user->active) { ?><li role="presentation"><a href="#domain" aria-controls="domain" role="tab" data-toggle="tab">Доменное имя</a></li><?php } ?>
        <?php if ($user->active && Yii::app()->user->id == 3) { ?><li role="presentation"><a href="/users/profile/orders" aria-controls="payments" role="tab" data-toggle="tab" data-target="#payments" class="ajax-tab">Платежи</a></li><?php } ?>
        <?php if ($user->active && Yii::app()->user->id == 3) { ?><li role="presentation"><a href="/users/profile/orders" aria-controls="orders" role="tab" data-toggle="tab" data-target="#orders" class="ajax-tab">История платяжей</a></li><?php } ?>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="profile"><?php $this->renderPartial('_profile', array('user' => $user)) ?></div>
        <div role="tabpanel" class="tab-pane" id="changepass"><?php $this->renderPartial('_changepass', array('user' => $user, 'changePasswordForm' => $changePasswordForm)) ?></div>
        <div role="tabpanel" class="tab-pane" id="shops"><?php $this->renderPartial('_shops', array('user' => $user)) ?></div>
        <?php if ($user->active) { ?><div role="tabpanel" class="tab-pane" id="domain"><?php $this->renderPartial('_domain', array('user' => $user)) ?></div><?php } ?>
        <?php if ($user->active && Yii::app()->user->id == 3) { ?><div role="tabpanel" class="tab-pane" id="payments"></div><?php } ?>
        <?php if ($user->active && Yii::app()->user->id == 3) { ?><div role="tabpanel" class="tab-pane" id="orders"></div><?php } ?>
    </div>




</div>

