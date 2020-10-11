<?php 

include "../../config.php";
?>

<script type="text/javascript">
$(function(){


$('#add_date_tr_all').mask('99/99/9999');
$('#btnSave_doc').button();

// - - кнопка добавление назначений - сохранение - - >
$("#form_add_adidas").submit(function() {  
      var perfTimes = $("#form_add_adidas").serialize(); 
      $.post("../../control/add_adidas.php", perfTimes, function(data) {
 
 
 

	  
	  
	  var arr = data.split(/[|]/);
      $('#result').html(arr[0]);
      if(arr[1]==1){$("#fa_add_adidas").dialog("close");}
	  
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });});
     return false;  
  });
});




</script>

<form method="post" id="form_add_adidas">
<fieldset style="width:93%;"><legend>Счет, выставленный Клиенту:</legend>
<table cellpadding="5"><tr><td align="right"><b>Номер счета:</b>&nbsp;</td><td width="135">№<input type="text" id="add_bill_cl" name="add_bill_cl" style="width:95px;" class="input" value="" placeholder="0"/></td>
<td align="right">Дата выставл.:&nbsp;</td><td><input type="text" id="add_date_cl_bill" name="add_date_cl_bill" style="width:80px;" class="input" value="<?php echo date("d/m/Y");?>" /></td></tr></table>
</fieldset>

<fieldset  style="width:93%;"><legend>Список заявок Адидас:</legend>
<input type="text" id="add_adidas_ord" name="add_adidas_ord" style="width:96%;" class="input" value="" placeholder="номера через запятую!"/>
</fieldset>


<input type="submit" id="btnSave_doc" value="Сохранить" style="width: 250px;float:left;margin-top:10px;margin-left:100px;height:40px;">
<input type="button" id="btnClose_doc" class="button2" value="Закрыть" onclick="$('#fa_add_adidas').dialog('close');" style="width: 150px;height:30px;">
</form>
<br>
</div>