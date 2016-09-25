<?php

class HostingApi {

    public $URL = 'https://adm.tools/api.php';
    public $auth_login = "psevdobum@gmail.com";
    public $auth_token = "6d7sbv3a59dv713y3vn1f344a7a14y938bc3yv38e47lbeaber54872225ef3j3h";
    private $account = "corner2";
    private $site = "corner-cms.com";
    private $dbcollation = 'utf8_general_ci';
    private $dbuser_create = true;
    public $method;
    public $class;
    public $params = array();

    public function __construct() {
        $this->params = array(
            'auth_login' => $this->auth_login,
            'auth_token' => $this->auth_token,
        );
    }

    public function domainZones() {
        $data = array(
            'class' => "dns_zone",
            'method' => "listing",
            'account' => $this->account,
            'site' => $this->site,
        );
        return $this->exec($data);
    }
    
    public function dns_nsInfo($domainName) {
        $data = array(
            'class' => "dns_ns",
            'method' => "info",
            'account' => $this->account,
            'site' => $this->site,
            'domain'=>$domainName
        );
        return $this->exec($data);
    }
    
    public function dns_nsEdit($domainName,$nsArray=array()) {
        $data = array(
            'class' => "dns_ns",
            'method' => "edit",
            'account' => $this->account,
            'site' => $this->site,
            'domain'=>$domainName,
            'stack'=>$nsArray
        );
        return $this->exec($data);
    }

    private function exec($data) {
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query(CMap::mergeArray($this->params, $data)),
            ),
        );

        $context = stream_context_create($options);
        $json = json_decode(file_get_contents($this->URL, false, $context));
        return $json;
    }

}