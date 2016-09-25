<?php

class Html extends CHtml {

    public static function activeLabelEx($model, $attribute, $htmlOptions = array()) {
        $realAttribute = $attribute;
        self::resolveName($model, $attribute); // strip off square brackets if any
        $htmlOptions['required'] = $model->isAttributeRequired($attribute);
        return self::activeLabel($model, $realAttribute, $htmlOptions);
    }

    public static function activeLabel($model, $attribute, $htmlOptions = array()) {
        $inputName = self::resolveName($model, $attribute);
        if (isset($htmlOptions['for'])) {
            $for = $htmlOptions['for'];
            unset($htmlOptions['for']);
        } else
            $for = self::getIdByName($inputName);
        if (isset($htmlOptions['label'])) {
            if (($label = $htmlOptions['label']) === false)
                return '';
            unset($htmlOptions['label']);
        } else
            $label = $model->getAttributeLabel($attribute);
        if ($model->hasErrors($attribute))
            self::addErrorCss($htmlOptions);
        return self::label($label, $for, $htmlOptions);
    }

    /**
     * HTML and word filter
     * 
     * @param string $message
     * @param boolean $cut Обрезать текст. true|false
     * @return string
     */
    public static function text($message, $cut = false) {
        $config = Yii::app()->settings->get('app');
        //if (!$mode)
        //  $message = strip_tags(urldecode($message));
        //$message = htmlspecialchars(trim($message), ENT_QUOTES);
        // $message=html_entity_decode(htmlentities($message));
        if ($config['censor']) {
            $censor_l = explode(",", $config['censor_array']);
            foreach ($censor_l as $val)
                $message = preg_replace("#" . $val . "#iu", $config['censor_replace'], $message);
        }

        return self::highlight($message, $cut);
    }

    /**
     * 
     * @param string $text
     * @param boolean $cut
     * @return string
     */
    public static function highlight($text, $cut = false) {
        $params = (Yii::app()->request->getParam('word')) ? Yii::app()->request->getParam('word') : Yii::app()->request->getParam('tag');
        if ($params) {
            if ($cut) {
                $pos = max(mb_stripos($text, $params, null, Yii::app()->charset) - 100, 0);
                $fragment = mb_substr($text, $pos, 200, Yii::app()->charset);
            } else {
                $fragment = html_entity_decode(htmlentities($text));
            }
            if (is_array($params)) {
                foreach ($params as $k => $w) {
                    $fragment = str_replace($w, '<span class="highlight-word">' . $w . '</span>', $fragment);
                }
                $highlighted = $fragment;
            } else {
                $highlighted = str_replace($params, '<span class="highlight-word">' . $params . '</span>', $fragment);
            }
        } else {
            $highlighted = $text;
        }
        return $highlighted;
    }

}
