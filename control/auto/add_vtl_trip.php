<?php
// Подключение и выбор БД
include "../../config.php";

$validate=true;
if(@$_POST['date_trip']==""){echo '<font color="red" size="4">Заполните Дату рейса!</font><br>';$validate=false;}
if(@$_POST['order_trip']==""){echo '<font color="red" size="4">Заполните номера заявок!</font><br>';$validate=false;}



	
if($validate)
{

$in_elements  = explode("/",$_POST['date_trip']);
$date_trip=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));

$notify=mysql_real_escape_string(stripslashes($_POST['trip_notify']));

$str_order = explode(',',str_replace(" ", "", $_POST['order_trip'].','));
if(is_numeric($str_order[0])){
$str_order_list =(int)sizeof($str_order)-2;
$f=0;
$str_p_list='';
while ($f<=$str_order_list) {


$str_p_list.=(int)$_POST['p_list_'.($str_order_list-$f+1)].'&';

$order_trip=$order_trip.$str_order[($str_order_list-$f)].'&';
$query_order = "SELECT `tr_auto`,`id`,`transp` FROM `orders` WHERE `id`='".(int)$str_order[($str_order_list-$f)]."'";
$result_order = mysql_query($query_order) or die(mysql_error());
$order = mysql_fetch_row($result_order);
$t_order= explode('&',$order[0]);

$query_fr = "SELECT `id` FROM `vtl_trip` WHERE `order` LIKE '%".(int)$str_order[($str_order_list-$f)]."&%' AND `delete`='0'";
$result_fr = mysql_query($query_fr) or die(mysql_error());
if (mysql_num_rows($result_fr)>0&&@$_POST['edit']!="1"){$fr = mysql_fetch_row($result_fr);$ord_fail=$ord_fail."По заявке №<b>".(int)$str_order[($str_order_list-$f)]."</b> уже имеется рейс №<b>".$fr[0]."</b>!<br>";$validate=false;}

if(($q_order[0]!=$t_order[0]&&$f>=1)||($q_order[2]!=$t_order[2]&&$f>=1)||!is_numeric($t_order[2])){$ord_fail=$ord_fail."Заявка №<b>".$n_order."</b> не соответствует водителю и автотранспорту!<br>"; $validate=false;}

if($order[2]!=2){$ord_fail=$ord_fail."Компания с собственным транспортом не является перевозчиком в  заявке №<b>".$order[1]."</b><br>";$validate=false;}

$q_order= explode('&',$order[0]);
$m_order=$order[0];
$n_order=$order[1];
$f++;

}

if($validate){

if(@$_POST['edit']=="1"){
$query = "UPDATE `vtl_trip` SET `data`='$date_trip',`order`='$order_trip',`notify`='$notify',`tr_auto`='$m_order',`p_list`='$str_p_list' WHERE `id`='".mysql_escape_string((int)$_POST['trip_id'])."'";
$result = mysql_query($query) or die(mysql_error());
echo 'Сохранено!|1';} else {
$query = "INSERT INTO `vtl_trip` (`data`,`order`,`notify`,`tr_auto`) VALUES ('$date_trip','$order_trip','$notify','$m_order')";
$result = mysql_query($query) or die(mysql_error());

echo 'Добавлено!|1';
}

} else echo $ord_fail;
} else echo 'Номера заявок указаны неверно!<br>';

} else {echo 'Не все поля заполнены!';}	

?>


