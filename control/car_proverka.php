<?php
session_start();

include "../config.php";

function vk_date($date) {
$m['01']="янв"; 
$m['02']="фев"; 
$m['03']="мар"; 
$m['04']="апр"; 
$m['05']="мая";
$m['06']="июн"; 
$m['07']="июл"; 
$m['08']="авг"; 
$m['09']="сен"; 
$m['10']="окт"; 
$m['11']="ноя";
$m['12']="дек";
	
	if(date('Y-m-d',strtotime($date))==date('Y-m-d'))
$date_m='сегодня в '.date("H:i",strtotime($date));
else if(date('Y-m-d', strtotime($date))==date('Y-m-d', strtotime("-1 day"))) $date_m='вчера в '.date("H:i",strtotime($date)); else $date_m=date("j", strtotime($date)).' '.$m[date("m",strtotime($date))].' в '.date("H:i",strtotime($date));
return $date_m;
}

if ($_GET['mode']=='show_info') {

	

	
$sort=(int)$_GET['sort'];
$page=(int)$_GET['view'];

echo '<table cellpadding="5"  style="width:99%;overflow:auto;border-collapse: collapse;border-style: solid;border-color: black;" border="1" width="100%"><tr style="color:#000000;">
<td bgcolor="#edf1f3" align="center">№</td>
<td bgcolor="#edf1f3" align="center"><b>Дата запроса</b></td>
<td bgcolor="#edf1f3" align="center"><b>Транспорт</b> (Марка/Гос.номер)</td>
<td bgcolor="#edf1f3" align="center"><b>Данные о владельце</b> (Ф.И.О/СТС)</td>
<td bgcolor="#edf1f3" align="center"><b>Заявки</b></td>
<td bgcolor="#edf1f3" align="center"><b>Упр.</b></td></tr>';


$query_car = "SELECT `id`,`car_name`,`car_number`,`car_owner`,`car_owner_doc`,`check_date`,`check`,`transporter` FROM `tr_autopark` WHERE `check_mail`='1' AND `check`='1' ORDER BY `check_date` DESC";
$result_car = mysql_query($query_car) or die(mysql_error());
while($car_info = mysql_fetch_row($result_car)){
$orders='';
$query_ord = "SELECT `id` FROM `orders` WHERE `tr_auto`='".$car_info[0]."' ORDER BY `id` DESC LIMIT 5";
$result_ord = mysql_query($query_ord) or die(mysql_error());
while($ord_info = mysql_fetch_row($result_ord)){
$orders.='<b>'.$ord_info[0].'</b> ';
}

$query_tr = "SELECT `name`,`pref` FROM `transporters` WHERE `id`='".$car_info[7]."'";
$result_tr = mysql_query($query_tr) or die(mysql_error());
$tr_info = mysql_fetch_row($result_tr);

$pref_tr="";
switch ($tr_info[1]) {
case '1': $pref_tr='ООО';break;
case '2': $pref_tr='ОАО';break;
case '3': $pref_tr='ИП';break;
case '4': $pref_tr='ЗАО';break;
case '5': $pref_tr='';break;
}


echo '<tr>
<td align="center">'.$car_info[0].'</td>
<td align="center"><b>'.vk_date($car_info[5]).'</b></td>
<td align="center"><b>'.$car_info[2].'</b> ('.$car_info[1].')</td>
<td align="center"><font size="2">'.$pref_tr.' «'.$tr_info[0].'»</font><br><b>'.$car_info[3].'</b> ('.$car_info[4].')</td>
<td align="center">'.$orders.'</td>
<td align="center"><a href="#" id="car_edit" onclick=\'$("#fa_car").load("add_car.html?mode=car&m=check&car_check_mail=1&edit=1&tr='.$car_info[7].'&car_id='.$car_info[0].'");$("#fa_car").dialog({ title: "Редактировать транспорт" },{width: 480,height: 640,modal: true,resizable: false});\' style="text-decoration:none;"><img src="data/img/document-edit.png" style="width:25px;"></a></td>
</tr>';
}


echo '</table>';

}

?>