<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Управление клиентами</title>
<?php include_once("data/header.html");?>



<script type="text/javascript">
// - - главная таблица на странице заявок- -
$(function(){

$('#btnAdd').button({ icons: { primary: "ui-icon-plusthick" } });
$('#btnGroup').button();
$('#btnClGroup').button();


$("#list_menu_css").css({"background": "#BCBCBC", "color":"#000","text-shadow": "1px 2px 2px #FFF"});

               var table = $('#table');
      table.jqGrid({
                  url:'control/clients.php?mode=clients&adr=0&user=<?php echo $_SESSION["user_id"];?>&group=<?php echo $_SESSION["group"];?>',
                  datatype: 'json',
                  mtype: 'GET',
                  colNames:['№','Форма','Клиент','','Куратор клиента','Контактное лицо','Телефон','Блок'],
                  colModel :[
                    {name:'id', index:'id', width:20,align:['center']},
                    {name:'cl_pref', index:'cl_pref', width:30,align:['center']},
                    {name:'client', index:'client', width:100},
                    {name:'cl_nds', index:'cl_nds', width:30},
                    {name:'cl_manager', index:'cl_manager', width:100},
                    {name:'cl_support', index:'cl_support', width:70},
                    {name:'cl_phone', index:'cl_phone', width:70},
                    {name:'block', index:'block', width:0,hidden:true}],

viewrecords: true, <?php if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4){echo 'multiselect: true,';}?>
                  rowNum:11,
                  height:'auto',
                  autowidth: true,caption: '&nbsp;&nbsp;&nbsp;&nbsp;Управление клиентами',
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
                  subGridUrl: 'control/clients.php?mode=desc', 
                  subGridOptions: {
		"plusicon"  : "ui-icon-triangle-1-e",
		"minusicon" : "ui-icon-triangle-1-s",
		"openicon"  : "ui-icon-arrowreturn-1-e"
	},
                  subGridModel: [{ name : ['Управление','Информация о клиенте'], align:  ['center','left'], width : [80,950], params: ['id']} ],
rownumbers: false,
           pager: $('#tablePager'),
            ondblClickRow: function(id) {$("#fa_cl").load("theme/forms/add_client.php?client="+id);$("#fa_cl").dialog({ title: 'Редактировать клиента №'+id },{width: 990,height: 560,modal: true,resizable: false});
              
              
              // jQuery('#table').jqGrid('viewGridRow', id,{"modal":true,"drag":true,"resize":true,"closeOnEscape":true,"dataheight":"auto","width":400});
         
              
            }
        
                

                }).navGrid('#tablePager', {edit:false,excel:true,add:false,del:false,view:false,refresh:true,search:false});
           

// Поиск по клиентам
function updateTable(value) {
    jQuery("#table")
      .setGridParam({url:"control/clients.php?mode=clients&search=true&s_data="+value, page:1})
      .trigger("reloadGrid");
  }
  
 
  $('#search_form').submit(function() {
    updateTable($('#search').val());
    return false;
  });


// - - кнопка добавление клиента - - >
$("#btnAdd").click(function(){
$("#fa_cl").load("theme/forms/add_client.php"); 
    $("#fa_cl").dialog({ title: 'Новый клиент' },{
			width: 990,height: 560,
			modal: true,resizable: false
});
});


  
  });  
    
</script>

</head>
<body>	

<?php include "data/menu.html";?>

<!  - - форма добавления клиента  - - >  
<div id="fa_cl" style="background:#F8F8F8;"></div>


<!  - - форма добавления адреса  - - >  
<div id="fa_adr" style="background:#F8F8F8;"></div>

<div id="result"></div><div id="result_temp"></div>

<div id="dialogp" style="display: none;"></div>
<div id="dialog" style="display: none;"></div>

<div class="main">
<a class="button3" style="width:208px;margin-left:10px;" id="btnAdd" href="#">Добавить клиента</a>
<a class="button" id="btnAllCl" href="#" onclick='$("#table").setGridParam({url:"control/clients.php?mode=clients"});jQuery("#table").trigger("reloadGrid");' style="width:50px;">Все</a><a class="button" id="btnWays_search" href="#" onclick="window.location.href='ways_cl.php?group_id=0';" style="width:100px;">Направления</a>

<!--- Поиск ---->
<?php $description='по клиентам';include_once("theme/search.tpl");?>
<!--- End Поиск ---->



<div class="main_container">
<table id="table" align="center"></table>
        <div id="tablePager"></div>

</div>


</td></tr></table>

<div id="fa_cl_group" style="background:#F8F8F8;"></div>

<?php if($_SESSION["group"]==1||$_SESSION["group"]==2){
echo '
<div id="cl_group_div" style="display: none;"><br>&nbsp;&nbsp;&nbsp;Имя: <input name="cl_group_name" id="cl_group_name" value="" class="input" style="width: 135px;"></div>

<fieldset>
<input type="button" id="btnGroup" onclick=\'
if($("#table").jqGrid("getGridParam","selarrrow")!="") {
$("#cl_group_div").dialog({ title: "Для сохранения группы" },{ width: 250 },{ modal: true },{ resizable: false },{ buttons: { "Добавить": function() {if ($("#cl_group_name").val()!=""){$.post("control/admin.php?mode=group&cl_group_name="+$("#cl_group_name").val()+"&group_cl="+$("#table").jqGrid("getGridParam","selarrrow"), function(data) { $("#result").html(data);$("#result").dialog({ title: "Готово" },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");$("#cl_group_div").dialog("close"); } } });});} else {alert("Введите название группы клиента!");} } } });} else {alert("Выберите клиентов!");}


\' value="Обьединить в группу" style="font-size: 12px;width: 170px;">&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="btnClGroup" onclick=\'$("#fa_cl_group").load("theme/forms/add_cl_group.php");$("#fa_cl_group").dialog({ title: "Группы клиентов" },{width: 760,height: 400,modal: true,resizable: false});\' value="Группы клиентов" style="font-size: 12px;width: 150px;height:35px;"></fieldset>';
}?>

</body>
</html>