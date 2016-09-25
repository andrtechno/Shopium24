<div class="blog-post wow fadeInUp">
     <?php $this->widget('ext.admin.frontControl.FrontControlWidget', array('data' => $data,'options'=>array('position'=>'right'))); ?>
    <h1>
        <?= Html::link(Html::text($data->title), $data->getUrl()) ?>
    </h1>
    <span class="author"><?= $data->user->login ?></span>
    <?php if (isset($data->commentsCount)) { ?><span class="review"><?= $data->commentsCount; ?> Комментариев</span><?php } ?>
    <span class="date-time"><?= CMS::date($data->date_create) ?></span>
    <p><?= Html::text($data->short_text); ?></p>
    <?= Html::link('read more', $data->getUrl(), array('class' => 'btn btn-upper btn-primary read-more')) ?>
</div>
<?php //$this->widget('mod.users.widgets.favorites.FavoritesWidget', array('model' => $data));  ?>

