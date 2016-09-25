<?php

/**
 * Работа с DNS-записями осуществляется посредством класса dns_record.
 * 
 * @author Andrew S. <andrew.panix@gmail.com>
 * @copyright (c) 2015, Andrew S
 * @link http://corner-cms.com/ CORNER CMS
 * @version 0.1
 * @uses CMethod общий класс методов
 * @package corner.app.hosting_api.methods
 * @link https://api.adm.tools Hosting Ukraine
 */
class hosting_quota extends CMethod {

    /**
     * @var string Указываем название класса 
     */
    public $_className = __CLASS__;

    public function info() {
        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec($params);
    }

    public function used_mysql() {
        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec($params);
    }

    public function used_ftp() {
        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec($params);
    }

}

?>