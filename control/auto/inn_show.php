<?php 
session_start();

include "../../config.php";

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

$date_m='<b>'.date("d",strtotime($date)).' '.$m[date("m",strtotime($date))].'</b> '.date('Y', strtotime($date));
return $date_m;
}

if ($_GET['mode']=='show') {


echo '<table><tr><td><fieldset style="margin-top:5px;width:208px;padding-bottom:5px;"><form method="post" id="search_form"><div style="float:left;"><input name="tr_inn" id="tr_inn" placeholder="ИНН перевозчика" class="input" style="width:145px;margin-right:5px;" value="'.$_GET['tr_inn'].'"><br>найти</div><input type="button" class="search_btn" value="" onclick=\'$("#orders_show-33").load("/control/auto/inn_show.php?mode=show&tr_inn="+$("#tr_inn").val());\'></form></fieldset></td>';

if ($_GET['tr_inn']!='') {

$query_user = "SELECT `id`,`name` FROM `workers`";
$result_user = mysql_query($query_user) or die(mysql_error());
while($user = mysql_fetch_row($result_user)) {
$pieces = explode(" ", $user[1]);
$managers=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
$users[$user[0]]= $managers;
}	
	
$query_tr_inn = "SELECT `id`,`name`,`pref`,`nds` FROM `transporters` WHERE `tr_inn`= '".mysql_escape_string($_GET['tr_inn'])."'";
$result_tr_inn = mysql_query($query_tr_inn) or die(mysql_error());
echo '<td>';
while($tr_i = mysql_fetch_row($result_tr_inn)) {
$pref_tr="";

switch ($tr_i[2]) {
case '1': $pref_tr='ООО';break;
case '2': $pref_tr='ОАО';break;
case '3': $pref_tr='ИП';break;
case '4': $pref_tr='ЗАО';break;
case '6': $pref_tr='Физ.Л';break;}	

switch ($tr_i[3]) {
case '0': $nds_tr='без НДС';break;
case '1': $nds_tr='с НДС';break;
case '2': $nds_tr='НАЛ';break;
}

echo 'Перевозчик: <font size="5">'.$pref_tr.' <b>'.$tr_i[1].'</b></font>&nbsp;('.$nds_tr.')<br>';
$tr_inn=$tr_i[0];
}
echo '</td>';
echo '</tr></table>';




$query = "SELECT `id`,`data`,`tr_cash`,`tr_manager`,`tr_plus`,`tr_minus`,`tr_nds` FROM `orders` WHERE `transp`='".mysql_escape_string($tr_inn)."'";
$result = mysql_query($query) or die(mysql_error());
if(mysql_num_rows($result)>0){
echo '<table cellpadding="5"  style="width:99%;overflow:auto;border-collapse: collapse;border-style: solid;border-color: black;" border="1" width="100%"><tr style="color:#000000;"><td bgcolor="#edf1f3" align="center"><b>Дата</b></td><td bgcolor="#edf1f3" align="center"><b>№ Заявки</b></td><td bgcolor="#edf1f3" align="center"><b>Ставка</b></td><td bgcolor="#edf1f3" align="center"><b>Оплачено</b></td><td bgcolor="#edf1f3" align="center"><b>Менеджер</b></td></tr>';	
$tr_cash=0;
$tr_pays=0;	
while($row = mysql_fetch_array($result)) {

$tr_pay=0;
$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($row['id'])."' AND `delete`='0' AND `appoint`='2' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$tr_pay=(int)$pay[0]+(int)$tr_pay;
}

$tr_pay_plus=0;
$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($row['id'])."' AND `delete`='0' AND `appoint`='5' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$tr_pay_plus=(int)$pay[0]+(int)$tr_pay_plus;
}

$tr_pay_minus=0;
$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($row['id'])."' AND `delete`='0' AND `appoint`='6' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$tr_pay_minus=(int)$pay[0]+(int)$tr_pay_minus;
}
	
switch ($row['tr_nds']) {
case '0': $nds_tr='без НДС';break;
case '1': $nds_tr='с НДС';break;
case '2': $nds_tr='НАЛ';break;
}	
$color="";
if(($tr_pay/100)==$row['tr_cash'])$color='bgcolor="#B3F495" style="color:#2C4359;"'; else $color='bgcolor="#FFC1C1" style="color:#2C4359;"';
		
echo '<tr '.$color.'><td align="center">'.vk_date($row['data']).'</td><td align="center"><font size="4">'.$row['id'].'</font></td><td align="center">'.'<font size="4">'.$row['tr_cash'].'</font> руб&nbsp;(+'.$row['tr_minus'].'/-'.$row['tr_plus'].')&nbsp;<font size="1">'.$nds_tr.'</font></td><td align="center">'.'<b>'.number_format($tr_pay/100, 0, ',', '').'</b> руб.&nbsp;(+'.number_format($tr_pay_plus/100, 0, ',', '').'/-'.number_format($tr_pay_minus/100, 0, ',', '').')'.'</td><td align="center">'.$users[$row['tr_manager']].'</td></tr>';

$tr_cash=(int)$tr_cash+(int)$row['tr_cash'];
$tr_pays=(int)$tr_pays+(int)$tr_pay/100;
}
echo '<tr><td colspan="2" align="right"><font size="5">Итого:</font></td><td align="center"><b><font size="5">'.$tr_cash.'</font></b> руб.</td><td align="center"><b><font size="5">'.$tr_pays.'</font></b> руб.</td><td>&nbsp;</td></tr>';
} else echo '<b>С данным перевозчиком отсутствуют заявки!</b>';

echo '</table>';
}
}
?>