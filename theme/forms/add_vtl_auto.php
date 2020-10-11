<?php include "../../config.php";?>

<script type="text/javascript">

$('#Save_vtl_auto').button();
$('#btnClose_vtl_auto').button();

$("#date_ve").datepicker();
$("#date_to").datepicker();
$("#date_s").datepicker();

$.mask.definitions['~']='[+-]';
$('#date_ve').mask('99/99/9999'); 
$('#date_to').mask('99/99/9999');
$('#date_s').mask('99/99/9999');



// - - кнопка добавление машины - сохранение - - >
$("#form_vtl_auto").submit(function() {  
      
      
      var perfTimes = $("#form_vtl_auto").serialize(); 
      $.post("control/auto/vtl_auto_add.php", perfTimes, function(data) {
      
      var arr = data.split(/[|]/);
      $('#result').html(arr[0]);
      if(arr[1]==1){$("#fa_vtl_auto").dialog("close");jQuery("#table_tr").trigger("reloadGrid");}

 
      
      $("#result").dialog({ title: 'Готово' },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });}); 
  return false;});     

<?php if(@$_GET['auto_id']!=""){
$car_id=(int)$_GET['auto_id'];
$query_car = "SELECT * FROM `vtl_auto` WHERE `id`='".$car_id."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car_info = mysql_fetch_row($result_car);
     
echo '$("#car_name").val("'.$car_info[1].'");$("#type_auto").val("'.$car_info[2].'").change();$("#car_num").val("'.$car_info[3].'");$("#date_ve").val("'.date('d/m/Y',strtotime($car_info[4])).'");$("#auto_v").val("'.$car_info[7].'");$("#auto_v").val("'.$car_info[7].'");$("#daykm").val("'.$car_info[8].'");$("#petrol_add").val("'.$car_info[6].'");$("#lpk").val("'.$car_info[5].'");$("#dop_auto").val("'.$car_info[15].'").change();$("#drv_auto").val("'.$car_info[11].'").change();';

if($car_info[14]!='0000-00-00') echo '$("#date_s").val("'.date('d/m/Y',strtotime($car_info[14])).'");';
if($car_info[13]!='0000-00-00') echo '$("#date_to").val("'.date('d/m/Y',strtotime($car_info[13])).'");';
}
?>    
 function setInput(){
 
if($("select#type_auto").val()==1){$("#daykm").attr("disabled","");$("#petrol_add").attr("disabled","");$("#lpk").attr("disabled","");$("#auto_v").attr("disabled","disabled");$("#car_load1").attr("disabled","disabled");$("#car_load2").attr("disabled","disabled");$("#car_load3").attr("disabled","disabled");$("#dop_auto").attr("disabled","");$("#drv_auto").attr("disabled","");} else {$("#car_load1").attr("disabled","");$("#car_load2").attr("disabled","");$("#car_load3").attr("disabled","");}

if($("select#type_auto").val()==2){$("#daykm").attr("disabled","disabled");$("#petrol_add").attr("disabled","disabled");$("#lpk").attr("disabled","disabled");$("#auto_v").attr("disabled","");$("#dop_auto").attr("disabled","disabled");$("#drv_auto").attr("disabled","disabled");}

if($("select#type_auto").val()==3){$("#daykm").attr("disabled","");$("#petrol_add").attr("disabled","");$("#lpk").attr("disabled","");$("#auto_v").attr("disabled","");$("#dop_auto").attr("disabled","");$("#drv_auto").attr("disabled","");} 

if($("select#type_auto").val()==4){$("#daykm").attr("disabled","disabled");$("#auto_v").attr("disabled","");$("#petrol_add").attr("disabled","disabled");$("#lpk").attr("disabled","disabled");$("#dop_auto").attr("disabled","disabled");$("#drv_auto").attr("disabled","disabled");}
 }
 
 
</script>
<form method="post" id="form_vtl_auto">


<table>

<tr><td width="180"><b>Тип транспорта:</b></td><td>

