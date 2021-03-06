
<script>
    $(function () {
        $('.hint_popup').popover({trigger: "hover",html:true});
    });

                        $(document).ready(function () {
                            new WOW().init();
                        });
          
</script>
<?php
$this->pageName = 'Тарифы и цены';
$plans = Plans::model()->findAll();
$options = PlansOptions::model()->findAll(array('order' => 'ordern DESC'));
?>
<div class="table-responsive wow animated fadeInUp">
    <div class="membership-pricing-table">
        <table style="width:100%">
            <tbody>
                <tr>
                    <th width="40%">
                        <div class="text-center">
                            <h3 class="text-uppercase">Тарифы и цены</h3>
                        </div>
                    </th>

                    <?php
                    foreach ($plans as $key => $plan) {
                        if ($plan->name == 'Basic') {
                            $class = 'plan-header-default';
                        } elseif ($plan->name == 'Standard') {
                            $class = 'plan-header-standard';
                        } else {
                            $class = 'plan-header-blue';
                        }
                        if ($plan->name == 'Standard') {
                            $htlp = 'header-plan-inner';
                            $recommendHtml = '<span class="recommended-plan-ribbon">поплулярный</span>';
                        } else {
                            $recommendHtml = '';
                            $htlp = '';
                        }
                        ?>
                        <th class="plan-header <?= $class ?>" width="20%">
                            <div class="<?= $htlp ?>">
                                <?= $recommendHtml ?>
                                <div class="pricing-plan-name"><?= $plan->name ?></div>
                                <div class="pricing-plan-price">

                                    <?= $plan->price_month ?><span>грн.</span>
                                </div>
                                <div class="pricing-plan-period">месяц</div>
                            </div>
                        </th>

                    <?php } ?>


                    <th class="plan-header plan-header-standard" style="display:none">
                        <div class="header-plan-inner">
                            <!--<span class="plan-head"> </span>-->
                            <span class="recommended-plan-ribbon">поплулярный</span>
                            <div class="pricing-plan-name">Standard</div>
                            <div class="pricing-plan-price">
                                300<span>грн.</span>
                            </div>
                            <div class="pricing-plan-period">месяц</div>
                        </div>
                    </th>
                    <th class="plan-header plan-header-blue" style="display:none">
                        <div class="pricing-plan-name">Premium</div>
                        <div class="pricing-plan-price">
                            600<span>грн.</span>
                        </div>
                        <div class="pricing-plan-period">месяц</div>
                    </th>
                </tr>


                <tr>
                    <td>При оплате за пол года:</td>
                    <?php for ($x = 0; $x < 3; $x++) { ?>
                        <td  class="text-center"><?= $plans[$x]->price_6month ?> грн/мес.</td>
                    <?php } ?>
                </tr>
                <tr>
                    <td>При оплате за год:</td>
                    <?php for ($x = 0; $x < 3; $x++) { ?>
                        <td class="text-center"><?= $plans[$x]->price_year ?> грн/мес.</td>
                    <?php } ?>
                </tr>

                <tr style="display:none;">
                    <td></td>
                    <td colspan="4" class="text-center" style="background-color:#fff;">Возможности</td>
                </tr>

                <tr>
                    <td>
                        <?php if (Yii::app()->user->isGuest) { ?>
                            <b>Бесплатно 14 дней</b>
                        <?php } ?>
                    </td>
                    <?php foreach ($plans as $plan) { ?>
                        <?php
                        $btnClass  = ($plan->name == 'Standard')?'btn-warning':'btn-info';
                        ?>
                        <?php if (Yii::app()->user->isGuest) { ?>
                            <td class="action-header"><?= Html::link('Попробовать', array('/users/register', 'User[plan]' => $plan->name), array('class' => 'btn '.$btnClass)) ?></td>
                        <?php } else { ?>
                            <?php if($plan->name == Yii::app()->user->plan){ ?>
                            <td class="action-header">
                                <div class="current-plan">
                                    <div class="with-date">Текущий тариф</div>
                                    <div><em class="smaller block">---</em></div>
                                </div> 
                            </td>
                            <?php }else{ ?>
                            <td class="action-header"><?= Html::link('Перейти', array('/users/replan', 'User[plan]' => $plan->name), array('class' => 'btn '.$btnClass)) ?></td>
                            <?php } ?>
                            
                        <?php } ?>

                    <?php } ?>




                </tr>

                <tr>
                    <td></td>
                    <td colspan="3" class="text-center" style="background-color:#fff;"><b>Возможности</b></td>
                </tr>

                <?php foreach ($options as $k => $opt) { $animClass = ( $k%2 )?'1bounceInLeft': '1bounceInRight';  ?>
                    <tr class="1wow 1animated <?=$animClass?>">
                        <td><?= $opt->name; ?> 
                            <?php if (!empty($opt->hint)) { ?>
                                &nbsp;<i class="flaticon-info-circle pull-right hint_popup text-info" data-toggle="hover" data-placement="right" data-trigger="hover" data-html="true" title="<?= $opt->name; ?>" data-content="<?= $opt->hint ?>"></i>
                            <?php } ?></td>
                        <?php foreach ($opt->rels as $kk => $rels) { ?>
                            <td class="text-center <?= ($kk == 1) ? 'bg-warning2' : '' ?>">
                                <?php
                                if (strlen($rels->value) < 2) {
                                    $value = ($rels->value == 1) ? '<i class="flaticon-check text-success"></i>' : '<i class="flaticon-delete text-danger"></i>';
                                } else {
                                    $value = $rels->value;
                                }
                                ?>
                                <?= $value; ?></td>
                        <?php } ?>
                    </tr> 
                <?php } ?>
<?php if (Yii::app()->user->isGuest) { ?>
                <tr>
                    <td><b>Бесплатно 14 дней</b></td>
                    <?php foreach ($plans as $plan) { ?>
                        <td class="text-center"><?= Html::link('Попробовать', array('/users/register', 'User[plan]' => $plan->name), array('class' => 'btn btn-info')) ?> </td>
                    <?php } ?>
                </tr> 
  <?php } ?>
            </tbody>
        </table>
    </div>
</div>
