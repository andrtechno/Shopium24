<html>
<body>
Здравствуйте, <?=$user->username?>!<br>
<p>
    Вы зарегистрировались на сайте <?= Html::link(ucfirst($host),'http://'.$host.'/',array('target'=>'_blank'))?><br/>
    Код активации: <b><?=$user->active_key?></b><br><br>
    Для активации аккауна перейдите по ссылки:<br> <a target="_blank" href="http://<?= $host?>/users/profile/active/<?=$user->active_key?>">http://<?= $host?>/users/profile/active/<?=$user->active_key?></a>.
</p>
</body>
</html>