
<script>
    $(function () {
        $("#owl-slider-gal").owlCarousel({
            navigation: true, // Show next and prev buttons
            slideSpeed: 300,
            paginationSpeed: 400,
            singleItem: true,
            autoPlay: true,
            slideSpeed: 1000
        });



    });
</script>

<div id="owl-slider-gal" class="owl-carousel owl-theme">

    <div class="item"><?= Html::image($this->assetsUrl . '/images/banner-standard.jpg', '', array('class' => 'img-responsive')) ?></div>
    <div class="item"><?= Html::image($this->assetsUrl . '/images/banner-basic.jpg', '', array('class' => 'img-responsive')) ?></div>


</div>