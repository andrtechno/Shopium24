<?php

/**
 * Работа с FTP-пользователями осуществляется посредством класса hosting_ftp.
 * 
 * @author Andrew S. <andrew.panix@gmail.com>
 * @copyright (c) 2015, Andrew S
 * @link http://corner-cms.com/ CORNER CMS
 * @version 0.1
 * @uses CMethod общий класс методов
 * @package corner.app.hosting_api.methods
 * @link https://api.adm.tools Hosting Ukraine
 */
class hosting_ftp extends CMethod {

    /**
     * @var string Указываем название класса 
     */
    public $_className = __CLASS__;

    /**
     * Метод info - возвращает информацию о FTP-пользователях.
     * 
     * @param array $options
     * @param string $options['ftp'] логин FTP-пользователя. Необязательный параметр. Если указать, сервер возвратит только данные указанного FTP-пользователя.
     * @return type
     */
    public function info($options = array()) {
        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

    /**
     * Метод create - создание нового FTP-пользователя.
     * 
     * @param string $options['login'] Логин FTP-пользователя. Обязательный параметр.
     *      Внимание! Система автоматически добавит к указанному логину FTP-пользователя имя хостинг-аккаунта, как префикс. В результате логин примет вид: <хостинг_аккаунт>_<указанный_логин>.
     * 
     * @param string $options['password'] Пароль FTP-пользователя. Необязательный параметр. Если оставить поле пустым, система сгенерирует пароль автоматически.
     * @param string $options['homedir'] Каталог доступа FTP-пользователя. Необязательный параметр. По-умолчанию устанавлен корневой каталог хостинг-аккаунта.
     * @param boolean $options['readonly'] Установить режим "только для чтения". Необязательный параметр.
     * @return type
     */
    public function create($options = array()) {
        $this->_methodName = __FUNCTION__;
        if (!isset($options['login'])) {
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'login',
                '{method}' => $this->_methodName
            )), 404);
        }

        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

    /**
     * Метод edit - редактирование FTP-пользователя.
     * 
     * @param array $options
     * @param string $options['ftp'] логин FTP-пользователя. Обязательный параметр.
     *      Необходимо указывать полное имя FTP-пользователя <хостинг_аккаунт>_<логин>.
     * 
     *      Внимание! Все остальные параметры FTP-пользователя (см. метод create) являются необязательными.
     *      Таким образом, посредством метода edit можно редактировать как все параметры сразу, так и каждый по отдельности.
     * 
     * @return exec
     */
    public function edit($options = array()) {
        $this->_methodName = __FUNCTION__;
        if (!isset($options['ftp'])) {
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'ftp',
                '{method}' => $this->_methodName
            )), 404);
        }
        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

    /**
     * Метод delete - удаление FTP-пользователя.
     * 
     * @param array $options
     * @param string $options['ftp'] логин FTP-пользователя. Обязательный параметр.
     * @return type
     */
    public function delete($options = array()) {
        $this->_methodName = __FUNCTION__;
        if (!isset($options['ftp'])) {
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'ftp',
                '{method}' => $this->_methodName
            )), 404);
        }
        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

    /**
     * Метод stack_delete - удаление нескольких FTP-пользователей.
     * @param array $options['stack'] массив FTP-пользователей. Обязательный параметр.
     *      array("account_ftp1", "account_ftp2", ...)
     * @return type
     */
    public function stack_delete($options = array()) {
        if (!isset($options['stack']))
            throw new CException('Option "stack" not found.');
        if (!is_array($options['stack']))
            throw new CException('Option "stack" not array.');
        //..
    }

    /**
     * Метод access_info - возвращает информацию о настройках безопасности FTP.
     * 
     * @return exec
     */
    public function access_info() {
        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec($params);
    }

    /**
     * Метод access_edit - редактирование настроек безопасности FTP.
     * 
     * @param array $options['ip'] список IP адресов или сетей, для которых разрешен доступ по FTP. Необязательный параметр.
     * Пример списка IP адресов: 
     * array("193.178.34.14", "193.178.34.0/24 // Адрес сети в виде CIDR", "193.178.34.0/255.255.255.0", ...)
     * 
     * @param boolean $options['active'] включить ограничение доступа к FTP по IP. Необязательный параметр.
     * @param boolean $options['web_ftp'] открыть доступ с web-ftp. Необязательный параметр.
     * @return type
     */
    public function access_edit($options = array()) {
        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

}

?>