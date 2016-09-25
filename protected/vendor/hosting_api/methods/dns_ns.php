<?php

/**
 * Работа с NS-серверами доменов осуществляется посредством класса dns_ns.
 * 
 * @author Andrew S. <andrew.panix@gmail.com>
 * @copyright (c) 2015, Andrew S
 * @link http://corner-cms.com/ CORNER CMS
 * @version 0.1
 * @uses CMethod общий класс методов
 * @package corner.app.hosting_api.methods
 * @link https://api.adm.tools Hosting Ukraine
 */
class dns_record extends CMethod {

    /**
     * @var string Указываем название класса 
     */
    public $_className = __CLASS__;

    /**
     * Метод info - возвращает информацию о NS серверах домена.
     * 
     * @param string $options['domain'] домен. Обязательный параметр.
     */
    public function info($options) {
        if (!isset($options['domain']))
            throw new CException('Option "domain" not found.');
        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec($params);
    }

    /**
     * Метод edit - редактирование NS серверов домена.
     * 
     * @param string $options['domain'] домен. Обязательный параметр.
     * @param array $options['stack'] массив NS серверов вида 
     * <code>
     * array("ns1.fastdns.hosting.", "ns2.fastdns.hosting.")
     * </code>
     */
    public function edit($options) {
        if (!isset($options['domain'])) {
            throw new CException('Option "domain" not found.');
        }
//..
    }

    /**
     * Метод restore - восстановление NS серверов по-умолчанию.
     * 
     * @param string $options['domain'] домен. Обязательный параметр.
     */
    public function restore($options) {
        if (!isset($options['domain'])) {
            throw new CException('Option "domain" not found.');
        }
//..
    }

}

?>