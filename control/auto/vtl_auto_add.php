<?php
// Подключение и выбор БД
include "../../config.php";
$error='Не заполнено поле <font color="red">';
$err='</font><br>';
$validate=true;
if(@$_POST['type_auto']=="0"){echo '<font color="red" size="5">Выберите тип транспорта'.$err;$validate=false;}
if(@$_POST['car_name']==""){echo $error.'"марка автомобиля"'.$err;$validate=false;}
if(@$_POST['car_number']==""){echo $error.'"гос. номер авто"'.$err;$validate=false;}
//if(@$_POST['auto_v']==""){echo $error.'"объем"'.$err;$validate=false;}
//if(@$_POST['car_load1']==""&&@$_POST['car_load2']==""&&@$_POST['car_load3']==""){echo $error.'"вид погрузки"'.$err;$validate=false;}
//if(@$_POST['petrol']==""&&(@$_POST['type_auto']==1||@$_POST['type_auto']==3)){echo $error.'"Остаток топлива"'.$err;$validate=false;} 
//if(@$_POST['lpk']==""&&(@$_POST['type_auto']==1||@$_POST['type_auto']==3)){echo $error.'"Расход топлива"'.$err;$validate=false;} 
if(@$_POST['daykm']==""&&(@$_POST['type_auto']==1||@$_POST['type_auto']==3)){echo $error.'"Норма суточного пробега"'.$err;$validate=false;} 



	
if($validate)
{
$type_auto=(int)$_POST['type_auto'];
$car_name=addslashes($_POST['car_name']);
$car_number=addslashes($_POST['car_number']);


$car_v=(int)$_POST['auto_v'];
$car_dop=(int)$_POST['dop_auto'];
$drv_auto=(int)$_POST['drv_auto'];
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

$petrol=(int)$_POST['petrol'];
$lpk=(int)$_POST['lpk'];
$daykm=(int)$_POST['daykm'];
$in_elements  = explode("/",$_POST['date_ve']);
$date_ve=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));
$in_elements  = explode("/",$_POST['date_to']);
$date_to=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));
$in_elements  = explode("/",$_POST['date_s']);
$date_s=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));
$inv=addslashes($_POST['inv']);

if(@$_POST['edit']=="1"){
$query = "UPDATE `vtl_auto` SET `name`='$car_name',`type`='$type_auto',`number`='$car_number',`date_ve`='$date_ve',`lpk`='$lpk',`petrol`='$petrol',`v`='$car_v',`km`='$daykm',`car_load`='$car_load_save',`inv`='$inv',`date_to`='$date_to',`date_s`='$date_s',`dop_car`='$car_dop',`driver`='$drv_auto' WHERE `id`='".mysql_escape_string($_POST['car_idd'])."'";
}
else
{
$query = "INSERT INTO `vtl_auto` (`name`,`type`,`number`,`date_ve`,`lpk`,`petrol`,`v`,`km`,`car_load`,`inv`,`date_to`,`date_s`,`dop_car`,`driver`) VALUES ('$car_name','$type_auto','$car_number','$date_ve','$lpk','$petrol','$car_v','$daykm','$car_load_save','$inv','$date_to','$date_s','$car_dop','$drv_auto')";
}

$result = mysql_query($query) or die(mysql_error());

$query = "UPDATE `vtl_auto` SET `driver`='$drv_auto' WHERE `id`='".mysql_escape_string($car_dop)."'";
$result = mysql_query($query) or die(mysql_error());

echo 'Сохранено!|1';
} else {echo 'Не все поля заполнены!';}	

?>


