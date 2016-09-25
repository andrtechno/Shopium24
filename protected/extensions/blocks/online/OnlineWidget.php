<?php

class OnlineWidget extends BlockWidget {

    public $alias = 'ext.blocks.online';

    public function getTitle() {
        return 'Текущий онлайн';
    }

    public function init() {
        parent::init(__FILE__);
    }

    public function run() {
        $model = new Session('search');

        $this->render($this->skin, array(
            'model' => $model,
            'online' => Session::online(),
        ));
    }


}
