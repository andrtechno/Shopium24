<?php

/**
 * Контролле регистрации пользовотеля
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users.controllers
 * @uses Controller
 */

class RegisterController extends Controller {
    //public function allowedActions() {
    //    return 'register';
    //}

    /**
     * Дополнительные действия
     * @return array
     */
    public function actions() {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'transparent' => true,
                'testLimit' => 1,
                'padding' => 0,
                'height' => 40
            //'foreColor' => 0x348017
            ),
        );
    }

    /**
     * Действие регистрации пользователя.
     */
    public function actionRegister() {
        Yii::import('app.hosting_api.APIHosting');
        // die('Извините, регистрация временно приостановлена');

        $config = Yii::app()->settings->get('users');
        if (!Yii::app()->user->isGuest || !$config['registration'])
            Yii::app()->request->redirect('/');

        $user = new User('register');
        $this->pageName = Yii::t('UsersModule.default', 'REGISTRATION');
        $this->pageTitle = $this->pageName;
        $this->breadcrumbs = array($this->pageName);
        $view = 'register';


        $plan =new CreateUserPlan;


        if (Yii::app()->request->isAjaxRequest) {
            echo CActiveForm::validate($user);
            Yii::app()->end();
        }
        if (!empty($_GET['User']['plan']))
            $user->attributes = $_GET['User'];
        if (Yii::app()->request->isPostRequest && isset($_POST['User'])) {
            $user->attributes = $_POST['User'];
            if (Yii::app()->settings->get('app', 'forum') == null)
                $user->email = $user->login;
            $user->active = ($config['register_nomail']) ? 1 : 0;
           // $user->expired = date('Y-m-d H:i:s', strtotime('+2 week', strtotime(date('Y-m-d H:i:s'))));
            if ($user->validate()) {
                  //$prefix = CMS::gen(5) . '_';
                if ($user->save()) {
                 


                  /*  $domain = $plan->APIHosting_create_subdomain($user);
                    if ($domain->response->status == 'success') {
                        $plan->extractPlan($user);
                    }
                    $db = $plan->APIHosting_create_db($user);
                    if ($db->response->status == 'success') {
                        $plan->editConfigFile($user, $db->response->data->user, $prefix);
                        $dbconnect = array(
                            'dsn' => 'mysql:host=' . $plan::$db_host . ';dbname=' . $db->response->data->user->login . ';charset=UTF8',
                            'username' => $db->response->data->user->login,
                            'password' => $db->response->data->user->password,
                            'prefix' => $prefix
                        );
                        Yii::app()->database->importPlanSql($user, $dbconnect);
                        $plan->addUser($user, $dbconnect);
                    }*/

                    $plan->shop_register($user);
                    $this->sendMail($user);
                    Yii::app()->authManager->assign('Authenticated', $user->id);
                    CIntegrationForums::instance()->register($user->login, $_POST['User']['password'], $user->email);
                }
                // Add user to authenticated group

                $this->addFlashMessage(Yii::t('UsersModule.default', 'REG_SUCCESS'));
                // Authenticate user
                $identity = new EngineUserIdentity($user->login, $_POST['User']['password']);
                if ($identity->authenticate()) {
                     
                    Yii::app()->user->login($identity, Yii::app()->user->rememberTime);
                    if ($config['register_nomail']) {
                        Yii::app()->request->redirect($this->createUrl('/users/profile/index'));
                    } else {
                        //  $this->sendMail($user);
                        $view = 'success_register';
                    }
                } else {
                    die('authenticate(): Error');
                }
            }
        }

        $this->render($view, array(
            'user' => $user
        ));
    }
    
    
    
    public function actionRegisterOLD() {
        Yii::import('app.hosting_api.APIHosting');
        // die('Извините, регистрация временно приостановлена');

        $config = Yii::app()->settings->get('users');
        if (!Yii::app()->user->isGuest || !$config['registration'])
            Yii::app()->request->redirect('/');

        $user = new User('register');
        $this->pageName = Yii::t('UsersModule.default', 'REGISTRATION');
        $this->pageTitle = $this->pageName;
        $this->breadcrumbs = array($this->pageName);
        $view = 'register';


        $plan =new CreateUserPlan;


        if (Yii::app()->request->isAjaxRequest) {
            echo CActiveForm::validate($user);
            Yii::app()->end();
        }
        if (!empty($_GET['User']['plan']))
            $user->attributes = $_GET['User'];
        if (Yii::app()->request->isPostRequest && isset($_POST['User'])) {
            $user->attributes = $_POST['User'];
            if (Yii::app()->settings->get('app', 'forum') == null)
                $user->email = $user->login;
            $user->active = ($config['register_nomail']) ? 1 : 0;
            $user->expired = date('Y-m-d H:i:s', strtotime('+2 week', strtotime(date('Y-m-d H:i:s'))));
            if ($user->validate()) {
                  $prefix = CMS::gen(5) . '_';
                if ($user->save()) {
                    $domain = $plan->APIHosting_create_subdomain($user);
                    if ($domain->response->status == 'success') {
                        $plan->extractPlan($user);
                    }
                    $db = $plan->APIHosting_create_db($user);
                    if ($db->response->status == 'success') {
                        $plan->editConfigFile($user, $db->response->data->user, $prefix);
                        $dbconnect = array(
                            'dsn' => 'mysql:host=' . $plan::$db_host . ';dbname=' . $db->response->data->user->login . ';charset=UTF8',
                            'username' => $db->response->data->user->login,
                            'password' => $db->response->data->user->password,
                            'prefix' => $prefix
                        );
                        Yii::app()->database->importPlanSql($user, $dbconnect);
                        $plan->addUser($user, $dbconnect);
                    }


                    $this->sendMail($user);
                    Yii::app()->authManager->assign('Authenticated', $user->id);
                    CIntegrationForums::instance()->register($user->login, $_POST['User']['password'], $user->email);
                }
                // Add user to authenticated group

                $this->addFlashMessage(Yii::t('UsersModule.default', 'REG_SUCCESS'));
                // Authenticate user
                $identity = new EngineUserIdentity($user->login, $_POST['User']['password']);
                if ($identity->authenticate()) {

                    Yii::app()->user->login($identity, Yii::app()->user->rememberTime);
                    if ($config['register_nomail']) {
                        Yii::app()->request->redirect($this->createUrl('/users/profile/index'));
                    } else {
                        //  $this->sendMail($user);
                        $view = 'success_register';
                    }
                } else {
                    die('authenticate(): Error');
                }
            }
        }

        $this->render($view, array(
            'user' => $user
        ));
    }
    /**
     * Отправка уведомление зарегистрированному пользователю
     * @param User $user
     */
    public function generateKey($size) {
        $result = '';
        $chars = '1234567890qweasdzxcrtyfghvbnuioplkjnm';
        while (mb_strlen($result, 'utf8') < $size)
            $result .= mb_substr($chars, rand(0, mb_strlen($chars, 'utf8')), 1);

        if (User::model()->countByAttributes(array('active_key' => $result)) > 0)
            $this->generateKey($size);

        return strtoupper($result);
    }

    private function sendMail(User $user) {
        $user->active_key = $this->generateKey(50);
        $user->save(false, false, false);

        $site_name = Yii::app()->settings->get('app', 'site_name');
        $host = Yii::app()->request->serverName;
        $theme = Yii::t('admin', 'Вы загерестировались на сайте {site_name} ', array(
                    '{site_name}' => $site_name
                ));
        $mail = Yii::app()->mail;
        $mail->From = 'noreply@' . $host;
        $mail->FromName = $site_name;
        $mail->Subject = $theme;
        $mail->Body = $this->renderPartial('mail_template', array(
            'user' => $user,
            'host' => $host
                ), true, false);
        $mail->AddAddress($user->email);
        $mail->AddReplyTo('noreply@' . $host);
        $mail->isHtml(true);
        $mail->Send();
        $mail->ClearAddresses();
    }

}
