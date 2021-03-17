<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Управление заявками</title>
    <?php include_once("data/header.html");?>

    <script type="text/javascript">

        // - - главная таблица на странице заявок- -
        $(function () {

            $("#orders_menu").css({"background": "#607269", "text-shadow": "1px 1px 1px #000"});


            $('#btnAdd').button({icons: {primary: "ui-icon-plusthick"}});
            $('#btnUnBlock').button();
            $('#btnSetVzaim').button();
            $('#btnBlock').button();
            $('#btnShowDel').button();
            $('#btnSetPretenz').button();
            $('#btnCl_info').button();
            $('#btnNDSTr').button();
            $('#btnGroupping').button();

    <?php
        //if($_SESSION["group"]!=2&&$_SESSION["group"]!=1) echo '$("#info_text").load("/control/load_info_text.php?mode=random", function(data) {  if(data!="") $("#information").fadeIn(1000);});';
    ?>


var table = $('#table');
    table.jqGrid({url:'control/orders.php?mode=orders&order_id=<?php if(isset($_GET["order_id"])) echo $_GET["order_id"];?>',
datatype: 'json',
mtype: 'GET',
colNames:['№','№/дата','Загрузка','Выгрузка','Клиент','Дата','Ставка клиента','Оплачено клиент.','Перевозчик','Ставка перев.','Оплачено перев.','Блок','Менеджер заявки','Рентабельность','Stat_cl','Stat_tr','Stat_all','Взаимозачет','Претензия','Группа'],
colModel :[
        {name:'id', width:0,hidden:true},
        {name:'id_d', index:'id', width:35,align:['center']},
        {name:'in_adress', index:'in_adress', width:60,align:['center']},
        {name:'out_adress', index:'out_adress', width:60,align:['center']},
        {name:'client', index:'client', width:90},
        {name:'data', index:'data', width:0,hidden:true},
        {name:'cl_cash', index:'cl_cash', width:50,align:['center']},
        {name:'cl_cash_receive', index:'cl_cash_receive', width:50,align:['center']},

        {name:'transp', index:'transp', width:95},
        {name:'tr_cash', index:'tr_cash', width:50,align:['center']},
        {name:'tr_cash_receive', index:'tr_cash_receive', width:50,align:['center']},
        {name:'block', index:'block', width:0,hidden:true},
        {name:'manager', index:'manager', width:0,hidden:true},
        {name:'rent', index:'rent', width:0,hidden:true},
        {name:'stat_cl', index:'stat_cl', width:0,hidden:true},
        {name:'stat_tr', index:'stat_tr', width:0,hidden:true},
        {name:'stat_all', index:'stat_all', width:0,hidden:true},
        {name:'vzaimozachet', index:'vzaimozachet', width:0,hidden:true},
        {name:'pretenzia', index:'pretenzia', width:0,hidden:true},
        {name:'group_id', index:'group_id', width:0,hidden:true}
    ],
    multiselect: true,
    rowNum:8,
    height:'auto',
    autowidth: true,
    caption: '&nbsp;&nbsp;&nbsp;&nbsp;Управление заявками',
    sortname: 'id',
    sortorder: 'desc',
    loadtext: "Загрузка...",
    gridview: true,
    toolbar: [true,"bottom"], loadComplete: function() {

    var myGrid = jQuery("#table");
    var ids = myGrid.jqGrid('getDataIDs');
    var row_data= myGrid.jqGrid('getRowData');

    for (var i = 0; i < ids.length; i++) {
    var id=ids[i];

    //console.log(row_data[i].manager);
    if(row_data[i].rent=='-1'){
                myGrid.jqGrid('setCell',id,'id_d','',{'background-color':'#FFA95E'});
            }

            if(row_data[i].manager!=<?php echo $_SESSION["user_id"];?>&&<?php echo $_SESSION['group'];?>=='3') {
myGrid.jqGrid('setCell',id,'id_d','',{'background-color':'#FFD06B'});
                myGrid.jqGrid('setCell',id,'client','',{'background-color':'#FFD06B'});
                myGrid.jqGrid('setCell',id,'in_adress','',{'background-color':'#FFD06B'});
                myGrid.jqGrid('setCell',id,'out_adress','',{'background-color':'#FFD06B'});
                myGrid.jqGrid('setCell',id,'cl_cash','',{'background-color':'#FFD06B'});
                myGrid.jqGrid('setCell',id,'transp','',{'background-color':'#FFD06B'});
                myGrid.jqGrid('setCell',id,'tr_cash','',{'background-color':'#FFD06B'});
                myGrid.jqGrid('setCell',id,'cl_cash_receive','',{'background-color':'#FFD06B'});
                myGrid.jqGrid('setCell',id,'tr_cash_receive','',{'background-color':'#FFD06B'});
            }

            if(row_data[i].block=='1') {
                myGrid.jqGrid('setCell',id,'id_d','',{'background-color':'#FFC1C1'});
            }
            
            if(row_data[i].block=='0'){
                myGrid.jqGrid('setCell',id,'id_d','',{'background-color':'#C7FFA3'});
            }


            if(row_data[i].vzaimozachet=='1'){
                myGrid.jqGrid('setCell',id,'id_d','',{'background-image':'url(data/images/check_ord.png)'});
            }

            if(row_data[i].pretenzia=='1'){
                myGrid.jqGrid('setCell',id,'cl_cash_receive','',{'background-image':'url(data/images/check_ord.png)'});
            }

            /*<?php if($_SESSION["group"]==2) echo '$("#"+id+"").mouseenter(function() {$("#popup_info").hide();}).mouseleave(function(){  $("#popup_info").hide();  });$("#"+id+">td[aria-describedby=table_id_d]").mouseenter(function() {$("#popup_info").load("/control/popup_ord.php?mode=popup&id="+id,function() {$("#popup_info").show();});  }).mouseleave(function(){    $("#popup_info").hide();  });'; ?>*/

            if(row_data[i].stat_cl=='2'){
                myGrid.jqGrid('setCell',id,'cl_cash','',{'background-color':'#C7FFA3'});
                myGrid.jqGrid('setCell',id,'cl_cash_receive','',{'background-color':'#C7FFA3'});
            }

            if(row_data[i].stat_tr=='2'){
                myGrid.jqGrid('setCell',id,'tr_cash','',{'background-color':'#C7FFA3'});
                myGrid.jqGrid('setCell',id,'tr_cash_receive','',{'background-color':'#C7FFA3'});
            }


            if(row_data[i].stat_cl=='1'){
                myGrid.jqGrid('setCell',id,'cl_cash','',{'background-color':'#C7FFA3'});
                myGrid.jqGrid('setCell',id,'cl_cash_receive','',{'background-color':'#FFE97C'});
            }

            if(row_data[i].stat_tr=='1'){
                myGrid.jqGrid('setCell',id,'transp','',{'background-color':'#C7FFA3'});
                myGrid.jqGrid('setCell',id,'tr_cash','',{'background-color':'#C7FFA3'});
                myGrid.jqGrid('setCell',id,'tr_cash_receive','',{'background-color':'#FFE97C'});
            }

            if(row_data[i].stat_all=='1'){
                myGrid.jqGrid('setCell',id,'id_d','',{'background-color':'#C7FFA3'});
                myGrid.jqGrid('setCell',id,'client','',{'background-color':'#C7FFA3'});
                myGrid.jqGrid('setCell',id,'in_adress','',{'background-color':'#C7FFA3'});
                myGrid.jqGrid('setCell',id,'out_adress','',{'background-color':'#C7FFA3'});
                myGrid.jqGrid('setCell',id,'transp','',{'background-color':'#C7FFA3'});
            }

            
      }


if(document.getElementById('date_start').value!='') 
{
$.post('control/orders.php?mode=total&user=<?php echo $_SESSION["user_id"];?>&group=<?php echo $_SESSION["group"];?>&date_start='+document.getElementById('date_start').value+'&date_end='+document.getElementById('date_end').value, function(data) { 
$("#t_table").css("text-align","right").html("Выполнение: "+data) });
} else
{$.post('control/orders.php?mode=total&user=<?php echo $_SESSION["user_id"];?>&group=<?php echo $_SESSION["group"];?>&date_start=<?php echo "01".date("/m/Y"); ?>&date_end=<?php echo date("d/m/Y"); ?>', function(data) { 
$("#t_table").css("text-align","right").html("Выполнение: "+data) });}


},
                  subGrid: true,
                  subGridUrl: 'control/orders.php?mode=desc', 
                  subGridOptions: {
		"plusicon"  : "ui-icon-triangle-1-e",
		"minusicon" : "ui-icon-triangle-1-s",
		"openicon"  : "ui-icon-arrowreturn-1-e"
	},"reloadOnExpand" : false,

		"selectOnExpand" : true,
                  subGridModel: [{ name : ['Управление','Информация по заявке'], align:  ['center','left'], width : [80,950], params: ['id']} ],
rownumbers: false,
           pager: $('#tablePager'),
           
            ondblClickRow: function(id) {
<?php if($_SESSION["group"]!=5){echo '$("#fa").load("theme/forms/add_order.php?order="+id);$("#fa").dialog({ title: "Редактировать заявку №"+id },{			width: 980,position:[150,50],modal: true,resizable: false});';}?>
              
              
              // jQuery('#table').jqGrid('viewGridRow', id,{"modal":true,"drag":true,"resize":true,"closeOnEscape":true,"dataheight":"auto","width":400});
         
              
            },
              onSelectRow: function(row_id) {
//var gridRow = $("#table").getRowData(row_id);




//alert(""+gridRow['cl_cash']+"");

        }
                

                }).navGrid('#tablePager', {edit:false,excel:true,add:false,del:false,view:false,refresh:true,search:false});
           


// Поиск по заявкам
function updateTable(value) {
    jQuery("#table")
      .setGridParam({url:"control/orders.php?mode=orders&user=<?php echo $_SESSION["user_id"];?>&group=<?php echo $_SESSION["group"];?>&search=true&s_data="+value, page:1})
      .trigger("reloadGrid");
  }





  $('#search_form').submit(function() {
    updateTable($('#search').val());
    return false;
  });




// - - работа с датами - - >
$("#date_start").datepicker({
   onSelect: function(dateText, inst) {$("#date_end").val(dateText);}

});
$("#date_end").datepicker();

$('#date_end').mask('99/99/9999');
$('#date_start').mask('99/99/9999');

// - - кнопка добавление заявки - - >
$("#btnAdd").click(function(){
$("#fa").load("theme/forms/add_order.php"); 
    $("#fa").dialog({ title: 'Новая заявка' },{
			width: 970,position:[150,50],
			modal: true,resizable: false
});
});




// - - кнопка "показать" при выборе числового периода заявок  - - >
$("#btnData").click(function(){

var d_start = document.getElementById('date_start').value.split(/[/]/); 
d_start = new Date(d_start[2]+'-'+d_start[1]+'-'+d_start[0]);
var d_end = document.getElementById('date_end').value.split(/[/]/); 
d_end = new Date(d_end[2]+'-'+d_end[1]+'-'+d_end[0]);

if(d_start<=d_end){

$('#table').setGridParam({url:'control/orders.php?mode=orders&user=<?php echo $_SESSION["user_id"];?>&group=<?php echo $_SESSION["group"];?>&date_start='+document.getElementById('date_start').value+'&date_end='+document.getElementById('date_end').value});
jQuery("#table").trigger("reloadGrid");

} else {alert('Дата окончания периода должна быть позже даты начала!');}
});


 // - - кнопка показа за месяц заявок  - - >
 $("#btnMonth").click(function(){
 $("#date_start").val('<?php echo "01".date("/m/Y"); ?>');
 $("#date_end").val('<?php echo date("d/m/Y"); ?>');
$('#table').setGridParam({url:'control/orders.php?mode=orders&user=<?php echo $_SESSION["user_id"];?>&group=<?php echo $_SESSION["group"];?>&date_start=<?php echo "01".date("/m/Y"); ?>&date_end=<?php echo date("d/m/Y"); ?>'});
jQuery("#table").trigger("reloadGrid");});
  });  
    
    
 if (document.addEventListener) { // FF и другие
        document.addEventListener('keydown', reg_event,false);
    }

