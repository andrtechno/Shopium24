<div id="topcontrol">
    <?php if($this->enableTop){ ?><div class="backtotop" <?php echo ( ($this->opacity >0 && $this->opacity <= 1) ? '' : 'style="display:none;"'); ?>></div><?php } ?>
    <?php if($this->enableBottom){ ?><div class="backtobottom" <?php echo ( ($this->opacity >0 && $this->opacity <= 1) ? '' : 'style="display:none;"'); ?>></div><?php } ?>
</div>
    <?php
Yii::app()->clientScript->registerScript('topBottomScroll-js', "

    $(document).ready(function() {
        " . ( ($this->opacity >0 && $this->opacity <= 1)?"fadeOutTo(); showFadeOnMouseScrollButtons()":"$(window).scroll(function(){showHideScrollButtons()});" ) . "
    });
    
    " . ( ($this->opacity >0 && $this->opacity <= 1)?"":"showHideScrollButtons();" ) . "

    " . ( ($this->opacity >0 && $this->opacity <= 1)?"
        function fadeOutTo(){
            $('.backtotop').fadeTo(" . $this->fadeInTime . ", " . $this->opacity . ");
            $('.backtobottom').fadeTo(" . $this->fadeInTime . ", " . $this->opacity . ");
        }

        function showFadeOnMouseScrollButtons(){
            $('.backtotop').mouseover(function(){
                $('.backtotop').fadeTo(" . $this->fadeInTime . ", 1);
            });
            $('.backtotop').mouseout(function(){
                $('.backtotop').fadeTo(" . $this->fadeInTime . ", " . $this->opacity . ");
            });

            $('.backtobottom').mouseover(function(){
                $('.backtobottom').fadeTo(" . $this->fadeInTime . ", 1);
            });
            $('.backtobottom').mouseout(function(){
                $('.backtobottom').fadeTo(" . $this->fadeInTime . ", " . $this->opacity . ");
            });
        } 
    ":"
        function showHideScrollButtons(){
            if ($(this).scrollTop() > " . $this->minHeight . ") {
                $('.backtotop').fadeIn(" . $this->fadeInTime . ");
            } else {
                $('.backtotop').fadeOut(" . $this->fadeOutTime . ");
            }

            if (($(document).height() - $(this).scrollTop()) > " . $this->minDepth . ") {
                $('.backtobottom').fadeIn(" . $this->fadeInTime . ");
            } else {
                $('.backtobottom').fadeOut(" . $this->fadeOutTime . ");
            }
        }
    " ) . "

    
    $('.backtotop').click(function() {
        $(\"html, body\").animate({ scrollTop: 0 }, " . $this->scrollTopTime . ");
        return false;
    });

    $('.backtobottom').click(function() {
        $(\"html, body\").animate({ scrollTop: $(document).height() },  " . $this->scrollBottomTime . ");
        return false;
    });

");

Yii::app()->clientScript->registerCss('topBottomScroll-css', " 
    .hide {display:none;}
    
    #topcontrol{
        position: fixed;
        bottom: 5px;
        right: 5px;
        opacity: 1;
        cursor: pointer;
        z-index:1001;
    }

    .backtotop {
        background: url(" . $this->assetsUrl . "/images/icon-up.png) no-repeat;
        width: 68px;
        height: 68px;
    }

    .backtobottom {
        background: url(" . $this->assetsUrl . "/images/icon-down.png) no-repeat;
        width: 68px;
        height: 68px;
    }
");

