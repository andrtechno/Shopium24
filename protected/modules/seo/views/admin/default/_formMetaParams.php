
<ul class="list-group" id="container-param-<?=$model->id?>">

    <?php
    $params = SeoParams::model()->findAllByAttributes(array('main_id' => $model->id));
    foreach ($params as $param) {
        $paramrep = str_replace('{', '', $param->param);
        $paramrep = str_replace('}', '', $paramrep);
        $paramrep = str_replace('.', '', $paramrep);
        ?>
        <li class="list-group-item" id="<?=$paramrep.$model->id?>">
            <?php echo Html::hiddenField("param[$model->id][$param->obj]",$param->param);?>
            <?php //echo Html::hiddenField("param[$model->id][$model->name][]",$param->obj);?>
            <?php echo $param->param ?> <a href="javascript:void(0);" onclick="removeParam(this);" class="pull-right">Удалить</a>
        </li>

    <?php } ?>
</ul>