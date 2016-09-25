
<div class="text-center"><h1>Ошибка</h1>

<h2><?= $error['code'] ?></h2>
<?php
$message = (empty($error['message'])) ? Yii::t('error', $error['code']) : $error['message'];
?>
<p><?= $message ?></p>
</div>
