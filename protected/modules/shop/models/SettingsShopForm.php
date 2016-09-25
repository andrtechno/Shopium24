<?php

class SettingsShopForm extends FormModel {

    protected $_mid = 'shop';
    public $auto_gen_cat_meta;
    public $auto_gen_cat_tpl_title;
    public $auto_gen_cat_tpl_keywords;
    public $auto_gen_cat_tpl_description;
    public $per_page;
    public $auto_fill_short_desc;
    public $fp_penny;
    public $fp_separator_thousandth;
    public $fp_separator_hundredth;
    public $auto_gen_url;
    public $auto_gen_meta;
    public $auto_gen_tpl_title;
    public $auto_gen_tpl_keywords;
    public $auto_gen_tpl_description;
    public $path;
    public $thumbPath;
    public $wholesale;
    public $url;
    public $thumbUrl;
    public $maxFileSize;
    public $maximum_image_size;
    public $img_preview_size_list;
    public $img_view_thumbs_size;
    public $img_view_size;
    public $watermark_image;
    public $watermark_active;
    public $watermark_corner;
    public $watermark_offsetX = 0;
    public $watermark_offsetY = 0;
    public $filter_enable_price;
    public $filter_enable_brand;
    public $filter_enable_attr;
    public $ajax_mode;

    public static function defaultSettings() {
        return array(
            'per_page' => '10,20,30',
            'fp_penny' => 2,
            'fp_separator_thousandth' => 32,
            'fp_separator_hundredth' => 46,
            'path' => 'webroot.uploads.product',
            'thumbPath' => 'webroot.assets.product',
            'url' => '/uploads/product/', // With ending slash
            'thumbUrl' => '/assets/product/', // With ending slash
            'maxFileSize' => 10485760, //10*1024*1024,
            'watermark_active' => true,
            'watermark_image' => 'watermark.png',
            'watermark_corner' => 4,
            'watermark_offsetX' => 10,
            'watermark_offsetY' => 10,
            'auto_fill_short_desc' => false,
            'auto_gen_url' => false,
            'preview_size_list' => '200x200',
            'img_preview_size_list' => '200x200',
            'img_view_thumbs_size' => '100x100',
            'img_view_size' => '240x240',
            'maximum_image_size' => '800x600',
            'wholesale' => false,
            'auto_gen_meta' => true,
            'auto_gen_tpl_keywords' => 'название продукта %PRODUCT_ARTICLE%',
            'auto_gen_tpl_description' => 'название продукта %PRODUCT_ARTICLE%',
            'auto_gen_tpl_title' => 'Купить %PRODUCT_MAIN_CATEGORY%  %PRODUCT_BRAND% %PRODUCT_ARTICLE% в Одессе оптом',
            'order_emails' => Yii::app()->settings->get('core', 'admin_email'),
            'filter_enable_price' => true,
            'filter_enable_brand' => true,
            'filter_enable_attr' => true,
            'ajax_mode' => false
        );
    }

