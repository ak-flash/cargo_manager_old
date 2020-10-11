<?php 

include "../../config.php";
session_start();
if (@$_GET['tr']!=""){
$tr=$_GET['tr'];

$query = "SELECT * FROM `transporters` WHERE `Id`='".$tr."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);
}
?>
<script type="text/javascript" src="data/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript">
$.mask.definitions['~']='[+-]';





$('#Save_tr').button();
$('#btnClose_tr').button();
$('#btnAdd_car_add_tr').button();
$("#tr_tabs").tabs({fx: {opacity:'toggle', duration:1}});       

$('#car_tabs').tabs({
select: function(event, ui) {


}
});

$('#tr_select_add').flexbox('control/tr_search.php?mmm=tr_add', {
    width: 300,paging: {  
        summaryTemplate: 'Показать {start}-{end} из {total} перевозчиков',
        pageSize: 5 
    } , 
hiddenValue: 'id',
<?php if ($tr!="") {
echo "initialValue: '".addslashes($row['name'])."',";
}?>
onSelect: function() {

$('#result').html('Перевозчик уже имеется в базе данных!');
      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ width: 400 },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");}}});
}});


$("#btnAdd_car_add_tr").click(function(){
$("#fa_car").load("theme/forms/add_car.php?tr=<?php echo $row['id'];?>&mode=transp"); 
    $("#fa_car").dialog({ title: 'Новый транспорт' },{
			width: 480,height: 740,
			modal: true,resizable: false
});
});

// - - кнопка добавление перевозчика - сохранение - - >
$("#form_tr").submit(function() {  
  $('#tr_name').val($('#tr_select_add_hidden').val());  
     
        var perfTimes = $("#form_tr").serialize(); 
      $.post("control/tr_add.php", perfTimes, function(data) {
      
      var arr = data.split(/[|]/);
     
      if(arr[1]==1){$("#fa_tr").dialog("close");$('#form_tr').unbind();
      jQuery("#table").trigger("reloadGrid");
      
if (document.getElementById("car_select")){

     
      $('#tr_select').setValue(''+arr[3]+'');
$('#tr_select_hidden').val(arr[2]);
$('#tr_receive_select').setValue(''+arr[3]+'');

$('#tr_nds').val(arr[4]).change();
$('#tr_pref_temp').html(arr[5]);
$('#tr_tfpay').val(arr[6]);
$('#tr_event').val(arr[7]).change();
$('#temp_tr_nds').val(arr[4]);

document.getElementById('car_add').style.visibility = "visible";
$('#hidden_value_tr').html('<input type="hidden" name="transporter" id="transporter" value="'+arr[2]+'"><input type="hidden" name="tr_pref" id="tr_pref" value="'+arr[8]+'"><input type="hidden" name="tr_cont" id="tr_cont" value="'+arr[9]+'">');
$("#tr_autopark").attr("disabled","");

$("#car_select").load("control/car.php?id="+arr[2]); 

$('#hidden_value_tr_receive').html('<input type="hidden" name="tr_receive" id="tr_receive" value="'+arr[2]+'">');
} 

$('#result').html("Распечатать договор с перевозчиком?");  
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ width: 400 },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");
      if(arr[10]==1){window.location.href='control/print_c.php?mode=tr&id='+arr[11];}
      else {window.location.href='control/print_c.php?mode=tr&id=<?php  if ($tr!="") {echo base64_encode($tr);}?>';}
      $('#result').html(arr[0]);
      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ width: 400 },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");} } });
      } } });
      
       

    } else {
    $('#result').html(data);  
    $("#result").dialog({ title: 'Готово' },{ modal: true },{ width: 400 },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");} } });}
      
        
      
      


      }); 
  return false;});




function getCars(){
$('#car_info').load('/control/car_load.php?tr=<?php echo $row['id'];?>');
}



