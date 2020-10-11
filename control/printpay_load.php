<?php
include "../config.php";
session_start();




if($_GET['mode']=='print'){
echo '<table cellpadding="5"  style="width:100%;overflow:auto;border-collapse: collapse;border-style: solid;border-color: black;" border="1"><tr><td bgcolor="#edf1f3" align=center>Упр.</td><td bgcolor="#edf1f3" align=center>№</td><td bgcolor="#edf1f3">№ Заявки</td><td bgcolor="#edf1f3" width="350">Перевозчик</td><td bgcolor="#edf1f3" align=center></td><td bgcolor="#edf1f3" align=center>План.сумма</td><td bgcolor="#edf1f3" align=center>Факт.сумма</td><td bgcolor="#edf1f3" align=center>Просрочка</td></tr>';




$query_pay = "SELECT `Id`,`order`,`cash` FROM `pays` WHERE `way`='2' AND `category`='1' AND `appoint`='2' AND `status`='0' AND `delete`='0'";
$result_pay = mysql_query($query_pay) or die(mysql_error());

while($ps= mysql_fetch_row($result_pay)) {
$query = "SELECT `id`,`transp`,`tr_nds`,`tr_cash`,`tr_tfpay`,`tr_event`,`date_in1`,`date_out2`,`date_in2`,`client`,`cl_cash`,`agat_number` FROM `orders` WHERE `Id`='".mysql_escape_string($ps[1])."' ORDER BY `Id` DESC";


$result = mysql_query($query) or die(mysql_error());
while($orders= mysql_fetch_row($result)) {

$query_tr = "SELECT `name` FROM `transporters` WHERE `id`='".mysql_escape_string($orders[1])."'";
$result_tr = mysql_query($query_tr) or die(mysql_error());
$tr = mysql_fetch_row($result_tr);

switch ($orders[2]) {
case '0': $nds_tr='без НДС';break;
case '1': $nds_tr='с НДС';break;
case '2': $nds_tr='НАЛ';break;
}

$query_cl = "SELECT `name` FROM `clients` WHERE `id`='".mysql_escape_string($orders[9])."'";
$result_cl = mysql_query($query_cl) or die(mysql_error());
$cl = mysql_fetch_row($result_cl);


$tr_pay=0;

$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($orders[0])."' AND `delete`='0' AND `appoint`='2' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$tr_pay=(int)$pay[0]+(int)$tr_pay;
}
if((int)$tr_pay/100!=(int)$orders[3]){
$tr_event="";
switch ($orders[5]) {
case '1': $tr_event=$orders[6];break;
case '2': $tr_event=$orders[7];break;

case '3': $query_docs = "SELECT `date_tr_receve` FROM `docs` WHERE `order`='".mysql_escape_string($orders[0])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());
$tr_events=mysql_fetch_row($result_docs);
if(mysql_num_rows($result_docs)>0&&$tr_events[0]!="0000-00-00"&&$tr_events[0]!="1970-01-01"&&$tr_events[0]!=""){
$tr_event=$tr_events[0];};break;

case '4': $query_docs = "SELECT `date_tr_receve` FROM `docs` WHERE `order`='".mysql_escape_string($orders[0])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());
$tr_events=mysql_fetch_row($result_docs);
if(mysql_num_rows($result_docs)>0&&$tr_events[0]!="0000-00-00"&&$tr_events[0]!="1970-01-01"&&$tr_events[0]!=""){
$tr_event=$tr_events[0];};break;
}
if($tr_event!="0000-00-00"&&$tr_event!="1970-01-01"&&$tr_event!=""){
$tr_event_date=date('d/m/Y', strtotime('+'.(int)$orders[4].' day', strtotime($tr_event)));

$elements  = explode("/",$tr_event_date);
$current_date = mktime (0,0,0,date("m") ,date("d"),date("Y")); //дата сегодня
$old_date = mktime (0,0,0,$elements[1],$elements[0],$elements[2]); 
$difference = ($current_date - $old_date);
$difference_in_days = floor($difference / 86400); //разница в днях
} 
} else $difference_in_days=0;
$cash_el= explode(",",number_format((int)$ps[2]/100, 2, ',', ' '));
$cash_all=$cash_el[0]." руб.".$cash_el[1]." коп.";

if($orders[11]!='') $ord_number='<font size=4><b>'.$orders[11].".'</b></font><br><font size=2>(".$orders[0].')</font>'; else $ord_number='<font size=4><b>'.$orders[0].'</b></font>';
	
echo '<tr><td align=center bgcolor=#F2F5F7><input type="checkbox" name="list_pay_id[]" id="list_pay_id" value=""></td><td>'.$ps[0].'</td><td align=center bgcolor=#F2F5F7>'.$ord_number.'</td><td><b>«'.$tr[0].'»</b><br>(Клиент: «'.$cl[0].'»)</td><td bgcolor=#F9F9F9>'.$nds_tr."</td><td align=center";

if(((int)$ps[2]+(int)$tr_pay)/100>(int)$orders[3]){echo ' bgcolor="#FF7575" ';}



echo "><b>".$orders[3]." руб.</b><br>(Оплачено: ".((int)$tr_pay/100).")</td><td>".$cash_all."</td><td align=center>".$difference_in_days." дн.</td></tr>";
}

}

echo '</table>';
}







?>