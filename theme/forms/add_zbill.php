<script type="text/javascript">

  
$("#zyear").val(<?php echo date("Y");?>).change();
$("#zmonth").val(<?php echo ((int)date("m")-1);?>).change();
 $("#zyear_load").val(<?php echo date("Y");?>).change();
$("#zmonth_load").val(<?php echo ((int)date("m")-1);?>).change();
 
 $('#zbill').load('control/zbill_load.php?zyear='+$("#zyear_load").val()+'&zmonth='+$("#zmonth_load").val());
 
$('#Save_zbill').button();
$('#btnClose_zbill').button();
$('#btnDischarge_zbill').button();
$('#Delete_zbill').button();
$('#btnReload_zbill').button();
// - - форма добавления - сохранение  - - >
$("#form_zpay_add").submit(function() {

 var perfTimes = $("#form_zpay_add").serialize();

$.post("control/zpay_add.php", perfTimes, function(data) {
     
     var arr = data.split(/[|]/);
      if(arr[1]==1){$('#zbill').load('control/zbill_load.php?zyear='+$("#zyear_load").val()+'&zmonth='+$("#zmonth_load").val());
      
      $("#zcash").val("");
      $("#zway").val(0).change();
      $("#zyear").val(<?php echo date("Y");?>).change();
$("#zmonth").val(<?php echo ((int)date("m")-1);?>).change();
$("#zworker").val(0).change();
$("#zedit").val("0");
 
 /*     
 if(arr[7]!=1){$('#result').html('Создать платёж?');$('#result').dialog({ title: 'Внимание' },{ modal: true },{ resizable: false },{ buttons: [{text: 'Да',click: function() {$.post('control/zpay_add.php?mode=pay_add&cash='+arr[2]+'&worker='+arr[3]+'&way='+arr[4]+'&month='+arr[5]+'&year='+arr[6], function(data){$(this).dialog('close');$('#result').dialog("close");$('#result').html(data);
      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ width: 400 },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");$('#result').dialog("close");} } });});}},{text: 'Нет',click: function() {$(this).dialog('close');$('#result').dialog("close");}}] });}
      
 */     
      
      }
      else {

      $('#result').html(arr[0]);
      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ width: 400 },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");} } });
      }
   


      }); 

     
      
  return false;
   });   


</script>


<div id="result"></div>
<font size="4"><b>Просмотр данных за </b></font><select name="zmonth_load" style="width:100px;" id="zmonth_load" class="select" onchange="$('#zbill').load('/control/zbill_load.php?zyear='+$('#zyear_load').val()+'&zmonth='+$('#zmonth_load').val());">
<option value="0">...</option>
<option value="1">Январь</option>
  <option value="2">Февраль</option>
  <option value="3">Март</option>
  <option value="4">Апрель</option>
  <option value="5">Май</option>
  <option value="6">Июнь</option>
  <option value="7">Июль</option>
  <option value="8">Август</option>
  <option value="9">Сентябрь</option>
  <option value="10">Октябрь</option>
  <option value="11">Ноябрь</option>
  <option value="12">Декабрь</option></select>&nbsp;&nbsp;<select name="zyear_load" style="" id="zyear_load" class="select" onchange="$('#zbill').load('control/zbill_load.php?zyear='+$('#zyear_load').val()+'&zmonth='+$('#zmonth_load').val());">
  


<?php $i=-1;
while ($i <= 1) {
echo '<option value="'.(date("Y")+$i).'">'.(date("Y")+$i).'</option>';
$i++; 
 }?>
</select>&nbsp;&nbsp;<input type="button" id="btnReload_zbill" value="Обновить" onclick="$('#zbill').load('control/zbill_load.php?zyear='+$('#zyear_load').val()+'&zmonth='+$('#zmonth_load').val());">

  <input type="button" id="btnDischarge_zbill" value="Уволенные" onclick="$('#zbill').load('control/zbill_load.php?mode=fire&zyear='+$('#zyear_load').val()+'&zmonth='+$('#zmonth_load').val());" style="width: 150px;float:right;">

<br>

<form method="post" id="form_zpay_add">
<input type="hidden" name="zedit" id="zedit" value="0">
<br><fieldset style="width:95%;"><legend>Добавить:</legend>
<table cellspacing="5">
<tr><td align="right">Сотрудник:</td><td><select name="zworker" id="zworker" style="width:165px;" class="select" ><option value="0">Выберите...</option>
<?php 
    
    include "../../config.php";
    
$query = "SELECT `name`,`id` FROM `workers` WHERE `delete`='0' ORDER BY `name` ASC";
$result = mysql_query($query) or die(mysql_error());
while($user= mysql_fetch_row($result)) {

$pieces = explode(" ", $user[0]);
$print_add_name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
echo '<option value='.$user[1].'>'.$print_add_name.'</option>';

} ?>
</select></td><td align="right" width="70">Статья:</td><td><select name="zway" id="zway" style="width:150px;" class="select"><option value="1">Переработка</option><option value="2">Вознаграждение за подкл.</option><option value="3">Штрафы</option><option value="4">Удержано</option><option value="5">На карту</option><option value="6">Аванс</option></select></td><td rowspan="2" align="right" width="285"><input type="submit" id="Save_zbill" value="Добавить" style="width: 135px;height:60px;margin-right:5px;">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="button5" id="Delete_zbill" value="Удалить" style="float:right;" onclick="$('#zbill').load('/control/zbill_load.php?mode=delete&zedit='+$('#zedit').val());"></td></tr><tr><td align="right">Дата:</td><td><select name="zmonth" style="width:100px;" id="zmonth" class="select">
<option value="0">...</option>
<option value="1">Январь</option>
  <option value="2">Февраль</option>
  <option value="3">Март</option>
  <option value="4">Апрель</option>
  <option value="5">Май</option>
  <option value="6">Июнь</option>
  <option value="7">Июль</option>
  <option value="8">Август</option>
  <option value="9">Сентябрь</option>
  <option value="10">Октябрь</option>
  <option value="11">Ноябрь</option>
  <option value="12">Декабрь</option></select>&nbsp;&nbsp;<select name="zyear" style="" id="zyear" class="select">

<?php $i=-1;
while ($i <= 1) {
echo '<option value="'.(date("Y")+$i).'">'.(date("Y")+$i).'</option>';
$i++; 
 }?>
</select></td><td align="right">Сумма:</td><td><input name="zcash" id="zcash" style="width: 100px;"  placeholder="" class="input" onchange="$(this).val($(this).val().replace(/,+/,'.'));"> руб.</td></tr>
</table></fieldset>
</form>


<br>


<div id="zbill" style="height: 25em; overflow: auto"></div>

<br>
<input type="button" id="btnClose_zbill" value="Закрыть" onclick="$('#fa_bill').dialog('close');" style="width: 150px;float:right;">
<br>
