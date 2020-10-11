<script type="text/javascript" src="data/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript" src="data/javascript/jquery.autocomplete.js"></script>

<script type="text/javascript">
$.mask.definitions['~']='[+-]';
$('#postcode').mask('999999');
$('#Save_adr').button();
$('#btnClose_adr').button(); 
<?php 
if (@$_GET['adr_mode']!=""){
$mode=$_GET['adr_mode'];
$fmod=$_GET['fmod'];
echo '$("#adr_mode").val('.$mode.').change();$("#adr_mode_cl_tr").val('.$fmod.').change();';
}

if (@$_GET['adr_id']!=""){
$adr_id=$_GET['adr_id'];
include "../../config.php";

$query = "SELECT * FROM `adress` WHERE `Id`='".mysql_escape_string($adr_id)."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);

if($row['flat']=="0") $flat="-"; else $flat=$row['flat'];
if(addslashes($row['street']=="По ТТН")) echo '$("#ttn").attr("checked","checked");document.getElementById("dom").style.visibility = "hidden";document.getElementById("dom_extra").style.visibility = "hidden";document.getElementById("flat").style.visibility = "hidden";';
echo '$("#adr_mode_cl_tr").val('.$row['adr_mode_cl_tr'].').change();$("#postcode").val("'.addslashes($row['postcode']).'");$("#country").val("'.addslashes($row['country']).'");$("#obl").val("'.addslashes($row['obl']).'");$("#city").val("'.addslashes($row['city']).'");$("#street").val("'.addslashes($row['street']).'");$("#dom").val("'.addslashes($row['dom']).'");$("#dom_extra").val("'.$row['dom_extra'].'");$("#flat").val("'.$flat.'");$("#adr_place").val("'.addslashes($row['adr_place']).'");$("#adr_mode").val('.$row['adr_mode'].').change();$("#contact_name").val("'.addslashes($row['contact_name']).'");$("#contact_phone").val("'.addslashes($row['contact_phone']).'");$("#adr_temp_hidden").html("<input type=\"hidden\" name=\"adr_id\" id=\"adr_id\" value=\"'.$adr_id.'\"><input type=\"hidden\" name=\"edit\" value=\"1\">")';


}

?>

// - - кнопка добавление адреса - сохранение - - >
$("#form_adr").submit(function() { 
      var perfTimes = $("#form_adr").serialize(); 
      $.post("control/adr_add.php", perfTimes, function(data) {
      
      var arr = data.split(/[|]/);
      if(arr[1]==1){$('#form_adr').unbind(); $("#fa_adr").dialog('close');  }
      $('#result').html(arr[0]);
if(arr[4]==1){
$('#adr_in_validate').html('');
$('#adr_in_value').append('<div style="padding: 10px 10px 10px 10px;background: #ddd;border: 1px solid #bbb;width: 99%; " id="adr'+arr[2]+'"><input type="hidden" name="in_adr[]" id="in_adr'+arr[2]+'" value="'+arr[2]+'">'+arr[3]+'&nbsp;&nbsp;<div style="float:right;"><a href="#" onClick="javascript: $(\'#adr'+arr[2]+'\').remove();$(\'#in_adr'+arr[2]+'\').remove();$(this).remove();">[удалить]</a></div></div>');
} 

if(arr[4]==2){
$('#adr_out_validate').html('');
$('#adr_out_value').append('<div style="padding: 10px 10px 10px 10px;background: #ddd;border: 1px solid #bbb;width: 99%; " id="adr'+arr[2]+'"><input type="hidden" name="out_adr[]" id="out_adr'+arr[2]+'" value="'+arr[2]+'">'+arr[3]+'&nbsp;&nbsp;<div style="float:right;"><a href="#" onClick="javascript: $(\'#adr'+arr[2]+'\').remove();$(\'#out_adr'+arr[2]+'\').remove();$(this).remove();">[удалить]</a></div></div>');
} 

if(arr[4]==3){
//if($("#adr_cl_select_f_hidden").val()==0){
if(arr[5]==1){$('#adr_cl_select_f').setValue(''+arr[3]+'');
$('#adr_cl_select_f_hidden').val(arr[2]);
}
if(arr[5]==2){$('#adr_cl_select_u').setValue(''+arr[3]+'');
$('#adr_cl_select_u_hidden').val(arr[2]);}
}

if(arr[4]==4){
if($("#adr_tr_select_f_hidden").val()==0){
$('#adr_tr_select_f').setValue(''+arr[3]+'');
$('#adr_tr_select_f_hidden').val(arr[2]);
}
//$('#adr_tr_select_u').setValue(''+arr[3]+'');
//$('#adr_tr_select_u_hidden').val(arr[2]);
}

if(arr[4]==5){
$('#adr_w_select').setValue(''+arr[3]+'');
$('#adr_value').html('<input type="hidden" name="w_adr" id="w_adr" value="'+arr[2]+'">');
}

      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });jQuery("#table").trigger("reloadGrid");}); 
  return false;});
  
 
 
 function getAdrForm(){
if($("#adr_mode").val()==3||$("#adr_mode").val()==4){ document.getElementById('place').style.visibility = "hidden";
  document.getElementById('contact').style.visibility = "hidden";document.getElementById('adr_mode_cl_tr').style.visibility = "visible";} else {document.getElementById('place').style.visibility = "visible";document.getElementById('adr_mode_cl_tr').style.visibility = "hidden";
  document.getElementById('contact').style.visibility = "visible";}
if($("#adr_mode").val()==1||$("#adr_mode").val()==2){ document.getElementById('postcode').style.visibility = "hidden";} else {document.getElementById('postcode').style.visibility = "visible";}
if($("#adr_mode").val()==5){ document.getElementById('place').style.visibility = "hidden";
  document.getElementById('contact').style.visibility = "hidden";} 
  
}  


