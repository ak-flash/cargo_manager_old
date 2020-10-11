<?php
session_start();




// Подключение и выбор БД
include "../../config.php";
$error='Не заполнено поле ';
$validate=true;

if(@$_POST['report_way']=="0"){echo $error.'<font color=red>"Категория"</font><br>'; $validate=false;}

if(@$_POST['report_cash']=="0"||@$_POST['report_cash']==""){echo $error.'<font color=red>"Сумма"</font>'; $validate=false;}
	
if($validate)
{
	$report_trip=(int)$_POST['trip'];
$report_way=(int)$_POST['report_way'];
$report_cash=(float)$_POST['report_cash']*100;
$report_notify=addslashes($_POST['report_notify']);

$report_card_number=(int)$_POST['report_card_number'];

$in_elements  = explode("/",$_POST['date_report']);
$date_report=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));

$litrov=(float)$_POST['litrov']*100;

if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4){
if($report_way==10){
$card_id=(int)$_POST['card_id'];	
$query_card_p = "SELECT `card_cash` FROM `vtl_oil_card` WHERE `id`='".mysql_escape_string($card_id)."'";
$result_card_p = mysql_query($query_card_p) or die(mysql_error());
$card_p = mysql_fetch_row($result_card_p);
$card_cash=$card_p[0]-$report_cash;	
}	
	
$query = "INSERT INTO `drivers_report` (`date`,`trip`,`way`,`cash`,`notify`,`l`,`card_oil`,`card_cash`) VALUES ('$date_report','$report_trip','$report_way','$report_cash','$report_notify','$litrov','$report_card_number','$card_cash')";
$result = mysql_query($query) or die(mysql_error());

$query_fr = "SELECT `plan_cash` FROM `vtl_trip` WHERE `id`='".$report_trip."'";
$result_fr = mysql_query($query_fr) or die(mysql_error());
$fr = mysql_fetch_row($result_fr);

$query_report = "SELECT `way`,`cash` FROM `drivers_report` WHERE `trip`='".$report_trip."' AND `delete`='0'";
$result_report = mysql_query($query_report) or die(mysql_error());
$cash_back=0;
$cash_nal=0;
while($report = mysql_fetch_row($result_report)) {
if($report[0]!=0&&$report[0]!=33&&$report[0]!=10)$cash_nal=(int)$cash_nal+(int)$report[1];
if($report[0]==33)$cash_back=(int)$cash_back+(int)$report[1];
}

$query = "UPDATE `vtl_trip` SET `o_cash`='".($fr[0]-$cash_back-$cash_nal)."' WHERE `Id`='".$report_trip."'";
$result = mysql_query($query) or die(mysql_error());


if($report_way==33){
$query_report = "SELECT `cash` FROM `pays` WHERE `trip_id`='".$report_trip."' AND `delete`='0'";
$result_report = mysql_query($query_report) or die(mysql_error());	
$report = mysql_fetch_row($result_report);	
$query = "UPDATE `pays` SET `cash`='".($report[0]-$report_cash)."' WHERE `trip_id`='".$report_trip."'";
$result = mysql_query($query) or die(mysql_error());
}


if($report_way==10){
$card_id=(int)$_POST['card_id'];
$query_card_p = "UPDATE `vtl_oil_card` SET `card_cash`=(`card_cash`-".$report_cash.") WHERE `id`='".mysql_escape_string($card_id)."'";
$result_card_p = mysql_query($query_card_p) or die(mysql_error());
}

echo 'Сохранено!|1';
} else {echo 'Нет доступа!|1';}

}	

?>