    public function getForm() {
        Yii::import('ext.TagInput');
        $tab = new TabForm(array('id' => __CLASS__,
                    'enctype' => 'multipart/form-data',
                    'showErrorSummary' => true,
                    'elements' => array(
                        'main' => array(
                            'type' => 'form',
                            'title' => $this->t('TAB_GENERAL'),
                            'elements' => array(
                                'per_page' => array(
                                    'type' => 'text',
                                    'hint' => $this->t('HINT_PER_PAGE'),
                                ),
                                //'currency_autoconvert' => array(
                                //    'type' => 'checkbox',
                                //    'hint' => $this->t('HINT_CURRENCY_AUTOCONVERT')
                                //),
                                /*
                                 * В планах */
                                //'ajax_mode' => array(
                                //    'type' => 'checkbox',
                                //    'hint'=>$this->t('HINT_AJAX_MODE')
                                // ),
                                'auto_fill_short_desc' => array(
                                    'type' => 'checkbox',
                                ),

                                'wholesale' => array('type' => 'checkbox'),
                                'filter_enable_price' => array('type' => 'checkbox'),
                                'filter_enable_brand' => array('type' => 'checkbox'),
                                'filter_enable_attr' => array('type' => 'checkbox'),

                                'auto_gen_url' => array(
                                    'type' => 'checkbox',
                                    'hint' => $this->t('HINT_AUTO_GEN_URL')
                                ),

                            )
                        ),
                        'seo' => array(
                            'type' => 'form',
                            'title' => $this->t('TAB_SEO'),
                            'elements' => array(
                                'auto_gen_meta' => array('type' => 'checkbox'),
                                'auto_gen_tpl_title' => array(
                                    'type' => 'textarea',
                                    'hint' => $this->t('META_TPL', array(
                                        '{currency}' => Yii::app()->currency->active->symbol
                                    ))
                                ),
                                'auto_gen_tpl_keywords' => array(
                                    'type' => 'textarea',
                                    'hint' => $this->t('META_TPL', array(
                                        '{currency}' => Yii::app()->currency->active->symbol
                                    ))
                                ),
                                'auto_gen_tpl_description' => array('type' => 'textarea', 'hint' => $this->t('META_TPL', array(
                                        '{currency}' => Yii::app()->currency->active->symbol
                                    ))),
                            )
                        ),
                        'catseo' => array(
                            'type' => 'form',
                            'title' => $this->t('TAB_CAT_SEO'),
                            'elements' => array(
                                'auto_gen_cat_meta' => array('type' => 'checkbox'),
                                'auto_gen_cat_tpl_title' => array(
                                    'type' => 'textarea',
                                    'hint' => $this->t('META_CAT_TPL', array(
                                        '{currency}' => Yii::app()->currency->active->symbol
                                    ))
                                ),
                                'auto_gen_cat_tpl_keywords' => array(
                                    'type' => 'textarea',
                                    'hint' => $this->t('META_CAT_TPL', array(
                                        '{currency}' => Yii::app()->currency->active->symbol
                                    ))
                                ),
                                'auto_gen_cat_tpl_description' => array('type' => 'textarea', 'hint' => $this->t('META_CAT_TPL', array(
                                        '{currency}' => Yii::app()->currency->active->symbol
                                    ))),
                            )
                        ),
                        'watermark' => array(
                            'type' => 'form',
                            'title' => $this->t('TAB_WM'),
                            'elements' => array(
                                'watermark_active' => array(
                                    'type' => 'checkbox',
                                ),
                                'watermark_image' => array(
                                    'type' => 'file',
                                ),
                                '<div class="formRow">
				<div class="grid5"><label></label></div>
				<div class="grid7">' . $this->renderWatermarkImageTag() . '</div>
                                    <div class="clear"></div>
				</div>',
                                'watermark_corner' => array(
                                    'type' => 'dropdownlist',
                                    'items' => $this->getWatermarkCorner()
                                ),
                                'watermark_offsetX' => array(
                                    'type' => 'text'
                                ),
                                'watermark_offsetY' => array(
                                    'type' => 'text'
                                ),
                            )
                        ),
                        'images' => array(
                            'type' => 'form',
                            'title' => $this->t('TAB_IMG'),
                            'elements' => array(
                                'path' => array(
                                    'type' => 'text',
                                    'afterField' => '<span class="fieldIcon icon-folder"></span>'
                                ),
                                'thumbPath' => array(
                                    'type' => 'text',
                                    'afterField' => '<span class="fieldIcon icon-folder"></span>'
                                ),
                                'url' => array(
                                    'type' => 'text',
                                    'afterField' => '<span class="fieldIcon icon-folder"></span>'
                                ),
                                'thumbUrl' => array(
                                    'type' => 'text',
                                    'afterField' => '<span class="fieldIcon icon-folder"></span>'
                                ),
                                'img_preview_size_list' => array(
                                    'type' => 'text',
                                    'class' => 'maskWidthHeight',
                                    'afterField' => '<span class="fieldIcon icon-image"></span>'
                                ),
                                'img_view_size' => array(
                                    'type' => 'text',
                                    'class' => 'maskWidthHeight',
                                    'afterField' => '<span class="fieldIcon icon-image"></span>'
                                ),
                                'img_view_thumbs_size' => array(
                                    'type' => 'text',
                                    //'class' => 'maskWidthHeight',
                                    'afterField' => '<span class="fieldIcon icon-image"></span>'
                                ),
                                'maxFileSize' => array(
                                    'type' => 'text',
                                    'hint' => Yii::t('ShopModule.admin', 'Укажите размер в байтах.')
                                ),
                                'maximum_image_size' => array(
                                    'type' => 'text',
                                    'hint' => Yii::t('ShopModule.admin', 'Изображения превышающие этот размер, будут изменены.')
                                ),
                            )
                        ),
                        'formatprice' => array(
                            'type' => 'form',
                            'title' => $this->t('TAB_FORMATPRICE'),
                            'elements' => array(
                                'fp_penny' => array(
                                    'type' => 'dropdownlist',
                                    'items' => array(0 => Yii::t('core', 'NO'), 2 => Yii::t('core', 'YES'))
                                ),
                                'fp_separator_thousandth' => array(
                                    'type' => 'dropdownlist',
                                    'items' => self::fpSeparator(),
                                ),
                                'fp_separator_hundredth' => array(
                                    'type' => 'dropdownlist',
                                    'items' => self::fpSeparator(),
                                ),
                            )
                        ),
                    ),
                    'buttons' => array(
                        'submit' => array(
                            'type' => 'submit',
                            'class' => 'buttonS bGreen',
                            'label' => Yii::t('core', 'SAVE')
                        )
                    )
                        ), $this);

        return $tab;
    }

