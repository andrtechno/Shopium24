<?php
Yii::app()->tpl->openWidget(array(
    'title' => $this->pageName,
));
echo $model->getForm()->tabs();
Yii::app()->tpl->closeWidget();

?>





<?php

$this->widget('mod.contacts.widgets.map.AdminMapWidget');