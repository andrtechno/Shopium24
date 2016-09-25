<?php

Yii::import('application.vendor.ext.payment.privat24.Privat24ConfigurationModel');
Yii::import('mod.cart.models.*');
/**
 * Privat24 payment system
 */
class Privat24PaymentSystem extends BasePaymentSystem {

    public $MERCHANT_ID=110541;
    public $MERCHANT_PASS='H4okGc9PeM1T30TEMQWl88pfqqfFz0X4';
    /**
     * This method will be triggered after redirection from payment system site.
     * If payment accepted method must return Order model to make redirection to order view.
     * @param ShopPaymentMethod $method
     * @return boolean|Order
     */
    public function processPaymentRequest(ShopPaymentMethod $method) {
        $request = Yii::app()->request;


        if (isset($_POST['payment'])) {
            parse_str($request->getPost('payment'), $payments);
        }

        $order_id = substr($payments['order'], 10);
        //$settings = $this->getSettings($method->id);

        $order = Order::model()->findByPk($order_id);


        // Check if order is paid.
        if ($order->paid) {
            Yii::log('Order is paid', 'info', 'payment privat24');
            return false;
        }

        // Check amount.
        if (Yii::app()->currency->active->iso != $payments['ccy']) {
            Yii::log('Currency error', 'info', 'payment privat24');
            return false;
        }



        if (!$request->getParam('payment')) {
            Yii::log('No find post param "payment"', 'info', 'payment privat24');
            return false;
        }

        // Create and check signature.
        $sign = sha1(md5($request->getParam('payment') . $this->MERCHANT_PASS));

        // If ok make order paid.
        if ($sign != $request->getParam('signature')) {
            Yii::log('signature error', 'info', 'payment privat24');
            return false;
        }


        // Set order paid
        $order->paid = 1;
        if($order->save(false,false,false)){
            $order->updateExpiredUser();
             Yii::app()->user->setFlash('success','Спасибо ваша заказ успешно оплачен.');
        }else{
             $this->log('error save Order');
        }
        

        $log = '';
        $log.='PayID: ' . $payments['ref'];
        $log.='Datatime: ' . $payments['date'];
        $log.='UserID: ' . (Yii::app()->user->isGuest) ? 0 : Yii::app()->user->id;
        $log.='IP: ' . $request->userHostAddress;
        $log.='User-agent: ' . $request->userAgent;
        $this->log($log);


        return $order;
    }

    public function renderPaymentForm(ShopPaymentMethod $method, Order $order) {
        $price = Yii::app()->currency->convert($order->full_price, $method->currency_id);
        $htmlForm = '
            <form action="https://api.privatbank.ua/p24api/ishop" method="POST" accept-charset="UTF-8">
                <input type="hidden" name="amt" value="{AMOUNT}"/>
                <input type="hidden" name="ccy" value="UAH" />
                <input type="hidden" name="merchant" value="{MERCHANT_ID}" />
                <input type="hidden" name="order" value="{ORDER}" />
                <input type="hidden" name="details" value="{ORDER_TEXT}" />
                <input type="hidden" name="ext_details" value="{ORDER_ID}" />
                <input type="hidden" name="pay_way" value="privat24" />
                <input type="hidden" name="return_url" value="{SUCCESS_URL}" />
                <input type="hidden" name="server_url" value="{RESULT_URL}" />
                {SUBMIT}
            </form> '.$price.' грн.';

        $html = strtr($htmlForm, array(
            '{AMOUNT}' => $price,
            '{ORDER_ID}' => md5($order->id),
            '{ORDER_TEXT}' => $this->getOrderTextServices($order),
            '{MERCHANT_ID}' => $this->MERCHANT_ID,
            '{ORDER}' => CMS::gen(10) . $order->id,
            '{SUCCESS_URL}' => Yii::app()->createAbsoluteUrl('/cart/payment/process', array('payment_id' => $method->id)),
            '{RESULT_URL}' => Yii::app()->createAbsoluteUrl('/cart/payment/process', array('payment_id' => $method->id, 'result' => true)),
            '{SUBMIT}' => $this->renderSubmit(),
                ));

        return $html;
    }
    public function getOrderTextServices(Order $order){
        $result='';

        foreach($order->products as $service){
            if($service->quantity){
                $result.= $service->name.' на '.$service->quantity.' мес.';
            }else{
                $result.= $service->name;
            }
        }
        return $result;
    }

    /**
     * This method will be triggered after payment method saved in admin panel
     * @param $paymentMethodId
     * @param $postData
     */
    public function saveAdminSettings($paymentMethodId, $postData) {
        $this->setSettings($paymentMethodId, $postData['Privat24ConfigurationModel']);
    }

    /**
     * @param $paymentMethodId
     * @return string
     */
    public function getSettingsKey($paymentMethodId) {
        return $paymentMethodId . '_Privat24PaymentSystem';
    }

    /**
     * Get configuration form to display in admin panel
     * @param string $paymentMethodId
     * @return CForm
     */
    public function getConfigurationFormHtml($paymentMethodId) {
        $model = new Privat24ConfigurationModel;
        $model->attributes = $this->getSettings($paymentMethodId);
        $form = new BasePaymentForm($model->getFormConfigArray(), $model);
        return $form;
    }

}
