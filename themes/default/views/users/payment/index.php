
<script>
    var plan = '<?= Yii::app()->user->plan ?>';
    if(plan=='pro'){
        var oneMonthPrice = 1000;
        var sixMonthPrice = 950;
        var yearMonthPrice = 900;
    }else if(plan=='standart'){
        var oneMonthPrice = 500;
        var sixMonthPrice = 480;
        var yearMonthPrice = 450;
    }else if(plan=='lite'){
        var oneMonthPrice = 160;
        var sixMonthPrice = 150;
        var yearMonthPrice = 140;
    }


    $(function(){
        $('#months').change(function(){
            var that = $(this).val();
            var t = $('#total');
            if(that >= 12){
                t.html(that * yearMonthPrice);
                console.log('year');
            }else if(that >= 6){
                t.html(that * sixMonthPrice); 
                console.log('six');
            }else{
                t.html(that * oneMonthPrice);
                console.log('one');
            }
        });
        
        
        $('select.payments').on('change', function() {
            var render = $(this).parent().parent().find('.render_payment');
            $.ajax({
                url:'/admin/core/payment/getPayment',
                data: {system:this.value,price:$('#total').text()},
                type:'POST',
                success:function(data){
                    render.html(data);
                }
            });
        });
        
    });

</script>


<h3><span>123</span> <small>грн.</small></h3>

<style>
    .pay-block{
        padding: 10px;
        border:1px solid red;
    }
</style>
<div class="col-xs-12">
    <?php
    $payments = PaymentMethod::model()->findAll();

    foreach ($payments as $pay) {
        echo 'ss';
        echo $pay->renderPaymentForm();
        ?>

        <div class="row pay-block">
            <div class="col-xs-3 bg-success">
                <img src="<?= $this->assetsUrl ?>/images/<?= $pay->image ?>" alt="" />
            </div>
            <div class="col-xs-9">
                <p><?= $pay->short_text ?></p>
                <a href="#" class="btn btn-lg btn-success">Оплатить</a>

                <form id=pay name=pay method="POST" action="https://merchant.webmoney.ru/lmi/payment.asp" accept-charset="windows-1251"> 
                   


                        <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="1.0">
                        <input type="hidden" name="LMI_PAYMENT_NO" value="1">
                        <input type="hidden" name="LMI_PAYMENT_DESC" value="тестовый платеж">
                        <input type="hidden" name="LMI_PAYEE_PURSE" value="U413076124046">
                        <input type="hidden" name="LMI_RESULT_URL" value="http://buildshop.net/payment">
                        <input type="hidden" name="LMI_SUCCESS_URL" value="<?= Yii::app()->createAbsoluteUrl('/users/payment/process', array('payment_id' => 123, 'result' => true))?>">
                        <input type="hidden" name="LMI_FAIL_URL" value="http://buildshop.net/payment/fail">

                        <input type="submit" value="submit" class="btn btn-lg btn-success">
         
                </form> 


            </div>
        </div>
<?php } ?>

</div>

<select class="payments" name="system">
    <option value="privat24">Привать24</option>
    <option value="webmoney">WebMoney</option>
</select>

<div class="render_payment"></div>



<a class="payment payment-pb24" href="#"></a>
<a class="payment payment-webmoney" href="#"></a>





<div class="row">
    <div class="col-lg-5">
        Количество месяцов: <input type="text" id="months" name="months" value="1" class="form-control" />
        <div id="total" class="h3">0</div>
    </div>
</div>