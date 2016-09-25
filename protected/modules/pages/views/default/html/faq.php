<script type="text/javascript">
    $(document).ready(function(){
        $("#myNav").affix({
            offset: { 
                top: 200 
            }
        });
        $("#myNav").on('affixed.bs.affix', function(){
            // alert("Меню навигации была прикреплена. Теперь она не прокручивается вместе со страницей.");
        });
    });
</script>

<div class="container">
    <div class="row">
        <div class="col-xs-3">
            <div id="myNav" class="list-group">
                <ul class="list-unstyled">
                    <li>
                        <a href="#cart">Корзина</a>
                        <ul >
                            <li><a href="#cart_notify">Уведомления</a>
                            <li><a href="#cart_status">Статусы заказов</a>
                                <li><a href="#cart_orders">Заказы</a>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-9">
            <section id="cart">
                <h3>Корзина</h3>
                <p>Содержание раздела 1...</p>
                <section id="cart_notify">
                    <h4>Уведомления</h4>
                    <p class="well">В этом разделе хронятся уведомление пользователей о том чтобы им сообщили когда товар появится в налиции.</p>
                </section>
                <section id="cart_status">
                    <h4>Статусы заказов</h4>
                    <p class="well">Статусы заказов - в этом разделе Вы можете управлять статусами.</p>
                </section>
                <section id="cart_orders">
                    <h4>Заказы</h4>
                    <p class="well">В разделе заказов Вы можете управлять заказами</p>
                </section>
            </section>

        </div> 
    </div>
</div>


