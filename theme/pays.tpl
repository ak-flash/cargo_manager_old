<?php 
    include "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Управление платежами</title>
<?php include_once("data/header.html");?>

<script type="text/javascript">
// - - главная таблица на странице направлений- -
$(function(){
$('#date_end').mask('99/99/9999');
$('#date_start').mask('99/99/9999');


$("#pays_menu").css({"background": "#BCBCBC", "color":"#000","text-shadow": "1px 2px 2px #FFF"});

$('#btnAppoints').button();$('#btnDeleteShow').button();$('#btnAdd').button({ icons: { primary: "ui-icon-circle-plus" } });$('#btnAdd_group').button();
$('#btnBill').button();$('#btnUnlockPay').button({ icons: { primary: "ui-icon-pin-w" } });$('#btnlockPay').button({ icons: { primary: "ui-icon-pin-s" } });
$('#btnPaymentsList').button();
$('#btnDocs').button();$('#btnPaysReport').button();

// - - работа с датами - - >
$("#date_start").datepicker({
   onSelect: function(dateText, inst) {$("#date_end").val(dateText);}

});
$("#date_end").datepicker();

<?php //if($_SESSION["group"]==1){echo '$("#count_pay").load(\'/control/listpay_load.php?mode=count\',function(data) {if(data==0){document.getElementById(\'number\').style.visibility = "hidden";} else {document.getElementById(\'number\').style.visibility = "visible";}});';} ?>


               var table = $('#table');
      table.jqGrid({
                  url:'control/pays.php?mode=pays',
                  datatype: 'json',
                  mtype: 'GET',
                  colNames:['№','Дата','Направление', 'Способ', 'Категория','Назначение','Заявка','Сумма','Состояние','Сотрудник<br><br>','Del_ID','Удаленные','Владелец'],
                  colModel :[
                    {name:'id', index:'id', width:30,align:['center']},
                    {name:'date', index:'date', width:60,align:['center']},
                    {name:'way', index:'way', width:60,align:['center']},
                    {name:'nds', index:'nds', width:40,align:['center']},
                     {name:'category', index:'category', width:30,align:['center']},
                    {name:'appointment', index:'appoint', width:80,align:['center']},
                    {name:'order', index:'order', width:90,align:['center']},
                    {name:'cash', index:'cash', width:80,align:['center']},
                    {name:'status', index:'status', width:45,align:['center']},
                    {name:'add_name', index:'add_name', width:70,align:['center']},
                    {name:'del_id', index:'del_id', width:0,hidden:true},
                    {name:'deleted', index:'deleted', width:0,hidden:true},
                    {name:'owner', index:'owner', width:0,hidden:true}],

        viewrecords: true,
        rowNum:8,minHeight: 500,
        height:'auto',
        multiselect: true,
        autowidth: true,caption: '&nbsp;&nbsp;&nbsp;&nbsp;Управление платежами',
        sortname: 'id',
        sortorder: 'desc',
        afterInsertRow: function(row_id, row_data){


            if(row_data.owner=='1'){
            $('#table').jqGrid('setCell',row_id,'add_name','',{'background-color':'#EDFFEE'});
} else $('#table').jqGrid('setCell',row_id,'add_name','',{'background-color':'#FFEFEF'});


if(row_data.way=='<b><font size="3">Поступление</font></b>'){
$('#table').jqGrid('setCell',row_id,'way','',{'background-color':'#EDFFEE'});
} else {$('#table').jqGrid('setCell',row_id,'way','',{'background-color':'#FFEFEF'});}

if(row_data.status=='<font size="3"><b>Проведен</b></font>'){
$('#table').jqGrid('setCell',row_id,'id','',{'background-color':'#C7FFA3'});
$('#table').jqGrid('setCell',row_id,'status','',{'background-color':'#C7FFA3'});
}
if(row_data.del_id!='0'){
$('#table').jqGrid('setCell',row_id,'status','',{'background-color':'#FFC7A3'});
}

if(row_data.deleted=='1'){
$('#table').jqGrid('setCell',row_id,'id','',{'background-color':'#FF5656'});
}
},
                  subGrid: true,
                  subGridUrl: 'control/pays.php?mode=desc&group_id=<?php echo $_SESSION["group"];?>', 
                  subGridOptions: {
		"plusicon"  : "ui-icon-triangle-1-e",
		"minusicon" : "ui-icon-triangle-1-s",
		"openicon"  : "ui-icon-arrowreturn-1-e"
	},
                  subGridModel: [{ name : ['Управление','Информация о платеже'], align:  ['center','left'], width : [80,800], params: ['id']} ],
rownumbers: false,
           pager: $('#tablePager'),
            ondblClickRow: function(id) {$("#fa_pay").load("theme/forms/add_pay.php?&pay_id="+id);$("#fa_pay").dialog({ title: 'Редактировать платеж №'+id },{width: 670,height: 860,modal: true,resizable: false});
              
              
          
              
            }
        
                

                }).navGrid('#tablePager', {edit:false,excel:true,add:false,del:false,view:false,refresh:true,search:false});
           

// Поиск по направлениям
function updateTable(value) {
    jQuery("#table")
      .setGridParam({url:"control/pays.php?mode=pays&search=true&s_data="+value, page:1})
      .trigger("reloadGrid");
  }
  
 
  $('#search_form').submit(function() {
    updateTable($('#search').val());
    return false;
  });






// - - кнопка добавление направления - - >
$("#btnAdd").click(function(){
            $("#fa_pay").load("theme/forms/add_pay.php");
            $("#fa_pay").dialog({title: 'Новый платеж'},{
            width: 670,height: 860,
            modal: true,resizable: false
        });
        });

        // - - кнопка добавление направления - - >
        $("#btnAdd_group").click(function(){
            $("#fa_pay_group").load("theme/forms/add_group_pay.php");
            $("#fa_pay_group").dialog({title: 'Создать платежи оплаты для ПЕРЕВОЗЧИКА'},{
            width: 530,height: 560,
            modal: true,resizable: false
        });
        });

        // - - кнопка "показать" при выборе числового периода заявок - - >
        $("#btnData").click(function(){
            if(document.getElementById('date_start').value<=document.getElementById('date_end').value){

            $('#table').setGridParam({url:'control/pays.php?mode=pays&date_start='+document.getElementById('date_start').value+'&date_end='+document.getElementById('date_end').value});
jQuery("#table").trigger("reloadGrid");

} else {alert('Дата окончания периода должна быть позже даты начала!');}
});


  
  });  
    
   


