<?php
include "../../config.php";
 header("Content-type: text/script;charset=utf-8");

if(@$_GET['mode']=="auto_load"){
$query_car = "SELECT `id`,`name`,`number` FROM `vtl_auto` WHERE (`type`='1' OR `type`='3') AND `delete`='0'";
$result_car = mysql_query($query_car) or die(mysql_error());

echo '<select name="tr_autopark1" style="width:170px;" id="tr_autopark" onchange="var arr = $(\'#tr_autopark :selected\').html().split(/[|]/);$(\'#auto_name\').html(arr[0]);$(\'#auto_number\').html(arr[1]);" class="select"><option value="0">Тягач...</option>';

while($car = mysql_fetch_row($result_car)) {

echo '<option value='.$car[0].'>'.$car[1].' | '.$car[2].'</option>';
}
echo '</select>';
}

if(@$_GET['mode']=="dop_load"){
$query_car = "SELECT `id`,`name`,`number` FROM `vtl_auto` WHERE (`type`='2' OR `type`='4') AND `delete`='0'";
$result_car = mysql_query($query_car) or die(mysql_error());

echo '<select name="tr_autopark2" style="width:170px;" id="dop_autopark" onchange="var arr = $(\'#dop_autopark :selected\').html().split(/[|]/);$(\'#dop_name\').html(arr[0]);$(\'#dop_number\').html(arr[1]);" class="select"><option value="0">Полу/прицеп...</option>';

while($car = mysql_fetch_row($result_car)) {

echo '<option value='.$car[0].'>'.$car[1].' | '.$car[2].'</option>';
}
echo '</select>';
}

if(@$_GET['mode']=="drv_load"){
$query_car = "SELECT `id`,`name` FROM `workers` WHERE `group`='5' and `delete`='0'";
$result_car = mysql_query($query_car) or die(mysql_error());

echo '<select name="tr_autopark3" style="width:125px;" id="drv_autopark" onchange="$(\'#drv_name\').html($(\'#drv_autopark :selected\').html());" class="select"><option value="0">Водитель...</option>';

while($car = mysql_fetch_row($result_car)) {
$pieces = explode(" ", $car[1]);
$print_name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
echo '<option value='.$car[0].'>'.$print_name.'</option>';
}
echo '</select>';
}

?>