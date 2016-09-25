<?php

/**
 * This is the model class for table "user".
 * The followings are the available columns in table 'user':
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @uses BaseModel
 * @package modules.users.models
 * @property integer $id
 * @property string $username Имя пользовотеля
 * @property string $login Логин
 * @property string $password sha1(Пароль)
 * @property string $email Почта
 * @property integer $date_registration Дата регистрации
 * @property integer $last_login
 * @property string $login_ip IP-адрес входа пользователя
 * @property string $recovery_key Password recovery key
 * @property string $recovery_password
 * @property boolean $banned
 */
class User extends ActiveRecord {

    const MODULE_ID = 'users';

    public $subdomain;
    public $domain;
    public $plan;
    public $new_password;
    public $confirm_password;
    public $verifyCode;
    public $duration;
    public $terms = false; //соглашение с договором.

    /*
      public function getSubdomainUrl() {
      return 'http://' . $this->subdomain . '.' . Yii::app()->request->serverName;
      }

      public function getSubdomainFull() {
      return $this->subdomain . '.' . Yii::app()->request->serverName;
      }

      public function getDomainUrl() {
      return 'http://' . $this->domain;
      } */

    // public $phone;
    // public $address;

    public function getGridColumns() {
        return array(
            array(
                'name' => 'avatar',
                'type' => 'raw',
                'filter' => false,
                'value' => 'Html::image($data->getAvatarUrl("25x25"), $data->username, array())',
                'htmlOptions' => array('class' => 'text-center')
            ),
            array(
                'name' => 'login',
                'type' => 'raw',
                'value' => 'Html::link(Html::encode($data->login),array("update","id"=>$data->id))',
            ),
            array(
                'type' => 'raw',
                'name' => 'email',
                'value' => '$data->emailLink'
            ),
            array(
                'type' => 'raw',
                'header' => 'Роли',
                'value' => '$data->role()'
            ),
            array(
                'type' => 'raw',
                'name' => 'gender',
                'filter' => self::getSelectGender(),
                'value' => 'CMS::gender("$data->gender")'
            ),
            array(
                'type' => 'raw',
                'name' => 'last_login',
                'value' => 'CMS::date("$data->last_login")'
            ),
            array(
                'type' => 'raw',
                'name' => 'login_ip',
                'value' => 'CMS::ip("$data->login_ip", 1)'
            ),
            'DEFAULT_CONTROL' => array(
                'class' => 'ButtonColumn',
                'template' => '{update}{delete}',
            ),
            'DEFAULT_COLUMNS' => array(
                array(
                    'class' => 'IdColumn',
                    'type' => 'raw',
                    'value' => '$data->isUserOnline()',
                    'htmlOptions'=>array('class'=>'text-center')
                ),
            ),
        );
    }

    // class User
    public function getFullName() {
        return $this->login;
    }

    public function getSuggest($q) {
        $c = new CDbCriteria();
        $c->addSearchCondition('login', $q, true, 'OR');
        $c->addSearchCondition('email', $q, true, 'OR');
        return $this->findAll($c);
    }

    public function attributeLabels() {
        return CMap::mergeArray(parent::attributeLabels(), array(
                    'terms' => Yii::t('UsersModule.User', 'TERMS')
        ));
    }

    public function init() {

        $this->_attrLabels['confirm_password'] = $this->t('CONFIRM_PASSWORD');
        //$this->_attrLabels['verifyCode'] = $this->t('VERIFY_CODE');
        $this->_attrLabels['new_password'] = $this->t('NEW_PASSWORD');
        return parent::init();
    }