$('#adr_tr_select_f').flexbox('/control/adr_search.php?mode=4&fmod=1', {

<?php if ($tr!="") {
$query_adress = "SELECT * FROM `adress` WHERE `id`='".$row['tr_adr_f']."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());

$adress = mysql_fetch_row($result_adress);
if($adress[8]=="0") $flat=""; else $flat=" - ".$adress[8];
if($adress[6]=="0") $dom=""; else $dom='д.'.$adress[6].'/'.$adress[7];
if($adress[1]=="0") $postcode=""; else $postcode=$adress[1];
echo "initialValue: '".$postcode.' '.$adress[2].' '.$adress[3].' обл. '.$adress[4].' ул.'.$adress[5].' '.$dom.$flat."',";
}?>

watermark: 'Поиск по адресам...',
    paging: {  
        summaryTemplate: 'Показать {start}-{end} из {total} адресов',
        pageSize: 3  
    } , 
    width: 620,
    

hiddenValue: 'id',
onSelect: function() {


}
});




$('#adr_tr_select_u').flexbox('control/adr_search.php?mode=4&fmod=2', {

<?php if ($tr!="") {
$query_adress = "SELECT * FROM `adress` WHERE `id`='".$row['tr_adr_u']."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());

$adress = mysql_fetch_row($result_adress);
if($adress[8]=="0") $flat=""; else $flat=" - ".$adress[8];
if($adress[6]=="0") $dom=""; else $dom='д.'.$adress[6].'/'.$adress[7];
if($adress[1]=="0") $postcode=""; else $postcode=$adress[1];
echo "initialValue: '".$postcode.' '.$adress[2].' '.$adress[3].' обл. '.$adress[4].' ул.'.$adress[5].' '.$dom.$flat."',";
}?>

watermark: 'Поиск по адресам...',
    paging: {  
        summaryTemplate: 'Показать {start}-{end} из {total} адресов',
        pageSize: 3  
    } , 
    width: 620,
    

hiddenValue: 'id',
onSelect: function() {


}
});

<?php if ($tr!="") {
if($row['contract']!="") echo '$("#contract").val("'.addslashes($row['contract']).'");'; else echo '$("#contract").val("'.addslashes($row['id']).'");';
echo '$("#adr_tr_select_u_hidden").val("'.$row['tr_adr_u'].'");$("#adr_tr_select_f_hidden").val("'.$row['tr_adr_f'].'");$("#tr_pref_add").val('.addslashes($row['pref']).').change();$("#tr_nds_add").val('.$row['nds'].').change();$("#tr_cont_add").val('.(int)$row['tr_cont'].').change();$("#tr_manager_add").val('.$row['tr_manager'].').change(); $("#tr_code_ati").val("'.addslashes($row['tr_code_ati']).'");$("#tr_name").val("'.addslashes($row['name']).'");$("#tr_point").val('.$row['tr_point'].').change();$("#tr_time").val('.$row['tr_time'].');$("#tr_support").val("'.$row['tr_support'].'");$("#tr_phone").val("'.$row['tr_phone'].'");$("#tr_mail").val("'.$row['tr_mail'].'");getCars();$("#tr_kpp").val("'.$row['tr_kpp'].'");$("#tr_inn").val("'.$row['tr_inn'].'");$("#tr_ogrn").val("'.$row['tr_ogrn'].'");$("#tr_rs").val("'.$row['tr_rs'].'");$("#tr_bank").val("'.addslashes($row['tr_bank']).'");$("#tr_bik").val("'.$row['tr_bik'].'");$("#tr_ks").val("'.$row['tr_ks'].'");$("#tr_orderform").val('.$row['tr_orderform'].').change();$("#contract").val("'.addslashes($row['contract']).'");$("#tr_chief").val("'.$row['tr_chief'].'");$("#tr_dchief").val("'.$row['tr_dchief'].'");$("#tr_dsupport").val("'.$row['tr_dsupport'].'");$("#tr_pref_phone").val('.$row['tr_pref_phone'].');$("#tr_ochief").val("'.addslashes($row['tr_ochief']).'");$("#tr_chief_contract").val("'.addslashes($row['tr_chief_contract']).'");$("#tr_dchief_contract").val("'.addslashes($row['tr_dchief_contract']).'");$("#tr_icq").val("'.addslashes($row['tr_icq']).'");';

if((int)$row['pref']==3)echo "document.getElementById('tr_dchief').style.visibility = 'hidden';document.getElementById('tr_dchief_contract').style.visibility = 'hidden';$('#tr_inn').mask('999999999999');";

}?>  
</script>
<form method="post" id="form_tr">
<?php  if ($tr!="") {

echo '<input type="hidden" name="tr_id" value="'.$row['id'].'"><input type="hidden" name="edit" value="1">';
} ?>

