<div class="list-group-item">
    <span class="label label-default">
        <?= CMS::date($data->datetime) ?>
    </span>&nbsp;
    <span data-toggle="popover" data-placement="top" data-content="<?= CMS::getCountryNameByIp($data->ip) ?>"> [<?= $data->user->login ?>]</span> <?= $data->message ?>
</div>