</script>

</head>
<body>	
<input type="hidden" id="show_hide" value="0">

<?php include "data/menu.html"; ?>


<!  - - форма добавления платежа  - - >  
<div id="fa_pay" style="background:url('/data/bg_order.png') top left repeat;"></div>
<div id="fa_pay_group" style="background:url('/data/bg_order.png') top left repeat;">
</div><div id="fa_app" style="background:#F8F8F8;"></div>
<div id="fa" style="background:#F8F8F8;"></div>
<div id="fa_bill_company"></div>

<div id="fa_listpay" style="background:#F8F8F8;"></div>

<div id="result"></div>
<div id="result_temp"></div>

<div id="dialogp" style="display: none;"></div>
<div id="dialog" style="display: none;"></div>

<div class="main">
    <button class="button3" id="btnAdd" style="width:138px;" title="платёж">Добавить</button>
    &nbsp;<button id="btnAdd_group" class="button4" title="платежей по заявкам для перевозчика">Автосоздание</button>

    &nbsp;&nbsp;&nbsp;&nbsp;
    За период: с <input type="text" id="date_start" name="date_start" style="width:80px;" value="" class="input"> по
    <input type="text" id="date_end" name="date_end" style="width:80px;" class="input">
    &nbsp;<a class="button" id="btnData" href="javascript:" style="width:80px;">показать</a>

    <!--- Поиск ---->
    <?php $description='по платежам';include_once("theme/search.tpl");?>
    <!--- End Поиск ---->


<div class="main_container" id="main_container">
<table id="table" align="center"></table>
        <div id="tablePager"></div>

<fieldset><legend>Дополнительно: </legend>

