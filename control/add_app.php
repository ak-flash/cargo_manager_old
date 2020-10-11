<?php
session_start();



function ValFail($srt){ 
 return '<script type="text/javascript">$("#'.$srt.'").css({"border-color":"red","background-color":"#FFBABA","color":"#000"});</script>';     
}

function ValOk($srt){ 
 return '<script type="text/javascript">$("#'.$srt.'").css({"border-color":"#187C22","background-color":"#BAE2BD","color":"#000"});</script>';     
}

// Подключение и выбор БД
include "../config.php";
$error='Не заполнено поле ';
$validate=true;

if(@$_POST['app_name']==""){echo $error.'<font color=red>"Назначение"</font><br>'.ValFail('app_name'); $validate=false;} else {echo ValOk('app_name');}

if(@$_POST['auth_level']=="0"){echo $error.'<font color=red>"Уровень доступа"</font>'; $validate=false;}
	
if($validate)
{



$app_name=addslashes($_POST['app_name']);
$auth_level=(int)$_POST['auth_level'];
$app_notify=addslashes($_POST['app_notify']);


if($_SESSION["group"]==1||$_SESSION["group"]==2){
$query = "INSERT INTO `pays_appoints` (`app`,`group`,`notify`,`auth_id`,`way`) VALUES ('$app_name','2','$app_notify','$auth_level','0')";



$result = mysql_query($query) or die(mysql_error());

echo 'Сохранено!|1';
} else {echo 'Нет доступа!|1';}

}	

?>