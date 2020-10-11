<?php 
include "../../config.php";

$query = "SELECT `id`,`name` FROM `company` ORDER BY `Id` ASC";
$result = mysql_query($query) or die(mysql_error());
while($company= mysql_fetch_row($result)) {
$source.='<option value='.$company[0].'>«'.$company[1].'»</option>';
}
?>







<script type="text/javascript">
$('#save_pay_group').button();
$('#btnClose_pay_group').button();
$("#date_pay_group").datepicker();




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



$("#form_pay_group").submit(function() {  
  validate=true;    

   
   if(validate){ 
   
     $("#save_pay_group").attr("disabled","disabled");
     
      var perfTimes = $("#form_pay_group").serialize(); 
      $.post("control/add_listpay_tr.php?create=gr&mode=group", perfTimes, function(data) {
      
      var arr = data.split(/[|]/);
      $('#result').html(arr[0]);
      if(arr[1]==1){$("#fa_pay_group").dialog("close");} else $("#save_pay_group").attr("disabled","");
      
      $("#result").dialog({ title: 'Готово' },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");jQuery("#table").trigger("reloadGrid"); } } });}); 
 }
  
  return false;
    
  }); 

$('table input').keydown(function(e) {
    var td;
    switch (e.keyCode) {
      case 39: // right
        td = $(this).parent('td').next();
        break;
      
      case 37: // left
        td = $(this).parent('td').prev();
        break;
        
      case 40: // down
        var i = $(this).parent().index() + 1;
        td = $(this).closest('tr').next().find('td:nth-child(' + i + ')');
        break;
        
      case 38: // up
        var i = $(this).parent().index() + 1;
        td = $(this).closest('tr').prev().find('td:nth-child(' + i + ')');
        break;
    }
    td.find('input').focus();
  });
</script>
<form method="post" id="form_pay_group">

<fieldset style="width:93%;margin-top:0;"><legend>Платежи:</legend>
<table><tr><td align="right" width="50">Дата:</td><td width="150"><input type="text" id="date_pay_group" name="date_pay_group" style="width:80px;" value="<?php echo date('d/m/Y');?>" class="input"></td><td><input type="checkbox" name="transaction" id="transaction" onclick="if(this.checked){$('#prov').html('<u>ПРОВЕДЕНИЕ</u>');$('#transaction').val(1);} else {$('#prov').html('<u>ПЛАНИРОВАНИЕ</u>');$('#transaction').val(0);}" value="0">&nbsp;&nbsp;<font size="3">Провести</font></td></tr>
</table>
</fieldset>

<fieldset style="width:93%;margin-top:0;"><legend>По заявкам:</legend>
<table style="width:98%;border-collapse: collapse;" border="1" align="center">
<tr style="background: #b0e0e6;"><td width="90" align="center"><b>Заявка:</b></td><td width="50" align="center">Оплатить полностью</td><td width="100" align="center"><b>Сумма заявки</b></td><td align="center"><b>Сумма оплаты</b></td>
</tr>

<tr><td><input type="text" name="order_pay[]" style="width:80px;" id="ord_pay_1" value="" class="input" onchange="$('#plan_cash_1').load('control/pay_load.php?order_id='+$(this).val()+'&mode=2&way=2', function(data){
if(data=='Заявки не существует!') {$('#plan_cash_1').html('');alert('Заявки '+$('#ord_pay_1').val()+' не существует!');$('#ord_pay_1').val('');} else if(data=='Заявка оплачена!') {$('#plan_cash_1').html('');alert('Заявка '+$('#ord_pay_1').val()+' оплачена!');$('#ord_pay_1').val('');} else {$('#plan_cash_1').html('&nbsp;&nbsp;'+data+'<input name=cash[] type=hidden value='+data+'>')};
});"></td><td align="center"><input name="transaction_full[]" id="trans_id_1" value="0" type="hidden"><input type="checkbox" value="1" onclick="if(this.checked){$(this).val(1);$('#trans_id_1').val(1);} else {$(this).val(0);$('#trans_id_1').val(0);}"></td><td><div id="plan_cash_1"></div></td><td><input name="cash_pay[]" type="text" style="width:80px;" class="input" value=""></td></tr>




<tr><td><input type="text" name="order_pay[]" style="width:80px;" id="ord_pay_2" value="" class="input" onchange="$('#plan_cash_2').load('control/pay_load.php?order_id='+$(this).val()+'&mode=2&way=2', function(data){
if(data=='Заявки не существует!') {$('#plan_cash_2').html('');alert('Заявки '+$('#ord_pay_2').val()+' не существует!');$('#ord_pay_2').val('');} else if(data=='Заявка оплачена!') {$('#plan_cash_2').html('');alert('Заявка '+$('#ord_pay_2').val()+' оплачена!');$('#ord_pay_2').val('');} else {$('#plan_cash_2').html('&nbsp;&nbsp;'+data+'<input name=cash[] type=hidden value='+data+'>')};
});"></td><td align="center"><input name="transaction_full[]" id="trans_id_2" value="0" type="hidden"><input type="checkbox" value="1" onclick="if(this.checked){$(this).val(1);$('#trans_id_2').val(1);} else {$(this).val(0);$('#trans_id_2').val(0);}"></td><td><div id="plan_cash_2"></div></td><td><input name="cash_pay[]" type="text" style="width:80px;" class="input" value=""></td></tr>

