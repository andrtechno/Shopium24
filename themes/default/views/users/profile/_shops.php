<table class="table table-bordered">
    <tr>
        <th class="text-center">Сайта</th>
        <th class="text-center">Окончание периода</th>
        <th class="text-center">Тарифный план</th>
        <th class="text-center">Дата создание магазина</th>
        <th class="text-center">Опции</th>
    </tr>
    <?php foreach ($user->shop as $shop) { ?>
        <tr>
            <td><?= Html::link($shop->getSubdomainFull(), $shop->getSubdomainUrl(), array('traget' => '_blank')); ?></td>
            <td class="text-center"><?= CMS::date(date('Y-m-d H:i:s', strtotime($shop->expired))) ?></td>
            <td class="text-center"><span class="label label-primary"><?= $shop->plan ?></span></td>
            <td class="text-center"><?= CMS::date($shop->date_create); ?></td>
            <td class="text-center">---</td>
        </tr>
    <?php } ?>
</table>

