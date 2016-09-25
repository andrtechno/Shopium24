
    <?php if (Yii::app()->user->hasFlash('success')) { ?>
        <div class="alert alert-success"><?= Yii::app()->user->getFlash('success') ?></div>
    <?php } ?>
    <?php
    Yii::app()->tpl->openWidget(array('title' => $this->pageName));
    echo $form;
Yii::app()->tpl->closeWidget();

    ?>
