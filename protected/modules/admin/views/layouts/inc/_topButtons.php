<?php

if (!isset($this->topButtons)) {
    echo Html::link(Yii::t('app', 'CREATE', 0), array('create'), array('title' => Yii::t('app', 'CREATE', 0), 'class' => 'btn btn-success'));
} else {
    if ($this->topButtons == true) {
        if (is_array($this->topButtons)) {
            foreach ($this->topButtons as $button) {
                if (isset($button['icon'])) {
                    $icon = '<i class="' . $button['icon'] . '"></i> ';
                } else {
                    $icon = '';
                }
                echo Html::link($icon . $button['label'], $button['url'], $button['htmlOptions']);
            }
        }
    }
}
