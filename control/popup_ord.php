<?php
// Подключение и выбор БД
include "../config.php";	
include "komissia.php";

session_start();




if ($_GET['mode']=='popup') 
{
$id =$_GET['id'];
$query_order= "SELECT * FROM `orders` WHERE `Id`=".mysql_escape_string($id);
$result_order = mysql_query($query_order) or die(mysql_error());
$row = mysql_fetch_array($result_order);

$query_adress = "SELECT * FROM `adress`";
$result_adress = mysql_query($query_adress) or die(mysql_error());

while($adress = mysql_fetch_row($result_adress)) {
if($adress[8]=="0") $flat=""; else $flat=' кв.'.$adress[8];
if($adress[3]==""||$adress[3]=="0") $obl=""; else $obl=$adress[3]." обл.";	
if($adress[6]==""||$adress[6]=="0") $dom=""; else $dom="&nbsp;д.".$adress[6];
if($adress[7]==""||$adress[7]=="0") $dom_extra=""; else $dom_extra='/'.$adress[7];
	
$adresses[$adress[0]]= $adress[2].', '.$obl.' '.$adress[4].' ул. '.$adress[5].$dom.$dom_extra.$flat.' ('.$adress[9].' - '.$adress[10].')';
}


$query_company = "SELECT `id`,`name` FROM `company`";
$result_company = mysql_query($query_company) or die(mysql_error());

while($company = mysql_fetch_row($result_company)) {
$companys[$company[0]]= $company[1];
}

$query_tr = "SELECT `name` FROM `transporters` WHERE `id`='".$row['tr_receive']."'";
$result_tr = mysql_query($query_tr) or die(mysql_error());
$transp = mysql_fetch_row($result_tr);

 $query_cl = "SELECT `name` FROM `clients` WHERE `id`='".$row['client']."'";
$result_cl = mysql_query($query_cl) or die(mysql_error());
$client = mysql_fetch_row($result_cl);

if($row['transp']=='2'){
$str_auto = explode('&',$row['tr_auto']);	
$query_car = "SELECT `name`,`number` FROM `vtl_auto` WHERE `id`='".(int)$str_auto[0]."' OR `id`='".(int)$str_auto[1]."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$iii=0;
while($car = mysql_fetch_row($result_car)){$car_info[$iii]=$car[0];$iii++;$car_info[$iii]=$car[1];$iii++;}
$car_info[4]=(int)$str_auto[2];

$query_drv = "SELECT `pref_phone`,`phone`,`name` FROM `workers` WHERE `Id`='".(int)$str_auto[2]."'";
$result_drv = mysql_query($query_drv) or die(mysql_error());
$drv = mysql_fetch_row($result_drv);
$car_info[5]='8('.$drv[0].')'.$drv[1];

} else {$query_car = "SELECT `car_name`,`car_number`,`car_driver_name`,`car_driver_phone`,`car_extra_name`,`car_extra_number` FROM `tr_autopark` WHERE `id`='".$row['tr_auto']."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car_info = mysql_fetch_row($result_car);

}
        	
if($row['transp']=='2'){
$driver=$drv[2];
$drv_phone=$car_info[5];
$dop_car=$car_info[3];
$dop_car_name=$car_info[2];
} else {
$driver=$car_info[2];
$drv_phone=$car_info[3];
$dop_car=$car_info[5];
$dop_car_name=$car_info[4];}

switch ($row['gr_load']) {
case '1': $gr_load='верхняя';break;
case '2': $gr_load='задняя';break;
case '3': $gr_load='боковая';break;
} 



$str_in = explode('&',$row['in_adress']);
$str_adr_in =(int)sizeof($str_in)-2;
$f=0;
while ($f<=$str_adr_in) {
$res_adr_in =$res_adr_in.($f+1).") ".$adresses[$str_in[($str_adr_in-$f)]]."<br>";
$f++;
}

$str_out = explode('&',$row['out_adress']);
$str_adr_out =(int)sizeof($str_out)-2;
$f=0;
while ($f<=$str_adr_out) {
$res_adr_out =$res_adr_out.($f+1).") ".$adresses[$str_out[($str_adr_out-$f)]]."<br>";
$f++;
}
if($row['date_in1']!="1970-01-01"&&$row['date_in1']!="0000-00-00") {$date_in1=date("d/m/Y",strtotime($row['date_in1']))." (".date("H:i",strtotime($row['time_in11']))." - ".date("H:i",strtotime($row['time_in12'])).")";} else $date_in1="";
if($row['date_in2']!="1970-01-01"&&$row['date_in2']!="0000-00-00") {$date_in2=" по ".date("d/m/Y",strtotime($row['date_in2']))." (".date("H:i",strtotime($row['time_in21']))." - ".date("H:i",strtotime($row['time_in22'])).")";$date_in=" c ";} else {$date_in2="";$date_in="";};


if($row['date_out1']!="1970-01-01") {$date_out1="c ".date("d/m/Y",strtotime($row['date_out1']))." (".date("H:i",strtotime($row['time_out1'])).")";} else $date_out1="-";
if($row['date_out2']!="1970-01-01") {$date_out2=" по ".date("d/m/Y",strtotime($row['date_out2']))." (".date("H:i",strtotime($row['time_out2'])).")";} else $date_out2="";


$cash=komissia($row['cl_cash'],$row['cl_minus'],$row['cl_plus'],$row['cl_nds'],$row['tr_cash'],$row['tr_minus'],$row['tr_plus'],$row['tr_nds']);


switch ($row['cl_event']) {
case '1': $cl_event='Загрузка';break;
case '2': $cl_event='Выгрузка';break;
case '3': $cl_event='Поступление факсимильных документов';break;
case '4': $cl_event='Поступление оригинальных документов';break;}

switch ($row['tr_event']) {
case '1': $tr_eventsss='Загрузка';break;
case '2': $tr_eventsss='Выгрузка';break;
case '3': $tr_eventsss='Поступление факсимильных документов';break;
case '4': $tr_eventsss='Поступление оригинальных документов';break;}

$cash_ok=$cash*100/(int)$row['cl_cash'];	

$tr_event="";
switch ($row['tr_event']) {
case '1': $tr_event=$row['date_in1'];break;
case '2': $tr_event=$row['date_out1'];break;

case '3': $query_docs = "SELECT `date_tr_receve` FROM `docs` WHERE `order`='".mysql_escape_string($row['Id'])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());
$tr_events=mysql_fetch_row($result_docs);
if(mysql_num_rows($result_docs)>0&&$tr_events[0]!="0000-00-00"&&$tr_events[0]!="1970-01-01"&&$tr_events[0]!=""){
$tr_event=$tr_events[0];};break;

case '4': $query_docs = "SELECT `date_tr_receve` FROM `docs` WHERE `order`='".mysql_escape_string($row['Id'])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());
$tr_events=mysql_fetch_row($result_docs);
if(mysql_num_rows($result_docs)>0&&$tr_events[0]!="0000-00-00"&&$tr_events[0]!="1970-01-01"&&$tr_events[0]!=""){
$tr_event=$tr_events[0];};break;
}
if($tr_event!="0000-00-00"&&$tr_event!="1970-01-01"&&$tr_event!=""){
$tr_event_date=" (".date('d/m/Y', strtotime('+'.(int)$row['tr_tfpay'].' day', strtotime($tr_event)))."г.)";
}

