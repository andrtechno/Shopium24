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
class dns_record extends CMethod {

    /**
     * @var string Указываем название класса 
     */
    public $_className = __CLASS__;

    /**
     * Метод info - возвращает информацию о DNS записях домена.
     * 
     * @param array $options
     * @param string $options['domain'] домен. Обязательный параметр.
     */
    public function info($options = array()) {
        $this->_methodName = __FUNCTION__;
        if (!isset($options['domain'])) {
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'domain',
                '{method}' => $this->_methodName
            )), 404);
        }
        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

    /**
     * Метод create - создание новой DNS записи домена.
     * 
     * @param string $options['domain'] домен. Обязательный параметр.
     * @param string $options['subdomain'] cубдомен. Обязательный параметр. Можно указывать в виде:
     *      имени субдомена - например, test;
     *      @ - означает ссылку на текущий домен;
     *      * - означает любой поддомен текущего домена.
     * 
     * 
     * @param enum $options['type'] тип записи. Обязательный параметр. По-умолчанию установлена запись типа "A". Может принимать значения:
     *      A - в поле data необходимо указывать IPv4-адрес;
     *      CNAME - в поле data необходимо указывать действующую запись типа A (IP адреса и другие CNAME записи не разрешены);
     *      MX - в поле data необходимо указывать действующую запись типа A (IP адреса и CNAME записи не разрешены);
     *      TXT - в поле data необходимо указывать только символы латиницы и знаки пунктуации (до 250 символов);
     *      NS - в поле data необходимо указывать полное имя домена (IP адреса недопустимы);
     *      AAAA - в поле data необходимо указывать IPv6-адрес;
     *      SPF - определяет почтовые серверы, которым разрешено отправлять электронную почту от имени домена. Детальные сведения о формате записей SPF можно найти на сайте  @link http://www.openspf.org/Project_Overview/ Sender Policy Framework;
     * 
     * @param int $options['priority'] приоритет MX записи. Необязательный параметр.
     * @param string $options['data'] данные записи. Обязательный параметр. Формат поля напрямую зависит от указанного типа записи (см. выше).
     */
    public function create($options = array()) {
        if (!isset($options['domain']))
            throw new CException('Option "domain" not found.');
        if (!isset($options['subdomain']))
            throw new CException('Option "subdomain" not found.');
    }

    /**
     * Метод edit - редактирование DNS записей домена.
     * 
     * @param string $options['domain'] домен. Обязательный параметр.
     * @param array $options['stack'] многомерный массив записей вида 
     * <code>
     * array(
     *      array('id' => 1001, 'priority' => 15, 'data' => "192.168.0.1"),
     *      array('id' => 1002, 'data' => "192.168.0.2"),
     * ...
     * )
     * </code>
     * 
     * , где id - идентификатор DNS-записи внутри системы ukraine.com.ua (полный список записей домена можно получить методом info); priority - приоритет MX-записи (необходимо указывать только для MX-записей); data - данные записи. Обязательный параметр.
     */
    public function edit($options) {
        $this->_methodName = __FUNCTION__;
        if (!isset($options['domain'])) {
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'domain',
                '{method}' => $this->_methodName
            )), 404);
        }
        return $this->exec($options);
    }

    /**
     * Метод restore - восстановление DNS записей по-умолчанию.
     * 
     * @param array $options
     * @param string $options['domain'] домен. Обязательный параметр.
     */
    public function restore($options = array()) {
        $this->_methodName = __FUNCTION__;
        if (!isset($options['domain'])) {
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'domain',
                '{method}' => $this->_methodName
            )), 404);
        }
        return $this->exec($options);
    }

    /**
     * Метод mx_predefined - настройка MX-записей в соответствии с одинм из предопределенных наборов.
     * @param string $options['domain'] домен. Обязательный параметр.
     * @param enum $options['set'] предопределенный набор MX-записей.
     *      Обязательный параметр. По-умолчанию установлен набор "ukraine".
     *      Может принимать значения:
     * 
     *          ukraine - все записи MX, SPF и SPF-в-TXT в домене будут заменены на записи, рекомендованные для настройки почты на ukraine.com.ua;
     *          google - все записи MX, SPF и SPF-в-TXT в домене будут заменены на записи, рекомендованные для настройки почты на Google;
     *          yandex - все записи MX, SPF и SPF-в-TXT в домене будут заменены на записи, рекомендованные для настройки почты на Яндекс;
     */
    public function mx_predefined($options = array()) {
        $this->_methodName = __FUNCTION__;
        if (!isset($options['domain']))
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'domain',
                '{method}' => $this->_methodName
            )), 404);

        return $this->exec($options);
    }

    /**
     * Метод delete - удаление DNS записей домена.
     * 
     * @param array $options
     * @param string $options['domain'] домен. Обязательный параметр.
     * @param array $options['stack'] массив идентификаторов DNS-записей вида array(1001, 1002, 1003, ...). Обязательный параметр.
     */
    public function delete($options = array()) {
        $this->_methodName = __FUNCTION__;
        if (!isset($options['domain']))
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'domain',
                '{method}' => $this->_methodName
            )), 404);
        if (!isset($options['stack']))
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'stack',
                '{method}' => $this->_methodName
            )), 404);
        if (!is_array($options['stack']))
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION_NOARRAY', array(
                '{option}' => 'stack',
                '{method}' => $this->_methodName
            )), 403);

        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

}

