<?php


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

if(@$_POST['in_city']==""){echo $error.'"город загрузки"<br>'.ValFail('in_city'); $validate=false;} else {echo ValOk('in_city');}
if(@$_POST['out_city']==""){echo $error.'"город выгрузки"<br>'.ValFail('out_city'); $validate=false;} else {echo ValOk('out_city');}
//if(@$_POST['in_load']==""||!CheckStr($_POST['in_load'])){echo $error.'"вид загрузки"<br>'; $validate=false;}
//if(@$_POST['out_load']==""||!CheckStr($_POST['out_load'])){echo $error.'"вид выгрузки"<br>'; $validate=false;}
//if(@$_POST['times']==""||!CheckStr($_POST['times'])){echo $error.'"периодичность"<br>'; $validate=false;}

	
if($validate)
{
$way_id=(int)$_POST['way_id'];

$tr_id=$_POST['tr_id'];
$cl_id=$_POST['cl_id'];

$in_city=addslashes($_POST['in_city']);
$out_city=addslashes($_POST['out_city']);
$in_load=$_POST['car_in_load'];
$out_load=$_POST['car_out_load'];
$times=$_POST['times'];

if(@$_POST['edit']=="1")
{
$query = "UPDATE `ways` SET  `in_city`='$in_city',`out_city`='$out_city',`in_load`='$in_load',`out_load`='$out_load',`times`='$times' WHERE `id`='$way_id'";

} else {
if($tr_id!=""&&$tr_id!="0")$query = "INSERT INTO `ways` (`in_city`,`out_city`,`in_load`,`out_load`,`times`,`tr`) VALUES ('$in_city','$out_city','$in_load','$out_load','$times','$tr_id')";

if($cl_id!=""&&$cl_id!="0")$query = "INSERT INTO `ways` (`in_city`,`out_city`,`in_load`,`out_load`,`times`,`cl`) VALUES ('$in_city','$out_city','$in_load','$out_load','$times','$cl_id')";

}


$result = mysql_query($query) or die(mysql_error());

echo 'Сохранено!|1';
}	

?>