<?php
include "../config.php";
session_start();

echo '<script type="text/javascript">$(function(){
$("#count_day_info").load("/control/admin.php?mode=day&count_day=1",function(data) {
var arr = data.split(/[|]/);
$("#count_day_info").html("&nbsp;<b>"+arr[0]+"</b>&nbsp;");
$("#all_day_info").html("&nbsp;<b>"+arr[1]+"</b>&nbsp;");
});});</script>';

$q[]="";
$q[]="января"; 
$q[]="февраля"; 
$q[]="марта"; 
$q[]="апреля"; 
$q[]="мая";
$q[]="июня"; 
$q[]="июля"; 
$q[]="августа"; 
$q[]="сентября"; 
$q[]="октября"; 
$q[]="ноября";
$q[]="декабря";

$e[0]="Воскресенье"; 
$e[1]="Понедельник"; 
$e[2]="Вторник"; 
$e[3]="Среда"; 
$e[4]="Четверг";
$e[5]="Пятница"; 
$e[6]="Суббота";

$m=date('m');
if ($m=="01") $m=1; 
if ($m=="02") $m=2;
if ($m=="03") $m=3;
if ($m=="04") $m=4; 
if ($m=="05") $m=5;
if ($m=="06") $m=6;
if ($m=="07") $m=7;
if ($m=="08") $m=8; 
if ($m=="09") $m=9;

$we=date('w');

$chislo=date('d');

$den_nedeli = $e[$we];

$mesyac = $q[$m];


echo '<p align="center"><font size="5"><b>Сегодня:</b> <font size="6">'.$den_nedeli.' <b>'.$chislo.' '.$mesyac.'</b> '.date("Y").' г.</font></p>';
//<div style="margin-left:100px;"><div id="count_day_info" style="display:inline;"></div>рабочий день из <div id="all_day_info" style="display:inline;"></div>в этом месяце </font></div>
echo '</td></tr>';

if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4){echo '<fieldset style="width:95%"><legend>Сумма непроведенных платежей: </legend>';

$query_bill = "SELECT `company`,`c_bill`,`id`,`c_bank` FROM `bill` WHERE `delete`='0' ORDER BY `company` ASC";
$result_bill = mysql_query($query_bill) or die(mysql_error());
while($bill_info = mysql_fetch_row($result_bill)) {

$query_pays = "SELECT `cash` FROM `pays` WHERE `status`='0' AND `delete`='0' AND `pay_bill`='".mysql_escape_string($bill_info[2])."'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
$pay=0;
while($pay_info = mysql_fetch_row($result_pays)) {

$pay=(int)$pay+(int)$pay_info[0];


}
$query_c = "SELECT `name` FROM `company` WHERE `id`='".mysql_escape_string($bill_info[0])."'";
$result_c = mysql_query($query_c) or die(mysql_error());
$pay_c = mysql_fetch_row($result_c);

if($pay>0)$color='size="5" color="red"'; else $color='';

echo "<p><font size='5'><b>".$pay_c[0].":</b></font> ".$bill_info[1]." (<i>".$bill_info[3]."</i>) - <b><font ".$color.">".((int)$pay/100).' руб.</font></b></p>';
}

echo '</fieldset>'; }




