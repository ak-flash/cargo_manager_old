<?php 
include "../../config.php";

session_start();
if (@$_GET['company']!=""||intval($_GET['company'])){
$company=$_GET['company'];

$query = "SELECT * FROM `company` WHERE `Id`='".mysql_escape_string($company)."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);
} 
?>

<script type="text/javascript">
$.mask.definitions['~']='[+-]';


$('#company_inn').mask('9999999999');
$('#company_kpp').mask('999999999');
$('#company_bik').mask('999999999');
$('#company_ogrn').mask('9999999999999');
$('#company_rs').mask('99999999999999999999');
$('#company_ks').mask('99999999999999999999');


function is_valid_inn(i)
{
    if ( i.match(/\D/) ) return false;
    
    var inn = i.match(/(\d)/g);
    
    if ( inn.length == 10 )
    {
        return inn[9] == String(((
            2*inn[0] + 4*inn[1] + 10*inn[2] + 
            3*inn[3] + 5*inn[4] +  9*inn[5] + 
            4*inn[6] + 6*inn[7] +  8*inn[8]
        ) % 11) % 10);
    }
    else if ( inn.length == 12 )
    {
        return inn[10] == String(((
             7*inn[0] + 2*inn[1] + 4*inn[2] +
            10*inn[3] + 3*inn[4] + 5*inn[5] +
             9*inn[6] + 4*inn[7] + 6*inn[8] +
             8*inn[9]
        ) % 11) % 10) && inn[11] == String(((
            3*inn[0] +  7*inn[1] + 2*inn[2] +
            4*inn[3] + 10*inn[4] + 3*inn[5] +
            5*inn[6] +  9*inn[7] + 4*inn[8] +
            6*inn[9] +  8*inn[10]
        ) % 11) % 10);
    }
    
    return false;
}


 
$("#company_tabs").tabs({fx: {opacity:'toggle', duration:100}});       



<?php if ($company!="") {

echo '$("#company_pref_add").val('.addslashes($row['pref']).').change();$("#company_name").val("'.addslashes($row['name']).'");$("#company_short_name").val("'.addslashes($row['short_name']).'");$("#company_inn").val("'.addslashes($row['inn']).'");$("#company_kpp").val("'.addslashes($row['kpp']).'");$("#company_ogrn").val("'.addslashes($row['ogrn']).'");$("#company_rs").val("'.addslashes($row['rs']).'");$("#company_bank").val("'.addslashes($row['bank']).'");$("#company_bik").val("'.addslashes($row['bik']).'");$("#company_ks").val("'.addslashes($row['ks']).'");$("#company_chief").val("'.addslashes($row['chief']).'");$("#company_dchief").val("'.addslashes($row['chief_status']).'");$("#company_ochief").val("'.addslashes($row['ochief_contract']).'");$("#company_phone").val("'.addslashes($row['phone']).'");$("#company_mail").val("'.addslashes($row['email']).'");$("#company_pref_phone").val("'.addslashes($row['pref_phone']).'");$("#company_chief_contract").val("'.addslashes($row['chief_contract']).'");$("#company_dchief_contract").val("'.addslashes($row['dchief_contract']).'");$("#c_nds").val('.addslashes($row['nds']).').change();';


}?>  

// - - форма добавления клиента - сохранение  - - >
$("#form_company").submit(function() {
 var perfTimes = $("#form_company").serialize();
//if(is_valid_inn($("#cl_inn").val())){validate=false;} else {alert('Не верный ИНН!');}

$.post("control/company_add.php", perfTimes, function(data) {
     
     var arr = data.split(/[|]/);
      if(arr[1]==1){$("#fa_company").dialog("close");$('#form_company').unbind();}
      

      $('#result').html(arr[0]);
      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ width: 400 },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");
      
       } } });jQuery("#table").trigger("reloadGrid");
      
   


      }); 

     
      
  return false;
   });   
  
  

 $('#btnClose_company').button(); 
$('#save').button(); 
   
</script>

<form method="post" id="form_company">

<?php  if ($company!="") {

echo '<input type="hidden" name="company_id" value="'.$row['id'].'"><input type="hidden" name="edit" value="1">';
} ?>

<div id="company_tabs">
<ul>
		<li><a href="#company_tabs-1">Компания</a></li>
		<li><a href="#company_tabs-2">Реквизиты</a></li>
<li><a href="#company_tabs-3">Контакты</a></li>
	</ul>

