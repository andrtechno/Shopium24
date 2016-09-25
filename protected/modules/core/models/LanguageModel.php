<?php

/**
 * This is the model class for table "EngineLanguage".
 *
 * The followings are the available columns in table 'EngineLanguage':
 * @property integer $id
 * @property string $name Language name
 * @property string $code Url prefix
 * @property string $locale Language locale
 * @property boolean $default Is lang default
 * @property boolean $flag_name Flag image name
 */
class LanguageModel extends ActiveRecord {

    const MODULE_ID = 'core';

    public $data_lang;
    public $start_auto_translate = true;
    private static $_languages;
    public $hidden_delete = array(1);

    public function getDataLangList() {
        $currLangs = Yii::app()->languageManager->getCodes();
        $result = array();
        foreach (self::langListArray() as $lang) {
            if (!array_keys($currLangs, $lang[0])) {
                $result[$lang[0]] = $lang[1]['name'];
            }
        }
        return $result;
    }

    /**
     * Locale helper http://lh.2xlibre.net/locales/
     * @return array
     */
    public static function langListArray() {
        return array(
            array('en', array('name' => 'English', 'image' => 'en.png', 'locale' => 'en_US')),
            array('uk', array('name' => 'Ukrainian', 'image' => 'ua.png', 'locale' => 'uk_UA')),
            array('ru', array('name' => 'Russian', 'image' => 'ru.png', 'locale' => 'ru_RU')),
            array('ar', array('name' => 'Arabic', 'image' => 'ps.png', 'locale' => 'ar_AE')),
            array('hy', array('name' => 'Armenian', 'image' => 'am.png', 'locale' => 'hy_AM')),
            array('sq', array('name' => 'Albanian', 'image' => 'al.png', 'locale' => 'sq_AL')),
            array('az', array('name' => 'Azerbaijani', 'image' => 'az.png', 'locale' => 'az_AZ')),
            array('be', array('name' => 'Belarusian', 'image' => 'by.png', 'locale' => 'be_BY')),
            array('bg', array('name' => 'Bulgarian', 'image' => 'bg.png', 'locale' => 'bg_BG')),
            array('bs', array('name' => 'Bosnian', 'image' => 'ba.png', 'locale' => 'bs_BA')),
            array('ca', array('name' => 'Catalan', 'image' => 'catalonia.png', 'locale' => 'ca_ES')),
            array('cs', array('name' => 'Czech', 'image' => 'cz.png', 'locale' => 'cs_CZ')),
            array('hr', array('name' => 'Croatian', 'image' => 'hr.png', 'locale' => 'hr_HR')),
            array('zh', array('name' => 'Chinese', 'image' => 'cn.png', 'locale' => 'zh_CN')),
            array('da', array('name' => 'Danish', 'image' => 'dk.png', 'locale' => 'da_DK')),
            array('nl', array('name' => 'Dutch', 'image' => 'nl.png', 'locale' => 'nl_AW')),
            array('de', array('name' => 'German', 'image' => 'de.png', 'locale' => 'de_DE')),
            array('el', array('name' => 'Greek', 'image' => 'gr.png', 'locale' => 'el_GR')),
            array('ka', array('name' => 'Georgian', 'image' => 'ge.png', 'locale' => 'ka_GE')),
            array('et', array('name' => 'Estonian', 'image' => 'ee.png', 'locale' => 'et_EE')),
            array('fi', array('name' => 'Finnish', 'image' => 'fi.png', 'locale' => 'fi_FI')),
            array('fr', array('name' => 'French', 'image' => 'fr.png', 'locale' => 'fr_FR')),
            array('he', array('name' => 'Hebrew', 'image' => 'hn.png', 'locale' => 'he_IL')),
            array('hu', array('name' => 'Hungarian', 'image' => 'hu.png', 'locale' => 'hu_HU')),
            array('id', array('name' => 'Indonesian', 'image' => 'id.png', 'locale' => 'id_ID')),
            array('is', array('name' => 'Icelandic', 'image' => 'is.png', 'locale' => 'is_IS')),
            array('it', array('name' => 'Italian', 'image' => 'ie.png', 'locale' => 'it_IT')),
            array('lt', array('name' => 'Lithuanian', 'image' => 'lt.png', 'locale' => 'lt_LT')),
            array('lv', array('name' => 'Latvian', 'image' => 'lv.png', 'locale' => 'lv_LV')),
            array('mk', array('name' => 'Macedonian', 'image' => 'mk.png', 'locale' => 'mk_MK')),
            array('ms', array('name' => 'Malay', 'image' => 'my.png', 'locale' => 'ms_MY')),
            array('mt', array('name' => 'Maltese', 'image' => 'mt.png', 'locale' => 'mt_MT')),
            array('no', array('name' => 'Norwegian', 'image' => 'no.png', 'locale' => 'nn_NO')),
            array('pl', array('name' => 'Polish', 'image' => 'pl.png', 'locale' => 'pl_PL')),
            array('pt', array('name' => 'Portuguese', 'image' => 'pt.png', 'locale' => 'pt_PT')),
            array('ro', array('name' => 'Romanian', 'image' => 'ro.png', 'locale' => 'ro_RO')),
            array('sk', array('name' => 'Slovak', 'image' => 'sk.png', 'locale' => 'sk_SK')),
            array('sl', array('name' => 'Slovenian', 'image' => 'si.png', 'locale' => 'sl_SI')),
            array('sr', array('name' => 'Serbian', 'image' => 'si.png', 'locale' => 'sr_RS')),
            array('sv', array('name' => 'Swedish', 'image' => 'se.png', 'locale' => 'sv_SE')),
            array('es', array('name' => 'Spanish', 'image' => 'es.png', 'locale' => 'an_ES')),
            array('th', array('name' => 'Thai', 'image' => 'th.png', 'locale' => 'th_TH')),
            array('tr', array('name' => 'Turkish', 'image' => 'tr.png', 'locale' => 'tr_TR')),
            array('vi', array('name' => 'Vietnamese', 'image' => 'vn.png', 'locale' => 'vi_VN')),
        );
    }

