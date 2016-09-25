<?php
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
$xddialog = new Dialog;

$dialogs = $xddialog->get_user_dialogs();
$out = '';
$xddialog->send('vasya');
print_r($dialogs);
foreach($dialogs as $dg){
    $out.='
    <div class="dialog '.(!$dg['msg_status']?'newmsg':'').'">
        <div class="float_left">
            <span class="nikname"><a href="#" id="user_'.$dg['sender_id'].'">'.$dg['sender_name'].'</a></span>
            <span class="message_time">'.date('H:i:s d/m/Y',$dg['public']).'</span>
            <div>'.$dg['message'].'</div>
        </div>
        <div class="float_right">
            <input  class="btn gotodialog" id="dialog_'.$dg['hash'].'" value="ПЕРЕЙТИ К ДИАЛОГУ"/>
        </div>
        <div class="clearex"></div>
    </div>';
}
echo $out;