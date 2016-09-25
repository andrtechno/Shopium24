<?php
$geoip = Yii::app()->geoip;
$data = $geoip->lookupLocation($ip);
?>
<div class="row">
    <div class="col-sm-6">
        <img class="img-thumbnail" src="//static-maps.yandex.ru/1.x/?ll=<?= $data->longitude ?>,<?= $data->latitude ?>&z=9&l=map&pt=<?= $data->longitude ?>,<?= $data->latitude ?>&pm2ywl99~<?= $data->longitude ?>,<?= $data->latitude ?>,round"alt="" width="100%">
    </div>
    <div class="col-sm-6">

        <?= CMS::ip($ip, 0); ?> <?php echo Yii::t('geoip_country', $data->countryName); ?>,
        <?php echo Yii::t('geoip_city', $data->city); ?>,
        <?php echo Yii::t('geoip_regions', $data->regionName); ?>

        <hr/>
        <?php
        $users = User::model()->findAllByAttributes(array('login_ip' => $ip));
        if (!empty($users)) {
            echo 'Пользователи заходившие с этого IP-адреса:<br>';
            foreach ($users as $user) {
                echo Html::link($user->login, array('/admin/users/default/update', 'id' => $user->id)) . '<br>';
            }
        }
        ?>

        <?php
       // $cr = new CDbCriteria;
       // $cr->condition = '`t`.`ip_address`=:ip';
       // $cr->group = '`t`.`ip_address`';
       //// $cr->distinct = true;
       // $cr->params = array(':ip' => $ip);
       // $sessions = Session::model()->findAll($cr);
        
        
        
                $sessions = Yii::app()->db->createCommand(array(
                            'select' => array('*'),
                            'from' => "{{session}}",
                    'distinct'=>true,
                    'group'=>'ip_address',
                            'where' => 'ip_address=:ip',
                            'params' => array(':ip' => $ip),
                        ))->queryAll();
        

        
        if (!empty($sessions)) {
            echo '<hr/><b>Сессии:</b><br>';
            foreach ($sessions as $sess) {
                //if ($sess->user) {
                  //  echo Html::link($sess->user->login, array('/admin/users/default/update', 'id' => $sess->user->id)) . '<br>';
               // } else {
                    if ($sess->user_type == 3) {
                        echo 'Администратор';
                    } elseif ($sess->user_type == 0) {
                        echo 'Гость';
                    }
              //  }
            }
        }
        ?>


        <div class="panel panel-default" style="display:none;">
            <div class="panel-body">

                <ul class="list-group">
                    <li class="list-group-item"><?= CMS::ip($ip, 0); ?> <?php echo Yii::t('geoip_country', $data->countryName); ?></li>
                    <li class="list-group-item"><?php echo Yii::t('geoip_regions', $data->regionName); ?></li>
                    <li class="list-group-item"><?php echo Yii::t('geoip_city', $data->city); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>



