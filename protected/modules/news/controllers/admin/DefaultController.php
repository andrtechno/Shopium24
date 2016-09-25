<?php

/**
 * Контроллер админ-панели статичных страниц
 * 
 * @author CORNER CMS development team <dev@corner-cms.com>
 * @package modules.news.controllers.admin
 * @uses AdminController
 */
class DefaultController extends AdminController {

    public function actions() {
        return array(
            'switch' => array(
                'class' => 'ext.adminList.actions.SwitchAction',
            ),
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
            'saveImageAttachment' => 'ext.image-attachment.ImageAttachmentAction',
            'sortable' => array(
                'class' => 'ext.sortable.SortableAction',
                'model' => News::model(),
            )
        );
    }

    public function actionIndex() {
        $this->pageName = $this->module->name;
        $this->breadcrumbs = array($this->pageName);
        $model = new News('search');
        $model->unsetAttributes();
        if (!empty($_GET['News']))
            $model->attributes = $_GET['News'];

        $this->render('index', array('model' => $model));
    }

    /**
     * Create or update new page
     * @param boolean $new
     */
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new News;
        } else {
            $model = News::model()
                    ->findByPk($_GET['id']);
        }

        if (!$model)
            throw new CHttpException(404);


        $isNewRecord = ($model->isNewRecord) ? true : false;
        $this->breadcrumbs = array(
            $this->module->name => $this->createUrl('index'),
            ($model->isNewRecord) ? $model::t('PAGE_TITLE', 0) : CHtml::encode($model->title),
        );

        $this->pageName = ($model->isNewRecord) ? $model::t('PAGE_TITLE', 0) : $model::t('PAGE_TITLE', 1);

        $form = new TabForm($model->getForm(), $model);
        $form->additionalTabs[$model::t('TAB_IMG')] = array(
            'content' => $this->renderPartial('_image', array('model' => $model), true)
        );
        $form->additionalTabs[Yii::t('app','TAB_META')] = array(
            'content' => $this->renderPartial('mod.seo.views.admin.default._module_seo', array('model' => $model, 'form' => $form), true)
        );

        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['News'];
            if ($model->validate()) {
                $model->save();
     $this->redirect(array('index'));
                /* if(!$this->edit_mode){
                  if($isNewRecord){
                  $this->redirect(array('update','id'=>$model->id));
                  }else{

                  $this->redirect(array('index'));

                  }
                  } */
            }
        }
        $this->render('update', array('model' => $model, 'form' => $form));
    }

    public function getAddonsMenu() {
        return array(
            array(
                'label' => Yii::t('app', 'SETTINGS'),
                'url' => array('/admin/news/settings/index'),
                'icon' => 'flaticon-settings',
                'visible' => Yii::app()->user->isSuperuser
            ),
        );
    }

}
