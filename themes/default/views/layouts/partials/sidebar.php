<!-- ============================================== SIDEBAR ============================================== -->	
<div class="col-xs-12 col-sm-12 col-md-3 sidebar">

    <?php
    $this->widget('mod.shop.widgets.categories.CategoriesWidget', array(
        'htmlOptions' => array('class' => 'nav'),
        'totalCount' => false,
        'itemOptions' => array('class' => 'gagag'),
        'submenuHtmlOptions' => array('class' => 'dropdown-menu mega-menu')
    ));
    ?>



    <!-- ============================================== SPECIAL DEALS ============================================== -->

    <div class="sidebar-widget outer-bottom-small wow fadeInUp" style="display: none">
        <h3 class="section-title">Special Deals</h3>
        <div class="sidebar-widget-body outer-top-xs">
            <div class="owl-carousel sidebar-carousel special-offer custom-carousel owl-theme outer-top-xs">

                <div class="item">
                    <div class="products special-product">
                        <?php
                        $model = ShopProduct::model()->labelStatus(1)->findAll();
                        $i = 0;
                        foreach ($model as $data) {
                            $i++;
                            ?>
                            <div class="product">
                                <div class="product-micro">
                                    <div class="row product-micro-row">
                                        <div class="col col-xs-5">
                                            <div class="product-image">
                                                <div class="image">

                                                    <?php
                                                    if ($data->mainImage) {
                                                        $imgSource = $data->mainImage->getUrl('100x120');
                                                    } else {
                                                        $imgSource = 'http://placehold.it/100x120';
                                                    }
                                                    echo Html::link(Html::image($imgSource, $data->mainImageTitle, array('data-echo' => $imgSource)) . '<div class="zoom-overlay"></div>', array('product/view', 'seo_alias' => $data->seo_alias), array('class' => 'thumbnail2'));
                                                    ?>

                                                </div><!-- /.image -->
                                                <div class="tag tag-micro hot">
                                                    <span>hot</span>
                                                </div>
                                            </div><!-- /.product-image -->
                                        </div><!-- /.col -->
                                        <div class="col col-xs-7">
                                            <div class="product-info">
                                                <h3 class="name">
                                                    <?= Html::link(Html::encode($data->name), array('product/view', 'seo_alias' => $data->seo_alias)) ?>
                                                </h3>
                                                <div class="rating rateit-small"></div>
                                                <div class="product-price">	
                                                    <span class="price"><?php echo $data->priceRange() ?> <?= Yii::app()->currency->active->symbol ?></span>
                                                </div>


                                                <div class="action">
                                                    <?php
                                                    echo Html::form(array('/cart/add'), 'post', array('id' => 'form-add-cart-spec-' . $data->id));
                                                    echo Html::hiddenField('product_id', $data->id);
                                                    echo Html::hiddenField('product_price', $data->price);
                                                    echo Html::hiddenField('use_configurations', $data->use_configurations);
                                                    echo Html::hiddenField('currency_rate', Yii::app()->currency->active->rate);
                                                    echo Html::hiddenField('currency_id', $data->currency_id);
                                                    echo Html::hiddenField('supplier_id', $data->supplier_id);
                                                    echo Html::hiddenField('pcs', $data->pcs);
                                                    echo Html::hiddenField('configurable_id', 0);
                                                    ?>
                                                    <?php
                                                    if ($data->isAvailable) {
                                                        ?>

                                                        <?php
                                                        echo Html::link(Yii::t('CartModule.default', 'BUY'), 'javascript:cart.add("#form-add-cart-spec-' . $data->id . '")', array('class' => 'btn btn-primary'));
                                                    } else {
                                                        echo Html::link(Yii::t('CartModule.default', 'NOT_AVAILABLE'), 'javascript:cart.notifier(' . $data->id . ');', array('class' => 'btn btn-link'));
                                                    }
                                                    ?>

                                                    <?php echo Html::endForm(); ?>
                                                </div>
                                            </div>
                                        </div><!-- /.col -->
                                    </div><!-- /.product-micro-row -->
                                </div><!-- /.product-micro -->

                            </div>
                            <?php
                            if ($i % 3 == 0) {
                                echo '</div></div><div class="item">
                    <div class="products special-product">';
                            }
                        }
                        ?>
                    </div></div>
                <!-- /TESTTTTTTTTTTTTTTTTT -->

            </div>
        </div><!-- /.sidebar-widget-body -->
    </div><!-- /.sidebar-widget -->
    <!-- ============================================== SPECIAL DEALS : END ============================================== -->

    <!-- ============================================== HOT DEALS ============================================== -->
    <div class="sidebar-widget hot-deals wow fadeInUp">
        <h3 class="section-title">Горячие предложения</h3>
        <div class="owl-carousel sidebar-carousel custom-carousel owl-theme outer-top-xs">
            <?php
            $model = ShopProduct::model()->labelStatus(2)->findAll();
            if (isset($model)) {
                foreach ($model as $data) {
                    ?>
                    <div class="item">
                        <div class="products">
                            <div class="hot-deal-wrapper">
                                <div class="image">
                                    <?php
                                    if ($data->mainImage) {
                                        $imgSource = $data->mainImage->getUrl('270x334');
                                    } else {
                                        $imgSource = 'http://placehold.it/270x334';
                                    }
                                    echo Html::image($imgSource, $data->mainImageTitle, array());
                                    ?>
                                </div>
                                <div class="sale-offer-tag"><span>hot</span></div>
                            </div><!-- /.hot-deal-wrapper -->
                            <div class="product-info text-left m-t-20">
                                <h3 class="name">
                                    <?php echo Html::link(Html::encode($data->name), array('product/view', 'seo_alias' => $data->seo_alias)) ?>
                                </h3>

                                <div class="product-price">	
                                    <span class="price"><?php echo $data->priceRange() ?> <?= Yii::app()->currency->active->symbol ?></span>
                                    <?php
                                    if (Yii::app()->hasModule('discounts')) {
                                        if ($data->appliedDiscount) {
                                            ?>
                                            <span class="price-before-discount"><?= $data->toCurrentCurrency('originalPrice') ?> <?= Yii::app()->currency->active->symbol ?></span>
                                            <?php
                                        }
                                    }
                                    ?>				
                                </div><!-- /.product-price -->
                            </div><!-- /.product-info -->
                            <div class="cart clearfix animate-effect">
                                <div class="action">
                                    <?php
                                    echo Html::form(array('/cart/add'), 'post', array('id' => 'form-add-cart-deals-' . $data->id));
                                    echo Html::hiddenField('product_id', $data->id);
                                    echo Html::hiddenField('product_price', $data->price);
                                    echo Html::hiddenField('use_configurations', $data->use_configurations);
                                    echo Html::hiddenField('currency_rate', Yii::app()->currency->active->rate);
                                    echo Html::hiddenField('currency_id', $data->currency_id);
                                    echo Html::hiddenField('supplier_id', $data->supplier_id);
                                    echo Html::hiddenField('pcs', $data->pcs);
                                    echo Html::hiddenField('configurable_id', 0);
                                    ?>
                                    <div class="add-cart-button btn-group">
                                        <?php
                                        if ($data->isAvailable) {
                                            ?>
                                            <span class="btn btn-primary icon" onClick="javascript:cart.add('#form-add-cart-deals-<?= $data->id ?>')">
                                                <i class="fa fa-shopping-cart"></i>													
                                            </span>             
                                            <?php
                                            echo Html::link(Yii::t('CartModule.default', 'BUY'), 'javascript:cart.add("#form-add-cart-deals-' . $data->id . '")', array('class' => 'btn btn-primary'));
                                        } else {
                                            echo Html::link(Yii::t('CartModule.default', 'NOT_AVAILABLE'), 'javascript:cart.notifier(' . $data->id . ');', array('class' => 'btn btn-link'));
                                        }
                                        ?>
                                    </div>
                                    <?php echo Html::endForm(); ?>
                                </div><!-- /.action -->

                            </div><!-- /.cart -->
                        </div>	
                    </div>	           
                <?php }
            } ?>



        </div><!-- /.sidebar-widget -->
    </div>
    <!-- ============================================== HOT DEALS: END ============================================== -->
    <div class="sidebar-widget  wow fadeInUp outer-top-vs ">
        <div id="advertisement" class="advertisement">

            <?php
            $files = CFileHelper::findFiles(Yii::getPathOfAlias('webroot.bnr.left'), array(
                        'fileTypes' => array('jpg', 'png', 'jpeg'),
                        'absolutePaths' => false,
                        'exclude' => array('.txt')
                    ));
            foreach ($files as $file) {
                ?>
                <div class="item" style="background-image: url(/bnr/left/<?= $file ?>);"></div>  
<?php } ?>


        </div><!-- /.owl-carousel -->
    </div>


</div><!-- /.sidemenu-holder -->
<!-- ============================================== SIDEBAR : END ============================================== -->
