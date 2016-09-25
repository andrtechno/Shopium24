<?php

// $pw = User::encodePassword('aprilpw');
$pw = 'admin';
//echo $pw;
$url = 'http://corner-cms.com/license/auth';
$url = 'http://admin:admin@corner-cms.com/license/auth';



if (Yii::app()->hasComponent('curl')) {
    $curl = Yii::app()->curl;
    // $curl->setHttpLogin('admin','admin');
    $curl->options = array(
        'timeout' => 320,
        'setOptions' => array(
            //CURLOPT_HTTPHEADER=> array('Content-Type: application/json'),
            CURLOPT_HEADER => false,
           // CURLOPT_VERBOSE=>true,
           // CURLOPT_FAILONERROR=>true,
           // CURLOPT_HTTPAUTH=>CURLAUTH_BASIC,
            //CURLOPT_HTTPHEADER => $header,
           // CURLOPT_RETURNTRANSFER => 1,

           // CURLOPT_FOLLOWLOCATION => 1,
           // CURLOPT_POST=>true,
            //  CURLOPT_NOBODY=>true,
            //CURLOPT_HTTPAUTH=>CURLAUTH_ANY,
           // CURLOPT_USERPWD => "admin:admin",
        ),
       /* 'login' => array(
            'username' => 'admin',
            'password' => 'admin'
        ),*/
    );
    $connent = $curl->run($url,array('test'=>1));

    if (!$connent->hasErrors()) {
        //print_r($connent->getData());
        $result = CJSON::decode($connent->getData());

  echo '<pre>';
    print_r(json_decode($connent->getData()));
    echo '</pre>';
    echo $connent->getInfo();
        print_r($result);
    } else {
        $error = $connent->getErrors();
        $result = array(
            'status' => 'error',
            'message' => $error->message,
            'code' => $error->code
        );


        print_r($error);
    }
} else {
    throw new Exception(Yii::t('exception', 'COM_CURL_NOTFOUND', array('{com}' => 'curl')));
}
?>
