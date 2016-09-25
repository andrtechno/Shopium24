<?php if (!empty($user->payments)) { ?>
    <table class="table">
        <tr>
            <th class="text-center">№</th>
            <th>Название платежа</th>
            <th class="text-center">Сумма</th>
            <th class="text-center">Система оплаты</th>
            <th class="text-center">Дата</th>
            <th class="text-center">Статус</th>
        </tr>
        <?php foreach ($user->payments as $payment) { ?>

            <tr>
                <td class="text-center"><?= $payment->id; ?></td>
                <td><?= $payment->name; ?></td>
                <td class="text-center"><?= $payment->sum; ?></td>
                <td class="text-center">Webmoney</td>
                <td class="text-center"><?= CMS::date($payment->date_create,true); ?></td>
                <td class="text-center"><?= $payment->statusByHtml; ?></td>
            </tr>
        <?php } ?>
    </table>
    <?php
} else {
    Yii::app()->tpl->alert('info', 'У Вас нет платежей.');
}
?>