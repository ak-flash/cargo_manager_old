<?php 
    session_start();
    include "config.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Автопарк</title>
<?php include_once("data/header.html");?>

<script type="text/javascript">


$(function(){
// - - работа с датами - - >
$("#date_start").datepicker({
   onSelect: function(dateText, inst) {$("#date_end").val(dateText);}

});
$("#date_end").datepicker();

$("#date_start_card").datepicker({
   onSelect: function(dateText, inst) {$("#date_end_card").val(dateText);}

});
$("#date_end_card").datepicker();

$('#date_start').mask('99/99/9999');
$('#date_end').mask('99/99/9999');
$('#date_start_card').mask('99/99/9999');
$('#date_end_card').mask('99/99/9999');


$("#trip_fail").load('control/auto/trip_fail.php?mode=orders');
$("#card_number").load('control/auto/load_card.php?mode=card');


$('#btnAdd').button();$('#btnVTLcar').button();
$('#del_trip').button();$('#edit_trip').button();$('#add_tr').button();
$('#del_tr').button();$('#edit_tr').button();
$('#add_repair').button();$('#btnPetrolReport').button();
$('#del_repair').button();$('#edit_repair').button();
$('#add_dop').button();$('#btnTripReport').button();$('#btnTripNewReport').button();$('#btnAutoReport').button();
$('#del_dop').button();$('#edit_dop').button();
$('#add_cash').button();$('#btnLizReport').button();
$('#print_trip').button();

$('#add_card').button();$('#edit_card').button();$('#del_card').button();$('#cash_add_card').button();

$("#autopark_tabs").tabs({fx: {opacity:'toggle', duration:1}}); 

$("#control_menu_css").css({"background": "#BCBCBC", "color":"#000","text-shadow": "1px 2px 2px #FFF"});




var table_tr = $('#table_tr');
      table_tr.jqGrid({
                  url: 'control/auto/vtl_auto.php?mode=auto',
                  datatype: 'json',
                  mtype: 'GET',
                  colNames:['№','Тип','Марка','Гос/номер','Водитель','Дата В/Э','Дата Т/О','Дата оконч.страх.','Расход топл.','Остаток топлива','Объём'],
                  colModel :[
                    {name:'id', index:'id', width:40,align:['center']},
                    {name:'type', index:'type', width:70,align:['center']},
                    {name:'name', index:'name', width:100,align:['center']},
                    {name:'number', index:'number',  width:100,align:['center']},
                    {name:'drv', index:'driver',  width:110,align:['center']},
                    {name:'auto_date', sortable:false, width:50,align:['center']},
                    {name:'date_to', sortable:false, width:50,align:['center']},
                    {name:'date_s', sortable:false, width:50,align:['center']},
                    {name:'auto_lpk', sortable:false, width:50,align:['center']},
                    {name:'auto_oil', sortable:false, width:50,align:['center']},
                    {name:'auto_v', sortable:false, width:50,align:['center']}],
                    
                  viewrecords: true,
                  rowNum:10,
                  height: 485,
                  width:1195,caption: '&nbsp;&nbsp;&nbsp;&nbsp;Автотранспорт',
                  sortname: 'id',
                  sortorder: 'asc',
afterInsertRow: function(row_id){},
            ondblClickRow: function(id) {
            
$('#fa_vtl_auto').load('theme/forms/add_vtl_auto.php?edit=1&auto_id='+id);$('#fa_vtl_auto').dialog({ title: 'Редактировать транспорт №'+id },{width: 470,height: 675,modal: true,resizable: false});

            
            
            }, rownumbers: false,
           pager: '#tablePager_tr'}).navGrid('#tablePager_tr', {edit:false,excel:true,add:false,del:false,view:false,refresh:true,search:false});





var table_tr_repair = $('#table_tr_repair');
      table_tr_repair.jqGrid({
                  url: 'control/auto/vtl_repair.php?mode=repair', datatype: 'json', mtype: 'GET',
                  colNames:['№','Дата','ТС','Водитель','Назначение','Объём','Стоимость'],
                  colModel :[
                    {name:'id', index:'id', width:30,align:['center']},
                    {name:'date', index:'date',align:['center'], width:70},
                    {name:'auto', width:100,align:['center']},
                    {name:'driver', width:60,align:['center']},
                    {name:'way_repair', index:'way',width:80,align:['center']},
                    {name:'details', sortable:false,width:140,align:['left']},
                                        {name:'cash', sortable:false,width:70,align:['center']}],
                    
                  viewrecords: true, rowNum:10,
                  height: 500, width:1195, caption: '&nbsp;&nbsp;&nbsp;&nbsp;Ремонтные работы',
                  sortname: 'id', sortorder: 'desc',
            ondblClickRow: function(id) {
            $('#fa_vtl_repair').load('theme/forms/add_vtl_repair.php?edit=1&repair_id='+id);$('#fa_vtl_repair').dialog({ title: 'Редактировать список ремонтных работ №'+id },{width: 440,height: 360,modal: true,resizable: false});
            
            },
afterInsertRow: function(row_id){},
rownumbers: false,pager: '#tablePager_tr_repair' 
                }).navGrid('#tablePager_tr_repair', {edit:false,excel:true,add:false,del:false,view:false,refresh:true,search:false});


var table_tr_driver = $('#table_tr_driver');
      table_tr_driver.jqGrid({
                  url: 'control/auto/vtl_driver.php?mode=driver', datatype: 'json', mtype: 'GET',
                  colNames:['№','Ф.И.О.','Тягач','Полуприцеп','Оклад','Суточные','Городские','Ремонтные','Километры','В подотчёте'],
                  colModel :[
                    {name:'id', index:'id', width:20,align:['center']},
                    {name:'driver_name', index:'name', width:130},
                    {name:'driver_tr', width:100,align:['center']},
                    {name:'driver_dop', sortable:false,width:80,align:['center']},
                    {name:'z_sum', index:'z_sum', width:0,hidden:true},
                    {name:'z_day', index:'z_day', width:0,hidden:true},
                    {name:'z_city', index:'z_city', width:0,hidden:true},
                    {name:'z_repair', index:'z_repair', width:0,hidden:true},
                    {name:'z_km', index:'z_km', width:0,hidden:true},
                    {name:'o_cash', index:'o_cash', width:60,align:['center']}],
                    
                  viewrecords: true, rowNum:10,
                  height: 375, width:1195, caption: '&nbsp;&nbsp;&nbsp;&nbsp;Водители',
                  sortname: 'id', sortorder: 'asc',
            onSelectRow: function(rowid) {
           var rowData=jQuery(this).getRowData(rowid); 
        
$('#z_sum').html(rowData['z_sum']);
 $('#z_day').html(rowData['z_day']);
 $('#z_city').html(rowData['z_city']);
 $('#z_repair').html(rowData['z_repair']);   
 $('#z_km').html(rowData['z_km']);        
            },
            ondblClickRow: function(id) {
            
            
            },
rownumbers: false,pager: '#tablePager_tr_driver' 
                }).navGrid('#tablePager_tr_driver', {edit:false,excel:true,add:false,del:false,view:false,refresh:true,search:false});
                
                
var table_tr_trip = $('#table_tr_trip');
      table_tr_trip.jqGrid({
                  url: 'control/auto/vtl_trip.php?mode=trip', datatype: 'json', mtype: 'GET',
                  colNames:['№','Дата','№ Заявок','Километраж','Водитель','Аванс','Подтвержд.'],
                  colModel :[
                    {name:'id', index:'id', width:40,align:['center']},
                    {name:'date_trip', width:70,align:['center']},
                    {name:'order', width:200,align:['center']},
                    {name:'km_trip', sortable:false,width:70,align:['center']},
                    {name:'drv_trip', sortable:false,width:100,align:['center']},
                    {name:'cash_plan', sortable:false,width:200,align:['center']},
                    {name:'cash_trip', sortable:false,width:80,align:['center']}],
                    
                  viewrecords: true, rowNum:9,
                  height:'auto',
                  autowidth: true, caption: '&nbsp;&nbsp;&nbsp;&nbsp;Рейсы',
                  sortname: 'order', sortorder: 'asc',
afterInsertRow: function(row_id){
}, onSelectRow: function(rowId){ 
      
      $("#table_tr_trip").jqGrid ('toggleSubGridRow', rowId);
   },
                  subGrid: true,
                  subGridUrl: 'control/auto/vtl_trip.php?mode=desc', 
                  subGridOptions: {
		"plusicon"  : "ui-icon-triangle-1-e",
		"minusicon" : "ui-icon-triangle-1-s",
		"openicon"  : "ui-icon-arrowreturn-1-e"
	},"reloadOnExpand" : false,

		"selectOnExpand" : true,
		gridComplete: function() {
   
      

},
                  subGridModel: [{ name : ['Информация по рейсу'], align:  ['left'], width : [1150], params: ['id']} ],
rownumbers: false,pager: '#tablePager_tr_trip' 
                }).navGrid('#tablePager_tr_trip', {edit:false,excel:true,add:false,del:false,view:false,refresh:true,search:false});                
   
   
   // Поиск по заявкам
function updateTable(value) {
    jQuery("#table_tr_trip")
      .setGridParam({url:"control/auto/vtl_trip.php?mode=trip&search=true&s_data="+value, page:1})
      .trigger("reloadGrid");
  }
  



  $('#search_form').submit(function() {
    updateTable($('#search').val());
    return false;
  });
  
   // - - кнопка "показать" при выборе числового периода заявок  - - >
$("#btnData").click(function(){
if(document.getElementById('date_start').value<=document.getElementById('date_end').value){

$('#table_tr_trip').setGridParam({url:'control/auto/vtl_trip.php?mode=trip&date_start='+document.getElementById('date_start').value+'&date_end='+document.getElementById('date_end').value});
jQuery("#table_tr_trip").trigger("reloadGrid");

} else {alert('Дата окончания периода должна быть позже даты начала!');}
});

            
});
</script>
</head>
<body>	
<?php include "data/menu.html";?>
<div id="result" style="display: none;"></div>
<div id="result_temp" style="display: none;"></div>
<! - - форма отчет по автопарку  - - >  
<div id="fa_car_report" style="background:#F8F8F8;"></div>



