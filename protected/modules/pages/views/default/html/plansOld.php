<?php

function check($obj) {
    if (is_string($obj)) {
        return $obj;
    } else {
        return ($obj) ? '<i class="fa fa-check text-success"></i>' : '<i class="fa fa-remove text-danger"></i>';
    }
}

$list = array(
    array(
        'name' => 'Поддержка 24/7',
        'hint' => 'Поддержка производится по системе тикет',
        'plans' => array(
            'lite' => true,
            'medium' => true,
            'pro' => true,
        )
    ),
    array(
        'name' => 'Количество товаров',
        'plans' => array(
            'lite' => '1000',
            'medium' => '5000',
            'pro' => 'не ограничено',
        )
    ),
    array(
        'name' => 'E-mail рассылки',
        'plans' => array(
            'lite' => true,
            'medium' => true,
            'pro' => true,
        )
    ),
    array(
        'name' => 'E-mail и SMS оповещения о заказе',
        'plans' => array(
            'lite' => true,
            'medium' => true,
            'pro' => true,
        )
    ),
    array(
        'name' => 'Система скидок / наценок',
        'plans' => array(
            'lite' => true,
            'medium' => true,
            'pro' => true,
        )
    ),
    array(
        'name' => 'Личный кабинет покупателя',
        'plans' => array(
            'lite' => true,
            'medium' => true,
            'pro' => true,
        )
    ),
    array(
        'name' => 'Сайт на своём домене',
        'plans' => array(
            'lite' => true,
            'medium' => true,
            'pro' => true,
        )
    ),
    array(
        'name' => 'Адаптивый базовый дизайн',
        'hint' => 'Есть возможность выбрать цветовую гамму и структуру.',
        'plans' => array(
            'lite' => true,
            'medium' => true,
            'pro' => true,
        )
    ),
    array(
        'name' => 'Импорт/экспорт из/в Excel и CSV-файлы',
        'plans' => array(
            'lite' => false,
            'medium' => true,
            'pro' => true,
        )
    ),
    array(
        'name' => 'sitemap.xml',
        'plans' => array(
            'lite' => false,
            'medium' => true,
            'pro' => true,
        )
    ),
    array(
        'name' => 'Экспорт в Яндекс.Маркет',
        'plans' => array(
            'lite' => false,
            'medium' => true,
            'pro' => true,
        )
    ),
    array(
        'name' => 'Интеграция 1С',
        'plans' => array(
            'lite' => false,
            'medium' => true,
            'pro' => true,
        )
    ),
        /*  array(
          'name' => 'Несколько администраторов с разными правами',
          'plans' => array(
          'lite' => false,
          'medium' => true,
          'pro' => true,
          )
          ), */
);

$productsInfo = array(
    array(
        'name' => 'Количество фото товара',
        'plans' => array(
            'lite' => '3',
            'medium' => '6',
            'pro' => 'не ограничено',
        )
    ),
    array(
        'name' => 'Отзывы о товаре',
        'plans' => array(
            'lite' => true,
            'medium' => true,
            'pro' => true,
        )
    ),
    array(
        'name' => 'Рейтинг товаров',
        'plans' => array(
            'lite' => false,
            'medium' => true,
            'pro' => true,
        )
    ),
    array(
        'name' => 'Соц. кнопки +1 в товаре',
        'plans' => array(
            'lite' => false,
            'medium' => true,
            'pro' => true,
        )
    ),
);


$cart = array(
    array(
        'name' => 'Выбор способов оплаты',
        'plans' => array(
            'lite' => true,
            'medium' => true,
            'pro' => true,
        )
    ),
    array(
        'name' => 'Выбор способов доставки',
        'plans' => array(
            'lite' => true,
            'medium' => true,
            'pro' => true,
        )
    ),
);
?>

<script>
    $(function () {
        $('.hint_popup').popover({ trigger: "hover" });
    });    
</script>
<div class="text-center">
    <h1>Тарифны и цены</h1>
