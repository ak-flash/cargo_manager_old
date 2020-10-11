<?php
include "../config.php";
 header("Content-type: text/script;charset=utf-8");
if(@$_GET['id']!=""){
$tr=(int)$_GET['id'];
$car_id=(int)$_GET['car'];
	
$query_car = "SELECT `id`,`car_number`,`check` FROM `tr_autopark` WHERE `transporter`='".mysql_escape_string($tr)."' AND `delete`='0'";
$result_car = mysql_query($query_car) or die(mysql_error());

echo '<select name="tr_autopark" style="width:130px;" id="tr_autopark" onchange="getCar();$(\'#img\').attr(\'src\',\'data/img/pencil.png\');" class="select"><option value="0">Выберите...</option>';
//document.getElementById(\'vtl_tr\').style.display =\'none\';
$ff=0;
while($car = mysql_fetch_row($result_car)) {
if($car_id==$car[0]){$ff++;}

if($car[2]==3) $car_check=' onclick=\'$("#result_temp").html("<font color=red size=4>Данные СТС и Ф.И.О. собственника недостоверны!</font>");$("#result_temp").dialog({ title: "Внимание" },{ height: 170 },{ width: 300 },{ modal: true },{ resizable: false },{ buttons: [{text: "закрыть",click: function() {$("#result_temp").dialog("close");}}]}   );\''; else $car_check='';
echo '<option value="'.$car[0].'"'.$car_check.'>'.$car[1].'</option>';
}
if($ff==0&&isset($_GET['car'])){
$query_car = "SELECT `id`,`car_number`,`check` FROM `tr_autopark` WHERE `id`='".mysql_escape_string($car_id)."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car_none = mysql_fetch_row($result_car);

if($car_none[2]==3) $car_check=' onclick=\'alert("Ошибка 175!");\''; else $car_check='';
echo '<option value="'.$car_none[0].'"'.$car_check.'>'.$car_none[1].'</option>';

}

echo '</select>';
}

if(@$_GET['mode']=="all"){
$tr=(int)$_GET['tr'];



if(@$_GET['search']=='true'){
if(@$_GET['s_data']!=''){
$query_car = "SELECT * FROM `tr_autopark` WHERE `delete`='0'";
$result_car = mysql_query($query_car) or die(mysql_error());
mb_internal_encoding("UTF-8");

echo '<div style="height: 370px; overflow: auto;">';
while($row = mysql_fetch_array($result_car)) {
	
if(mb_stristr($row['id'], $_GET['s_data'])||mb_stristr($row['car_name'], $_GET['s_data'])||mb_stristr($row['car_number'], $_GET['s_data'])||mb_stristr($row['car_driver_name'], $_GET['s_data'])||mb_stristr($row['car_driver_phone'], $_GET['s_data']))	{
switch ($row['car_load']) {
case '1': $car_load='верхняя';break;
case '2': $car_load='задняя';break;
case '3': $car_load='боковая';break;
} 
echo '<div style="padding: 10px 10px 20px 10px;background: #ddd;border: 1px solid #bbb;width: 97%;text-align:left;" id="car'.$row['id'].'"><div style="float:left;width:700px;">'.$row['id'].') Марка: '.$row['car_name'].' [Г/н: <b>'.$row['car_number'].'</b>] ('.$row['car_m'].' кг,'.$row['car_v'].'  м3, загрузка: '.$car_load.', тип кузова: '.$row['car_kuzov'].')</div><div style="float:right;width:70px;"><a href="#" onclick=\'$("#tr_autopark").append( $("<option value='.$row['id'].'>'.$row['car_number'].'</option>"));$("#fa_show_car").dialog("close");$("#tr_autopark").val('.$row['id'].').change();\'style="text-decoration:none;float:left;">[выбрать]</a></div><br></div>';
}

}
echo '</div>';
} else {echo '&nbsp;&nbsp;&nbsp;А искать то что???';}
} else {

$query_car = "SELECT * FROM `tr_autopark` WHERE `transporter`='".mysql_escape_string($tr)."' AND `delete`='0'";
$result_car = mysql_query($query_car) or die(mysql_error());

echo '<div style="height: 370px; overflow: auto;">';
while($car_info = mysql_fetch_row($result_car)) {
switch ($car_info[6]) {
case '1': $car_load='верхняя';break;
case '2': $car_load='задняя';break;
case '3': $car_load='боковая';break;
} 


echo '<div style="padding: 10px 10px 30px 10px;background: #EFEFEF;border: 1px solid #bbb;width: 97%;text-align:left;" id="car'.$car_info[0].'"><div style="float:left;width:700px;">'.$car_info[0].') Марка: <b>'.$car_info[2].'</b> [Г/н: <b>'.$car_info[3].'</b>] ('.$car_info[4].' кг, '.$car_info[5].' м3, загрузка: '.$car_load.', тип кузова: '.$car_info[12].')<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Водитель: <b>'.$car_info[9].'</b> (<font color=green>'.$car_info[11].'</font>)</div><div style="float:right;width:70px;padding: 10px;"><a href="#" onclick=\'$("#tr_autopark").append( $("<option value='.$car_info[0].'>'.$car_info[3].'</option>"));$("#fa_show_car").dialog("close");$("#tr_autopark").val('.$car_info[0].').change();\'style="text-decoration:none;float:left;">[выбрать]</a></div><br></div>';

}
echo '</div>';
}
}
?>