<?php

/**
 * @author CORNER CMS development team <dev@corner-cms.com>
 * @package Settings
 * @copyright (c) 2014, Andrew S.
 * @version 1.0
 * @link http://cms.corner.com.ua CORNER CMS
 * 
 * @property string $site_name Site name
 * @property string $forum Integration of forums
 * @property string $theme Theme name
 * @property string $etheme Event theme name
 * @property datetime $etheme_start Start date event theme
 * @property datetime $etheme_end End date event theme
 * @property string $tableToolbarClass
 * @property boolean $site_close Close site
 * @property text $site_close_text Close site text
 * @property string $format_date Format datetime CMS::date()
 * @property int $cookie_time Cookie time in day
 */
class SettingsAppForm extends FormSettingsModel {

    const NAME = 'app';

    public $license_key;
    public $translate_object_url;
    public $site_name;
    public $forum;
    public $forum_path;
    public $theme;
    public $etheme;
    public $etheme_start;
    public $etheme_end;
    public $tableToolbarClass;
    public $site_close;
    public $site_close_text;
    public $format_date;
    public $cookie_time;
    public $cache_time;
    public $pagenum;
    public $multi_language;
    public $censor_array;
    public $censor;
    public $censor_replace;
    public $site_close_allowed_users;
    public $site_close_allowed_ip;
    public $session_time;
    public $admin_email;
    public $default_timezone;

