<?php
include "../config.php";
session_start();
if(@$_GET['mode']=="random"){
$query = "SELECT `show_info` FROM `workers` WHERE `Id`='".mysql_escape_string((int)$_SESSION["user_id"])."'";
$result = mysql_query($query) or die(mysql_error());
$datas= mysql_fetch_row($result);

if(strtotime($datas[0])!=strtotime(date("Y-m-d"))){
$num = mysql_num_rows(mysql_query("SELECT `id` FROM `information`"));
if(@$_GET['mode']=="next"&&$num>(int)$_SESSION["vtl_info_id"])$query = "SELECT `info`,`Id` FROM `information` WHERE `Id`='".((int)$_SESSION["vtl_info_id"]+1)."'"; else 
$query = "SELECT `info`,`Id` FROM `information` LIMIT 1";





$result = mysql_query($query) or die(mysql_error());
$info= mysql_fetch_row($result);


echo $info[0];
$_SESSION["vtl_info_id"]=$info[1];
}
}

if(@$_GET['mode']=="notify"){
$query = "SELECT `date_to`,`date_s`,`name`,`type`,`number` FROM `vtl_auto`";
$result = mysql_query($query) or die(mysql_error());


while($datas = mysql_fetch_row($result)) {
	
	switch ($datas[3]) {
case '1': $type='Тягач';break;
case '2': $type='Полу/прицеп';break;
case '3': $type='Грузовик';break;
case '4': $type='Прицеп';break;

}


	
if($datas[0]!="1970-01-01"&&$datas[0]!="0000-00-00"&&$datas[0]!=""&&strtotime('-15 day', strtotime($datas[0]))<=strtotime('now')&&strtotime($datas[0])>strtotime('now')){
echo "У автотранспорта <b>".$type." <font color='yellow' size='4'>".$datas[2]."</font></b> Г/н: <b><font color='yellow' size='4'>".$datas[4].'</font></b> действие <b>талона тех. осмотра</b> истекает <font color="yellow" size="4">'.date('d/m/Y',strtotime($datas[0]))." г.</font><br>";
}

if($datas[0]!="1970-01-01"&&$datas[0]!="0000-00-00"&&$datas[0]!=""&&strtotime($datas[0])<strtotime('now')){
echo "У автотранспорта <b>".$type." <font color='yellow' size='4'>".$datas[2]."</font></b> Г/н: <b><font color='yellow' size='4'>".$datas[4].'</font></b> действие <b>талона тех. осмотра</b> <font color="#FAB1B7" size="4">закончилось</font> <font color="yellow" size="4">'.date('d/m/Y',strtotime($datas[0]))." г.</font><br>";
}

if($datas[1]!="1970-01-01"&&$datas[1]!="0000-00-00"&&$datas[1]!=""&&strtotime('-15 day', strtotime($datas[1]))<=strtotime('now')&&strtotime($datas[1])>strtotime('now')){
echo "У автотранспорта <b>".$type." <font color='yellow' size='4'>".$datas[2]."</font></b> Г/н: <b><font color='yellow' size='4'>".$datas[4].'</font></b> действие <b>страховки</b> истекает <font color="yellow" size="4">'.date('d/m/Y',strtotime($datas[1]))." г.</font><br>";
}

if($datas[1]!="1970-01-01"&&$datas[1]!="0000-00-00"&&$datas[1]!=""&&strtotime($datas[1])<strtotime('now')){
echo "У автотранспорта <b>".$type." <font color='yellow' size='4'>".$datas[2]."</font></b> Г/н: <b><font color='yellow' size='4'>".$datas[4].'</font></b> действие <b>страховки</b> <font color="#FAB1B7" size="4">закончилось</font> <font color="yellow" size="4">'.date('d/m/Y',strtotime($datas[1]))." г.</font><br>";
}

}
}
?>