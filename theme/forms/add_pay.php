<?php 
include "../../config.php";


if (@$_GET['pay_id']!=""||intval($_GET['pay_id'])){
$pay_id=$_GET['pay_id'];

$query = "SELECT * FROM `pays` WHERE `Id`='".mysql_escape_string($pay_id)."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);

if ($row['del_id']!=0){$delete_id=$row['del_id'];
$query = "SELECT * FROM `pays` WHERE `Id`='".mysql_escape_string($row['del_id'])."'";
$result = mysql_query($query) or die(mysql_error());
$del_row = mysql_fetch_array($result);}

} else {session_start();

if (@$_GET['delete_id']!=""||intval($_GET['delete_id'])){
$delete_id=$_GET['delete_id'];
$query = "SELECT * FROM `pays` WHERE `Id`='".mysql_escape_string($delete_id)."'";
$result = mysql_query($query) or die(mysql_error());
$del_row = mysql_fetch_array($result);
}
}
?>

<script type="text/javascript">
$('#save_pay').button();
$('#btnClose_pay').button();
$("#date_pay").datepicker();
$("#appointment").attr("disabled","disabled");



$.mask.definitions['~']='[+-]';
$('#date').mask('99/99/9999'); 

function number_format( number, decimals, dec_point, thousands_sep ) {	// Format a number with grouped thousands
	// 
	// +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +	 bugfix by: Michael White (http://crestidg.com)

	var i, j, kw, kd, km;

	// input sanitation & defaults
	if( isNaN(decimals = Math.abs(decimals)) ){
		decimals = 2;
	}
	if( dec_point == undefined ){
		dec_point = ",";
	}
	if( thousands_sep == undefined ){
		thousands_sep = ".";
	}

	i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

	if( (j = i.length) > 3 ){
		j = j % 3;
	} else{
		j = 0;
	}

	km = (j ? i.substr(0, j) + thousands_sep : "");
	kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
	//kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
	kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


	return km + kw + kd;
}


function Komiss(){
$('#check_info').html('<table bgcolor="#FFFFFF" cellspacing="10"><tr><td width="220">Планируемая сумма:&nbsp;</td><td width="200">Предлагается:&nbsp;</td></tr><tr><td><b><font size="5"><div id="plan_cash" style="float:left;margin-left:20px;">0</div></font></b>&nbsp;у.е.&nbsp;</td><td><font size="5"><div id="inc" style="float:left;"></div> <div id="plan_pay" style="float:left;">0</div></font>&nbsp;у.е.</td></tr></table>');

$('#cash').val($('#cash').val().replace(/,+/,"."));
$('#plan_pay').html(number_format($('#cash').val(), 2, '.', ' '));

if($('#order').val()=='') $('#order_info').html('');
if($('#category').val()=='2'){$('#order_info').html('');$('#plan_cash').html(number_format($('#cash').val(), 2, '.', ' '));$('#order').val('')} 

if($('#order').val()!=''&&$('#category').val()!='2'){$('#order_info').html('&nbsp;<u>по заявке №'+$('#order').val()+'</u>&nbsp;');
$("#plan_cash").load('control/pay_load.php?order_id='+$('#order').val()+'&order_type='+$('#order_type').val()+'&mode='+$('#appointment').val()+'&way='+$('#way').val(), function(data){
if(data=='Заявки не существует!') {$("#plan_cash").html("0");$('#error').val("1");toastr.error('Заявки не существует!');} else {$("#plan_cash").html(number_format(data, 2, '.', ' '));

$('#plan_cash_int').val(data);$('#error').val("0");}
if(data=='Заявка оплачена!')$('#check_info').html('<input type="hidden" name="pay_cash_full" id="pay_cash_full" value="1"><table bgcolor="#FFFFFF" cellspacing="10" height="80" width="450" ><tr><td align="center"><font color="green" size="6">'+data+'</font></td></tr></table>');});
}
}


