<?php 
    session_start();
    include "config.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Формирование отчетов (Транспортной компании)</title>
<?php include_once("data/header.html");?>
<script type="text/javascript" src="data/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript">
$(function(){
$("#date_start_cl").datepicker({
   onSelect: function(dateText, inst) {$("#date_end_cl").val(dateText);}
});
$("#date_end_cl").datepicker();
$("#date_start_tr").datepicker({
   onSelect: function(dateText, inst) {$("#date_end_tr").val(dateText);}
});

$("#date_start_cl_all").datepicker({
   onSelect: function(dateText, inst) {$("#date_end_cl_all").val(dateText);}
});
$("#date_end_cl_all").datepicker();

$("#date_end_tr").datepicker();

$("#date_start").datepicker({
   onSelect: function(dateText, inst) {$("#date_end").val(dateText);}
});
$("#date_end").datepicker();

$('#date_start_cl_all').mask('99/99/9999');
$('#date_end_cl_all').mask('99/99/9999');

$('#date_start_cl').mask('99/99/9999');
$('#date_end_cl').mask('99/99/9999');
$('#date_start_tr').mask('99/99/9999');
$('#date_end_tr').mask('99/99/9999');

$("#date_komiss").val(<?php echo date("Y");?>).change();

$('#btnReport_tr').button();$('#btnReport_tr_car').button();
$('#btnReport_cl').button();$('#btnReport_cl_ex').button();
$('#btnReport_dir').button();$('#btnReport_dir_old').button();$('#btnReport_sum').button();$('#btnReport_komiss').button();
$("#report_tabs").tabs({fx: {opacity:'toggle', duration:1}}); 

$("#reports_menu").css({"background": "#BCBCBC", "color":"#000","text-shadow": "1px 2px 2px #FFF"});

$("#logs").load('control/logs.php?mode=logs');

$('#cl_select').flexbox('control/cl_search.php', {

    paging: {  
        summaryTemplate: 'Показать {start}-{end} из {total} клиентов',
        pageSize: 6  
    } , 
    width: 300,watermark: 'Выберите...',

hiddenValue: 'id',
onSelect: function() {
var arr = $('#cl_select_hidden').val().split(/[|]/);
$('#cl_id').val(arr[0]);
}
});    

$('#tr_select').flexbox('control/tr_search.php', {

    paging: {  
        summaryTemplate: 'Показать {start}-{end} из {total} перевозчиков',
        pageSize: 6  
    } , 
    width: 300,watermark: 'Выберите...',

hiddenValue: 'id',
onSelect: function() {
var arr = $('#tr_select_hidden').val().split(/[|]/);
$('#tr_id').val(arr[0]);
}
});
    
$('#cl_all_select').flexbox('control/cl_search.php', {

    paging: {  
        summaryTemplate: 'Показать {start}-{end} из {total} клиентов',
        pageSize: 6  
    } , 
    width: 300,watermark: 'Выберите...',

hiddenValue: 'id',
onSelect: function() {
var arr = $('#cl_all_select_hidden').val().split(/[|]/);
$('#cl_all_id').val(arr[0]);

}
});    

var table = $('#table_cl');
      table.jqGrid({
                  url: 'control/rp_load.php',
                  datatype: 'json',
                  mtype: 'GET',
                  colNames:['№','Клиент','Общая','Текущая','Просроченная','Просроченная плановая'],
                  colModel :[
                    {name:'id', index:'id', width:40,align:['center']},
                    {name:'client', index:'name', width:350},
                    {name:'all_cash', sortable:false, width:100,align:['center']},
                    {name:'now_cash', sortable:false, width:100,align:['center']},
                    {name:'old_cash', sortable:false, width:100,align:['center']},
                    {name:'plan_old_cash', sortable:false, width:100,align:['center']}],
                    
                  viewrecords: true,
                  rowNum:10,
                  height: 'auto',
                  width:1200,
                  caption: '&nbsp;&nbsp;&nbsp;&nbsp;Дебиторская задолженность',
                  sortname: 'name',
                  sortorder: 'asc',
afterInsertRow: function(row_id){




}, toolbar: [true,"bottom"], loadComplete: function() { 

},
                  subGrid: true,
                  subGridUrl: 'control/rp_load.php?mode=desc', 
                  subGridOptions: {
		"plusicon"  : "ui-icon-triangle-1-e",
		"minusicon" : "ui-icon-triangle-1-s",
		"openicon"  : "ui-icon-arrowreturn-1-e"
	},
                  subGridModel: [{ name : ['Информация об оплатах'], align:  ['left'], width : [1200], params: ['id']} ],
rownumbers: false,
           pager: '#tablePager'
        
                

                }).navGrid('#tablePager', {edit:false,excel:true,add:false,del:false,view:false,refresh:true,search:false});


var table_sum = $('#table_sum');
      table_sum.jqGrid({
                  url: 'control/rp_load_sum.php',
                  datatype: 'json',
                  mtype: 'GET',
                  colNames:['№','Клиент','Ср.арифм.','Медиана','Мин.-Макс.'],
                  colModel :[
                    {name:'id', index:'id', width:40,align:['center']},
                    {name:'client', index:'name', width:200},
                    {name:'all_cash', sortable:false, width:100,align:['left']},
                    {name:'now_cash', sortable:false,width:100,align:['left']},
                    {name:'old_cash', sortable:false,width:100,align:['center']}],
                    
                  viewrecords: true,
                  rowNum:9,
                  height: 'auto',
                  width:1200,
                  caption: '&nbsp;&nbsp;&nbsp;&nbsp;Отчёт по клиентам',
                  sortname: 'name',
                  sortorder: 'asc',
afterInsertRow: function(row_id){




}, toolbar: [true,"bottom"], loadComplete: function() { 

},
                  subGrid: true,
                  subGridUrl: 'control/rp_load.php?mode=desc', 
                  subGridOptions: {
		"plusicon"  : "ui-icon-triangle-1-e",
		"minusicon" : "ui-icon-triangle-1-s",
		"openicon"  : "ui-icon-arrowreturn-1-e"
	},
                  subGridModel: [{ name : ['Информация об оплатах'], align:  ['left'], width : [1200], params: ['id']} ],
rownumbers: false,
           pager: '#tablePager_sum'
        
                

                }).navGrid('#tablePager_sum', {edit:false,excel:true,add:false,del:false,view:false,refresh:true,search:false});

});
</script>
</head>
<body>	
<?php include "data/menu.html";?>