function reg_event(event){
    //обработка события
    if(event.ctrlKey&&event.keyCode==81){
    if($("#show_hide").val()=='0'){
        $("#show_hide").val("1");document.getElementById('main_container').style.visibility = 'hidden';
        } else
        {
        $("#show_hide").val("0");
        document.getElementById('main_container').style.visibility = 'visible';
        }
     event.preventDefault();//запрет на дальнейшее распространение
         return false;//возвращаем false
    }

} 

  function showAll() {
    $('#table').jqGrid('groupingRemove', true);
    jQuery("#table")
      .setGridParam({url:"control/orders.php?mode=orders", page:1})
      .trigger("reloadGrid");
  }


 function showInternational() {
    $('#table').jqGrid('groupingRemove', true);
    jQuery("#table")
      .setGridParam({url:"control/orders.php?mode=orders&international=1", page:1})
      .trigger("reloadGrid");
  }

function showRussian() {
    $('#table').jqGrid('groupingRemove', true);
    jQuery("#table")
      .setGridParam({url:"control/orders.php?mode=orders&international=0", page:1})
      .trigger("reloadGrid");
}

function showGroups() {
    $('#table').jqGrid(
        'groupingGroupBy',
        ['group_id'],
        { groupText: ['<b>Группа: {0}</b>'], groupColumnShow: [false], groupOrder : ['desc'] });
    }

