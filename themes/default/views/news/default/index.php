<div class="blog-page">
<h3 class="section-title"><?= $this->pageName; ?></h3>
<?php

$this->widget('ListView', array(
    'dataProvider' => $provider->search(),
    'id' => 'news-list',
    'ajaxUpdate' => true,
    'template' => '{items} {pager}',
    'itemView' => '_list',
    'pager' => array(
        'header' => ''
    ),
));
?>
</div>
