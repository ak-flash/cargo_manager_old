<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
<title>Система обращения документов по заявкам</title>
<?php include_once("data/header.html");?>

<script type="text/javascript">

// - - главная таблица на странице заявок- -
$(function(){

$("#docs_menu").css({"background": "#BCBCBC", "color":"#000","text-shadow": "1px 2px 2px #FFF"});
$('#btnCl_date_bill').button();
$('#btnCl_add_bill').button();
$('#btnTr_date_bill').button();
$('#btnTr_add_bill').button();
$('#btnCreatePayCl').button();
$('#btnCreatePayTr').button();
$('#btnDocsReport').button(); 


// - - работа с датами - - >
$("#date_start").datepicker({
   onSelect: function(dateText, inst) {$("#date_end").val(dateText);}

});
$("#date_end").datepicker();

$('#date_end').mask('99/99/9999');
$('#date_start').mask('99/99/9999');

<?php if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4){echo "$('#show_dir').css('display','');";} ?>


               var table = $('#table');
      table.jqGrid({
                  url:'control/docs.php?mode=docs&user=<?php echo $_SESSION["user_id"];?>&group=<?php echo $_SESSION["group"];?>&doc_id=<?php echo $_GET["doc_id"];?>',
                  datatype: 'json',
                  mtype: 'GET',
                  colNames:['№','Заявка','Клиент','','№ Счёта','Дата выст.счёта','Дата получ.док.','','Перевозчик','','Дата получ.док.','Признак.','Stat','cl_pay','tr_pay'],
                  colModel :[
                    {name:'id', index:'id',key : true, width:20,align:['center']},
                    {name:'order', index:'order', width:25,align:['center']},
                    {name:'client', index:'client', width:100},
                    {name:'cl_nds', index:'cl_nds', width:30,align:['center']},
                    {name:'cl_bill', index:'cl_bill', width:30,align:['center']},
                    {name:'date_add_bill', index:'date_add_bill', width:50,align:['center']},
                    {name:'date_cl_receve', index:'date_cl_receve', width:40,align:['center']},
                    {name:'empty', index:'empty', width:5},
                    {name:'transp', index:'transp', width:65},
                     {name:'tr_nds', index:'tr_nds', width:40,align:['center']},
                    {name:'date_tr_receve', index:'date_tr_receve', width:40,align:['center']},
                    {name:'status', index:'status', width:30,align:['center']},
                    {name:'stat', index:'stat', width:0,hidden:true},
                    {name:'cl_pay', width:0,hidden:true},
                    {name:'tr_pay', width:0,hidden:true}],
                    
                  viewrecords: true,
                  rowNum:11,
                  height:'auto',
                  autowidth: true,
                  caption: '&nbsp;&nbsp;&nbsp;&nbsp;Система обращения документов по заявкам',
                  sortname: 'order',
                  sortorder: 'desc',
afterInsertRow: function(row_id, row_data){
if(row_data.stat=='1'){
$('#table').jqGrid('setCell',row_id,'date_tr_receve','',{'background-color':'#C7FFA3'});
} else $('#table').jqGrid('setCell',row_id,'date_tr_receve','',{'background-color':'#FFEFEF'});

if(row_data.cl_pay=='1')$('#table').jqGrid('setCell',row_id,'client','',{'background-color':'#C7FFA3'});
if(row_data.tr_pay=='1')$('#table').jqGrid('setCell',row_id,'transp','',{'background-color':'#C7FFA3'});

if(row_data.cl_pay=='2')$('#table').jqGrid('setCell',row_id,'client','',{'background-color':'#FFEE9E'});
if(row_data.tr_pay=='2')$('#table').jqGrid('setCell',row_id,'transp','',{'background-color':'#FFEE9E'});

if(row_data.cl_pay=='1'&&row_data.tr_pay=='1')$('#table').jqGrid('setCell',row_id,'order','',{'background-color':'#C7FFA3'});

if(row_data.date_cl_receve=='-')$('#table').jqGrid('setCell',row_id,'date_cl_receve','',{'background-color':'#FFEFEF'});
if(row_data.date_tr_receve=='-')$('#table').jqGrid('setCell',row_id,'date_tr_receve','',{'background-color':'#FFEFEF'});

if(row_data.date_cl_receve!='-'&&row_data.date_tr_receve!='-'){
$('#table').jqGrid('setCell',row_id,'id','',{'background-color':'#C7FFA3'});
$('#table').jqGrid('setCell',row_id,'status','',{'background-color':'#C7FFA3'});
}



if(row_data.manager!=<?php echo $_SESSION["user_id"];?>&&<?php echo $_SESSION['group'];?>=='3'){
$('#table').jqGrid('setCell',row_id,'id','',{'background-color':'#FFEFEF'});


}
}, toolbar: [true,"bottom"], loadComplete: function() { 

},
                  subGrid: true,
                  subGridUrl: 'control/docs.php?mode=desc', 
                  subGridOptions: {
		"plusicon"  : "ui-icon-triangle-1-e",
		"minusicon" : "ui-icon-triangle-1-s",
		"openicon"  : "ui-icon-arrowreturn-1-e"
	},
                  subGridModel: [{ name : ['Управление','Информация о документах'], align:  ['center','left'], width : [200,700], params: ['id']} ],
rownumbers: false,
           pager: '#tablePager',
            ondblClickRow: function(id) {
    
              $("#fa_add_docs").load("theme/forms/add_docs.php?doc_id="+id);$("#fa_add_docs").dialog({ title: 'Редактировать пакет документов №'+id },{width: 700,height:810,modal: true,resizable: false});
              
              // jQuery('#table').jqGrid('viewGridRow', id,{"modal":true,"drag":true,"resize":true,"closeOnEscape":true,"dataheight":"auto","width":400});
         
              
            }
        
                

                }).navGrid('#tablePager', {edit:false,excel:true,add:false,del:false,view:false,refresh:true,search:false});

    
jQuery('#table').jqGrid('bindKeys', {"onEnter":function(id) { $("#fa_add_docs").load("theme/forms/add_docs.php?doc_id="+id);$("#fa_add_docs").dialog({ title: 'Редактировать пакет документов №'+id },{width: 680,height:730,modal: true,resizable: false});

} } );
        

// Поиск по документам
function updateTable(value) {
    jQuery("#table")
      .setGridParam({url:"control/docs.php?mode=docs&user=<?php echo $_SESSION["user_id"];?>&group=<?php echo $_SESSION["group"];?>&search=true&s_data="+value, page:1})
      .trigger("reloadGrid");
  }
  
 
  $('#search_form').submit(function() {
    updateTable($('#search').val());
    return false;
  });











});

 
    
