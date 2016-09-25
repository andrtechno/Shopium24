<?php


/**
 * Работа с сайтами осуществляется посредством класса hosting_site.
 * 
 * @author Andrew S. <andrew.panix@gmail.com>
 * @copyright (c) 2015, Andrew S
 * @link http://corner-cms.com/ CORNER CMS
 * @version 0.1
 * @uses CMethod общий класс методов
 * @package corner.app.hosting_api.methods
 * @link https://api.adm.tools Hosting Ukraine
 */
class hosting_site extends CMethod {

    /**
     * @var string Указываем название класса 
     */
    public $_className = __CLASS__;

    /**
     * Метод info - возвращает информацию о сайтах аккаунта.
     * @param bool $options['site'] сайт. Необязательный параметр. Если указать, сервер возвратит только данные указанного сайта.
     * @return type
     */
    public function info($options) {

        $this->_methodName = __FUNCTION__;
        $defaultOptions = array(
            'account' => $this->account,
            'site'=>Yii::app()->params['baseDomain']
        );
        return $this->exec(CMap::mergeArray($options, $defaultOptions));
    }

    /**
     * Метод host_stack_delete - удаление нескольких поддоменов.
     * @param array $options['stack'] массив хостов. Обязательный параметр. 
     * @param bool $options['file'] удалить файлы хоста на сервере. Необязательный параметр.
     * @param bool $options['mailbox'] удалить почтовые ящики хоста. Необязательный параметр.
     * @return type
     */
    public function host_stack_delete($options) {
        if (isset($options['stack'])) {
            if (is_array($options['stack'])) {
                //...
            } else {
                throw new CException('Option "stack" not array.');
            }
        } else {
            throw new CException('Option "stack" not found.');
        }
    }

    /**
     * Метод host_delete - удаление поддомена.
     * @param string $options['host'] хост. Обязательный параметр.
     * @param bool $options['file'] удалить файлы хоста на сервере. Необязательный параметр.
     * @param bool $options['mailbox'] удалить почтовые ящики хоста. Необязательный параметр.
     * @return type
     */
    public function host_delete($options) {
        if (!isset($options['host'])) {
            throw new CException('Option "host" not found.');
        }
        //...
    }

    /**
     * Метод host_create - создание поддомена (виртуального хоста).
     * @param string $options['site'] сайт. Обязательный параметр.
     * @param string $options['subdomain'] имя субдомена. Обязательный параметр.
     * @return type
     */
    public function host_create($options) {
        if (!isset($options['subdomain']))
            throw new CException('Option "subdomain" not found.');

        $this->_methodName = __FUNCTION__;
        $defaultOptions = array(
            'account' => $this->account,
            'site'=>Yii::app()->params['baseDomain']
        );
        return $this->exec(CMap::mergeArray($options, $defaultOptions));

    }

    /**
     * Метод site_delete - удаление сайта и всех его поддоменов.
     * 
     * Внимание! Метод удаляет сайт, все его поддомены,
     * а также все файлы и почтовые ящики сайта и всех его поддоменов.
     * 
     * @param string $options['site'] сайт. Обязательный параметр.
     * @return type
     */
    public function site_delete($options) {
        if (!isset($options['site']))
            throw new CException('Option "site" not found.');
        //...
    }

    /**
     * Метод site_create - создание нового сайта.
     * @param string $options['site'] сайт. Обязательный параметр.
     * @return type
     */
    public function site_create($options) {
        if (!isset($options['site']))
            throw new CException('Option "site" not found.');

        $this->_methodName = __FUNCTION__;
        $defaultOptions = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($options, $defaultOptions));

    }

}

?>