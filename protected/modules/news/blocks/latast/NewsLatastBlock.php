<?php

Yii::import('mod.news.models.News');

class NewsLatastBlock extends BlockWidget {

   // public $alias = 'mod.news.blocks.latast';

    public function getTitle() {
        return Yii::t('default', 'Новости');
    }

    public function run() {
       // if (Yii::app()->getModule('news')) {
            $criteria = new CDbCriteria;
            $criteria->with = array('user', 'translate', 'category');
            $criteria->limit = $this->config['num'];
            $provider = new ActiveDataProvider('News', array(
                'criteria' => $criteria,
                'sort' => News::getCSort(),
                'pagination' => array('pageSize' => $this->config['num']),
            ));
            $this->render($this->skin, array(
                'provider' => $provider,
            ));
       // }
    }

}
