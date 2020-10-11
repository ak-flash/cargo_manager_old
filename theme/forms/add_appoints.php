<script type="text/javascript">

$('#appoints').load('control/app_load.php?load_appoints=1');


// - - кнопка добавление назначений - сохранение - - >
$("#form_app").submit(function() {  
      var perfTimes = $("#form_app").serialize(); 
      $.post("control/add_app.php", perfTimes, function(data) {
      
      var arr = data.split(/[|]/);
      //if(arr[1]==1){}
      $('#result').html(arr[0]);
 
      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });$('#appoints').load('control/app_load.php?load_appoints=2');});
     return false;  
  });
  

 
 
$('#Save_app').button();
$('#btnClose_app').button();
$('#btnMain_Appoints').button();
$('#btnDop_Appoints').button();
</script>
<form method="post" id="form_app">

<div id="result"></div>


<fieldset><legend>Назначения:</legend>


<div id="appoints" style="height: 20em; overflow: auto">

</div>

<br>
<input type="button" id="btnMain_Appoints" onclick='$("#appoints").load("control/app_load.php?load_appoints=1");' value="Основные" style="font-size: 12px;">
<input type="button" id="btnDop_Appoints" onclick='$("#appoints").load("control/app_load.php?load_appoints=2");' value="Дополнительные" style="font-size: 12px;">

</fieldset>

<img src="data/img/app_add.png" style="float:left;margin-left:50px;margin-right:50px;margin-top:20px;"><br><fieldset style="width: 450px;"><legend><b>Добавить в дополнительную категорию:</b></legend>
<table><tr><td align="right">Назначение:</td><td><input name="app_name" id="app_name" style="width: 150px;"  placeholder="Укажите" class="input"></td><td align="right" width="80">Доступ:</td><td><select name="auth_level"  id="auth_level" style="width: 130px;" class="select">
  <option value="0">Выберите...</option>
  <option value="3">Бухгалтер&Директор</option>
  <option value="1">Директор</option></select></td></tr>
<tr><td align="right">Комментарий:</td><td colspan="3"><textarea cols="46" name="app_notify"></textarea></td></tr></table>

<br>
<input type="submit" id="Save_app" value="Добавить" style="width: 250px;">
<input type="button" id="btnClose_app" value="Закрыть" onclick="$('#fa_app').dialog('close');" style="float:right;width: 150px;">
</fieldset>
</form>