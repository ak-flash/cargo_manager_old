<?php
// Подключение и выбор БД
include "../../config.php";

$validate=true;

if(@$_POST['cash_refill']==""){echo '<font color="red" size="3">Заполните сумму платежа!</font><br>';$validate=false;}




	
if($validate)
{
$card_id=(int)$_POST['card_id'];
$card_p_id=(int)$_POST['card_p_id'];
$in_elements  = explode("/",$_POST['date_refill']);
$date_refill=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));
$cash_refill=(float)$_POST['cash_refill']*100;
$cash_refill_old=(float)$_POST['cash_refill_old']*100;

if(@$_POST['edit']=="1"){
$query_card_p = "SELECT `card_cash` FROM `vtl_oil_card` WHERE `id`='".mysql_escape_string($card_id)."'";
$result_card_p = mysql_query($query_card_p) or die(mysql_error());
$card_p = mysql_fetch_row($result_card_p);
$card_cash=$card_p[0]+$cash_refill-$cash_refill_old;	
	
	
$query = "UPDATE `drivers_report` SET `date`='$date_refill',`card_oil`='$card_id',`cash`='$cash_refill',`card_cash`='$card_cash' WHERE `id`='".mysql_escape_string($card_p_id)."'";
$result = mysql_query($query) or die(mysql_error());

$query_card_p = "UPDATE `vtl_oil_card` SET `card_cash`=(`card_cash`+".$cash_refill."-".$cash_refill_old.") WHERE `id`='".mysql_escape_string($card_id)."'";
$result_card_p = mysql_query($query_card_p) or die(mysql_error());


echo 'Сохранено!|1';}

else {
$query_card_p = "SELECT `card_cash` FROM `vtl_oil_card` WHERE `id`='".mysql_escape_string($card_id)."'";
$result_card_p = mysql_query($query_card_p) or die(mysql_error());
$card_p = mysql_fetch_row($result_card_p);
$card_cash=$card_p[0]+$cash_refill;

$query = "INSERT INTO `drivers_report` (`way`,`date`,`cash`,`card_oil`,`card_cash`) VALUES ('100','$date_refill','$cash_refill','$card_id','$card_cash')";
$result = mysql_query($query) or die(mysql_error());


$query_card_p = "UPDATE `vtl_oil_card` SET `card_cash`=(`card_cash`+".$cash_refill.") WHERE `id`='".mysql_escape_string($card_id)."'";
$result_card_p = mysql_query($query_card_p) or die(mysql_error());



echo 'Сохранено!|1';
}

} else {echo 'Не все поля заполнены!';}	

?>


