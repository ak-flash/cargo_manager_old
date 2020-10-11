<?php
// Подключение и выбор БД
include "../config.php";

$validate=true;
if(@$_POST['add_bill_cl']==""){echo '<font color="red" size="4">Укажите номер счета!</font><br>';$validate=false;}if(@$_POST['add_date_cl_bill']==""){echo '<font color="red" size="4">Заполните Дату!</font><br>';$validate=false;}
if(@$_POST['add_adidas_ord']==""){echo '<font color="red" size="4">Заполните номера заявок!</font><br>';$validate=false;}
	
if($validate)
{
$cl_bill=mysql_real_escape_string(stripslashes($_POST['add_bill_cl']));
$in_elements  = explode("/",$_POST['add_date_cl_bill']);
$add_date_cl_bill=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));


$str_order = explode(',',str_replace(" ", "", $_POST['add_adidas_ord'].','));
if(is_numeric($str_order[0])){
$str_order_list =(int)sizeof($str_order)-2;
$f=0;

while ($f<=$str_order_list) {
$query_order = "SELECT `cl_bill`,`id` FROM `docs` WHERE `order`='".(int)$str_order[($str_order_list-$f)]."'";
$result_order = mysql_query($query_order) or die(mysql_error());
$order = mysql_fetch_row($result_order);
if($order[0]==""){
$query = "UPDATE `docs` SET `cl_bill`='$cl_bill',`date_add_bill`='$add_date_cl_bill' WHERE `id`='".$order[1]."'";
$result = mysql_query($query) or die(mysql_error());
} else {
$query = "UPDATE `docs` SET `cl_bill`='".$order[0]."/".$cl_bill."' WHERE `id`='".$order[1]."'";
$result = mysql_query($query) or die(mysql_error());
}

$f++;
}

} else {echo 'Номера заявок указаны неверно!<br>';$error=true;}







if(!$error)echo 'Обновлено успешно!|1';


}	

?>