function load_print_dialog(order_id, order_hash, only_cl_print_btn) {
    $('#dialogpr').dialog(
            {title: 'Распечатать заявку №' + order_id},
            {width: 400, height: 140, modal: true, resizable: false}
    );
    if (only_cl_print_btn == 1) $('#print_btn_tr').css('display', 'none');

    let print_type = '';
    
    

    print_btn_cl.onclick = function () {
        if ($('input[name="print_template_type"]:checked').val()==3) {
            print_type = '_xls';
        } else print_type = '';
        window.open('control/print' + print_type + '.php?mode=cl&type=' + $('input[name="print_template_type"]:checked').val() + '&id=' + order_hash);
    };

    print_btn_tr.onclick = function () {
        if ($('input[name="print_template_type"]:checked').val()==3) {
            print_type = '_xls';
        } else print_type = '';
        window.open('control/print' + print_type + '.php?mode=tr&type=' + $('input[name="print_template_type"]:checked').val() + '&id=' + order_hash);
    };
}

function show_TN_dialog(order_id, order_hash) {
    $('#dialogTN').dialog(
            {title: 'Сформировать ТН к заявке №' + order_id},
            {width: 500, height: 200, modal: true, resizable: false}
    );

    $("#TN_adress").load('control/print_tn.php?mode=load_adress&id=' + order_hash); 
   
    TN_btn.onclick = function () {
        window.open('control/print_tn.php?mode=tn&id=' + order_hash + '&adress_in=' + $('#tnAdressIn').val() + '&adress_out=' + $('#tnAdressOut').val());

        $("#dialogTN").dialog("close");
    };


}

