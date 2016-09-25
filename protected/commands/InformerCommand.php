<?php
Yii::import('mod.users.UsersModule');
Yii::import('mod.users.models.UserShop');

class InformerCommand extends CConsoleCommand {

    public function run($args) {
        $shops = UserShop::model()->expiredDays(7)->findAll();
        if (isset($shops)) {
            foreach ($shops as $shop) {
                $this->sendEmail($shop);
            }
        }
    }

    /**
     * TODO Yii::app()->request->serverName = not work :(
     * @param object $shop UserShop
     */
    protected function sendEmail($shop) {
        $site_name = Yii::app()->settings->get('core', 'site_name');
        $host = 'buildshop.net';
        $theme = 'Уведомляем Вас';
        $body = '<html>
<body>
Уведомляем Вас, ' . $shop->user->username . ' (http://' . $shop->subdomain . '.buildshop.net)<br>

<p>Ваш интернет магазин прекратить работу "' . CMS::date_normal($shop->expired) . '" </p>
<p>Вам необходимо продлить Ваш магазин</p>
</body>
</html>';
        $mail = Yii::app()->mail;
        $mail->From = 'noreply@' . $host;
        $mail->FromName = $site_name;
        $mail->Subject = $theme;
        $mail->Body = $body;
        $mail->AddAddress($shop->user->email);
        $mail->AddReplyTo('noreply@' . $host);
        $mail->isHtml(true);
        $mail->Send();
        $mail->ClearAddresses();
    }

}

