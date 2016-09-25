<ul id="langs">
    <?php
    foreach ($language->getLanguages() as $lang) {

        $class = ($language->active->id == $lang->id) ? 'active' : '';
        $link = ($lang->default) ? CMS::currentUrl() : '/' . $lang->code . CMS::currentUrl();
        ?>
        <li class="<?= $class ?>">
            <?= Html::link($lang->code, $link, array('class' => 'text-uppercase')); ?>
        </li>
    <?php } ?>
</ul>