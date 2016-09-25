
<div class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">
            <?php echo $title ?>
            <?php if (isset($options)) { ?>
                <div class="dropdown panel-option">
                    <a data-toggle="dropdown" href="#" class="btn btn-link">
                        <i class="flaticon-settings"></i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <?php foreach ($options as $opt) { ?>
                            <li><?= Html::link('<i class="' . $opt['icon'] . '"></i> ' . $opt['label'], $opt['href'], $opt['htmlOptions']) ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
        </div>



    </div>
    <div class="panel-container">
        <?php if (isset($buttons)) { ?>
            <?php foreach ($buttons as $btn) { ?>
                <?= Html::link($btn['label'], $btn['url'], $btn['htmlOptions']); ?>
            <?php } ?>
        <?php } ?>