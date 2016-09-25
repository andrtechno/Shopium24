<?php

class TranslatesController extends AdminController {

    const PATH_MOD = 'webroot.protected.modules';
    const PATH_APP = 'webroot.protected.messages';

    public $topButtons = false;
    private $_sl; //SourceLanguage default is Class languageManager
    private $_tl; //TargetLanguage

    public function init() {
        parent::init();
        $this->_sl = Yii::app()->languageManager->default->code;
    }

    public function actionTester() {

        $this->generateMessagesModules('fr');
    }

    public function generateMessagesModules($locale) {
        $this->_tl = $locale;
        $modules = ModulesModel::getModules();
        $t = new yandexTranslate;
        $result = array();
        $num = -1;
        $params = array();
        foreach ($modules as $key => $mod) {
            $pathDefault = Yii::getPathOfAlias(self::PATH_MOD . '.' . $key . '.messages.ru');
            $listfile = CFileHelper::findFiles($pathDefault, array(
                        'fileTypes' => array('php'),
                        'absolutePaths' => false
            ));
            $path = Yii::getPathOfAlias(self::PATH_MOD . '.' . $key . '.messages' . DS . $this->_tl);
//CFileHelper::createDirectory($path, 0777);


            CFileHelper::copyDirectory($pathDefault, $path, array(
                'fileTypes' => array('php'),
                'level' => 1,
            ));

            foreach ($listfile as $file) {
//   $file = str_replace('.php', '', $file);
                $openFileContent = self::PATH_MOD . ".{$key}.messages.{$this->_tl}";
                $contentList = include(Yii::getPathOfAlias($openFileContent) . DS . $file);
// foreach($contentList as $pkey=>$value){

                foreach ($contentList as $pkey => $val) {
                    $params[] = $val;
                    $num++;
                    $spec[$num] = $pkey;
                }

                $response = $t->translate(array($this->_sl, $this->_tl), $contentList);
                foreach ($response['text'] as $k => $v) {
                    $result[$spec[$k]] = $v;
                }


                $this->writeContent($path . DS . $file, $result);
            }
            die('finish ' . $key);
        }
        die('Complate');
    }

    public function actionIndex() {
        $this->pageName = Yii::t('app', 'TRANSLATES');
        $this->breadcrumbs = array(Yii::t('app', 'SYSTEM') => array('admin/index'), $this->pageName);

        $this->render('index');
    }

    public function actionAjaxGet() {
        $type = $_POST['type'];
        $tree = array();
        if ($type == 'core') {
            $view = '_ajaxGetCore';
            $tree = $this->getArray(Yii::getPathOfAlias(self::PATH_APP));
        } else {
            $view = '_ajaxGetModules';
            $tree = ModulesModel::getModules();
        }
        $this->render($view, array('tree' => $tree));
    }

