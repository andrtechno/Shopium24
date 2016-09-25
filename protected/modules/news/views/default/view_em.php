<?php
$this->widget('ext.admin.frontControl.FrontControlWidget', array(
    'data' => $model,
    'options' => array(
        'position' => 'right'
    )
));
?>

    <h1><?= $model->getTitle(); ?></h1>
    <div class="date">
        <span class="icon-calendar-2"></span>
<?= CMS::date($model->date_create) ?>
    </div>
    <div class="edit_mode_text2" id="News[short_text]"><?= Html::text($model->short_text); ?></div>


<?php
if (Yii::app()->hasModule('comments')) {
    $this->widget('mod.comments.widgets.comment.CommentWidget', array('model' => $model));
}


