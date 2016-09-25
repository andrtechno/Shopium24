<?php
Yii::app()->clientScript->registerScript('tabs2', "
(function($){
    $('.loganalyzer').on('click','.stack-btn',function(e){
        $(this).nextAll('.stack-pre').slideToggle('fast');
        e.preventDefault();
        return false;
    });
    
    $('#stack-showall').click(function(e){
        $('.stack-pre').slideDown('fast');
        e.preventDefault();
        return false;
    });
    
    $('#stack-collapseall').click(function(e){
        $('.stack-pre').slideUp('fast');
        e.preventDefault();
        return false;
    });
    
    $('#clear').click(function(e){
        if(!confirm('" . Yii::t('LogAnalyzerWidget.main', 'Are you sure you want to clear this log file?') . "')) {
            e.preventDefault();
            return false;
        }
    });
    
    $('.filter-log').click(function (e) {
        var rel   = $(this).attr('rel'),
            error = $('.log-list .error-line'),
            warn  = $('.log-list .warning-line'),
            info  = $('.log-list .info-line');
            sql  = $('.log-list .sql-line');

        if (rel == 'error') {
            error.slideDown('fast');
            warn.slideUp('fast');
            info.slideUp('fast');
        } else if (rel == 'warning') {
            error.slideUp('fast');
            warn.slideDown('fast');
            info.slideUp('fast');
        } else if (rel == 'info') {
            error.slideUp('fast');
            warn.slideUp('fast');
            info.slideDown('fast');
        } else if (rel == 'sql') {
            error.slideUp('fast');
            warn.slideUp('fast');
            info.slideDown('fast');
        }else if (rel == 'all') {
            error.slideDown('fast');
            warn.slideDown('fast');
            info.slideDown('fast');
        }
        
        e.preventDefault();
        return false;
    });
})(jQuery);
"
);

Yii::app()->tpl->openWidget(array(
    'title' => $this->title,
));
?>

<div class="loganalyzer">
    <div class="row-fluid log-actions-bar">
        <?php
        $logFile = Yii::getPathOfAlias('application.runtime') . DS . 'application.log';
        if (file_exists($logFile)) {
            ?>
            <a href="<?php echo $this->getUrl(); ?>" class="btn btn-danger"><?php echo Yii::t('LogAnalyzerWidget.main', 'Clear Log') ?></a>

            <span class="sep"></span>
        <?php } ?>
        <?php echo Yii::t('LogAnalyzerWidget.main', 'Log Filter') ?>:
        <div class="btn-group">
            <a href="#" class="filter-log btn btn-default" rel='all'><?= Yii::t('LogAnalyzerWidget.main', 'All') ?></a>
            <a href="#" class="filter-log btn btn-danger" rel='error'>Error</a>
            <a href="#" class="filter-log btn btn-warning" rel='warning'>Warning</a>
            <a href="#" class="filter-log btn btn-info" rel='info'>Info</a>
            <a href="#" class="filter-log btn btn-primary" rel='sql'>SQL</a>
        </div>
        <span class="sep"></span>

        Stack Trace:
        <div class="btn-group">
            <a href="#" id="stack-showall" class="btn btn-default"><?php echo Yii::t('LogAnalyzerWidget.main', 'Show All') ?></a>
            <a href="#" id="stack-collapseall" class="btn btn-default"><?php echo Yii::t('LogAnalyzerWidget.main', 'Collapse All') ?></a>
        </div>
        <hr>
    </div>


    <div class="row-fluid log-list" style="word-wrap: break-word;">
        <?php
        $flag = false;
        foreach ($log as $l):
            if ($this->filterLog($l)):
                $status = $this->showStatus($l);
                ?>
                <div class="line <?= ($flag = !$flag) ? 'odd' : '' ?> <?php echo $status['status'] ?>-line">
                    <span class="label label-info"><?php echo $this->showDate($l); ?></span>                    
                    <span class="label <?php echo $status['class'] ?>"><?php echo $status['status']; ?></span>
                    <a href="#" class="stack-btn"><span class="label label-inverse"><?php echo Yii::t('LogAnalyzerWidget.main', 'Show') ?> Stack trace</span></a>

                    <pre><?php echo $this->showError($l); ?></pre>
                    <pre class="stack-pre" style="display:none;"><?php echo $this->showStack($l); ?></pre>
                </div>
                <?php
            endif;
        endforeach;
        ?>
    </div>
</div>
<?php Yii::app()->tpl->closeWidget(); ?>