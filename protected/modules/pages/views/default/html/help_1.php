<div class="help">
    <a href="#" class="help-link" data-well-id="w1">Как установить цены?</a>
    <div class="well hidden" id="help-well-1">
        dasdsasda
    </div>
</div>
<script>
    
    $(function(){
        $('.help-link').click(function(){
            $(this).parent().find('.well').toggleClass('hidden');
        });
    });
</script>


<?php


        
$payments = new stdClass();
$payments->payments = (object) array(
    (object) array(
        'name' => 'Банковский перевод',
        'className' => 'payment-bank'
    ),
    (object) array(
        'name' => 'Приват 24',
        'className' => 'payment-pb24'
    ),
    (object) array(
        'name' => 'Webmoney',
        'className' => 'payment-webmoney'
    ),
);


?>

<?php foreach ($payments->payments as $pay) { ?>

    <?= Html::link(null, '#', array('class' => 'payment ' . $pay->className, 'title' => $pay->name)) ?>


<?php } ?>
<a href="#" class="btn btn-default">Кнопка</a>
<a href="#" class="btn btn-lg btn-default">Кнопка</a>
<a href="#" class="btn btn-sm btn-default">Кнопка</a>
<a href="#" class="btn btn-xs btn-default">Кнопка</a>


<a href="#" class="btn btn-buildstore">Кнопка</a>
<a href="#" class="btn btn-lg btn-buildstore">Кнопка</a>
<a href="#" class="btn btn-sm btn-buildstore">Кнопка</a>
<a href="#" class="btn btn-xs btn-buildstore">Кнопка</a>


<a href="#" class="btn btn-danger">Кнопка</a>
<a href="#" class="btn btn-lg btn-danger">Кнопка</a>
<a href="#" class="btn btn-sm btn-danger">Кнопка</a>
<a href="#" class="btn btn-xs btn-danger">Кнопка</a>


<a href="#" class="btn btn-info">Кнопка</a>
<a href="#" class="btn btn-lg btn-info">Кнопка</a>
<a href="#" class="btn btn-sm btn-info">Кнопка</a>
<a href="#" class="btn btn-xs btn-info">Кнопка</a>


<a href="#" class="btn btn-success">Кнопка</a>
<a href="#" class="btn btn-lg btn-success">Кнопка</a>
<a href="#" class="btn btn-sm btn-success">Кнопка</a>
<a href="#" class="btn btn-xs btn-success">Кнопка</a>


<a href="#" class="btn btn-primary">Кнопка</a>
<a href="#" class="btn btn-lg btn-primary">Кнопка</a>
<a href="#" class="btn btn-sm btn-primary">Кнопка</a>
<a href="#" class="btn btn-xs btn-primary">Кнопка</a>



<a href="#" class="btn btn-warning">Кнопка</a>
<a href="#" class="btn btn-lg btn-warning">Кнопка</a>
<a href="#" class="btn btn-sm btn-warning">Кнопка</a>
<a href="#" class="btn btn-xs btn-warning">Кнопка</a>