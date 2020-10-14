<?php 
    session_start();
    include "config.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Настройки АСТП</title>
<?php include_once("data/header.html");?>
<script type="text/javascript">
$(function(){
$("#control_menu_css").css({"background": "#BCBCBC", "color":"#000","text-shadow": "1px 2px 2px #FFF"});
$('#import').button();
$('#btn_save').button();
$('#btn_export').button();
$('#btn_import').button();
$("#importsPays").submit(function() { 

  $('#result').html('');
  validate=true;
  if($("#filename").val()==''){$('#result').append('Выберите файл!<br>');validate=false;}
  if($("#receiver").val()==''){$('#result').append('Заполните Получателя!<br>');validate=false;}
  if($("#pays_appoints").val()==0){$('#result').append('Выберите назначение платежей!<br>');validate=false;}
  	if(!validate){$("#result").dialog({ title: 'Внимание' },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");}}});return false;} 
  	


});


});

function backupDB(token){

  document.getElementById("btnBackup").disabled = true; 
  
  $.post("tools/backup/db.php", "token="+token)
    .done(function(data) {
          toastr.success(data);      
  });  
}

</script>

</head>
<body>	
<?php require_once("data/menu.html");
$query_date = "SELECT * FROM `settings`";
$result_date = mysql_query($query_date) or die(mysql_error());
$dates_num= mysql_fetch_row($result_date);

?>

<div id="result"></div>
</div><div id="fa_app" style="background:#F8F8F8;"></div>


<fieldset style="float:left;width:320px;margin:20px;font-size:15px;"><legend>Настройки</legend>
<table><tr>
<td width="240">Рабочих дней в текущем месяце: </td><td><input name="now_month" id="now_month" style="width: 25px;" class="input" value="<?php echo $dates_num[0];?>"></td></tr>
<tr>
<td>Рабочих дней в прошлом месяце: </td><td><input name="last_month" id="last_month" style="width: 25px;" class="input" value="<?php echo $dates_num[1];?>"></td></tr>
<tr>
<td>Сегодня (по счету раб.день): </td><td><input name="now_date" id="now_date" style="width: 25px;" class="input" value="<?php echo $dates_num[3];?>"></td></tr>
<tr>
<td><a href="#" id="btn_save" onclick='$.post("control/admin.php?mode=save_date&now_month="+$("#now_month").val()+"&last_month="+$("#last_month").val()+"&now_date="+$("#now_date").val(), function(data) {alert(data);});' style="font-size:16px;">Сохранить</a><td></td></tr>
</table>
</fieldset>

<fieldset style="width:520px;margin:20px;font-size:15px;"><legend>Дополнительно: </legend>
&nbsp;&nbsp;<button class="button3" id="btnBackup" onclick="backupDB('cf2efc22cf2')" style="width:230px;">Сохранить БАЗУ данных</button>
&nbsp;&nbsp;|&nbsp;&nbsp;
<a class="button" id="btnAppoints" href="javascript:" onclick='$("#fa_app").load("theme/forms/add_appoints.php");$("#fa_app").dialog({ title: "Назначения платежей" },{width: 950,height: 710,modal: true,resizable: false});' style="width:170px;">Категории платежей</a>
</fieldset>

<!--------

<div id="payments_list">
<fieldset style="float:left;width:420px;margin:20px;font-size:15px;"><legend>Импорт платежей</legend>
      <form method="post" action="import.php" id="importsPays" enctype="multipart/form-data">
<table><tr>
<td>Файл: </td><td><input type="file" name="filename" id="filename" class="select"></td></tr>
<tr>
<td>Получатель: </td><td><input name="receiver" id="receiver" style="width: 155px;" placeholder="без кавычек и префиксов" class="input"></td></tr>
<tr>
<td width="150">Назначение платежей: </td><td><select name="pays_appoints" style="width:155px;" id="pays_appoints" class="select"><option value="0">Выберите...</option>
  <?php
$query = "SELECT `id`,`app` FROM `pays_appoints` WHERE `auth_id`='3'";
$result = mysql_query($query) or die(mysql_error());
 while($pays_app= mysql_fetch_row($result)) {
 if($pays_app[0]>6) echo '<option value="'.$pays_app[0].'">'.$pays_app[1].'</option>';
 

 
 } 
  
  ?>

  
  </select>
   </td></tr>
  <tr>
<td></td><td><br><input type="submit" id="import" value="Загрузить"></td></tr>     
</table>   
  
      </form>
</fieldset>
</div>

<fieldset style="width:40%;font-size:15px;"><legend>Экспорт ЗАЯВОК</legend>
<table width="100%"> <tr>
<td>Номера заявок: </td><td><input name="export_ord" id="export_ord" style="width: 255px;" placeholder="через запятую без пробелов, только цифры" class="input"></td><td align="right"><a href="#" id="btn_export" onclick='window.open("tools/sync.php?type=export&orders="+$("#export_ord").val());' style="font-size:16px;">Экспортировать</a><td></tr> </table>
  

</fieldset>

<fieldset style="width:40%;font-size:15px;"><legend>Импорт ЗАЯВОК</legend>
  <form method="post" action="import_ord.php" id="importsOrders" enctype="multipart/form-data">
<table width="100%"><tr>
<td>Файл заявок: </td><td><input type="file" name="import_ord" id="import_ord" class="input"></td>
<td align="right"><input type="submit" id="btn_import" value="Импортировать" style="font-size:16px;"></td></tr>     
</table>   
  
      </form> 

</fieldset>
---!>


</body>
</html>