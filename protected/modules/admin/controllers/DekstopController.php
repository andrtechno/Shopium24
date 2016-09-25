<?php

class DekstopController extends AdminController {

    public $topButtons = false;

    public function actionUpdate($new = false) {
        $model = ($new === true) ? new Dekstop : Dekstop::model()->findByPk($_GET['id']);
        $pageName = ($new === true) ? Yii::t('app', 'CREATE', 1) : $model->name;
        $this->pageName = $pageName;
        $this->breadcrumbs = array($this->pageName);

        if (isset($model)) {
            if (isset($_POST['Dekstop'])) {
                $model->attributes = $_POST['Dekstop'];
                
                if ($model->validate()) {
                    $model->save();
                    $this->redirect('/admin/?d=' . $model->id);
                }
            }
            $this->render('update', array('model' => $model));
        } else {
            throw new CHttpException(404);
        }
    }

    public function actionCreateWidget() {

        header('Content-Type: application/json');
        $response=array();
        $model = new DekstopWidgets;
        if (isset($_POST['DekstopWidgets'])) {
            $model->attributes = $_POST['DekstopWidgets'];
            $model->widget_id = $_POST['DekstopWidgets']['widget_id'];
            try {
                if ($model->validate()) {
                    $model->save();
                    $response['success']=true;
                    //$this->redirect(array('/admin'));
                }
            } catch (Exception $e) {
                print_r($e);
                $response['success']=false;
                $model->addError('widget_id', $model::t('ERROR'));
            }
        }
        Yii::app()->cache->flush();
        $response['content']=$this->renderPartial('widget_create', array('model' => $model), true, false);
        echo CJSON::encode($response);
        //$this->render('widget_create', array('model' => $model), false, true);
    }

    public function actionUpdateColumns() {

        $columnTO = $_POST['columnTo'];
        $columnFROM = $_POST['columnFrom'];
        $ordern = $_POST['ordern'];
        $model = DekstopWidgets::model()->findAllByPk($ordern);
        $count = 1;
        foreach ($model as $key => $block) {
            $block->ordern = $ordern[$key];
            if (isset($columnFROM) && isset($columnTO)) {
                $block->column = $columnTO;
                echo $columnTO;
            }

            $block->update();
            $count++;
        }
    }
    
    /**
     * @param $id
     */
    public function actionDeleteWidget($id) {
        if (Yii::app()->request->isPostRequest) {
            $model = DekstopWidgets::model()->findByPk($id);
            if(isset($model)){
                   $model->delete();
            }
            if (!Yii::app()->request->isAjaxRequest)
                $this->redirect('admin');
        }
    }
    
   // private function detectID($alias){
        //$model = WidgetsModel::model()->findByAttributes(array('alias_wgt'=>$alias));
      //  return $model->id;
   // }
}