$("#form_pay").submit(function() {  
  validate=true;    
<?php if ($row['status']!='1'){echo 'if($("#error").val()!="1"){
var plan_cash_int=parseInt($("#plan_cash_int").val(), 10);

var pay_cash=parseInt(document.getElementById("cash").value, 10);
if(($("#way").val()==1&&$("#appointment").val()==1)||($("#way").val()==2&&$("#appointment").val()==2)||($("#way").val()==2&&$("#appointment").val()==3)||($("#way").val()==2&&$("#appointment").val()==6)||($("#way").val()==1&&$("#appointment").val()==4)||($("#way").val()==1&&$("#appointment").val()==5)){if(pay_cash>plan_cash_int){$("#result").html("Сумма «"+$("#way option:selected").html()+"» больше, чем ожидаемая сумма.<br> Изменить данные в заявке?");validate=false;} if(!validate){$("#result").dialog({ title: "Внимание" },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: [{text: "Да",click: function() {$("#fa").load("theme/forms/add_order.php?order="+$("#order").val());
$("#fa").dialog({ title: "Редактировать заявку №"+$("#order").val() },{width: 970,height: 780,modal: true,resizable: false});}},{text: "Нет",click: function() {$(this).dialog("close");}}]});}} 
}
'; } ?> 
   
   if(validate){ 
   
     $("#save_pay").attr("disabled","disabled");
     
      var perfTimes = $("#form_pay").serialize(); 
      $.post("control/add_pay.php", perfTimes, function(data) {
      jQuery("#table").trigger("reloadGrid");
      var arr = data.split(/[|]/);
      $('#result').html(arr[0]);
      if(arr[1]==1){$("#fa_pay").dialog("close");} else $("#save_pay").attr("disabled","");
      
      $("#result").dialog({ title: 'Сообщение' },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });}); 
 }
  
  return false;
    
  }); 

<?php
  
    
if ($pay_id!="") {

$query_check = "SELECT `c_bill`,`c_cash`,`c_bank` FROM `bill` WHERE `id`='".mysql_escape_string($row['pay_bill'])."'";
$result_check = mysql_query($query_check) or die(mysql_error());
$check_bill = mysql_fetch_row($result_check);

echo '$("#category").val('.$row['category'].').change();$("#appointment").val('.$row['appoint'].').change();$("#order").val("'.$row['order'].'");$("#cash").val("'.((int)$row['cash']/100).'");$("#nds").val("'.$row['nds'].'");Komiss();$("#add_name").val("'.$row['add_name'].'");$("#pay_bill").val("'.$row['pay_bill'].'");$("#payment_source").val('.$row['pay_bill'].').change();$("#bill_info").html("Счёт № <b>'.$check_bill[0].'</b>");$("#bill_info_balance").html("Баланс: <b><font size=5>'.number_format($check_bill[1]/100, 2, ',', ' ').'</b></font> у.е.");$("#bill_info_bank").html("'.addslashes($check_bill[2]).'");$("#currency").val("'.$row['currency'].'").change();$("#order_type").val("'.$row['order_type'].'").change();';


if($row['status']=='1'){echo '$("#prov").html("<u>ПРОВЕДЕНИЯ</u>");$("#order").attr("disabled","disabled");$("#date_pay").attr("disabled","disabled");$("#cash").attr("disabled","disabled");$("#car_number").attr("disabled","disabled");$("#category").attr("disabled","disabled");$("#way").attr("disabled","disabled");$("#nds").attr("disabled","disabled");$("#payment_source").attr("disabled","disabled");$("#currency").attr("disabled","disabled");$("#order_type").attr("disabled","disabled");';} else {echo '$("#prov").html("<u>ПЛАНИРОВАНИЯ</u>");';}

echo '$("#way").val('.$row['way'].').change();';

if($row['way']==1){echo "\$('#inc').html('+&nbsp;');if(\$('#category').val()=='1'){\$('#app_select').load('control/pay_load.php?way=1&category=1";
if($row['status']=='1')echo "&status=1";
echo "', function(data){\$('#appointment').val(".$row['appoint'].").change();Komiss();$('#appoint').html('<u>'+\$('#appointment option:selected').html()+'</u>');});} if(\$('#category').val()=='2'){\$('#app_select').load('control/pay_load.php?way=1&category=2";
if($row['status']=='1')echo "&status=1";
echo "', function(data){\$('#appointment').val(".$row['appoint'].").change();Komiss();$('#appoint').html('<u>'+\$('#appointment option:selected').html()+'</u>');});} ";}

if($row['way']==2){echo "\$('#inc').html('-&nbsp;');if(\$('#category').val()=='1'){\$('#app_select').load('control/pay_load.php?way=2&category=1";
if($row['status']=='1')echo "&status=1";
echo "', function(data){\$('#appointment').val(".$row['appoint'].").change();Komiss();$('#appoint').html('<u>'+\$('#appointment option:selected').html()+'</u>');});} if(\$('#category').val()=='2'){\$('#app_select').load('control/pay_load.php?way=2&category=2";
if($row['status']=='1')echo "&status=1";
echo "', function(data){\$('#appointment').val(".$row['appoint'].").change();Komiss();$('#appoint').html('<u>'+\$('#appointment option:selected').html()+'</u>');});} ";

$query_s = "SELECT `car` FROM `pays_appoints` WHERE `id`='".$row['appoint']."'";
$result_s = mysql_query($query_s) or die(mysql_error());
$pays_app= mysql_fetch_row($result_s);
if($pays_app[0]==1)echo "document.getElementById('car_ls').style.display='inline';$('#car_number').val(".$row['car_id'].").change();";

}

}

