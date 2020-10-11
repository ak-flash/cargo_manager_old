<?php
// Подключение и выбор БД
include "../../config.php";

$validate=true;
if(@$_POST['city_in']==""){echo '<font color="red" size="4">Заполните город загрузки!</font><br>';$validate=false;}
if(@$_POST['city_out']==""){echo '<font color="red" size="4">Заполните город выгрузки!</font><br>';$validate=false;}

if(@$_POST['order']==""&&(int)$_POST['status']==3){echo '<font color="red" size="4">Не указан номер заявки!</font><br>';$validate=false;}

if(@$_POST['cash_cl']==""){echo '<font color="red" size="4">Не указана ставка клиента!</font><br>';$validate=false;}
	
if($validate)
{

$city_in=mysql_real_escape_string($_POST['city_in']);
$city_out=mysql_real_escape_string($_POST['city_out']);
$cash_cl=(int)$_POST['cash_cl'];
$cash_vtl=(int)$_POST['cash_vtl'];
$order=(int)$_POST['order'];
$status=(int)$_POST['status'];
$cl_id=(int)$_POST['cl_id'];
$s_contract=(int)$_POST['s_contract'];
$manager=mysql_real_escape_string($_POST['manager']);
$notify=mysql_real_escape_string($_POST['notify']);
$s_manager=mysql_real_escape_string($_POST['s_manager']);

if(@$_POST['edit']=="1"){
$query = "UPDATE `cl_gruz` SET `city_in`='$city_in',`city_out`='$city_out',`notify`='$notify',`cash_cl`='$cash_cl',`cash_vtl`='$cash_vtl',`order`='$order',`status`='$status',`manager`='$manager',`cl_id`='$cl_id',`s_man`='$s_manager',`s_contract`='$s_contract' WHERE `id`='".mysql_escape_string((int)$_POST['gruz_id'])."'";
$result = mysql_query($query) or die(mysql_error());
echo 'Сохранено!|1';} else {
$query = "INSERT INTO `cl_gruz` (`city_in`,`city_out`,`notify`,`cash_cl`,`manager`,`cl_id`,`cash_vtl`,`order`,`status`,`s_man`,`s_contract`) VALUES ('$city_in','$city_out','$notify','$cash_cl','$manager','$cl_id','$cash_vtl','$order','$status','$s_manager','$s_contract')";
$result = mysql_query($query) or die(mysql_error());

echo 'Добавлено!|1';
}
 

} 	

?>


