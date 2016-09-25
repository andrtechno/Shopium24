<?php

/**
 * Контроллер профиля пользователей.
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users.controllers
 * @uses Controller
 */
class ProfileController extends Controller {

    public function actions() {
        return array(
            'widget.' => 'mod.users.widgets.webcam.Webcam',
            'getAvatars' => array(
                'class' => 'mod.users.actions.AvatarAction',
            ),
            'saveAvatar' => array(
                'class' => 'mod.users.actions.SaveAvatarAction',
            ),
        );
    }

    public function actionActiveUser($key) {
        if (User::activeKey($key) === true) {

            // $componentPlan = new CreateUserPlan;
           // $componentPlan->shop_register(Yii::app()->user->getModel());
           /* $prefix = CMS::gen(5) . '_';
            $user = Yii::app()->user->getModel();
            $componentPlan = new CreateUserPlan;
            $domain = $componentPlan->APIHosting_create_subdomain($user);
            if ($domain->response->status == 'success') {
                $componentPlan->extractPlan($user);
                $db = $componentPlan->APIHosting_create_db($user);
                if ($db->response->status == 'success') {
                    $componentPlan->editConfigFile($user, $db->response->data->user, $prefix);
                    $dbconnect = array(
                        'dsn' => 'mysql:host=' . $componentPlan::$db_host . ';dbname=' . $db->response->data->user->login . ';charset=UTF8',
                        'username' => $db->response->data->user->login,
                        'password' => $db->response->data->user->password,
                        'prefix' => $prefix
                    );
                    Yii::app()->database->importPlanSql($user, $dbconnect);
                    $componentPlan->addUser($user, $dbconnect);
                }
            }*/

            Yii::app()->user->setFlash('success', 'Ваш аккаунт успешно активирован.');
            $this->redirect(array('/users/profile'));
        } else {
            // die('Ошибка активации аккаунта.');
            Yii::app()->user->setFlash('error', 'Ошибка активации аккаунта');
            $this->redirect(array('/users/profile'));
        }
    }

    public function filters() {
        return array(
            'ajaxOnly + addFriend',
        );
    }

    /**
     * Check if user is authenticated
     * @return bool
     * @throws CHttpException

      public function beforeAction($action) {
      if (Yii::app()->user->isGuest)
      throw new CHttpException(404, Yii::t('UsersModule.core', 'Ошибка доступа.'));
      return true;
      } */
    /**
     * Display user orders
     */