<div style="width: 99%;margin: 10px;POSITION: absolute;Z-INDEX: 1;">
<div id="report_tabs">
<ul style="font-size: 18px;height: 28px;">
		
	<?php if($_SESSION["group"]==2||$_SESSION["group"]==1||$_SESSION["group"]==4) {
			echo '<li><a href="#report_tabs-0">Действия пользователей<div id="name_tab-0" style="display:inline;"></div></a></li><li><a href="#report_tabs-4">Общий отчёт<div id="name_tab-4" style="display:inline;"></div></a></li>';
	}?>

		<li><a href="#report_tabs-1">По клиентам<div id="name_tab-1" style="display:inline;"></div></a></li>
		<li><a href="#report_tabs-2">По перевозчикам<div id="name_tab-2" style="display:inline;"></div></a></li>

	<?php if($_SESSION["group"]==2||$_SESSION["group"]==1||$_SESSION["group"]==4){echo '<li><a href="#report_tabs-3">Рентабельность<div id="name_tab-3" style="display:inline;"></div></a></li><li><a href="#report_tabs-5">Комиссии<div id="name_tab-5" style="display:inline;"></div></a></li><li><a href="#report_tabs-6" onclick=\'$("#cl_notify").load("control/notify.php?mode=show_cl")\';>Уведомления</a></li>';}?>


	</ul>
