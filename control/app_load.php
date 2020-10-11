<?php
include "../config.php";


$query = "SELECT * FROM `pays_appoints`";
$result = mysql_query($query) or die(mysql_error());



echo '<table cellpadding="5"  style="height: 300px; overflow:auto;border-collapse: collapse;border-style: solid;border-color: black;" border="1">
<tr><td bgcolor="#edf1f3" align=center>№</td><td width="300" bgcolor="#edf1f3">Назначение</td><td width="100" bgcolor="#edf1f3">Категория</td><td width="100" bgcolor="#edf1f3">Направление</td><td width="60" bgcolor="#edf1f3">Доступ</td><td width="250" bgcolor="#edf1f3">Комментарий</td><td bgcolor="#edf1f3" align=center>Управление</td></tr>';

while($pays_app= mysql_fetch_row($result)) {
switch ($pays_app[5]) {
case '0': $way='-';break;
case '1': $way='Поступление';break;
case '2': $way='Выплата';break;
} 

switch ($pays_app[2]) {
case '1': $category='Основная';break;
case '2': $category='Дополнительная';break;
}

switch ($pays_app[4]) {
case '1': $auth='<b>Дир.</b>';break;
case '3': $auth='Б&Д';break;
}

if($pays_app[0]<=6&&@$_GET['load_appoints']=="1"){echo '<tr><td align=center bgcolor=#F2F5F7>'.$pays_app[0].'</td><td bgcolor=#F9F9F9><b>«'.$pays_app[1].'»</b></td><td bgcolor=#F9F9F9>'.$category.'</td><td bgcolor=#F9F9F9>'.$way.'</td><td align=center bgcolor=#F9F9F9>'.$auth.'</td><td bgcolor=#F9F9F9 >'.$pays_app[3].'</td><td bgcolor=#FFD1D1></td></tr>';} 
	if($pays_app[0]>6&&@$_GET['load_appoints']=="2"){echo '<tr><td align=center>'.$pays_app[0].'</td><td><b>«'.$pays_app[1].'»</b></td><td>'.$category.'</td><td>'.$way.'</td><td align=center>'.$auth.'</td><td width="250">'.$pays_app[3].'</td><td align=center bgcolor=#C7FFA3><a href="" onclick=\'
$("#result").html("<b>Удалить назначение <font color=red>«'.$pays_app[1].'»</font>?</b>");
$("#result").dialog({ title: "Внимание" },{ modal: true },{ width: 350 },{ resizable: false },{ buttons: [{text: "Да",click: function() {$.post("control/admin.php?del_appoint='.$pays_app[0].'", function(data) {alert(data);$("#appoints").load("/control/app_load.php?load_appoints=2");});$(this).dialog("close");}},{text: "Нет",click: function() {$(this).dialog("close");}}] });


\'><b>удалить</b></a></td></tr>';}
}


echo '</table>';





?>