<?php if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4){

    echo '&nbsp;&nbsp;<a class="button" id="btnlockPay" href="javascript:" onclick=\'if($("#table").jqGrid("getGridParam","selrow")!=null){$.post("control/admin.php?mode=pay_lock&pay="+$("#table").jqGrid("getGridParam","selarrrow"), function(data) { $("#result").html(data);jQuery("#table").trigger("reloadGrid");$("#result").dialog({ title: "Готово" },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });});} else {$("#result").html("Выберите платеж(и)!");$("#result").dialog({ title: "Внимание" },{width: 250,height: 140,modal: true,resizable: false},{ buttons: { "Ok": function() { $(this).dialog("close"); } } });};\' style="font-size: 12px;height:30px;width: 100px;font-weight:normal;">Провести</a>&nbsp;&nbsp;<a class="button" id="btnUnlockPay" href="javascript:" onclick=\'if($("#table").jqGrid("getGridParam","selrow")!=null){$.post("control/admin.php?mode=pay_unlock&pay="+$("#table").jqGrid("getGridParam","selarrrow"), function(data) { $("#result").html(data);jQuery("#table").trigger("reloadGrid");$("#result").dialog({ title: "Готово" },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });});} else {$("#result").html("Выберите платеж(и)!");$("#result").dialog({ title: "Внимание" },{width: 250,height: 140,modal: true,resizable: false},{ buttons: { "Ok": function() { $(this).dialog("close"); } } });};\' style="font-size: 12px;font-weight:normal;height:30px;width: 110px;">Распровести</a>';

    }
?>


&nbsp;&nbsp;|&nbsp;&nbsp;
<input type="button" id="btnPaymentsList" onclick='$("#fa_listpay").load("theme/forms/add_listpay.php");$("#fa_listpay").dialog({ title: "Список на оплату" },{width: 1050,height: 770,modal: true,resizable: false});' value="Список на оплату" style="font-size: 14px;width: 160px;height:35px;">
&nbsp;&nbsp;|&nbsp;&nbsp;<input type="button" id="btnDocs" onclick='if(document.getElementById("date_start").value!=""&&document.getElementById("date_end").value!="")window.location.href="control/print_listpay.php?mode=print&date_start="+document.getElementById("date_start").value+"&date_end="+document.getElementById("date_end").value; else {$("#result").html("Выберите период для отчета!");$("#result").dialog({ title: "Внимание" },{width: 250,height: 140,modal: true,resizable: false},{ buttons: { "Ok": function() { $(this).dialog("close"); } } });}' value="Непров. выплаты" style="font-size: 12px;width: 150px;height:30px;">
&nbsp;&nbsp;|&nbsp;&nbsp;

<select name="pays_appoints" style="width:115px;" id="pays_appoints" class="select"><option value="0">Выберите...</option><option value="1000">По всем категориям</option>
<option value="1033">Наличные</option>
  <?php
$query = "SELECT `id`,`app` FROM `pays_appoints` WHERE `auth_id`='3'";
$result = mysql_query($query) or die(mysql_error());
 while($pays_app= mysql_fetch_row($result)) {
 if($pays_app[0]==2) echo '<option value="'.$pays_app[0].'">'.$pays_app[1].'</option>';
if($pays_app[0]>6) echo '<option value="'.$pays_app[0].'">'.$pays_app[1].'</option>';
 } 
  
  ?>

  
  </select>



<input type="button" id="btnPaysReport" onclick='if(document.getElementById("date_start").value!=""&&document.getElementById("date_end").value!="")window.location.href="control/pays_reports.php?mode=pays&app_id="+$("#pays_appoints").val()+"&date_start="+document.getElementById("date_start").value+"&date_end="+document.getElementById("date_end").value; else {$("#result").html("Выберите период для отчета!");$("#result").dialog({ title: "Внимание" },{width: 250,height: 140,modal: true,resizable: false},{ buttons: { "Ok": function() { $(this).dialog("close"); } } });}' value="Отчет" style="font-size: 12px;">




<button class="button5" id="btnDeleteShow" onclick="$('#table').setGridParam({url:'control/pays.php?mode=pays&del_show=true'});jQuery('#table').trigger('reloadGrid');" style="float:right;">Удалённые платежи</button>

</fieldset>

</div>

</div>


<div class="right_container">&nbsp;</div>
</body>
</html>
</body>
</html>