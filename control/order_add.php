<?php
session_start();

// Подключение и выбор БД
include "../config.php";


function CheckStr($srt){ 
 return !mb_ereg("[^A-Za-zа-яА-Я0-9_-]",$srt);     
}

function CheckInt($srt){ 
 return !mb_ereg("[^0-9]",$srt);     
}

function ValFail($srt){ 
 return '<script type="text/javascript">$("#'.$srt.'").css({"border-color":"red","background-color":"#FFBABA","color":"#000"});</script>';     
}

function ValOk($srt){ 
 return '<script type="text/javascript">$("#'.$srt.'").css({"border-color":"#187C22","background-color":"#BAE2BD","color":"#000"});</script>';     
}

$error='Проверьте поле  <font color="red">';
$err='</font><br>';
$validate=true;



$cl_cont=(int)$_POST['cl_cont'];
$tr_cont=(int)$_POST['tr_cont'];



if(@$_POST['cl_cash_min']!=""&&!CheckInt($_POST['cl_cash_min'])){echo $error.'"Вычет клиента"'.$err.ValFail('cl_cash_min'); $validate=false;} else {echo ValOk('cl_cash_min');}
if(@$_POST['cl_cash_plus']!=""&&!CheckInt($_POST['cl_cash_plus'])){echo $error.'"Надбавка клиента"'.$err.ValFail('cl_cash_plus'); $validate=false;} else {echo ValOk('cl_cash_plus');}

if(@$_POST['tr_cash']==""||!CheckInt($_POST['tr_cash'])){echo $error.'"Ставка перевозчика"'.$err.ValFail('tr_cash'); $validate=false;} else {echo ValOk('tr_cash');}

if(@$_POST['tr_cash_min']!=""&&!CheckInt($_POST['tr_cash_min'])){echo $error.'"Вычет перевозчика"'.$err.ValFail('tr_cash_min'); $validate=false;} else {echo ValOk('tr_cash_min');}
if(@$_POST['tr_cash_plus']!=""&&!CheckInt($_POST['tr_cash_plus'])){echo $error.'"Надбавка перевозчика"'.$err.ValFail('tr_cash_plus'); $validate=false;} else {echo ValOk('tr_cash_plus');}

if(@$_POST['cl_komissia']!=""&&!CheckInt($_POST['cl_komissia'])){echo $error.'"Комиссия клиента"'.$err.ValFail('cl_komissia'); $validate=false;} else {echo ValOk('cl_komissia');}
if(@$_POST['tr_komissia']!=""&&!CheckInt($_POST['tr_komissia'])){echo $error.'"Комиссия перевозчика"'.$err.ValFail('tr_komissia'); $validate=false;} else {echo ValOk('tr_komissia');}

//if(@$_POST['cl_tfpay']==""||!CheckInt($_POST['cl_tfpay'])){echo $error.'"Период рассчетов клиента"'.$err.ValFail('cl_tfpay'); $validate=false;} else {echo ValOk('cl_tfpay');}

//if(@$_POST['tr_tfpay']==""||!CheckInt($_POST['tr_tfpay'])){echo $error.'"Период рассчетов перевозчика"'.$err.ValFail('tr_tfpay'); $validate=false;} else {echo ValOk('tr_tfpay');}


// Проверка лимита - этот участок тормозит. Удалить?

$query_check = "SELECT `id`,`name`,`cl_limit`,`cl_limit_order` FROM `clients` WHERE `id`='".mysql_escape_string((int)$_POST['client'])."'";
$result_check = mysql_query($query_check) or die(mysql_error());
$cl_check = mysql_fetch_row($result_check);

$limit_check=0;
$dolg_check="";
$query_ord = "SELECT `id`,`cl_cash`,`cl_tfpay`,`cl_event`,`date_in1`,`date_out2`,`date_in2`,`date_out1` FROM `orders` WHERE `client`='".mysql_escape_string((int)$_POST['client'])."'";
$result_ord = mysql_query($query_ord) or die(mysql_error());
while($row_ord = mysql_fetch_array($result_ord)) {
$cl_pay=0;

$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($row_ord['id'])."' AND `delete`='0' AND `appoint`='1' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$cl_pay=(int)$pay[0]+(int)$cl_pay;
}



if((int)$row_ord['cl_cash']!=((int)$cl_pay/100)) {
$limit_check=(int)$limit_check+(int)$row_ord['cl_cash']-((int)$cl_pay/100);

$cl_event_check="";


switch ($row_ord['cl_event']) {
case '1': if($row_ord['date_in2']!="0000-00-00"&&$row_ord['date_in2']!="1970-01-01"&&$row_ord['date_in2']!="")$cl_event_check=$row_ord['date_in2']; else $cl_event_check=$row_ord['date_in1'];break;
case '2': if($row_ord['date_out2']!="0000-00-00"&&$row_ord['date_out2']!="1970-01-01"&&$row_ord['date_out2']!="")$cl_event_check=$row_ord['date_out2']; else $cl_event_check=$row_ord['date_out1'];break;
case '3': $query_docs_chk = "SELECT `date_cl_receve` FROM `docs` WHERE `order`='".mysql_escape_string($row_ord['id'])."'";
$result_docs_chk = mysql_query($query_docs_chk) or die(mysql_error());$cl_events_check=mysql_fetch_row($result_docs_chk);if($cl_events_check[0]!="0000-00-00"&&$cl_events_check[0]!="1970-01-01"&&$cl_events_check[0]!=""){
$cl_event_check=$cl_events_check[0];};break;
case '4': $query_docs_chk = "SELECT `date_cl_receve` FROM `docs` WHERE `order`='".mysql_escape_string($row_ord['id'])."'";
$result_docs_chk = mysql_query($query_docs_chk) or die(mysql_error());$cl_events_check=mysql_fetch_row($result_docs_chk);if($cl_events_check[0]!="0000-00-00"&&$cl_events_check[0]!="1970-01-01"&&$cl_events_check[0]!=""){
$cl_event_check=$cl_events_check[0];};break;
}

if($cl_event_check!="0000-00-00"&&$cl_event_check!="1970-01-01"&&$cl_event_check!=""){

$cl_event_date=date('d/m/Y', strtotime('+'.(int)$orders['cl_tfpay'].' day', strtotime($cl_event_check)));

$elements  = explode("/",$cl_event_date);
$current_date = mktime (0,0,0,date("m") ,date("d"),date("Y")); //дата сегодня
$old_date = mktime (0,0,0,$elements[1],$elements[0],$elements[2]); 
$difference = ($current_date - $old_date);
$difference_in_days = floor($difference / 86400); //разница в днях

if($difference_in_days>=0) $dolg_check.='<b>'.$row_ord['id'].'<b> ('.$difference_in_days.' дн.);';
		}
	}
}