</script>

</head>
<body>
<input type="hidden" id="show_hide" value="0">

<?php include_once("data/menu.html");?>


<! - - форма добавления заявки - - >
<div id="fa" style="background:url('data/bg_order.png') top left repeat;"></div>

<! - - форма отчет по автопарку - - >
<div id="fa_car_report" style="background:#F8F8F8;"></div>

<!  - - форма добавления адреса  - - >  
<div id="fa_adr" style="background:#F8F8F8;"></div>

<!  - - форма добавления в заявку доп данных  - - >  
<div id="fa_extra" style="background:#F8F8F8;"></div>


<! - - форма добавления перевозчика - - >
<div id="fa_tr" style="background:#F8F8F8;"></div>


<! - - форма добавления автотранспорта - - >
<div id="fa_car" style="background:#F8F8F8;"></div>
<div id="fa_show_car" style="background:#F8F8F8;"></div>


<! - - форма добавления клиента - - >
<div id="fa_cl" style="background:#F8F8F8;"></div>


<div id="result" style="display: none;"></div>
<div id="result_temp" style="display: none;"></div>

<div id="dialogp" style="display: none;">Выделите заявку!</div>
<div id="dialog" style="display: none;">Выберите запись для удаления!</div>

<div id="dialogpr" style="display: none;text-align: center;">
    <input type="radio" value="1" name="print_template_type" style="margin-right: 10px;" checked>Заявка

    <input type="radio" value="2" name="print_template_type" style="margin-left: 30px; margin-right: 7px;">Договор-заявка

    <input type="radio" value="3" name="print_template_type" style="margin-left: 30px; margin-right: 7px;">XLS

    <br>
    <button class="button4" id="print_btn_cl" style="width: 140px; margin-top: 15px; height: 45px; font-size:  1.3em;">
        Клиенту
    </button>
    <button class="button3" id="print_btn_tr"
            style="width:  140px; height: 45px; margin-left: 20px; font-size:  1.3em;">Перевозчику
    </button>
