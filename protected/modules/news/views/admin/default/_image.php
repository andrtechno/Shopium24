<div class="clearfix"></div>
<?php
$this->widget('ext.xupload.XUpload', array(
                    //'url' => Yii::app()->createUrl("site/upload"),
                    'model' => $model,
                    'attribute' => 'file',
                    'multiple' => true,
                    'showForm'=>false,
));
/*
$this->widget('ext.image-attachment.ImageAttachmentWidget', array(
    'model' => $model,
    'behaviorName' => 'preview',
    'apiRoute' => '/admin/news/default/saveImageAttachment',
));*/
