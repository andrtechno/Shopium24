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
<?php
Yii::import('app.hosting_api.*');
?>
<div class="container">
    <div class="row">
        <div class="col-xs-3">
            <div id="myNav" class="list-group">
                <ul class="list-unstyled">
                    <li>
                        <a href="#selfdomain">Сайт на своем домене</a>

                    </li>
                </ul>
            </div>
        </div>
        <div class="col-xs-9">
            <section id="selfdomain">
                <h3>Сайт на своем домене</h3>
                <div class="well">

                    <b>Вариат 1:</b><br/>
                    Если у вас уже есть доменное имя. Следует войти в управление доменна и прописать NS сервера.<br/>

                    <b>NS сервера</b>
                    <ul>
                        <li>ns1.fastdns.hosting</li>
                        <li>ns2.fastdns.hosting</li>
                        <li>ns3.fastdns.hosting</li>
                    </ul>
                    После вам необходимо войти в Ваш профиль. В разделе "Доменное имя", затем прописать название вашего доменного имени.
                    <br/><br/>
                    <b>Вариат 2:</b><br/>
                    Если у Вас еще нету доменного имени, Вы можете <a target="_blank" href="https://www.ukraine.com.ua/?page=<?= CMethod::ACCOUNT_ID?>">приобрести</a> у нашего партнера.<br/>
                    После покупки доменного имени вам необходимо зайти в профиль и прописать свой домен, следуя инструкции.<br/>
                    <small>полезно: <a href="/html/domain">цены на доменное имя</a>, <a target="_blank" href="https://www.ukraine.com.ua/domains/?page=<?= CMethod::ACCOUNT_ID?>">проверить доменное имя</a></small><br/>

                </div>
            </section>


        </div> 
    </div>
</div>


