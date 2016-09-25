<footer class="footer">
    <div class="container">
        <div class="row wow animated fadeIn">
            <div class="col-lg-4">
                <?=
                Yii::t('default', 'COPYRIGHT', array(
                    '{year}' => date('Y'),
                    '{site_name}' => Yii::app()->settings->get('app', 'site_name')
                ))
                ?>
                <br><br>
                <div class="made-in-ukraine">
                    Made<br/>
                    In<br/>
                    Ukraine
                </div>
            </div>
            <div class="col-lg-4">
                <ul class="list-group">
                    <li class="list-group-item"><a href="/html/plans">Цены</a></li>
                    <li class="list-group-item"><a href="/html/domain">Домены</a></li>
                    <li class="list-group-item"><a href="/html/terms">Условия использования</a></li>
                </ul>
            </div>
            <div class="col-lg-4">
                <ul class="list-group">
                    <li class="list-group-item"><i class="flaticon-phone-call text-md"></i> <span class="text-md">+38 063 390 71 36</span></li>
                    <li class="list-group-item"><i class="flaticon-email text-md"></i> <span class="text-md">support@<?=Yii::app()->request->serverName?></span></li>

                </ul>
                {copyright}
            </div>
        </div>
    </div>
</footer>

<?php if (!YII_DEBUG) { ?>
    <?php $this->widget('ext.Siteheart'); ?>
    <?php $this->widget('ext.YandexMetrika'); ?>
<?php } ?>