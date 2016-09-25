<?php

class InformerCommand extends CConsoleCommand {

    public function run($args) {
        Yii::log('InformerCommand started', 'info', 'cron');


        $users = User::model()->expired(7)->findAll();
        if (isset($users)) {
            foreach ($users as $user) {
                $this->sendEmail($user);
            }
        }
    }

    protected function sendEmail($user) {
        Yii::log('InformerCommand sendmail to ' . $user->login . '', 'info', 'cron');

        $site_name = Yii::app()->settings->get('core', 'site_name');
        $host = Yii::app()->request->serverName;
        $theme = 'Уведомляем Вас';
        $body = '<html>
<body>
Уведомляем Вас, ' . $user->username . ' (http://' . $user->shop[0]->subdomain . '.buildshop.net)<br>

<p>Ваш интернет магазин прекратить работу ' . $user->shop[0]->expired . ' </p>
<p>Вам необходимо продлить Ваш магазин</p>
</body>
</html>';
        $mail = Yii::app()->mail;
        $mail->From = 'noreply@' . $host;
        $mail->FromName = $site_name;
        $mail->Subject = $theme;
        $mail->Body = $body;
        $mail->AddAddress($user->email);
        $mail->AddReplyTo('noreply@' . $host);
        $mail->isHtml(true);
        $mail->Send();
        $mail->ClearAddresses();
    }

}