<div style="width: 98%;margin: 10px;POSITION: absolute;Z-INDEX: 1;">
<div id="autopark_tabs">
<ul style="font-size: 18px;height: 28px;">
		

		<li><a href="#autopark_tabs-1">Рейсы</a></li>
		<li><a href="#autopark_tabs-2">Транспорт</a></li>
		<li><a href="#autopark_tabs-3">Водители</a></li>
<li><a href="#autopark_tabs-4">Ремонт</a></li>
<li><a href="#autopark_tabs-5">Топливные карты</a></li>
<li><a href="#autopark_tabs-6">Уведомления</a></li>
	</ul>
<div id="fa_vtl_auto" style="background:#F8F8F8;"></div>
<div id="fa_vtl_trip" style="background:#F8F8F8;"></div>
<div id="fa_vtl_repair" style="background:#F8F8F8;"></div>
<div id="fa_vtl_drv" style="background:#F8F8F8;"></div>
<div id="fa_car_ls" style="background:#F8F8F8;"></div>
<div id="fa_vtl_way" style="background:#F8F8F8;"></div>
<div id="fa_report" style="background:#F8F8F8;"></div>
<div id="fa_vtl_card" style="background:#F8F8F8;"></div>
<div id="fa_vtl_card_refill" style="background:#F8F8F8;"></div>
<! - - форма отчет по автопарку  - - >  
<div id="fa_car_report" style="background:#F8F8F8;"></div>
<div id="result"></div>

