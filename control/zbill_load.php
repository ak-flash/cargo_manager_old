<?php
include "config.php";
session_start();

if($_GET['mode']=='delete') {
$query = "DELETE FROM `zpay` WHERE `id`='".(int)$_GET['zedit']."'";
$result = mysql_query($query) or die(mysql_error());
}


if($_GET['mode']=='fire') $query_user = "SELECT `id`,`name`,`zarplata` FROM `workers` WHERE `delete`='1'"; else $query_user = "SELECT `id`,`name`,`zarplata` FROM `workers` WHERE `delete`='0'";

$result_user = mysql_query($query_user) or die(mysql_error());


$zarplata_all=0;

echo '<table border="1" style="padding: 10px;border: 1px solid #bbb;width: 100%;"><tr style="background: #ddd;font-size:16px;"><td align="center" style="padding: 10px;">Ф.И.О.</td><td align="center" style="padding: 10px;background: green;color:#FFF;">Оклад</td><td align="center" style="padding: 10px;background: green;color:#FFF;">Переработка</td><td align="center" style="padding: 10px;background: green;color:#FFF;">В. за подкл.</td><td align="center" style="padding: 10px;background: red;color:#FFF;">Штрафы</td><td align="center" style="padding: 10px;background: red;color:#FFF;">Удержано</td><td align="center" style="padding: 10px;">На карту</td><td align="center" style="padding: 10px;">Аванс</td><td align="center" style="padding: 10px;">Итого</td></tr>'; 
while($user = mysql_fetch_row($result_user)) {
$zarplata=0;
$pieces = explode(" ", $user[1]);
$name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";

echo '<tr><td><b><font size="4"><a href="#" style="color:#000;text-decoration: none;" onclick=\'$("#zworker").val('.$user[0].').change();\'>&nbsp;&nbsp;'.$name.'</a></font></b></td><td align="center"><b>'.$user[2].'</b></td>';

$zarplata=$user[2];


for ($x=1; $x<=6; $x++){

$query = "SELECT * FROM `zpay` WHERE `worker`='".(int)$user[0]."' AND  DATE(`time`) BETWEEN '".$_GET['zyear'].'-'.$_GET['zmonth']."-01' AND '".$_GET['zyear'].'-'.$_GET['zmonth']."-31' AND `way`='".$x."'";
$result = mysql_query($query) or die(mysql_error());
$zsum=0;
if(mysql_num_rows($result)>0){
	
while($zbill= mysql_fetch_row($result)) {
	

$zdata=explode("-", $zbill[3]);
	
if($zbill[2]==1||$zbill[2]==2) $zarplata=(int)$zarplata+$zbill[4]/100; else $zarplata=(int)$zarplata-$zbill[4]/100;
	
	
$zsum=$zsum+$zbill[4]/100;
$zsums=$zbill[4]/100;
$zedit=$zbill[0];
$zworker=$zbill[1];
$zway=$zbill[2];
}

echo '<td align="center"><b><a href="#" style="color: #000;" onclick=\'$("#zcash").val("'.$zsums.'");$("#zway").val('.$zway.').change();$("#zmonth").val('.$zdata[1].').change();$("#zyear").val('.$zdata[0].').change();$("#zworker").val('.$zworker.').change();$("#zedit").val("'.$zedit.'");\'>'.$zsum.'</b></a></td>';


} else echo '<td align="center">-</td>';


}


echo '<td align="center"><b><font size="5">'.$zarplata.'</font></b></td></tr>';
$zarplata_all=(int)$zarplata_all+(int)$zarplata;
}
echo '<tr><td colspan="8"></td><td align="center"><b><font size="5">'.$zarplata_all.'</font></b></td></tr></table>';
	








?>