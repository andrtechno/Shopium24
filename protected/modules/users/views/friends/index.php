<?php

$this->pageTitle = Yii::t('usersModule.site', 'Друзия');


$this->widget('ListView', array(
    'dataProvider' => $dataProvider,
    'ajaxUpdate' => false,
    'template' => '{items} {pager}',
    'itemView' => '_list',
    'sortableAttributes' => array('date_create'),
    'pager' => array(
        'htmlOptions' => array('class' => 'pagination'),
        'header' => '',
        'nextPageLabel' => 'Следующая »',
        'prevPageLabel' => '« Предыдущая',
    )
));
?>
<style>
    .friend{
        height:100px;
        padding: 0 10px;
        border-bottom: 1px solid #232323;
        position: relative;
    }

    .friend .friend_options{
        position: absolute;
        right:0;
        top:0;
    }
    .friend .friend_info{
        position: absolute;
        left:100px;
        top:0;
    }
    .friend .friend_image{
        position: absolute;
        left:0;
        top:0;
    }
    </style>