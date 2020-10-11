<?php
set_time_limit(0);
session_start();include "../config.php";
$manager =$_SESSION["user_id"];

if (@$_GET['mode']=="show_cl"){
$fff=0;



if($_SESSION["group"]==2||$_SESSION["group"]==1) $query = "SELECT `id`,`client`,`cl_nds`,`cl_cash`,`cl_tfpay`,`cl_event`,`date_in1`,`date_out2`,`date_in2`,`date_out1` FROM `orders` WHERE `data`>= DATE_SUB(CURRENT_DATE, INTERVAL 180 DAY)"; else $query = "SELECT `id`,`client`,`cl_nds`,`cl_cash`,`cl_tfpay`,`cl_event`,`date_in1`,`date_out2`,`date_in2`,`date_out1` FROM `orders` WHERE `manager`='".mysql_escape_string($manager)."' AND `delete`='0' AND `data`>= DATE_SUB(CURRENT_DATE, INTERVAL 180 DAY)";

$result = mysql_query($query) or die(mysql_error());

while($orders= mysql_fetch_row($result)) {

$cl_pay=0;

$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($orders[0])."' AND `delete`='0' AND `appoint`='1' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$cl_pay=(int)$pay[0]+(int)$cl_pay;
}
if((int)$cl_pay!=(int)$orders[3]*100){
$cl_event="";


switch ($orders[5]) {
case '1': if($orders[8]!="0000-00-00"&&$orders[8]!="1970-01-01"&&$orders[8]!="")$cl_event=$orders[8]; else $cl_event=$orders[6];break;
case '2': if($orders[7]!="0000-00-00"&&$orders[7]!="1970-01-01"&&$orders[7]!="")$cl_event=$orders[7]; else $cl_event=$orders[9];break;
case '3': $query_docs = "SELECT `date_cl_receve` FROM `docs` WHERE `order`='".mysql_escape_string($orders[0])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());$cl_events=mysql_fetch_row($result_docs);if($cl_events[0]!="0000-00-00"&&$cl_events[0]!="1970-01-01"&&$cl_events[0]!=""){
$cl_event=$cl_events[0];};break;
case '4': $query_docs = "SELECT `date_cl_receve` FROM `docs` WHERE `order`='".mysql_escape_string($orders[0])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());$cl_events=mysql_fetch_row($result_docs);if($cl_events[0]!="0000-00-00"&&$cl_events[0]!="1970-01-01"&&$cl_events[0]!=""){
$cl_event=$cl_events[0];};break;
}


if($cl_event!="0000-00-00"&&$cl_event!="1970-01-01"&&$cl_event!=""){

$cl_event_date=date('d/m/Y', strtotime('+'.(int)$orders[4].' day', strtotime($cl_event)));

$elements  = explode("/",$cl_event_date);
$current_date = mktime (0,0,0,date("m") ,date("d"),date("Y")); //дата сегодня
$old_date = mktime (0,0,0,$elements[1],$elements[0],$elements[2]); 
$difference = ($current_date - $old_date);
$difference_in_days = floor($difference / 86400); //разница в днях

if($difference_in_days>=0){
$fff++;
switch ($orders[2]) {
case '0': $nds_cl='без НДС';break;
case '1': $nds_cl='с НДС';break;
case '2': $nds_cl='НАЛ';break;
}

$query_cl = "SELECT `name` FROM `clients` WHERE `id`='".mysql_escape_string($orders[1])."'";
$result_cl = mysql_query($query_cl) or die(mysql_error());
$cl = mysql_fetch_row($result_cl);


$cash_el= explode(",",number_format(((int)$orders[3]*100-(int)$cl_pay)/100, 2, ',', ''));
$cash_all=$cash_el[0]." руб. ".$cash_el[1]." коп.";


echo '<img src="data/img/exclamation.png" style="float:left;margin:5px;margin-left:10px;"><div style="padding: 10px;background: #ddd;border: 1px solid #bbb;width: 96%;">&nbsp;&nbsp;'.$fff.') Заявка <font size=4><b><a href="#" onclick="window.location.href=\'orders.php?order_id='.$orders[0].'\'">№'.$orders[0].'</a></b></font> имеет просрочку по оплате <b><font size=4>'.$cash_all.'</font></b> в <b><font size=4 color=red>'.$difference_in_days.' дн.</font></b> (Клиент: <b>«'.$cl[0].'»</b>) ['.$nds_cl.']</div>';
}

}
}


}


}


