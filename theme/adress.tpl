<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Управление адресами</title>
    <?php include_once("data/header.html");?>

    <script type="text/javascript">
        // - - главная таблица на странице адресов- -
        $(function () {
            $('#btnAdd').button({icons: {primary: "ui-icon-plusthick"}});
            $("#list_menu_css").css({"background": "#BCBCBC", "color":"#000","text-shadow": "1px 2px 2px #FFF"});
            var table = $('#table');
            table.jqGrid({
                url: 'control/adress.php?mode=adress',
                datatype: 'json',
                mtype: 'GET',
                colNames: ['№', 'Почтовый код', 'Страна', 'Область', 'Населённый пункт', 'Улица', 'Дом', 'Кв.', 'Блок', 'Тип'],
                colModel: [
                    {name:'id', index:'id', width:20,align:['center']},
                    {name:'adr_postcode', index:'adr_postcode', width:40,align:['center']},
                    {name:'adr_country', index:'adr_country', width:80},
                    {name:'adr_obl', index:'adr_obl', width:100},
                     {name:'adr_city', index:'adr_city', width:100},
                    {name:'adr_street', index:'adr_street', width:100},
                    {name:'adr_dom', index:'adr_dom', width:40},
                    {name:'adr_flat', index:'adr_flat', width:40},
                    {name:'block', index:'block', width:0,hidden:true},
                    {name:'adr_mode', index:'adr_mode', width:0,hidden:true}],
                    
                  viewrecords: true,
                  rowNum:10,
                  height: 550,
                  autowidth: true,
                  sortname: 'id',caption: '&nbsp;&nbsp;&nbsp;&nbsp;Управление адресами',
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
                subGridUrl: 'control/adress.php?mode=desc',
                subGridOptions: {
                    "plusicon": "ui-icon-triangle-1-e",
                    "minusicon": "ui-icon-triangle-1-s",
                    "openicon": "ui-icon-arrowreturn-1-e"
                },
                subGridModel: [{
                    name: ['Управление', 'Информация об адресе'],
                    align: ['center', 'left'],
                    width: [80, 800],
                    params: ['id']
                }],
                rownumbers: false,
                pager: $('#tablePager'),
                ondblClickRow: function (id) {$("#fa_adr").load("theme/forms/add_adr.php?adr_id="+id);$("#fa_adr").dialog({ title: 'Редактировать адрес №'+id },{width: 500,height: 500,modal: true,resizable: false});


            // jQuery('#table').jqGrid('viewGridRow', id,{"modal":true,"drag":true,"resize":true,"closeOnEscape":true,"dataheight":"auto","width":400});


        }


        }).navGrid('#tablePager', {edit:false,excel:true,add:false,del:false,view:false,refresh:true,search:false});
           

// Поиск по адресам
function updateTable(value) {
    jQuery("#table")
      .setGridParam({url:"control/adress.php?mode=adress&search=true&s_data="+value, page:1})
      .trigger("reloadGrid");
  }
  
 
  $('#search_form').submit(function() {
    updateTable($('#search').val());
    return false;
  });






// - - кнопка добавление адреса - - >
$("#btnAdd").click(function(){
$("#fa_adr").load("theme/forms/add_adr.php"); 
    $("#fa_adr").dialog({ title: 'Новый адрес' },{
			width: 500,height: 500,
			modal: true,resizable: false
});
});






  
  });  
    
    

</script>

</head>
<body>	


<?php include "data/menu.html";?>


<!  - - форма добавления адреса  - - >  

<div id="fa_adr" style="background:#F8F8F8;"></div>


<div id="result" style="display: none;"></div>
<div id="result_temp" style="display: none;"></div>

<div id="dialogp" style="display: none;"></div>
<div id="dialog" style="display: none;"></div>

<div class="main">
    <button class="button3" id="btnAdd" href="#">Добавить</button>
    <a class="button" id="btnShare" href="#"
       onclick='$("#table").setGridParam({url:"control/adress.php?mode=adress"});jQuery("#table").trigger("reloadGrid");'
       style="width: 100px;">Все</a><a class="button" id="btnIn" href="#"
                                       onclick='$("#table").setGridParam({url:"control/adress.php?mode=adress&mode_type=1"});jQuery("#table").trigger("reloadGrid");'>Загрузки</a><a
            class="button" id="btnOut" href="#"
            onclick='$("#table").setGridParam({url:"control/adress.php?mode=adress&mode_type=2"});jQuery("#table").trigger("reloadGrid");'>Выгрузки</a>


    <!--- Поиск ---->
    <?php $description='по адресам';include_once("theme/search.tpl");?>
    <!--- End Поиск ---->


    <div class="main_container">
        <table id="table" align="center"></table>
        <div id="tablePager"></div>

    </div>


    </td></tr></table>
    <br>


</body>
</html>