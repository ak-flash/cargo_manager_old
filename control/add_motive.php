<?php
// Подключение и выбор БД
include "../config.php";
$error='Не заполнено поле ';
$validate=true;

function ValFail($srt){ 
 return '<script type="text/javascript">$("#'.$srt.'").css({"border-color":"red","background-color":"#FFBABA","color":"#000"});</script>';     
}

function ValOk($srt){ 
 return '<script type="text/javascript">$("#'.$srt.'").css({"border-color":"#187C22","background-color":"#BAE2BD","color":"#000"});</script>';     
}

if(@$_POST['motive_2']==""){echo $error.ValFail('motive_2'); $validate=false;} else {echo ValOk('motive_2');}
if(@$_POST['motive_3']==""){echo $error.ValFail('motive_3'); $validate=false;} else {echo ValOk('motive_3');}
if(@$_POST['motive_4']==""){echo $error.ValFail('motive_4'); $validate=false;} else {echo ValOk('motive_4');}
if(@$_POST['motive_6']==""){echo $error.ValFail('motive_6'); $validate=false;} else {echo ValOk('motive_6');}
if(@$_POST['motive_7']==""){echo $error.ValFail('motive_7'); $validate=false;} else {echo ValOk('motive_7');}
if(@$_POST['motive_8']==""){echo $error.ValFail('motive_8'); $validate=false;} else {echo ValOk('motive_8');}

	
if($validate)
{

$motive_2=addslashes($_POST['motive_2']);
$motive_3=addslashes($_POST['motive_3']);
$motive_4=addslashes($_POST['motive_4']);
$motive_6=addslashes($_POST['motive_6']);
$motive_7=addslashes($_POST['motive_7']);
$motive_8=addslashes($_POST['motive_8']);



$query = "UPDATE `settings` SET  `motive_2`='$motive_2',`motive_3`='$motive_3',`motive_4`='$motive_4',`motive_6`='$motive_6',`motive_7`='$motive_7',`motive_8`='$motive_8'";


$result = mysql_query($query) or die(mysql_error());

echo 'Сохранено!|1';
}	

?>