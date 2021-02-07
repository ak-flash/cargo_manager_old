<?php
// Подключение и выбор БД
include "../config.php";
$error='Не заполнено поле <font color="red">';
$err='</font><br>';
$validate=true;
if(@$_POST['tr']==""){echo $error.'"перевозчик"'.$err;$validate=false;}
if(@$_POST['car_name']==""){echo $error.'"марка автомобиля"'.$err;$validate=false;}
if(@$_POST['car_number']==""){echo $error.'"гос. номер авто"'.$err;$validate=false;}
if(@$_POST['car_m']==""){echo $error.'"грузоподьемность"'.$err;$validate=false;}
if(@$_POST['car_v']==""){echo $error.'"объем"'.$err;$validate=false;}
if(@$_POST['car_load1']==""&&@$_POST['car_load2']==""&&@$_POST['car_load3']==""){echo $error.'"вид погрузки"'.$err;$validate=false;}
if(@$_POST['car_driver_name']==""){echo $error.'"фамилия водителя"'.$err;$validate=false;} 
if(@$_POST['car_driver_doc1']==""){echo $error.'"номер паспорта водителя"'.$err;$validate=false;} 
if(@$_POST['car_driver_doc2']==""){echo $error.'"кем выдан паспорт водителя"'.$err;$validate=false;} 
if(@$_POST['car_driver_doc3']==""){echo $error.'"когда выдан паспорт водителя"'.$err;$validate=false;} 
if(@$_POST['car_driver_phone']==""){echo $error.'"номер телефона водителя"'.$err;$validate=false;} 


	
if($validate)
{
$transporter=$_POST['tr'];
$car_name=mysql_real_escape_string(stripslashes($_POST['car_name']));
$car_number=mysql_real_escape_string(stripslashes($_POST['car_number']));
$car_kuzov=$_POST['car_kuzov_other'];
$car_m=$_POST['car_m'];
$car_v=$_POST['car_v'];

$car_load1=$_POST['car_load1'];
$car_load2=$_POST['car_load2'];
$car_load3=$_POST['car_load3'];

if($car_load1=="1"&&$car_load2==""&&$car_load3=="") $car_load_save=1;
if($car_load1==""&&$car_load2=="2"&&$car_load3=="") $car_load_save=2;
if($car_load1==""&&$car_load2==""&&$car_load3=="3") $car_load_save=3;
if($car_load1=="1"&&$car_load2=="2"&&$car_load3=="") $car_load_save=4;
if($car_load1==""&&$car_load2=="2"&&$car_load3=="3") $car_load_save=5;
if($car_load1=="1"&&$car_load2==""&&$car_load3=="3") $car_load_save=6;
if($car_load1=="1"&&$car_load2=="2"&&$car_load3=="3") $car_load_save=7;

$car_extra_name=mysql_real_escape_string(stripslashes($_POST['car_extra_name']));
$car_extra_number=mysql_real_escape_string(stripslashes($_POST['car_extra_number']));
$car_driver_name=mysql_real_escape_string(stripslashes($_POST['car_driver_name']));

$car_driver_inn=(int)$_POST['car_driver_inn'];

$car_driver_doc=mysql_real_escape_string(stripslashes($_POST['car_driver_doc1'])).'|'.mysql_real_escape_string(stripslashes($_POST['car_driver_doc2'])).'|'.mysql_real_escape_string(stripslashes($_POST['car_driver_doc3']));
$car_driver_phone=mysql_real_escape_string(stripslashes($_POST['car_driver_phone']));
$car_owner=mysql_real_escape_string(stripslashes($_POST['car_owner']));
$car_owner_doc=mysql_real_escape_string(stripslashes($_POST['car_owner_doc']));
$car_owner_check=mysql_real_escape_string(stripslashes($_POST['car_owner_check']));



$car_owner_type=(int)$_POST['car_owner_type'];


$car_notify=mysql_real_escape_string(stripslashes($_POST['car_notify']));

if(@$_POST['edit']=="1"){
$query = "UPDATE `tr_autopark` SET `transporter`='$transporter',`car_name`='$car_name',`car_number`='$car_number',`car_m`='$car_m',`car_v`='$car_v',`car_load`='$car_load_save',`car_extra_name`='$car_extra_name',`car_extra_number`='$car_extra_number',`car_driver_name`='$car_driver_name',`car_driver_doc`='$car_driver_doc',`car_driver_phone`='$car_driver_phone',`car_kuzov`='$car_kuzov',`car_owner`='$car_owner',`car_owner_doc`='$car_owner_doc',`car_notify`='$car_notify',`car_owner_type`='$car_owner_type',`car_driver_inn`='$car_driver_inn' WHERE `id`='".mysql_escape_string($_POST['car_idd'])."'";
}
else
{
$query = "INSERT INTO `tr_autopark` (`transporter`,`car_name`,`car_number`,`car_m`,`car_v`,`car_load`,`car_extra_name`,`car_extra_number`,`car_driver_name`,`car_driver_doc`,`car_driver_phone`,`car_kuzov`,`car_owner`,`car_owner_doc`,`car_notify`,`car_driver_inn`,`car_owner_type`) VALUES ('$transporter','$car_name','$car_number','$car_m','$car_v','$car_load_save','$car_extra_name','$car_extra_number','$car_driver_name','$car_driver_doc','$car_driver_phone','$car_kuzov','$car_owner','$car_owner_doc','$car_notify','$car_driver_inn','$car_owner_type')";
}
$result = mysql_query($query) or die(mysql_error());

echo 'Сохранено!|1';
} else {echo 'Не все поля заполнены!';}	

?>


