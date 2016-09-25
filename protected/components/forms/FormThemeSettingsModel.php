<?php

/**
 * @package components
 * @uses FormModel
 */
class FormThemeSettingsModel extends FormModel {

    protected $_attrLabels = array();


    
    public function uploadFile($attr, $old_image = null) {
        $file = CUploadedFile::getInstance($this, $attr);
        $path = Yii::getPathOfAlias('webroot.uploads') . DS;
        //TODO добавить проверку на наличие папки.
        if (isset($file)) {
            if ($old_image && file_exists($path . $old_image))
                unlink($path . $old_image);
            $newname = "logo." . $file->extensionName;
            if (in_array($file->extensionName, array('jpg', 'jpeg', 'png', 'gif'))) { //Загрузка для изображений
                $img = Yii::app()->img;
                $img->load($file->tempName);
                $img->save($path . $newname);
            } else {

                $this->addError($attr,'Error format');
            }

            $this->$attr = (string) $newname;
        } else {

            $this->$attr = (string) $old_image;
        }
    }
    public function __construct($scenario = '') {
        parent::__construct($scenario);
    }

    public function init() {
        $this->attributes = Yii::app()->theme->get(Yii::app()->theme->name);
    }


    public function save($message = true) {
        Yii::app()->theme->set(Yii::app()->theme->name, $this->attributes);
        parent::save($message);
    }

}
