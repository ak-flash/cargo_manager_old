<?php
// Подключение и выбор БД
include "../config.php";	


if ($_GET['ord_id']!=''){
$id=$_GET['ord_id'];


$query_order= "SELECT * FROM `orders` WHERE `Id`=".mysql_escape_string($id);
$result_order = mysql_query($query_order) or die(mysql_error());
if(mysql_num_rows($result_order)>= 1){

echo "<font color='red' size='5'><div align='center'>Заявка №".$id." уже есть в базе!</div></font>";
}
}
?>