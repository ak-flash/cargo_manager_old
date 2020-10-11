<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Управление перевозчиками</title>
<?php include_once("data/header.html");?>


<script type="text/javascript">
// - - главная таблица на странице заявок- -
$(function(){$("#list_menu_css").css({"background": "#BCBCBC", "color":"#000","text-shadow": "1px 2px 2px #FFF"});

$('#btnAdd').button();


               var table = $('#table');
      table.jqGrid({
                  url:'control/transp.php?mode=transp&adr=0',
                  datatype: 'json',
                  mtype: 'GET',
                  colNames:['№','Форма','Перевозчик','','Кол-во машин','Контактное лицо','Телефон','Код АТИ','Блок'],
                  colModel :[
                    {name:'id', index:'id', width:20,align:['center']},
                    {name:'tr_pref', index:'tr_pref', width:20,align:['center']},
                    {name:'transp', index:'transp', width:100},
                    {name:'tr_nds', index:'tr_nds', width:30,align:['center']},
                    {name:'tr_auto', index:'tr_auto', width:20,align:['center']},
                    {name:'tr_support', index:'tr_support', width:60,align:['center']},
                    {name:'tr_phone', index:'tr_phone', width:100},
			{name:'tr_code_ati', index:'tr_code_ati', width:50,align:['center']},
                    {name:'block', index:'block', width:0,hidden:true}],
                    
                  viewrecords: true,
                  rowNum:13,
                  height:'auto',
                  autowidth: true,caption: '&nbsp;&nbsp;&nbsp;&nbsp;Управление перевозчиками',
                  sortname: 'id',
                  sortorder: 'desc',
afterInsertRow: function(row_id, row_data){
if(row_data.block=='1'){
$('#table').jqGrid('setCell',row_id,'id','',{'background-color':'#FFC1C1'});
}
if(row_data.block=='0'){
$('#table').jqGrid('setCell',row_id,'id','',{'background-color':'#C7FFA3'});
}


},
                  subGrid: true,
                  subGridUrl: 'control/transp.php?mode=desc', 
                  subGridOptions: {
		"plusicon"  : "ui-icon-triangle-1-e",
		"minusicon" : "ui-icon-triangle-1-s",
		"openicon"  : "ui-icon-arrowreturn-1-e"
	},
                  subGridModel: [{ name : ['Управление','Информация о перевозчике'], align:  ['center','left'], width : [80,950], params: ['id']} ],
rownumbers: false,
           pager: $('#tablePager'),
            ondblClickRow: function(id) {$("#fa_tr").load("theme/forms/add_transporter.php?tr="+id);$("#fa_tr").dialog({ title: 'Редактировать перевозчика №'+id },{width: 990,height: 560,modal: true,resizable: false});
              
              
              // jQuery('#table').jqGrid('viewGridRow', id,{"modal":true,"drag":true,"resize":true,"closeOnEscape":true,"dataheight":"auto","width":400});
         
              
            }
        
                

                }).navGrid('#tablePager', {edit:false,excel:true,add:false,del:false,view:false,refresh:true,search:false});
           

// Поиск по заявкам
function updateTable(value) {
    jQuery("#table")
      .setGridParam({url:"control/transp.php?mode=transp&search=true&s_data="+value, page:1})
      .trigger("reloadGrid");
  }
  
 
  $('#search_form').submit(function() {
    updateTable($('#search').val());
    return false;
  });






// - - кнопка добавление перевозчика - - >
$("#btnAdd").click(function(){
$("#fa_tr").load("theme/forms/add_transporter.php"); 
    $("#fa_tr").dialog({ title: 'Новый перевозчик' },{
			width: 990,height: 560,
			modal: true,resizable: false
});
});






  
  });  
    
    

</script>

</head>
<body>	


<?php include "data/menu.html";?>


<!  - - форма добавления перевозчика  - - >  
<div id="fa_tr" style="background:#F8F8F8;"></div>



<!  - - форма добавления адреса  - - >  
<div id="fa_adr" style="background:#F8F8F8;"></div>

<!  - - форма добавления автотранспорта  - - >  
<div id="fa_car" style="background:#F8F8F8;"></div>


<div id="result"></div><div id="result_temp"></div>

<div id="dialogp" style="display: none;"></div>
<div id="dialog" style="display: none;"></div>

<div class="main">
<img src="data/img/transport.png" style="float:left;margin-left:20px;">&nbsp;<a class="button3" id="btnAdd" href="#">Добавить</a>
<a class="button" id="btnAllTr" href="#" onclick='$("#table").setGridParam({url:"control/transp.php?mode=transp"});jQuery("#table").trigger("reloadGrid");'>Все</a><a class="button" id="btnWays_search" href="#" onclick="window.location.href='ways.php?group_id=0';" style="margin:12px;margin-right:30px;">Направления</a>

<!--- Поиск ---->
<?php $description='по перевозчикам';include_once("theme/search.tpl");?>
<!--- End Поиск ---->



<div class="main_container">
<table id="table" align="center"></table>
        <div id="tablePager"></div>

</div>




</td></tr></table>



</body>
</html>