<div id="autopark_tabs-2">
<div style="height:43em;">


<table id="table_tr" align="center"></table>
        <div id="tablePager_tr"></div><fieldset style="width:97%;">&nbsp;&nbsp;&nbsp;&nbsp;<b>Управление</b>&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="add_tr" value="Добавить" style="height:35px;font-size:14px;margin-top:6px;" onclick='$("#fa_vtl_auto").load("theme/forms/add_vtl_auto.php");$("#fa_vtl_auto").dialog({ title: "Новый транспорт" },{width: 460,height: 655,modal: true,resizable: false});'>&nbsp;&nbsp;<input type="button" id="edit_tr" style="height:35px;font-size:14px;" onclick="var selrow=jQuery('#table_tr').jqGrid('getGridParam','selrow');if(selrow){
$('#fa_vtl_auto').load('theme/forms/add_vtl_auto.php?edit=1&auto_id='+selrow);$('#fa_vtl_auto').dialog({ title: 'Редактировать транспорт' },{width: 460,height: 675,modal: true,resizable: false});
} else alert('Выберите транспорт!');" value="Редактировать">&nbsp;&nbsp;|&nbsp;&nbsp;
<input type="button" id="del_tr" value="Удалить" style="height:35px;font-size:14px;" onclick="var selrow=jQuery('#table_tr').jqGrid('getGridParam','selrow');if(selrow){$('#result').html('Удалить автотранспорт №'+selrow+'?');$('#result').dialog({ title: 'Внимание' },{ modal: true },{ resizable: false },{ buttons: [{text: 'Да',click: function() {$.post('control/auto/vtl_auto.php?mode=delete&id='+selrow, function(data) {jQuery('#table_tr').trigger('reloadGrid');$('#result').dialog('close');$('#result_temp').html(data);$('#result_temp').dialog({ title: 'Внимание' },{ height: 90 },{ width: 400 },{ modal: true },{ resizable: false });});}},{text: 'Нет',click: function() {$(this).dialog('close');}}] });
} else alert('Выберите транспорт!');"></fieldset>  


