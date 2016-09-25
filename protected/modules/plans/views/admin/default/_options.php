<?php //echo Html::checkBoxList('options[]', null, CHtml::listData(PlansOptions::model()->findAll(), 'id', 'name'))  ?>
<?php
$check = array();
foreach($model->optionsList2 as $t){
    $check[$t->option_id]['check'] = true;
    $check[$t->option_id]['value'] = $t->value;

    
}

?>
<?php Yii::app()->tpl->alert('info','Значение 1|0 - отвечает за "доступно/не доступно"',false);
?>
<table class="table table-striped">
<?php foreach (PlansOptions::model()->findAll(array('order' => 'ordern DESC')) as $option) { ?>
    <tr>
        <td> <?= $option->name; ?></td>
        <?php //echo Html::checkBox('options[]',$option->id); ?>
       
        <td>
        <?= Html::textField('options['.$option->id.']',$check[$option->id]['value'],array('class'=>'form-control')); ?>
        </td>
    </tr>
<?php } ?>
</table>
