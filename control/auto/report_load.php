<?php
include "../../config.php";

if (@$_GET['trip']!=""){
$trip=(int)$_GET['trip'];

echo '<table cellpadding="5"  style="border-collapse: collapse;border-style: solid;border-color: black;" border="1">
<tr><td bgcolor="#edf1f3" align="center"><b>№</b></td><td bgcolor="#edf1f3" align="center"><b>Дата</b></td><td width="150" bgcolor="#edf1f3"><b>Категория</b></td><td width="120" bgcolor="#edf1f3" align="center"><b>Сумма</b></td><td width="250" bgcolor="#edf1f3"><b>Комментарий</b></td><td bgcolor="#edf1f3" align="center" width="100">Управление</td></tr>';

$query = "SELECT * FROM `drivers_report` WHERE `trip`='".$trip."' AND `delete`='0' ORDER BY `id` ASC";
$result = mysql_query($query) or die(mysql_error());

if(mysql_num_rows($result)==0){echo '<tr><td colspan="5">Отсутствуют статьи расходов</td></tr>';} else {

while($drv_report= mysql_fetch_row($result)) {

switch ($drv_report[2]) {
case '1': $way='Суточные';break;
case '2': $way='Стоянка';break;
case '3': $way='Штрафы ГАИ';break;
case '4': $way='Платная дорога';break;
case '5': $way='Охрана';break;
case '6': $way='Автозапчасти';break;
case '7': $way='Шиномонтаж';break;
case '8': $way='Услуги ремонта';break;
case '9': $way='ГСМ (НАЛ)';break;
case '10': $way='ГСМ (БезНАЛ)';break;
case '11': $way='Инструменты';break;
case '12': $way='Простой';break;
case '13': $way='Лизинг';break;
case '14': $way='Страховка';break;
case '15': $way='Мобильная связь';break;
case '33': $way='Возврат';break;

} 

if($drv_report[7]!=0)$petrol=($drv_report[7]/100).' литров'; else $petrol='';

echo '<tr><td align="center">'.$drv_report[0].'</td><td>'.date("d/m/Y",strtotime($drv_report[5])).'</td><td><b>«'.$way.'»</b></td><td align="center">'.((int)$drv_report[3]/100).'</td><td>'.$petrol.$drv_report[4].'</td><td align="center" bgcolor="#C7FFA3"><a href="" onclick=\'
$("#result").html("<b>Удалить №<font color=red>«'.$drv_report[0].'»</font>?</b>");
$("#result").dialog({ title: "Внимание" },{ modal: true },{ width: 350 },{ resizable: false },{ buttons: [{text: "Да",click: function() {$.post("control/auto/trip.php?mode=delete&report_way='.$drv_report[2].'&report_cash='.$drv_report[3].'&trip='.$drv_report[0].'&card_id='.$drv_report[8].'", function(data) {$("#appoints").load("control/auto/report_load.php?trip='.$drv_report[1].'");});$(this).dialog("close");}},{text: "Нет",click: function() {$(this).dialog("close");}}] });


\'><b>удалить</b></a></td></tr>';
}
}



echo '</table>';
}


?>