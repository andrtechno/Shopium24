<?php

/**
 * Работа с логами осуществляется посредством класса hosting_log.
 * 
 * @author Andrew S. <andrew.panix@gmail.com>
 * @copyright (c) 2015, Andrew S
 * @link http://corner-cms.com/ CORNER CMS
 * @version 0.1
 * @uses CMethod общий класс методов
 * @package corner.app.hosting_api.methods
 * @link https://api.adm.tools Hosting Ukraine
 */
class hosting_log extends CMethod {

    /**
     * @var string Указываем название класса 
     */
    public $_className = __CLASS__;

    /**
     * Метод dates - возвращает список дат в формате массива записей вида ГГГГ-ММ-ДД, за которые есть лог файлы для просмотра.
     * 
     * @param array $options
     * @param string $options['type'] тип лога. Обязательный параметр. Допустимые значения:
     *      apache - error лог
     *      nginx - access лог,
     *      ftp - лог подключений по ftp,
     *      crontab - лог расписания задач,
     *      mail - лог писем с сайтов.
     *  
     * @param string $options['host'] сайт. Обязательный параметр для таких типов логов из предыдущего параметра: apache, nginx.
     */
    public function dates($options = array()) {
        $this->_methodName = __FUNCTION__;
        if (!isset($options['type'])) {
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'type',
                '{method}' => $this->_methodName
            )), 404);
        }
        if (!isset($options['host'])) {
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'host',
                '{method}' => $this->_methodName
            )), 404);
        }
        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }
    
    /**
     * Метод view - возвращает массив данных вида:
     * 
     * @param array $options
     * @return type
     * @throws CException
     */
    public function view($options = array()) {
        $this->_methodName = __FUNCTION__;
        if (!isset($options['type'])) {
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'type',
                '{method}' => $this->_methodName
            )), 404);
        }
        if (!isset($options['host'])) {
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'host',
                '{method}' => $this->_methodName
            )), 404);
        }
        $params = array(
            'account' => $this->account,
        );
       // return $this->exec(CMap::mergeArray($params, $options));
    }

}

