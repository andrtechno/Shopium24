<?php

$this->widget('ListView', array(
    'dataProvider' => $provider,
    'id' => 'news-list-block',
    'enablePagination' => false,
    'separator' => '<hr/>',
    'ajaxUpdate' => true,
    'template' => '{items}',
    'itemView' => '_list',
    'pager' => array(
        'header' => ''
    ),
));