    public function getForm() {
        $themesNames = Yii::app()->themeManager->themeNames;
        $themes = array_combine($themesNames, $themesNames);

        $eventThemes = array_combine($themesNames, $themesNames);
        unset($eventThemes[$this->theme]);
        $df = Yii::app()->dateFormatter;
        Yii::app()->controller->widget('ext.tinymce.TinymceWidget');
        Yii::import('app.jui.JuiDateTimePicker');
        Yii::import('ext.TagInput');
        Yii::import('ext.bootstrap.selectinput.SelectInput');
        return new TabForm(array(
            'attributes' => array(
                'id' => __CLASS__,
                'class' => 'form-horizontal',
            ),
            'showErrorSummary' => false,
            'elements' => array(
                'global' => array(
                    'type' => 'form',
                    'title' => static::t('TAB_GENERAL'),
                    'elements' => array(
                        'translate_object_url' => array('type' => 'checkbox'),
                        'site_name' => array('type' => 'text'),
                        'license_key' => array('type' => (Yii::app()->user->isSuperuser) ? 'text' : 'read'),
                        'admin_email' => array('type' => 'text', 'afterField' => '<span class="fieldIcon icon-envelope"></span>'),
                        'forum' => array(
                            'type' => 'SelectInput',
                            'data' => self::forums(),
                            'hint'=>'Расположение директории форума должна быть в корне сайта. <code>"'.$_SERVER['DOCUMENT_ROOT'].'"</code>',
                            'htmlOptions' => array(
                                'empty' => Yii::t('app', 'NO')
                            )
                        ),
                        'forum_path' => array('type' => 'text'),
                        'session_time' => array('type' => 'text', 'afterField' => '<span class="fieldIcon icon-alarm-2"></span>'),
                        'cookie_time' => array('type' => 'text', 'afterField' => '<span class="fieldIcon icon-alarm-2"></span>'),
                        'cache_time' => array('type' => 'text', 'afterField' => '<span class="fieldIcon icon-alarm-2"></span>'),
                        'pagenum' => array('type' => 'text'),
                        'multi_language' => array('type' => 'checkbox'),
                        'theme' => array(
                            'type' => 'SelectInput',
                            'data' => $themes,
                        ),
                        'etheme' => array(
                            'type' => 'SelectInput',
                            'data' => $eventThemes,
                            'htmlOptions' => array('empty' => Yii::t('app', 'NO'))
                        ),
                        'etheme_start' => array(
                            'type' => 'JuiDateTimePicker',
                            'options' => array(
                                'dateFormat' => 'yy-mm-dd',
                                'timeFormat' => 'HH:mm:00'
                            ),
                        // 'htmlOptions' => array(
                        //     'value' => ($this->isNewRecord) ? date('Y-m-d H:i:s') : $this->date_create,
                        // )
                        ),
                        'etheme_end' => array(
                            'type' => 'JuiDateTimePicker',
                            'options' => array(
                                'dateFormat' => 'yy-mm-dd',
                                'timeFormat' => 'HH:mm:00'
                            ),
                        // 'htmlOptions' => array(
                        //     'value' => ($this->isNewRecord) ? date('Y-m-d H:i:s') : $this->date_create,
                        // )
                        ),
                        'tableToolbarClass' => array(
                            'type' => 'SelectInput',
                            'data' => array(
                                'tablectrl_xlarge2' => Yii::t('CoreModule.admin', 'small'),
                                'tablectrl_xlarge3' => Yii::t('CoreModule.admin', 'medium'),
                                'tablectrl_xlarge4' => Yii::t('CoreModule.admin', 'large'),
                            )
                        ),
                    )
                ),
                'close_site' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_CLOSESITE'),
                    'elements' => array(
                        'site_close' => array('type' => 'checkbox'),
                        'site_close_text' => array('type' => 'textarea', 'class' => 'editor'),
                        'site_close_allowed_users' => array(
                            'type' => 'TagInput',
                            'options' => array(
                                'defaultText' => self::t('ADD_USER')
                            ),
                            'hint' => Yii::t('app', 'HINT_TAGS_PLUGIN')
                        ),
                        'site_close_allowed_ip' => array(
                            'type' => 'text',
                            'type' => 'TagInput',
                            'options' => array(
                                'defaultText' => self::t('ADD_IP')
                            ),
                            'hint' => Yii::t('app', 'HINT_TAGS_PLUGIN')
                        ),
                    )
                ),
                'censor' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_CENSOR'),
                    'elements' => array(
                        'censor' => array('type' => 'checkbox'),
                        'censor_array' => array(
                            'type' => 'TagInput',
                            'options' => array(
                                'defaultText' => self::t('ADD_WORD')
                            ),
                            'hint' => Yii::t('app', 'HINT_TAGS_PLUGIN')
                        ),
                        'censor_replace' => array('type' => 'text'),
                    )
                ),
                'datetime' => array(
                    'type' => 'form',
                    'title' => self::t('TAB_DATETIME'),
                    'elements' => array(
                        'format_date' => array(
                            'type' => 'text',
                            'afterField' => '<span class="fieldIcon icon-calendar-2 "></span>',
                            'hint' => "<div>День (d или dd): " . $df->format('dd', date('Y-m-d H:i:s')) . "</div>
                    <div>Месяц (MM): " . $df->format('MM', date('Y-m-d H:i:s')) . "</div>
                    <div>Месяц (MMM): " . $df->format('MMM', date('Y-m-d H:i:s')) . "</div>
                    <div>Месяц (MMMM): " . $df->format('MMMM', date('Y-m-d H:i:s')) . "</div>
                    <div>Год (yy): " . $df->format('yy', date('Y-m-d H:i:s')) . "</div>
                    <div>Год (yyyy): " . $df->format('yyyy', date('Y-m-d H:i:s')) . "</div>"
                        ),
                        'default_timezone' => array(
                            'type' => 'SelectInput',
                            'data' => TimeZoneHelper::getTimeZoneData()
                        ),
                    )
                ),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'btn btn-success',
                    'label' => Yii::t('app', 'SAVE')
                )
            )
                ), $this);
    }

    public function init() {
        $param = Yii::app()->settings->get('app');
        $param['cache_time'] = $param['cache_time'] / 86400;
        $param['cookie_time'] = $param['cookie_time'] / 86400;
        $param['session_time'] = $param['session_time'] / 60;
        $this->attributes = $param;
    }

    /**
     * @return array
     */
    public function rules() {
        return array(
            array('etheme_end, etheme_start', 'type', 'type' => 'datetime', 'datetimeFormat' => 'yyyy-MM-dd hh:mm:ss'),
            array('pagenum, admin_email, default_timezone, session_time, site_close_allowed_users, site_name, censor_replace, censor_array, theme, tableToolbarClass, site_close_text, format_date, cache_time, cookie_time, license_key', 'required'),
            array('site_close_allowed_ip', 'IPValidator'),
            array('license_key', 'validateLicense'),
            array('forum, etheme, etheme_start, etheme_end, default_timezone, forum_path', 'type', 'type' => 'string'),
            array('multi_language, censor, site_close, translate_object_url', 'boolean')
        );
    }

    public function validateLicense($attr) {
        $data = LicenseCMS::run()->connected($this->$attr);
        if ($data['status'] == 'error') {
            $this->addError($attr, $data['message']);
        } else {
            LicenseCMS::run()->removeLicenseCache();
        }
    }

    /**
     * Saves attributes into database
     */
    public function save($message = true) {
        $this->cache_time = $_POST['SettingsAppForm']['cache_time'] * 86400;
        $this->cookie_time = $_POST['SettingsAppForm']['cookie_time'] * 86400;
        $this->session_time = $_POST['SettingsAppForm']['session_time'] * 60;
        parent::save($message);
    }

    /**
     * Integration forums
     * @return array
     */
    private static function forums() {
        return array(
            'ipb|3.4.x' => 'Invision Power Board (3.4.x)',
            'phpbb3|3' => 'phpBB 3',
            'phpbb2|2' => 'phpBB 2',
                //'vb3|3.x'=>'vBulletin (3.x)',
                //'vb5|4.x'=>'vBulletin (4.x)',
                //  'vb5|5.x'=>'vBulletin (5.x)',
                //  'smf|2.x'=>'Simple Machines Forum',
                // 'phpbb|2.x'=>'phpBB (2.x)',
        );
    }

}