    public function actionAjaxGetLocale() {
        $type = $_POST['type'];
        $mod = $_POST['module'];
        $array = array();
        if ($type == 'core') {
            $dir = Yii::getPathOfAlias(self::PATH_APP);
        } else {
            $dir = Yii::getPathOfAlias(self::PATH_MOD . '.' . $mod . '.messages');
        }
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file != "." && $file != "..")
                $array[$file] = $file;
        }
        $this->render('_ajaxGetLocale', array('array' => $this->getArray($dir)));
    }

    public function actionAjaxGetLocaleFile() {
        $mod = $_POST['module'];
        $this->_tl = $_POST['locale'];
        $type = $_POST['type'];
        if ($type == 'core') {
            $dir = Yii::getPathOfAlias(self::PATH_APP . '.' . $locale);
        } else {
            $dir = Yii::getPathOfAlias(self::PATH_MOD . '.' . $mod . '.messages.' . $this->_tl);
        }
        $this->render('_ajaxGetLocaleFile', array('tree' => $this->getArray($dir)));
    }

    public function actionAjaxOpen() {
        $this->_tl = $_POST['locale'];
        $mod = $_POST['module'];
        $file = $_POST['file'];
        $addonsLang = $_POST['lang'];
        $type = $_POST['type'];
        if ($type == 'modules') {
            $fullPath = self::PATH_MOD . '.' . $mod . '.messages';
            $dir = Yii::getPathOfAlias($fullPath . '.' . $this->_tl) . DS . $file;
        } else {
            $fullPath = self::PATH_APP;
            $dir = Yii::getPathOfAlias($fullPath . '.' . $this->_tl) . DS . $file;
        }

        if (isset($_POST['TranslateForm'])) {
            $trans = array();
            foreach ($_POST['TranslateForm'] as $key => $val) {
                if (is_array($val)) {
                    $param = array();
                    foreach ($val as $key2 => $value) {
                        $param[] = $key2 . '#' . $value;
                    }
                    $trans[stripslashes($key)] = implode('|', $param);
                } else {
                    $trans[stripslashes($key)] = $val;
                }
            }


            if (!empty($addonsLang)) {

                $this->createFileLanguage(Yii::getPathOfAlias($fullPath), $file, array($this->_tl, $addonsLang), $trans);
            }

            $this->writeContent($dir, $trans);
        }
        $return = include($dir);
        $this->render('_ajaxOpen', array('return' => $return, 'module' => $mod, 'locale' => $this->_tl, 'file' => $file, 'type' => $type));
    }

    public function actionAjaxApplication() {
        if (Yii::app()->request->getParam('lang')) {
            $filename = $_POST['file'];
            $fullpath = $_POST['path'];
            $this->generateMessages($filename, $fullpath, Yii::app()->request->getParam('lang'));
        } else {
            throw new Exception('Error no select language');
        }
    }

    public function createFileLanguage($fullpath, $filename, $langs = array(), $content = array()) {
        $this->_tl = $langs[1];
        $newLangPath = $fullpath . DS . $this->_tl;

        if (!is_dir($newLangPath)) {
            mkdir($newLangPath, 0750);
        }
        $t = new yandexTranslate;
        $fh = fopen($newLangPath . DS . $filename, "w");
        if (!is_resource($fh)) {
            return false;
        }
        fclose($fh);
        $params = array();
        $result = array();
        $spec = array();
        $num = -1;
        foreach ($content as $key => $val) {
            $params[] = $val;
            $num++;
            $spec[$num] = $key;
        }
        $response = $t->translate($langs, $params);
        foreach ($response['text'] as $k => $v) {
            $result[$spec[$k]] = $v;
        }
        $this->writeContent($newLangPath . DS . $filename, $result);
    }

    public function actionApplication() {
        $this->pageName = 'Перевод сайта';
        $this->breadcrumbs = array(
            Yii::t('app', 'SYSTEM') => array('admin/default'),
            Yii::t('app', 'LANGUAGES') => array('admin/languages'),
            $this->pageName
        );
        $this->render('application', array('lang' => Yii::app()->request->getParam('lang')));
    }

    private function generateMessages($file, $path, $locale) {
        $this->_tl = $locale;
// $this->_sl = 'ru';
        $t = new yandexTranslate;
        $result = array();
        $num = -1;
        $params = array();
        $pathDefault = Yii::getPathOfAlias("{$path}.{$this->_sl}");
        $newPath = Yii::getPathOfAlias("{$path}.{$this->_tl}");

        if (!file_exists($newPath)) {
            CFileHelper::copyDirectory($pathDefault, $newPath, array(
                'fileTypes' => array('php'),
                'level' => 1,
            ));
        }
        $contentList = require($newPath . DS . $file);

        foreach ($contentList as $pkey => $val) {
            $params[] = $val;
            $num++;
            $spec[$num] = $pkey;
        }

        $response = $t->translate(array($this->_sl, $this->_tl), $contentList, true);

        if ($response) {
            foreach ($response['text'] as $k => $v) {
                $result[$spec[$k]] = $v;
            }
            $this->writeContent($newPath . DS . $file, $result);
            echo CJSON::encode(array(
                'status' => 'success',
                'message' => 'ОК',
            ));
        } else {
            echo CJSON::encode(array(
                'status' => 'error',
                'message' => $result['message'],
            ));
        }
    }

    public function getArray($path) {
        $files = scandir($path);
        $tree = array();
        foreach ($files as $file) {
            if ($file != "." && $file != "..")
                $tree[$file] = $file;
        }
        return $tree;
    }

    /**
     * Запись перевода в файл.
     * 
     * @param string $filePath
     * @param array $content
     * @throws CException
     */
    private function writeContent($filePath, $content) {
        if (!@file_put_contents($filePath, '<?php

/**
 * Message translations. (auto translate)
 * 
 * Each array element represents the translation (value) of a message (key).
 * If the value is empty, the message is considered as not translated.
 * Messages that no longer need translation will have their translations
 * enclosed between a pair of \'@@\' marks.
 * 
 * @author CORNER CMS development team <dev@corner-cms.com>
 * @package modules.messages.' . $this->_tl . '
 */
return ' . var_export($content, true) . ';')) {
            throw new CException(Yii::t('app', 'Error write modules setting in {file}...', array('{file}' => $filePath)));
        }
    }

    /**
     * 
     * @param string $path
     */
    public function remove_old_lang_dir($path) {
        if (Yii::app()->request->getParam('lang')) {
            if (file_exists(Yii::getPathOfAlias($path))) {
                CFileHelper::removeDirectory(Yii::getPathOfAlias($path), array('traverseSymlinks' => true));
            }
        }
    }

}
