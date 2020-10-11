<style>
table {
border-spacing: 0 10px;
font-family: 'Open Sans', sans-serif;
font-weight: bold;
}
th {
padding: 10px 20px;
background: green;
color: #FFF;
border-right: 2px solid; 
font-size: 0.9em;
}
th:first-child {
text-align: left;
}
th:last-child {
border-right: none;
}
td {
vertical-align: middle;
padding: 10px;
font-size: 14px;
text-align: center;
border-top: 2px solid #56433D;
border-bottom: 2px solid #56433D;
border-right: 2px solid #56433D;
}
td:first-child {
border-left: 2px solid #56433D;
border-right: none;
}
td:nth-child(2){
text-align: left;
}
</style>

<?php
// Подключение и выбор БД
include "../../config.php";	

if ($_GET['mode']=='cash') 
{




if ($_GET['date_start']!=''){
$start_elements  = explode("/",$_GET['date_start']);
if ($start_elements[0]=='01'){$month=1;}
$date_start=$start_elements[2]."-".$start_elements[1]."-".$start_elements[0];
$end_elements  = explode("/",$_GET['date_end']);
$date_end=$end_elements[2]."-".$end_elements[1]."-".$end_elements[0];
}

echo '<table cellpadding="5" style="width:100%;overflow:auto;"><tr><th align=center>№ </th><th width="350" align=center>Назначение (Основные)</th><th align=center>Поступления (+) руб.</th><th align=center>Выплаты (-) руб.</th></tr>';

// Запрос выборки данных
$query = "SELECT * FROM `pays_appoints`";


$result = mysql_query($query) or die(mysql_error());

$cash_plus_all=0;
$cash_minus_all=0;

while($row = mysql_fetch_array($result)) {

	if($row['id']==7){echo '<tr><th align=right colspan=2><b><font size=4>Итого:</b></font></th><th colspan=2 align=center><b><font size=5>'.number_format(($cash_plus_all-$cash_minus_all)/100, 2, '.', ' ').'</b></font> руб.</th></tr></table><br><table cellpadding="5" style="width:100%;"><tr><td bgcolor="#edf1f3" align=center>№ </td><td bgcolor="#edf1f3" width="300">Назначение (Дополнительные)</td><td bgcolor="#edf1f3" align=center>Поступления (+) руб.</td><td bgcolor="#edf1f3" align=center>Выплаты (-) руб.</td></tr>';
	$cash_plus_all_n=$cash_plus_all;
	$cash_minus_all_n=$cash_minus_all;
	$cash_plus_all=0;
	$cash_minus_all=0;}
	
$cash_plus=0;
$cash_minus=0;

$nds0_p=0;
$nds1_p=0;
$nds2_p=0;



if ($_GET['date_start']!='')$query_pays = "SELECT `way`,`category`,`cash`,`nds` FROM `pays` WHERE `delete`='0' AND `status`='1' AND `appoint`='".mysql_escape_string($row['id'])."' AND DATE(`date`) BETWEEN '".$date_start."' AND '".$date_end."' AND `add_name`!='0'";
else $query_pays = "SELECT `way`,`category`,`cash`,`nds` FROM `pays` WHERE `delete`='0' AND `status`='1' AND `appoint`='".mysql_escape_string($row['id'])."' AND `add_name`!='0'";
 

$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pays = mysql_fetch_row($result_pays)) {
switch ($pays[3]) {
case '0': $nds0_p=$nds0_p+(int)$pays[2];break;
case '1': $nds1_p=$nds1_p+(int)$pays[2];break;
case '2': $nds2_p=$nds2_p+(int)$pays[2];break;
}

if($pays[0]==1){$cash_plus=$cash_plus+(int)$pays[2];$cash_plus_all=$cash_plus_all+(int)$pays[2];


}
if($pays[0]==2){$cash_minus=$cash_minus+(int)$pays[2];$cash_minus_all=$cash_minus_all+(int)$pays[2];


}
}
if($cash_plus!=0)$cash_p=number_format($cash_plus/100, 2, '.', ' '); else $cash_p='-';
if($cash_minus!=0)$cash_m=number_format($cash_minus/100, 2, '.', ' '); else $cash_m='-';

if($nds0_p!=0)$nds0_m=number_format($nds0_p/100, 2, '.', ' '); else $nds0_m='-';
if($nds1_p!=0)$nds1_m=number_format($nds1_p/100, 2, '.', ' '); else $nds1_m='-';
if($nds2_p!=0)$nds2_m=number_format($nds2_p/100, 2, '.', ' '); else $nds2_m='-';
	
if($row['id']==1)
 echo '<tr><td align=center>'.$row['id'].'</td><td align=center><font size=5><b>«'.$row['app'].'»</b></font></td><td align=center><font size=5><b>'.$cash_p."</font></b><br><font size=2><table border=0 align=center><tr><td align=right>без НДС:</td><td><b>".$nds0_m."</b></td></tr><tr><td align=right>с НДС:</td><td><b>".$nds1_m."</b></td></tr><tr><td align=right>НАЛ:</td><td><b>".$nds2_m."</b></td></tr></table></font></td><td align=center><b><font size=5>".$cash_m."</b></font></td></tr>"; else 
if($row['id']==2)
 echo '<tr><td align=center>'.$row['id'].'</td><td align=center><font size=5><b>«'.$row['app'].'»</b></font></td><td align=center><font size=5><b>'.$cash_p."</font></b></td><td align=center><b><font size=5>".$cash_m."</b></font><br><font size=2><table border=0 align=center><tr><td align=right>без НДС:</td><td><b>".$nds0_m."</b></td></tr><tr><td align=right>с НДС:</td><td><b>".$nds1_m."</b></td></tr><tr><td align=right>НАЛ:</td><td><b>".$nds2_m."</b></td></tr></table></font></td></tr>";
  else      
echo '<tr><td align=center>'.$row['id'].'</td><td align=center><font size=4><b>«'.$row['app'].'»</b></font></td><td align=center><font size=5><b>'.$cash_p."</font></b></td><td align=center><b><font size=5>".$cash_m."</b></font></td></tr>"; 

}

echo '<tr><th align=right colspan=2><b><font size=4>Итого:</b></font></th><th colspan=2 align=center><b><font size=5>'.number_format(($cash_plus_all-$cash_minus_all)/100, 2, '.', ' ').'</b></font> руб.</th></tr><tr><td align=center colspan=4 bgcolor="#edf1f3"><b><font size=5>Общий баланс:</b></font>&nbsp;&nbsp;&nbsp;<font size=8>'.number_format(($cash_plus_all+$cash_plus_all_n-$cash_minus_all_n-$cash_minus_all)/100, 2, '.', ' ').'</font> руб.</td></tr></table>';

	
}





?>