<?php
include "../config.php";
session_start();



if ($_GET['mode']!='count') {
if ($_GET['load_listpay']=='33'){echo '<table cellpadding="5"  style="width:100%;overflow:auto;border-collapse: collapse;border-style: solid;border-color: black;" border="1"><tr><td bgcolor="#edf1f3">Заявка</td><td bgcolor="#edf1f3">Перевозчик</td><td bgcolor="#edf1f3" align=center></td><td bgcolor="#edf1f3" align=center>План.сумма</td><td bgcolor="#edf1f3" align=center>Факт.сумма</td><td bgcolor="#edf1f3" align=center>Просрочка</td></tr>';
} else {
echo '<table cellpadding="5"  style="width:100%;overflow:auto;border-collapse: collapse;border-style: solid;border-color: black;" border="1"><tr><td bgcolor="#edf1f3" align=center>Упр.</td><td bgcolor="#edf1f3">Заявка</td><td bgcolor="#edf1f3">Перевозчик</td><td bgcolor="#edf1f3" align=center></td><td bgcolor="#edf1f3" align=center>План.сумма</td><td bgcolor="#edf1f3" align=center>Факт.сумма</td><td bgcolor="#edf1f3" align=center>Срок оплаты</td><td bgcolor="#edf1f3" align=center>Просрочка</td></tr>';}

} else $fff=0;



	
$list_pay_id=$_POST['list_pay_id'];
$g=0;

if ($list_pay_id&&$_GET['load_listpay']=='33'){
	 foreach($list_pay_id as $in){
	 $elems= explode("|",$in);
	 $id_mass[$g]=$elems[0];
	 $g++;
	 }

$query = "SELECT `id`,`transp`,`tr_nds`,`tr_cash`,`tr_tfpay`,`tr_event`,`date_in1`,`date_out2`,`date_in2`,`client`,`cl_cash`,`date_out1` FROM `orders` WHERE `id` IN (".implode(',',$id_mass).") ORDER BY `Id` DESC";



}
else {
if($_SESSION["group"]==3){
$manager =$_SESSION["user_id"];
$query = "SELECT `id`,`transp`,`tr_nds`,`tr_cash`,`tr_tfpay`,`tr_event`,`date_in1`,`date_out2`,`date_in2`,`client`,`cl_cash`,`date_out1` FROM `orders` WHERE `manager`='".mysql_escape_string($manager)."' AND `transp`!='2' AND `delete`='0' AND `data`>='2012-01-01'";
} else {$query = "SELECT `id`,`transp`,`tr_nds`,`tr_cash`,`tr_tfpay`,`tr_event`,`date_in1`,`date_out2`,`date_in2`,`client`,`cl_cash`,`date_out1` FROM `orders` WHERE `delete`='0' AND `transp`!='2' AND `data`>='2012-01-01' ORDER BY `Id` DESC";}
}


$result = mysql_query($query) or die(mysql_error());

while($orders= mysql_fetch_row($result)) {

$tr_pay=0;

$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($orders[0])."' AND `delete`='0' AND `appoint`='2' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$tr_pay=(int)$pay[0]+(int)$tr_pay;
}