<tr><td><input type="text" name="order_pay[]" style="width:80px;" id="ord_pay_3" value="" class="input" onchange="$('#plan_cash_3').load('control/pay_load.php?order_id='+$(this).val()+'&mode=2&way=2', function(data){
if(data=='Заявки не существует!') {$('#plan_cash_3').html('');alert('Заявки '+$('#ord_pay_3').val()+' не существует!');$('#ord_pay_3').val('');} else if(data=='Заявка оплачена!') {$('#plan_cash_3').html('');alert('Заявка '+$('#ord_pay_3').val()+' оплачена!');$('#ord_pay_3').val('');} else {$('#plan_cash_3').html('&nbsp;&nbsp;'+data+'<input name=cash[] type=hidden value='+data+'>')};
});"></td><td align="center"><input name="transaction_full[]" id="trans_id_3" value="0" type="hidden"><input type="checkbox" value="1" onclick="if(this.checked){$(this).val(1);$('#trans_id_3').val(1);} else {$(this).val(0);$('#trans_id_3').val(0);}"></td><td><div id="plan_cash_3"></div></td><td><input name="cash_pay[]" type="text" style="width:80px;" class="input" value=""></td></tr>

<tr><td><input type="text" name="order_pay[]" style="width:80px;" id="ord_pay_4" value="" class="input" onchange="$('#plan_cash_4').load('control/pay_load.php?order_id='+$(this).val()+'&mode=2&way=2', function(data){
if(data=='Заявки не существует!') {$('#plan_cash_4').html('');alert('Заявки '+$('#ord_pay_4').val()+' не существует!');$('#ord_pay_4').val('');} else if(data=='Заявка оплачена!') {$('#plan_cash_4').html('');alert('Заявка '+$('#ord_pay_4').val()+' оплачена!');$('#ord_pay_4').val('');} else {$('#plan_cash_4').html('&nbsp;&nbsp;'+data+'<input name=cash[] type=hidden value='+data+'>')};
});"></td><td align="center"><input name="transaction_full[]" id="trans_id_4" value="0" type="hidden"><input type="checkbox" value="1" onclick="if(this.checked){$(this).val(1);$('#trans_id_4').val(1);} else {$(this).val(0);$('#trans_id_4').val(0);}"></td><td><div id="plan_cash_4"></div></td><td><input name="cash_pay[]" type="text" style="width:80px;" class="input" value=""></td></tr>

<tr><td><input type="text" name="order_pay[]" style="width:80px;" id="ord_pay_5" value="" class="input" onchange="$('#plan_cash_5').load('control/pay_load.php?order_id='+$(this).val()+'&mode=2&way=2', function(data){
if(data=='Заявки не существует!') {$('#plan_cash_5').html('');alert('Заявки '+$('#ord_pay_5').val()+' не существует!');$('#ord_pay_5').val('');} else if(data=='Заявка оплачена!') {$('#plan_cash_5').html('');alert('Заявка '+$('#ord_pay_5').val()+' оплачена!');$('#ord_pay_5').val('');} else {$('#plan_cash_5').html('&nbsp;&nbsp;'+data+'<input name=cash[] type=hidden value='+data+'>')};
});"></td><td align="center"><input name="transaction_full[]" id="trans_id_5" value="0" type="hidden"><input type="checkbox" value="1" onclick="if(this.checked){$(this).val(1);$('#trans_id_5').val(1);} else {$(this).val(0);$('#trans_id_5').val(0);}"></td><td><div id="plan_cash_5"></div></td><td><input name="cash_pay[]" type="text" style="width:80px;" class="input" value=""></td></tr>



