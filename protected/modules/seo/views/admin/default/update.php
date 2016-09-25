

<?php

echo $this->renderPartial('_form', array('model' => $model,
    'modelTitle' => $modelTitle,
    "modelKeywords" => $modelKeywords,
    "modelDescription" => $modelDescription,
    "modelOther" => $modelOther,));
?>