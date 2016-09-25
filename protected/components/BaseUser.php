<?php

/**
 * @package components
 */
class BaseUser extends RWebUser {

    private $_identity;

    /**
     * @var int
     */
    public $rememberTime = 2622600;

    /**
     * @var User model
     */
    private $_model;
    public $guestName = '_GUEST';

    public function login($identity, $duration = 0) {
        $this->_identity = $identity;
        return parent::login($identity, $duration);
    }

    public function afterLogin($fromCookie) {
        if ($this->_identity !== null) {
            CIntegrationForums::instance()->log_in($this->_identity->username, $this->_identity->password);
        }
        return parent::afterLogin($fromCookie);
    }

    public function afterLogout() {
        CIntegrationForums::instance()->log_out();
    }

    public function getBalance() {
        $this->_loadModel();
        return $this->_model->balance;
    }

    public function getPlan() {
        $this->_loadModel();
        return $this->_model->shop[0]->plan;
    }

    /**
     * @return string user email
     */
    public function getEmail() {
        $this->_loadModel();
        return $this->_model->email;
    }

    public function getTheme() {
        $this->_loadModel();
        return $this->_model->theme;
    }

    public function getLanguage() {
        $this->_loadModel();
        return $this->_model->language;
    }

    public function getTimezone() {
        $this->_loadModel();
        return $this->_model->timezone;
    }

    public function getLast_login() {
        $this->_loadModel();
        return $this->_model->last_login;
    }

    public function getLogin() {
        $this->_loadModel();
        return $this->_model->login;
    }

    public function getPhone() {
        $this->_loadModel();
        return $this->_model->phone;
    }

    public function getAddress() {
        $this->_loadModel();
        return $this->_model->address;
    }

    public function getAccessMessage() {
        if (Yii::app()->user->message) {
            return true;
        } else {
            throw new CHttpException(401, MessageModule::t('ACCESS_DENIED_USER'));
        }
    }

    /**
     * @return string username
     */
    public function getUsername() {
        $this->_loadModel();
        return $this->_model->username;
    }

    public function getService() {
        $this->_loadModel();
        return $this->_model->service;
    }

    public function getMessage() {
        $this->_loadModel();
        return $this->_model->message;
    }

    /**
     * Load user model
     */
    private function _loadModel() {
        if (!$this->_model)
            $this->_model = User::model()
                    //->cache(Yii::app()->controller->cacheTime)
                    ->findByPk($this->id);
    }

    public function getModel() {
        $this->_loadModel();
        return $this->_model;
    }

    public function getAvatarUrl($size = false, $isGuest = false) {
        if ($size === false) {
            $size = Yii::app()->settings->get('users', 'avatar_size');
        }
        $ava = $this->_model->avatar;
        if (!preg_match('/(http|https):\/\/(.*?)$/i', $ava)) {
            $r = true;
        } else {
            $r = false;
        }
        // if (!is_null($this->service)) {
        //     return $this->avatar;
        // }
        if ($size !== false && $r !== false) {
            if (!$isGuest) {
                if (empty($ava)) {
                    $returnUrl = CMS::resizeImage($size, 'user.png', 'users.avatars', 'user_avatar');
                } else {
                    $returnUrl = CMS::resizeImage($size, $ava, 'users.avatar', 'user_avatar');
                }
            } else {
                $returnUrl = CMS::resizeImage($size, 'guest.png', 'users.avatars', 'user_avatar');
            }
            return $returnUrl;
        } else {
            return $ava;
        }
    }

}
