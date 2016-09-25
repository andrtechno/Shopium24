<?php

/**
 * Приложение системы.
 * 
 * @uses CWebApplication
 * @author CORNER CMS development team <dev@corner-cms.com>
 * @package components
 */
class Application extends CWebApplication {

    private $_theme = null;

    public function getVersion() {
        return '0.8b';
    }

    public function getCopyright() {
        return Yii::t('app', 'COPYRIGHT_APP', array(
                    '{year}' => date('Y'),
                    '{v}' => Yii::app()->getVersion(),
                    '{site_name}' => Html::link('CORNER CMS', '//corner-cms.com', array('title' => 'CORNER CMS', 'target' => '_blank'))
        ));
    }

    /**
     * @param null $config
     */
    public function __construct($config = null) {
        parent::__construct($config);
    }

    public function intallComponent($name, $class) {

        if (isset($class) && isset($name)) {
            if (ComponentsModel::model()->countByAttributes(array('name' => $name)) == 0) {
                // if (file_exists(Yii::getPathOfAlias($alias_wgt) . '.php')) {
                $w = new ComponentsModel();
                $w->class = $class;
                $w->name = $name;
                if ($w->validate()) {
                    if (!$w->save(false, false)) {
                        die('error save');
                    }
                } else {
                    throw new CException(Yii::t('exception', 'SET_WIDGET_ERR_VALID', array('{name}' => $name, '{class}' => $class)));
                }
                // } else {
                //     throw new CException(Yii::t('app', 'SET_WIDGET_NOTFOUND'));
                // }
            } else {
                throw new CException(Yii::t('exception', 'SET_WIDGET_ALREADY_EXISTS'));
            }
        } else {
            throw new CException(Yii::t('exception', 'SET_WIDGET_ERR'));
        }
    }

    public function unintallComponent($name) {
        if (isset($name)) {
            if ($this->getComponent($name)) {
                $c = ComponentsModel::model()->findByAttributes(array('name' => $name));
                if (isset($c)) {
                    $c->delete();
                } else {
                    throw new CException(Yii::t('exception', 'ERR2'));
                }
            }
        } else {
            throw new CException(Yii::t('exception', 'ERR'));
        }
    }

    /**
     * Initialize component
     */
    public function init() {
        $this->setEngineModules();
        parent::init();
    }

    /**
     * Set enabled system modules to enable url access.
     */
    protected function setEngineModules() {
        $mods = ModulesModel::getEnabled();
        if ($mods) {
            foreach ($mods as $module) {
                $this->setModules(array($module->name => array(
                        'access' => $module->access
                    )
                ));
            }
        }
    }

    /**
     * @return CTheme
     */
    public function getTheme() {
        $globConfig = Yii::app()->settings->get('app');
        if ($this->_theme === null) {
            if (Yii::app()->settings->get('users', 'change_theme')) {
                if (isset(Yii::app()->user->theme)) {
                    $theme = Yii::app()->user->theme;
                } else {
                    $theme = $globConfig['theme'];
                }
            } else {
                $theme = $globConfig['theme'];
            }
            $this->_theme = Yii::app()->themeManager->getTheme($theme);
        }
        return $this->_theme;
    }

}