</div>

<div id="dialogTN" style="display: none;text-align: center;">
    
    
    <div id="TN_adress"></div>

    <button class="button3" id="TN_btn"
            style="width:  140px; height: 45px; margin-left: 20px; font-size:  1.3em;">Скачать
    </button>

    <button class="button4" onclick='$("#dialogTN").dialog("close");' style="width:120px;">Закрыть
    </button>

</div>

<div class="main">

    <?php //Ограничения для добавления заявок бухгалтером
if($_SESSION["group"]!=5){echo '<button class="button3" id="btnAdd" style="width:145px;">Добавить</button>';}?>
<a class="button" id="btnMonth" href="#" style="width:90px;">За месяц</a>
<select name="orders_filter"  id="orders_filter" class="select" style="width:145px;margin-left:20px;margin-right:20px;">
            <option value="0" onclick="showAll()">Все</option>
            <option value="1" onclick="showInternational();">Международные</option>
            <option value="0" onclick="showRussian();">Российские</option>
            <option value="2" onclick="showGroups();">Групповые заявки</option>
        </select>

За период: с <input type="text" id="date_start" name="date_start" style="width:80px;" value="" class="input"> по <input type="text" id="date_end" name="date_end" style="width:80px;" class="input">&nbsp;<a class="button" id="btnData" href="#" style="width:80px;">Показать</a>


