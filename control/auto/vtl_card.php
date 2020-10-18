<?php 
include "../../config.php";

if ($_GET['mode']=='card') {
$card_id=addslashes($_GET['card_id']);
echo '<table cellpadding="5"  style="width:99%;overflow:auto;border-collapse: collapse;border-style: solid;border-color: black;" border="1" width="100%"><tr><td bgcolor="#edf1f3" align="center"><b>Дата</b></td><td bgcolor="#edf1f3" align="center"><b>Вид операции</b></td><td bgcolor="#edf1f3" align="center"><b>Кол-во (литров)</b></td><td bgcolor="#edf1f3" align="center"><b>Сумма</b></td><td bgcolor="#edf1f3" align="center"><b>Остаток по карте</b></td></tr>';


$query_card_p = "SELECT `card_cash` FROM `vtl_oil_card` WHERE `id`='".mysql_escape_string($card_id)."'";
$result_card_p = mysql_query($query_card_p) or die(mysql_error());
$card_cash = mysql_fetch_row($result_card_p);

if ($_GET['start']!=''&&$_GET['end']!=''){
$start_elements  = explode("/",$_GET['start']);
$date_start=date("Y-m-d",strtotime($start_elements[2]."-".$start_elements[1]."-".$start_elements[0]));
$end_elements  = explode("/",$_GET['end']);
$date_end=date("Y-m-d",strtotime($end_elements[2]."-".$end_elements[1]."-".$end_elements[0]));

$query_card = "SELECT `id`,`date`,`trip`,`way`,`cash`,`l`,`card_cash` FROM `drivers_report` WHERE `card_oil`='".mysql_escape_string($card_id)."' AND `delete`='0' AND DATE(`date`) BETWEEN '".$date_start."' AND '".$date_end."' ORDER BY `date` DESC";
} else {
$query_card = "SELECT `id`,`date`,`trip`,`way`,`cash`,`l`,`card_cash` FROM `drivers_report` WHERE `card_oil`='".mysql_escape_string($card_id)."' AND `delete`='0' ORDER BY `date` DESC LIMIT 15";

}


$result_card = mysql_query($query_card) or die(mysql_error());
if (mysql_num_rows($result_card)>0){

while($card = mysql_fetch_row($result_card)) {
switch ($card[3]) {
case '10': $way='Покупка ДТ';$edit='';break;
case '100': $way='Кредитование';$edit='<a href="#" id="card_p" onclick=\'$("#fa_vtl_card_refill").load("add_vtl_card_refill.html?edit=1&card_p_id='.$card[0].'&card_id='.$card_id.'");$("#fa_vtl_card_refill").dialog({ title: "Редактировать платеж" },{width: 320,height: 230,modal: true,resizable: false});\'style="text-decoration:none;"><img src="data/img/document-edit.png" style="width:25px;"></a>&nbsp;&nbsp;<a href="#" id="card_p" onclick=\'$("#result").html("<b>Удалить платёж?");
$("#result").dialog({ title: "Внимание" },{ modal: true },{ width: 350 },{ resizable: false },{ buttons: [{text: "Да",click: function() {$.post("control/auto/vtl_card.php?mode=del_p&del_card_p='.$card[0].'&card_id='.$card_id.'", function(data) {$("#card_load_info").load("/control/auto/vtl_card.php?mode=card&card_id='.$card_id.'");});$(this).dialog("close");}},{text: "Нет",click: function() {$(this).dialog("close");}}] });\' style="text-decoration:none;"><img src="data/img/delete.png" style="width:25px;"></a>'; break;
}
$trip="";$oil="";$cash_card="";
if($card[2]!=0)$trip=' (Рейс: №'.$card[2].')';
if($card[5]!=0)$oil=($card[5]/100); else $oil='-';
if($card[6]!=0)$cash_card=($card[6]/100).' руб.'; else $cash_card='-';

echo '<tr><td align="center"><b>'.date('d/m/Y',strtotime($card[1])).'</b></td><td align="center">'.$way.$trip.' </td><td align="center">'.$oil.'</td><td align="center"><b>'.($card[4]/100).' руб.</b></td><td align="center">'.$cash_card.'</td></tr>';

}

} else {echo '<tr><td align="center" colspan="5"><font color="red"><b>Отсутствуют операции по карте...</b></font></td></tr>';
//echo '<script type="text/javascript">alert("Отсутствуют операции по карте...");</script>';
}

echo '</table>';
echo '<script type="text/javascript">$("#card_balance").text("'.($card_cash[0]/100).' руб.");</script>';



}


if ($_GET['mode']=='delete') {
$card_id=(int)$_GET['card_id'];
$query = "UPDATE `vtl_oil_card` SET `delete`='1' WHERE `id`='".mysql_escape_string($card_id)."'";
$result = mysql_query($query) or die(mysql_error());

echo '<font color="red" size="3">Топливная карта удалена!</font>';


}

if ($_GET['mode']=='del_p') {
$card_p_id=(int)$_GET['del_card_p'];
$card_id=(int)$_GET['card_id'];
$query = "UPDATE `drivers_report` SET `delete`='1' WHERE `id`='".mysql_escape_string($card_p_id)."'";
$result = mysql_query($query) or die(mysql_error());

$query_card = "SELECT `cash` FROM `drivers_report` WHERE `id`='".mysql_escape_string($card_p_id)."'";
$result_card = mysql_query($query_card) or die(mysql_error());
$card = mysql_fetch_row($result_card);

$query_card_p = "UPDATE `vtl_oil_card` SET `card_cash`=(`card_cash`-".$card[0].") WHERE `id`='".mysql_escape_string($card_id)."'";
$result_card_p = mysql_query($query_card_p) or die(mysql_error());



echo '<font color="red" size="3">Платёж удален!</font>';


}
?>