// Конец участка, который тормозит.


if($validate&&@$_POST['edit']!="1")
{


if($dolg_check!="" && $cl_check[3]==0) {
	echo '<font size="4" color="red">Невозможно создать заявку!</font><br><br>У клиента имеются задолженности по заявкам '.$dolg_check.'<br><br><font size="1">Примите меры для ликвидации задолженности...</font><br><br>';$validate=false;
}

//if(@$_POST['transporter']==0&&(@$_POST['ati_km']=='0'||@$_POST['ati_km']==''||!CheckInt($_POST['ati_km']))) {echo $error.'"Заполните километраж в закладке Адреса"'.$err.ValFail('ati_km'); $validate=false;} else {echo ValOk('ati_km');}

if((int)$cl_check[2]<(int)@$_POST['cl_cash']){echo '<font size="4" color="red">Невозможно создать заявку!</font><br><br>Ставка клиента ('.(int)@$_POST['cl_cash'].' '.$_POST['cl_currency'].') больше<br>лимита дебиторской задолженности<br><br><b>Лимит: '.(int)$cl_check[2].' '.$_POST['cl_currency'].'</b><br><br><font size="4">Обратитесь к Директору...</font>'; $validate=false;} else {


if((int)$cl_check[2]<((int)$limit_check+(int)@$_POST['cl_cash'])){echo '<font size="4" color="red">Невозможно создать заявку!</font><br><br>Достигнут лимит дебиторской задолженности ('.(int)$cl_check[2].' '.$_POST['cl_currency'].')<br> для  клиента <b>«'.addslashes($cl_check[1]).'»</b><br><br><b>Долг составляет: '.number_format((int)$limit_check, 2, ',', ' ').' '.$_POST['cl_currency'].'</b><br>Плюс ставка клиента: '.(int)@$_POST['cl_cash'].' '.$_POST['cl_currency'].'<br><br><font size="1">Примите меры для ликвидации задолженности...</font>'; $validate=false;}
}
} else {



$query_change_check = "SELECT `client` FROM `orders` WHERE `id`='".mysql_escape_string((int)$_POST['order'])."'";
$result_change_check = mysql_query($query_change_check) or die(mysql_error());
$cl_change_check = mysql_fetch_row($result_change_check);	
	
if((int)$cl_check[2]<(int)@$_POST['cl_cash']&&$cl_change_check[0]!=(int)@$_POST['client']){echo '<font size="4" color="red">Невозможно обновить заявку!</font><br>Клиент изменён!<br>Ставка клиента ('.(int)@$_POST['cl_cash'].' '.$_POST['cl_currency'].') больше<br>лимита дебиторской задолженности<br><br><b>Лимит: '.(int)$cl_check[2].' '.$_POST['cl_currency'].'</b><br><br><font size="4">Обратитесь к Директору...</font>'; $validate=false;} else {


if((int)$cl_check[2]<((int)$limit_check+(int)@$_POST['cl_cash'])&&$cl_change_check[0]!=(int)@$_POST['client']){echo '<font size="4" color="red">Невозможно обновить заявку!</font><br><b>Клиент изменён!</b><br>Достигнут лимит дебиторской задолженности ('.(int)$cl_check[2].' '.$_POST['cl_currency'].')<br> для  клиента <b>«'.addslashes($cl_check[1]).'»</b><br><br><b>Долг составляет: '.number_format((int)$limit_check, 2, ',', ' ').' '.$_POST['cl_currency'].'</b><br>Плюс ставка клиента: '.(int)@$_POST['cl_cash'].' '.$_POST['cl_currency'].'<br><br><font size="1">Примите меры для ликвидации задолженности...</font>'; $validate=false;}
}
}


