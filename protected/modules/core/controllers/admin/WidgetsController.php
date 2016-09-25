<?php

class WidgetsController extends AdminController {

    const CHACHEID = 'widgets_cache';

    public function actionIndex() {

        $this->pageName = Yii::t('app', 'WIDGETS');
        $this->breadcrumbs = array(Yii::t('app', 'SYSTEM') => array('admin/default'), $this->pageName);


        $result = Yii::app()->cache->get(self::CHACHEID);
        if ($result === false) {
            $result = array();

            $extPath = Yii::getPathOfAlias("ext.blocks");
            $files = CFileHelper::findFiles($extPath, array(
                        'fileTypes' => array('php'),
                        'level' => 1,
                        'absolutePaths' => false,
            ));
            Yii::import('app.blocks_settings.*');
            $manager = new WidgetSystemManager;
            foreach ($files as $obj) {

                $obj = explode(DIRECTORY_SEPARATOR, $obj);


                $className = str_replace('.php', '', $obj[1]);
                $classDir = $obj[0];

                if (file_exists(Yii::getPathOfAlias("ext.blocks.{$classDir}") . DS . $obj[1])) {
                    Yii::import("ext.blocks.{$classDir}.{$className}");
                    if (new $className instanceof BlockWidget) {
                        $class = new $className;

                        Yii::import('app.blocks_settings.*');
                        $manager = new WidgetSystemManager;

                        $system = $manager->getClass("ext.blocks.{$classDir}", $className);

                        if (!$system) {
                            $edit = false;
                        } else {
                            $edit = true;
                        }


                        $result[] = array(
                            'title' => $class->getTitle(),
                            'alias' => "ext.blocks.{$classDir}.{$className}",
                            'category' => 'ext',
                            'edit' => ($edit) ? Html::link(Yii::t('app','UPDATE',0), array('/admin/core/widgets/update', 'alias' => "ext.blocks.{$classDir}.{$className}"),array('class'=>'btn btn-default')) : ''
                        );
                    }
                }
            }




            /* start modules widget parse */
            foreach (Yii::app()->getModules() as $mod => $module) {
                if (file_exists(Yii::getPathOfAlias("mod.{$mod}.blocks"))) {
                    $modulesfile = CFileHelper::findFiles(Yii::getPathOfAlias("mod.{$mod}.blocks"), array(
                                'fileTypes' => array('php'),
                                'level' => 1,
                                'absolutePaths' => false
                    ));
                }
                foreach ($modulesfile as $obj) {

                    $obj = explode(DIRECTORY_SEPARATOR, $obj);


                    $className = str_replace('.php', '', $obj[1]);
                    $classDir = $obj[0];
                    if (file_exists(Yii::getPathOfAlias("mod.{$mod}.blocks"))) {
                        if (file_exists(Yii::getPathOfAlias("mod.{$mod}.blocks.{$classDir}") . DS . $obj[1])) {
                            Yii::import("mod.{$mod}.blocks.{$classDir}.{$className}");
                            if (new $className instanceof BlockWidget) {
                                $class = new $className;



                                $system = $manager->getClass("mod.{$mod}.blocks.{$classDir}", $className);

                                if (!$system) {
                                    $edit = false;
                                } else {
                                    $edit = true;
                                }


                                $result[] = array(
                                    'title' => $class->getTitle(),
                                    'alias' => "mod.{$mod}.blocks.{$classDir}.{$className}",
                                    'category' => 'module',
                                    'edit' => ($edit) ? Html::link(Yii::t('app','UPDATE',0), array('/admin/core/widgets/update', 'alias' => "mod.{$mod}.blocks.{$classDir}.{$className}"),array('class'=>'btn btn-default')) : ''
                                );
                            }
                        }
                    }
                }
            }
            Yii::app()->cache->set(self::CHACHEID, $result, 3600*12);
        }
        $data_db = new CArrayDataProvider($result, array(
            'sort' => array(
                'attributes' => array('alias', 'category', 'title'),
                'defaultOrder' => array('alias' => false),
            ),
            'pagination' => array(
                'pageSize' => Yii::app()->settings->get('app', 'pagenum'),
            ),
                )
        );
        $this->render('index', array('data_db' => $data_db));



















        // print_r($result);
        die;
    }

    public function actionUpdate($alias) {
        $this->pageName = Yii::t('app', 'WIDGETS');
        $this->breadcrumbs = array(
            $this->pageName => array('/admin/core/widgets2'),
            Yii::t('app', 'UPDATE', 1)
        );

        Yii::import('app.blocks_settings.*');
        //   $systemId = Yii::app()->request->getQuery('alias');

        if (empty($alias))
            exit;
        $manager = new WidgetSystemManager;
        $system = $manager->getSystemClass($alias);

        if (!$system) {
            Yii::app()->user->setFlash('error', 'Виджет не использует конфигурации');
            $this->redirect(array('index'));
        }

        if (Yii::app()->request->isPostRequest) {
            if ($system) {
                Yii::app()->user->setFlash('success', Yii::t('app', 'SUCCESS_UPDATE'));
                $system->saveSettings($alias, $_POST);
            }
        }
        $this->render('update', array('form' => $system->getConfigurationFormHtml($alias)));
    }

}