if (@$_GET['mode']=="show_tr"){
$fff=0;

$query = "SELECT `id`,`transp`,`tr_nds`,`tr_cash`,`tr_tfpay`,`tr_event`,`date_in1`,`date_out2`,`date_in2`,`date_out1`,`tr_plus`,`tr_minus` FROM `orders` WHERE `tr_manager`='".mysql_escape_string($manager)."' AND `delete`='0' AND `transp`!='2' ORDER BY `id` DESC";
$result = mysql_query($query) or die(mysql_error());

while($orders= mysql_fetch_row($result)) {
if($orders[10]==0&&$orders[11]==0) $tr_dop_cash=''; else {
if($orders[10]!=0) $tr_plus='+'.$orders[10]; else $tr_plus='';
if($orders[11]!=0) $tr_minus='-'.$orders[11]; else $tr_minus='';
if($orders[10]!=0&&$orders[11]!=0) $ttt=',';
$tr_dop_cash=' ('.$tr_plus.$ttt.$tr_minus.') - <b><font size="4" color="red">'.($orders[3]+$orders[10]-$orders[11]).'</font></b> ';}

$tr_pay=0;

$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($orders[0])."' AND `delete`='0' AND `appoint`='2' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$tr_pay=(int)$pay[0]+(int)$tr_pay;
}
if((int)$tr_pay!=(int)$orders[3]*100){
$tr_event="";

switch ($orders[5]) {
case '1': if($orders[8]!="0000-00-00"&&$orders[8]!="1970-01-01"&&$orders[8]!="")$tr_event=$orders[8]; else $tr_event=$orders[6];break;
case '2': if($orders[7]!="0000-00-00"&&$orders[7]!="1970-01-01"&&$orders[7]!="")$tr_event=$orders[7]; else $tr_event=$orders[9];break;

case '3': $query_docs = "SELECT `date_tr_receve` FROM `docs` WHERE `order`='".mysql_escape_string($orders[0])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());
$tr_events=mysql_fetch_row($result_docs);
if($tr_events[0]!="0000-00-00"&&$tr_events[0]!="1970-01-01"&&$tr_events[0]!=""){
$tr_event=$tr_events[0];  };break;

case '4': $query_docs = "SELECT `date_tr_receve` FROM `docs` WHERE `order`='".mysql_escape_string($orders[0])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());
$tr_events=mysql_fetch_row($result_docs);
if($tr_events[0]!="0000-00-00"&&$tr_events[0]!="1970-01-01"&&$tr_events[0]!=""){
$tr_event=$tr_events[0];};break;

}



if($tr_event!="0000-00-00"&&$tr_event!="1970-01-01"&&$tr_event!=""){



$elements  = explode("-",$tr_event);
$current_date = mktime(0,0,0,date("m") ,date("d"),date("Y")); //дата сегодня
$old_date = mktime(0,0,0,$elements[1],$elements[2]+(int)$orders[4],$elements[0]); 

$difference_in_days =floor(($current_date - $old_date)/86400); //разница в днях

if($difference_in_days>=0){
$fff++;
switch ($orders[2]) {
case '0': $nds_tr='без НДС';break;
case '1': $nds_tr='с НДС';break;
case '2': $nds_tr='НАЛ';break;
}

$query_tr = "SELECT `name` FROM `transporters` WHERE `id`='".mysql_escape_string($orders[1])."'";
$result_tr = mysql_query($query_tr) or die(mysql_error());
$tr = mysql_fetch_row($result_tr);


$cash_el= explode(",",number_format(((int)$orders[3]*100-(int)$tr_pay)/100, 2, ',', ''));
$cash_all=$cash_el[0]." руб. ".$cash_el[1]." коп.";






echo '<img src="data/img/exclamation.png" style="float:left;margin:5px;margin-left:10px;"><div style="padding: 10px;background: #ddd;border: 1px solid #bbb;width: 96%;">&nbsp;&nbsp;'.$fff.') Заявка <font size=4><b><a href="#" onclick="window.location.href=\'orders.php?order_id='.$orders[0].'\'">№'.$orders[0].'</a></b></font> имеет просрочку по оплате <b><font size=4>'.$cash_all.'</font></b> '.$tr_dop_cash.' в <b><font size=4>'.$difference_in_days.' дн.</font></b> (Перевозчик: <b>«'.$tr[0].'»</b>) ['.$nds_tr.']</div>';

}

}
}


}
}