</div>
<table class="table table-bordered table-plans">
    <tr>
        <th width="40%"></th>
        <th width="20%" class="plan-default">LITE</th>
        <th width="20%" class="plan-default">MEDIUM</th>
        <th width="20%" class="plan-danger">PRO</th>
    </tr>
    <tr>
        <td>При оплате помесячно:</td>
        <td align="center"><b>160.00 грн/мес.</b></td>
        <td align="center"><b>500.00 грн/мес.</b></td>
        <td align="center"><b>1000.00 грн/мес.</b></td>
    </tr>
    <tr>
        <td>При оплате за 6 месяцев:</td>
        <td align="center"><b>150.00 грн/мес.</b></td>
        <td align="center"><b>480.00 грн/мес.</b></td>
        <td align="center"><b>950.00 грн/мес.</b></td>
    </tr>
    <tr class="bg-danger">
        <td>При оплате за год:</td>
        <td align="center"><b>140.00 грн/мес.</b></td>
        <td align="center"><b>450.00 грн/мес.</b></td>
        <td align="center"><b>900.00 грн/мес.</b></td>
    </tr>
    <?php foreach ($list as $row) { ?>
        <tr>
            <td><?= $row['name'] ?>
                <?php if (isset($row['hint'])) { ?>
                    <i class="fa pull-right fa-info-circle hint_popup text-info" data-toggle="hover" data-placement="left" data-trigger="hover" data-content="<?= $row['hint'] ?>"></i>
                <?php } ?>

            </td>
            <td align="center"><?= check($row['plans']['lite']) ?></td>
            <td align="center"><?= check($row['plans']['medium']) ?></td>
            <td align="center"><?= check($row['plans']['pro']) ?></td>
        </tr>
    <?php } ?>
    <tr>
        <th class="plan-default text-left" colspan="4">Товар</th>
    </tr>
    <?php foreach ($productsInfo as $row) { ?>
        <tr>
            <td><?= $row['name'] ?>


                <?php if (isset($row['hint'])) { ?>
                    <i class="fa pull-right fa-info-circle hint_popup text-info" data-toggle="hover" data-placement="left" data-trigger="hover" data-content="<?= $row['hint'] ?>"></i>
                <?php } ?>
            </td>
            <td align="center"><?= check($row['plans']['lite']) ?></td>
            <td align="center"><?= check($row['plans']['medium']) ?></td>
            <td align="center"><?= check($row['plans']['pro']) ?></td>
        </tr>
    <?php } ?>
    <tr>
        <th class="plan-default text-left" colspan="4">Корзина, заказы</th>
    </tr>
    <?php foreach ($cart as $row) { ?>
        <tr>
            <td><?= $row['name'] ?>
                <?php if (isset($row['hint'])) { ?>
                    <i class="fa pull-right fa-info-circle hint_popup text-info" data-toggle="hover" data-placement="left" data-trigger="hover" data-content="<?= $row['hint'] ?>"></i>
                <?php } ?>


            </td>
            <td align="center"><?= check($row['plans']['lite']) ?></td>
            <td align="center"><?= check($row['plans']['medium']) ?></td>
            <td align="center"><?= check($row['plans']['pro']) ?></td>
        </tr>
    <?php } ?>
    <tr>
        <td><b>Бесплатно 14 дней</b></td>
        <td align="center"><?= Html::link('Попробовать', array('/users/register', 'User[plan]' => 'lite'), array('class' => 'btn btn-danger')) ?> <?= Html::link('Демо', 'http://lite.'.Yii::app()->params['baseDomain'], array('class' => 'btn btn-info','target'=>'_blank')) ?></td>
        <td align="center"><?= Html::link('Попробовать', array('/users/register', 'User[plan]' => 'basic'), array('class' => 'btn btn-danger')) ?> <?= Html::link('Демо', 'http://medium.'.Yii::app()->params['baseDomain'], array('class' => 'btn btn-info','target'=>'_blank')) ?></td>
        <td align="center"><?= Html::link('Попробовать', array('/users/register', 'User[plan]' => 'standart'), array('class' => 'btn btn-danger')) ?> <?= Html::link('Демо', 'http://pro.'.Yii::app()->params['baseDomain'], array('class' => 'btn btn-info','target'=>'_blank')) ?></td>
    </tr>
</table>