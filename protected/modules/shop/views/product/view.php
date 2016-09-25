<?php
Yii::import('mod.cart.CartModule');
CartModule::registerAssets();
?>

        <div class='col-sm-6 col-md-7 product-info-block'>
            <div class="product-info">
                <h1 class="name"><?php echo Html::encode($model->name); ?></h1>


                <div class="description-container m-t-20">
                    <?= $model->short_description; ?>
                </div><!-- /.description-container -->

                <div class="price-container info-container m-t-20">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="price-box">
                                <span class="price"><span id="productPrice"><?= ShopProduct::formatPrice($model->toCurrentCurrency()); ?></span> <?= Yii::app()->currency->active->symbol; ?></span>
                                <?php
                                if (Yii::app()->hasModule('discounts')) {
                                    if ($model->appliedDiscount) {
                                        echo '<span class="price-strike">' . $model->toCurrentCurrency('originalPrice') . '</span>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.price-container -->

                <div class="quantity-container info-container">
                    <div class="row">
                        <div class="col-sm-8">
                            <?php
                            if (Yii::app()->hasModule('cart')) {
                                Yii::import('mod.cart.CartModule');
                                echo Html::form(array('/cart/add'), 'post', array(
                                    //'class' => 'product-form2',
                                    'id' => 'form-add-cart-' . $model->id
                                ));
                                echo Html::hiddenField('product_id', $model->id);
                                echo Html::hiddenField('product_price', $model->price);
                                echo Html::hiddenField('currency_rate', Yii::app()->currency->active->rate);
                                echo Html::hiddenField('currency_id', $model->currency_id);

                                echo Html::hiddenField('pcs', $model->pcs);

                                echo Html::textField('quantity', 1, array('class' => 'spinner btn-group form-control'));

                                if ($model->isAvailable) {
                                    echo Html::link('<i class="fa fa-shopping-cart"></i>' . Yii::t('CartModule.default', 'BUY'), 'javascript:cart.add("#form-add-cart-' . $model->id . '")', array('class' => 'btn btn-primary'));
                                } else {
                                    echo Html::link(Yii::t('CartModule.default', 'NOT_AVAILABLE'), 'javascript:cart.notifier(' . $model->id . ');');
                                }
                                echo Html::endForm();
                            }
                            ?>
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.quantity-container -->





            </div><!-- /.product-info -->
        </div><!-- /.col-sm-7 -->

    <div class="product-tabs inner-bottom-xs  wow fadeInUp">
        <div class="row">

            <div class="col-sm-9">
                <div class="tab-content">
                    <div id="description" class="tab-pane in active">
                        <div class="product-tab">
                            <?= $model->full_description; ?>
                        </div>	
                    </div>

                </div><!-- /.tab-content -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.product-tabs -->



