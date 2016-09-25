<?php

$this->pageName = 'Домены и цены';
$this->breadcrumbs = array($this->pageName);

function brandsort($a, $b) {
    return strnatcmp($a['classname'], $b['classname']);
}

$api = new APIHosting('dns_zone', 'listing');
$result = $api->callback(false);
?>

<?php
$array = array();
foreach ($result->response->data as $data) {

    $array[$data->class->name][] = array(
        'domain_name' => $data->name,
        'domain_price' => $data->price,
        'classname' => $data->class->name,
    );
}
?>
<div class="text-center">
    <!--<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="728" height="90" id="ukraine_1" align="middle">
    <param name="allowScriptAccess" value="http://www.ukraine.com.ua/design/ukraine/img/banner/ukraine_1.swf" />
    <param name="allowFullScreen" value="false" />
    <param name="movie" value="http://www.ukraine.com.ua/design/ukraine/img/banner/ukraine_1.swf" />
    <param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />
    <param name="FlashVars" value="partner_id=127522&destination=_blank" />
    <embed src="http://www.ukraine.com.ua/design/ukraine/img/banner/ukraine_1.swf" quality="high" bgcolor="#ffffff" width="728" height="90" name="ukraine_1" align="middle" allowScriptAccess="http://www.ukraine.com.ua/design/ukraine/img/banner/ukraine_1.swf" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" flashvars="partner_id=127522&destination=_blank" />
    </object>-->
</div>
<br/>

<div class="text-center">
    Цены указанны, нашего партрена <a target="_blank" href="<?= CMethod::getUrl('/domains/') ?>">хостинг UKRAINE</a>.
</div>


<table class="table table-bordered table-striped">
    <tr>
        <th class="text-center">Название</th>
        <th class="text-center">Место на диске</th>
        <!--<th class="text-center">inode</th>-->
        <th class="text-center">Сайтов</th>
        <th class="text-center">PHP memory_limit</th>
        <th class="text-center">Цена за период, грн</th>
    </tr>
    <?php
    $api = new APIHosting('hosting_account', 'plans');
    $result = $api->callback(false);
    $dd = array();
    foreach ($result->response->data as $plan) {
        $dd[$plan->name] = array(
            'disc' => Shopium::files_size($plan->quota->disc),
            'inode' => $plan->quota->inode,
            'sites' => $plan->quota->sites,
            'php_memory_limit' => $plan->quota->php_memory_limit,
        );
    }
    ?>

    <?php foreach ($result->response->data as $plan) { ?>

        <tr>
            <td><a target="_blank" href="<?= CMethod::getUrl() ?>"><?= $plan->name ?></a></td>
            <td class="text-center"><?= Shopium::files_size($plan->quota->disc) ?></td>
            <!--<td class="text-center"><?= $plan->quota->inode ?></td>-->
            <td class="text-center"><?= ($plan->quota->sites == 999) ? 'неограниченно' : $plan->quota->sites ?></td>
            <td class="text-center"><?= $plan->quota->php_memory_limit ?> Мб</td>
            <td>
                <?php foreach ($plan->price as $months => $price) { ?>

                    <?php
                    if ($months == 1) {
                        echo '1 месяц';
                    } elseif ($months == 3) {
                        echo '3 месяца';
                    } elseif ($months == 6) {
                        echo '6 месяцев';
                    } elseif ($months == 12) {
                        echo '1 год';
                    } else {
                        echo '2 года';
                    }
                    ?>
                    <?= $price ?> <?php //echo $plan->currency ?><br>
                <?php } ?>
                <a target="_blank" href="<?= CMethod::getUrl("/order/?plan={$plan->id}&period=12") ?>" class="btn btn-success">Заказать</a>
            </td>



        </tr>
<?php } ?>

</table>







