</div>
</div>


<div id="autopark_tabs-1">
<div style="height:43em;">
&nbsp;&nbsp;&nbsp;&nbsp;<a id="btnAdd" onclick='$("#fa_vtl_trip").load("theme/forms/add_vtl_trip.php");$("#fa_vtl_trip").dialog({ title: "Новый рейс" },{width: 510,height: 430,modal: true,resizable: false});'>Добавить</a>&nbsp;&nbsp;|&nbsp;&nbsp;<input type="button" id="edit_trip" style="height:35px;font-size:14px;" onclick="var selrow=jQuery('#table_tr_trip').jqGrid('getGridParam','selrow');if(selrow){
$('#fa_vtl_trip').load('theme/forms/add_vtl_trip.php?edit=1&trip_id='+selrow);$('#fa_vtl_trip').dialog({ title: 'Редактировать рейс' },{width: 510,height: 430,modal: true,resizable: false});
} else alert('Выберите рейс!');" value="Редактировать">&nbsp;&nbsp;|&nbsp;&nbsp;
<input type="button" id="del_trip" value="Удалить" style="height:35px;font-size:14px;" onclick="var selrow=jQuery('#table_tr_trip').jqGrid('getGridParam','selrow');if(selrow){$('#result').html('Удалить рейс №'+selrow+'?');$('#result').dialog({ title: 'Внимание' },{ modal: true },{ resizable: false },{ buttons: [{text: 'Да',click: function() {$(this).dialog('close');$.post('control/auto/vtl_trip.php?mode=delete&id='+selrow, function(data) {jQuery('#table_tr_trip').trigger('reloadGrid');$('#result_temp').html(data);$('#result_temp').dialog({ title: 'Внимание' },{ height: 90 },{ width: 150 },{ modal: true },{ resizable: false });});}},{text: 'Нет',click: function() {$(this).dialog('close');}}] });
} else alert('Выберите рейс!');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;За период: с <input type="text" id="date_start" name="date_start" style="width:80px;" value="" class="input"/> по <input type="text" id="date_end" name="date_end" style="width:80px;" class="input"/>&nbsp;<a class="button" id="btnData" href="#" style="height:22px;width:100px;">показать</a><!--- Поиск ---->
<?php $description='по заявкам';include_once("theme/search.tpl");?>
<!--- End Поиск ---->
<br><br><br>
<table id="table_tr_trip" align="center"></table>
        <div id="tablePager_tr_trip"></div><fieldset style="height:37px;width:97%;"><div style="float:right;"><select name="car_number" style="width:165px;margin-top:7px;" id="car_number" onchange="" class="select"><option value="0">Выберите...</option>
