<?php

class CMethod {

    //public $requireParams = array();
    public $json;
    private $url = 'https://adm.tools/api.php';
    public $auth_login = "andrew.panix@gmail.com";
    public $auth_token = "4abtu62s4kdk646ed99437ld3dd3ub9dbdc47v8s7jvpvk4qm4yr8sat9xprb36w";
    public $account = "corner";

    const ACCOUNT_ID = 127522; //ID аккаунта

    public $dbcollation = 'utf8_general_ci';
    public $dbuser_create = true;
    public $params = array();
    public $_className;
    public $_methodName;

    /**
     * Ссылка на партнерскую программу
     * 
     * @param string $uri Default '/' home
     * @return string
     */
    public static function getUrl($uri = '/') {
        $param = 'page=' . self::ACCOUNT_ID;
        if (strpos($uri, '?') === false) {
            $url = $uri . '?' . $param;
        } else {
            $url = $uri . '&' . $param;
        }
        return 'https://www.ukraine.com.ua' . $url;
    }

    public function __construct() {
        $this->json = $this->createDefaultJson();
        $this->params = array(
            'auth_login' => $this->auth_login,
            'auth_token' => $this->auth_token,
        );
    }

    /**
     * Создаем дефолтный JSON для ответов
     * @return json
     */
    public function createDefaultJson() {
        $retObject = json_decode('{}');
        return $retObject;
    }

    /**
     * 
     * @param array $data
     * @return json
     */
    protected function exec($data = array()) {
        $this->params['class'] = $this->_className;
        $this->params['method'] = $this->_methodName;
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query(CMap::mergeArray($this->params, $data)),
            ),
        );

        $context = stream_context_create($options);
        $json = json_decode(file_get_contents($this->url, false, $context));
        return $json;
    }
    //TODO no use
    public function setErrReq(){
            throw new CException(Yii::t('APIHosting.default', 'REQUIRED_OPTION', array(
                '{option}' => 'ftp',
                '{method}' => $this->_methodName
            )), 404);
    }
}

?>