<!--- Поиск ---->
    <?php $description='по заявкам';include_once("theme/search.tpl");?>
    <!--- End Поиск ---->


    <div class="main_container" id="main_container" style="width:99%;">
        <table id="table" align="center"></table>
        <div id="tablePager"></div>

        <fieldset style="height:35px;">

            <button class="button2" id="btnGroupping"
                    onclick='$.post("control/admin.php?groupping="+$("#table").jqGrid("getGridParam","selarrrow"), function(data) { $("#result").html(data);jQuery("#table").trigger("reloadGrid");$("#result").dialog({ title: "Готово" },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });});'
                    title="заявки в группу или разъединить">Объединить
            </button>
            &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;

            <?php if($_SESSION["group"]==1||$_SESSION["group"]==2){
echo '<button id="btnBlock" onclick=\'$.post("control/admin.php?lock="+$("#table").jqGrid("getGridParam","selarrrow"), function(data) { $("#result").html(data);jQuery("#table").trigger("reloadGrid");$("#result").dialog({ title: "Готово" },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });});\' style="font-size: 0.9em;">
            Заблокировать</button>
            <input type="button" id="btnUnBlock"
                   onclick=\'$.post("control/admin.php?unlock="+$("#table").jqGrid("getGridParam","selarrrow"),
                   function(data) { $("#result").html(data);jQuery("#table").trigger("reloadGrid");$("#result").dialog({
            title: "Готово" },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() {
            $(this).dialog("close"); } } });});\' value="Разблокировать" style="font-size: 12px;">
            &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="btnSetVzaim"
                                                                    onclick=\'$.post("control/admin.php?vzaimozachet="+$("#table").jqGrid("getGridParam","selarrrow"),
                                                                    function(data) { $("#result").html(data);jQuery("#table").trigger("reloadGrid");$("#result").dialog({
            title: "Готово" },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() {
            $(this).dialog("close"); } } });});\' value="«Взаимозачет»" style="font-size: 12px;width: 120px;">&nbsp;&nbsp;&nbsp;&nbsp;<input
                    type="button" id="btnSetPretenz"
                    onclick=\'$.post("control/admin.php?pretenzia="+$("#table").jqGrid("getGridParam","selarrrow"),
                    function(data) { $("#result").html(data);jQuery("#table").trigger("reloadGrid");$("#result").dialog({
            title: "Готово" },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() {
            $(this).dialog("close"); } } });});\' value="«Претензия»" style="font-size: 12px;width: 100px;">&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<button
                    class="button5" id="btnShowDel" onclick=\'$("#table").setGridParam({url:"control/orders.php?mode=orders&showdel=true"});jQuery("#table").trigger("reloadGrid");\' style="float:right;">Удалённые заявки</button>';

}

if($_SESSION["group"]==4){
        echo '<input type="button" id="btnNDSTr" onclick=\'window.location.href="control/tr_nds.php?mode=nds&date_start="+document.getElementById("date_start").value+"&date_end="+document.getElementById("date_end").value\' value="Перевозчики с НДС" style="font-size: 12px;width: 150px;height:30px;">&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" id="btnBlock" onclick=\'$.post("control/admin.php?lock="+$("#table").jqGrid("getGridParam","selarrrow"), function(data) { $("#result").html(data);jQuery("#table").trigger("reloadGrid");$("#result").dialog({ title: "Готово" },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });});\' value="Заблокировать" style="font-size: 12px;width: 160px;">
<input type="button" id="btnUnBlock" onclick=\'$.post("control/admin.php?unlock="+$("#table").jqGrid("getGridParam","selarrrow"), function(data) { $("#result").html(data);jQuery("#table").trigger("reloadGrid");$("#result").dialog({ title: "Готово" },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });});\' value="Разблокировать" style="font-size: 12px;width: 160px;">
&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="btnSetVzaim" onclick=\'$.post("control/admin.php?vzaimozachet="+$("#table").jqGrid("getGridParam","selarrrow"), function(data) { $("#result").html(data);jQuery("#table").trigger("reloadGrid");$("#result").dialog({ title: "Готово" },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });});\' value="Отм. «Взаимозачет»" style="font-size: 12px;width: 160px;">';
}
?>



</fieldset>
</div>


<div class="right_container">&nbsp;</div>

<div id="popup_info"></div>

<!--
<div id="information" style="background:#606060;display: none;border: 2px solid #A0A0A0;z-index:1003;border-radius: 5px;width:650px;position:absolute;top:300px;left:300px;">
  <img  src="data/img/P&O4.png" style="float: left;margin: 15px;"><div style="padding: 15px;color:#FFF;" id="info_text"></div>
  <a id="btnNext_info" href="#" style="color:#FFD800;margin-left: 15px;margin-bottom: 5px;" onclick="$('#info_text').load('control/load_info_text.php?mode=next');">Следующий совет</a>
  <a class="button3" id="btnCl_info" href="#" style="float:right;width:128px;" onclick="$('#information').fadeOut(100);$.post('control/admin.php?mode=close_info');">Закрыть</a>
</div>
-->
</body>
</html>