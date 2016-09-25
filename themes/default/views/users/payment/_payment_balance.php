<div class="alert alert-info">
    ...
</div>

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
        $('#PaymentForm_months').change(function(){
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
        
        
        $('#PaymentForm_system').on('change', function() {
            var render = $(this).parent().parent().find('.render_payment');

            if($('#PaymentForm_months').val()){
            $.ajax({
                url:'/users/payment/renderForm?system='+this.value,
                data: {system:this.value,months:$('#PaymentForm_months').val()},
                type:'POST',
                success:function(data){
                   // render.html(data);
                   $('#total').html(data);
                }
            });
            }
        });
        //$('#PaymentForm_system').on('change',function(){
        //$('#total').load('/users/payment/renderConfigurationForm/system/'+$(this).val()+'/payment_method_id/'+$(this).attr('rel'));
        // });
        
    });

</script>


<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'user-paybalance-form',
    'enableAjaxValidation' => true, // Disabled to prevent ajax calls for every field update
    'enableClientValidation' => false,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => true,
        'errorCssClass' => 'has-error',
        'successCssClass' => 'has-success',
    ),
    'htmlOptions' => array('class' => '', 'role' => 'form')
        ));
?>
<div class="form-group">
    <?= $form->label($paymentForm, 'months', array('class' => 'control-label')); ?>
    <?= $form->textField($paymentForm, 'months', array('class' => 'form-control')); ?>
    <?= $form->error($paymentForm, 'months'); ?>

</div>
<div class="form-group">
    <?= $form->dropDownList($paymentForm, 'system', array('webmoney' => 'WebMoney', 'privat24' => 'Приват24'), array('class' => 'control-label')); ?>


</div>
<div class="text-center">
    <?= Html::submitButton(Yii::t('core', 'SAVE'), array('class' => 'btn btn-primary')); ?>
</div>

<?php $this->endWidget(); ?>

<div id="total"></div>