if($validate)
{
$order=$_POST['order'];
$cash_ok=$_POST['cash_ok'];
$order_id=(int)$_POST['order_id'];

$tr_days=(int)$_POST['tr_days'];

$days_tfpay=(int)$_POST['days_tfpay'];

	
$in_adress=$_POST['in_adr'];
if ($in_adress){
	 foreach($in_adress as $in){
	 $in_adr=$in.'&'.$in_adr;
	 }
	}
	
$out_adress=$_POST['out_adr'];
if ($out_adress){
	 foreach($out_adress as $out){
	 $out_adr=$out.'&'.$out_adr;
	 }
	}

if(@$_POST['transp_mode']=='vtl'){$car=$_POST['tr_autopark1'].'&'.$_POST['tr_autopark2'].'&'.$_POST['tr_autopark3'];} else $car=(int)$_POST['car'];



if(@$_POST['data']!=""&&@$_POST['edit']=="1"){
$elements  = explode("/",$_POST['data']);
$data=date("Y-m-d",strtotime($elements[2]."-".$elements[1]."-".$elements[0]));
}
if(@$_POST['data']==""&&@$_POST['edit']=="1"){
$data=date("Y-m-d"); 
}
if(@$_POST['edit']!="1"&&@$_POST['data']=="")$data=date("Y-m-d"); if(@$_POST['edit']!="1"&&@$_POST['data']!=""){$elements  = explode("/",$_POST['data']);
$data=date("Y-m-d",strtotime($elements[2]."-".$elements[1]."-".$elements[0]));}
	

$cl_rashod_sb = (int)$_POST['cl_rashod_sb'];
$cl_rashod_na_cl = (int)$_POST['cl_rashod_na_cl'];

$cl_komissia = (int)$_POST['cl_komissia'];
$tr_komissia = (int)$_POST['tr_komissia'];

$cl_currency = $_POST['cl_currency'];
$tr_currency = $_POST['tr_currency'];

$manager=$_POST['manager'];
$tr_manager=(int)$_POST['tr_manager'];

$client=(int)$_POST['client'];
$transporter=(int)$_POST['transporter'];

$cl_pref=$_POST['cl_pref'];
$tr_pref=$_POST['tr_pref'];

$print_cl=mysql_real_escape_string(stripslashes($_POST['print_cl']));
$print_tr=mysql_real_escape_string(stripslashes($_POST['print_tr']));

$car_notify=mysql_real_escape_string(stripslashes($_POST['car_notify']));
$notify=mysql_real_escape_string(stripslashes($_POST['order_notify']));

$cl_cash=(int)$_POST['cl_cash'];
$cl_kop=mysql_real_escape_string(stripslashes($_POST['cl_kop']));

$tr_cash=(int)$_POST['tr_cash'];

$cl_tfpay=(int)$_POST['cl_tfpay'];
$tr_tfpay=(int)$_POST['tr_tfpay'];

$cl_minus=(int)$_POST['cl_cash_min'];
$cl_plus=(int)$_POST['cl_cash_plus'];

$tr_minus=(int)$_POST['tr_cash_min'];
$tr_plus=(int)$_POST['tr_cash_plus'];

$tr_gruz_worker=(int)$_POST['tr_gruz_worker'];

$krugoreis=(int)$_POST['krugoreis'];

$cl_event=(int)$_POST['cl_event'];
$tr_event=(int)$_POST['tr_event'];

$cl_nds=(int)$_POST['cl_nds'];
if($transporter==2)$tr_nds=1; else $tr_nds=(int)$_POST['tr_nds'];

$ati_km=(int)$_POST['ati_km'];

$tr_receive=(int)$_POST['tr_receive'];

if(@$_POST['transp_mode']=='vtl'){

$tr_cont=(int)$_POST['vtl_cont_select'];
}

$gruz_name=mysql_real_escape_string(stripslashes($_POST['gruz_name'])); 
$gruz_m=(float)$_POST['gruz_m'];
$gruz_v=(int)$_POST['gruz_v'];
$gruz_num=(int)$_POST['gruz_num'];
$gruz_load=(int)$_POST['gruz_load'];

$in_elements  = explode("/",$_POST['in_data1']);
$in_data1=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));
$in_elements  = explode("/",$_POST['in_data2']);
$in_data2=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));

$in_time11=mysql_real_escape_string(stripslashes($_POST['in_time11']));
$in_time12=mysql_real_escape_string(stripslashes($_POST['in_time12']));
$in_time21=mysql_real_escape_string(stripslashes($_POST['in_time21']));
$in_time22=mysql_real_escape_string(stripslashes($_POST['in_time22']));

$out_elements1= explode("/",$_POST['out_data1']);
$out_data1=date("Y-m-d",strtotime($out_elements1[2]."-".$out_elements1[1]."-".$out_elements1[0]));
$out_elements2= explode("/",$_POST['out_data2']);
$out_data2=date("Y-m-d",strtotime($out_elements2[2]."-".$out_elements2[1]."-".$out_elements2[0]));
$out_time1=$_POST['out_time1'];
$out_time2=$_POST['out_time2'];

if($_POST['out_data2']!="")$date_plan=date('Y-m-d', strtotime('+'.($cl_tfpay+16).' day', strtotime($out_data2))); else $date_plan=date('Y-m-d', strtotime('+'.($cl_tfpay+16).' day', strtotime($out_data1)));


$query_check = "SELECT * FROM `orders` ORDER BY `id` DESC LIMIT 1";
$result_check = mysql_query($query_check) or die(mysql_error()); 
$check = mysql_fetch_array($result_check);

$ord_check=true;

if($check['data']==$data&&$check['manager']==$manager&&$check['client']==$client&&$check['cl_nds']==$cl_nds&&$check['cl_cash']==$cl_cash&&$check['cl_tfpay']==$cl_tfpay&&$check['cl_event']==$cl_event&&$check['cl_minus']==$cl_minus&&$check['cl_plus']==$cl_plus&&$check['in_adress']==$in_adr&&$check['out_adress']==$out_adr&&$check['transp']==$transporter&&$check['tr_nds']==$tr_nds&&$check['tr_cash']==$tr_cash&&$check['tr_tfpay']==$tr_tfpay&&$check['tr_event']==$tr_event&&$check['tr_minus']==$tr_minus&&$check['tr_plus']==$tr_plus&&$check['tr_auto']==$car&&$check['gruz']==$gruz_name&&$check['gr_m']==$gruz_m&&$check['date_in1']==$in_data1&&$check['in_time11']==$in_time11&&$check['date_out1']==$out_data1)$ord_check=false;






