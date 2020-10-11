<?php



// Подключение и выбор БД
include "config.php";
session_start();

if(@$_GET['mode']!='pay_add')
{
$error='Не заполнено поле ';
$validate=true;

if(@$_POST['zworker']=="0"){echo $error.' "Сотрудник"<br>'; $validate=false;}
if(@$_POST['zcash']==""){echo $error.' "Сумма"<br>'; $validate=false;}
}
	
if($validate&&@$_GET['mode']!='pay_add')
{
$zworker=(int)$_POST['zworker'];

$zway=(int)$_POST['zway'];
$zmonth=(int)$_POST['zmonth'];
$zyear=(int)$_POST['zyear'];
$zcash=$_POST['zcash']*100;



if($_POST['zedit']==0)
$query = "INSERT INTO `zpay` (`worker`,`way`,`time`,`cash`) VALUES ('$zworker','$zway','".$zyear."-".$zmonth."-01','".(int)$zcash."')";
else {

if($_POST['zcash']!=0)
$query = "UPDATE `zpay` SET `worker`='$zworker',`way`='$zway',`time`='".$zyear."-".$zmonth."-01',`cash`='".(int)$zcash."' WHERE `Id`='".mysql_escape_string($_POST['zedit'])."'"; else {$query = "DELETE FROM `zpay` WHERE `Id`='".mysql_escape_string($_POST['zedit'])."'";$zdel="1";}
}


$result = mysql_query($query) or die(mysql_error());

echo 'Сохранено!|1|'.(int)$zcash.'|'.(int)$zworker.'|'.(int)$zway.'|'.$zmonth.'|'.$zyear.'|'.$zdel;
}	
if(@$_GET['mode']=="pay_add"){

$date_pay=date("Y-m-d",strtotime("now"));;
$add_name=(int)$_SESSION['user_id'];

$pay_cash=$_GET['cash'];
$worker=$_GET['worker'];
$way=$_GET['way'];
$month=$_GET['month'];
$year=$_GET['year'];

$query_user = "SELECT `name` FROM `workers` WHERE `id`='".mysql_escape_string($worker)."'";
$result_user = mysql_query($query_user) or die(mysql_error());
$user = mysql_fetch_row($result_user);

switch ($way) {
case '1': $zway='Зарплата';break;
case '2': $zway='Премия';break;
case '3': $zway='Аванс';break;
}
	
$query = "INSERT INTO `pays` (`date`,`way`,`nds`,`category`,`appoint`,`order`,`cash`,`status`,`notify`,`add_name`,`payment_source`) VALUES ('$date_pay','2','0','2','10','','$pay_cash','0','$zway - $user[0] - $month.$year г.','$add_name','0')";
$result = mysql_query($query) or die(mysql_error());
	 	 
	 	 
echo "Платеж создан ($zway - $user[0])";	


}
?>