if (@$_GET['delete_id']!="") {
echo '$("#way").val('.$del_row['way'].').change();$("#payment_source").val('.$del_row['payment_source'].').change();$("#category").val('.$del_row['category'].').change();$("#appointment").val('.$del_row['appoint'].').change();$("#order").val("'.$del_row['order'].'");$("#cash").val("'.($del_row['cash']/100).'");$("#nds").val("'.$del_row['nds'].'");Komiss();$("#ddd").val("1")';
}
?>
</script>
<form method="post" id="form_pay">
<input type="hidden" name="error" id="error" value="0">
<input type="hidden" name="plan_cash_int" id="plan_cash_int" value="0">
<input type="hidden" name="add_name" id="add_name" value="<?php echo $_SESSION['user_id'];?>">
<input type="hidden" name="pay_bill" id="pay_bill" value="0">
<input type="hidden" name="payment_source_id" id="payment_source_id" value="0">


<?php  if ($pay_id!="") {
echo '<input type="hidden" name="pay_id" value="'.$row['id'].'"><input type="hidden" name="edit" value="1">';

} ?>
<input type="hidden" name="ddd" id="ddd" value="0">
<fieldset style="width:93%;margin-top:0;"><legend>Платеж:</legend>
<table><tr><td><b><font size="4">№<?php echo $row['id'];?></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php if($pay_id!=''){switch ($row['status']) {case '0': $status='<font size="4" color="#FF4F4F">Не проведен</font>';break;case '1': $status='<font size="4" color="#6AAF3B">Проведен</font>';break;} echo $status;}?></b></td><td align="right" width="100">Дата:</td><td width="150"><input type="text" id="date_pay" name="date_pay" style="width:80px;" value="<?php if($pay_id==''){echo date('d/m/Y');} else {echo date('d/m/Y',strtotime($row['date']));}?>" class="input"></td><td><input type="checkbox" name="transaction" id="transaction" <?php if ($row['status']=='1'){echo 'checked disabled';}?>  onclick="if(this.checked){$('#prov').html('<u>ПРОВЕДЕНИЯ</u>');$('#transaction').val(1);} else {$('#prov').html('<u>ПЛАНИРОВАНИЯ</u>');$('#transaction').val(0);}" value="<?php echo $row['status'];?>">&nbsp;&nbsp;<font size="3">Провести</font></td></tr>
</table>
</fieldset>
<fieldset style="margin-top:5px;width:93%;">
<table cellspacing="5">
<tr><td align="right" width="100">Категория</td><td><select name="category"  id="category" class="select" onchange="Komiss();$('#way').val(0).change();$('#appointment').val(0).change();$('#appoint').html('')">
  <option value="1" onclick='$("#order").attr("disabled","");'>Основная</option>
  <option value="2" onclick='$("#order").attr("disabled","disabled");'>Дополнительная</option></select></td><td align="right" width="100"><b>Источник получатель</b></td><td><table cellspacing="5">
<tr><td><select name="payment_source"  id="payment_source" class="select" <?php if ($row['status']!='1'){echo 'onchange="this.options[this.selectedIndex].onclick();"';}?> style="width:180px;float:left;">
<option value='0'>Выберите...</option>
 <?php 
