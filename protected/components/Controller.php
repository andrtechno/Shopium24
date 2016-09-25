<?php

/**
 * Базовый класс контроллеров.
 * 
 * @author CORNER CMS development team <dev@corner-cms.com>
 * @uses RController
 * @package components
 */
Yii::import('mod.users.UsersModule');

class Controller extends RController {

    public $dataModel = null;

    public function adminModel() {
        $result = array();
        $data = $this->dataModel;
        if ($this->dataModel->scenario == 'search') {
            // $result['route']='dsadsa';
        } elseif ($this->dataModel->scenario == 'update') {
            // $result['route_delete'] = $this->dataModel->getDeleteUrl();
            // $result['route_update'] = $this->dataModel->getUpdateUrl();
            // $result['route_switch'] = $this->dataModel->getSwitchUrl();
            $result['route_create'] = $this->dataModel->getCreateUrl();
            $result['create_btn'] = Html::link(Yii::t(ucfirst($data::MODULE_ID) . 'Module.default', 'CREATE'), $this->dataModel->getCreateUrl());
        }
        return $data;
    }

    public $commonJsMessages = array();
    public $copyright_color = '333333';
    public $copyright_size = '92x20';
    public $isAdminController = false;
    protected $_assetsUrl = false;
    private $_baseAssetsUrl;
    protected $_edit_mode = false;
    protected $_messages;
    public $breadcrumbs = array();
    public $pageKeywords;
    public $pageDescription;
    private $_pageTitle;
    public $layout = '';
    public $currentModule;
    protected $_timezone;

    public function getFirstCityId() {
        Yii::import('mod.contacts.models.ContactsCites');

        $cr = new CDbCriteria;
        $cr->limit = 1;
        $city = ContactsCites::model()->find($cr);
        return $city->id;
    }

    public function getCacheTime() {
        return !YII_DEBUG ? 0 : 3600;
    }

    public function getBaseAssetsUrl() {
        if ($this->_baseAssetsUrl === null) {
            $this->_baseAssetsUrl = Yii::app()->assetManager->publish(
                    Yii::getPathOfAlias('app.assets'), false, -1, YII_DEBUG
            );
        }
        return $this->_baseAssetsUrl;
    }

    /**
     * @return string Показывает информацию о сгенерируемой страницы.
     */
    public function getPageGen() {
        $sql_stats = YII::app()->db->getStats();
        return Yii::t('default', 'PAGE_GEN', array(
                    '{TIME}' => number_format(Yii::getLogger()->getExecutionTime(), 3, '.', ' '),
                    '{MEMORY}' => round(memory_get_peak_usage() / (1024 * 1024), 2),
                    '{DB_QUERY}' => $sql_stats[0],
                    '{DB_TIME}' => number_format($sql_stats[1], 2, '.', ' '),
        ));
    }

    /**
     * 
     * @return array
     */
    public function behaviors() {
        return array_merge(parent::behaviors(), array('LayoutBehavior' => array('class' => 'app.behaviors.LayoutBehavior')));
    }

    public function getEdit_mode() {
        return $this->_edit_mode;
    }

    protected function setEdit_mode() {
        if (Yii::app()->user->isSuperuser && isset($_REQUEST['edit_mode'])) {
            $mode = $_REQUEST['edit_mode'];
            if ($mode == 'true') {
                Yii::app()->session['edit_mode'] = true;
            } else {
                unset(Yii::app()->session['edit_mode']);
            }
        }
        $this->_edit_mode = Yii::app()->session['edit_mode'];
    }