<?php if($_SESSION["group"]==2||$_SESSION["group"]==1||$_SESSION["group"]==4){echo '<div id="report_tabs-0">
&nbsp;&nbsp;&nbsp;&nbsp;<b>Найти:</b> <input type="text" id="log_search" class="input" style="width:120px;" onchange=\'$("#logs").load("control/logs.php?mode=logs&l_search="+$(this).val()+"&user="+$("#user").val());\'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Пользователь: <select id="user" style="width:160px; font-size: 18px;" class="select" onchange=\'$("#logs").load("control/logs.php?mode=logs&user="+$(this).val());\'><option value="0">Все...</option>';
$query = "SELECT `id`,`name` FROM `workers` WHERE `delete`='0' AND (`group`='3' OR `group`='2' OR `group`='1') ORDER BY `name` ASC";
$result = mysql_query($query) or die(mysql_error());
while($user= mysql_fetch_row($result)) {
$pieces = explode(" ", $user[1]);
$print_add_name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
echo '<option value='.$user[0].'>'.$print_add_name.'</option>';
}
echo '</select>



<div id="logs">';






echo '</div>
</div>';}?>


<div id="report_tabs-1">
<input type="hidden" name="cl_id" id="cl_id" value="0">
<div  style="font: bold 130% Arial;">Подробный отчет по клиентам</div>
<hr style="width: 99%; height: 2px;" />
<fieldset style="font-size: 16px;"><legend><b>Отчёт:</b></legend>
<table>
<tr><td width="470"><INPUT TYPE=RADIO NAME="report_cl" id="report_cl" VALUE="1" CHECKED>&nbsp;&nbsp;<b>Общий</b><br><table><tr><td width="130"><INPUT TYPE=RADIO NAME="report_cl" VALUE="3">&nbsp;&nbsp;По клиенту&nbsp;</td><td><div id="cl_select" style="float:right;"></div></td></tr></table></td><td width="370">За период
&nbsp;&nbsp;с&nbsp;&nbsp;<input type="text" id="date_start_cl" name="date_start_cl" style="width:85px;" value="" class="input"/>&nbsp;&nbsp;по&nbsp;&nbsp;<input type="text" id="date_end_cl" name="date_end_cl" style="width:85px;" class="input"/></td><td>&nbsp;&nbsp;&nbsp;<a href="#" style="font-size:18px;" id="btnReport_cl" <?php if($_SESSION["group"]!=2&&$_SESSION["group"]!=1&&$_SESSION["group"]!=4)echo 'onclick=\'if(document.getElementById("date_start_cl").value<=document.getElementById("date_end_cl").value){window.location.href="control/reports.php?mode=cl&mode_id="+$("input[name=report_cl]:checked").val()+"&cl_id="+$("#cl_id").val()+"&date_start_cl="+$("#date_start_cl").val()+"&date_end_cl="+$("#date_end_cl").val();} else {alert("Дата окончания периода должна быть позже даты начала!");}\''; else echo 'onclick=\'if(document.getElementById("date_start_cl").value<=document.getElementById("date_end_cl").value){if($(":radio[name=report_cl]").filter(":checked").val()==1){jQuery("#table_cl").setGridParam({url:"control/rp_load.php?mode=rp_load&date_start_cl="+$("#date_start_cl").val()+"&date_end_cl="+$("#date_end_cl").val(),subGridUrl:"control/rp_load.php?mode=desc&date_start_cl="+$("#date_start_cl").val()+"&date_end_cl="+$("#date_end_cl").val(), page:1});jQuery("#table_cl").trigger("reloadGrid");}
if($(":radio[name=report_cl]").filter(":checked").val()==3){jQuery("#table_cl").setGridParam({url:"control/rp_load.php?mode=rp_load&cl_id="+$("#cl_id").val()+"&date_start_cl="+$("#date_start_cl").val()+"&date_end_cl="+$("#date_end_cl").val(),subGridUrl:"control/rp_load.php?mode=desc&date_start_cl="+$("#date_start_cl").val()+"&date_end_cl="+$("#date_end_cl").val(), page:1});jQuery("#table_cl").trigger("reloadGrid");}
} else {alert("Дата окончания периода должна быть позже даты начала!");}\'';
?>>Сформировать</a>&nbsp;|&nbsp;
<?php if($_SESSION["group"]==2||$_SESSION["group"]==1||$_SESSION["group"]==4)echo '<a href="#" style="font-size:12px;" id="btnReport_cl_ex" onclick=\'if(document.getElementById("date_start_cl").value<=document.getElementById("date_end_cl").value){window.location.href="control/reports.php?mode=cl&mode_id="+$("input[name=report_cl]:checked").val()+"&cl_id="+$("#cl_id").val()+"&date_start_cl="+$("#date_start_cl").val()+"&date_end_cl="+$("#date_end_cl").val();} else {alert("Дата окончания периода должна быть позже даты начала!");}\'>Excel</a>';?>

