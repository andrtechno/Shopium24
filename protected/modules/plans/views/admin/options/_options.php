<?php //echo Html::checkBoxList('options[]', null, CHtml::listData(PlansOptions::model()->findAll(), 'id', 'name'))    ?>

<table class="table table-bordered">
    <tr>
        <?php foreach (Plans::model()->findAll() as $plan) { ?>
            <th class="text-center"><?= $plan->name ?></th>
        <?php } ?>
    </tr>   
    <tr>
        <?php foreach (Plans::model()->findAll() as $pan) { ?>
            <td>
                <?php
                $find = PlansOptionsRel::model()->findByAttributes(array(
                    'plan_id' => $pan->id,
                    'option_id' => $model->id,
                        ));
                ?>

                <?= Html::textField('options[' . $pan->id . ']', ($find) ? $find->value : null,array('class'=>'form-control')) ?>
            </td>

        <?php } ?>
    </tr>
</table>