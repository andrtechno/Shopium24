
<script type="text/javascript">
    $(function () {
        $('.delete-widget').click(function () {
            var uri = $(this).attr('href');
            var ids = $(this).attr('data-id');
            console.log(ids);
            common.ajax(uri, {}, function (data) {
                $('#ordern_' + ids).remove();
                common.notify('<?=Yii::t('app','SUCCESS_RECORD_DELETE')?>','success');
            });
            return false;
        });

        $('#createWidget').click(function () {
            var uri = $(this).attr('href');
            common.ajax(uri, {}, function (data) {

                $('body').append('<div id="dialog"></div>');

                $('#dialog').dialog({
                    modal: true,
                    autoOpen: true,
                    width: 500,
                    title: "<?= Yii::t('app', 'DEKSTOP_CREATE_WIDGET'); ?>",
                    resizable: false,
                    open: function () {
                        var obj = $.parseJSON(data);
                        $(this).html(obj.content);
                    },
                    close: function (event, ui) {
                        $(this).remove();
                    },
                    buttons: [{
                            text: common.message.save,
                            'class': 'btn btn-sm btn-success',
                            click: function () {
                                common.ajax(uri, $('#dialog form').serialize(), function (data) {
                                    var obj = $.parseJSON(data);
                                    if (obj.success) {
                                        $('#dialog').dialog('close');
                                        location.reload();
                                    } else {
                                        $('#dialog').html(obj.content);
                                    }
                                });
                            }
                        }, {
                            text: common.message.cancel,
                            'class': 'btn btn-sm btn-default',
                            click: function () {
                                $(this).dialog('close');
                            }
                        }]
                });
                $("#dialog").dialog("open");
            });
            return false;
        });

        $(".column").sortable({
            cursor: 'move',
            connectWith: ".column",
            handle: ".handle",
            placeholder: "placeholder",
            update: function (event, ui) {
                var data = $(this).sortable('serialize');
                data += '&dekstop_id=' + $(this).attr('data-dekstop-id');
                if (ui.sender) {
                    data += '&columnFrom=' + $(ui.sender).attr('data-id');
                    data += '&columnTo=' + $(this).attr('data-id');
                }
                $.post('/admin/dekstop/updateColumns', data, function (response) {

                });
            }
        });
        //$( ".column" ).disableSelection();
    });


</script>


<?php
if (isset($AddonsItems) && $dekstop->addons) {
    ?>
    <div class="row">
        <?php
        foreach ($AddonsItems as $key => $item) {
            if (isset($item['count'])) {
                $badge = '<span class="badge badge-success">' . $item['count'] . '</span>';
            } else {
                $badge = '';
            }

            $html = $badge . '<i class=" ' . $item['icon'] . ' size-x5 display-block"></i>';
            $html .= '<h4>' . $item['label'] . '</h4>';
            ?>
            <div class="col-xs-6 col-md-2 col-sm-4 col-lg-2">
                <?= Html::link($html, $item['url'], array('class' => 'small-thumbnail text-center')); ?>


            </div>
            <?php
        }
        ?>
    </div>
    <?php
}
?>


<div class="row">
    <?php
    Yii::import('app.blocks_settings.*');
    $manager = new WidgetSystemManager;
    $x = 0;
    while ($x++ < $dekstop->columns) {
        if ($dekstop->columns == 3) {
            $class = 'col-lg-4 col-md-4';
        } elseif ($dekstop->columns == 2) {
            $class = 'col-lg-6 col-md-4';
        } else {
            $class = '';
        }
        ?>
        <div class="column <?= $class; ?>" data-id="<?= $x; ?>" data-dekstop-id="<?= $dekstop->id ?>">&nbsp;
            <?php
            $cr = new CDbCriteria;

            $cr->condition = '`t`.`column`=:clmn AND `t`.`dekstop_id`=:dekstopID';
            $cr->order = '`t`.`ordern` ASC';
            $cr->params = array(
                ':clmn' => $x,
                ':dekstopID' => $dekstop->id
            );
            $widgets = DekstopWidgets::model()
                    ->findAll($cr);
            foreach ($widgets as $wgt) {
                ?>
                <div class="panel panel-default dekstop-widget" id="ordern_<?= $wgt->id ?>">
                    <div class="panel-heading">
                        <div class="panel-title">
                           <?php
                           echo $manager->getWidgetTitle($wgt->widget_id);
     
                           ?>
                            
                            
                        </div>
                        <div class="panel-option">
                            <?php

                            $system = $manager->getSystemClass($wgt->widget_id);
                            if ($system) {
                                echo Html::link('<i class="flaticon-settings"></i>', $this->createUrl('/admin/core/widgets/update', array('alias' => $wgt->widget_id)), array('class' => ' btn btn-link'));
                            }
                       
                            echo Html::link('<i class="flaticon-drag"></i>', '#', array('class' => 'handle btn btn-link'));
                            echo Html::link('<i class="flaticon-delete"></i>', $this->createUrl('dekstop/deleteWidget', array('id' => $wgt->id)), array('data-id' => $wgt->id, 'class' => 'delete-widget btn btn-link'));
                            ?>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?php $this->widget($wgt->widget_id) ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>