    public function attributeLabels() {
        return CMap::mergeArray(array(
                    'data_lang' => Yii::t('CoreModule.LanguageModel', 'DATA_LANG'),
                    'start_auto_translate' => Yii::t('CoreModule.LanguageModel', 'START_AUTO_TRANSLATE')
                        ), parent::attributeLabels());
    }

    public function getForm() {
        Yii::import('ext.bootstrap.selectinput.SelectInput');

        $formArray = array();
        if ($this->isNewRecord) {
            $formArray = array(
                'data_lang' => array(
                    'type' => 'SelectInput',
                    'data' => $this->getDataLangList(),
                ),
                'start_auto_translate' => array(
                    'type' => 'checkbox',
                ),
                'default' => array(
                    'type' => 'checkbox',
                )
            );
        } else {
            $formArray = array(
                'name' => array(
                    'type' => 'text',
                ),
                'code' => array(
                    'type' => 'text',
                    'hint' => Yii::t('app', 'EXAMPLE', array('{example}' => 'ru')),
                ),
                'locale' => array(
                    'type' => 'text',
                    'hint' => Yii::t('app', 'EXAMPLE', array('{example}' => 'en_US')),
                ),
                'flag_name' => array(
                    'type' => 'SelectInput',
                    'data' => self::getFlagImagesList(),
                    'afterField' => '<span id="flag_render" class="" style="margin-left:15px;"></span>'
                ),
                'default' => array(
                    'type' => 'checkbox',
            ));
        }
        return new CMSForm(array(
            'attributes' => array(
                'id' => __CLASS__,
                'class' => 'form-horizontal',
            ),
            'elements' => $formArray,
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => $this->isNewRecord ? Yii::t('app', 'CREATE', 0) : Yii::t('app', 'SAVE')
                ),
                'translate' => array(
                    'type' => 'link',
                    'visible' => !$this->isNewRecord,
                    'href' => array('/admin/core/translates/application?lang=' . $this->code),
                    'class' => 'btn btn-default',
                    'onClick' => 'if(confirm("' . Yii::t('app', 'CONFIRM_LANG_TRANSLATE', array('{name}' => $this->name)) . '")) {
                        window.location.href="/admin/core/translates/application?lang=' . $this->code . '";
                        return true;
                      } else {
                        return false;
                      }
                    ',
                    'label' => Yii::t('app', 'TRANSLATE_ALL_WEBSITE')
                ),
            )
                ), $this);
    }

    /**
     * Returns the static model of the specified AR class.
     * @return EngineLanguage the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{language}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('data_lang, start_auto_translate', 'required', 'on' => 'insert'),
            array('start_auto_translate', 'boolean'),
            array('name, code', 'required', 'on' => 'update'),
            array('name, locale', 'length', 'max' => 100),
            array('flag_name', 'length', 'max' => 255),
            array('code', 'length', 'max' => 25),
            array('default', 'in', 'range' => array(0, 1)),
            array('id, name, code, locale', 'safe', 'on' => 'search'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('locale', $this->locale, true);
        $criteria->compare('default', $this->default);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    private function attrCreate() {
        if ($this->isNewRecord) {
            $langsArray = self::langListArray();
            $findLang = array();
            foreach ($langsArray as $lang) {
                if ($this->data_lang == $lang[0]) {
                    $findLang = $lang;
                    break;
                }
            }
            $this->name = $findLang[1]['name'];
            $this->code = $this->data_lang;
            $this->locale = $findLang[1]['locale'];
            $this->flag_name = $findLang[1]['image'];
        }
    }

    public function afterValidate() {
        $this->attrCreate();
        return parent::afterValidate();
    }

    public function afterSave() {
        $this->attrCreate();

        // Leave only one default language
        /* if ($this->default)
          {
          self::model()->updateAll(array(
          'default'=>0,
          ), 'id != '.$this->id);
          } */
        return parent::afterSave();
    }

    private static function removeDir($dir) {
        CFileHelper::removeDirectory($dir, array('traverseSymlinks' => true));
    }

    public function afterDelete() {
        // Remove application messages
        if (file_exists(Yii::getPathOfAlias("application.messages.{$this->code}"))) {
            self::removeDir(Yii::getPathOfAlias("application.messages.{$this->code}"));
        }

        // Remove locale dirs in modules
        foreach (Yii::app()->getModules() as $key => $mod) {
            $dir = Yii::getPathOfAlias("mod.{$key}.messages.{$this->code}");
            if (file_exists($dir)) {
                self::removeDir($dir);
            }

            //Remove module widget messages.
            $widgetsPath = "webroot.protected.modules.{$key}.widgets";
            if (file_exists(Yii::getPathOfAlias($widgetsPath))) {
                $widgetdirs = scandir(Yii::getPathOfAlias($widgetsPath));
                foreach ($widgetdirs as $entry) {
                    if ($entry != '.' && $entry != '..' && $entry != 'index.html' && !preg_match("/\.([a-zA-Z0-9]+)/", $entry)) {
                        if (file_exists(Yii::getPathOfAlias("{$widgetsPath}.{$entry}.messages.{$this->code}"))) {
                            self::removeDir(Yii::getPathOfAlias("{$widgetsPath}.{$entry}.messages.{$this->code}"));
                        }
                    }
                }
            }
        }

        // Remove in folder extensions.
        $extPath = "webroot.protected.extensions";
        $extdirs = scandir(Yii::getPathOfAlias($extPath));
        foreach ($extdirs as $entry) {
            if ($entry != '.' && $entry != '..' && $entry != 'index.html' && !preg_match("/\.([a-zA-Z0-9]+)/", $entry)) {
                if (file_exists(Yii::getPathOfAlias("{$extPath}.{$entry}.messages.{$this->code}"))) {
                    self::removeDir(Yii::getPathOfAlias("{$extPath}.{$entry}.messages.{$this->code}"));
                }
            }
        }

        return parent::afterDelete();
    }

    public function beforeDelete() {
        if ($this->default)
            return false;

        return parent::beforeDelete();
    }

    public static function getFlagImagesList() {
        Yii::import('system.utils.CFileHelper');
        $flagsPath = 'webroot.uploads.language';

        $result = array();
        $flags = CFileHelper::findFiles(Yii::getPathOfAlias($flagsPath));

        foreach ($flags as $f) {
            $parts = explode(DS, $f);
            $fileName = end($parts);
            $result[$fileName] = $fileName;
        }

        return $result;
    }

}
