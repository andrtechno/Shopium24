<?php

/**
 * Контроллер закладок пользователей.
 * 
 * @author Semenov Andrew <andrew.panix@gmail.com>
 * @package modules.users.controllers
 * @uses Controller
 */
class PaymentController extends Controller {

    /**
     * Renders payment system configuration form
     */
    public function actionRenderForm() {

        $systemId = Yii::app()->request->getQuery('system');
        $paymentMethodId = Yii::app()->request->getQuery('payment_method_id');
        if (empty($systemId))
            exit('exit');
        $manager = new PaymentSystemManager;
        $system = $manager->getSystemClass($systemId);
        // var_dump($system);
        // die;
        echo $system->renderPaymentForm();
        //  echo $system->getConfigurationFormHtml($paymentMethodId);
    }

    public function actionProcess() {
       // if (Yii::app()->request->getParam('Shp_pmId'))
       //     $_GET['payment_id'] = $_GET['Shp_pmId'];

        $payment_id = (int) Yii::app()->request->getParam('payment_id');
        $model = ShopPaymentMethod::model()->findByPk($payment_id);

        if (!$model)
            throw new CHttpException(404, 'Ошибка');

        $system = $model->getPaymentSystemClass();
        if ($system instanceof BasePaymentSystem) {

            $response = $system->processPaymentRequest($model);

            if ($response instanceof Order)
                $this->redirect($this->createUrl('/cart', array('view' => $response->secret_key)));
            else
                throw new CHttpException(404, Yii::t('default', 'Возникла ошибка при обработке запроса. <br> {err}', array('{err}' => $response)));
        }
    }

}