</td></tr>

</table>







        
</fieldset>
<br>

<?php if($_SESSION["group"]==2||$_SESSION["group"]==1||$_SESSION["group"]==4)echo '<table id="table_cl" align="center"></table>
        <div id="tablePager"></div>';?>

</div>
<div id="report_tabs-2">
<input type="hidden" name="tr_id" id="tr_id" value="0">
<div  style="font: bold 130% Arial;">Подробный отчет по перевозчикам</div>
<hr style="width: 99%; height: 2px;" />
<fieldset style="font-size: 16px;"><legend><b>Отчёт:</b></legend>
<div style="float:left;">
<br>&nbsp;&nbsp;За период
&nbsp;&nbsp;с&nbsp;&nbsp;<input type="text" id="date_start_tr" name="date_start_tr" style="width:85px;" value="" class="input" />&nbsp;&nbsp;по&nbsp;&nbsp;<input type="text" id="date_end_tr" name="date_end_tr" style="width:85px;" class="input"/>

<br><br>

<table><tr><td colspan="2"><INPUT TYPE=RADIO NAME="report_tr" id="report_tr" VALUE="1" CHECKED>&nbsp;&nbsp;<b>Общий</b></td></tr><tr><td width="150"><INPUT TYPE=RADIO NAME="report_tr" VALUE="3">&nbsp;&nbsp;По перевозчику</td><td><div id="tr_select"></div></td></tr></table>
</div>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" style="font-size:18px;" id="btnReport_tr" onclick='if(document.getElementById("date_start_tr").value<=document.getElementById("date_end_tr").value){window.location.href="control/reports.php?mode=tr&mode_id="+$("input[name=report_tr]:checked").val()+"&tr_id="+$("#tr_id").val()+"&date_start_tr="+$("#date_start_tr").val()+"&date_end_tr="+$("#date_end_tr").val();} else {alert("Дата окончания периода должна быть позже даты начала!");}'>Сформировать</a>
</fieldset>
<br>

<div  style="font: bold 130% Arial;">Подробный отчет по автотранспорту</div>
<hr style="width: 99%; height: 2px;" />
<a href="#" style="font-size:18px;" id="btnReport_tr_car" onclick='window.location.href="control/reports_car.php?mode=tr_car";'>Сформировать</a>
</div>


