<?php

class BasePaymentSystem extends CComponent {

    protected function priceByMonths($months) {
        if (Yii::app()->user->plan == 'pro') {
            $oneMonthPrice = 1000;
            $sixMonthPrice = 950;
            $yearMonthPrice = 900;
        } else if (Yii::app()->user->plan == 'standart') {
            $oneMonthPrice = 500;
            $sixMonthPrice = 480;
            $yearMonthPrice = 450;
        } else if (Yii::app()->user->plan == 'lite') {
            $oneMonthPrice = 160;
            $sixMonthPrice = 150;
            $yearMonthPrice = 140;
        }
        if ($months >= 12) {
            $result = ($months * $yearMonthPrice);
        } else if ($months >= 6) {
            $result = ($months * $sixMonthPrice);
        } else {
            $result = ($months * $oneMonthPrice);
        }
        return $result;
    }
    /**
     * @return string
     */
    public function renderSubmit() {
        return '<input type="submit" class="btn btn-success" value="' . Yii::t('app', 'Оплатить') . '">';
    }

    /**
     * @param $paymentMethodId
     * @param $data
     */
    public function setSettings($paymentMethodId, $data) {
        Yii::app()->settings->set($this->getSettingsKey($paymentMethodId), $data);
    }

    /**
     * @param $paymentMethodId
     */
    public function getSettings($paymentMethodId) {
        // die($this->getSettingsKey($paymentMethodId));
        return Yii::app()->settings->get($this->getSettingsKey($paymentMethodId));
    }

    public static function log($message) {
        return Yii::log($message, 'info', 'payment');
    }

}