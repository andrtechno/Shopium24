<?php

/**
 * Работа с базами данных осуществляется посредством класса hosting_database.
 * 
 * @author Andrew S. <andrew.panix@gmail.com>
 * @copyright (c) 2015, Andrew S
 * @link http://corner-cms.com/ CORNER CMS
 * @version 0.1
 * @uses CMethod общий класс методов
 * @package corner.app.hosting_api.methods
 * @link https://api.adm.tools Hosting Ukraine
 */
class hosting_database extends CMethod {

    public $_className = __CLASS__;

    public function info($options = array()) {
        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec($params);
    }

    public function database_create($options = array()) {

        $this->_methodName = __FUNCTION__;
        if (!isset($options['name']))
            throw new CException('Option "name" not found.');
        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($options, $params));
    }

    /**
     * Метод user_delete - удаление пользователя базы данных и всех его привилегий.
     * 
     * @param string $options['user'] пользователь базы данных. Обязательный параметр.
     * @return type
     */
    public function user_delete($options = array()) {
        if (!isset($options['user']))
            throw new CException('Option "user" not found.');
        //..
    }

    /**
     * Метод user_revoke - удаление всех привилегий доступа пользователя базы данных к соответствующей базе данных.
     * 
     * @param string $options['database'] база данных, для которой будут удалены привилегии. Обязательный параметр.
     * @param string $options['user'] пользователь базы данных, для которого будут удалены привилегии. Обязательный параметр.
     * @return type
     */
    public function user_revoke($options = array()) {
        if (!isset($options['database']))
            throw new CException('Option "database" not found.');
        if (!isset($options['user']))
            throw new CException('Option "user" not found.');
        //..
    }

    /**
     * Метод user_privileges - изменение привилегий доступа пользователя базы данных к соответствующей базе данных.
     * 
     * @param string $options['database'] база данных, для которой редактируются привилегии. Обязательный параметр.
     * @param string $options['user'] пользователь базы данных, для которого будут удалены привилегии. Обязательный параметр.
     * @param array $options['privileges'] массив привилегий. Обязательный параметр. Список всех доступных привилегий см. в описании метода user_create.
     * @return type
     */
    public function user_privileges($options = array()) {
        if (!isset($options['database']))
            throw new CException('Option "database" not found.');
        if (!isset($options['user']))
            throw new CException('Option "user" not found.');
        if (!isset($options['privileges']))
            throw new CException('Option "privileges" not found.');
        //..
    }

    /**
     * Метод user_password - смена пароля пользователя базы данных.
     * 
     * @param string $options['user'] пользователь базы данных. Обязательный параметр.
     *      Внимание! Необходимо указывать полное имя пользователя <хостинг_аккаунт>_<имя_пользователя>.
     * @param string $options['password'] новый пароль. Обязательный параметр.
     * @return type
     */
    public function user_password($options = array()) {
        if (!isset($options['password']))
            throw new CException('Option "database" not found.');
        if (!isset($options['user']))
            throw new CException('Option "user" not found.');
        //..
    }

    /**
     * Метод database_delete - удаление базы данных.
     * 
     * @param string $options['database'] база данных, которую необходимо удалить. Обязательный параметр.
     *      Внимание! Необходимо указывать полное имя базы данных <хостинг_аккаунт>_<имя_базы_данных>.
     * @return type
     */
    public function database_delete($options = array()) {
        if (!isset($options['database']))
            throw new CException('Option "database" not found.');
        //..
    }

}

?>