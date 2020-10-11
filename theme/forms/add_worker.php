<?php 
include "../../config.php";


if (@$_GET['worker_id']!=""||intval($_GET['worker_id'])){
$worker_id=$_GET['worker_id'];

$query = "SELECT * FROM `workers` WHERE `Id`='".mysql_escape_string($worker_id)."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);
}
?>

<script type="text/javascript" src="data/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript">
    
$('#save').button();
$('#btnClose').button();



<?php if ($worker_id!="") {

$w_doc = explode('|',$row['passport']); 
echo '$("#w_passport1").val("'.$w_doc[0].'");$("#w_passport2").val("'.$w_doc[1].'");$("#w_passport3").val("'.$w_doc[2].'");';

echo '$("#w_zday").val("'.$row['z_day'].'");$("#w_zcity").val("'.$row['z_city'].'");$("#w_zrepair").val("'.$row['z_repair'].'");$("#w_zkm").val("'.$row['z_km'].'");$("#w_group").val('.$row['group'].').change();$("#w_name").val("'.$row['name'].'");$("#fake_name").val("'.$row['fake_name'].'");$("#w_ndfl").val("'.$row['ndfl'].'");$("#w_login").val("'.$row['login'].'");$("#w_pref_phone").val("'.$row['pref_phone'].'");$("#w_phone").val("'.$row['phone'].'");$("#w_mail").val("'.$row['email'].'");$("#w_voip").val("'.$row['voip'].'");$("#w_icq").val("'.$row['icq'].'");$("#w_zarplata").val("'.$row['zarplata'].'");$("#w_motive").val('.$row['motive'].').change();';
if($row['password']!="") echo '$("#w_pass").val("'.$row['password'].'");'; else echo '$("#w_pass").val("");';
if($row['ip']!="") echo '$("#w_ip").val("'.$row['ip'].'");'; else echo '$("#w_ip").val("0.0.0.0");';
}?>  

// - - форма добавления сотрудника - сохранение  - - >
$("#form_worker").submit(function() {
 var perfTimes = $("#form_worker").serialize();

$.post("control/worker_add.php", perfTimes, function(data) {
     
     var arr = data.split(/[|]/);
      if(arr[1]==1){$("#fa_worker").dialog("close");$('#form_worker').unbind();}
      $('#result').html(arr[0]);
      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });jQuery("#table").trigger("reloadGrid");
      


      }); 

     
      
  return false;
   });   
  
  

  



$.mask.definitions['~']='[+-]';
$('#w_passport3').mask('99.99.9999');
$('#w_data').mask('99/99/9999');
$("#w_data").datepicker(); 
$('#w_date_birth').mask('99/99/9999');
$("#w_date_birth").datepicker();  
$("#w_passport3").datepicker();
 
</script>

<form method="post" id="form_worker">

<?php  if ($worker_id!="") {

echo '<input type="hidden" name="worker_id" value="'.$row['id'].'"><input type="hidden" name="edit" value="1">';
} ?>


<fieldset><legend>Личные данные:</legend>
<table cellspacing="10">
<tr><td><b>Фамилия Имя Отчество:</b></td><td>
<input name="w_name" id="w_name" style="width: 300px;" placeholder="Укажите Ф.И.О. полностью" class="input">&nbsp;&nbsp;&nbsp;&nbsp;<b>Дата рождения:</b>&nbsp;&nbsp;<input name="w_date_birth" id="w_date_birth" style="width: 80px;" class="input" value="<?php if($worker_id!="") {echo date('d/m/Y',strtotime($row['date_birth']));} else {echo date('d/m/Y');}?>">
</td></tr>
<tr><td align="right"><b>Должность:</b></td><td>
<select name="w_group"  id="w_group" class="select" style="width: 150px;" onchange="var arr = $('select#w_group').val(); if(arr==5){document.getElementById('w_voip').disabled='true';document.getElementById('auth').style.display='none';document.getElementById('drv').style.display='inline';} else {document.getElementById('w_voip').disabled=false;document.getElementById('auth').style.display='inline';document.getElementById('drv').style.display='none';}">
  <option value="0">Выберите...</option>
  <option value="1">Администратор</option>
  <option value="2">Директор</option>
  <option value="3">Менеджер</option>
  <option value="4">Бухгалтер</option>
  <option value="5">Водитель</option>
  <option value="6">Складской работник</option></select>&nbsp;&nbsp;&nbsp;&nbsp;<b>Дата приема на работу:</b>&nbsp;&nbsp;<input name="w_data" id="w_data" style="width: 80px;" class="input" value="<?php if($worker_id!="") {echo date('d/m/Y',strtotime($row['date_start']));} else {echo date('d/m/Y');}?>">&nbsp;&nbsp;&nbsp;*формат: дд/мм/гггг
