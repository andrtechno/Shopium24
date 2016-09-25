
<div class="widget fluid">
    <div class="whead"><h6><?php echo $this->pageName?></h6><div class="clear"></div></div>
 
            <ul class="messagesOne">
                
                
                <?php foreach($model as $pm){ ?>
                <?php $class = ($pm->from_user==1)?'by_me':'by_user';?>
                <li class="<?php echo $class?>">
                    <a href="#" title=""><img src="<?php echo $pm->userTo->avatar?>" alt="" /></a>
                    <div class="messageArea">
                        <span class="aro"></span>
                        <div class="infoRow">
                            <span class="name"><strong><?php echo $pm->userTo->login?></strong> сказал:</span>
                            <span class="time"><?= CMS::date($pm->date_create)?></span>
                            <div class="clear"></div>
                        </div>
                        <?php echo $pm->text ?>
                    </div>
                    <div class="clear"></div>
                </li>
            
                <li class="divider"><span></span></li>

                <?php } ?>
            </ul>
    
</div>