if (@$_GET['mode']=="show"){

$query_docs = "SELECT `order`,`id`,`date_tr_receve` FROM `docs` WHERE `date_cl_receve`='' OR `date_cl_receve`='0000-00-00' OR `date_cl_receve`='1970-01-01'";
$result_docs = mysql_query($query_docs) or die(mysql_error());
$fff=1;
while($docs= mysql_fetch_row($result_docs)) {

$query = "SELECT `id`,`client`,`cl_nds`,`cl_cash`,`cl_tfpay`,`cl_event`,`date_in1`,`date_out2`,`date_in2` FROM `orders` WHERE `manager`='".mysql_escape_string($manager)."' AND `id`='".mysql_escape_string($docs[0])."' AND `delete`='0'";
$result = mysql_query($query) or die(mysql_error());
$orders= mysql_fetch_row($result);


$cl_pay=0;
$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($docs[0])."' AND `delete`='0' AND `appoint`='1' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$cl_pay=(int)$pay[0]+(int)$cl_pay;
}

//if((int)$orders[3]*100<=(int)$cl_pay){
//$add_date_cl_all=date('Y-m-d');
//$query = "UPDATE `docs` SET `date_cl_receve`='$add_date_cl_all' WHERE `id`='".mysql_escape_string($docs[1])."'";
//$result = mysql_query($query) or die(mysql_error());
//}


if((int)$orders[3]*100>(int)$cl_pay&&$orders[2]!="2"){
echo '<img src="data/img/exclamation.png" style="float:left;margin:5px;margin-left:10px;"><div style="padding: 10px;color:#000;background: #737C82;border: 2px solid #bbb;width: 96%;">&nbsp;&nbsp;'.$fff.') Заявка <font size=4><b><a href="#" style="color:#000;" onclick="window.location.href=\'orders.php?order_id='.$docs[0].'\'">№'.$docs[0].'</a></b></font> не имеет оснований для оплаты Клиентом <div style="float:right"><a href="#" style="color:#000;" onclick="window.location.href=\'docs.php?doc_id='.$docs[1].'\'">[исправить]</a></div></div>';
$fff++;
}
if((int)$orders[3]*100>(int)$cl_pay){
switch ($orders[5]) {
case '1': $cl_event='Загрузка';break;
case '2': $cl_event='Выгрузка';break;
case '3': $cl_event='Поступление факсимильных документов';break;
case '4': $cl_event='Поступление оригинальных документов';break;}

if(strtotime('+30 day', strtotime($orders[6]))<strtotime('now')) {

if($orders[5]==1&&strtotime('+3 day', strtotime($orders[6]))<=strtotime('now')) {echo '<div style="padding: 10px;background: #ddd;border: 1px solid #bbb;width: 96%;">&nbsp;&nbsp;По заявке <font size=4><b><a href="#" onclick="window.location.href=\'orders.php?order_id='.$docs[0].'\'">№'.$docs[0].'</a></b></font> нет документов, подтверждающих событие (<b>'.$cl_event.'</b>), осуществлен ли данный рейс? <div style="float:right"><a href="#" onclick="window.location.href=\'docs.php?doc_id='.$docs[1].'\'">[Да]</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick=\'$("#result").html("Удалить выбранную заявку?");$("#result").dialog({ title: "Внимание" },{ modal: true },{ resizable: false },{ buttons: [{text: "Да",click: function() {$.post("control/orders.php?mode=delete&id='.$docs[0].'", function(data) {$("#result").dialog("close");$("#result_temp").html(data);$("#result_temp").dialog({ title: "Внимание" },{ height: 90 },{ width: 400 },{ modal: true },{ resizable: false });});}},{text: "Нет",click: function() {$(this).dialog("close");}}] });\'>[Нет]</a></div></div>';
}

if($orders[5]==2&&strtotime('+10 day', strtotime($orders[6]))<=strtotime('now')&&($orders[7]=="1970-01-01"||$orders[7]=="0000-00-00"||$orders[7]=="")) {echo '<div style="padding: 10px;background: #ddd;border: 1px solid #bbb;width: 96%;">&nbsp;&nbsp;По заявке <font size=4><b><a href="#" onclick="window.location.href=\'orders.php?order_id='.$docs[0].'\'">№'.$docs[0].'</a></b></font> нет документов, подтверждающих событие (<b>'.$cl_event.'</b>), осуществлен ли данный рейс? <div style="float:right"><a href="#" onclick="window.location.href=\'docs.php?doc_id='.$docs[1].'\'">[Да]</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick=\'$("#result").html("Удалить выбранную заявку?");$("#result").dialog({ title: "Внимание" },{ modal: true },{ resizable: false },{ buttons: [{text: "Да",click: function() {$.post("control/orders.php?mode=delete&id='.$docs[0].'", function(data) {$("#result").dialog("close");$("#result_temp").html(data);$("#result_temp").dialog({ title: "Внимание" },{ height: 90 },{ width: 400 },{ modal: true },{ resizable: false });});}},{text: "Нет",click: function() {$(this).dialog("close");}}] });\'>[Нет]</a></div></div>';
}

if($orders[5]==3&&strtotime('+15 day', strtotime($orders[6]))<=strtotime('now')) {echo '<div style="padding: 10px;background: #ddd;border: 1px solid #bbb;width: 96%;">&nbsp;&nbsp;По заявке <font size=4><b><a href="#" onclick="window.location.href=\'orders.php?order_id='.$docs[0].'\'">№'.$docs[0].'</a></b></font> нет документов, подтверждающих событие (<b>'.$cl_event.'</b>), осуществлен ли данный рейс? <div style="float:right"><a href="#" onclick="window.location.href=\'docs.php?doc_id='.$docs[1].'\'">[Да]</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick=\'$("#result").html("Удалить выбранную заявку?");$("#result").dialog({ title: "Внимание" },{ modal: true },{ resizable: false },{ buttons: [{text: "Да",click: function() {$.post("control/orders.php?mode=delete&id='.$docs[0].'", function(data) {$("#result").dialog("close");$("#result_temp").html(data);$("#result_temp").dialog({ title: "Внимание" },{ height: 90 },{ width: 400 },{ modal: true },{ resizable: false });});}},{text: "Нет",click: function() {$(this).dialog("close");}}] });\'>[Нет]</a></div></div>';
echo '<hr>';}

if($orders[5]==4&&strtotime('+30 day', strtotime($orders[6]))<=strtotime('now')) {echo '<div style="padding: 10px;background: #ddd;border: 1px solid #bbb;width: 96%;">&nbsp;&nbsp;По заявке <font size=4><b><a href="#" onclick="window.location.href=\'orders.php?order_id='.$docs[0].'\'">№'.$docs[0].'</a></b></font> нет документов, подтверждающих событие (<b>'.$cl_event.'</b>), осуществлен ли данный рейс? <div style="float:right"><a href="#" onclick="window.location.href=\'docs.php?doc_id='.$docs[1].'\'">[Да]</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick=\'$("#result").html("Удалить выбранную заявку?");$("#result").dialog({ title: "Внимание" },{ modal: true },{ resizable: false },{ buttons: [{text: "Да",click: function() {$.post("control/orders.php?mode=delete&id='.$docs[0].'", function(data) {$("#result").dialog("close");$("#result_temp").html(data);$("#result_temp").dialog({ title: "Внимание" },{ height: 90 },{ width: 400 },{ modal: true },{ resizable: false });});}},{text: "Нет",click: function() {$(this).dialog("close");}}] });\'>[Нет]</a></div></div>';
echo '<hr>';}

}
if(strtotime('+30 day', strtotime($orders[6]))<strtotime('now')) {echo '<div style="padding: 10px;background: #FF8D87;border: 1px solid #bbb;width: 96%;">&nbsp;&nbsp;<img src="data/img/warning.png" style="float:left;margin-top:-10px;margin-right:10px;">По заявке <font size=4><b><a href="#" onclick="window.location.href=\'orders.php?order_id='.$docs[0].'\'">№'.$docs[0].'</a></b></font> критическая задержка расчёта даты оплаты! Примите меры к оплате рейса </div>';
}



if($docs[2]!="1970-01-01"&&$docs[2]!="0000-00-00"&&$docs[2]!=""){echo '<div style="padding: 10px;background: #FFCEAD;border: 1px solid #bbb;width: 96%;">&nbsp;&nbsp;<img src="data/img/info.png" style="float:left;margin-right:10px;margin-left:5px;">Примите меры к ускорению получения оплаты заявки <font size=4><b><a href="#" onclick="window.location.href=\'orders.php?order_id='.$docs[0].'\'">№'.$docs[0].'</a></b></font>, поскольку дата оплаты перевозчику определена, а дата оплаты клиентом нет, идёт растягивание платёжного периода</div>';}
echo '<hr>';}



}

echo '<script type="text/javascript">if('.($fff-1).'==0){document.getElementById("number").style.visibility = "hidden";} else {document.getElementById("number").style.visibility = "visible";} $("#count_doc").html("'.($fff-1).'");</script>';
}
?>