if($ord_check){

$str_in = explode('&',$in_adr);
$query_adress = "SELECT `city` FROM `adress` WHERE `id`='".(int)$str_in[0]."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());
$res_in= mysql_fetch_row($result_adress);


$str_out = explode('&',$out_adr);
$str_adr_out = (int)sizeof($str_out)-2;
$query_adress = "SELECT `city` FROM `adress` WHERE `id`='".$str_out[$str_adr_out]."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());
$res_out= mysql_fetch_row($result_adress);

$query_adress_compare_cl = "SELECT `in_city`,`out_city` FROM `ways` WHERE `cl`='".mysql_escape_string((int)$client)."'";
$result_adress_compare_cl = mysql_query($query_adress_compare_cl) or die(mysql_error());
$ic=0;
while($adress_cl = mysql_fetch_row($result_adress_compare_cl)) {
if($adress_cl[0]==$res_in[0]&&$adress_cl[1]==$res_out[0]){$ic++;}
}
if($ic==0){$query_way = "INSERT INTO `ways` (`tr`,`cl`,`in_city`,`out_city`,`auto_id`) VALUES ('0','$client','$res_in[0]','$res_out[0]','$car')";
$result_way = mysql_query($query_way) or die(mysql_error());}


$query_adress_compare_tr = "SELECT `in_city`,`out_city` FROM `ways` WHERE `tr`='".mysql_escape_string((int)$transporter)."'";
$result_adress_compare_tr = mysql_query($query_adress_compare_tr) or die(mysql_error());
$it=0;
while($adress_tr = mysql_fetch_row($result_adress_compare_tr)) {
if($adress_tr[0]==$res_in[0]&&$adress_tr[1]==$res_out[0]){$it++;}
}
if($it==0){$query_way = "INSERT INTO `ways` (`cl`,`tr`,`in_city`,`out_city`,`auto_id`) VALUES ('0','$transporter','$res_in[0]','$res_out[0]','$car')";
$result_way = mysql_query($query_way) or die(mysql_error());}
}