<?php if($_SESSION["group"]==2||$_SESSION["group"]==1||$_SESSION["group"]==4){echo '<div id="report_tabs-3"><table cellspacing="10" width="100%"><tr><td width="350">&nbsp;&nbsp;За период
&nbsp;&nbsp;с&nbsp;&nbsp;<input type="text" id="date_start"  name="date_start" style="width:85px;" value="" class="input"/>&nbsp;&nbsp;по&nbsp;&nbsp;<input type="text" id="date_end" name="date_end" style="width:85px;" class="input"/><br><br>
&nbsp;&nbsp;Рентабельность
&nbsp;&nbsp;от&nbsp;&nbsp;<input type="text" id="start_rent" onchange=\'$("#end_rent").val($("#start_rent").val());\' name="start_rent" style="width:35px;" value="" class="input"/>&nbsp;&nbsp;до&nbsp;&nbsp;<input type="text" id="end_rent" name="end_rent" style="width:35px;" class="input"/> %</td><td>
<a href="#" style="font-size:18px;" id="btnReport_sum" onclick=\'if(document.getElementById("date_start").value<=document.getElementById("date_end").value){jQuery("#table_sum").setGridParam({url:"control/rp_load_sum.php?mode=rp_load&date_start="+$("#date_start").val()+"&date_end="+$("#date_end").val()+"&start_rent="+$("#start_rent").val()+"&end_rent="+$("#end_rent").val(),subGridUrl:"control/rp_load_sum.php?mode=desc&date_start="+$("#date_start").val()+"&date_end="+$("#date_end").val()+"&start_rent="+$("#start_rent").val()+"&end_rent="+$("#end_rent").val(), page:1});jQuery("#table_sum").trigger("reloadGrid");
} else {alert("Дата окончания периода должна быть позже даты начала!");}\'>Сформировать</a></td></tr></table><br>
<table id="table_sum" align="center"></table>
        <div id="tablePager_sum"></div></div>';}?>

<?php if($_SESSION["group"]==2||$_SESSION["group"]==1||$_SESSION["group"]==4){echo '<div id="report_tabs-4"><fieldset style="font-size: 16px;"><legend><b>Отчёт:</b></legend>
<div style="float:left;">
<br>&nbsp;&nbsp;За период
&nbsp;&nbsp;с&nbsp;&nbsp;<input type="text" id="date_start_cl_all" name="date_start_cl_all" style="width:85px;" value="" class="input"/>&nbsp;&nbsp;по&nbsp;&nbsp;<input type="text" id="date_end_cl_all" name="date_end_cl_all" style="width:85px;" class="input"/>

<br><br>

<input type="hidden" name="cl_all_id" id="cl_all_id" value="0">
<table><tr><td colspan="2"><INPUT TYPE=RADIO NAME="report_cl_all" id="report_cl_all" VALUE="1" CHECKED>&nbsp;&nbsp;<b>Общий</b></td></tr><tr><td width="150"><INPUT TYPE=RADIO NAME="report_cl_all" VALUE="3">&nbsp;&nbsp;По клиенту</td><td><div id="cl_all_select"></div></td></tr>

<tr><td width="200"><INPUT TYPE=RADIO NAME="report_cl_all" VALUE="5">&nbsp;&nbsp;По группе клиентов</td><td><select name="group" id="group" style="width:260px; font-size: 18px;" class="select"><option value="0">Выберите...</option>';
$query = "SELECT `group_id`,`group_name`,`group_cl` FROM `cl_group` ORDER BY `group_name` ASC";
$result = mysql_query($query) or die(mysql_error());
while($group= mysql_fetch_row($result)) {
echo '<option value='.$group[2].'>'.$group[1].'</option>';
}
echo '</select>



</td></tr>


<tr><td width="200">&nbsp;&nbsp;Контрагенты</td><td><select name="group_cont" id="group_cont" style="width:260px; font-size: 18px;" class="select"><option value="0">Выберите...</option>';
$query = "SELECT `id`,`name` FROM `company` ORDER BY `id` ASC";
$result = mysql_query($query) or die(mysql_error());
while($group_cont= mysql_fetch_row($result)) {
if($group_cont[0]!=1)echo '<option value='.$group_cont[0].'>'.$group_cont[1].'</option>';
}
echo '</select>
</td></tr>


<tr><td colspan="2">&nbsp;&nbsp;</td></tr><tr><td><INPUT TYPE=RADIO NAME="report_cl_all" id="report_cl_all" VALUE="4" CHECKED>&nbsp;&nbsp;По менеджеру</td><td><select name="username" id="username" style="width:160px; font-size: 18px;" class="select"><option value="0">Выберите...</option>';
$query = "SELECT `id`,`name` FROM `workers` WHERE `delete`='0' AND (`group`='3' OR `group`='2' OR `group`='1') ORDER BY `name` ASC";
$result = mysql_query($query) or die(mysql_error());
while($user= mysql_fetch_row($result)) {
$pieces = explode(" ", $user[1]);
$print_add_name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
echo '<option value='.$user[0].'>'.$print_add_name.'</option>';
}
echo '</select></td></tr><tr><td colspan="2">&nbsp;&nbsp;</td></tr><tr><td colspan="2"><INPUT TYPE=RADIO NAME="report_cl_all" id="report_cl_all" VALUE="6">&nbsp;&nbsp;по НДС</td></tr></table>
</div>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" style="height:35px;font-size:18px;margin-top:40px;" id="btnReport_dir_old" onclick=\'if(document.getElementById("date_start_cl_all").value<=document.getElementById("date_end_cl_all").value){window.location.href="control/export_old.php?mode=cl_all&mode_id="+$("input[name=report_cl_all]:checked").val()+"&cl_all="+$("#cl_all_id").val()+"&group_cont="+$("#group_cont").val()+"&date_start="+$("#date_start_cl_all").val()+"&date_end="+$("#date_end_cl_all").val()+"&user="+$("#username").val()+"&group="+$("#group").val();} else {alert("Дата окончания периода должна быть позже даты начала!");}\'>Старый</a><br>
<br>
&nbsp;<a href="#" style="height:65px;font-size:30px;" id="btnReport_dir" onclick=\'if(document.getElementById("date_start_cl_all").value<=document.getElementById("date_end_cl_all").value){window.location.href="control/export.php?mode=cl_all&mode_id="+$("input[name=report_cl_all]:checked").val()+"&cl_all="+$("#cl_all_id").val()+"&group_cont="+$("#group_cont").val()+"&date_start="+$("#date_start_cl_all").val()+"&date_end="+$("#date_end_cl_all").val()+"&user="+$("#username").val()+"&group="+$("#group").val();} else {alert("Дата окончания периода должна быть позже даты начала!");}\'>Новый</a></fieldset></div>';}?>




