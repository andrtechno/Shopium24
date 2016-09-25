
<div class="widget fluid">
    <div class="whead"><h6><?php echo $this->pageName ?></h6><div class="clear"></div></div>

    
            <ul class="messagesOne">
                
                
                <?php foreach($pmessages as $pm){ ?>
                <?php $class = ($pm->from_user==Yii::app()->user->id)?'by_me':'by_user';?>
                <li class="<?php echo $class?>">

                    <a href="#" title=""><img src="<?php echo ($pm->from_user==Yii::app()->user->id)?$pm->receiver->avatarPath:$pm->sender->avatarPath;?>" alt="" /></a>
                    <div class="messageArea">
                        <span class="aro"></span>
                        <div class="infoRow">
                            <span class="name"><strong><?php echo ($pm->from_user==Yii::app()->user->id)?$pm->receiver->username:$pm->sender->username;?></strong> сказал:</span>
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
    
    
    
    
    <div class="formRow">
    <?php $form = $this->beginWidget('CActiveForm'); ?>
    <?php echo $form->errorSummary($model); ?>
    <?php echo CHtml::hiddenField('to_user', $_GET['to_user']) ?>
    <div class="enterMessage">
        <?php echo $form->textField($model, 'text', array('placeholder' => 'Enter your message...')) ?>

        <div class="sendBtn">
            <a class="attachPhoto" title="" href="#"></a>
            <a class="attachLink" title="" href="#"></a>

            <?php echo CHtml::submitButton('send', array('class' => 'buttonS bLightBlue')); ?>
        </div>
    </div>





    <?php $this->endWidget(); ?>
        </div>
</div>

