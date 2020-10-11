<?php 
include "../../config.php";

session_start();
if (@$_GET['client']!=""||intval($_GET['client'])){
$client=$_GET['client'];

$query = "SELECT * FROM `clients` WHERE `Id`='".mysql_escape_string($client)."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);
} 
?>
<script type="text/javascript" src="data/jquery.maskedinput-1.3.min.js"></script>

<script type="text/javascript">
$.mask.definitions['~']='[+-]';


$('#cl_kpp').mask('999999999');
$('#cl_bik').mask('999999999');
$('#cl_ogrn').mask('9999999999999');
$('#cl_rs').mask('99999999999999999999');
$('#cl_ks').mask('99999999999999999999');

$('#Save_cl').button();
$('#btnClose_cl').button();

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


 
$("#cl_tabs").tabs({fx: {opacity:'toggle', duration:1}});       

$('#cl_select_add').flexbox('control/cl_search.php?mmm=cl_add', {
    width: 300,paging: {  
        summaryTemplate: 'Показать {start}-{end} из {total} клиентов',
        pageSize: 5 
    } , 
hiddenValue: 'id',
<?php if ($client!="") {
echo "initialValue: '".addslashes($row['name'])."',";
}?>
onSelect: function() {

$('#result').html('Клиент уже имеется в базе данных!');
      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ width: 400 },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");}}});
}});