    /**
     * Display profile start page
     */
    public function actionIndex() {
        if (!Yii::app()->user->isGuest) {
            $this->pageName = Yii::t('UsersModule.default', 'PROFILE');
            $this->pageTitle = $this->pageName;
            $this->breadcrumbs = array($this->pageName);

            Yii::import('mod.users.forms.ChangePasswordForm');
            Yii::import('mod.users.forms.PaymentForm');
            $paymentForm = new PaymentForm();
            $request = Yii::app()->request;
            $user = Yii::app()->user->getModel();
            $oldAvatar = $user->avatar;

            $changePasswordForm = new ChangePasswordForm();

            $changePasswordForm->user = $user;

            if (Yii::app()->request->isAjaxRequest) {
                if ($request->getPost('PaymentForm')) {
                    echo CActiveForm::validate($paymentForm);
                } elseif ($request->getPost('User')) {
                    echo CActiveForm::validate($user);
                }
                Yii::app()->end();
            }
            if (isset($_POST['User'])) {
                $user->attributes = $_POST['User'];

                if ($user->validate()) {

                    $user->saveImage('avatar', 'webroot.uploads.users.avatar', $oldAvatar);
                    $user->save();

                    //TODO сделать добавление для суб-домена, псевдодоменов
                    // $this->APIHosting_create_domain($user);
                    // $this->refresh();
                }
            }

            if ($request->getPost('ChangePasswordForm')) {
                $changePasswordForm->attributes = $request->getPost('ChangePasswordForm');
                if ($changePasswordForm->validate()) {
                    $user->password = User::encodePassword($changePasswordForm->new_password);
                    if ($user->save(false, false, false)) {
                        $forum = new CIntegrationForums;
                        $forum->changepassword($user->login, $changePasswordForm->new_password, $user->email);
                    }
                    $this->addFlashMessage(Yii::t('UsersModule.default', 'Пароль успешно изменен.'));
                    $this->redirect('post/read', array('#' => 'chagepass'));
                }
            }

            $uConfig = Yii::app()->settings->get('users');


            if ($request->getPost('PaymentForm')) {
                $paymentForm->attributes = $request->getPost('PaymentForm');
                if ($paymentForm->validate()) {
                    
                }
            }

            $this->render('index', array(
                'user' => $user,
                // 'tabs' => $tabs,
                'changePasswordForm' => $changePasswordForm,
                'paymentForm' => $paymentForm
            ));
        } else {
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    /**
     * Display user orders
     */
    public function actionOrders() {
        if (Yii::app()->request->isAjaxRequest) {
            $min = (YII_DEBUG) ? '' : '.min';
            Yii::app()->clientScript->scriptMap["jquery{$min}.js"] = false;

        }
        Yii::import('mod.cart.models.*');
        Yii::import('mod.cart.CartModule');
        $this->pageName = Yii::t('UsersModule.core', 'Мои заказы');
        $this->pageTitle = $this->pageName;
        $orders = new Order('search');
        $orders->unsetAttributes();
        if (!empty($_GET['Order']))
            $orders->attributes = $_GET['Order'];
        $orders->user_id = Yii::app()->user->getId();

        $this->render('orders', array(
            'orders' => $orders,
        ));
    }

    public function actionUserInfo($user_id) {
        $user = User::model()->findByPk((int) $user_id);

        if (isset($user)) {
            //if(!$user->banned){
            $this->render('user_info', array('user' => $user));
            //}else{
            // $this->redirect('/');
            //    Yii::app()->tpl->alert('info','ban');
            //}
        } else {
            $this->redirect('/');
        }
    }

    public function APIHosting_create_domain($user) {
        if (isset($user->domain)) {
            Yii::import('app.hosting_api.*');
            $api = new APIHosting('hosting_site', 'site_create', array(
                        'site' => $user->domain,
                    ));
            return $api->callback(false);
        }
    }

    public function actionSaveAvatar22() {


        $collection = (isset($_POST['collection'])) ? $_POST['collection'] : $_GET['collection'];
        $avatar = (isset($_POST['img'])) ? $_POST['img'] : $_GET['img'];

        if (!Yii::app()->user->isGuest) {
            $user_id = intval(Yii::app()->user->id);

            $user = User::model()->findByPk($user_id);

            if ($user->validate()) {
                $user->avatar = $avatar;
                $user->save();


                //$this->redirect('/users/profile');
            } else {
                die(print_r($user->getErrors()));
            }
        } else {

            $this->redirect('/users/profile');
        }
    }

    public function actionGetAvatars2222() {
        $collection = $_POST['collection'];
        $avatars = array();
        if (!Yii::app()->user->isGuest && $collection) {


            $dir = opendir(Yii::getPathOfAlias('webroot.uploads.users.avatars') . '/' . $collection);
            while ($entry = readdir($dir)) {
                if (preg_match("/(\.gif|\.png|\.jpg|\.jpeg)$/is", $entry) && $entry != "." && $entry != "..") {
                    //  $entryname = str_replace("_", " ", preg_replace("/^(.*)\..*$/", "\\1", $entry));
                    $avatars[] = $entry;
                }
            }
            closedir($dir);


            $this->render('_avatar_collections', array('avatars' => $avatars, 'collection' => $collection));
        } else {
            //redirect
        }
    }

}
