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
class hosting_site_config_ws extends CMethod {

    /**
     * @var string Указываем название класса 
     */
    public $_className = __CLASS__;

    public function edit($options) {
        if (!isset($options['host']))
            throw new CException('Option "host" not found.');
        
        $this->_methodName = __FUNCTION__;
        $defaultOptions = array(
            'account' => $this->account,
        );
        
        return $this->exec(CMap::mergeArray($options, $defaultOptions));
    }

}

?>