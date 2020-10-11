<?php include "../../config.php";?>
<script type="text/javascript" src="data/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript">

$('#Save_vtl_repair').button();
$('#btnClose_vtl_repair').button();

$("#date_repair").datepicker();

$.mask.definitions['~']='[+-]';
$('#date_repair').mask('99/99/9999'); 





// - - кнопка добавление машины - сохранение - - >
$("#form_vtl_repair").submit(function() {  
      
      
      var perfTimes = $("#form_vtl_repair").serialize(); 
      $.post("control/auto/vtl_repair_add.php", perfTimes, function(data) {
      
      var arr = data.split(/[|]/);
      $('#result').html(arr[0]);
      if(arr[1]==1){$("#fa_vtl_repair").dialog("close");jQuery("#table_tr_repair").trigger("reloadGrid");}

 
      
      $("#result").dialog({ title: 'Готово' },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });}); 
  return false;});     

<?php if(@$_GET['repair_id']!=""){
$repair_id=(int)$_GET['repair_id'];
$query_repair = "SELECT * FROM `vtl_repair` WHERE `Id`='".$repair_id."'";
$result_repair = mysql_query($query_repair) or die(mysql_error());
$repair_info = mysql_fetch_row($result_repair);
     
echo '$("#repair_auto").val("'.$repair_info[2].'").change();$("#repair_drv").val("'.$repair_info[7].'").change();$("#date_repair").val("'.date('d/m/Y',strtotime($repair_info[1])).'");$("#repair_area").val("'.$repair_info[3].'").change();$("#cash_repair").val("'.($repair_info[5]/100).'");';
}
?>    
 
 
 
</script>
<form method="post" id="form_vtl_repair">


<table align="center">
<tr><td>
Дата:</td><td><input name="date_repair" id="date_repair" style="width: 80px;"  class="input" value="<?php echo date('d/m/Y');?>"></td></tr>


<?php 
if (@$_GET['edit']=="1"){echo '<input type="hidden" name="edit" id="edit" value="1"><input type="hidden" name="repair_idd" id="repair_idd" value="'.(int)$_GET['repair_id'].'">';}
?>
<tr><td><b>ТС:</b></td><td>
<?php $query_car = "SELECT `id`,`name`,`number`,`type` FROM `vtl_auto` WHERE `delete`='0'";
$result_car = mysql_query($query_car) or die(mysql_error());

echo '<select name="repair_auto" style="width:240px;" id="repair_auto" onchange="" class="select"><option value="0">Выберите...</option>';

while($car = mysql_fetch_row($result_car)) {
switch ($car[3]) {
case '1': $type='Т';break;
case '2': $type='П';break;
case '3': $type='Г';break;
case '4': $type='П';break;

} 
echo '<option value='.$car[0].'>'.$type.'. | '.$car[1].' | '.$car[2].'</option>';
}
echo '</select>';?>
</td></tr>
<tr><td><b>Водитель:</b></td><td>
<?php $query_drv = "SELECT `id`,`name` FROM `workers` WHERE `delete`='0' AND `group`='5'";
$result_drv = mysql_query($query_drv) or die(mysql_error());

echo '<select name="repair_drv" style="" id="repair_drv" onchange="" class="select"><option value="0">Выберите...</option>';

while($drv = mysql_fetch_row($result_drv)) {
$pieces = explode(" ", $drv[1]);
$driver=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
 
echo '<option value='.$drv[0].'>'.$driver.'</option>';
}
echo '</select>';?>
</td></tr>

<tr><td><b>Назначение:</b></td><td>
<select name="repair_area" id="repair_area" class="select">
   <option value="0">Выберите...</option>
     <option value="6">Автозапчасти</option>
   <option value="7">Шиномонтаж</option>
   <option value="8">Услуги ремонта</option>
   <option value="11">Инструменты</option>
    </select>
</td></tr>



<tr><td><b>Объём:</b></td><td><textarea cols="35" rows="3" name="repair_details"><?php echo $repair_info[4];?></textarea></td></tr>
<tr><td><b>Стоимость:</b></td><td>
<input name="cash_repair" id="cash_repair" style="width: 80px;" onchange="$('#cash_repair').val($('#cash_repair').val().replace(/,+/,'.'));" class="input" value=""> руб.
</td></tr>
</table>

</fieldset>
</td></tr>
</table>
<hr>
&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" id="Save_vtl_repair" value="Сохранить" style="width: 200px;">
<input type="button" id="btnClose_vtl_repair" onclick="$('#fa_vtl_repair').dialog('close');" value="Закрыть" style="width: 120px;">	

</form>