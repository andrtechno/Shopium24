<?php

/**
 * Работа с почтовыми ящиками осуществляется посредством класса hosting_mailbox.
 * 
 * @author Andrew S. <andrew.panix@gmail.com>
 * @copyright (c) 2015, Andrew S
 * @link http://corner-cms.com/ CORNER CMS
 * @version 0.1
 * @uses CMethod общий класс методов
 * @package corner.app.hosting_api.methods
 * @link https://api.adm.tools Hosting Ukraine
 */
class hosting_mailbox extends CMethod {

    /**
     * @var string Указываем название класса 
     */
    public $_className = __CLASS__;

    /**
     * Метод info - возвращает информацию о сайтах аккаунта.
     * 
     * @param array $options
     * @param string $options['domain'] домен. Необязательный параметр. Если указать, сервер возвратит только почтовые ящики указанного домена.
     * @param string $options['mailbox'] почтовый ящик. Необязательный параметр. Если указать, сервер возвратит только данные указанного почтового ящика.
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
     * Метод create - создание нового почтового ящика.
     * @param string $options['password'] пароль почтового ящика. Обязательный параметр.
     * @param string $options['mailbox'] имя почтового ящика в формате <имя>@<домен>. Обязательный параметр.
     * @param enum $options['type'] тип почтового ящика. Необязательный параметр, по-умолчанию установлено - mailbox. Может принимать значения:
     *      mailbox - стандартный почтовый ящик;
     *      redirect - вся почта будет перенаправляться с новосозданного ящика на почтовые ящики, указанные в параметре forward (см. ниже);
     *      copy - стандартный почтовый ящик с функцией перенаправления почты.
     * 
     * 
     * @param array $options['forward'] массив почтовых ящиков, на которые будет перенаправлена почта с новосозданного ящика. Обязательный параметр в случае, если параметр type установлен в redirect или copy. 
     *      array("redirect1@test.com.ua", "redirect2@test.com.ua", ...)
     * @param enum $options['antispam'] уровень защиты от спама. Необязательный параметр, по-умолчанию установлено - medium. Может принимать значения:
     *      off - антиспам отключен;
     *      low - низкий уровень защиты от спама;
     *      medium - средний уровень защиты;
     *      high - высокий уровень защиты.
     * 
     * @param array $options['autoresponder'] настройки автоответчика. 
     *      array('enabled' => 1, 'title' => "..", 'text' => "..")
     * 
     * @return type
     */
    public function create($options = array()) {
        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

    /**
     * Метод edit - редактирование параметров почтового ящика.
     * 
     * Внимание! Все остальные параметры почтового ящика (см. метод create) являются необязательными. Таким образом, посредством метода edit можно редактировать как все параметры сразу, так и каждый по отдельности.
     * 
     * @param array $options
     * @param string $options['mailbox'] имя почтового ящика. Обязательный параметр.
     * @return type
     */
    public function edit($options = array()) {
        $this->_methodName = __FUNCTION__;
        if (!isset($options['mailbox'])) {
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'mailbox',
                '{method}' => $this->_methodName
            )), 404);
        }

        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

    /**
     * Метод clear - очистить почтовый ящик.
     * 
     * @param array $options
     * @param string $options['mailbox'] имя почтового ящика. Обязательный параметр.
     * @return type
     */
    public function clear($options = array()) {
        $this->_methodName = __FUNCTION__;
        if (!isset($options['mailbox'])) {
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'mailbox',
                '{method}' => $this->_methodName
            )), 404);
        }

        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

    /**
     * Метод delete - удалить почтовый ящик.
     * 
     * @param array $options
     * @param string $options['mailbox'] имя почтового ящика. Обязательный параметр.
     * @return type
     */
    public function delete($options = array()) {
        $this->_methodName = __FUNCTION__;
        if (!isset($options['mailbox'])) {
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'mailbox',
                '{method}' => $this->_methodName
            )), 404);
        }

        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($params, $options));
    }

}

?>