<?php
// Подключение и выбор БД
include "../../config.php";

$validate=true;
if(@$_POST['auto']=="0"){echo '<font color="red" size="4">Выберите Тягач!</font><br>';$validate=false;}
if(@$_POST['dop_auto']=="0"){echo '<font color="red" size="4">Выберите Прицеп!</font><br>';$validate=false;}



	
if($validate)
{
$auto=(int)$_POST['auto'];
$dop_auto=(int)$_POST['dop_auto'];

if(@$_POST['edit']=="1"){
$query = "UPDATE `workers` SET `auto`='$auto',`dop_auto`='$dop_auto' WHERE `id`='".mysql_escape_string($_POST['drv_idd'])."'";
$result = mysql_query($query) or die(mysql_error());

$query = "UPDATE `vtl_auto` SET `driver`='".mysql_escape_string($_POST['drv_idd'])."' WHERE `id`='".mysql_escape_string($auto)."'";
$result = mysql_query($query) or die(mysql_error());

$query = "UPDATE `vtl_auto` SET `driver`='".mysql_escape_string($_POST['drv_idd'])."' WHERE `id`='".mysql_escape_string($dop_auto)."'";
$result = mysql_query($query) or die(mysql_error());

echo 'Сохранено!|1';}
} else {echo 'Не все поля заполнены!';}	

?>