$query_bill = "SELECT `id`,`c_cash`,`c_bill`,`c_bank`,`company` FROM `bill` WHERE `delete`='0'";
$result_bill = mysql_query($query_bill) or die(mysql_error());
while($bill_info = mysql_fetch_row($result_bill)) {
echo '<option value='.$bill_info[0].' onclick=\'$("#payment_source_id").val("'.$bill_info[4].'");$("#pay_bill").val("'.$bill_info[0].'");$("#bill_info_bank").html("'.addslashes($bill_info[3]).'");$("#bill_info").html("<b>'.$bill_info[2].'</b>");$("#bill_info_balance").html("<font size=4>Баланс: <b>'.number_format($bill_info[1]/100, 2, ',', ' ').'</b></font> у.е.");\'>'.$bill_info[2].'</option>';

} ?> 
</select></td><td>

</td></tr></table>
</td></tr>

<tr><td align="right"><b>Направление платежа</b></td><td><select name="way"  id="way" class="select" onchange="$('#appointment').attr('disabled',''); if($(this).val()=='1'){$('#inc').html('+&nbsp;');if($('#category').val()=='1'){$('#app_select').load('control/pay_load.php?way=1&category=1', function(data){});}if($('#category').val()=='2'){$('#app_select').load('control/pay_load.php?way=1&category=2', function(data){});}} if($(this).val()=='2'){$('#inc').html('-&nbsp;');if($('#category').val()=='1'){$('#app_select').load('control/pay_load.php?way=2&category=1', function(data){});}if($('#category').val()=='2'){$('#app_select').load('control/pay_load.php?way=2&category=2', function(data){});}}">
  <option value="0">Выберите...</option>
  <option value="1">поступление</option>
  <option value="2">выплата</option>
  </select></td><td colspan="2" rowspan="3">
  <div id="bill_info" style="margin-left:20px;"></div>
    <div id="bill_info_bank" style="margin-left:20px;"></div>
  <div id="bill_info_balance" style="margin-left:20px;"></div>
 </td></tr>

<tr><td align="right">Способ платежа</td><td><select name="nds"  id="nds" class="select">
  <option value="33">...</option><option value="0" onclick='$("#payment_source").val(3).change();$("#bill_info").html("");$("#bill_info_balance").html("");$("#bill_info_bank").html("");$("#payment_source [value=1]").attr("disabled","disabled");$("#payment_source [value=2]").attr("disabled","");$("#payment_source [value=3]").attr("disabled","");$("#payment_source [value=4]").attr("disabled","");$("#payment_source [value=5]").attr("disabled","");'>без НДС</option>
  <option value="1" onclick='$("#payment_source").val(2).change();$("#bill_info").html("");$("#bill_info_balance").html("");$("#bill_info_bank").html("");$("#payment_source [value=1]").attr("disabled","disabled");$("#payment_source [value=2]").attr("disabled","");$("#payment_source [value=3]").attr("disabled","");$("#payment_source [value=4]").attr("disabled","");$("#payment_source [value=5]").attr("disabled","");'>с НДС</option>
  <option value="2" onclick='$("#payment_source").val(1).change();$("#pay_bill").val("1");$("#bill_info").html("<b>Наличные</b>");$("#bill_info_bank").html("");$("#bill_info_balance").html("<font size=4>Баланс: <b><?php echo number_format($bill_info[0]/100, 2, ',', ' ');?></b></font> у.е.");$("#payment_source [value=1]").attr("disabled","");$("#payment_source [value=2]").attr("disabled","disabled");$("#payment_source [value=3]").attr("disabled","disabled");$("#payment_source [value=4]").attr("disabled","disabled");$("#payment_source [value=5]").attr("disabled","disabled");'>НАЛ</option></select></td>
  
</tr>
<tr><td align="right"><b>Назначение</b></td><td>
<div id="app_select"><select name="appointment"  id="appointment" class="select">
   <option value="0">...</option>
 </select></div>
</td></tr> 
 