</script>
<!  - - форма добавления адреса  - - >  

<form method="post" id="form_adr">

<div id="adr_temp_hidden" style="display: none;"></div>

<fieldset align="center" style="margin-right:20px;width:90%;"><legend>Адрес:</legend>

<table align="center" ><tr><td align="right"><b>Вид адреса:</b></td><td align="left">
<select name="adr_mode"  id="adr_mode" class="select" onchange="getAdrForm();">
<option value="0" <?php if ($mode!=""){echo 'disabled';}?>>Выберите...</option>
<option value="1" <?php if ($mode!=""&&$mode!="1"){echo 'disabled';}?>>Загрузка</option>
<option value="2" <?php if ($mode!=""&&$mode!="2"){echo 'disabled';}?>>Выгрузка</option>
<option value="3" <?php if ($mode!=""&&$mode!="3"){echo 'disabled';}?>>Клиент</option>
<option value="4" <?php if ($mode!=""&&$mode!="4"){echo 'disabled';}?>>Перевозчик</option>
<option value="5" <?php if ($mode!=""&&$mode!="5"){echo 'disabled';}?>>Другое</option>
</select>

<select name="adr_mode_cl_tr"  id="adr_mode_cl_tr" class="select">
<option value="1" <?php if ($fmod!=""&&$fmod!="1"){echo 'disabled';}?>>Фактический</option>
<option value="2" <?php if ($fmod!=""&&$fmod!="2"){echo 'disabled';}?>>Юридический</option>

</select>

</td></tr><tr><td align="right">Индекс:</td><td align="left">
<input name="postcode" id="postcode" style="width: 55px;" placeholder="000000" class="input">&nbsp;&nbsp;<font color="#919191">почтовый</font>
</td></tr>
<tr><td align="right"><b>Страна:</b></td><td align="left">
<input name="country" id="country" style="width: 200px;" placeholder="Укажите название" class="input" value="Россия">
</td></tr>
<tr><td align="right"><b>Область:</b></td><td align="left">
<input name="obl" id="obl" style="width: 250px;"  placeholder="Укажите название" class="input">
</td></tr>
<tr><td align="right"><b>Нас.пункт:</b></td><td align="left">
<input name="city" id="city" style="width: 250px;"  placeholder="Укажите название" class="input">
</td></tr>
<tr><td align="right"><b>Улица:</b></td><td align="left">
<input name="street" id="street" style="width: 200px;"  placeholder="Укажите название" class="input"><input type="checkbox" name="ttn" id="ttn" onclick="if(this.checked){$('#street').val('По ТТН');$('#contact_name').val('По ТТН');$('#adr_place').val('По ТТН');$('#contact_phone').val('По ТТН');document.getElementById('dom').style.visibility = 'hidden';document.getElementById('dom_extra').style.visibility = 'hidden';document.getElementById('flat').style.visibility = 'hidden';} else {$('#street').val('');$('#contact_name').val('');$('#contact_phone').val('');$('#adr_place').val('');document.getElementById('dom').style.visibility = 'visible';document.getElementById('dom_extra').style.visibility = 'visible';document.getElementById('flat').style.visibility = 'visible';}" <?php if ($mode=="3"||$mode=="4"||$mode=="5"){echo 'disabled';}?>>По ТТН
</td></tr>
<tr><td align="center" colspan="2"><br><b>Дом:</b>
<input name="dom" id="dom" style="width: 30px;" class="input">
Строение:
<input name="dom_extra" id="dom_extra" style="width: 30px;" class="input">
Кв.(Офис):
<input name="flat" id="flat" style="width: 30px;" class="input">
<br></td></tr></table></fieldset>
<?php if (@$_GET['adr_mode']==""||@$_GET['adr_mode']=="1"||@$_GET['adr_mode']=="2"){
echo '<table align="center"><tr><td colspan="2">
<fieldset id="place"><legend>Место:</legend>
<input name="adr_place" id="adr_place" style="width: 350px;"  placeholder="Укажите" class="input">
</fieldset>
<fieldset id="contact"><legend>Контактное лицо:</legend>
<table><tr><td align="right" width="100">
Ф.И.О.:</td><td>
<input name="contact_name" id="contact_name" style="width: 250px;"  placeholder="Укажите Ф.И.О." class="input">
</td></tr>
<tr><td align="right">Телефон:</td><td>
<input name="contact_phone" id="contact_phone" style="width: 150px;"  placeholder="Укажите телефон" class="input">
</fieldset></td>
</tr></table>
</td></tr>';}?>

</table><div align="center">
<input type="submit" id="Save_adr" value="Сохранить" style="width: 240px;">
<input type="button" id="btnClose_adr" onclick="$('#fa_adr').dialog('close');" value="Закрыть" style="width: 150px;"></div>
</form>