<div id="company_tabs-1">
<table>
<tr><td>Полное название:</td><td>
<input name="company_name" id="company_name" style="width: 250px;" placeholder="Укажите название" class="input">&nbsp;&nbsp;&nbsp;Короткое имя:&nbsp;<input name="company_short_name" id="company_short_name" style="width: 70px;" placeholder="Укажите" class="input">
</td></tr>
<tr><td>Форма организации:</td><td>
<select name="company_pref" style="width:70px;" id="company_pref_add" class="select">
  <option value="1" onclick="document.getElementById('company_dchief').style.visibility = 'visible';document.getElementById('company_dchief_contract').style.visibility = 'visible';$('#company_inn').mask('9999999999');">ООО</option>
  <option value="2" onclick="document.getElementById('company_dchief').style.visibility = 'visible';document.getElementById('company_dchief_contract').style.visibility = 'visible';$('#company_inn').mask('9999999999');">ОАО</option>
  <option value="3" onclick="document.getElementById('company_dchief').style.visibility = 'hidden';document.getElementById('company_dchief_contract').style.visibility = 'hidden';$('#company_inn').mask('999999999999');">ИП</option>
  <option value="4" onclick="document.getElementById('company_dchief').style.visibility = 'visible';document.getElementById('company_dchief_contract').style.visibility = 'visible';$('#company_inn').mask('9999999999');">ЗАО</option></select>
</td></tr>
<tr><td>Форма оплаты:</td><td>
<select name="c_nds"  id="c_nds" class="select">
  <option value="0">без НДС</option>
  <option value="1">с НДС</option>
  <option value="2">НАЛ</option></select>
</td></tr>
<tr><td>Адрес (фактич.):</td><td>
<input name="company_adr_f" id="company_adr_f" value="<?php echo $row['adr_f'];?>" style="width:550px;">
</td></tr>
<tr><td>Адрес (юридич.):</td><td>
<input name="company_adr_u" id="company_adr_u" value="<?php echo $row['adr_u'];?>" style="width:550px;">
</td></tr>   
</table><br>
<fieldset><legend>Примечание:</legend>
<textarea cols="75" name="company_notify"><?php echo $row['notify'];?></textarea>
</fieldset>
</div>

<div id="company_tabs-2">

<fieldset><legend>Реквизиты:</legend>
<table>
<tr><td align="right" width="150">ИНН:</td><td><input name="company_inn" id="company_inn" style="width: 100px;" placeholder="" class="input" value=""></td></tr>
<tr><td align="right">КПП:</td><td><input name="company_kpp" id="company_kpp" style="width: 100px;" placeholder="" class="input"></td></tr>
<tr><td align="right">ОГРН:</td><td><input name="company_ogrn" id="company_ogrn" style="width: 130px;" placeholder="" class="input"></td></tr>
<tr><td align="right">р/сч:</td><td><input name="company_rs" id="company_rs" style="width: 200px;"  placeholder="" class="input"></td></tr>
<tr><td align="right">Банк:</td><td><input name="company_bank" id="company_bank" style="width: 400px;"  placeholder="" class="input"></td></tr>
<tr><td align="right">БИК:</td><td><input name="company_bik" id="company_bik" style="width: 100px;"  placeholder="" class="input"></td></tr>
<tr><td align="right">к/сч:</td><td><input name="company_ks" id="company_ks" style="width: 200px;"  placeholder="" class="input"></td></tr>

</table></fieldset>

</div>



<div id="company_tabs-3">

<fieldset><legend>Контакты:</legend>
<table>
<tr><td align="right" width="230">Ответственное лицо(ФИО):</td><td><input name="company_chief" id="company_chief" style="width: 240px;"  placeholder="Укажите Ф.И.О. полностью" class="input" value=""><input name="company_chief_contract" id="company_chief_contract" style="width: 240px;"  placeholder="Родительный падеж (нет кого?)" class="input" value=""></td></tr>
<tr><td align="right">Должность ответственного лица:</td><td><input name="company_dchief" id="company_dchief" style="width: 240px;"  placeholder="" class="input"><input name="company_dchief_contract" id="company_dchief_contract" style="width: 240px;"  placeholder="Родительный падеж (нет кого?)" class="input" value=""></td></tr>
<tr><td align="right">Ответственное лицо действует на основании:</td><td><input name="company_ochief" id="company_ochief" style="width: 250px;"  placeholder="" class="input"></td></tr>


<tr><td align="right">Телефон:</td><td>
8 (<input name="company_pref_phone" id="company_pref_phone" style="width: 50px;"  placeholder="код" class="input" value="">) <input name="company_phone" id="company_phone" style="width: 120px;"  placeholder="номер телефона" class="input" value="">
</td>
</tr><tr><td align="right">E-mail:</td><td>
<input name="company_mail" id="company_mail" style="width: 150px;"  placeholder="адрес E-mail" class="input" value="">
</td></tr></table></fieldset>
<br>
<div align="center"><input type="submit" id="save" value="Сохранить" style="width: 250px;">
<input type="button" id="btnClose_company" onclick="$('#fa_company').dialog('close');" value="Закрыть" style="width: 150px;">
</div>
</div>	
</div>
</form>