    public function getForm() {
        Yii::import('zii.widgets.jui.CJuiDatePicker');
        return new CMSForm(array('id' => __CLASS__,
            'enctype' => 'multipart/form-data',
            'showErrorSummary' => false,
            'elements' => array(
                'login' => array(
                    'type' => 'text',
                    'disabled' => $this->isService,
                    'afterField' => '<span class="fieldIcon icon-user"></span>'
                ),
                'username' => array(
                    'type' => 'text',
                    'afterField' => '<span class="fieldIcon icon-user"></span>'
                ),
                'email' => array(
                    'type' => 'text',
                    'afterField' => '<span class="fieldIcon icon-envelope"></span>'
                ),
                'address' => array('type' => 'text'),
                'phone' => array(
                    'type' => 'text',
                    'afterField' => '<span class="fieldIcon icon-phone"></span>'
                ),
                'subscribe' => array('type' => 'checkbox'),
                'message' => array('type' => 'checkbox'),
                'last_login' => array(
                    'type' => 'CJuiDatePicker',
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd ' . date('H:i:s'),
                    ),
                    'afterField' => '<span class="fieldIcon icon-calendar-2"></span>'
                ),
                'date_birthday' => array(
                    'type' => 'CJuiDatePicker',
                    'options' => array(
                        'dateFormat' => 'yy-mm-dd',
                    ),
                    'afterField' => '<span class="fieldIcon icon-calendar-2"></span>'
                ),
                'timezone' => array(
                    'type' => 'dropdownlist',
                    'items' => TimeZoneHelper::getTimeZoneData()
                ),
                'language' => array(
                    'type' => 'dropdownlist',
                    'items' => Yii::app()->languageManager->getLangsByArray(),
                    'empty' => 'По умолчанию',
                ),
                'gender' => array(
                    'type' => 'dropdownlist',
                    'items' => self::getSelectGender(),
                    'disabled' => $this->isService
                ),
                'avatar' => array('type' => 'file', 'disabled' => $this->isService),
                // 'login_ip' => array('type' => 'text', 'disabled' => $this->isService),
                'new_password' => array(
                    'type' => 'password',
                    'disabled' => $this->isService,
                    'afterField' => '<span class="fieldIcon icon-lock"></span>'
                ),
                'banned' => array('type' => 'checkbox'),
            ),
            'buttons' => array(
                'submit' => array(
                    'type' => 'submit',
                    'class' => 'buttonS bGreen',
                    'label' => ($this->isNewRecord) ? Yii::t('app', 'CREATE', 1) : Yii::t('app', 'SAVE')
                ),
            )
                ), $this);
    }

    public static function getRoles($user_id) {
        foreach (Rights::getAssignedRoles($user_id) as $role) {
            echo $role->name . ', ';
        }
    }

    public function role() {
        foreach (Rights::getAssignedRoles($this->id) as $role) {
            echo $role->description . ', ';
        }
    }

    public function getIsService() {
        return (isset($this->service) && !empty($this->service)) ? true : false;
    }

    public function getThemes() {
        $themesNames = Yii::app()->themeManager->themeNames;
        return array_combine($themesNames, $themesNames);
    }

    public function getEmailLink() {

        Yii::app()->clientScript->registerScript('sendEmail', '
       function sendEmailer(mail){

          if($("#sendEmailer").length == 0)
    {
        var div =  $("<div id=\"sendEmailer\"/ class=\"fluid\">");
        $(div).attr("title", "Оптавить письмо:");
        $("body").append(div);
    }

    var dialog = $("#sendEmailer");
    dialog.html("Загрузка формы...");
    dialog.load("/admin/core/ajax/sendMailForm?mail="+mail+"");

    dialog.dialog({
        modal: true,
        width: "50%",
        buttons: {
            "Отправить": function() {
                $.ajax("/admin/core/ajax/sendMailForm", {
                    type:"post",
                    data: {
                        token: $(link_clicked).attr("data-token"),
                        data: $("#sendEmailer form").serialize()
                    },
                    success: function(data){
                        $(dialog).dialog("close");
                        dialog.html("Письмо отправлено!");
                        
                    },
                    error: function(){
                        $.jGrowl("Ошибка", {
                            position:"bottom-right"
                        });
                    }
                });
            },
            "Отмена": function() {
                $( this ).dialog( "close" );
            }
        }
    });
}
        ', CClientScript::POS_HEAD);



        if (!empty($this->email)) {
            $em = CHtml::link($this->email, Yii::app()->createAbsoluteUrl('admin/delivery', array('send' => $this->email)), array('onClick' => 'sendEmailer("' . $this->email . '"); return false;'));
        } else {
            $em = $this->service;
        }
        return $em;
    }

    public function isUserOnline() {
        $session = Session::model()->find(array('condition' => '`t`.`user_login`=:login', 'params' => array(':login' => $this->login)));
        if (isset($session)) {
            if (Yii::app()->controller instanceof AdminController) {
                return '<span class="label label-success" title="'.CMS::date($this->last_login).'">' . Yii::t('default', 'ONLINE') . '</span>';
            } else {
                return true;
            }
        } else {
            if (Yii::app()->controller instanceof AdminController) {
                return '<span class="label label-default" title="'.CMS::date($this->last_login).'">' . Yii::t('default', 'OFFLINE') . '</span>';
            } else {
                return false;
            }
        }
    }

    public function scopes() {
        return array(
            'subscribe' => array(
                'condition' => '`t`.`subscribe`=:subs AND `t`.`active`=:act',
                'params' => array(':subs' => 1, ':act' => 1)
            )
        );
    }

    /**
     * Returns the static model of the specified AR class.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{user}}';
    }

    public static function getPlansList() {
        return array(
            'basic' => 'Basic',
            'standard' => 'Standard',
            'premium' => 'Premium',
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        $config = Yii::app()->settings->get('users');

        $checker = (Yii::app()->settings->get('app', 'forum') != null) ? 'checkBadLogin' : 'checkBadEmail';
        if ($checker == 'checkBadLogin') {
            $register = array('login', 'required', 'on' => 'register');
        } else {
            $register = array('login', 'email', 'on' => 'register');
        }


        return array(
            array('confirm_password', 'compare', 'compareAttribute' => 'password', 'on' => 'register'),
            array('subdomain', 'match', 'pattern' => '/^[\w\s,]+$/', 'message' => 'Домен a-Z'),
            array('subdomain', 'validateRegisterSubdomain', 'on' => 'register'),
            //Регистрация
            array('username', 'checkBadName', 'on' => 'register'),
            // array('domain, subdomain', 'unique'),
            array('login', $checker, 'on' => 'register'),
            array('login, password, confirm_password, subdomain, plan', 'required', 'on' => 'register'),
            //array('login', 'email', 'on' => 'register'),
            $register,
            //array('verifyCode', 'captcha', 'on' => 'register', 'allowEmpty' => YII_DEBUG),
            array('login', 'required'),
            array('login', 'checkIfAvailable'),
            array('terms', 'checkTerms', 'on' => 'register'),
            array('banned, message', 'boolean'),
            array('avatar', 'file',
                'types' => $config['users'],
                'allowEmpty' => true,
                'maxSize' => $config['upload_size'],
                'wrongType' => Yii::t('app', 'WRONG_TYPES', array('{TYPES}' => $config['upload_types']))
            ),
            array('email', 'email'),
            array('date_registration', 'required', 'on' => 'update'),
            array('date_registration, last_login', 'date', 'format' => array('yyyy-M-d H:m:s', '0000-00-00 00:00:00')),
            array('date_birthday', 'date', 'format' => array('yyyy-M-d', '0000-00-00')),
            array('username, password, email, theme, avatar, login_ip, service, phone, address, timezone', 'length', 'max' => 255),
            array('new_password', 'length', 'min' => $config['min_password']),
            array('password', 'length', 'min' => $config['min_password']),
            array('gender, language, subscribe', 'numerical', 'integerOnly' => true),
            array('id, username, email, date_registration, last_login, banned, avatar, language, address, phone', 'safe', 'on' => 'search'),
        );
    }

    public function validateRegisterSubdomain($attr) {
        $shop = UserShop::model()->findByAttributes(array('subdomain' => $this->$attr));
        if ($shop) {
            if ($this->$attr == $shop->subdomain) {
                $this->addError($attr, Yii::t('app', 'Доменное имя "{attr}" уже занято', array('{attr}' => $this->$attr)));
            }
        }
    }

    public function checkTerms($attr) {
        $labels = $this->attributeLabels();
        if (!$this->$attr) {
            $this->addError($attr, Yii::t('app', 'ERROR_VALID_TERMS', array('{attr}' => $labels[$attr])));
        }
    }

    /**
     * Check if username/email is available
     */
    public function checkIfAvailable($attr) {
        $labels = $this->attributeLabels();
        $check = User::model()->countByAttributes(array(
            $attr => $this->$attr,
                ), 't.id != :id', array(':id' => (int) $this->id));

        if ($check > 0)
            $this->addError($attr, Yii::t('app', 'ERROR_ALREADY_USED', array('{attr}' => $labels[$attr])));
    }

    /**
     * Запись псевдонимов доменна.
     * @param string $attr
     * TODO: тут наверное нужно будет сделать проверку на NS сервера (еще уточняется.)
     */
    public function checkDomainAliasExist($attr) {

        $aliases = (!empty($this->$attr)) ? array($this->$attr) : null;

        Yii::import('app.hosting_api.*');
        $api = new APIHosting('hosting_site_config_ws', 'edit', array(
            'host' => CMS::domain($this->getSubdomainUrl()),
            'aliases' => $aliases
        ));
        $result = $api->callback(false);
        $data = (array) $result->response;

        if (isset($data['status'])) {
            if ($data['status'] == 'error')
                $this->addError($attr, $data['message']); //$data->notes[0]
        }
    }

    public function checkDomainExist($attr) {

        Yii::import('app.hosting_api.*');
        $api = new APIHosting('hosting_site', 'info', array(
            'site' => $this->$attr,
        ));
        $result = $api->callback(false);
        $data = (array) $result->response;

        if (isset($data['status'])) {
            if ($data['status'] == 'error')
                $this->addError($attr, Yii::t('app', 'ERROR_ALREADY', array('{attr}' => $this->$attr)));
        }
    }

    public function checkBadName($attr) {
        $labels = $this->attributeLabels();
        $names_array = explode(',', Yii::app()->settings->get('users', 'bad_name'));
        if (in_array($this->username, $names_array))
            $this->addError($attr, Yii::t('UsersModule.default', 'ERROR_BAD_NAMES', array('{attr}' => $labels[$attr], '{name}' => $this->username)));
    }

    public function checkBadEmail($attr) {
        $config = Yii::app()->settings->get('users', 'bad_email');
        if (!empty($config)) {
            $mails = explode(',', $config);

            foreach ($mails as $mail) {
                if (preg_match('#' . $mail . '$#iu', $this->email))
                    $this->addError($attr, Yii::t('UsersModule.default', 'ERROR_BAD_EMAILS', array('{email}' => $mail)));
            }
        }
    }

    public function getFriends() {
        $condition = 'user_id = :uid OR friend_id = :uid';
        return UserFriends::model()->findAll($condition, array(':uid' => $this->id));
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            //'orders' => array(self::HAS_MANY, 'Order', 'user_id'),
            //'ordersCount' => array(self::STAT, 'Order', 'user_id'),
            'comments' => array(self::HAS_MANY, 'Comment', 'user_id'),
            'commentsCount' => array(self::STAT, 'Comment', 'user_id'),
            'payments' => array(self::HAS_MANY, 'UserPaymentHistory', 'user_id'),
            'shop' => array(self::HAS_MANY, 'UserShop', 'uid'),
        );
    }

    public function getAvatarPath() {
        if (Yii::app()->user->isGuest) {
            $avatar = '/uploads/users/avatars/guest.png';
        } else {
            if ($this->avatar == null) {
                $avatar = '/uploads/users/avatars/user.png';
            } else {
                $avatar = $this->avatar;
            }
        }
        return $avatar;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search() {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('date_registration', $this->date_registration, true);
        $criteria->compare('avatar', $this->avatar, true);
        $criteria->compare('last_login', $this->last_login);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('banned', $this->banned);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     *  Encodes user password
     *
     * @param string $string
     * @return string
     */
    public static function encodePassword($string) {
        return sha1($string);
    }

    /**
     * @return bool
     */
    public function beforeSave() {
        // Set new password

        if ($this->isNewRecord) {
            if (!$this->date_registration)
                $this->date_registration = date('Y-m-d H:i:s');
            $this->login_ip = Yii::app()->request->userHostAddress;

            if (!$this->hasErrors())
                $this->password = self::encodePassword($this->password);
        }
        if ($this->new_password) {
            $this->password = self::encodePassword($this->new_password);
        }
        // $this->uploadFile('avatar', '/uploads/users/avatar/');
        return parent::beforeSave();
    }

    /**
     * Generate admin link to edit user.
     * @return string
     */
    public function getUpdateLink() {
        return Html::link(Html::encode($this->username), array('/users/admin/default/update', 'id' => $this->id));
    }

    /**
     * Activate new user password
     * @static
     * @param $key
     * @return bool
     */
    public static function activeNewPassword($key) {
        $user = User::model()->findByAttributes(array('recovery_key' => $key));

        if (!$user)
            return false;

        $user->password = self::encodePassword($user->recovery_password);
        $user->recovery_key = '';
        $user->recovery_password = '';
        $user->save(false, false, false);
        return true;
    }

    /**
     * Активация пользователя по ключу
     * @param type $key
     * @return boolean
     */
    public static function activeKey($key) {
        $user = User::model()->findByAttributes(array('active_key' => $key));

        if (!$user)
            return false;

        $user->active_key = NULL;
        $user->active = 1;
        $user->save(false, false, false);
        $dir = $user->shop[0]->subdomain;

        CFileHelper::removeDirectory(Yii::getPathOfAlias("rootpath.{$dir}.protected.runtime.cache"), array('traverseSymlinks' => true));


        return true;
    }

    /**
     * @return int
     */
    public function getOrdersTotalPrice() {
        $result = 0;

        foreach ($this->orders as $order)
            $result += $order->full_price;

        return $result;
    }

    //Пол
    public static function getSelectGender() {
        return array(
            0 => Yii::t('app', 'GENDER', 0),
            1 => Yii::t('app', 'GENDER', 1),
            2 => Yii::t('app', 'GENDER', 2)
        );
    }

    public static function getUserPanel($username, $id) {
        $txt = '<ul class="navi nav-pills">';
        $txt .= '<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown">' . $username . '<b class="caret"></b></a>';
        $txt .= '<ul class="dropdown-menu">';
        $txt .= '<li><a href="/admin/users/default/update?id=' . $id . '"><span class="iconb icon-wrench"></span>' . Yii::t('app', 'UPDATE', 1) . '</a></li>';
        $txt .= '<li><a href="/admin/users/default/update?id=' . $id . '"><span class="iconb icon-wrench"></span>' . Yii::t('app', 'UPDATE', 1) . '</a></li>';
        $txt .= '</ul>';
        $txt .= '</li>';
        $txt .= '</ul>';

        return $txt;
    }

    public function getAvatarUrl($size = false) {
        if ($size === false) {
            $size = Yii::app()->settings->get('users', 'avatar_size');
        }
        $ava = $this->avatar;
        if (!preg_match('/(http|https):\/\/(.*?)$/i', $ava)) {
            $r = true;
        } else {
            $r = false;
        }
        // if (!is_null($this->service)) {
        //     return $this->avatar;
        // }
        if ($size !== false && $r !== false) {
            if (empty($ava)) {
                $returnUrl = CMS::resizeImage($size, 'user.png', 'users.avatars', 'user_avatar');
            } else {
                $returnUrl = CMS::resizeImage($size, $ava, 'users.avatar', 'user_avatar');
            }
            return $returnUrl;
        } else {
            return $ava;
        }
    }

    /*
      public function getExpiredByMonth($month) {
      //Если expired менише или равно текущей даты, то записываем на новую дату
      if (strtotime($this->expired) <= time()) {
      $time = time();
      } else {//Добавление месяцев к текущему expired
      $time = strtotime($this->expired);
      }

      return date('Y-m-d H:i:s', strtotime("+{$month} month", $time));
      } */
}