<?php if($_SESSION["group"]==2||$_SESSION["group"]==1||$_SESSION["group"]==4){echo '<div id="report_tabs-5"><table cellspacing="10" width="100%"><tr><td width="100">За период</td><td width="150">
<select name="date_komiss" style="width:110px;" id="date_komiss" class="select">';

$i=-2;
while ($i <= 0) {
echo '<option value="'.(date("Y")+$i).'">'.(date("Y")+$i).'</option>';
$i++; 
 }
echo '</select>	
	 год</td><td rowsap="3"><a href="#" style="font-size:18px;" id="btnReport_komiss" onclick=\'window.location.href="control/komiss_report.php?mode=report&type="+$("input[name=report_komiss]:checked").val()+"&date_komiss="+$("#date_komiss").val();\'>Сформировать</a></td></tr>
<tr><td colspan="2"><INPUT TYPE=RADIO NAME="report_komiss" id="report_komiss" VALUE="1" CHECKED>&nbsp;&nbsp;<b>По КЛИЕНТАМ</b></td></tr><tr><td colspan="2"><INPUT TYPE=RADIO NAME="report_komiss" id="report_komiss" VALUE="2">&nbsp;&nbsp;<b>По МЕНЕДЖЕРАМ</b></td></tr></table></div>';}?>

<div id="report_tabs-6">
<?php if($_SESSION["group"]==2||$_SESSION["group"]==1||$_SESSION["group"]==4){echo '<fieldset><legend><b>Фильтр</b></legend>
</fieldset>
<br>
<div style="height: 36em;width: 101%; overflow: auto" id="cl_notify">Идет загрузка данных...Ожидайте.</div>';}?>

</div>



</div>


</body>
</html>