if (document.addEventListener) { // FF и другие
        document.addEventListener('keydown', reg_event,false);
    }

function reg_event(event){//обработка события
if(event.ctrlKey&&event.keyCode==81){
if($("#show_hide").val()=='0'){$("#show_hide").val("1");document.getElementById('main_container').style.visibility = 'hidden';} else
 {$("#show_hide").val("0");
    document.getElementById('main_container').style.visibility = 'visible';}
 event.preventDefault();//запрет на дальнейшее распространение
     return false;//возвращаем false
}

}


 $('#btnClose_bill').button();
$('#save_bill').button(); 


</script>

</head>
<body>	
<input type="hidden" id="show_hide" value="0">

<?php include_once("data/menu.html");?>


<! - - форма добавления документов  - - >  
<div id="fa_docs" style="background:#F8F8F8;"></div>
<div id="fa_add_adidas" style="background:#F8F8F8;"></div>
<div id="fa_add_docs" style="display:none;">

</div>

<div style="margin-bottom:70px;">

<div id="show_dir" style="float:left;display:none;">
<input type="button" id="btnCreatePayCl" onclick='if($("#table").jqGrid("getGridParam","selrow")!=null){$.post("control/add_listpay.php?create=true&mode=cl&docs_id="+$("#table").jqGrid("getGridParam","selrow"), function(data) { $("#result").html(data);jQuery("#table").trigger("reloadGrid");$("#result").dialog({ title: "Готово" },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });});} else {alert("Выберите основание для платежа!")};' value="Созд. плат. клиента" style="margin-left:30px;height:35px;font-size: 14px;">&nbsp;&nbsp;|&nbsp;&nbsp;<input type="button" id="btnCreatePayTr" onclick='if($("#table").jqGrid("getGridParam","selrow")!=null){$.post("control/add_listpay.php?create=true&mode=tr&docs_id="+$("#table").jqGrid("getGridParam","selrow"), function(data) { $("#result").html(data);jQuery("#table").trigger("reloadGrid");$("#result").dialog({ title: "Готово" },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });});} else {alert("Выберите основание для платежа!")};' value="Созд. плат. перевоз." style="font-size: 14px;height:35px;">
&nbsp;&nbsp;&nbsp;&nbsp;
За период: с <input type="text" id="date_start" name="date_start" style="width:80px;" value="" class="input"> по <input type="text" id="date_end" name="date_end" style="width:80px;" class="input">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="btnDocsReport" onclick='window.location.href="control/docs_reports.php?mode=report&date_start="+document.getElementById("date_start").value+"&date_end="+document.getElementById("date_end").value' value="Отчет" style="font-size: 18px;width: 100px;height:40px;margin-top:10px;margin-bottom:-20px;">
&nbsp;&nbsp;&nbsp;&nbsp;

</div>

<!--- Поиск ---->
<?php $description='по документам';include_once("theme/search.tpl");?>
<!--- End Поиск ---->

</div>

<div id="result" style="display: none;"></div><div id="result_temp" style="display: none;"></div>

<div class="main">












<div class="main_container" id="main_container">
<table id="table" align="center"></table>
        <div id="tablePager"></div>





</div>

<div id="mouse"></div>
<div class="right_container">&nbsp;</div>
</body>
</html>