$('#adr_cl_select_f').flexbox('control/adr_search.php?mode=3&fmod=1', {

<?php if ($client!="") {
$query_adress = "SELECT * FROM `adress` WHERE `id`='".$row['cl_adr_f']."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());

$adress = mysql_fetch_row($result_adress);
if($adress[8]=="0") $flat=""; else $flat=" - ".$adress[8];
if($adress[3]=="") $obl=""; else $obl=$adress[3]." обл.";
if($adress[1]=="0") $postcode=""; else $postcode=$adress[1];
if($adress[7]=="") $dom_ext=""; else $dom_ext="(".$adress[7].")";

echo "initialValue: '".$postcode.' '.$adress[2].' '.$obl.' '.$adress[4].' ул.'.$adress[5].' д. '.$adress[6].' '.$dom_ext.$flat."',";
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

$('#adr_cl_select_u').flexbox('control/adr_search.php?mode=3&fmod=2', {

<?php if ($client!="") {
$query_adress = "SELECT * FROM `adress` WHERE `id`='".$row['cl_adr_u']."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());

$adress = mysql_fetch_row($result_adress);
if($adress[8]=="0") $flat=""; else $flat=" - ".$adress[8];
if($adress[3]=="") $obl=""; else $obl=$adress[3]." обл.";
if($adress[1]=="0") $postcode=""; else $postcode=$adress[1];
if($adress[7]=="") $dom_ext=""; else $dom_ext="(".$adress[7].")";

echo "initialValue: '".$postcode.' '.$adress[2].' '.$obl.' '.$adress[4].' ул.'.$adress[5].' д. '.$adress[6].' '.$dom_ext.$flat."',";
}?>

watermark: 'Поиск по адресам...',
    paging: {  
        summaryTemplate: 'Показать {start}-{end} из {total} адресов',
        pageSize: 2  
    } , 
    width: 620,
    

hiddenValue: 'id',
onSelect: function() {


}
});


<?php if ($client!="") {

echo '$("#cl_limit").val('.$row['cl_limit'].');$("#cl_limit_order").val('.$row['cl_limit_order'].');$("#cl_pref_add").val('.(int)$row['pref'].').change();$("#cl_nds_add").val('.(int)$row['nds'].').change(); $("#adr_cl_select_u_hidden").val("'.$row['cl_adr_u'].'");$("#adr_cl_select_f_hidden").val("'.$row['cl_adr_f'].'");$("#cl_icq").val("'.$row['cl_icq'].'");$("#cl_name").val("'.addslashes($row['name']).'");$("#cl_point").val('.addslashes($row['cl_point']).').change();$("#cl_time").val('.addslashes($row['cl_time']).');$("#cl_inn").val("'.$row['cl_inn'].'");$("#cl_kpp").val("'.addslashes($row['cl_kpp']).'");$("#cl_ogrn").val("'.addslashes($row['cl_ogrn']).'");$("#cl_rs").val("'.addslashes($row['cl_rs']).'");$("#cl_bank").val("'.addslashes($row['cl_bank']).'");$("#cl_bik").val("'.addslashes($row['cl_bik']).'");$("#cl_ks").val("'.addslashes($row['cl_ks']).'");$("#cl_order").val('.addslashes($row['cl_order']).').change();$("#cl_manager").val('.(int)$row['cl_manager'].').change();$("#cl_chief").val("'.addslashes($row['cl_chief']).'");$("#cl_dchief").val("'.addslashes($row['cl_dchief']).'");$("#cl_ochief").val("'.addslashes($row['cl_ochief']).'");$("#cl_support").val("'.addslashes($row['cl_support']).'");$("#cl_dsupport").val("'.addslashes($row['cl_dsupport']).'");$("#cl_phone").val("'.addslashes($row['cl_phone']).'");$("#cl_mail").val("'.addslashes($row['cl_mail']).'");$("#cl_pref_phone").val("'.addslashes($row['cl_pref_phone']).'");$("#cl_chief_contract").val("'.addslashes($row['cl_chief_contract']).'");$("#cl_dchief_contract").val("'.addslashes($row['cl_dchief_contract']).'");$("#cl_cont_sel").val('.(int)$row['cl_cont'].').change();';
if($row['contract']!="") echo '$("#contract").val("'.addslashes($row['contract']).'");'; else echo '$("#contract").val("'.addslashes($row['id']).'");';

if((int)$row['pref']==3)echo "document.getElementById('cl_dchief').style.visibility = 'hidden';document.getElementById('cl_dchief_contract').style.visibility = 'hidden';$('#cl_inn').mask('999999999999');";
}

if($_SESSION["group"]==3||$_SESSION["group"]==4)
echo '$("#cl_limit").attr("readonly","readonly");'
?>  

// - - форма добавления клиента - сохранение  - - >
$("#form_cl").submit(function() {

$('#cl_name').val($('#cl_select_add_hidden').val());


 var perfTimes = $("#form_cl").serialize();
//if(is_valid_inn($("#cl_inn").val())){validate=false;} else {alert('Не верный ИНН!');}

$.post("control/cl_add.php", perfTimes, function(data) {
     
     var arr = data.split(/[|]/);
      if(arr[1]==1){$("#fa_cl").dialog("close");$('#form_cl').unbind();
      jQuery("#table").trigger("reloadGrid");
  $('#cl_select').setValue(''+arr[3]+'');
$('#cl_select_hidden').val(arr[2]);
$('#cl_select_info').html(''+arr[3]+'');
    
$('#cl_nds').val(arr[4]).change();
$('#cl_pref_temp').html(arr[5]);
$('#cl_tfpay').val(arr[6]);
$('#cl_event').val(arr[7]).change();

$('#hidden_value_cl').html('<input type="hidden" name="client" id="client" value="'+arr[2]+'"><input type="hidden" name="cl_pref" id="cl_pref" value="'+arr[8]+'"><input type="hidden" name="cl_cont" id="cl_cont" value="'+arr[9]+'">');
    
    
      $('#result').html("Распечатать договор с клиентом?");
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ width: 400 },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");
      if(arr[10]==1){window.location.href='control/print_c.php?mode=cl&id='+arr[11];}
      else {window.location.href='control/print_c.php?mode=cl&id=<?php  if ($client!="") {echo base64_encode($client);}?>';}
      
       $('#result').html(arr[0]);
      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ width: 400 },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");
      
       } } });
      
       } } });
       } else {
      $('#result').html(arr[0]);
      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ width: 400 },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");
      
       } } });
      
      }


      }); 

     
      
  return false;
   });   
  
  

  

   
