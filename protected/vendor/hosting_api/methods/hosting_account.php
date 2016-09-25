<?php

/**
 * Работа с настройками хостинг аккаунта осуществляется посредством класса hosting_account.
 * 
 * @author Andrew S. <andrew.panix@gmail.com>
 * @copyright (c) 2015, Andrew S
 * @link http://corner-cms.com/ CORNER CMS
 * @version 0.1
 * @uses CMethod общий класс методов
 * @package corner.app.hosting_api.methods
 * @link https://api.adm.tools Hosting Ukraine
 */
class hosting_account extends CMethod {

    public $_className = __CLASS__;

    /**
     * Метод plans - возвращает список доступных тарифных планов.
     * @return type
     */
    public function plans() {
        $this->_methodName = __FUNCTION__;
        $params = array();
        //$params = array(
        //    'account' => $this->account,
        // );
        return $this->exec($params);
    }

    /**
     * Метод info - возвращает список хостинг аккаунтов, к которым у Вас открыт доступ.
     * @param account: (string) имя аккаунта. Необязательный параметр.
     * @return type
     */
    public function info() {
        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec($params);
    }

    /**
     * Метод migrate - миграция хостинг аккаунта в другую страну.
     * @return type
     */
    public function migrate($options) {
        if (!isset($options['country'])) {
            throw new CException('Option "country" required.');
        }
        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($options, $params));
    }

    /**
     * Метод migration_cancel - отмена активного запроса на миграцию хостинг аккаунта в другую страну.
     * @return type
     */
    public function migration_cancel() {

        $this->_methodName = __FUNCTION__;

        return $this->exec(array());
    }

    /**
     * Метод plan_change - смена тарифного плана хостинг аккаунта.
     * 
     * Пожалуйста, обратите внимание, мгновенный переход на другой тарифный план доступен только для хостинг-аккаунтов, у которых отсутствуют действующие заказы на дополнительные услуги.
     * 
     * 
     * @return type
     */
    public function plan_change($options) {
        if (!isset($options['plan'])) {
            throw new CException('Option "plan" not found.');
        }
        $this->_methodName = __FUNCTION__;
        $params = array(
            'account' => $this->account,
        );
        return $this->exec(CMap::mergeArray($options, $params));
    }

}

?>