if((int)$tr_pay/100!=(int)$orders[3]){
	
$tr_event="";
switch ($orders[5]) {
case '1': if($orders[8]!="0000-00-00"&&$orders[8]!="1970-01-01")$tr_event=$orders[8]; else $tr_event=$orders[6];break;
case '2': if($orders[7]!="0000-00-00"&&$orders[7]!="1970-01-01")$tr_event=$orders[7]; else $tr_event=$orders[11];break;

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

//$elements  = explode("/",date("d/m/Y",strtotime('+30 day', strtotime($orders[11]))));

//$old_date = mktime (0,0,0,$elements[1],$elements[0],$elements[2]); ||$current_date>$old_date

if(($tr_event!="0000-00-00"&&$tr_event!="1970-01-01"&&$tr_event!="")){
//if($current_date>$old_date)$tr_event=$orders[11];	
//дата сегодня
$current_date = mktime (0,0,0,date("m") ,date("d"),date("Y")); 
$tr_event_date=date('d/m/Y', strtotime('+'.(int)$orders[4].' day', strtotime($tr_event)));

$elements  = explode("/",$tr_event_date);
$old_date = mktime (0,0,0,$elements[1],$elements[0],$elements[2]); 
$difference = ($current_date - $old_date);
$difference_in_days = floor($difference / 86400); //разница в днях



if($difference_in_days>=0){
if ($_GET['mode']=='count') 
{$fff++;
$count_info=(int)$count_info+(int)$orders[3]-(int)$tr_pay/100;

} else {
switch ($orders[2]) {
case '0': $nds_tr='без НДС';break;
case '1': $nds_tr='с НДС';break;
case '2': $nds_tr='НАЛ';break;
}

$query_tr = "SELECT `name` FROM `transporters` WHERE `id`='".mysql_escape_string($orders[1])."'";
$result_tr = mysql_query($query_tr) or die(mysql_error());
$tr = mysql_fetch_row($result_tr);

$cl_pay=0;

$query_cl_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($orders[0])."' AND `delete`='0' AND `appoint`='1' AND `status`='1'";
$result_cl_pays = mysql_query($query_cl_pays) or die(mysql_error());
while($c_pay = mysql_fetch_row($result_cl_pays)) {
$cl_pay=(int)$c_pay[0]+(int)$cl_pay;
}

$query_cl = "SELECT `name` FROM `clients` WHERE `id`='".mysql_escape_string($orders[9])."'";
$result_cl = mysql_query($query_cl) or die(mysql_error());
$cl = mysql_fetch_row($result_cl);

$cash_el= explode(",",number_format((int)$tr_pay/100, 2, ',', ' '));

$count_info=(int)$count_info+(int)$orders[3];
$count_info_pay=(int)$count_info_pay+(int)$tr_pay;

if ($_GET['load_listpay']=='33'){
	$cash_all=$cash_el[0];
	
echo '<tr><td align=center bgcolor=#F2F5F7><font size=4><b>'.$orders[0].'</b></font></td><td><b>«'.$tr[0].'»</b><br>(Клиент: «'.$cl[0].'»)</td><td bgcolor=#F9F9F9>'.$nds_tr."</td><td align=center><b>".$orders[3]."</b><br>(К:".((int)$orders[10]).")</td><td  align=center";

	
	 
if((int)$cl_pay/100==(int)$orders[10]){echo ' bgcolor="#AAFFAD" ';}
if((int)$cl_pay/100!=0){echo ' bgcolor="#FFEE9E" ';}

echo "><b>".$cash_all."</b><br>(К:".((int)$cl_pay/100).")</td><td align=center>".$difference_in_days." дн.</td></tr>";



} else {
	$cash_all=$cash_el[0]." руб.".$cash_el[1]." коп.";
	
echo '<tr><td align=center bgcolor=#F2F5F7><input type="checkbox" name="list_pay_id[]" id="list_pay_id" value="'.$orders[0].'|'.((int)$orders[3]*100-(int)$tr_pay).'|'.$orders[2].'" onclick=\'document.getElementById("cash_tr_'.$orders[0].'").style.display="inline";\'></td><td align=center bgcolor=#F2F5F7><font size=4><b>'.$orders[0].'</b></font></td><td><b>«'.$tr[0].'»</b><br>(Клиент: «'.$cl[0].'»)</td><td bgcolor=#F9F9F9>'.$nds_tr."</td><td align=center><b>".$orders[3]." руб.</b><br>(К:".((int)$orders[10]).")</td><td  align=center";
	
	 
if((int)$cl_pay/100==(int)$orders[10]){echo ' bgcolor="#AAFFAD" ';}
if((int)$cl_pay/100!=0){echo ' bgcolor="#FFEE9E" ';}

//echo "><br>(К:".((int)$cl_pay/100)." руб.)</td><td align=center><font size=4>".$tr_event_date."</font></td><td align=center>".$difference_in_days." дн.</td></tr>";
//
	
echo "><div id='cash_tr_".$orders[0]."' style='display:none;'><input type='text' name='cash_tr_".$orders[0]."' value='0' style='width:55px;'> руб.<br></div><b>".$cash_all."</b><br>(К:".((int)$cl_pay/100)." руб.)</td><td align=center><font size=4>".$tr_event_date."</font></td><td align=center>".$difference_in_days." дн.</td></tr>";


}

}



}
}
}


}




if ($_GET['mode']=='count') 
{echo $fff.'-'.number_format($count_info, 0, ',', '.').' р';} else 

if ($_GET['load_listpay']=='33'){echo '<tr><td colspan="3" align="right"><b>Итого:</b></td><td align="center">'.number_format($count_info, 0, '.', ' ').' р</td><td align="center">'.number_format(((int)$count_info_pay/100), 0, '.', ' ').' р</td><td colspan="2">&nbsp;&nbsp;&nbsp;<b>'.number_format(($count_info-(int)$count_info_pay/100), 2, '.', ' ').'</b> руб.</td></tr></table>';} else {echo '<tr><td colspan="4" align="right"><b>Итого:</b></td><td align="center">'.number_format($count_info, 0, '.', ' ').' р</td><td align="center">'.number_format(((int)$count_info_pay/100), 0, '.', ' ').' р</td><td colspan="2">&nbsp;&nbsp;&nbsp;<b>'.number_format(($count_info-(int)$count_info_pay/100), 2, '.', ' ').'</b> руб.</td></tr></table>';}

?>