<div id="tr_tabs">
<ul>
		<li><a href="#tr_tabs-1">Перевозчик</a></li>
		<li><a href="#tr_tabs-2">Реквизиты</a></li>
		<li><a href="#tr_tabs-3">Заявки</a></li>
		<li><a href="#tr_tabs-4">Контакты</a></li>
<?php  if ($tr!="") {echo '<li><a href="#tr_tabs-5">Транспорт</a></li>';} ?>
	</ul>

<div id="tr_tabs-1">
<div style="height:24em;width: 101%; overflow: auto">
<table>
<tr><td>Наименование:</td><td>
<div id="tr_select_add"></div><input type="hidden" name="tr_name" id="tr_name" value="">
</td></tr>
<tr><td>Форма организации:</td><td>
<select name="tr_pref" style="width:70px;" id="tr_pref_add" class="select">
  <option value="1">ООО</option>
  <option value="2">ОАО</option>
    <option value="7">АО</option>
	<option value="3">ИП</option>  
  <option value="4">ЗАО</option>
    <option value="6">Физ.Л</option></select>
  <option value="5"></option>
</select>
  </select>
</td></tr>

 <tr><td>Адрес (фактич.):</td><td width="640">
<div id="adr_tr_select_f" style="float:left;"></div>
</td>
<td width="140">
<a id="btnAdd_adress_tr_f" href="#" style="font-size: 20px;text-decoration: none;float:left;" onclick='$("#fa_adr").load("theme/forms/add_adr.php?adr_mode=4&fmod=1", function() {$("#fa_adr").dialog({ title: "Новый фактический адрес перевозчика" },{width: 480,height: 400,modal: true,resizable: false});}); '>&nbsp;&nbsp;<img src="data/img/plus.png"></a>
<a id="btnEdit_adress_tr_f" href="#" style="font-size: 20px;text-decoration: none;float:left;" onclick='if($("#adr_tr_select_f_hidden").val()!=""){$("#fa_adr").load("theme/forms/add_adr.php?adr_mode=4&fmod=1&adr_id="+$("#adr_tr_select_f_hidden").val(), function() {$("#fa_adr").dialog({ title: "Редактировать фактический адрес перевозчика" },{width: 480,height: 400,modal: true,resizable: false});}); } else {alert("Выберите адрес перевозчика!");}'>&nbsp;&nbsp;<img src="data/img/pencil.png">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>


</td></tr>
<tr><td>Адрес (юридич.):</td><td width="640">
<div id="adr_tr_select_u" style="float:left;"></div>
</td>
<td><a id="btnAdd_adress_tr_u" href="#" style="font-size: 20px;text-decoration: none;float:left;" onclick='$("#fa_adr").load("theme/forms/add_adr.php?adr_mode=4&fmod=2", function() {$("#fa_adr").dialog({ title: "Новый юридический адрес перевозчика" },{width: 480,height: 400,modal: true,resizable: false});}); '>&nbsp;&nbsp;<img src="data/img/plus.png"></a>
<a id="btnEdit_adress_tr_u" href="#" style="font-size: 20px;text-decoration: none;float:left;" onclick='if($("#adr_tr_select_u_hidden").val()!=""){$("#fa_adr").load("theme/forms/add_adr.php?adr_mode=4&fmod=1&adr_id="+$("#adr_tr_select_u_hidden").val(), function() {$("#fa_adr").dialog({ title: "Редактировать юридический адрес перевозчика" },{width: 480,height: 400,modal: true,resizable: false});});} else {alert("Выберите адрес перевозчика!");} '>&nbsp;&nbsp;<img src="data/img/pencil.png">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>


</td></tr>   
<tr><td>Контактёр:</td><td>
<select name="tr_cont"  id="tr_cont_add" class="select">
 <?php $query = "SELECT `id`,`name` FROM `company` WHERE `active`='1'";
$result = mysql_query($query) or die(mysql_error());
while($company= mysql_fetch_row($result)) {
if($company[0]!=1) {
    $company_list_in_select = $company_list_in_select.'<option value='.$company[0].'>'.$company[1].'</option>'; 
  }
} 
echo $company_list_in_select;

