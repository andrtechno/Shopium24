<?php

class AdminModuleMenu extends CMenu {

    protected function renderMenuItem($item) {
        if (isset($item['url'])) {
            $icon = isset($item['icon']) ? '<i class="' . $item['icon'] . '"></i>' : '';

      //   $count = isset($item['count']) ? '<i class="'.((isset($item['countClass']))? $item['countClass'] :'badge').'">' . $item['count'] . '</i>' : '<span class="badge">13</span>';
            $label = $this->linkLabelWrapper === null ? $item['label'] : Html::tag($this->linkLabelWrapper, $this->linkLabelWrapperHtmlOptions, $item['label']);
            return Html::link($icon . '<span>'.$label .'</span>'.$count, $item['url'], isset($item['linkOptions']) ? $item['linkOptions'] : array());
        }
        else
            return Html::tag('span', isset($item['linkOptions']) ? $item['linkOptions'] : array(), $item['label']);
    }

}