<tr><td  align="right"><b>Заявка</b>&nbsp;&nbsp;</td><td><input type="number" type="text" id="order" name="order" type="number" style="width:80px;display:inline;" value="" onchange="Komiss()" placeholder="-" class="input"/>&nbsp;&nbsp;

<a href="#" onclick="if($('#order').val()!=''&&$('#category').val()!='2'){
$('#plan_cash').load('control/pay_load.php?order_id='+$('#order').val()+'&order_type='+$('#order_type').val()+'&mode='+$('#appointment').val()+'&way='+$('#way').val(), function(data){
if(data=='Заявки не существует!') {$('#plan_cash').html('0');$('#error').val('1');toastr.error('Заявки не существует!');} else {$('#plan_cash_int').val(data);$('#error').val('0');}
});}"><img src="data/img/arrow-circle-double.png"></a></td><td align="right"><font size="4"><b>Сумма</b></font></td><td><input type="text" id="cash" name="cash" style="width:100px;" type="number" value="" onchange="Komiss()" placeholder="0" class="input"/> 

<select name="currency" style="width:70px;" id="currency" class="select">
	<option value="руб">руб</option>
	<option value="USD">USD</option>
	<option value="Euro">Euro</option>
</select>

</td></tr> 
</table>
</fieldset><fieldset style="width:93%;"><legend>Комментарий:</legend>
<table><tr><td width="400"><textarea cols="42" rows="2" name="pay_notify" class="input"><?php if(!empty($row['notify'])) echo $row['notify']; else echo 'Номер счёта '; ?></textarea>
</td><td width="200"><div id="car_ls" style="display: none;">&nbsp;&nbsp;&nbsp;Авто,прицеп:&nbsp;<select name="car_number" style="width:160px;" id="car_number" onchange="" class="select"><option value="0">Выберите...</option>
<?php 
include "config.php";
$query_car = "SELECT `id`,`name`,`number`,`type` FROM `vtl_auto` WHERE `delete`='0' ORDER BY `type` desc";
$result_car = mysql_query($query_car) or die(mysql_error());
while($car = mysql_fetch_row($result_car)) {
switch ($car[3]) {
case '1': $type='Т';break;
case '2': $type='П';break;
case '3': $type='Г';break;
case '4': $type='П';break;

}
echo '<option value='.$car[0].'>'.$type.'. '.$car[1].' - '.$car[2].'</option>';
}
?>
</select></div></td></tr></table></fieldset>
<br>
<div align="center" style="float:left;margin-left:120px;margin-top:-10px;"><input type="submit" id="save_pay" value="<?php if($pay_id!=''){echo 'Сохранить';} else {echo 'Создать';}?>" style="width: 250px;">
<input type="button" id="btnClose_pay" onclick="$('#fa_pay').dialog('close');" value="Отмена" style="width: 160px;">
</div>
<fieldset style="width:93%;margin-top:40px;"><legend>Информация:</legend>
<div style="height: 210px; overflow: auto">
<table cellspacing="5"><tr><td width="100" align="center"><img src="data/img/secure-payment.png"></td><td><b>Данный платёж создаётся для:</b><br>
<b><div id="prov" style="float:left;"><u>ПЛАНИРОВАНИЯ</u></div></b><div style="float:left;">&nbsp;оплаты&nbsp;</div><b><div id="appoint" style="float:left;"></div></b><div id="order_info" style="float:left;"></div><br><hr>
<div id="check_info"><table bgcolor="#FFFFFF" cellspacing="10"><tr><td width="220">Планируемая сумма:&nbsp;</td><td width="200">Предлагается:&nbsp;</td></tr><tr><td><b><font size="5"><div id="plan_cash" style="float:left;margin-left:20px;">0</div></font></b>&nbsp;у.е.&nbsp;</td><td><font size="5"><div id="inc" style="float:left;"></div> <div id="plan_pay" style="float:left;">0</div></font>&nbsp;у.е.</td></tr></table></div><hr>
</td></tr>

<?php if ($delete_id!="") {
echo '<tr><td colspan="2" align="center"><br><b><font size="3">Создан на основе удалённого платежа №'.$delete_id.' от '.date('d/m/Y',strtotime($del_row['date'])).'г</b></font></td></tr><input type="hidden" name="del_id" value="'.$delete_id.'">';
}?>

</table></div>
</fieldset>

</form>