?> 
</select>&nbsp;&nbsp;№ договора:&nbsp;
<input name="contract"  id="contract" class="select" placeholder="Укажите номер" style="width: 80px;" class="input" value="">
&nbsp;&nbsp;&nbsp;&nbsp;Код в АТИ:&nbsp;
<input name="tr_code_ati"  id="tr_code_ati" class="select" placeholder="Укажите код" style="width: 80px;" class="input" value="">

</td></tr>
<tr><td colspan="3">
<fieldset><legend>Оплата:</legend>
<table><tr><td align="left" colspan="2">
Система налогообложения:&nbsp;&nbsp;&nbsp;<select name="tr_nds"  id="tr_nds_add" class="select">
  <option value="0">без НДС</option>
  <option value="1">с НДС</option>
  <option value="2">НАЛ</option></select>
  </td></tr><tr>
<td align="right" width="100">Точка оплаты:</td><td ><select name="tr_point"  id="tr_point" class="select">
  <option value="0">Выберите...</option>
  <option value="1">Загрузка</option>
  <option value="2">Выгрузка</option>
  <option value="3">Поступление факсимильных документов</option>
  <option value="4">Поступление оригинальных документов</option>
  </select></td>
  <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Период расчётов:</td><td colspan="2"><input name="tr_time" id="tr_time" class="select" style="width: 50px;"  placeholder="0" class="input" value=""> дн. * - календарных дней.</td></tr>
</fieldset></table>
</td></tr>
</table>
</div></div>
<div id="tr_tabs-2">
<div style="height: 21em;width: 101%; overflow: auto">
<fieldset><legend>Реквизиты:</legend>
<table>
<tr><td align="right" width="90">ИНН:</td><td><input type="number" name="tr_inn" id="tr_inn" style="width: 120px;" placeholder="" class="input" value="" onchange='$("#t_inn").html($(this).val().length);'> <font color="red" size="4">*</font>&nbsp;&nbsp;<div id="t_inn" style="display:inline;"></div></td></tr>
<tr><td align="right">КПП:</td><td><input type="number" name="tr_kpp" id="tr_kpp" style="width: 100px;" placeholder="" class="input" onchange='$("#t_kpp").html($(this).val().length);'>&nbsp;&nbsp;<div id="t_kpp" style="display:inline;"></div></td></tr>
<tr><td align="right">ОГРН:</td><td><input type="number" name="tr_ogrn" id="tr_ogrn" style="width: 130px;" placeholder="" class="input" onchange='$("#t_ogrn").html($(this).val().length);'>&nbsp;&nbsp;<div id="t_ogrn" style="display:inline;"></div></td></tr>
<tr><td align="right">р/сч:</td><td><input type="number" name="tr_rs" id="tr_rs" style="width: 200px;"  placeholder="" class="input" onchange='$("#t_rs").html($(this).val().length);'> <font color="red" size="4">*</font>&nbsp;&nbsp;<div id="t_rs" style="display:inline;"></div></td></tr>
<tr><td align="right">Банк в :</td><td><input name="tr_bank" id="tr_bank" style="width: 400px;"  placeholder="" class="input"> <font color="red" size="4">*</font></td></tr>
<tr><td align="right">БИК:</td><td><input type="number" name="tr_bik" id="tr_bik" style="width: 100px;"  placeholder="" class="input" onchange='$("#t_bik").html($(this).val().length);'> <font color="red" size="4">*</font>&nbsp;&nbsp;<div id="t_bik" style="display:inline;"></div></td></tr>
<tr><td align="right">к/сч:</td><td><input type="number" name="tr_ks" id="tr_ks" style="width: 200px;"  placeholder="" class="input" onchange='$("#t_ks").html($(this).val().length);'>&nbsp;&nbsp;<div id="t_ks" style="display:inline;"></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Поля, помеченные <font color="red" size="4">*</font> - обязательны для заполнения</td></tr>

</table>
</fieldset>

</div></div>
<div id="tr_tabs-3">
<div style="height: 21em;width: 101%; overflow: auto">
<table>
<tr><td align="right">Менеджер:</td><td>
<select name="tr_manager" id="tr_manager_add" style="width:174px;" class="select">
   <?php $query = "SELECT `id`,`name` FROM `workers` WHERE `group`!='5' AND `delete`='0'";
