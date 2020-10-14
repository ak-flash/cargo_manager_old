<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Управление компаниями</title>
<?php include_once("data/header.html");?>



<script type="text/javascript">
// - - главная таблица на странице заявок- -
$(function(){$("#control_menu_css").css({"background": "#BCBCBC", "color":"#000","text-shadow": "1px 2px 2px #FFF"});
               var table = $('#table');
      table.jqGrid({
                  url:'control/company.php?mode=company&adr=0',
                  datatype: 'json',
                  mtype: 'GET',
                  colNames:['№','Форма','Компания','','Ответственное лицо','Должность','Телефон','Почта'],
                  colModel :[
                    {name:'id', index:'id', width:20,align:['center']},
                    {name:'company_pref', index:'company_pref', width:30,align:['center']},
                    {name:'company', index:'company', width:90},
                    {name:'company_nds', index:'company_nds', width:30,align:['center']},
                    {name:'company_chief', index:'company_chief', width:90},
                    {name:'company_dchief', index:'company_dchief', width:50,align:['center']},
                    {name:'company_phone', index:'company_phone', width:50,align:['center']},
                    {name:'company_mail', index:'company_mail', width:60}],
                    
                  viewrecords: true,
                  rowNum:8,
                  height: 350,
                  autowidth: true,caption: '&nbsp;&nbsp;&nbsp;&nbsp;Управление компаниями',
                  sortname: 'id',
                  sortorder: 'asc',
afterInsertRow: function(row_id, row_data){


},
                  subGrid: true,
                  subGridUrl: 'control/company.php?mode=desc', 
                  subGridOptions: {
		"plusicon"  : "ui-icon-triangle-1-e",
		"minusicon" : "ui-icon-triangle-1-s",
		"openicon"  : "ui-icon-arrowreturn-1-e"
	},
                  subGridModel: [{ name : ['Управление','Информация о компании'], align:  ['center','left'], width : [80,950], params: ['id']} ],
rownumbers: false,
           pager: $('#tablePager'),
            ondblClickRow: function(id) {$("#fa_company").load("theme/forms/add_company.php?company="+id);$("#fa_company").dialog({ title: 'Редактировать компанию №'+id },{width: 870,height: 435,modal: true,resizable: false});
              
              
              // jQuery('#table').jqGrid('viewGridRow', id,{"modal":true,"drag":true,"resize":true,"closeOnEscape":true,"dataheight":"auto","width":400});
         
              
            }
        
                

                }).navGrid('#tablePager', {edit:false,excel:true,add:false,del:false,view:false,refresh:true,search:false});
           

// Поиск по клиентам
function updateTable(value) {
    jQuery("#table")
      .setGridParam({url:"control/company.php?mode=company&search=true&s_data="+value, page:1})
      .trigger("reloadGrid");
  }
  
 
  $('#search_form').submit(function() {
    updateTable($('#search').val());
    return false;
  });




$('#btnAdd').button();

// - - кнопка добавление клиента - - >
$("#btnAdd").click(function(){
$("#fa_company").load("theme/forms/add_company.php"); 
    $("#fa_company").dialog({ title: 'Новая компания' },{
			width: 870,height: 435,
			modal: true,resizable: false
});
});


  
  });  
    
  

</script>

</head>
<body>	


<?php include "data/menu.html";?>


<!  - - форма добавления компании  - - >  
<div id="fa_company" style="background:#F8F8F8;"></div>
<div id="fa_add_bill"></div>
<div id="fa_c_bill" style="background:#F8F8F8;"></div>


<!  - - форма добавления адреса  - - >  
<div id="fa_adr" style="background:#F8F8F8;"></div>

<div id="result" style="display: none;"></div><div id="result_temp" style="display: none;"></div>

<div id="dialogp" style="display: none;"></div>
<div id="dialog" style="display: none;"></div>

<div class="main">
<a class="button3" id="btnAdd" href="#" style="width:120px;">Добавить</a>




<table id="table" align="center"></table>
        <div id="tablePager"></div>




</div>





</body>
</html>