<?php 
if (@$_GET['edit']=="1"){echo '<input type="hidden" name="edit" id="edit" value="1"><input type="hidden" name="car_idd" id="car_idd" value="'.(int)$_GET['auto_id'].'">';}
?>
<select name="type_auto" id="type_auto" class="input" style="width:130px;" onchange="setInput();"><option value="0">Выберите...</option><option value="1">Тягач</option><option value="2">Полуприцеп</option><option value="3">Грузовик</option><option value="4">Прицеп</option></select>
</td></tr>
<tr><td><b>Марка:</b></td><td>
<input name="car_name" id="car_name" style="width: 150px;" placeholder="Укажите название" class="input" value="">
</td></tr>
<tr><td><b>Гос. номер:</b></td><td>
<input name="car_number" id="car_num" style="width: 150px;"  placeholder="формат А123ВС/34" class="input">
</td></tr>
<tr><td>
Дата ввода в экспл.:</td><td><input name="date_ve" id="date_ve" style="width: 80px;"  class="input" value="<?php echo date('d/m/Y');?>"></td></tr>
<tr><td>
Дата техосмотра:</td><td><input name="date_to" id="date_to" style="width: 80px;"  class="input" value=""></td></tr>
<tr><td>
Дата оконч.страховки:</td><td><input name="date_s" id="date_s" style="width: 80px;"  class="input" value=""></td></tr>
<tr><td>Обьем:</td><td>
<input name="auto_v" id="auto_v" style="width: 70px;"  placeholder="0" class="input" value="0"> м3
</td></tr>
<tr><td>Виды погрузки:</td><td height="40">
<input type="checkbox" name="car_load1" id="car_load1" value="1" <?php if($car_info[9]==1||$car_info[9]==4||$car_info[9]==6||$car_info[9]==7) echo "checked";?>>верх
&nbsp;&nbsp;<input type="checkbox" name="car_load2" id="car_load2" value="2" <?php if($car_info[9]==2||$car_info[9]==4||$car_info[9]==5||$car_info[9]==7) echo "checked";?>>зад
&nbsp;&nbsp;<input type="checkbox" name="car_load3" id="car_load3" value="3" <?php if($car_info[9]==3||$car_info[9]==5||$car_info[9]==6||$car_info[9]==7) echo "checked";?>>бок
</td></tr>


<tr><td><b>Полу/прицеп:</b></td><td><select name="dop_auto" style="width:140px;" id="dop_auto" onchange="" class="select"><option value="0">Выберите...</option>
<?php $query_car = "SELECT `id`,`name`,`number`,`type` FROM `vtl_auto` WHERE (`type`='2' OR `type`='4') AND `delete`='0'";
$result_car = mysql_query($query_car) or die(mysql_error());
while($car = mysql_fetch_row($result_car)) {
echo '<option value='.$car[0].'>'.$car[1].' ('.$car[2].')</option>';

}?></select>

</td></tr>

<tr><td><b>Водитель:</b></td><td><select name="drv_auto" style="width:140px;" id="drv_auto" onchange="" class="select"><option value="0">Выберите...</option>
<?php $query_drv = "SELECT `id`,`name` FROM `workers` WHERE `group`='5' AND `delete`='0'";
$result_drv = mysql_query($query_drv) or die(mysql_error());
while($drv = mysql_fetch_row($result_drv)) {
$pieces = explode(" ", $drv[1]);
$print_add_name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
echo '<option value='.$drv[0].'>'.$print_add_name.'</option>';

}?></select>

</td></tr>

<tr><td colspan="2">
<fieldset style="width:340px;">
<table><tr>
<td align="right">Норма суточного пробега:</td><td><input name="daykm" id="daykm" style="width: 100px;"  placeholder="0 км" class="input"> км</td>
</tr><tr>
<td align="right">Остаток топлива:</td><td><input name="petrol" id="petrol_add" style="width: 100px;"  placeholder="0 литров" class="input"> л</td>
</tr><tr>
<td align="right">Расход топлива:</td><td><input name="lpk" id="lpk" style="width: 100px;"  placeholder="0 литров" class="input"> л</td>
</tr>

<tr><td colspan="2">Инвентарь:<br><textarea cols="45" rows="4" name="inv"><?php echo $car_info[10];?></textarea></td></tr>
</table>

</fieldset>
</td></tr>
</table>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" id="Save_vtl_auto" value="Сохранить" style="width: 200px;">
<input type="button" id="btnClose_vtl_auto" onclick="$('#fa_vtl_auto').dialog('close');" value="Закрыть" style="width: 120px;">	

</form>