$query_docs = "SELECT `cl_bill`,`date_add_bill`,`tr_bill`,`date_tr_receve` FROM `docs` WHERE `order`='".mysql_escape_string($row['id'])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());
$docs=mysql_fetch_row($result_docs);

if($docs[0]!=0)$doc_cl="(Счёт №".$docs[0]." от ".date('d/m/Y',strtotime($docs[1]))." г.)"; else $doc_cl="";
if($docs[2]!=0)$doc_tr="(Счёт №".$docs[2]." от ".date('d/m/Y',strtotime($docs[3]))." г.)"; else $doc_tr="";

if ($row['krugoreis']=='1')$krugoreis='&nbsp;&nbsp;<img src="data/img/gtk-refresh.png" style="width:20px;float:right;display:inline;margin-top:-5px;"><div style="margin-top:-5px;float:right;display:inline;"><b>кругорейс</b>&nbsp;&nbsp;</div>'; else $krugoreis='';

echo '<div style="width:850px;background:#F9F9F9;border: 5px solid #A0A0A0;z-index:1003;border-radius: 5px;position:absolute;top:300px;left:300px;padding:10px;"><b>Комиссия:</b> '.$cash.' руб.<hr><font size="3"><b>Дата загрузки:</b> '.$date_in.$date_in1.$date_in2.$krugoreis.'<br><b>Адреса загрузки:</b> '.$res_adr_in.'<hr><b>Дата выгрузки:</b> '.$date_out1.$date_out2.$krugoreis.'<br><b>Адреса выгрузки:</b> '.$res_adr_out.'<hr><b>Наименование груза:</b> '.$row['gruz'].'&nbsp;&nbsp;&nbsp;<b>Вес:</b> '.$row['gr_m'].' т&nbsp;&nbsp;&nbsp;<b>Обьем:</b> '.$row['gr_v'].' м3&nbsp;&nbsp;&nbsp;<b>Количество:</b>'.$row['gr_number'].' шт.&nbsp;&nbsp;&nbsp;<b>Вид погрузки:</b> '.$gr_load.'<br><b>Автотранспорт: '.$car_info[0].'</b> Г/Н: <b>'.$car_info[1].'</b> (П/п: '.$dop_car_name.' - '.$dop_car.')<br><b>Водитель: '.$driver.'</b> - '.$drv_phone.'<hr><b>Клиент:</b>&nbsp;&nbsp;<b>Плательщик:</b> '.$nds_cl. '«'.$client[0].'»&nbsp;&nbsp;<b>Получатель:</b> «'.$companys[$row['cl_cont']].'»<br><b>Срок оплаты:</b> '.$cl_event.' + <b>'.$row['cl_tfpay'].' дн.</b> '.$doc_cl.'<br><b>Плановая дата оплаты:</b> '.date("d/m/Y",strtotime($row['date_plan']))."<hr><b>Перевозчик:</b>&nbsp;&nbsp;<b>Плательщик:</b>  «".$companys[$row['tr_cont']].'»&nbsp;&nbsp;<b>Получатель:</b> «'.$transp[0].'»<br><b>Срок оплаты:</b> '.$tr_eventsss.' + <b>'.$row['tr_tfpay'].' дн.</b> '.$tr_event_date.' '.$doc_tr.'</font></div>';
}



?>