    /**
     * Запись сессий
     * 
     * @param int $sessionTime
     */
    private function recordSession($sessionTime = 900) {
        if (true) {
            $db = Yii::app()->db;
            $ip = Yii::app()->request->userHostAddress;
            $user_agent = Yii::app()->request->userAgent;
            $url = htmlspecialchars(getenv("REQUEST_URI"));

            if (Yii::app()->user->isSuperuser) {
                $uname = Yii::app()->user->login;
                $user_type = 3;
            } elseif (!Yii::app()->user->isGuest) {
                $uname = Yii::app()->user->login;
                $user_type = 2;
            } elseif (Yii::app()->user->isGuest) {
                $checkBot = CMS::isBot();
                if ($checkBot['success']) {
                    $uname = substr($checkBot['name'], 0, 25);
                    $user_type = 1;
                } else {
                    $uname = $ip;
                    $user_type = 0;
                }
            }
            $sessionFile = Yii::getPathOfAlias('webroot.protected.runtime') . DS . 'session.txt';
            $sessTime = (file_exists($sessionFile) && filesize($sessionFile) != 0) ? file_get_contents($sessionFile) : 0;
            $past = CMS::time() - $sessionTime;
            if ($sessTime < $past) {
                $db->createCommand()->delete("{{session}}", 'expire < :exp', array(':exp' => $past));
                if (!Yii::app()->user->isGuest) {
                    $db->createCommand()->update("{{user}}", array(
                        'login_ip' => $ip,
                        'last_login' => date('Y-m-d H:i:s'),
                        'user_agent' => Yii::app()->request->userAgent,
                            ), 'id=:id', array(':id' => Yii::app()->user->id));
                }
                if (file_exists($sessionFile)) {
                    unlink($sessionFile);
                }
                $fp = fopen($sessionFile, "wb");
                fwrite($fp, CMS::time());
                fclose($fp);
            }
            $expire = CMS::time();
            if ($uname) {
                $num = $db->createCommand(array(
                            'select' => array('uname'),
                            'from' => "{{session}}",
                            'where' => 'uname=:uname',
                            'params' => array(':uname' => $uname),
                        ))->queryAll();

                if (count($num) >= 1) {
                    $db->createCommand()->update("{{session}}", array(
                        'uname' => $uname,
                        'user_login' => (!Yii::app()->user->isGuest) ? Yii::app()->user->login : NULL,
                        'expire' => $expire,
                        'ip_address' => $ip,
                        'user_agent' => $user_agent,
                        'user_type' => $user_type,
                        'user_avatar' => Yii::app()->user->getAvatarUrl('100x100', Yii::app()->user->isGuest),
                        'module' => (Yii::app()->controller->module->id) ? Yii::app()->controller->module->id : 'unknown',
                        'current_url' => $url
                            ), 'uname=:uname', array(':uname' => $uname));
                } else {
                    $db->createCommand()->insert("{{session}}", array(
                        'uname' => $uname,
                        'user_login' => (!Yii::app()->user->isGuest) ? Yii::app()->user->login : NULL,
                        'start_expire' => CMS::time(),
                        'expire' => $expire,
                        'ip_address' => $ip,
                        'user_agent' => $user_agent,
                        'user_type' => $user_type,
                        'user_avatar' => Yii::app()->user->getAvatarUrl('100x100', Yii::app()->user->isGuest),
                        'module' => (Yii::app()->controller->module->id) ? Yii::app()->controller->module->id : 'unknoew',
                        'current_url' => $url
                    ));
                }
            }
        }
    }

    protected function beforeRender($view) {
        if (!$this->isAdminController) {
            if (Yii::app()->hasModule('seo')) {
                Yii::app()->seo->googleAnalytics();
                Yii::app()->seo->yandexMetrika();
                // Yii::app()->seo->googleTagManager(false);
                Yii::import('mod.seo.models.Redirects');
                $redirect = Redirects::model()->published()->findByAttributes(array(
                    'url_from' => Yii::app()->request->url
                ));
                if ($redirect) {
                    $this->redirect(array($redirect->url_to), true, 301);
                }
            }
        }
        $this->recordSession();
        $this->initLayout();
        return parent::beforeRender($view);
    }

