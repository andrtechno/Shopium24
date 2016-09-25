<?php

class DefaultController extends AdminController {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    public function actionCreate() {
        $model = new SeoUrl;
        if (isset($_POST['SeoUrl'])) {
            $model->attributes = $_POST['SeoUrl'];
            if ($model->save()) {
                $this->redirect(array("index"));
            }
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id) {
        $model = $this->loadModel($id);

        if (isset($_POST['SeoUrl'])) {
            $model->attributes = $_POST['SeoUrl'];
            /* update url */
            if ($model->save()) {
                $this->saveParams($model);
                $this->redirect(array("index"));
            }
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    protected function saveParams($model) {
        $dontDelete = array();

        if (!empty($_POST['param'])) {
            foreach ($_POST['param'] as $main_id => $object) {
                // echo '<pre>'.CVarDumper::dumpAsString($object).'</pre>';


                $i = 0;
                foreach ($object as $key => $item) {
                    $variant = SeoParams::model()->findByAttributes(array(
                        'main_id' => $main_id,
                        'param' => $item,
                        'obj' => $key
                    ));
                    // If not - create new.
                    if (!$variant)
                        $variant = new SeoParams();

                    $variant->setAttributes(array(
                        'url_id' => $main_id,
                        'param' => $item,
                        'obj' => $key,
                            ), false);

                    $variant->save(false, false, false);
                    array_push($dontDelete, $variant->id);
                    $i++;
                }


                if (!empty($dontDelete)) {
                    $cr = new CDbCriteria;
                    $cr->addNotInCondition('id', $dontDelete);
                    $cr->addCondition('main_id=' . $main_id);
                    SeoParams::model()->deleteAll($cr);
                } else
                    SeoParams::model()->deleteAllByAttributes(array('main_id' => $main_id));
            }
        }
        //   die;
    }

    /**
     * Manages all models.
     */
    public function actionIndex() {

        $model = new SeoUrl('search');
        $model->unsetAttributes();
        $this->pageName = Yii::t('SeoModule.default', 'MODULE_NAME');
        if (isset($_GET['SeoUrl'])) {
            $model->attributes = $_GET['SeoUrl'];
        }

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id) {
        $model = SeoUrl::model()->findByPk($id);

        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Получение списка всех моделей в проекте
     */
    public function getModels() {
        $file_list = array();
        //путь к директории с проектами
        //$file_list = scandir(Yii::getPathOfAlias('application.modules.news.models'));
        //$file_list = scandir(Yii::getPathOfAlias('mod.*.models'));
        $models = null;
        foreach (Yii::app()->getModules() as $mod => $obj) {
            //echo $mod;
            if (!in_array($mod, array('core', 'admin', 'rights', 'seo', 'users'))) {
                if (file_exists(Yii::getPathOfAlias("mod.{$mod}.models"))) {
                    $file_list[$mod] = scandir(Yii::getPathOfAlias("mod.{$mod}.models"));
                }
            }


            //если найдены файлы
            if (count($file_list[$mod])) {
                foreach ($file_list[$mod] as $file) {
                    if ($file != '.' && $file != '..' && !preg_match('/Translate/', $file)) {// исключаем папки с назварием '.' и '..'
                        // Yii::import("mod.{$mod}.models.{$file}");
                        $ext = explode(".", $file);
                        $model = $ext[0];
                        //if (new $model instanceof ActiveRecord) {
                        //проверяем чтобы модели были с расширением php
                        if ($ext[1] == "php") {
                            $models[] = array(
                                'model' => $model,
                                'path' => "mod.{$mod}.models"
                            );
                            //  $models[] = "mod.{$mod}.models";
                        }
                        // }
                    }
                }
            }
        }

        return $models;
    }

    /**
     * Получение списка артибутов всех моделей
     */
    public function getParams() {
        //загружаем модели
        $models = $this->getModels();
        $params = null;
        $i = 0;

        if (count($models)) {
            foreach ($models as $model) {
                $mdl = $model['model'];
                if($mdl instanceof ActiveRecord || $mdl instanceof CActiveRecord){
                   
               
                Yii::import("{$model['path']}.{$mdl}");
                //if($mdl!='ShopCategoryNode'){
                $modelNew = new $mdl(null);
                 }
               // }
                /* проверяем существует ли в данном классе функция "tableName"
                 * если она существует, то скорее всего эта модель CActiveRecord
                 * таким образом отсеиваем модели, которые были предназначены для валидации форм не работающих с Базой Данных
                 */
                if (method_exists($modelNew, "tableName")) {

                    $tableName = $modelNew->tableName();

                    if (($table = $modelNew->getDbConnection()->getSchema()->getTable($tableName)) !== null) {
                        $item = new $mdl;

                        foreach ($item as $attr => $val) {
                            $params[$i]['group'] = $mdl;
                            $params[$i]['name'] = $attr;
                            $params[$i++]['value'] = $mdl . '/' . $attr;
                        }
                        /*
                         * проверяем есть ли связи у данной модели
                         */
                        if (method_exists($item, "relations")) {
                            if (count($item->relations())) {
                                $relation = $item->relations();
                                foreach ($relation as $key => $rel) {
                                    /*
                                     * выбираем связи один к одному или многие к одному
                                     */
                                    if (($rel[0] == "CHasOneRelation") || ($rel[0] == "CBelongsToRelation")) {
                                        $newRel = new $rel[1];
                                        foreach ($newRel as $attr => $nR) {
                                            $params[$i]['group'] = $mdl;
                                            $params[$i]['name'] = $key . "." . $attr;
                                            $params[$i++]['value'] = $mdl . "/" . $key . "." . $attr;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            /*
             * если есть модели работающие с базой то возвращаем массив данных
             * иначе возвращаем пустой массив
             */
            if (count($params))
                return $params;
            else
                return array();
        } else
            return array();
    }

    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('app', 'SETTINGS'),
                'url' => array('/admin/seo/settings'),
                'icon' => 'flaticon-settings',
                'visible'=>true
            ),
            array(
                'label' => Yii::t('app', 'Redirect'),
                'url' => array('/admin/seo/redirects'),
                'icon' => 'flaticon-refresh',
                'visible'=>true
            ),
        );
    }

}
