
<?php if (isset($this->module->adminMenu['system'])) { ?>
    <div class="row">
        <?php foreach ($this->module->adminMenu['system']['items'] as $key => $item) { ?>
            <div class="col-sm-4 col-md-3">
                <?= Html::link('<i class="'.$item['icon'].' size-x5"></i><h4>'.$item['label'].'</h4>',$item['url'],array('class'=>'thumbnail text-center')); ?>
            </div>
        <?php } ?>
    </div>
<?php } ?>