    public function init() {
        $this->attributes = Yii::app()->settings->get('shop');
    }

    /**
     * Разделители цены chr()
     * @url http://ascii.org.ru/
     * @return array
     */
    public static function fpSeparator() {
        return array(0 => 'нечего', 32 => 'пробел', 44 => 'запятая', 46 => 'точка');
    }

    public function getWatermarkCorner() {
        return array(
            1 => $this->t('CORNER_LEFT_TOP'),
            2 => $this->t('CORNER_RIGHT_TOP'),
            3 => $this->t('CORNER_LEFT_BOTTOM'),
            4 => $this->t('CORNER_RIGHT_BOTTOM'),
            5 => $this->t('CORNER_CENTER'),
        );
    }

    public function rules() {
        return array(
            array('watermark_corner', 'numerical', 'integerOnly' => true),
            array('watermark_offsetX, watermark_offsetY, fp_penny, fp_separator_hundredth, fp_separator_thousandth', 'required'),
            //, watermark_opacity
            array('img_preview_size_list, img_view_thumbs_size, img_view_size, per_page, path, thumbPath, url, thumbUrl, maxFileSize, maximum_image_size', 'required'),
            array('watermark_image', 'validateWatermarkFile'),
            array('ajax_mode, wholesale, watermark_active, auto_gen_url, auto_gen_meta, filter_enable_price, filter_enable_brand, filter_enable_attr, auto_fill_short_desc, auto_gen_cat_meta', 'boolean'),
            // array('watermark_opacity', 'match', 'pattern' => '/^[\da-z][-_\d\.a-z]*@(?:[\da-z][-_\da-z]*\.)+[a-z]{2,5}$/iu'),
            array('auto_gen_tpl_title, auto_gen_tpl_keywords, auto_gen_tpl_description', 'type', 'type' => 'string'),
            array('auto_gen_cat_tpl_title, auto_gen_cat_tpl_keywords, auto_gen_cat_tpl_description', 'type', 'type' => 'string'),
        );
    }

    public function renderWatermarkImageTag() {
        if (file_exists(Yii::getPathOfAlias('webroot') . '/uploads/watermark.png'))
            return Html::image('/uploads/watermark.png?' . time());
    }

    /**
     * Validates uploaded watermark file
     */
    public function validateWatermarkFile($attr) {
        $file = CUploadedFile::getInstance($this, 'watermark_image');
        if ($file) {
            $allowedExts = array('jpg', 'gif', 'png');
            if (!in_array($file->getExtensionName(), $allowedExts))
                $this->addError($attr, $this->t('ERRPR_WM_NO_IMAGE'));
        }
    }

    public function getCurrencies() {
        $result = array();
        foreach (Yii::app()->currency->getCurrencies() as $id => $model)
            $result[$id] = $model->name;
        return $result;
    }

    public function save($message = true) {
        Yii::app()->settings->set('shop', $this->attributes);
        $this->saveWatermark();
        parent::save($message);
    }

    public function saveWatermark() {
        $watermark = CUploadedFile::getInstance($this, 'watermark_image');
        if ($watermark)
            $watermark->saveAs(Yii::getPathOfAlias('webroot') . '/uploads/watermark.png');
    }

}
