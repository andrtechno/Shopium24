<?php

/**
 * User login form
 */
class UserLoginForm extends FormModel {

    public $login;
    public $password;
    public $rememberMe = false;
    private $_identity;
    const MODULE_ID = 'users';
    /**
     * @return array
     */
    public function rules() {
        return array(
            array('login, password', 'required'),
            array('password', 'authenticate'),
            array('rememberMe', 'boolean'),
        );
    }


    /**
     * Try to authenticate user
     */
    public function authenticate() {
        if (!$this->hasErrors()) {
            $this->_identity = new EngineUserIdentity($this->login, $this->password);
            if (!$this->_identity->authenticate()) {
                if ($this->_identity->errorCode === EngineUserIdentity::ERROR_PASSWORD_INVALID) {
                    $this->addError('password', Yii::t('UsersModule.default', 'INCORRECT_LOGIN_OR_PASS'));
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function getIdentity() {
        return $this->_identity;
    }

}
