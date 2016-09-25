<?php

class NewsModule extends WebModule {

    public $edit_mode = true;
    public $_addonsArray;

    public function init() {
        $this->setImport(array(
            $this->id . '.models.*'
        ));
        $this->setIcon('flaticon-newspaper');
    }

    public function afterInstall() {
        Yii::app()->database->import($this->id);
        Yii::app()->settings->set($this->id, SettingsNewsForm::defaultSettings());
        CFileHelper::createDirectory(Yii::getPathOfAlias("webroot.uploads.{$this->id}"), 0777);
        return parent::afterInstall();
    }

    public function afterUninstall() {
        //Удаляем таблицу модуля
        Yii::app()->db->createCommand()->dropTable(News::model()->tableName());
        Yii::app()->db->createCommand()->dropTable(NewsTranslate::model()->tableName());
        CFileHelper::removeDirectory(Yii::getPathOfAlias("webroot.uploads.{$this->id}"), array('traverseSymlinks' => true));
        Yii::app()->settings->clear($this->id);
        return parent::afterUninstall();
    }

    public function getRules() {
        return array(
            'scrollpager' => 'news/default/scrollpager',
            'newstest' => 'news/default/test',
            'news' => 'news/default/index',
            // 'news/append/<append>/page/<page>' => 'news/default/index',
            'news/<seo_alias>' => 'news/default/view',
            // 'news/category/<category>' => 'news/default/index',
            'news/default/upload' => 'news/default/upload',
                /* 'news/<tag:.*?>' => 'news/default/index', */
                //    'news/page/<page:\d+>' => 'news/default/index', 
        );
    }

    public function getAdminMenu() {
        return array(
            'modules' => array(
                'items' => array(
                    array(
                        'label' => $this->name,
                        'url' => $this->adminHomeUrl,
                        'icon' => $this->icon,
                        'active' => self::hasActive('admin/news') && self::hasActiveModule('news'),
                        'visible' => Yii::app()->user->isSuperuser
                    ),
                ),
            ),
        );
    }

    public function getAddonsArray() {
        return array(
            'mainButtons' => array(
                array(
                    'label' => Yii::t('NewsModule.default', 'CREATE'),
                    'url' => '/admin/news/default/create',
                    'icon' => Yii::app()->getModule('news')->icon
                )
            )
        );
    }

}
