

<?php


Yii::app()->tpl->alert($alert['type'], $alert['text']); //ext.adminList.GridView //zii.widgets.grid.CGridView
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $model->search(),
    'ajaxUpdate'=>true,
    'columns' => array(
        array(
            'name' => 'owner_title',
            'type' => 'raw',
            'value' => 'Html::link(Html::encode($data->owner_title),array("update","id"=>$data->id))',
        ),
        array(
            'class' => 'CButtonColumn',
            'deleteButtonUrl' => 'Yii::app()->controller->createUrl("delete",array("id"=>$data->id))',
            'template' => '{delete}',
        ),
    ),
));
