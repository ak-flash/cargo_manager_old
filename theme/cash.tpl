<?php 
    session_start();
    include "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Автоматизированная система транспортных перевозок</title>
<?php include_once("data/header.html");?>

<style>
.table_blur {
  background: #f5ffff;
  border-collapse: collapse;
  text-align: left;
}
.table_blur th {
  border-top: 1px solid #777777;	
  border-bottom: 1px solid #777777; 
  box-shadow: inset 0 1px 0 #999999, inset 0 -1px 0 #999999;
  background: linear-gradient(#9595b6, #5a567f);
  color: white;
  padding: 10px 15px;
  position: relative;
}
.table_blur th:after {
  content: "";
  display: block;
  position: absolute;
  left: 0;
  top: 25%;
  height: 25%;
  width: 100%;
  background: linear-gradient(rgba(255, 255, 255, 0), rgba(255,255,255,.08));
}
.table_blur tr:nth-child(odd) {
  background: #ebf3f9;
}
.table_blur th:first-child {
  border-left: 1px solid #777777;	
  border-bottom:  1px solid #777777;
  box-shadow: inset 1px 1px 0 #999999, inset 0 -1px 0 #999999;
}
.table_blur th:last-child {
  border-right: 1px solid #777777;
  border-bottom:  1px solid #777777;
  box-shadow: inset -1px 1px 0 #999999, inset 0 -1px 0 #999999;
}
.table_blur td {
  border: 1px solid #e3eef7;
  padding: 10px 15px;
  position: relative;
  transition: all 0.5s ease;
}
.table_blur tbody:hover td {
  color: transparent;
  text-shadow: 0 0 3px #a09f9d;
}
.table_blur tbody:hover tr:hover td {
  color: #444444;
  text-shadow: none;
}
</style>

<script type="text/javascript">
$(function(){
$("#cash_menu").css({"background": "#BCBCBC", "color":"#000","text-shadow": "1px 2px 2px #FFF"});
$('#c_bill_show').load('control/c_bill.php?mode=all');
$('#btnRefresh_bill').button();
$('#listpays').load('control/auto/vtl_cash.php?mode=cash');
$('#btnadd_bill').button(); 
$("#cash_tabs").tabs({fx: {opacity:'toggle', duration:1}}); 
$('#btnData_print').button(); 
$("#date_start").datepicker({
   onSelect: function(dateText, inst) {$("#date_end").val(dateText);}

});
$("#date_end").datepicker();

$('#date_start').mask('99/99/9999');
$('#date_end').mask('99/99/9999');



$("#btnData").click(function(){
if(document.getElementById('date_start').value<=document.getElementById('date_end').value){
$('#listpays').load('control/auto/vtl_cash.php?mode=cash&date_start='+document.getElementById('date_start').value+'&date_end='+document.getElementById('date_end').value);
} else {alert('Дата окончания периода должна быть позже даты начала!');}
}); 

$("#btnData_now").click(function(){
$('#listpays').load('control/auto/vtl_cash.php?mode=cash&date_start=<?php echo "01/".date("m/Y"); ?>&date_end=<?php echo "31/".date("m/Y"); ?>');
}); 

$("#btnData_last").click(function(){
$('#listpays').load('control/auto/vtl_cash.php?mode=cash&date_start=<?php echo "01/".date("m/Y",strtotime("-30 day")); ?>&date_end=<?php echo "31/".date("m/Y",strtotime("-30 day")); ?>');
}); 

});                
</script>

</head>
<body style="">	
<?php include "data/menu.html";?>

<div id="cash_tabs" style="width: 97%;margin: 15px;POSITION: absolute;Z-INDEX: 1;">
<ul style="font-size: 18px;height: 28px;">
<li><a href="#cash_tabs-1">Баланс</a></li>
		<li><a href="#cash_tabs-2">Отчет</a></li>
</ul>


<div id="cash_tabs-1">
<div style="height:43em;">
<fieldset style="padding:20px;"><legend>Состояние счетов</legend>
<input type="button" id="btnRefresh_bill" onclick="$('#save').fadeIn(5000);$('#c_bill_show').load('control/c_bill.php?mode=all');var l = new Date();$('#time').html(l.toLocaleString());$('#save').html('<b><font size=4>Состояние счетов обновлено!</font></b></div>');$('#save').fadeOut(10000);" value="Обновить" style="width:150px;">
<input type="button5" id="btnadd_bill" onclick='$("#fa_add_bill").load("theme/forms/add_bill.php?mode=new&company=2");$("#fa_add_bill").dialog({ title: "Добавить новый счет" },{width: 550,height: 550,modal: true,resizable: false});' value="Добавить счет" style="width: 130px;float:right;" >


<br><br>

<div id="c_bill_show"></div>

<div style='padding: 10px;background: #ddd;border: 1px solid #bbb;width: 97%;text-align:left;'><b><font size=4>Актуальность:</font></b><div id="time" ><script type="text/javascript">
var l = new Date();
document.write (l.toLocaleString());
</script></div> <div id="save"></div></div>



<div id="fa_add_bill"></div>
<div id="fa_c_bill" style="background:#F8F8F8;"></div>
<div id="result"></div><div id="result_temp"></div>

</fieldset>


</div></div>

<div id="cash_tabs-2">
<div style="height:110%;">
<fieldset style="width:95%;"><a class="button" id="btnData_now" href="#" style="height:22px;width:130px;">Текущий месяц</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a class="button" id="btnData_last" href="#" style="height:22px;width:140px;">Прошлый месяц</a>&nbsp;&nbsp;|&nbsp;&nbsp; За период: с <input type="text" id="date_start" name="date_start" style="width:80px;" value="" class="input"/> по <input type="text" id="date_end" name="date_end" style="width:80px;" class="input"/>&nbsp;<a class="button" id="btnData" href="#" style="height:22px;width:100px;">показать</a>


<a class="button5" id="btnData_print" href="/control/auto/vtl_cash.php?mode=cash" target="_blank" style="float:right;width:100px;">Печать</a>

</fieldset>  

<div id="listpays" style="height: 38em; overflow: auto;">
<br><br><br><br><br><br><br><br><br><br><br><p align="center"><b><font size="5">Идёт загрузка...Ожидайте...</font></b><br><br><img src="/data/progressbar.gif"></p>
</div>




</div></div>

</div>
</body>
</html>