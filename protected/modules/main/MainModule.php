<?php

class MainModule extends WebModule {

    public function init() {
        $this->setIcon('flaticon-home');
    }

    public function getRules() {
        return array(
            'layout/<layout:(demo-blocks-layout|ui)>' => 'main/index/test',
        );
    }


}
