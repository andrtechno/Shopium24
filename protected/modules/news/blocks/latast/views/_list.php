<div class="list-group-item" style="border:0;padding:0;">
    <h4 class="list-group-item-heading"><?php echo Html::link(CMS::truncate(Html::text($data->title),$this->config['truncate_title']), $data->getUrl()); ?></h4>
    <div class="list-group-item-text"><?php echo CMS::truncate(Html::text($data->short_text),$this->config['truncate_text']); ?></div>
</div>




