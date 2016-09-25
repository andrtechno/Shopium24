

<script>
    $(document).ready(function () {
        $('.navbar-brand span').hover(function (e) {
            e.preventDefault();
            $(this).removeClass('swing');
        }, function () {
            $(this).addClass('swing');
        });
    });
</script>
<nav class="navbar navbar-static-top bg-white">
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-8 col-xs-12">
                    <div class="navbar-header">

                        <button type="button" class="navbar-toggle collapsed btn btn-info" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <i class="flaticon-menu"></i>
                        </button>
                        <?php if (Yii::app()->user->isGuest) { ?>
                            <a href="/users/register" class="btn btn-success btn-small-nav hidden-sm hidden-md hidden-lg"><i class="flaticon-plus"></i> Создать магазин</a>
                        <?php } else { ?>

                        <?php } ?>
                        <a class="navbar-brand" href="/">
                            <?= Yii::app()->settings->get('app', 'site_name') ?>
                            <span class="animated swing"></span>
                        </a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">

                        <ul class="nav navbar-nav">
                            <li><a href="/html/plans">Цены</a></li>
                            <li><a class="dropdown-toggle" href="#" id="drop-service" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Услуги <span class="caret"></span></a>
                                <ul class="dropdown-menu" aria-labelledby="drop-service">
                                    <li><?= Html::link('Домены', array('/html/domain')) ?></li>
                                </ul>
                            </li>
                            <li style="display:none;"><a href="#">Выкуп магазина</a></li>

                            <li><?= Html::link('Контакты', array('/contacts')) ?></li>
                            <?php $this->widget('mod.users.widgets.login.LoginWidget'); ?>
                        </ul>
                    </div>


                </div>

                <div class="col-lg-3 col-md-4 col-xs-12 hidden-xs">
                    <?php if (Yii::app()->user->isGuest) { ?>
                        <a href="/users/register" class="btn btn-lg btn-success"><i class="fa fa-plus fa-lg"></i> Создать магазин</a>
                    <?php } else { ?>
                        <div class="pull-right header-contacts">
                            <div><i class="flaticon-phone-call"></i> <span>+38 063 390 71 36</span></div>
                            <div><i class="flaticon-envelope-open"></i> <span>support@<?= Yii::app()->request->serverName ?></span></div>
                        </div>
                    <?php } ?>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</nav>