$result = mysql_query($query) or die(mysql_error());
while($user= mysql_fetch_row($result)) {
$pieces = explode(" ", $user[1]);
$manager=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";

if($tr!=""){
if($user[0]==$row['tr_manager']&&$_SESSION["group"]!='2'&&$_SESSION["group"]!='1'){echo '<option value="'.$user[0].'">'.$manager.'</option>';}
if($_SESSION["group"]==2||$_SESSION["group"]==1){echo '<option value="'.$user[0].'">'.$manager.'</option>';}
} else {if($user[0]==$_SESSION["user_id"]&&$_SESSION["group"]!='2'&&$_SESSION["group"]!='1'){echo '<option value="'.$user[0].'">'.$manager.'</option>';}if($_SESSION["group"]==2||$_SESSION["group"]==1){echo '<option value="'.$user[0].'">'.$manager.'</option>';}}


} ?> 
</select>

</td></tr>

<tr><td align="right">Форма печати заявки:</td><td><select name="tr_orderform"  id="tr_orderform" class="select">
  <option value="1">Общая</option>
<?php echo $company_list_in_select; ?>
  </select></td></tr>
<br>
<tr><td align="right">Примечание:</td><td><textarea cols="75" rows="5" name="tr_notify" class="input"><?php echo $row['notify'];?></textarea></td></tr>
</table></fieldset>

</div></div>
<div id="tr_tabs-4">
<div style="height: 21em;width: 101%; overflow: auto">
<fieldset><legend>Контакты:</legend>
<table>
<tr><td align="right" width="230">Ответственное лицо(ФИО):</td><td><input name="tr_chief" id="tr_chief" style="width: 300px;"  placeholder="Укажите Ф.И.О. полностью" class="input" value="">&nbsp;&nbsp;<input name="tr_chief_contract" id="tr_chief_contract" style="width: 280px;"  placeholder="Родительный падеж (нет кого?)" class="input" value=""></td></tr>
<tr><td align="right">Должность ответственного лица:</td><td><input name="tr_dchief" id="tr_dchief" style="width: 300px;"  placeholder="" class="input">&nbsp;&nbsp;<input name="tr_dchief_contract" id="tr_dchief_contract" style="width: 280px;"  placeholder="Родительный падеж (нет кого?)" class="input" value=""></td></tr>
<tr><td align="right">Ответственное лицо действует на основании:</td><td><input name="tr_ochief" id="tr_ochief" style="width: 300px;"  placeholder="" class="input"></td></tr>


<tr><td align="right">Контактное лицо:</td><td><input name="tr_support" id="tr_support" style="width: 300px;"  placeholder="Укажите Ф.И.О. полностью" class="input"></td></tr>
<tr><td align="right">Должность контактного лица</td><td><input name="tr_dsupport" id="tr_dsupport" style="width: 250px;"  placeholder="" class="input"></td></tr>

<tr><td align="right">Телефон:</td><td>
8 (<input name="tr_pref_phone" id="tr_pref_phone" style="width: 50px;"  placeholder="код" class="select" value="">) <input name="tr_phone" id="tr_phone" style="width: 514px;"  placeholder="номер телефона, через запятую можно указать дополнительные..." class="input" value="">
</td>
</tr><tr><td align="right">E-mail:</td><td>
<input name="tr_mail" id="tr_mail" style="width: 150px;"  placeholder="адрес E-mail" class="select" value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ICQ/Skype:&nbsp;&nbsp;<input name="tr_icq" id="tr_icq" style="width: 150px;"  placeholder="номер ICQ/Skype"  class="select" value="">
</td></tr></table></fieldset>
</div></div>
<div id="tr_tabs-5" align="left">

<?php  if ($tr!="") {echo '<div style="height: 23em;width: 100%; overflow: auto"><table><tr><td><img src="data/img/car_add.png" style="float:left;margin:5px;">&nbsp;&nbsp;&nbsp;&nbsp;<a id="btnAdd_car_add_tr" href="#" style="width: 220px;float:left;">Добавить автотранспорт</a></td><td><div id="car_save"></div></td></tr><tr><td colspan="2"><br><div id="car_info"></div></td></tr></table></div>';} ?>	

</div>
<br>
<input type="submit" id="Save_tr" value="Сохранить" style="width: 250px;margin-left:100px;">
<input type="button" id="btnClose_tr" onclick="$('#fa_tr').dialog('close');" value="Закрыть" style="width: 150px;">
</form><br><br>
</div>