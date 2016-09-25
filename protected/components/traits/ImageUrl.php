<?php

trait ImageUrl {

    /**
     * Get url to product image. Enter $size to resize image.
     * 
     * @param string $attr Model attribute
     * @param string|false $size New size of the image. e.g. '150x150'
     * @param string $dir Folder name in uploads
     * @param string $resize resize or adaptiveResize
     * @return string
     */
    public function getImageUrl($attr, $dir, $size = false, $resize = 'resize') {
        Yii::import('ext.phpthumb.PhpThumbFactory');
        $attrname = $this->$attr;
        if (!empty($attrname)) {
            if ($size !== false) {
                return CMS::resizeImage($size, $attrname, $dir, $dir,$resize);
            }
        } else {
            return false;
        }
    }

}