<?php 
include "config.php";
$query_car = "SELECT `id`,`name`,`number`,`type` FROM `vtl_auto` WHERE `delete`='0' AND (`type`='1' OR `type`='3')";
$result_car = mysql_query($query_car) or die(mysql_error());
while($car = mysql_fetch_row($result_car)) {
echo '<option value='.$car[0].'>'.$car[1].' - '.$car[2].'</option>';
}
?>
</select>
<!---- &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="btnAutoReport" onclick='if((document.getElementById("date_start").value==""||document.getElementById("date_end").value=="")||(document.getElementById("date_start").value==document.getElementById("date_end").value)) {$("#result").html("Выберите период для отчета!");$("#result").dialog({ title: "Внимание" },{width: 250,height: 140,modal: true,resizable: false},{ buttons: { "Ok": function() { $(this).dialog("close"); } } });} else {if(document.getElementById("car_number").value!=0)window.location.href="control/auto/vtl_auto_report.php?mode=auto_report&car_number="+$("#car_number").val()+"&date_start="+document.getElementById("date_start").value+"&date_end="+document.getElementById("date_end").value; else {$("#result").html("Выберите транспорт для отчета!");$("#result").dialog({ title: "Внимание" },{width: 300,height: 140,modal: true,resizable: false},{ buttons: { "Ok": function() { $(this).dialog("close"); } } });}}' value="Отчет" style="font-size: 12px;margin-top:5px;width: 100px;height:30px;"> --->
&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="btnTripReport" onclick='if((document.getElementById("date_start").value==""||document.getElementById("date_end").value=="")||(document.getElementById("date_start").value==document.getElementById("date_end").value)) {$("#result").html("Выберите период для отчета!");$("#result").dialog({ title: "Внимание" },{width: 250,height: 140,modal: true,resizable: false},{ buttons: { "Ok": function() { $(this).dialog("close"); } } });} else {if(document.getElementById("car_number").value!=0)window.location.href="control/auto/trip_reports.php?mode=trip&car_number="+$("#car_number").val()+"&date_start="+document.getElementById("date_start").value+"&date_end="+document.getElementById("date_end").value; else {$("#result").html("Выберите транспорт для отчета!");$("#result").dialog({ title: "Внимание" },{width: 300,height: 140,modal: true,resizable: false},{ buttons: { "Ok": function() { $(this).dialog("close"); } } });}}' value="Отчет по рейсам" style="font-size: 12px;margin-top:5px;width: 150px;height:30px;">&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="btnTripNewReport" onclick='if((document.getElementById("date_start").value==""||document.getElementById("date_end").value=="")||(document.getElementById("date_start").value==document.getElementById("date_end").value)) {$("#result").html("Выберите период для отчета!");$("#result").dialog({ title: "Внимание" },{width: 250,height: 140,modal: true,resizable: false},{ buttons: { "Ok": function() { $(this).dialog("close"); } } });} else {if(document.getElementById("car_number").value!=0)window.location.href="control/auto/trip_reports_new.php?mode=trip&car_number="+$("#car_number").val()+"&date_start="+document.getElementById("date_start").value+"&date_end="+document.getElementById("date_end").value; else {$("#result").html("Выберите транспорт для отчета!");$("#result").dialog({ title: "Внимание" },{width: 300,height: 140,modal: true,resizable: false},{ buttons: { "Ok": function() { $(this).dialog("close"); } } });}}' value="Отчет по рейсам 2014" style="font-size: 12px;margin-top:5px;width: 150px;height:30px;">&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="btnPetrolReport" onclick='if((document.getElementById("date_start").value==""||document.getElementById("date_end").value=="")||(document.getElementById("date_start").value==document.getElementById("date_end").value)) {$("#result").html("Выберите период для отчета!");$("#result").dialog({ title: "Внимание" },{width: 250,height: 140,modal: true,resizable: false},{ buttons: { "Ok": function() { $(this).dialog("close"); } } });} else {if(document.getElementById("car_number").value!=0)window.location.href="control/auto/oil_reports.php?mode=trip&car_number="+$("#car_number").val()+"&date_start="+document.getElementById("date_start").value+"&date_end="+document.getElementById("date_end").value; else {$("#result").html("Выберите транспорт для отчета!");$("#result").dialog({ title: "Внимание" },{width: 300,height: 140,modal: true,resizable: false},{ buttons: { "Ok": function() { $(this).dialog("close"); } } });}}' value="Отчет по топливу" style="font-size: 12px;margin-top:5px;width: 150px;height:30px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<input type="button" id="btnVTLcar" onclick='$("#fa_car_report").load("car_report.php");$("#fa_car_report").dialog({ title: "Отчёт по автопарку" },{width: 560,height: 250,modal: true,resizable: false});' value="Автопарк" style="font-size: 12px;width: 150px;height:35px;"></div></fieldset></div>
</div>

