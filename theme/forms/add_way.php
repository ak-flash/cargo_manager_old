<?php 

include "../../config.php";

if (@$_GET['way_id']!=""){
$way_id=$_GET['way_id'];

$query = "SELECT * FROM `ways` WHERE `Id`='".$way_id."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);
}
?>

<script type="text/javascript">
<?php  
if ($way_id!="") {
echo '$("#in_city").val("'.$row['in_city'].'");$("#out_city").val("'.$row['out_city'].'");$("#in_load").val("'.$row['in_load'].'");$("#out_load").val("'.$row['out_load'].'");$("#times").val("'.$row['times'].'");';
}
?>


// - - кнопка добавление направления - сохранение - - >
$("#form_way").submit(function() {  
      var perfTimes = $("#form_way").serialize(); 
      $.post("control/add_way.php", perfTimes, function(data) {
      
      var arr = data.split(/[|]/);
      if(arr[1]==1){$('#form_way').unbind();$('#fa_way').dialog('close');}
      $('#result').html(arr[0]);
 
      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });jQuery("#table").trigger("reloadGrid");}); 
  return false;});
  

 
 $('#SaveWay').button();
$('#btnClose_way').button();


</script>
<form method="post" id="form_way">
<?php  if ($way_id!="") {
echo '<input type="hidden" name="way_id" value="'.$row['id'].'"><input type="hidden" name="edit" value="1">';
} ?>

<?php  
if ($way_id!=""&&$_GET['tr_id']!="") {echo '<input type="hidden" name="tr_id" value="'.$row['tr'].'">';} else {echo '<input type="hidden" name="tr_id" value="'.$_GET['tr_id'].'">';}
if ($way_id!=""&&$_GET['cl_id']!="") {echo '<input type="hidden" name="cl_id" value="'.$row['cl'].'">';} else {echo '<input type="hidden" name="cl_id" value="'.$_GET['cl_id'].'">';}
?>

<fieldset style="width:250px;float:left;margin-top:0;"><legend>Город:</legend>
<table><tr><td>Загрузка</td><td width="100px;"><input name="in_city" id="in_city" style="width: 150px;"  placeholder="Укажите" class="input"></td></tr>
<tr><td>Выгрузка</td><td><input name="out_city" id="out_city" style="width: 150px;"  placeholder="Укажите" class="input"></td></tr></table>
</fieldset>
<fieldset style="width:150px;margin-top:0;"><legend>Вид:</legend>
<table><tr><td>Погрузки</td><td><select name="car_in_load" id="car_in_load" class="select">
<option value="1">верхняя</option>
<option value="2">задняя</option>
<option value="3">боковая</option>
</select></td></tr>
<tr><td>Выгрузки</td><td><select name="car_out_load" id="car_out_load" class="select">
<option value="1">верхняя</option>
<option value="2">задняя</option>
<option value="3">боковая</option>
</select></td></tr></table>
</fieldset>

<fieldset style="width: 450px;"><legend>Периодичность:</legend>
<input name="times" id="times" style="width: 400px;"  placeholder="Укажите количество перевозок в месяц" class="input">
</fieldset><br>
<div align="center">
<input type="submit" id="SaveWay" value="Сохранить" style="width: 250px;">
<input type="button" id="btnClose_way" value="Закрыть" onclick="$('#fa_way').dialog('close');" style="width: 150px;"></div>
</form>