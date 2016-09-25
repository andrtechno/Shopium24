<?php

/**
 * @version 1.0
 * @author Andrew S. <andrew.panix@gmail.com>
 * @name $attributes Array attributes model
 */
class UploadfileBehavior extends CActiveRecordBehavior {

    public $attributes = array();
    protected $oldUploadFiles = array();

    public function attach($owner) {
        return parent::attach($owner);
    }

    public function beforeSave($event) {
        $owner = $this->getOwner();
        foreach ($this->attributes as $attr => $path) {
            if (isset($owner->$attr)) {
                $owner->uploadFile($attr, $path, (isset($this->oldUploadFiles[$attr])) ? $this->oldUploadFiles[$attr] : null);
            }
        }
        return true;
    }

    public function afterFind($event) {
        $owner = $this->getOwner();

        if ($owner->scenario == 'update') {
            foreach ($this->attributes as $attr => $path) {
                if (isset($owner->$attr)) {
                    $this->oldUploadFiles[$attr] = $owner->$attr;
                }
            }
        }
    }

    public function getImageUrl($attr, $dir, $size = false, $resize = 'resize') {
        $owner = $this->getOwner();
        Yii::import('ext.phpthumb.PhpThumbFactory');
        $attrname = $owner->$attr;
        if (!empty($attrname)) {
            if ($size !== false) {
                return CMS::resizeImage($size, $attrname, $dir, $dir, $resize);
            }
        } else {
            return false;
        }
    }

    public function getFileUrl($attr) {
        $owner = $this->getOwner();
        if (array_key_exists($attr, $this->attributes)) {
            if ($owner->$attr && file_exists(Yii::getPathOfAlias($this->attributes[$attr]) . DS . $owner->$attr)) {
                return $this->getAbsoluteFilePath($attr);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private function getAbsoluteFilePath($attr) {
        $replace = str_replace('webroot', "", $this->attributes[$attr]);
        $path = str_replace('.', "/", $replace);
        return $path . '/' . $this->getOwner()->$attr;
    }

}
