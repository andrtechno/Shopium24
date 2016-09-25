<?php

class CreateUserPlan {

    public static $db_host = 'corner.mysql.ukraine.com.ua';

    public function shop_register(User $user) {
        $prefix = CMS::gen(5) . '_';
        $shop = new UserShop();
        $shop->uid = $user->id;
        $shop->plan = $user->plan;
        $shop->subdomain = $user->subdomain;
        $shop->expired = date('Y-m-d H:i:s', strtotime('+2 week', strtotime(date('Y-m-d H:i:s'))));

        if ($shop->save(false, false, false)) {
            $domain = $this->APIHosting_create_subdomain($user);
            if ($domain->response->status == 'success') {
                $this->extractPlan($user); //Извлекам архив
                $db = $this->APIHosting_create_db($user);
                if ($db->response->status == 'success') {
                    $this->editConfigFile($user, $db->response->data->user, $prefix);
                    $dbconnect = array(
                        'dsn' => 'mysql:host=' . self::$db_host . ';dbname=' . $db->response->data->user->login . ';charset=UTF8',
                        'username' => $db->response->data->user->login,
                        'password' => $db->response->data->user->password,
                        'prefix' => $prefix
                    );
                    Yii::app()->database->importPlanSql($user, $dbconnect);
                    $this->addUser($user, $dbconnect);
                }
            }
        }
    }

    public function addUser(User $user, $dbconnect) {
        $db = new CDbConnection($dbconnect['dsn'], $dbconnect['username'], $dbconnect['password']);
        // устанавливаем соединение
        // можно использовать конструкцию try…catch для перехвата возможных исключений
        $db->active = true;
        $db->createCommand()->insert($dbconnect['prefix'] . 'user', array(
            'login' => $user->login,
            'email' => $user->email,
            'date_registration' => $user->date_registration,
            'group_id' => 1,
            'active' => 1,
            'password' => $user->password,
        ));

        $db->createCommand()->insert($dbconnect['prefix'] . 'settings', array(
            'value' => $user->email,
            'category' => 'core',
            'key' => 'admin_email'
        ));
        $db->active = false;
    }

    /**
     * Редактирование конфиг файла, базы.
     * @param User $user
     * @param array $db
     * @throws CException
     */
    public function editConfigFile(User $user, $db, $prefix) {

        $path = Yii::getPathOfAlias('rootpath') . DS . $user->subdomain . DS . 'protected' . DS . 'config';

        if (file_exists($path . DS . 'client.php')) {
            if (!@file_put_contents($path . DS . 'client.php', '<?php
$commonPath = dirname(__DIR__) . DS . ".." . DS . ".." . DS."common";
 
return CMap::mergeArray(require($commonPath . "/config/main.php"), array(
            "basePath" => dirname(__FILE__) . DS . "..",
            "params" => array(
                "client_id" => '.$user->id.',
            ),
            "components" => array(
                "db" => array(
                    "class" => "app.DbConnection",
                    "connectionString" => "mysql:host=' . self::$db_host . ';dbname=' . $db->login . '",
                    "username" => "'.$db->login.'",
                    "password" => "'.$db->password.'",
                    "tablePrefix" => "'.$prefix.'",
                    "charset" => "utf8",
                    "enableProfiling" => false,
                    "enableParamLogging" => false,
                    "schemaCachingDuration" => 3600
                )
            )
));

')) {
                throw new CException(Yii::t('admin', 'Error write client config.'));
            }
        } else {
            throw new CException('file config not exist');
        }
    }

    /**
     * Извлекаем архив тарифного плана, в папку поддомена
     * @param User $user
     */
    public function extractPlan($user) {
        $zip = new ZipArchive();

        $filepath = Yii::getPathOfAlias('webroot.archive.plans') . DS . 'extract.zip';
        if (file_exists($filepath)) {
            $extractTo = Yii::getPathOfAlias("rootpath.{$user->subdomain}") . DS;
            if ($zip->open($filepath) === true) {
                $zip->extractTo($extractTo); //Извлекаем файлы в указанную директорию
                $zip->close(); //Завершаем работу с архивом
                //remove Hosting default index file
                if (file_exists($extractTo . 'index.html'))
                    unlink($extractTo . 'index.html');
            } else {
                //  echo "Архива не существует!"; //Выводим уведомление об ошибке
            }
        } else {
            die('error plan not found');
        }
    }

    /**
     * Создаем базу данных и пользователя
     * @param User $user
     * @return object array
     */
    public function APIHosting_create_db($user) {
        Yii::import('app.hosting_api.*');
        $api = new APIHosting('hosting_database', 'database_create', array(
                    'name' => 'bs' . $user->shop[0]->id,
                    'collation' => 'utf8_general_ci',
                    'user_create' => true
                ));
        return $api->callback(false);
    }

    /**
     * создаем поддомен
     * @param User $user
     * @return type
     */
    public function APIHosting_create_subdomain($user) {
        Yii::import('app.hosting_api.*');
        $api = new APIHosting('hosting_site', 'host_create', array(
                    'subdomain' => $user->subdomain,
                ));
        return $api->callback(false);
    }

}

?>
