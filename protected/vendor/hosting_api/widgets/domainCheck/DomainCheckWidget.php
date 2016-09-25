<?php

/**
 * 
 * @url widget index.php?r=main/ajax/widget.callback
 * @example 
 * 
 * Add to AjaxController
 * 
 * public function actions() {
 *      return array(
 *          'callback.' => 'ext.callback.CallbackWidget',
 *      );
 *  }
 */
class DomainCheckWidget extends CWidget {

    function brandsort($a, $b) {
        return strnatcmp($a['classname'], $b['classname']);
    }

    public function run() {
        Yii::import('hosting.widgets.domainCheck.DomainCheckForm');

        Yii::import('hosting.APIHosting');


        $api = new APIHosting('dns_domain', 'zones',array('available'=>1));
        $result = $api->callback(false);
        ?>

        <?php

        $domainList = array();

        foreach ($result->response->data as $data) {

            $domainList[$data->class->name][] = array(
                'domain_name' => $data->name,
                'domain_price' => $data->price,
                'classname' => $data->class->name,
            );
        }

        $selectOptions = array();
        foreach ($domainList as $key => $items) {
            usort($items, 'brandsort');
            $i = 0;
            foreach ($items as $kz => $row) {
                $i++;
                $selectOptions[$key][$row['domain_name']] = $row['domain_name'];
            }
        }






        $checkData = array();
        $stackDomain = array();
        $model = new DomainCheckForm();

        if (isset($_POST['DomainCheckForm'])) {
            $model->attributes = $_POST['DomainCheckForm'];
            if ($model->validate()) {
                $domainArray = explode(',', $model->name);
                if (count($domainArray) <= 10) {
                    foreach ($domainArray as $stack) {
                        $stackDomain[] = $stack . "." . $model->domain;
                    }
                    $api = new APIHosting('dns_domain', 'check', array('stack' => $stackDomain)); //array('stack' => array($model->name . "." . $model->domain))
                    $checkdomain = $api->callback(false);

                    foreach ($checkdomain->response->data as $domain => $data) {
                        $checkData[$domain][] = $data;
                    }
                }
            }
        }

        $this->render($this->skin, array(
            'model' => $model,
            'selectOptions' => $selectOptions,
            'checkData' => $checkData
        ));
    }

    public function getReasonCode($data) {
        if ($data->reason_code == 'already_served') {
            return 'Доменное имя уже обслуживается';
        } elseif ($data->reason_code == 'object_exists') {
            return 'object_exists';
        } else {
            return '---';
        }
    }

}
