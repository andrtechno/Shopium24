<?php

class PaymentSystemManager extends CComponent {

    /**
     * @var array
     */
    private $_systems = array();

    /**
     * Find all payment systems installed
     * @return array
     */
    public function getSystems() {
        $pattern = Yii::getPathOfAlias('application.vendor.ext.payment') . DS . '*' . DS . 'config.xml';

        foreach (glob($pattern, GLOB_BRACE) as $file) {
            $config = simplexml_load_file($file);
            $this->_systems[(string) $config->id] = $config;
        }
        return $this->_systems;
    }

    /**
     * Read and return system config.xml
     * @param $name
     */
    public function getSystemInfo($name) {
        return $this->systems[$name];
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getSystemClass($id) {
        $systemInfo = $this->getSystemInfo($id);
        $className = (string) $systemInfo->class;

        Yii::import("application.vendor.ext.payment.{$systemInfo->id}.{$className}");
        return new $className;
    }

}
