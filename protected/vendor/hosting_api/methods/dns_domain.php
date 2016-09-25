<?php

/**
 * Работа с доменами осуществляется посредством класса dns_domain.
 * 
 * @author Andrew S. <andrew.panix@gmail.com>
 * @copyright (c) 2015, Andrew S
 * @link http://corner-cms.com/ CORNER CMS
 * @version 0.1
 * @uses CMethod общий класс методов
 * @package corner.app.hosting_api.methods
 * @link https://api.adm.tools Hosting Ukraine
 */
class dns_domain extends CMethod {

    /**
     * @var string Указываем название класса 
     */
    public $_className = __CLASS__;

    /**
     * Метод zones - возвращает список DNS зон с ценами на них.
     * 
     * @param array $options
     * @param array $options['stack'] Список зон для фильтрации. Необязательный параметр.
     * @param boolean $options['available'] запросить только те зоны, для которых доступна регистрация доменов через API. Необязательный параметр.
     */
    public function zones($options = array()) {
        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

    /**
     * Метод info - возвращает список доменов, к которым у Вас открыт доступ.
     * 
     * @param array $options
     * @param string $options['search'] Поисковый запрос. Необязательный параметр.
     * 
     * @param enum $options['sort'] поле, по которому необходимо отсортировать список доменов.
     *      Необязательный параметр. По-умолчанию - name.
     *      Может принимать значения: name - имя домена, valid_untill - срок окончания услуги.
     * 
     * @param enum $options['by'] формат сортировки.
     *      Необязательный параметр. По-умолчанию - asc.
     *      Может принимать значения: asc - от меньшего к большему, desc - от большего к меньшему.
     * 
     * @throws CException
     */
    public function info($options = array()) {

        if (isset($options['by'])) {
            if (!in_array($options['by'], array('asc', 'desc'))) {
                throw new CException('Option "by" only asc|desc.');
            }
        }

        if (isset($options['sort'])) {
            if (!in_array($options['sort'], array('name', 'valid_untill'))) {
                throw new CException('Option "by" only name|valid_untill.');
            }
        }

        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

    /**
     * Метод check - проверка доступности домена для регистрации.
     * 
     * @param array $options
     * @param array $options['stack'] массив доменов. Необходимо указывать полные имена доменов, с указанием зоны: example.com
     * 
     * @throws CException
     */
    public function check($options = array()) {

        if (!isset($options['stack']))
            throw new CException('Option "stack" not found.');
        if (!is_array($options['stack']))
            throw new CException('Option "stack" not array.');


        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

    /**
     * Метод create - добавить домен на DNS сервера adm.tools.
     * 
     * @param array $options
     * @param string $options['domain'] Имя домена. Обязательный параметр.
     * @param boolean $options['import'] импортировать существующие DNS-записи. Необязательный параметр.
     * 
     * @throws CException
     */
    public function create($options = array()) {

        if (!isset($options['domain']))
            throw new CException('Option "domain" is req.');

        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

    /**
     * Метод delete - удалить домен из adm.tools.
     * 
     * @param array $options
     * @param string $options['domain'] Имя домена. Обязательный параметр.
     * 
     * @throws CException
     */
    public function delete($options = array()) {

        if (!isset($options['domain']))
            throw new CException('Option "domain" is req.');

        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

}

?>