<tr><td><input type="text" name="order_pay[]" style="width:80px;" id="ord_pay_6" value="" class="input" onchange="$('#plan_cash_6').load('control/pay_load.php?order_id='+$(this).val()+'&mode=2&way=2', function(data){
if(data=='Заявки не существует!') {$('#plan_cash_6').html('');alert('Заявки '+$('#ord_pay_6').val()+' не существует!');$('#ord_pay_6').val('');} else if(data=='Заявка оплачена!') {$('#plan_cash_6').html('');alert('Заявка '+$('#ord_pay_6').val()+' оплачена!');$('#ord_pay_6').val('');} else {$('#plan_cash_6').html('&nbsp;&nbsp;'+data+'<input name=cash[] type=hidden value='+data+'>')};
});"></td><td align="center"><input name="transaction_full[]" id="trans_id_6" value="0" type="hidden"><input type="checkbox" value="1" onclick="if(this.checked){$(this).val(1);$('#trans_id_6').val(1);} else {$(this).val(0);$('#trans_id_6').val(0);}"></td><td><div id="plan_cash_6"></div></td><td><input name="cash_pay[]" type="text" style="width:80px;" class="input" value=""></td></tr>


<tr><td><input type="text" name="order_pay[]" style="width:80px;" id="ord_pay_7" value="" class="input" onchange="$('#plan_cash_7').load('control/pay_load.php?order_id='+$(this).val()+'&mode=2&way=2', function(data){
if(data=='Заявки не существует!') {$('#plan_cash_7').html('');alert('Заявки '+$('#ord_pay_7').val()+' не существует!');$('#ord_pay_7').val('');} else if(data=='Заявка оплачена!') {$('#plan_cash_7').html('');alert('Заявка '+$('#ord_pay_7').val()+' оплачена!');$('#ord_pay_7').val('');} else {$('#plan_cash_7').html('&nbsp;&nbsp;'+data+'<input name=cash[] type=hidden value='+data+'>')};
});"></td><td align="center"><input name="transaction_full[]" id="trans_id_7" value="0" type="hidden"><input type="checkbox" value="1" onclick="if(this.checked){$(this).val(1);$('#trans_id_7').val(1);} else {$(this).val(0);$('#trans_id_7').val(0);}"></td><td><div id="plan_cash_7"></div></td><td><input name="cash_pay[]" type="text" style="width:80px;" class="input" value=""></td></tr>


<tr><td><input type="text" name="order_pay[]" style="width:80px;" id="ord_pay_8" value="" class="input" onchange="$('#plan_cash_8').load('control/pay_load.php?order_id='+$(this).val()+'&mode=2&way=2', function(data){
if(data=='Заявки не существует!') {$('#plan_cash_8').html('');alert('Заявки '+$('#ord_pay_8').val()+' не существует!');$('#ord_pay_8').val('');} else if(data=='Заявка оплачена!') {$('#plan_cash_8').html('');alert('Заявка '+$('#ord_pay_8').val()+' оплачена!');$('#ord_pay_8').val('');} else {$('#plan_cash_8').html('&nbsp;&nbsp;'+data+'<input name=cash[] type=hidden value='+data+'>')};
});"></td><td align="center"><input name="transaction_full[]" id="trans_id_8" value="0" type="hidden"><input type="checkbox" value="1" onclick="if(this.checked){$(this).val(1);$('#trans_id_8').val(1);} else {$(this).val(0);$('#trans_id_8').val(0);}"></td><td><div id="plan_cash_8"></div></td><td><input name="cash_pay[]" type="text" style="width:80px;" class="input" value=""></td></tr>

</table>
</fieldset>
<br>
<div align="center" style="float:left;margin-left:60px;margin-top:-10px;"><input type="submit" id="save_pay_group" value="Создать" style="width: 150px;">
<input type="button" id="btnClose_pay_group" onclick="$('#fa_pay_group').dialog('close');" value="Отмена" style="width: 100px;">
</div>

</form>