<?php
Yii::import('mod.seo.SeoModule');
Yii::import('mod.seo.models.*');
if ($model->isNewRecord) {
    $modelseo = new SeoUrl;
} else {
    $modelseo = SeoUrl::model()->findByAttributes(array('url' => $model->getUrl()));
    if (!$modelseo) {
        $modelseo = new SeoUrl;
    }
}
?>

<div class="form-group">
    <div class="col-sm-4"><?php echo Html::activeLabelEx($modelseo, 'title', array('class' => 'control-label')); ?></div>
    <div class="col-sm-8">
        <?php echo Html::activeTextField($modelseo, 'title', array('class' => 'form-control')); ?>
        <?php //echo Html::error($modelseo, 'title'); ?>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-4"><?php echo Html::activeLabelEx($modelseo, 'description', array('class' => 'control-label')); ?></div>
    <div class="col-sm-8">
        <?php echo Html::activeTextArea($modelseo, 'description', array('class' => 'form-control')); ?>
        <?php //echo Html::error($modelseo, 'title'); ?>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-4"><?php echo Html::activeLabelEx($modelseo, 'keywords', array('class' => 'control-label')); ?></div>
    <div class="col-sm-8">
        <?php
        $this->widget('ext.TagInput',array(
            'attribute'=>'keywords',
            'model'=>$modelseo
        ));
        ?>
        <?php //echo Html::activeTextField($modelseo, 'keywords', array('class' => 'form-control')); ?>
        <?php //echo Html::error($modelseo, 'title'); ?>
    </div>
</div>
