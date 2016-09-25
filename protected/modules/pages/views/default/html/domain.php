<?php

$this->pageName = 'Домены и цены';
$this->pageTitle = 'Цены и проверить доменов. | Домены: ua, ru, su, net, com, in, org, и другие';


$this->pageKeywords ="купить домен, регистрация домена, регистрация доменов, купить доменное имя, домены, проверить домен, проверка домена, зарегистрировать домен, доменные имена, проверка доменов, регистратор доменов, регистрация доменного имени, домен ua";
$this->pageDescription ="Регистрация доменов. Мы поможем Вам выбрать домен для Вашего сайта.";

$this->breadcrumbs = array($this->pageName);

function brandsort($a, $b) {
    return strnatcmp($a['classname'], $b['classname']);
}

//$api = new APIHosting('dns_zone', 'listing');
$api = new APIHosting('dns_domain', 'zones',array('available'=>1));
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
    Цены указанны за год, нашего партрена <a target="_blank" href="<?= CMethod::getUrl('/domains/') ?>">хостинг UKRAINE</a>.
</div>
<br/><br/>





<?php
$this->widget('hosting.widgets.domainCheck.DomainCheckWidget');
?>




<table class="table table-bordered">
    <?php foreach ($array as $key => $items) { ?>
        <tr>
            <th colspan="6" class="text-center bg-warning"><?= $key ?></th>
        </tr>
        <?php
        usort($items, 'brandsort');
        $i = 0;
        foreach ($items as $kz => $row) {
            $i++;
            ?>

            <td style="text-align: center"><?= $row['domain_name'] ?></td>
            <td style="width:10%;text-align: center"><span class="text-success"><?= $row['domain_price'] ?> грн.</span></td>

            <?php if ($i % 3 == 0) { ?>
                <tr></tr>
            <?php } ?>
        <?php } ?>
    <?php } ?>
</table>
<div class="text-center">
    <a href="<?= CMethod::getUrl('/domains/') ?>" class="btn btn-lg btn-primary">Перейти к регистрации домена</a>
</div>