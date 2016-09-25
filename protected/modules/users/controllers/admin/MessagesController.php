<?php

/**
 * Контроллер сообщение пользователей
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users.controllers.admin
 * @uses AdminController
 */
class MessagesController extends AdminController {

    public function actions() {
        return array(
            'delete' => array(
                'class' => 'ext.adminList.actions.DeleteAction',
            ),
        );
    }

    public function actionIndex() {
        $model = new UserMessages('search');
        $model->unsetAttributes();

        if (!empty($_GET['UserMessages']))
            $model->attributes = $_GET['UserMessages'];

        $dataProvider = $model->search();

        $this->render('list', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Create new user
     */
    public function actionCreate() {
        $this->actionUpdate(true);
    }

    /**
     * Create/update user
     * @param boolean $new
     */
    public function actionUpdate($new = false) {
        if ($new === true) {
            $model = new UserMessages;
        }
        else
            $model = UserMessages::model()->findByPk($_GET['id']);

        if (!$model)
            throw new CHttpException(400, 'Bad request.');

        $form = new CMSForm($model->config, $model);
        $form['user']->model = $model;
        if (Yii::app()->request->isPostRequest) {
            $model->attributes = $_POST['UserMessages'];
            if ($model->validate()) {
                $model->save();
                $this->setFlashMessage(Yii::t('app', 'SUCCESS_SAVE'));
                if (isset($_POST['REDIRECT']))
                    $this->smartRedirect($model);
                else
                    $this->redirect(array('index'));
            }
        }

        $this->render('update', array(
            'model' => $model,
            'form' => $form,
        ));
    }

    public function actionSend($to_user) {
        if (isset($to_user)) {
            if ($to_user != Yii::app()->user->id) {
                $model = new UserMessages;
                if (Yii::app()->request->isPostRequest) {
                    $model->attributes = $_POST['UserMessages'];
                    $model->to_user = $to_user;
                    if ($model->validate()) {


                        $model->save();


                        $this->setFlashMessage(Yii::t('app', 'MESSAGE_SEND'));
                        //$this->redirect(array('index'));
                    }
                }

            /*    $pm = UserMessages::model()->findAll(array(
                    'condition' => 'to_user=:tid AND from_user=:fid',
                    'params' => array(
                        ':tid' => 2,
                        ':fid' => 1
                    )
                ));

                $pm2 = UserMessages::model()->findAll(array(
                    'condition' => 'to_user=:tid AND from_user=:fid',
                    'params' => array(
                        ':tid' => 1,
                        ':fid' => 2
                    )
                ));

           //     $massArray = CMap::mergeArray($pm, $pm2);
                // $pm = UserMessages::model()->findByAttributes($cr);
$massArray= UserMessages::model()->findAll(array('condition' =>'dialogID="V6WhfpU6QKY2O5uvsxVpADRXUL2BrS8WRWhnTuv3"'));
 */            //   $this->render('send', array('model' => $model, 'pmessages' => $massArray));
 $this->render('send');
            } else {
                throw new Exception('Нельзя отправить письмо самому себе.');
            }
        }
    }

    public function actionShow($to_user, $from_user) {

        if (isset($to_user) && isset($from_user)) {
            $cr = new CDbCriteria;


            $cr->addCondition('t.to_user = :tid');
            // $cr->addCondition('t.from_user = :fid');
            // $cr->addInCondition();
            //$cr->addCondition('t.deleted_by <> :deleted_by_receiver OR t.deleted_by IS NULL');
            $cr->order = 't.date_create DESC';
            $cr->params = array(
                'tid' => $to_user,
                    //':fid' => (int) $from_user
                    //'deleted_by_receiver' => Message::DELETED_BY_RECEIVER,
            );

            /* $cr->group = "`t`.`to_user`, `t`.`from_user`";
              //$cr->condition='`t`.`to_user`=:tid AND `t`.`from_user`=:fid';
              $cr->params = array(
              ':tid' => (int) $to_user,
              ':fid' => (int) $from_user
              );

              $model = UserMessages::model()->findAllByAttributes(array('from_user'=>$from_user,'to_user'=>$to_user)); */
            $model = UserMessages::model()->findAll($cr);


            $this->render('show', array('model' => $model));
        } else {
            throw new CHttpException(404);
        }
    }

}