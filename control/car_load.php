<?php  
include "../config.php";

if (@$_GET['del_car']!=""){
$del_car=(int)$_GET['del_car'];
$query_pays = "UPDATE `tr_autopark` SET `delete`='1' WHERE Id='".mysql_escape_string($del_car)."'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
}


if (@$_GET['tr']!=""){
$tr=(int)$_GET['tr'];	
	
$query_car = "SELECT * FROM `tr_autopark` WHERE `transporter`='".mysql_escape_string($tr)."' AND `delete`='0'";
$result_car = mysql_query($query_car) or die(mysql_error());
$f=0;
echo '<div style="height: 245px;width: 103%; overflow: auto;">';
while($car_info = mysql_fetch_row($result_car)) {
switch ($car_info[6]) {
case '1': $car_load='верхняя';break;
case '2': $car_load='задняя';break;
case '3': $car_load='боковая';break;
case '4': $car_load='верхняя,задняя';break;
case '5': $car_load='задняя,боковая';break;
case '6': $car_load='верхняя,боковая';break;
case '7': $car_load='верхняя,боковая,задняя';break;
} 


echo '<div style="padding: 5px;border-radius: 5px;background: #ddd;border: 1px solid #bbb;width: 97%;text-align:left;" id="car'.$car_info[0].'"><table width="850"><tr><td>'.($f+1).') Марка: <b>'.$car_info[2].'</b> [Г/н: <b>'.$car_info[3].'</b>] ('.$car_info[4].' т; '.$car_info[5].' м3; загрузка: <b>'.$car_load.'</b>; тип кузова: <b>'.$car_info[12].'</b>)</td><td align="center" width="90"><a href="#" id="car" onclick=\'$("#fa_car").load("theme/forms/add_car.php?tr='.$tr.'&mode=car&edit=1&car_id='.$car_info[0].'");$("#fa_car").dialog({ title: "Новый транспорт" },{width: 480,height: 800,modal: true,resizable: false});\'style="text-decoration:none;float:left;"><img src="data/img/document-edit.png" style="width:35px;"></a>&nbsp;<a href="#" id="car" onclick=\'$("#result").html("<b>Удалить автотранспорт <font color=red>«'.$car_info[3].'»</font></b>");
$("#result").dialog({ title: "Внимание" },{ modal: true },{ width: 350 },{ resizable: false },{ buttons: [{text: "Да",click: function() {$.post("control/car_load.php?del_car='.$car_info[0].'", function(data) {$("#car_info").load("/control/car_load.php?tr='.$tr.'");});$(this).dialog("close");}},{text: "Нет",click: function() {$(this).dialog("close");}}] });\' style="text-decoration:none;"><img src="data/img/delete.png" style="width:35px;"></a></td></tr></table></div>';

if($row['pref']==3){echo "<script type=\"text/javascript\">document.getElementById('tr_dchief').style.visibility = 'hidden';document.getElementById('tr_dchief_contract').style.visibility = 'hidden';$('#tr_inn').mask('999999999999');</script>";}  
$f++;}
echo '</div>';
}
?>	