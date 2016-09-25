<?php

Yii::setPathOfAlias('hosting', Yii::getPathOfAlias('application.vendor.hosting_api'));

class APIHosting {

    private $className;
    private $methodName;
    private $params;

    /**
     * Статичная функция для подключения API из других API при необходимости в методах
     * 
     * @param string $apiName ClassName
     * @return \apiName
     */
    static function getApiEngineByName($apiName) {
        //Yii::import("app.hosting_api.CMethod");
        Yii::import("hosting.methods.{$apiName}");
        $apiClass = new $apiName();
        return $apiClass;
    }

    //Конструктор
    //$classFileName - название класса API
    //$methodName - название метода класса
    //$params - параметры метода в строковом представлении
    public function __construct($classFileName, $methodName, $params = array()) {
        $this->params = $params;
        $this->className = $classFileName;
        $this->methodName = $methodName;
    }

    /**
     * Создаем JSON ответа
     * @return type
     */
    public function createDefaultJson() {
        $retObject = json_decode('{}');
        $response = APIConstants::$RESPONSE;
        $retObject->$response = json_decode('{}');
        return $retObject;
    }

    /**
     * Вызов функции по переданным параметрам в конструкторе
     * 
     * @param string $format Default object. json|object
     * @return object|json
     */
    public function callback($format = 'json') {
        $resultFunctionCall = $this->createDefaultJson(); //Создаем JSON  ответа
        $apiName = strtolower($this->className); //название API проиводим к нижнему регистру
        $filePath = Yii::getPathOfAlias("hosting.methods.{$apiName}");
        if (file_exists($filePath . '.php')) {
            $apiClass = APIHosting::getApiEngineByName($apiName); //Получаем объект API
            $apiReflection = new ReflectionClass($apiName); //Через рефлексию получем информацию о классе объекта
            try {
                $methodName = $this->methodName; //Название метода для вызова
                $apiReflection->getMethod($methodName); //Провераем наличие метода
                $response = APIConstants::$RESPONSE;

                $resultFunctionCall->$response = $apiClass->$methodName($this->params); //Вызыаем метод в API который вернет JSON обект
            } catch (Exception $ex) {
                //Непредвиденное исключение
                $resultFunctionCall->$response->status = 'error';
                $resultFunctionCall->$response->code = $ex->getCode();
                $resultFunctionCall->$response->message = $ex->getMessage();
            }
        } else {
            //Если запрашиваемый API не найден
                $resultFunctionCall->$response->status = 'error';
            $resultFunctionCall->$response->code = APIConstants::$ERROR_ENGINE_PARAMS;
            $resultFunctionCall->$response->message = 'File not found';
        }
        if ($format == 'json') {
            return json_encode($resultFunctionCall);
        } else {
            return (object) ($resultFunctionCall);
        }
    }

}

?>