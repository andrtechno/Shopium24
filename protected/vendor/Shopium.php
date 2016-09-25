<?php
Yii::import('app.CMS');
class Shopium extends CMS {

    public static function files_size($size) {
        $name = array("MB", "GB", "TB", "PB", "EB", "ZB", "YB");
        $mysize = $size ? round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . " " . $name[$i] : $size . " Bytes";
        return $mysize;
    }

}
