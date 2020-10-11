<?php
include "../config.php";
 header("Content-type: text/script;charset=utf-8");
$car=$_GET['id'];

$query_car = "SELECT * FROM `tr_autopark` WHERE `id`='".$car."'";
$result_car = mysql_query($query_car) or die(mysql_error());

$car = mysql_fetch_row($result_car);

switch ($car[18]) {
case '0': $check_info="<font color='grey'>не проверялся";break;
case '1': $check_info="<font color='#5954FF'>ожидает проверки";break;
case '2': $check_info="<font color='green'>подтверждено";break;
case '3': $check_info="<font color='red'>недостоверные данные";break;
}
echo $car[0].'|'.$car[2].'|'.$car[3].'|'.$car[7].'|'.$car[8].'|'.$car[9].'|'.$car[10].'|'.$car[11].'|'.$car[19].'|'.$check_info.'</font>';

?>