
<?php

$this->widget('ext.adminList.GridView', array(
    'dataProvider' => $dataProvider,
    //'afterAjaxUpdate'=>"function(){registerDatePickers()}",
    //'filter'=>$model,
    'name' => $this->pageName,
));
?>
