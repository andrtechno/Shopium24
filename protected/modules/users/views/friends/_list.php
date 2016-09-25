<?php if ($data->inviter->id != Yii::app()->user->id) { ?>


    <div class="friend">
        <div class="friend_image">
            <?php echo CHtml::link(CHtml::image($data->invited->avatarPath), '/users/profile/3'); ?>
        </div>
        <div class="friend_info">
            <?= CHtml::link($data->invited->login, '#') ?><br/>
            <?php
            if ($data->invited->isCheckOnline2(1)) {
                $onlineStatusCss = 'online';
                $onlineStatusText = 'В сети';
            } else {
                $onlineStatusCss = 'offline';
                $onlineStatusText = 'Нет в сети';
            }
            ?><span class="isOnline <?php echo $onlineStatusCss ?>"><?php echo $onlineStatusText ?></span>
        </div>
        <ul class="friend_options">
            <li><a href="">Написать сообщение</a></li>
            <li><a href="">Посмотреть друзей</a></li>
            <li><a href="">Убрать из друзей</a></li>
        </ul>
    </div>
<?php }
?>