</script>

<form method="post" id="form_cl">

<?php  if ($client!="") {

echo '<input type="hidden" name="client_id" value="'.$row['id'].'"><input type="hidden" name="edit" value="1">';
} ?>

<div id="cl_tabs">
<ul>
		<li><a href="#cl_tabs-1">Клиент</a></li>
		<li><a href="#cl_tabs-2">Реквизиты</a></li>
		<li><a href="#cl_tabs-3">Заявки</a></li>
<li><a href="#cl_tabs-4">Контакты</a></li>
	</ul>

<div id="cl_tabs-1">
<div style="height: 24em;width: 101%; overflow: auto">
<table>
<tr><td width="120">Наименование:</td><td colspan="2">
<div id="cl_select_add"></div><input type="hidden" name="cl_name" id="cl_name" value="">
</td></tr>
<tr><td>Форма организации:</td><td colspan="2">
<select name="cl_pref" style="width:70px;" id="cl_pref_add" class="select">

   <option value="1">ООО</option>
  <option value="2">ОАО</option>
 <option value="7">АО</option>
    <option value="3">ИП</option>
    <option value="4">ЗАО</option>
<option value="6">Физ.Л.</option>
<option value="5"></option>


</select>
</td></tr>

<tr><td>Адрес (фактич.):</td><td width="640">
<div id="adr_cl_select_f" style="float:left;"></div>
</td>
<td>
<a id="btnAdd_adress_cl_f" href="#" style="font-size: 20px;text-decoration: none;float:left;" onclick='$("#fa_adr").load("theme/forms/add_adr.php?adr_mode=3&fmod=1", function() {$("#fa_adr").dialog({ title: "Новый фактический адрес клиента" },{width: 480,height: 400,modal: true,resizable: false});}); '><img src="data/img/plus.png"></a>
<a id="btnEdit_adress_cl_f" href="#" style="font-size: 20px;text-decoration: none;float:left;" onclick='if($("#adr_cl_select_f_hidden").val()!=""){$("#fa_adr").load("theme/forms/add_adr.php?adr_mode=3&fmod=1&adr_id="+$("#adr_cl_select_f_hidden").val(), function() {$("#fa_adr").dialog({ title: "Редактировать фактический адрес клиента" },{width: 480,height: 400,modal: true,resizable: false});}); } else {alert("Выберите адрес клиента!");}'>&nbsp;&nbsp;<img src="data/img/pencil.png">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>


</td></tr>
<tr><td>Адрес (юридич.):</td><td width="640">
<div id="adr_cl_select_u" style="float:left;"></div>
</td>
<td><a id="btnAdd_adress_cl_u" href="#" style="font-size: 20px;text-decoration: none;float:left;" onclick='$("#fa_adr").load("theme/forms/add_adr.php?adr_mode=3&fmod=2", function() {$("#fa_adr").dialog({ title: "Новый юридический адрес клиента" },{width: 480,height: 400,modal: true,resizable: false});}); '><img src="data/img/plus.png"></a>
<a id="btnEdit_adress_cl_u" href="#" style="font-size: 20px;text-decoration: none;float:left;" onclick='if($("#adr_cl_select_u_hidden").val()!=""){$("#fa_adr").load("theme/forms/add_adr.php?adr_mode=3&fmod=1&adr_id="+$("#adr_cl_select_u_hidden").val(), function() {$("#fa_adr").dialog({ title: "Редактировать юридический адрес клиента" },{width: 480,height: 400,modal: true,resizable: false});});} else {alert("Выберите адрес клиента!");} '>&nbsp;&nbsp;<img src="data/img/pencil.png">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>


</td></tr>   
<tr><td>Контактёр:</td><td colspan="2">
<select name="cl_cont_sel"  id="cl_cont_sel" class="select">

 <?php $query = "SELECT `id`,`name` FROM `company` WHERE `active`='1'";
