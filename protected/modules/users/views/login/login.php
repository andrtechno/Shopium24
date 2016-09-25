
<h1><?= $this->pageName ?></h1>

<?php
$this->renderPartial('_form', array('model' => $model));
?>

<?php
if (Yii::app()->hasComponent('eauth'))
    Yii::app()->eauth->renderWidget();
?>