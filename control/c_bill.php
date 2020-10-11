<?php  
include "../config.php";
session_start();

if (@$_GET['del_c_bill']!=""){
$del_c_bill=(int)$_GET['del_c_bill'];
$query_bill = "UPDATE `bill` SET `delete`='1' WHERE Id='".mysql_escape_string($del_c_bill)."'";
$result_bill = mysql_query($query_bill) or die(mysql_error());
echo 'Счет помечен на удаление!';}


if (@$_GET['company']!=""&&@$_GET['mode']=="show"){
$company=(int)$_GET['company'];	
	
$query_bill = "SELECT * FROM `bill` WHERE `company`='".mysql_escape_string($company)."' AND `delete`='0'";
$result_bill = mysql_query($query_bill) or die(mysql_error());

$f=0;


echo '<div style="height: 290px; overflow: auto;">';


if(mysql_num_rows($result_bill)!=0)
		{
			
echo '<table cellpadding="5"  style="width:100%;overflow:auto;border-collapse: collapse;border-style: solid;border-color: black;" border="1"><tr><td bgcolor="#edf1f3" align=center><b>№</b></td><td bgcolor="#edf1f3" align=center><b>№ Счета</b></td><td bgcolor="#edf1f3" align=center><b>БИК</b></td><td bgcolor="#edf1f3" align=center width=180><b>БАНК</b></td><td bgcolor="#edf1f3" align=center><b>Коррсч.</b></td><td bgcolor="#edf1f3" align=center><b>Баланс</b></td><td bgcolor="#edf1f3" align=center><b>Упр.</b></td></tr>';			

while($bill_info = mysql_fetch_row($result_bill)) {
	
$cash_el= explode(",",number_format($bill_info[6]/100, 2, ',', ' '));
$cash_all="<font size=\"4\">".$cash_el[0]." </font>руб.".$cash_el[1]." коп.";

if($bill_info[0]!=1) $del_button='<a href="#" id="del_bill" onclick=\'$("#result").html("<b>Удалить счет №<font color=red>«'.$bill_info[2].'»</font>?</b>");
$("#result").dialog({ title: "Внимание" },{ modal: true },{ width: 350 },{ resizable: false },{ buttons: [{text: "Да",click: function() {$.post("control/c_bill.php?del_c_bill='.$bill_info[0].'", function(data) {$("#car_info").load("/control/c_bill.php?company='.$company.'");});$(this).dialog("close");}},{text: "Нет",click: function() {$(this).dialog("close");}}] });\' style="text-decoration:none;"><img src="data/img/delete.png" style="width:35px;"></a>'; else $del_button='';

echo '<tr><td align=center bgcolor=#F2F5F7><font size=4><b>'.($f+1).'</b></font></td><td align=center><b>'.$bill_info[2].'</b></td><td bgcolor=#F9F9F9 align=center>'.$bill_info[3].'</td><td align=center>'.$bill_info[4].'</td><td  align=center>'.$bill_info[5].'</td><td  align=center>'.$cash_all.' </td><td align=center bgcolor=#F2F5F7><a href="#" id="edit_bill" onclick=\'$("#fa_add_bill").load("theme/forms/add_bill.php?mode=edit&c_bill='.$bill_info[0].'");$("#fa_add_bill").dialog({ title: "Редактировать счет №'.$bill_info[2].'" },{width: 730,height: 390,modal: true,resizable: false});\' style="text-decoration:none;"><img src="data/img/document-edit.png" style="width:35px;"></a>&nbsp;&nbsp;&nbsp;&nbsp;
'.$del_button.'</td></tr>';

 
$f++;}
echo '</table>';

} else {echo '<h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Счета отсутствуют...</h2>';}
echo '</div>';

}



if (@$_GET['mode']=="all"){

$query_bill = "SELECT * FROM `bill` WHERE `delete`='0' ORDER BY `id` ASC";
$result_bill = mysql_query($query_bill) or die(mysql_error());

$f=0;
echo '<div style="height: 450px; overflow: auto;">';


if(mysql_num_rows($result_bill)!=0)
		{



			
echo '<table cellpadding="5" class="table_blur" style="width:100%;overflow:auto;"><tr><th align=center><b>№</b></th><th align=center width=150><b>Организация</b></th><th align=center><b>№ Счета</b></th><th align=center width=300><b>БАНК</b></th><th align=center><b>Баланс</b></th>';
if($_SESSION["group"]==2||$_SESSION["group"]==1){echo '<th bgcolor="#C0C0C0" align=center><b>Упр.</b></th>';}
echo '</tr>';			

while($bill_info = mysql_fetch_row($result_bill)) {
	
if($bill_info[0]!=1) $del_button='<a href="#" id="del_bill" onclick=\'$("#result").html("<b>Удалить счет №<font color=red>«'.$bill_info[2].'»</font>?</b>");
$("#result").dialog({ title: "Внимание" },{ modal: true },{ width: 350 },{ resizable: false },{ buttons: [{text: "Да",click: function() {$.post("control/c_bill.php?del_c_bill='.$bill_info[0].'", function(data) {$("#car_info").load("/control/c_bill.php?company='.$company.'");});$(this).dialog("close");}},{text: "Нет",click: function() {$(this).dialog("close");}}] });\' style="text-decoration:none;"><img src="data/img/delete.png" style="width:35px;"></a>'; else $del_button='';

$query_с = "SELECT `name` FROM `company` WHERE `delete`='0' AND `id`='".mysql_escape_string($bill_info[1])."'";
$result_с = mysql_query($query_с) or die(mysql_error());
$с_info = mysql_fetch_row($result_с);

	
$cash_el= explode(",",number_format($bill_info[6]/100, 2, ',', ' '));
$cash_all="<font size=\"6\"><b>".$cash_el[0]."</b> </font>руб.".$cash_el[1]." коп.";

if($bill_info[0]==1) $bill_name = 'КАССА (Нал)'; else $bill_name = $с_info[0];

echo '<tr><td align=center><font size=3><b>'.($f+1).'</b></font></td><td align=center><font size=4><b>'.$bill_name.'</b></font></td><td align=center><b>'.$bill_info[2].'</b></td><td align=center><i>'.$bill_info[4].'</i></td><td  align=center>'.$cash_all.' </td>';

if($_SESSION["group"]==2||$_SESSION["group"]==1){echo '<td align=center><a href="#" id="edit_bill" onclick=\'$("#fa_add_bill").load("theme/forms/add_bill.php?mode=edit&c_bill='.$bill_info[0].'");$("#fa_add_bill").dialog({ title: "Редактировать счет №'.$bill_info[2].'" },{width: 550,height: 550,modal: true,resizable: false});\' style="text-decoration:none;"><img src="data/img/document-edit.png" style="width:35px;"></a>&nbsp;&nbsp;&nbsp;'.$del_button.'</td>';}

echo '</tr>';

 
$f++;}
echo '</table>';

} else {echo '<h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Счета отсутствуют...</h2>';}
echo '</div>';

}
?>	