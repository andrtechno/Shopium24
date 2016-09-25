
<?php

Yii::app()->tpl->openWidget(array('title' => $this->pageName));
$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $model->search(),
    'enableCustomActions' => false,
    'selectableRows' => false,
    'autoColumns' => false,
    'enableHeader' => false, //rowHtmlOptionsExpression 
    //'rowCssStyleExpression' => function($row, $data) {
    //    return ($data->info->name == 'unknown') ? 'background-color:#f2dede' : '';
    //},
    'columns' => array(
        array(
            'header' => '',
            'type' => 'html',
            'value' => 'Html::tag("span",array("class"=>$data->info->icon),"")',
            'htmlOptions' => array('class' => 'text-center')
        ),
        array(
            'name' => 'name',
            'type' => 'raw',
            'value' => '($data->info->adminHomeUrl) ? Html::link(CHtml::encode($data->info->name), $data->info->adminHomeUrl) : Html::encode($data->info->name)',
        ),
        array(
            'name' => 'access',
            'type' => 'html',
            'value' => 'Yii::app()->access->getName($data->access)',
            'htmlOptions' => array('class' => 'text-center')
        ),
        array(
            'name' => 'description',
            'value' => 'Html::encode($data->info->description)',
            'header' => Yii::t('app', 'DESCRIPTION'),
        ),
        array(
            'header' => Yii::t('app', 'VERSION'),
            'type' => 'raw',
            'value' => '$data->info->version',
            'htmlOptions' => array('class' => 'text-center')
        ),
        array(
            'header' => Yii::t('app', 'AUTHOR'),
            'type' => 'raw',
            'class'=>'EmailColumn',
            'value' => '$data->info->author',
            'htmlOptions' => array('class' => 'text-center')
        ),
        array(
            'class' => 'ButtonColumn',
            'template' => '{switch}{update}{delete}',
        ),
    ),
));
Yii::app()->tpl->closeWidget();
?>
