<?php

class Session extends ActiveRecord {

    public function getBrowserVersion() {
        $browserClass = new Browser($this->user_agent);
        return $browserClass->getVersion();
    }

    public function getPlatformName() {
        $browserClass = new Browser($this->user_agent);
        return $browserClass->getPlatform();
    }

    public function getBrowserName() {
        $browserClass = new Browser($this->user_agent);
        return $browserClass->getBrowser();
    }

    public function getIconBrowser() {
        $browserClass = new Browser($this->user_agent);
        $browser = $browserClass->getBrowser();
        if ($browser == Browser::BROWSER_FIREFOX) {
            return 'firefox';
        } elseif ($browser == Browser::BROWSER_SAFARI) {
            return 'safari';
        } elseif ($browser == Browser::BROWSER_OPERA) {
            return 'opera';
        } elseif ($browser == Browser::BROWSER_CHROME) {
            return 'chrome';
        } elseif ($browser == Browser::BROWSER_IE) {
            return 'ie';
        } else {
            return false;
        }
    }

    public function getIconPlatform() {
        $browserClass = new Browser($this->user_agent);
        $platform = $browserClass->getPlatform();
        if ($platform == Browser::PLATFORM_WINDOWS) {
            return 'windows7';
        } elseif ($platform == Browser::PLATFORM_WINDOWS_8) { //no tested
            return 'windows8';
        } elseif ($platform == Browser::PLATFORM_ANDROID) {
            return 'android';
        } elseif ($platform == Browser::PLATFORM_LINUX) {
            return 'linux';
        } elseif ($platform == Browser::PLATFORM_APPLE) {
            return 'apple';
        } else {
            return false;
        }
    }

    public static function online() {
        $cr = new CDbCriteria;
        //  $cr->group = '`t`.`ip_address`, `t`.`user_type`';
        //$cr->distinct='ip_address, user_type';
        $session = Session::model()->findAll();
        $result = array();
        if (isset($session)) {
            $t = 0;
            $g = 0;
            $b = 0;
            $u = 0;
            $a = 0;
            foreach ($session as $val) {
                $result['users'][] = array(
                    'login' => $val->user_login,
                    'ip' => $val->ip_address,
                    'user_agent' => $val->user_agent,
                    'avatar' => $val->user_avatar,
                    'type' => $val->user_type
                );
                if ($val->user_type == 2) {
                    $u++;
                } elseif ($val->user_type == 3) {
                    $a++;
                } elseif ($val->user_type == 1) {
                    $b++;
                } else {
                    $g++;
                }
                $t++;
            }
            $result['totals'] = array(
                'all' => $t,
                'guest' => $g,
                'admin' => $a,
                'users' => $u,
                'bot' => $b
            );
        }
        return $result;
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return '{{session}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('ip_address, user_agent, user_type', 'length', 'max' => 255),
            array('ip_address, user_agent, user_type, start_expire', 'safe'),
            array('expire, start_expire', 'numerical', 'integerOnly' => true),
            array('user_login, uname, ip_address, user_agent, module, current_url', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',

            'ip_address' => 'ip_address',
            'user_agent' => 'user_agent',
        );
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get ActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return ActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        //$criteria->with =array('user');
        $criteria->compare('user_login', $this->user_login, true);

        $criteria->compare('t.user_agent', $this->user_agent, true);
        $criteria->compare('t.user_avatar', $this->user_avatar, true);
        $criteria->compare('t.ip_address', $this->ip_address, true);
        $criteria->compare('t.module', $this->module, true);
        $criteria->compare('t.uname', $this->uname, true);

        $criteria->compare('t.current_url', $this->current_url, true);

        return new ActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }


    public function getOnlineTime() {
        if ($this->start_expire) {
            return CMS::display_time(CMS::time() - (int)$this->start_expire);
        } else {
            return 'unknown';
        }
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Blocks the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