$result = mysql_query($query) or die(mysql_error());
while($company= mysql_fetch_row($result)) {
if($company[0]!=1) {
    $company_list_in_select = $company_list_in_select.'<option value='.$company[0].'>'.$company[1].'</option>'; 
  }
} 
echo $company_list_in_select;

 ?> 
</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
Номер договора:
<input name="contract"  id="contract" class="select" placeholder="Укажите номер" style="width: 110px;" class="input" value="">

</td></tr>
<tr><td colspan="3">
<fieldset><legend>Оплата:</legend>
<table cellpadding="5" style="margin-top:-5px;"><tr><td align="left" colspan="2">
Система налогообложения:&nbsp;&nbsp;&nbsp;<select name="cl_nds"  id="cl_nds_add" class="select">
  <option value="0">без НДС</option>
  <option value="1">с НДС</option>
  <option value="2">НАЛ</option></select>
  </td><td align="left" colspan="2">
Лимит:&nbsp;&nbsp;&nbsp;<input name="cl_limit" id="cl_limit" style="width: 50px;" class="input" value="50000"> руб.&nbsp;&nbsp;&nbsp;
Разрешить <input name="cl_limit_order" id="cl_limit_order" style="width: 20px;" class="input" value="0"> заявки
  </td></tr><tr>
<td align="right" width="100">Точка оплаты:</td><td ><select name="cl_point"  id="cl_point" class="select">
  <option value="0">Выберите...</option>
  <option value="1">Загрузка</option>
  <option value="2">Выгрузка</option>
  <option value="3">Поступление факсимильных документов</option>
  <option value="4">Поступление оригинальных документов</option>
  </select></td>
  <td align="right">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Период расчётов:</td><td colspan="2"><input name="cl_time" id="cl_time" style="width: 50px;"  placeholder="0" class="input" value=""> дн. * - календарных</td></tr>
</fieldset></table>
</td></tr>
</table>
</div></div>
<div id="cl_tabs-2">
<div style="height: 24em;width: 101%; overflow: auto">
<fieldset><legend>Реквизиты:</legend>
<table>
<tr><td align="right" width="150">ИНН:</td><td><input type="number" name="cl_inn" id="cl_inn" style="width: 120px;" class="select" placeholder="" class="input" value=""> <font color="red" size="4">*</font></td></tr>
<tr><td align="right">КПП:</td><td><input name="cl_kpp" id="cl_kpp" style="width: 100px;" class="select" placeholder="" class="input"></td></tr>
<tr><td align="right">ОГРН:</td><td><input name="cl_ogrn" id="cl_ogrn" style="width: 130px;" class="select" placeholder="" class="input"></td></tr>
<tr><td align="right">р/сч:</td><td><input name="cl_rs" id="cl_rs" style="width: 200px;" class="select"  placeholder="" class="input"> <font color="red" size="4">*</font></td></tr>
<tr><td align="right">Банк в :</td><td><input name="cl_bank" id="cl_bank" style="width: 400px;" class="select"  placeholder="" class="input"> <font color="red" size="4">*</font></td></tr>
<tr><td align="right">БИК:</td><td><input name="cl_bik" id="cl_bik" style="width: 100px;" class="select"  placeholder="" class="input"> <font color="red" size="4">*</font></td></tr>
<tr><td align="right">к/сч:</td><td><input name="cl_ks" id="cl_ks" style="width: 200px;" class="select"  placeholder="" class="input">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Поля, помеченные <font color="red" size="4">*</font> - обязательны для заполнения</td></tr>

</table>

</fieldset>

</div>

</div>
<div id="cl_tabs-3">
<div style="height: 24em;width: 101%; overflow: auto">
<fieldset><legend>Дополнительно:</legend>
<table>
<tr><td align="right" width="250">Способ получения заказа:</td><td><select name="cl_order"  id="cl_order" class="select">
  <option value="0">Выберите...</option>
  <option value="1">e-mail</option>
  <option value="2">телефон</option>
  <option value="3">ICQ</option>

    </select></td></tr>