if($_SESSION["group"]==3){ echo '<img src="data/img/checkout.png" style="float:left;margin:25px;"><b>Выполнение:</b><br> <fieldset><legend>Текущие данные по мотивации с начала месяца: </legend>
<b>Оклад:</b> '; 
$query_zarplata= "SELECT `zarplata`,`ndfl` FROM `workers` WHERE `id`='".mysql_escape_string($_SESSION["user_id"])."'";
$result_zarplata = mysql_query($query_zarplata) or die(mysql_error());
$zarplata = mysql_fetch_row($result_zarplata);

echo '<font size="5">'.$zarplata[0].'</font> руб. - '.$zarplata[1].' руб.(НДФЛ)<br>
<b>Начисление переменной части:</b> ';

$manager =$_SESSION["user_id"];
$group =$_SESSION["group"];
$total=0;
$total_tr=0;


$query = "SELECT * FROM `orders` WHERE  `manager`='".$manager."' AND `tr_manager`='".$manager."' AND DATE(`data`) BETWEEN '".date("Y-m-")."01' AND '".date("Y-m-").date("t")."'";
$result = mysql_query($query) or die(mysql_error());
$cash=0;
while($row = mysql_fetch_array($result)) {
$cl_cash_all=$row['cl_cash']-$row['cl_minus']+$row['cl_plus'];
$tr_cash_all=$row['tr_cash']+$row['tr_minus']-$row['tr_plus'];

if($row['cl_nds']==0&&$row['tr_nds']==0)$cash=$cl_cash_all-$tr_cash_all;
if($row['cl_nds']==1&&$row['tr_nds']==1)$cash=$cl_cash_all-$tr_cash_all;
if($row['cl_nds']==1&&$row['tr_nds']==0)$cash=$cl_cash_all-($tr_cash_all+$tr_cash_all*0.03);
if($row['cl_nds']==1&&$row['tr_nds']==2)$cash=$cl_cash_all-($tr_cash_all+$tr_cash_all*0.06);
if($row['cl_nds']==0&&$row['tr_nds']==1)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*0.03;
if($row['cl_nds']==0&&$row['tr_nds']==2)$cash=$cl_cash_all-($tr_cash_all+$tr_cash_all*0.03);
if($row['cl_nds']==2&&$row['tr_nds']==1)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*0.06;
if($row['cl_nds']==2&&$row['tr_nds']==0)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*0.03;
if($row['cl_nds']==2&&$row['tr_nds']==2)$cash=$cl_cash_all-$tr_cash_all;


$total=(int)$total+(int)$cash;
}


$query = "SELECT * FROM `orders` WHERE  `manager`='".$manager."' AND `tr_manager`!='".$manager."' AND DATE(`data`) BETWEEN '".date("Y-m-")."01' AND '".date("Y-m-").date("t")."'";
$result = mysql_query($query) or die(mysql_error());
$cash=0;
while($row = mysql_fetch_array($result)) {
$cl_cash_all=$row['cl_cash']-$row['cl_minus']+$row['cl_plus'];
$tr_cash_all=$row['tr_cash']+$row['tr_minus']-$row['tr_plus'];

if($row['cl_nds']==0&&$row['tr_nds']==0)$cash=$cl_cash_all-$tr_cash_all;
if($row['cl_nds']==1&&$row['tr_nds']==1)$cash=$cl_cash_all-$tr_cash_all;
if($row['cl_nds']==1&&$row['tr_nds']==0)$cash=$cl_cash_all-($tr_cash_all+$tr_cash_all*0.03);
if($row['cl_nds']==1&&$row['tr_nds']==2)$cash=$cl_cash_all-($tr_cash_all+$tr_cash_all*0.06);
if($row['cl_nds']==0&&$row['tr_nds']==1)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*0.03;
if($row['cl_nds']==0&&$row['tr_nds']==2)$cash=$cl_cash_all-($tr_cash_all+$tr_cash_all*0.03);
if($row['cl_nds']==2&&$row['tr_nds']==1)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*0.06;
if($row['cl_nds']==2&&$row['tr_nds']==0)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*0.03;
if($row['cl_nds']==2&&$row['tr_nds']==2)$cash=$cl_cash_all-$tr_cash_all;


$total_tr=(int)$total_tr+(int)$cash;
}

$query = "SELECT * FROM `orders` WHERE  `manager`!='".$manager."' AND `tr_manager`='".$manager."' AND DATE(`data`) BETWEEN '".date("Y-m-")."01' AND '".date("Y-m-").date("t")."'";
$result = mysql_query($query) or die(mysql_error());
$cash=0;
while($row = mysql_fetch_array($result)) {
$cl_cash_all=$row['cl_cash']-$row['cl_minus']+$row['cl_plus'];
$tr_cash_all=$row['tr_cash']+$row['tr_minus']-$row['tr_plus'];

if($row['cl_nds']==0&&$row['tr_nds']==0)$cash=$cl_cash_all-$tr_cash_all;
if($row['cl_nds']==1&&$row['tr_nds']==1)$cash=$cl_cash_all-$tr_cash_all;
if($row['cl_nds']==1&&$row['tr_nds']==0)$cash=$cl_cash_all-($tr_cash_all+$tr_cash_all*0.03);
if($row['cl_nds']==1&&$row['tr_nds']==2)$cash=$cl_cash_all-($tr_cash_all+$tr_cash_all*0.06);
if($row['cl_nds']==0&&$row['tr_nds']==1)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*0.03;
if($row['cl_nds']==0&&$row['tr_nds']==2)$cash=$cl_cash_all-($tr_cash_all+$tr_cash_all*0.03);
if($row['cl_nds']==2&&$row['tr_nds']==1)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*0.06;
if($row['cl_nds']==2&&$row['tr_nds']==0)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*0.03;
if($row['cl_nds']==2&&$row['tr_nds']==2)$cash=$cl_cash_all-$tr_cash_all;


$total_tr_dop=(int)$total_tr_dop+(int)$cash;
}


if(((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2))<100000) $zarplata_plus=((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2))*0.1;
if(((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2))>100000) $zarplata_plus=((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2))*0.15;
	
echo '<font size="5">'.$zarplata_plus.'</font> руб.<br>
<font size="4"><b>ИТОГО: <font size="5">'.($zarplata[0]-$zarplata[1]+$zarplata_plus).'</font> руб.</b></font>
</fieldset><br><fieldset><legend>Дебиторская задолженость: </legend>
<b>Общая:</b> руб.<br>
<b>Просроченная:</b> руб.<br>
<b>Текущая:</b> руб.<br>
<font size="4"><b>Дебет/Кредит:</b> </font>
</fieldset>
';
 }
 
 

?>