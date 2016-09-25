
<?php
$domainName  ='shopium24.com';
$this->pageName = 'Домены и цены';
$this->breadcrumbs = array($this->pageName);
$api = new APIHosting('dns_domain', 'check', array('stack'=>array($domainName)));
$check = $api->callback(false);
echo($check->response->data->{$domainName}->available);
die;
$api = new APIHosting('billing_cart', 'order', array('domains'=>array('name' => 'shopium24.com', 'period' => 1)));
$result = $api->callback(false);
?>
<div class="col-xs-12">
<?php
//      [login] = corner_buildshop
//[password] = dk5y013c
print_r($result);
if($result->response->status == 'success'){
    print_r($result);
}else{
    Yii::app()->tpl->alert('info',$result->response->message);
}
?>
</div>