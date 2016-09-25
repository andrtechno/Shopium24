<?php

/**
 * Заказ новых услуг и продление существующих можно выполнить посредством класса billing_cart.
 * 
 * @author Andrew S. <andrew.panix@gmail.com>
 * @copyright (c) 2015, Andrew S
 * @link http://corner-cms.com/ CORNER CMS
 * @version 0.1
 * @uses CMethod общий класс методов
 * @package corner.app.hosting_api.methods
 * @link https://api.adm.tools Hosting Ukraine
 */
class billing_cart extends CMethod {

    /**
     * @var string Указываем название класса 
     */
    public $_className = __CLASS__;

    /**
     * Метод order - заказ новых услуг. Выписывает счет на оплату.
     * 
     * @param array $options
     * @param array $options['domain'] многомерный массив записей вида 
     * 
     * array(
     *      array('name' => "example-1.com", 'period' => 1),
     *      array('name' => "example-2.net", 'period' => 1),       
     *      array('name' => "example-3.ua", 'period' => 1, 'license' => 12345),
     *      ...
     * );
     * 
     * , где name - имя домена, который необходимо зарегистрировать (имя указывается полностью, с зоной); period - период регистрации домена; license - номер лицензии (только для зоны .ua).
     * Если по каким-либо причинам один из переданных доменов не может быть зарегистрирован, сервер возвратит ошибку и счет выписан не будет.
     */
    public function order($options = array()) {
        $this->_methodName = __FUNCTION__;
        if (!is_array($options['domains'])) {
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION_NOARRAY', array(
                '{option}' => 'domains',
                '{method}' => $this->_methodName
            )), 403);

            if (strpos('ua', $options['domain']['name'])) {
                
            } else {
                
            }
        }

        return $this->exec($options);
    }

    /**
     * Метод buy - упрощенный способ заказа доменов.
     * Выписывает счет и оплачивает его, если средств на счету достаточно.
     * В противном случае сформированный счет можно оплатить из панели управления или посредством API позже.
     * 
     * @param array $options
     * @param array $options['domains'] многомерный массив записей вида 
     * где name - имя домена, который необходимо продлить (имя указывается полностью, с зоной); period - период продления домена.
     * 
     * Если по каким-либо причинам один из переданных доменов не может быть продлен, сервер возвратит ошибку и счет выписан не будет.
     * 
     * @return type
     * @throws CException
     */
    public function buy($options = array()) {
        $this->_methodName = __FUNCTION__;
        if (!is_array($options['domains'])) {
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION_NOARRAY', array(
                '{option}' => 'domains',
                '{method}' => $this->_methodName
            )), 403);
        }

        //return $this->exec($options);
    }

    /**
     * Метод prolong - пролонгация услуг. Выписывает счет на оплату.
     * 
     * @param array $options
     * @param array $options['domains'] многомерный массив записей вида 
     * где name - имя домена, который необходимо продлить (имя указывается полностью, с зоной); period - период продления домена.
     * 
     * Если по каким-либо причинам один из переданных доменов не может быть продлен, сервер возвратит ошибку и счет выписан не будет.
     * 
     * 
     * 
     * 
     * 
     * 
     * @param array $options['hosting']  массив параметров хостиг-аккаунта, срок действия которого необходимо продлить. Имеет вид: 
     * 'hosting' => array(
     *      'account' => <имя аккаунта>,
     *      'period' => 1
     * )
     * где account: (string) - имя аккаунта, который необходимо продлить. Список доступных хостинг-аккаунтов можно получить методом info класса hosting_account.
     * period: (int) - период, на который необходимо продлить аккаунт. От 1 до 24 месяцев.
     * 
     * 
     * 
     * @return type
     * @throws CException
     */
    public function prolong($options = array()) {
        $this->_methodName = __FUNCTION__;
        if (isset($options['domains'])) {
            if (!is_array($options['domains'])) {
                throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION_NOARRAY', array(
                    '{option}' => 'domains',
                    '{method}' => $this->_methodName
                )), 403);
            }
        }
        if (isset($options['hosting'])) {
            if (!is_array($options['hosting'])) {
                throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION_NOARRAY', array(
                    '{option}' => 'hosting',
                    '{method}' => $this->_methodName
                )), 403);
            }
            if (!isset($options['hosting']['period'])) {
                throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                    '{option}' => 'hosting.period',
                    '{method}' => $this->_methodName
                )), 404);
            } else {
                if (!in_array($options['hosting']['period'], range(1, 24))) {
                    throw new CException('Период не верен от 1 до 24 месяцев', 404);
                }
            }
        }

        //return $this->exec($options);
    }

}
