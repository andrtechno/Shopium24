<?php
Yii::import('mod.seo.models.SeoUrl');
class SeoBehavior extends CActiveRecordBehavior {

    /**
     * @var string model primary key attribute
     */
    public $pk = 'id';

    /**
     * @var string attribute name to present comment owner in admin panel. e.g: name - references to Page->name
     */
    public $url;

    /**
     * @return string pk name
     */
    public function getObjectPk() {
        return $this->pk;
    }

    public function attach($owner) {
        parent::attach($owner);
    }

    public function afterSave($event) {
        $model = $this->owner;
        if ($model->isNewRecord) {
            $seo = new SeoUrl;
        } else {
            $seo = SeoUrl::model()->findByAttributes(array('url' => (string) $model->getUrl()));
            if (!$seo) {
                $seo = new SeoUrl;
            }
        }
        $seo->attributes = $_POST['SeoUrl'];
        $seo->url = (string) $model->getUrl();
        $seo->save(false, false, false);
        return true;
    }

    /**
     * @param CEvent $event
     * @return mixed
     */
    public function afterDelete($event) {
        SeoUrl::model()->deleteAllByAttributes(array(
            'url' => $this->url,
        ));

        return parent::afterDelete($event);
    }

}
