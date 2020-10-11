<?php

// Подключение и выбор БД
include "../config.php";
session_start();


$validate=true;

$err='Не заполнено поле <font color="red">';
$er='</font><br>';

if(@$_POST['company_id']!=1){
if(@$_POST['company_rs']==""||mb_ereg("[^0-9]",$_POST['company_rs'])){echo $err.' Рассчетный счет'.$er;$validate=false;}
if(@$_POST['company_bank']==""){echo $err.' Банк'.$er;$validate=false;}
if(@$_POST['company_bik']==""||mb_ereg("[^0-9]",$_POST['company_bik'])){echo $err.' БИК'.$er;$validate=false;}
if(@$_POST['company_ks']==""||mb_ereg("[^0-9]",$_POST['company_ks'])){echo $err.' Корр.счет'.$er;$validate=false;}
}

if(@$_POST['company_cash']==""||mb_ereg("[^0-9.]",$_POST['company_cash'])){echo $err.' Баланс счета'.$er;$validate=false;}

	
if($validate)
{
$company_rs=$_POST['company_rs'];
$company_bank=addslashes($_POST['company_bank']);
$company_bik=$_POST['company_bik'];
$company_ks=$_POST['company_ks'];	 
$company_cash=(float)$_POST['company_cash']*100;
$company_id=(int)$_POST['company_id'];



if(@$_POST['edit']=="1"&&@$_POST['c_bill']!="")
{
$c_bill=$_POST['c_bill'];	 
$query = "UPDATE `bill` SET `company`='$company_id',`c_bill`='$company_rs',`c_bik`='$company_bik',`c_bank`='$company_bank',`c_ks`='$company_ks',`c_cash`='$company_cash' WHERE `id`='".mysql_escape_string($c_bill)."'";
$result = mysql_query($query) or die(mysql_error());
	 	 
	 	 
echo "Счет №".$company_rs." обновлён|1";	 
}
else {$query = "INSERT INTO `bill` (`company`,`c_bill`,`c_bik`,`c_bank`,`c_ks`,`c_cash`,`delete`,`default`) VALUES ('$company_id','$company_rs','$company_bik','$company_bank','$company_ks','$company_cash','0','0')";
$result = mysql_query($query) or die(mysql_error());
	 	 
	 	 
echo "Счет №".$company_rs." создан|1";}	 
	 
	 	 
	 }

	

?>