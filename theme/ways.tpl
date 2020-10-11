<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Управление направлениями работы</title>
<?php include_once("data/header.html");?>

<script type="text/javascript">
// - - главная таблица на странице направлений- -
$(function(){
$('#btnAdd').button();
$('#btnAll').button();
               var table = $('#table');
      table.jqGrid({
                  url:'control/ways.php?mode=ways&tr_id=<?php echo $_GET["tr_id"];?>',
                  datatype: 'json',
                  mtype: 'GET',
                  colNames:['№','Перевозчик','Загрузка','Выгрузка', 'Периодичность', 'Вид погрузки','Вид выгрузки','Блок'],
                  colModel :[
                    {name:'id', index:'id', width:20,align:['center']},
                    {name:'tr', index:'tr', width:120},
                    {name:'in_city', index:'in_city', width:100,align:['center']},
                    {name:'out_city', index:'out_city', width:100,align:['center']},
                    {name:'times', index:'times', width:60,align:['center']},
                     {name:'load_in', index:'load_in', width:50,align:['center']},
                    {name:'load_out', index:'load_out', width:50,align:['center']},
                    {name:'block', index:'block', width:0,hidden:true}],
                    
                  viewrecords: true,
                  rowNum:12,
                  height:'auto',
                  autowidth: true,caption: '&nbsp;&nbsp;&nbsp;&nbsp;Управление направлениями работы',
                  sortname: 'id',
                  sortorder: 'desc',
afterInsertRow: function(row_id, row_data){



},subGridOptions: {
		"plusicon"  : "ui-icon-triangle-1-e",
		"minusicon" : "ui-icon-triangle-1-s",
		"openicon"  : "ui-icon-arrowreturn-1-e"
	},
                  subGrid: true,
                  subGridUrl: 'control/ways.php?mode=desc&group=<?php echo $_GET["m"];?>&group_id=<?php echo $_GET["group_id"];?>', 
                  
                  subGridModel: [{ name : ['Управление','Информация о направлении'], align:  ['center','left'], width : [80,800], params: ['id']} ],
rownumbers: false,
           pager: $('#tablePager'),
            ondblClickRow: function(id) {$("#fa_way").load("theme/forms/add_way.php?way_id="+id);$("#fa_way").dialog({ title: 'Редактировать направление №'+id },{width: 530,height: 330,modal: true,resizable: false});
              
              
              // jQuery('#table').jqGrid('viewGridRow', id,{"modal":true,"drag":true,"resize":true,"closeOnEscape":true,"dataheight":"auto","width":400});
         
              
            }
        
                

                }).navGrid('#tablePager', {edit:false,excel:true,add:false,del:false,view:false,refresh:true,search:false});
           

// Поиск по направлениям
function updateTable(value,value1,value2) {
    jQuery("#table")
      .setGridParam({url:"control/ways.php?mode=ways&search=true&s_data_in="+value1+"&s_data_out="+value2+"&s_data="+value, page:1})
      .trigger("reloadGrid");
  }
  
 
  $('#search_form').submit(function() {
    updateTable($('#search').val(),$('#in').val(),$('#out').val());
    return false;
  });






// - - кнопка добавление направления - - >
$("#btnAdd").click(function(){
$("#fa_way").load("theme/forms/add_way.php?tr_id=<?php echo $_GET['tr_id'];?>"); 
    $("#fa_way").dialog({ title: 'Новое направление работы' },{
			width: 530,height: 330,
			modal: true,resizable: false
});
});






  
  });  
    
    

</script>

</head>
<body>	


<?php include "data/menu.html"; ?>


<!  - - форма добавления направления  - - >  
<div id="fa_way" style="background:#F8F8F8;"></div>



<div id="result"></div><div id="result_temp"></div>

<div id="dialogp" style="display: none;"></div>
<div id="dialog" style="display: none;"></div>

<div class="main">
<table width="100%"><tr><td>
<img src="data/img/transport.png" style="float:left;margin-left:20px;">&nbsp;<?php if($_GET['m']) echo '<a class="button3" id="btnAdd" href="#">Добавить</a>';?><a class="button" href="#" onclick='$("#table").setGridParam({url:"control/ways.php?mode=ways"});jQuery("#table").trigger("reloadGrid");' id="btnAll" style="height:40px;">Все</a>
</td><td>
<div style="float:right;margin-right:30px;margin-top:-5px;"/><fieldset style="width:515px;">
<form method="post" id="search_form"><div style="float:left;"><input name="search" id="search" placeholder="по перевозчику"><input name="in" id="in" placeholder="по г. загрузки"><input name="out" id="out" placeholder="по г. выгрузки"><br>
найти</div><input type="submit" class="search_btn" value="">
</form>
</fieldset></div>
</td></tr><tr><td colspan="2">


<div class="main_container">
<table id="table" align="center"></table>
        <div id="tablePager"></div>

</div>




</td></tr></table>


</td></tr></table>
</body>
</html>