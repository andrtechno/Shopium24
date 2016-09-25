<?php

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'name' => $this->pageName,
    'headerOptions' => false,
    'autoColumns' => false,
    'columns' => array(
        array('class' => 'CheckBoxColumn'),
        array('class' => 'ext.sortable.SortableColumn'),
        'name',
        array(
            'name' => 'content',
            'value' => '($data->content)?"---":$data->widget;',
            'htmlOptions' => array('class' => 'text-center')
        ),
        array(
            'name' => 'position',
            'value' => '$data->showPosition("$data->position")',
            'htmlOptions' => array('class' => 'text-center')
        ),
        array(
            'name' => 'access',
            'value' => 'Yii::app()->access->getName($data->access)',
            'htmlOptions' => array('class' => 'text-center')
        ),
        array(
            'name' => 'expire',
            'value' => '($data->expire>0)?CMS::purchased_time("$data->expire"):"Без ограничений"',
            'htmlOptions' => array('class' => 'text-center')
        ),
        array(
            'class' => 'ButtonColumn',
            'template' => '{switch}{update}{delete}',
        ),
    ),
));