<div id="autopark_tabs-4">
<div style="height:43em;">
<table id="table_tr_repair" align="center"></table><div id="tablePager_tr_repair"></div><fieldset >&nbsp;&nbsp;&nbsp;&nbsp;<b>Управление</b>&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="add_repair" value="Добавить" style="height:35px;font-size:14px;margin-top:6px;" onclick='$("#fa_vtl_repair").load("theme/forms/add_vtl_repair.php");$("#fa_vtl_repair").dialog({ title: "Новые ремонтные работы" },{width: 440,height: 360,modal: true,resizable: false});'>&nbsp;&nbsp;<input type="button" id="edit_repair" value="Редактировать" style="height:35px;font-size:14px;" onclick="var selrow=jQuery('#table_tr_repair').jqGrid('getGridParam','selrow');if(selrow){
$('#fa_vtl_repair').load('add_vtl_repair.html?edit=1&repair_id='+selrow);$('#fa_vtl_repair').dialog({ title: 'Редактировать список ремонтных работ №'+selrow },{width: 440,height: 360,modal: true,resizable: false});
} else alert('Выберите лист ремонтных работ!');">&nbsp;&nbsp;|&nbsp;&nbsp;
<input type="button" id="del_repair" value="Удалить" style="height:35px;font-size:14px;" onclick="var selrow=jQuery('#table_tr_repair').jqGrid('getGridParam','selrow');if(selrow){$('#result').html('Удалить лист ремонтных работ №'+selrow+'?');$('#result').dialog({ title: 'Внимание' },{ modal: true },{ resizable: false },{ buttons: [{text: 'Да',click: function() {$(this).dialog('close');$.post('control/auto/vtl_repair.php?mode=delete&id='+selrow, function(data) {jQuery('#table_tr_repair').trigger('reloadGrid');$('#result_temp').html(data);$('#result_temp').dialog({ title: 'Внимание' },{ height: 80 },{ width: 320 },{ modal: true },{ resizable: false });});}},{text: 'Нет',click: function() {$(this).dialog('close');}}] });
} else alert('Выберите лист ремонтных работ!');"></fieldset> 
</div>


</div>

<div id="autopark_tabs-3">
<div style="height:43em;">
<table id="table_tr_driver" align="center"></table>
        <div id="tablePager_tr_driver"></div><hr><fieldset><legend><b>Зарплатные ставки:</b></legend> 
        <table cellspacing="5"><tr><td align="right">Оклад:</td><td width="200"><b><div id="z_sum" style="float:left;">0</div></b>&nbsp;&nbsp;руб.</td></tr><tr><td align="right">Суточные:</td><td><b><div id="z_day" style="float:left;">0</div></b>&nbsp;&nbsp;руб.</td></tr><tr><td align="right">Городские:</td><td><b><div id="z_city" style="float:left;">0</div></b>&nbsp;&nbsp;руб.</td></tr><tr><td align="right">Ремонтные:</td><td><b><div id="z_repair" style="float:left;">0</div></b>&nbsp;&nbsp;руб.</td></tr><tr><td align="right">Километры:</td><td><b><div id="z_km" style="float:left;">0</div></b>&nbsp;&nbsp;руб./км</td></tr></table> </fieldset>
        
        </div></div>