    protected function beforeAction($action) {

        $cs = Yii::app()->clientScript;
        $cs->registerMetaTag(null, null, null, array(
            'charset' => Yii::app()->charset
        ));
        $cs->registerMetaTag('text/html; charset=' . Yii::app()->charset, null, 'Content-Type');

        $appletouch = array('57x57', '60x60', '72x72', '76x76', '114x114', '120x120', '144x144', '152x152', '180x180');
        foreach ($appletouch as $size) {
            if (file_exists(Yii::getPathOfAlias("current_theme.assets.images") . DS . "apple-touch-icon-{$size}.png")) {
                $cs->registerLinkTag('apple-touch-icon', NULL, $this->assetsUrl . "/images/apple-touch-icon-{$size}.png", NULL, array('sizes' => $size));
            } elseif (file_exists(Yii::getPathOfAlias("current_theme.assets.images") . DS . "apple-touch-icon.png")) {
                $cs->registerLinkTag('apple-touch-icon', NULL, $this->assetsUrl . "/images/apple-touch-icon.png", NULL);
            }
        }

        $mod_id = Yii::app()->controller->module->id;
        $appdebug = (YII_DEBUG) ? 'true' : 'false';
        $cs->registerScriptFile($this->baseAssetsUrl . "/js/common.js", CClientScript::POS_HEAD);
        $cs->registerScript('commonjs', "
        common.token = '" . Yii::app()->request->csrfToken . "';
        common.language = '" . Yii::app()->language . "';
        common.debug = " . $appdebug . ";
        common.message = " . CJavaScript::encode($this->commonJsMessages) . ";

        ", CClientScript::POS_HEAD);

        $cs->registerMetaTag('CORNER CMS', 'author');
        $cs->registerMetaTag('CORNER CMS ' . Yii::app()->version, 'generator');
        return parent::beforeAction($action);
    }

    protected function afterRender($view, &$output) {
        //if (!Yii::app()->request->isAjaxRequest && !preg_match("#" . base64_decode('e2NvcHlyaWdodH0=') . "#", $output) && !preg_match("/print/", $this->layout)) {
        //    $this->renderPartial('app.maintenance.layouts.alert', array('content' => Yii::t('app', base64_decode('Tk9fQ09QWVJJR0hU'))), false, true);
        //    Yii::app()->end();
        //}
        if (!Yii::app()->hasModule('seo')) {
            $cs = Yii::app()->clientScript;
            if ($this->pageDescription !== null) {
                $cs->registerMetaTag($this->pageDescription, 'description', null, null, 'description');
            }
            if ($this->pageKeywords !== null) {
                $cs->registerMetaTag($this->pageKeywords, 'keywords', null, null, 'keywords');
            }
        }
        parent::afterRender($view, $output);
    }

    /**
     * 
     * @param type $message
     */
    public function addFlashMessage($message) {
        $currentMessages = Yii::app()->user->getFlash('messages');

        if (!is_array($currentMessages))
            $currentMessages = array();

        Yii::app()->user->setFlash('messages', CMap::mergeArray($currentMessages, array($message)));
    }

    public function setPageTitle($title) {
        $this->_pageTitle = $title;
    }

    /**
     * Register assets file of theme
     * @return string
     */
    private function registerAssets() {
        $assets = Yii::getPathOfAlias('current_theme.assets');
        $url = Yii::app()->getAssetManager()->publish($assets, false, -1, YII_DEBUG);
        $this->_assetsUrl = $url;
    }

    /**
     * @return string
     */
    public function getAssetsUrl() {
        return $this->_assetsUrl;
    }

    /**
     * 
     * @return string Timezone 
     */
    public function getTimezone() {
        $user = Yii::app()->user;
        $config = Yii::app()->settings->get('app');
        if (!$user->isGuest) {
            if ($user->timezone) {
                $this->_timezone = $user->timezone;
            } elseif (isset(Yii::app()->session['timezone'])) {
                $this->_timezone = Yii::app()->session['timezone'];
            } else {
                $this->_timezone = $config['default_timezone'];
            }
        } else {
            if (isset(Yii::app()->session['timezone'])) {
                $this->_timezone = Yii::app()->session['timezone'];
            } else {
                $this->_timezone = $config['default_timezone'];
            }
        }
        return $this->_timezone;
    }

    public function printer($title, $content, $date) {
        if (Yii::app()->request->getParam('print')) {
            $this->layout = '//layouts/print';
            $this->pageTitle = 'Печать';


            $this->render('//layouts/_print', array(
                'title' => $title,
                'content' => $content,
                'date' => CMS::date($date)
            ));
            Yii::app()->end();
        }
    }

    public function init() {

        $user = Yii::app()->user;
        $langManager = Yii::app()->languageManager;

        if ($user->language) {
            if ($user->language != $langManager->default->code) {
                $getLang = $langManager->getById($user->language)->code;
                Yii::app()->language = $getLang;
                $strpos = strpos(Yii::app()->request->requestUri, '/' . $getLang);
                if ($strpos === false) {
                    if ($langManager->default->code != $getLang) {
                        if ($this->isAdminController)
                            $this->redirect("/{$getLang}/admin");
                        else
                            $this->redirect('/' . $getLang);
                    }
                }
            } else {
                Yii::app()->language = $langManager->active->code;
            }
        } else {
            Yii::app()->language = $langManager->active->code;
        }


        $this->setEdit_mode();
        $this->addComponents();
        $this->currentModule = $this->module->id;
        $theme = Yii::app()->theme->name;
        Yii::setPathOfAlias("current_theme", Yii::getPathOfAlias("webroot.themes.{$theme}"));
        $this->backup();
        $this->registerAssets();
        if (Yii::app()->getModule('stats') && !$this->isAdminController) {
            if (Yii::app()->hasComponent('stats')) {
                $stats = Yii::app()->stats;
                $stats->record();
            }
        }
        $this->commonJsMessages = array(
            'error' => array(
                '404' => Yii::t('error', '404')
            ),
            'cancel' => Yii::t('app', 'CANCEL'),
            'delete' => Yii::t('app', 'DELETE'),
            'save' => Yii::t('app', 'SAVE'),
            'close' => Yii::t('app', 'CLOSE'),
            'ok' => Yii::t('app', 'OK'),
        );
        parent::init();
    }

    private function addComponents() {
        $components = ComponentsModel::model()
                ->findAll();
        $compArray = array();
        foreach ($components as $component) {
            $compArray[$component->name] = array(
                'class' => $component->class
            );
        }
        Yii::app()->setComponents($compArray);
    }

    private function backup() {
        $security = Yii::app()->settings->get('security');
        if ($security['backup_db'] && Yii::app()->user->isSuperuser) {
            if ($security['backup_time_cache'] < time()) {
                /* Записываем новое текущие время + указанное время */
                Yii::app()->settings->set('security', array('backup_time_cache' => time() + $security['backup_time']));
                /* Делаем Backup */
                Yii::app()->database->export();
            }
        }
    }

    /**
     * 
     * @return string
     */
    public function getPageTitle() {
        $title = Yii::app()->settings->get('app', 'site_name');
        if (!empty($this->_pageTitle)) {
            $title = $this->_pageTitle.=' / ' . $title;
        }
        return $title;
    }

    public function setEmView($view) {
        if (file_exists($this->getViewFile($view))) {
            if ($this->getEdit_mode()) {
                return (file_exists($this->getViewFile($view . '_em'))) ? $view . '_em' : $view;
            } else {
                return $view;
            }
        } else {
            return $view;
        }
    }

    /**
     * Изменяя или удаление копирайты системы, Вы нарушаете соглашение договора.
     * Проверка наличие копирейта в шаблонах
     */
    public function processOutput($output) {
        $css = '<style type="text/css" scoped>a.corner{display:inline-block;background: url("http://corner-cms.com/logo.php?size=' . $this->copyright_size . '&color=' . $this->copyright_color . '") no-repeat right center;}</style>';
        if ($this->isAdminController) {
            $copyright = Yii::app()->getCopyright();
        } else {
            $copyright = '<a class="corner" href="//corner.com.ua/portfolio/sites" target="_blank"><span>' . Yii::t('default', 'CORNER') . '</span> &mdash; </a>';
        }
        $output = str_replace(base64_decode('e2NvcHlyaWdodH0='), $css . $copyright, $output);
        return parent::processOutput($output);
    }

    /**
     * 
     * @param string $view
     * @param array $data
     * @param boolean $return
     * @param boolean $processOutput
     */
    public function render($view, $data = null, $return = false, $processOutput = false) {
        if (Yii::app()->request->isAjaxRequest === true) {
            parent::renderPartial($view, $data, $return, $processOutput);
        } else {
            parent::render($view, $data, $return);
        }
    }

    /**
     * 
     * @param type $message
     */
    public function setFlashMessage($message) {
        $currentMessages = Yii::app()->user->getFlash('messages');
        if (!is_array($currentMessages))
            $currentMessages = array();

        Yii::app()->user->setFlash('messages', CMap::mergeArray($currentMessages, array($message)));
    }

    public function setNotify($message, $type = 'info') {
        $currentMessages = Yii::app()->user->getFlash($type);
        if (!is_array($currentMessages))
            $currentMessages = array();
        $messages = array($type => $message);
        Yii::app()->user->setFlash('notify', CMap::mergeArray($currentMessages, $messages));
    }

    public function performAjaxValidation($model, $formid) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $formid) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
