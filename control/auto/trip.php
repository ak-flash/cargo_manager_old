<?php
include "../../config.php";
session_start();

if(@$_GET['km'])
{
$query_km = "UPDATE `vtl_trip` SET `km`='".(int)$_GET['km']."' WHERE `Id`='".(int)$_GET['trip_id']."'";
$result_km = mysql_query($query_km) or die(mysql_error());
echo '1|';
}

if(@$_GET['days'])
{
$query_days = "UPDATE `vtl_trip` SET `days`='".(int)$_GET['days']."' WHERE `Id`='".(int)$_GET['trip_id']."'";
$result_days = mysql_query($query_days) or die(mysql_error());
echo '1|';
}

if((@$_GET['nal']&&@$_GET['days_cash'])||($_GET['nal_credit']=='1'&&@$_GET['days_cash']))
{
$add_name=(int)$_SESSION['user_id'];

$date_pay=$_GET['data'];

$query = "SELECT `cash` FROM `drivers_report` WHERE `way`='1' AND `trip`='".(int)$_GET['trip_id']."' AND `delete`='0'";
$result = mysql_query($query) or die(mysql_error());	
if (mysql_num_rows($result)==0){
$query = "INSERT INTO `drivers_report` (`trip`,`way`,`cash`,`date`) VALUES ('".(int)$_GET['trip_id']."','1','".((float)$_GET['days_cash']*100)."','$date_pay')";
$result = mysql_query($query) or die(mysql_error());

$query_report = "SELECT `cash`,`way` FROM `drivers_report` WHERE `trip`='".(int)$_GET['trip_id']."' AND `delete`='0'";
$result_report = mysql_query($query_report) or die(mysql_error());	
while($report = mysql_fetch_row($result_report)){
if($report[1]!='0'&&$report[1]!='33'&&$report[1]!='10')$r_cash=(int)$r_cash+(int)$report[0];
}

if($_GET['nal_credit']=='1'){	
$query = "UPDATE `vtl_trip` SET `credit`='1',`cash_day`='".((float)$_GET['days_cash']*100)."',`o_cash`='".((float)$_GET['nal']*100-(int)$r_cash)."' WHERE `Id`='".(int)$_GET['trip_id']."'";} else {$query = "UPDATE `vtl_trip` SET `plan_cash`='".((float)$_GET['nal']*100)."',`cash_day`='".((float)$_GET['days_cash']*100)."',`o_cash`='".((float)$_GET['nal']*100-(int)$r_cash)."' WHERE `Id`='".(int)$_GET['trip_id']."'";}

$result = mysql_query($query) or die(mysql_error());






if($_GET['nal_credit']!='1'){
$query = "INSERT INTO `pays` (`date`,`way`,`nds`,`category`,`appoint`,`order`,`cash`,`status`,`notify`,`add_name`,`payment_source`,`trip_id`) VALUES ('$date_pay','2','2','2','40','0','".((float)$_GET['nal']*100)."','1','Выдача по рейсу №".(int)$_GET['trip_id']."','$add_name','1','".(int)$_GET['trip_id']."')";
$result = mysql_query($query) or die(mysql_error());
}

if($_GET['nal_credit']!='1'){
echo "1|<font color='green' size='3'>Выдано: <b>".$_GET['nal']."</b>  руб.</font> (Включая суточные: <b>".$_GET['days_cash']."</b> руб.)<br>Платеж <b>№".mysql_insert_id()."</b> - проведён";
} else {echo "1|<font color='green' size='3'>Используется НАЛ из подотчёта</b>  руб.</font> (Включая суточные: <b>".$_GET['days_cash']."</b> руб.)";}

} else echo '<b>Имеется запись о ранее выданных суточных.</b><br>Удалите из «Авансового отчёта» данные перед выдачей суммы!';

} else {if(!@$_GET['nal']&&$_GET['nal_credit']!='1'&&$_GET['mode']!='petrol') echo 'Введите выданную сумму!<br>';if(!@$_GET['days_cash']&&$_GET['mode']!='petrol') echo 'Укажите количество дней рейса!<br>';}

if($_GET['start_petrol']!=""&&$_GET['end_petrol']!=""&&$_GET['mode']=='petrol')
{
$query = "UPDATE `vtl_trip` SET `start_petrol`='".mysql_escape_string((int)$_GET['start_petrol'])."',`end_petrol`='".mysql_escape_string((int)$_GET['end_petrol'])."' WHERE `id`='".(int)$_GET['trip_id']."'";
$result = mysql_query($query) or die(mysql_error());
echo '1|success';
}

if(@$_GET['mode']=='delete')
{
	$report_trip=(int)$_GET['trip'];
$report_way=(int)$_GET['report_way'];
$report_cash=$_GET['report_cash'];


$query_del = "UPDATE `drivers_report` SET `delete`='1' WHERE `Id`='".$report_trip."'";
$result_del = mysql_query($query_del) or die(mysql_error());



if($report_way==33){

//$query_report = "SELECT `cash` FROM `pays` WHERE `trip_id`='".$report_trip."' AND `delete`='0'";
//$result_report = mysql_query($query_report) or die(mysql_error());	
//$report = mysql_fetch_row($result_report);	
$query = "UPDATE `pays` SET (`cash`='cash'-'".$report_cash."') WHERE `trip_id`='".$report_trip."'";
$result = mysql_query($query) or die(mysql_error());
}

if($report_way==10){
$card_id=(int)$_GET['card_id'];
	
$query_card_p = "UPDATE `vtl_oil_card` SET `card_cash`=(`card_cash`+".$report_cash.") WHERE `id`='".mysql_escape_string($card_id)."'";
$result_card_p = mysql_query($query_card_p) or die(mysql_error());
}


}
?>