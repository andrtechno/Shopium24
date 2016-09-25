<?php
$list = array(
    array(
        'name' => 'Индивидуальный дизайн',
        'price' => 'от 2.000',
        'text' => '',
    ),
    array(
        'name' => 'URL-English',
        'price' => '300',
        'text' => 'Ваши ссылки будут выглить к примеру так: в место <code>/product/moy_luchsiy_tovar</code>  <code>/product/my_best_product</code><br/> все ссылки генерируются с помощью Yandex translate<br><br>Рекомендуем подключение услуги с самого начало формирование вашего магазина, в противном случае Вам придется пересохронять все записи заного.'
    ),
    array(
        'name' => 'Авторизация через соц. сети',
        'price' => '250',
        'text' => 'Ваши клиенты смогут авторизироватся с помощью социальных сетей:
            <span class="label label-default">ВКонтакте</span>
            <span class="label label-default">Facebook</span>
            <span class="label label-default">Google+</span> и другие.'
    ),
    array(
        'name' => 'Социальное приложение VK',
        'price' => 'от 5.000',
        'text' => 'Приложение ВКонтакте, это дубликат вашего магазина который будет работать как приложение в Вконтакте.'
    ),
    array(
        'name' => 'Социальное приложение VK',
        'price' => 'от 5.000',
        'text' => 'Приложение ВКонтакте, это дубликат вашего магазина который будет работать как приложение в Вконтакте.'
    ),
);
?>

<div class="text-center">
    <h1>Дополнительные услуги</h1>
</div>

<table class="table table-bordered table-plans">
    <tr>
        <th width="30%"></th>
        <th width="50%" class="text-center">Описание</th>
        <th width="10%" class="text-center">Цена</th>
        <th width="10%"></th>
    </tr>

    <?php foreach ($list as $row) { ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td align="center" class="text-left"><small><?= $row['text'] ?></small></td>
            <td align="center"><?= $row['price'] ?> грн.</td>
            <td align="center"><?= Html::link('Заказать', array('/users/register'), array('class' => 'btn btn-primary btn-sm')) ?></td>
        </tr>
    <?php } ?>

</table>

<div class="alert alert-info">
    Все услигу действуют на постоянной основе.
</div>