<tr><td align="right">Куратор клиента:</td><td>
<select name="cl_manager" id="cl_manager" style="width:174px;" class="select">
   <?php $query = "SELECT `id`,`name` FROM `workers` WHERE `group`!='5' AND `delete`='0'";
$result = mysql_query($query) or die(mysql_error());
while($user= mysql_fetch_row($result)) {

$pieces = explode(" ", $user[1]);
$manager=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";

if($user[0]==$_SESSION["user_id"]&&$_SESSION["group"]!='2'&&$_SESSION["group"]!='1'){echo '<option value="'.$user[0].'">'.$manager.'</option>';}
if($_SESSION["group"]==2||$_SESSION["group"]==1||$_SESSION["group"]==4){echo '<option value="'.$user[0].'">'.$manager.'</option>';}


} ?> 
</select>

</td></tr>

<tr><td align="right">Форма печати заявки:</td><td><select name="cl_orderform"  id="cl_orderform" class="select">
  <option value="1">Общая</option>
<?php echo $company_list_in_select; ?>
  </select></td></tr>

<tr><td align="right">Примечание:</td><td><textarea cols="75" rows="5" name="cl_notify" class="select"><?php echo $row['notify'];?></textarea></td></tr>
</table></fieldset>

</div>
</div>

<div id="cl_tabs-4">
<div style="height: 24em;width: 101%; overflow: auto">
<fieldset><legend>Контакты:</legend>
<table>
<tr><td align="right" width="230">Ответственное лицо(ФИО):</td><td><input name="cl_chief" id="cl_chief" style="width: 270px;" class="select"  placeholder="Укажите Ф.И.О. полностью" class="input" value="">&nbsp;&nbsp;<input name="cl_chief_contract" id="cl_chief_contract" class="select" style="width: 270px;"  placeholder="Родительный падеж (нет кого?)" class="input" value=""></td></tr>
<tr><td align="right">Должность ответственного лица:</td><td><input name="cl_dchief" class="select" id="cl_dchief" style="width: 270px;"  placeholder="">&nbsp;&nbsp;<input name="cl_dchief_contract" id="cl_dchief_contract" class="select" style="width: 270px;"  placeholder="Родительный падеж (нет кого?)" value=""></td></tr>
<tr><td align="right">Ответственное лицо действует на основании:</td><td><input name="cl_ochief" class="select" id="cl_ochief" style="width: 300px;"  placeholder=""></td></tr>


<tr><td align="right">Контактное лицо:</td><td><input name="cl_support" class="select" id="cl_support" style="width: 270px;"  placeholder="Укажите Ф.И.О. полностью" class="input"></td></tr>
<tr><td align="right">Должность контактного лица</td><td><input name="cl_dsupport" class="select" id="cl_dsupport" style="width: 270px;"  placeholder="" class="input"></td></tr>

<tr><td align="right">Телефон:</td><td>
8 (<input name="cl_pref_phone" id="cl_pref_phone" style="width: 50px;"  placeholder="код" class="select" value="">) <input name="cl_phone" id="cl_phone" style="width: 514px;"  placeholder="номер телефона, через запятую можно указать дополнительные..." class="input" value="">
</td>
</tr><tr><td align="right">E-mail:</td><td>
<input name="cl_mail" id="cl_mail" style="width: 150px;"  placeholder="адрес E-mail" class="select" value="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ICQ:&nbsp;&nbsp;<input name="cl_icq" id="cl_icq" style="width: 150px;"  placeholder="номер ICQ"  class="select" value="">
</td></tr></table></fieldset>
</div>
</div><br>
<input type="submit" id="Save_cl" value="Сохранить" style="width: 250px;margin-left:100px;">
<input type="button" id="btnClose_cl" onclick="$('#fa_cl').dialog('close');" value="Закрыть" style="width: 150px;">	
<br><br></div>

</form>