</td></tr>
<tr><td align="right"><b>Адрес проживания:</b></td><td>
<input type="text" name="w_adr" id="w_adr" style="width:520px;" value="<?php echo $row['adress'];?>" class="input">
</td></tr>
<tr><td align="right"><b>Паспорт №:</b></td><td>
<input type="text" name="w_passport1" id="w_passport1" style="width:110px;" value="" placeholder="1234 №123456" class="input">&nbsp;&nbsp;<b>Кем выдан:</b>&nbsp;&nbsp;<input type="text" name="w_passport2" id="w_passport2" style="width:180px;" value="" class="input">&nbsp;&nbsp;<b>Когда:</b>&nbsp;&nbsp;<input type="text" name="w_passport3" id="w_passport3" style="width:80px;" value="" class="input">
</td></tr>
</table>

</fieldset>
<table><tr><td valign="top"><fieldset style="width:190px;"><legend>Заработная плата:</legend>
<table cellspacing="5"><tr><td align="right" width="60">Оклад:</td><td><input name="w_zarplata" id="w_zarplata" style="width: 60px;"  placeholder="0" class="input" value="">&nbsp;&nbsp;руб.</td></tr><tr><td align="right">НДФЛ:</td><td><input name="w_ndfl" id="w_ndfl" style="width: 60px;"  placeholder="0" class="input" value="">&nbsp;&nbsp;руб.</td></tr>
<tr><td align="right">Мотив. схема:</td><td><select name="w_motive"  id="w_motive" style="width:130px;" class="select">
  <option value="0">Выберите...</option>
  <option value="1">Стартовая</option>
  <option value="3">М. Направления</option>
  <option value="4" onclick='$("#w_zarplata").val("7000")'>М. По перевозкам</option>
  <option value="2">Водитель</option>
  </select></td></tr>
</table>
</fieldset>

</td><td valign="top">
<div id="drv" style="display:none;"><fieldset style="width:260px;"><legend>Зарплатные ставки:</legend>
<table cellspacing="5"><tr><td align="right">Суточные:</td><td><input name="w_zday" id="w_zday" style="width: 60px;"  placeholder="0" class="input" value="500">&nbsp;&nbsp;руб./сут.</td></tr><tr><td align="right">Городские:</td><td><input name="w_zcity" id="w_zcity" style="width: 60px;"  placeholder="0" class="input" value="680">&nbsp;&nbsp;руб./сут.</td></tr>
<tr><td align="right">Ремонтные:</td><td><input name="w_zrepair" id="w_zrepair" style="width: 60px;"  placeholder="0" class="input" value="350">&nbsp;&nbsp;руб./сут.</td></tr>
<tr><td align="right">Километры:</td><td><input name="w_zkm" id="w_zkm" style="width: 60px;"  placeholder="0" class="input" value="3">&nbsp;&nbsp;руб./км</td></tr></table>
</fieldset></div>

<div id="auth"><fieldset style="width:240px;"><legend>Данные в системе:</legend>
<table cellspacing="5">
<tr><td align="right"><b>Псевдоним:</b></td><td>
<input name="fake_name" id="fake_name" style="width: 120px;"  placeholder="Имя для заявок" class="input" value=""></td></tr>
<tr><td align="right"><b>Пароль:</b></td><td>
<input name="w_pass" id="w_pass" style="width: 120px;"  placeholder="укажите" class="input" value="" TYPE=password></td></tr>
<tr><td align="right"><b>IP адрес:</b></td><td>
<input name="w_ip" id="w_ip" style="width: 120px;" disabled placeholder="0.0.0.0" class="input" value=""></td></tr>
</table></fieldset></div>

</td><td valign="top">
<fieldset style="width:280px;"><legend>Контакты:</legend>
<table cellspacing="5">
<tr><td align="right"><b>Телефон:</b></td><td width="250">
8 (<input name="w_pref_phone" id="w_pref_phone" style="width: 50px;"  placeholder="код" class="input" value="">) <input name="w_phone" id="w_phone" style="width: 80px;"  placeholder="номер " class="input" value="">
</td></tr><tr><td align="right"><b>E-mail:</b></td><td>
<input name="w_mail" id="w_mail" style="width: 180px;"  placeholder="адрес E-mail (test@mail.ru)" class="input" value="">
</td></tr>

<tr>

<td align="right">ICQ/Skype:</td><td>
<input name="w_icq" id="w_icq" style="width: 150px;"  placeholder="номер ICQ/Skype" class="input" value="">
</td>
</tr>
</table>
</fieldset></td></tr>
<tr><td colspan="3" valign="top">


<fieldset><legend>Примечание:</legend><textarea cols="77" rows="4" name="w_notify"><?php echo $row['notify'];?></textarea></fieldset>
</td></tr></table>


<hr>
<div align="center"><input type="submit" id="save" value="Сохранить" style="width: 250px;">
<input type="button" id="btnClose" onclick="$('#fa_worker').dialog('close');" value="Закрыть" style="width: 160px;">
</div>
</form>