// Managers can edit only their orders - now disabled
//if($manager==$_SESSION['user_id']||($tr_manager==$_SESSION["user_id"]&&@$_POST['gruz_id']!="")||($tr_manager==$_SESSION["user_id"]&&($client==774||$client==635||$client==468))||$_SESSION['group']==2||$_SESSION['group']==1||$_SESSION['group']==4||$manager==11){
if($_SESSION['group']!=5){

if(@$cash_ok=="2"){
if(@$_POST['edit']=="1")
{
// Ведение логов
$query_log = "SELECT `client`,`transp`,`cl_cash`,`tr_cash`,`cl_nds`,`tr_nds`,`cl_event`,`tr_event`,`cl_tfpay`,`tr_tfpay` FROM `orders` WHERE `Id`='".$order."'";
$result_log = mysql_query($query_log) or die(mysql_error()); 
$logs = mysql_fetch_array($result_log);

	
$query = "UPDATE `orders` SET `tr_manager`='$tr_manager',`client`='$client',`cl_pref`='$cl_pref',`cl_nds`='$cl_nds',`cl_event`='$cl_event',`in_adress`='$in_adr',`out_adress`='$out_adr',`cl_cash`='$cl_cash',`transp`='$transporter',`tr_pref`='$tr_pref',`tr_nds`='$tr_nds',`tr_event`='$tr_event',`tr_cash`='$tr_cash',`cl_minus`='$cl_minus',`cl_plus`='$cl_plus',`tr_minus`='$tr_minus',`tr_plus`='$tr_plus',`cl_tfpay`='$cl_tfpay',`tr_tfpay`='$tr_tfpay',`gruz`='$gruz_name',`gr_m`='$gruz_m',`gr_v`='$gruz_v',`gr_number`='$gruz_num',`gr_load`='$gruz_load',`tr_auto`='$car',`cl_cont`='$cl_cont',`tr_cont`='$tr_cont',`rent`='0',`tr_receive`='$tr_receive',`print_cl`='$print_cl',`print_tr`='$print_tr',`date_in1`='$in_data1',`time_in11`='$in_time11',`time_in12`='$in_time12',`date_in2`='$in_data2',`time_in21`='$in_time21',`time_in22`='$in_time22',`date_out1`='$out_data1',`date_out2`='$out_data2',`time_out1`='$out_time1',`time_out2`='$out_time2',`car_notify`='$car_notify',`data`='$data',`id`='$order_id',`notify`='$notify',`manager`='$manager',`km`='$ati_km',`date_plan`='$date_plan',`krugoreis`='$krugoreis',`days_tfpay`='$days_tfpay',`cl_kop`='$cl_kop',`tr_days`='$tr_days',`cl_rashod_na_cl`='$cl_rashod_na_cl',`cl_rashod_sb`='$cl_rashod_sb',`cl_komissia`='$cl_komissia',`tr_komissia`='$tr_komissia',`cl_currency`='$cl_currency',`tr_currency`='$tr_currency',`tr_gruz_worker`='$tr_gruz_worker' WHERE `id`='$order'";
$query_block = "SELECT `block` FROM `orders` WHERE `Id`='".$order."'";
$result_block = mysql_query($query_block) or die(mysql_error());
$block = mysql_fetch_row($result_block);
if($block[0]!='1')
{
$result = mysql_query($query) or die(mysql_error());

// Удалить потом!!!
//$query_docs = "UPDATE `docs` SET `order`='".$order_id."' WHERE `order`='".$order."'";
//$result_docs = mysql_query($query_docs) or die(mysql_error());

// Автообновление платежа для заявок с перевозчиком Транспортная компания
if((int)$transporter==2){
	$query_autopay = "UPDATE `pays` SET `date`='".$data."',`cash`='".((int)$tr_cash*100)."' WHERE `order`='".$order."' AND `way`='2' AND `notify`='auto_vtl'";
	$result_autopay = mysql_query($query_autopay) or die(mysql_error());
} else {
	$query_autopay = "UPDATE `pays` SET `delete`='1' WHERE `order`='".$order."' AND `way`='2' AND `notify`='auto_vtl'";
	$result_autopay = mysql_query($query_autopay) or die(mysql_error());
}



// Ведение логов

$log_dop='';

if($logs['client']!=$client)$log_dop='<b>клиент</b> - с '.$logs['client'].' на '.$client.' ';
if($logs['transp']!=$transporter)$log_dop.='<b>перевозчик</b> - с '.$logs['transp'].' на '.$transporter.' ';

if($logs['cl_cash']!=$cl_cash)$log_dop.='<b>ставка клиента</b> - с '.$logs['cl_cash'].' на '.$cl_cash.' ';
if($logs['tr_cash']!=$tr_cash)$log_dop.='<b>ставка перевозчика</b> - с '.$logs['tr_cash'].' на '.$tr_cash.' ';

//if($logs['cl_nds']!=$cl_nds)$log_dop.='<b>способ опл. клиентом</b> - с '.$logs['cl_nds'].' на '.$transporter.' ';
//if($logs['tr_nds']!=$tr_nds)$log_dop.='<b>способ опл. перевозчику</b> - с '.$logs['tr_nds'].' на '.$tr_nds.' ';


if($log_dop=='')$log_dop=' - ';

$query_logs = "INSERT INTO `logs` (`user`,`log_message`) VALUES ('".$_SESSION["user_id"]."','Отредактирована заявка №".$order." ( Изменено: ".$log_dop.")') ";
$result_logs = mysql_query($query_logs) or die(mysql_error());

echo 'Сохранено!|1|'.$order;
} else {

if(@$cash_ok=="0"&&$_SESSION["group"]==3){
echo 'Заявка нерентабельна! Вы не можете её сохранить...';	
} else {
$query_block = "SELECT `block` FROM `orders` WHERE `Id`='".$order."'";
$result_block = mysql_query($query_block) or die(mysql_error());
$block = mysql_fetch_row($result_block);

// Managers can edit only their orders - now disabled	
//if($tr_manager==$_SESSION["user_id"]&&$block[0]!='1') {
	
if($block[0]!='1') {	
$query = "UPDATE `orders` SET `transp`='$transporter',`tr_pref`='$tr_pref',`tr_nds`='$tr_nds',`tr_event`='$tr_event',`tr_cash`='$tr_cash',`tr_minus`='$tr_minus',`tr_plus`='$tr_plus',`tr_tfpay`='$tr_tfpay',`tr_auto`='$car',`tr_receive`='$tr_receive',`print_tr`='$print_tr',`notify`='$notify' WHERE `id`='$order'";	
$result = mysql_query($query) or die(mysql_error());
echo '<b>Сохранено!</b><br>Перевозчик и транспорт обновлён.1|1|'.$order;

$query_logs = "INSERT INTO `logs` (`user`,`log_message`) VALUES ('".$_SESSION["user_id"]."','Отредактирована заявка №".$order." (".date("d/m/Y",strtotime($data)).")')";
$result_logs = mysql_query($query_logs) or die(mysql_error());

}
else echo '<font size="3" color="red">Заявка заблокирована для редактирования!</font>';
}

}	
} else {
$query_check = "SELECT `id` FROM `orders` WHERE `id`='".$order_id."'";
$result_check = mysql_query($query_check) or die(mysql_error());
if (mysql_num_rows($result_check)==0&&$ord_check)
{

if((int)$_POST['international']==1) {
$query_settings = "SELECT `international_number` FROM `settings`";
$result_settings = mysql_query($query_settings) or die(mysql_error());
$settings = mysql_fetch_row($result_settings);
$international_number = 'М-'.$settings[0];
$query_settings = "UPDATE `settings` SET `international_number`='".((int)$settings[0]+1)."'";
$result_settings = mysql_query($query_settings) or die(mysql_error());
} else $international_number = "0"; 
	
	
	
$query = "INSERT INTO `orders` (`manager`,`tr_manager`,`client`,`cl_pref`,`cl_nds`,`cl_event`,`data`,`in_adress`,`out_adress`,`cl_cash`,`transp`,`tr_pref`,`tr_nds`,`tr_event`,`tr_cash`,`cl_minus`,`cl_plus`,`tr_minus`,`tr_plus`,`cl_tfpay`,`tr_tfpay`,`gruz`,`gr_m`,`gr_v`,`gr_number`,`gr_load`,`tr_auto`,`cl_cont`,`tr_cont`,`tr_receive`,`print_cl`,`print_tr`,`date_in1`,`time_in11`,`time_in12`,`date_in2`,`time_in21`,`time_in22`,`date_out1`,`date_out2`,`time_out1`,`time_out2`,`car_notify`,`id`,`notify`,`km`,`date_plan`,`krugoreis`,`days_tfpay`,`international_number`,`cl_kop`,`tr_days`,`cl_rashod_na_cl`,`cl_rashod_sb`,`cl_komissia`,`tr_komissia`,`cl_currency`,`tr_currency`,`tr_gruz_worker`) VALUES ('$manager','$tr_manager','$client','$cl_pref','$cl_nds','$cl_event','$data','$in_adr','$out_adr','$cl_cash','$transporter','$tr_pref','$tr_nds','$tr_event','$tr_cash','$cl_minus','$cl_plus','$tr_minus','$tr_plus','$cl_tfpay','$tr_tfpay','$gruz_name','$gruz_m','$gruz_v','$gruz_num','$gruz_load','$car','$cl_cont','$tr_cont','$tr_receive','$print_cl','$print_tr','$in_data1','$in_time11','$in_time12','$in_data2','$in_time21','$in_time22','$out_data1','$out_data2','$out_time1','$out_time2','$car_notify','$order_id','$notify','$ati_km','$date_plan','$krugoreis','$days_tfpay','$international_number','$cl_kop','$tr_days','$cl_rashod_na_cl','$cl_rashod_sb','$cl_komissia','$tr_komissia','$cl_currency','$tr_currency','$tr_gruz_worker')";

$result = mysql_query($query) or die(mysql_error());
echo 'Сохранено!|1|'.mysql_insert_id();

$log_order=mysql_insert_id();

$query_docs = "INSERT INTO `docs` (`order`) VALUES ('".$log_order."')";
$result_docs = mysql_query($query_docs) or die(mysql_error());

if($dolg_check!=""){
$query_limit_order = "UPDATE `clients` SET `cl_limit_order`=`cl_limit_order`-1 WHERE `id`='".mysql_escape_string($client)."'";
$result_limit_order = mysql_query($query_limit_order) or die(mysql_error());
}



$query_settings_check = "SELECT `international_number` FROM `orders` WHERE `id`='".mysql_escape_string($log_order)."'";
$result_settings_check = mysql_query($query_settings_check) or die(mysql_error());
$settings_check = mysql_fetch_row($result_settings_check);





if(@$_POST['gruz_id']!=""){
$query_gruz = "UPDATE `cl_gruz` SET `order`='".mysql_escape_string($log_order)."' WHERE `id`='".mysql_escape_string((int)$_POST['gruz_id'])."'";
$result_gruz = mysql_query($query_gruz) or die(mysql_error());
}



// Автосоздание платежа для заявок с перевозчиком Траспортная компания
if((int)$transporter==2){
$query_autopay = "INSERT INTO `pays` (`date`,`way`,`nds`,`category`,`appoint`,`order`,`cash`,`status`,`notify`,`add_name`,`payment_source`,`del_id`,`pay_bill`,`car_id`) VALUES ('".$data."','2','1','1','2','".$log_order."','".((int)$tr_cash*100)."','1','auto_vtl','0','0','0','0','0')";
$result_autopay = mysql_query($query_autopay) or die(mysql_error());
}

//$query_trip = "INSERT INTO `vtl_trip` (`order`) VALUES ('".mysql_insert_id()."')";
//$result_trip = mysql_query($query_trip) or die(mysql_error());

$query_logs = "INSERT INTO `logs` (`user`,`log_message`) VALUES ('".$_SESSION["user_id"]."','Создана заявка ".$international_number." №".$log_order." (".date("d/m/Y",strtotime($data)).")')";
$result_logs = mysql_query($query_logs) or die(mysql_error());
} else echo "Ошибка! Заявка с номером <b>".$order_id."</b> уже существует в базе!";
}
}

if(@$cash_ok=="0"&&$_SESSION["group"]==3){
echo 'Заявка нерентабельна! Вы не можете её сохранить...';	
}

if(@$cash_ok=="1"||(@$cash_ok=="0"&&$_SESSION["group"]!=3)){
if(@$_POST['order_code']!="")
{
$order_code=$_POST['order_code'];
$query_code = "SELECT `code` FROM `security_code`";
$result_code = mysql_query($query_code) or die(mysql_error());
$code = mysql_fetch_row($result_code);
if($code[0]==$order_code){
if(@$_POST['edit']=="1")
{
$query = "UPDATE `orders` SET `tr_manager`='$tr_manager',`client`='$client',`cl_pref`='$cl_pref',`cl_nds`='$cl_nds',`cl_event`='$cl_event',`in_adress`='$in_adr',`out_adress`='$out_adr',`cl_cash`='$cl_cash',`transp`='$transporter',`tr_pref`='$tr_pref',`tr_nds`='$tr_nds',`tr_event`='$tr_event',`tr_cash`='$tr_cash',`cl_minus`='$cl_minus',`cl_plus`='$cl_plus',`tr_minus`='$tr_minus',`tr_plus`='$tr_plus',`cl_tfpay`='$cl_tfpay',`tr_tfpay`='$tr_tfpay',`gruz`='$gruz_name',`gr_m`='$gruz_m',`gr_v`='$gruz_v',`gr_number`='$gruz_num',`gr_load`='$gruz_load',`tr_auto`='$car',`cl_cont`='$cl_cont',`tr_cont`='$tr_cont',`rent`='0',`tr_receive`='$tr_receive',`print_cl`='$print_cl',`print_tr`='$print_tr',`date_in1`='$in_data1',`time_in11`='$in_time11',`time_in12`='$in_time12',`date_in2`='$in_data2',`time_in21`='$in_time21',`time_in22`='$in_time22',`date_out1`='$out_data1',`date_out2`='$out_data2',`time_out1`='$out_time1',`time_out2`='$out_time2',`car_notify`='$car_notify',`data`='$data',`id`='$order_id',`notify`='$notify',`manager`='$manager',`km`='$ati_km',`date_plan`='$date_plan',`krugoreis`='$krugoreis',`days_tfpay`='$days_tfpay',`cl_kop`='$cl_kop',`tr_days`='$tr_days',`cl_rashod_na_cl`='$cl_rashod_na_cl',`cl_rashod_sb`='$cl_rashod_sb',`cl_komissia`='$cl_komissia',`tr_komissia`='$tr_komissia',`cl_currency`='$cl_currency',`tr_currency`='$tr_currency',`tr_gruz_worker`='$tr_gruz_worker' WHERE `id`='$order'";
$query_block = "SELECT `block` FROM `orders` WHERE `Id`='".$order."'";
$result_block = mysql_query($query_block) or die(mysql_error());
$block = mysql_fetch_row($result_block);
if($block[0]!='1')
{
$result = mysql_query($query) or die(mysql_error());

$query_logs = "INSERT INTO `logs` (`user`,`log_message`) VALUES ('".$_SESSION["user_id"]."','Отредактирована заявка №".$order." (".date("d/m/Y",strtotime($data)).")')";
$result_logs = mysql_query($query_logs) or die(mysql_error());

// Удалить потом!!!
//$query_docs = "UPDATE `docs` SET `order`='".$order_id."' WHERE `order`='".$order."'";
//$result_docs = mysql_query($query_docs) or die(mysql_error());

// Автообновление платежа для заявок с перевозчиком Транспортна компания
if((int)$transporter==2){
$query_autopay = "UPDATE `pays` SET `date`='".$data."',`cash`='".((int)$tr_cash*100)."' WHERE `order`='".$order."' AND `way`='2' AND `notify`='auto_vtl'";
$result_autopay = mysql_query($query_autopay) or die(mysql_error());
} else {
$query_autopay = "UPDATE `pays` SET `delete`='1' WHERE `order`='".$order."' AND `way`='2' AND `notify`='auto_vtl'";
$result_autopay = mysql_query($query_autopay) or die(mysql_error());
}

echo 'Сохранено!|1|'.$order;
} else {

if(@$cash_ok=="0"&&$_SESSION["group"]==3){
echo 'Заявка нерентабельна! Вы не можете её сохранить...';	
} else {
$query_block = "SELECT `block` FROM `orders` WHERE `Id`='".$order."'";
$result_block = mysql_query($query_block) or die(mysql_error());
$block = mysql_fetch_row($result_block);
	
if($tr_manager==$_SESSION["user_id"]&&$block[0]!='1') {
$query = "UPDATE `orders` SET `transp`='$transporter',`tr_pref`='$tr_pref',`tr_nds`='$tr_nds',`tr_event`='$tr_event',`tr_cash`='$tr_cash',`tr_minus`='$tr_minus',`tr_plus`='$tr_plus',`tr_tfpay`='$tr_tfpay',`tr_auto`='$car',`tr_receive`='$tr_receive',`print_tr`='$print_tr',`notify`='$notify' WHERE `id`='$order'";	
$result = mysql_query($query) or die(mysql_error());
echo '<b>Сохранено!</b><br>Перевозчик и транспорт обновлён.2|1|'.$order;

$query_logs = "INSERT INTO `logs` (`user`,`log_message`) VALUES ('".$_SESSION["user_id"]."','Отредактирована заявка №".$order." (".date("d/m/Y",strtotime($data)).")')";
$result_logs = mysql_query($query_logs) or die(mysql_error());

}
else echo '<font size="3" color="red">Заявка заблокирована для редактирования!</font>';
}


}	
} else {
$query_check = "SELECT `id` FROM `orders` WHERE `id`='".$order_id."'";
$result_check = mysql_query($query_check) or die(mysql_error());
if (mysql_num_rows($result_check)==0&&$ord_check)
{
	
	if((int)$_POST['international']==1) {
		$query_settings = "SELECT `international_number` FROM `settings`";
		$result_settings = mysql_query($query_settings) or die(mysql_error());
		$settings = mysql_fetch_row($result_settings);
		$international_number = 'М-'.$settings[0];
		$query_settings = "UPDATE `settings` SET `international_number`='".((int)$settings[0]+1)."'";
		$result_settings = mysql_query($query_settings) or die(mysql_error());
		} else $international_number = "0"; 
	
$query = "INSERT INTO `orders` (`manager`,`tr_manager`,`client`,`cl_pref`,`cl_nds`,`cl_event`,`data`,`in_adress`,`out_adress`,`cl_cash`,`transp`,`tr_pref`,`tr_nds`,`tr_event`,`tr_cash`,`cl_minus`,`cl_plus`,`tr_minus`,`tr_plus`,`cl_tfpay`,`tr_tfpay`,`gruz`,`gr_m`,`gr_v`,`gr_number`,`gr_load`,`tr_auto`,`cl_cont`,`tr_cont`,`tr_receive`,`print_cl`,`print_tr`,`date_in1`,`time_in11`,`time_in12`,`date_in2`,`time_in21`,`time_in22`,`date_out1`,`date_out2`,`time_out1`,`time_out2`,`id`,`car_notify`,`notify`,`km`,`date_plan`,`krugoreis`,`days_tfpay`,`international_number`,`cl_kop`,`tr_days`,`cl_rashod_na_cl`,`cl_rashod_sb`,`cl_komissia`,`tr_komissia`,`cl_currency`,`tr_currency`,`tr_gruz_worker`) VALUES ('$manager','$tr_manager','$client','$cl_pref','$cl_nds','$cl_event','$data','$in_adr','$out_adr','$cl_cash','$transporter','$tr_pref','$tr_nds','$tr_event','$tr_cash','$cl_minus','$cl_plus','$tr_minus','$tr_plus','$cl_tfpay','$tr_tfpay','$gruz_name','$gruz_m','$gruz_v','$gruz_num','$gruz_load','$car','$cl_cont','$tr_cont','$tr_receive','$print_cl','$print_tr','$in_data1','$in_time11','$in_time12','$in_data2','$in_time21','$in_time22','$out_data1','$out_data2','$out_time1','$out_time2','$order_id','$car_notify','$notify','$ati_km','$date_plan','$krugoreis','$days_tfpay','$international_number','$cl_kop','$tr_days','$cl_rashod_na_cl','$cl_rashod_sb','$cl_komissia','$tr_komissia','$cl_currency','$tr_currency','$tr_gruz_worker')";

$result = mysql_query($query) or die(mysql_error());
echo 'Сохранено!|1|'.mysql_insert_id();

$log_order=mysql_insert_id();
$query_docs = "INSERT INTO `docs` (`order`) VALUES ('".$log_order."')";
$result_docs = mysql_query($query_docs) or die(mysql_error());

if($dolg_check!=""){
$query_limit_order = "UPDATE `clients` SET `cl_limit_order`=`cl_limit_order`-1 WHERE `id`='".mysql_escape_string($client)."'";
$result_limit_order = mysql_query($query_limit_order) or die(mysql_error());
}



if(@$_POST['gruz_id']!=""){
$query_gruz = "UPDATE `cl_gruz` SET `order`='".mysql_escape_string($log_order)."' WHERE `id`='".mysql_escape_string((int)$_POST['gruz_id'])."'";
$result_gruz = mysql_query($query_gruz) or die(mysql_error());
}


// Автосоздание платежа для заявок с перевозчиком  Транспортна компания
if((int)$transporter==2){
$query_autopay = "INSERT INTO `pays` (`date`,`way`,`nds`,`category`,`appoint`,`order`,`cash`,`status`,`notify`,`add_name`,`payment_source`,`del_id`,`pay_bill`,`car_id`) VALUES ('".$data."','2','1','1','2','".$log_order."','".((int)$tr_cash*100)."','1','auto_vtl','0','0','0','0','0')";
$result_autopay = mysql_query($query_autopay) or die(mysql_error());
}

//$query_trip = "INSERT INTO `vtl_trip` (`order`) VALUES ('".mysql_insert_id()."')";
//$result_trip = mysql_query($query_trip) or die(mysql_error());

$query_logs = "INSERT INTO `logs` (`user`,`log_message`) VALUES ('".$_SESSION["user_id"]."','Создана заявка ".$international_number." №".$log_order." (".date("d/m/Y",strtotime($data)).")')";
$result_logs = mysql_query($query_logs) or die(mysql_error());
} else echo "Ошибка! Заявка с номером <b>".$order_id."</b> уже существует в базе!";
}
//Одноразовый пароль ВКЛЮЧЕНИЕ!!!
//$query_code = "UPDATE `security_code` SET `code`=''";
//$result_code = mysql_query($query_code) or die(mysql_error());

} else {echo 'Пароль не верный! Уведомление отправлено директору...';
$query_logs = "INSERT INTO `logs` (`user`,`log_message`) VALUES ('".$_SESSION["user_id"]."','Введен не верный пароль для Заявки №".$order." (".date("d/m/Y",strtotime($data)).")')";
$result_logs = mysql_query($query_logs) or die(mysql_error());}
} else {
echo 'Заявка сомнительной рентабельности! Для сохранения введите пароль...|2|';	
}
}



} else {
	
if(@$cash_ok=="0"&&$_SESSION["group"]==3){
echo 'Заявка нерентабельна! Вы не можете её сохранить...';	
} else {
$query_block = "SELECT `block` FROM `orders` WHERE `Id`='".$order."'";
$result_block = mysql_query($query_block) or die(mysql_error());
$block = mysql_fetch_row($result_block);
	
if($block[0]!='1') {
if($tr_manager==$_SESSION["user_id"]){
$query = "UPDATE `orders` SET `transp`='$transporter',`tr_pref`='$tr_pref',`tr_nds`='$tr_nds',`tr_event`='$tr_event',`tr_cash`='$tr_cash',`tr_minus`='$tr_minus',`tr_plus`='$tr_plus',`tr_tfpay`='$tr_tfpay',`tr_auto`='$car',`tr_receive`='$tr_receive',`print_tr`='$print_tr',`notify`='$notify' WHERE `id`='$order'";	
$result = mysql_query($query) or die(mysql_error());
echo '<b>Сохранено!</b><br>Перевозчик и транспорт обновлён.3|1|'.$order;

$query_logs = "INSERT INTO `logs` (`user`,`log_message`) VALUES ('".$_SESSION["user_id"]."','Отредактирована заявка №".$order." (".date("d/m/Y",strtotime($data)).")')";
$result_logs = mysql_query($query_logs) or die(mysql_error());
} else echo 'Менеджер перевозчика указан неверно...';
}
else echo '<font size="3" color="red">Заявка заблокирована для редактирования!</font>';
}


}
}	

?>