<div id="autopark_tabs-5">
<div style="height:43em;">
<fieldset><table cellspacing="5"><tr><td rowspan="2" width="130"><input type="button" id="add_card" value="Добавить" style="height:35px;font-size:14px;margin-top:6px;width:100px;" onclick='$("#fa_vtl_card").load("add_vtl_card.html");$("#fa_vtl_card").dialog({ title: "Новая топливная карта" },{width: 340,height: 200,modal: true,resizable: false});' ></td><td width="230">&nbsp;<b>Баланс: <div id="card_balance" style="display:inline;font-size:18px;">n/a</div></b>&nbsp;&nbsp;<a href="#" onclick="if($('#card_number').val()!=0){$('#card_load_info').load('control/auto/vtl_card.php?mode=card&card_id='+$('#card_number').val());
} else alert('Выберите топливную карту!');"><img src="data/img/arrow-circle-double.png"></a>
</td><td rowspan="2" width="40"><input type="button" id="cash_add_card" value="+" style="height:35px;font-size:14px;" onclick="if($('#card_number').val()!=0){$('#fa_vtl_card_refill').load('theme/forms/add_vtl_card_refill.php?card_id='+$('#card_number').val());$('#fa_vtl_card_refill').dialog({ title: 'Кредитование топливной карты №'+$('#card_number').val()},{width: 320,height: 230,modal: true,resizable: false});
} else alert('Выберите топливную карту!');"></td><td rowspan="2">
<input type="button" id="edit_card" value="Редактировать" style="height:35px;font-size:12px;" onclick="
if($('#card_number').val()!=0){$('#fa_vtl_card').load('theme/forms/add_vtl_card.php?edit=1&card_id='+$('#card_number').val());$('#fa_vtl_card').dialog({ title: 'Редактировать топливную карту №'+$('#card_number').val()},{width: 340,height: 200,modal: true,resizable: false});
} else alert('Выберите топливную карту!');"></td><td rowspan="2" width="130">
<input type="button" id="del_card" value="Удалить" style="height:35px;font-size:12px;" onclick="if($('#card_number').val()!=0){$('#result').html('Удалить топливную карту №'+$('#card_number :selected').text()+'?');$('#result').dialog({ title: 'Внимание' },{ modal: true },{ resizable: false },{ width: 420 },{ buttons: [{text: 'Да',click: function() {$(this).dialog('close');$.post('control/auto/vtl_card.php?mode=delete&card_id='+$('#card_number').val(), function(data) {$('#card_number').load('control/auto/load_card.php?mode=card');$('#result_temp').html(data);$('#result_temp').dialog({ title: 'Внимание' },{ height: 80 },{ width: 250 },{ modal: true },{ resizable: false });});}},{text: 'Нет',click: function() {$(this).dialog('close');}}] });
} else alert('Выберите топливную карту!');"></td><td rowspan="2">

За период: с <input type="text" id="date_start_card" name="date_start_card" style="width:80px;" value="" class="input"> по <input type="text" id="date_end_card" name="date_end_card" style="width:80px;" class="input">&nbsp;<a class="button" onclick="$('#card_load_info').load('control/auto/vtl_card.php?mode=card&card_id='+$('#card_number').val()+'&start='+$('#date_start_card').val()+'&end='+$('#date_end_card').val());" href="#" style="width:90px;">показать</a>

</td></tr><tr><td><select name="card_number" style="width:200px;" id="card_number" onchange="" class="select">
</select>


</td></tr></table></fieldset> 

<fieldset>
<div id="card_load_info" style="height:33em;"></div>
</fieldset> 

 </div></div>
 
 
 
<div id="autopark_tabs-6">
<div style="height:43em;">
<img src="data/img/exclamation.png" style="float:left;margin:15px;margin-top:20px;"><div style="padding: 10px;background: #ddd;border: 1px solid #bbb;width: 96%;font-size:16px;">По заявкам: <b><div id="trip_fail" style="display:inline;"></div></b> отсутствуют авансовые отчёты!</div>


</div></div>



</div>
</body>
</html>