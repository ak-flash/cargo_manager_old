<?php include "../../config.php";
if (@$_GET['trip']!=""){
$trip=(int)$_GET['trip'];
$card_id=(int)$_GET['card_id'];
}
?>
<script type="text/javascript">

$('#appoints').load('control/auto/report_load.php?trip=<?php echo $trip;?>');

$("#date_report").datepicker();
$('#date_report').mask('99/99/9999'); 

// - - кнопка добавление назначений - сохранение - - >
$("#form_drv_report").submit(function() {  
      var perfTimes = $("#form_drv_report").serialize(); 
      $.post("control/auto/add_drv_report.php", perfTimes, function(data) {
      
      var arr = data.split(/[|]/);
      if(arr[1]==1){$('#appoints').load('control/auto/report_load.php?trip=<?php echo $trip;?>');
      $('#report_cash').val('0');
      $('#report_way').val(0).change();
      document.getElementById('petrol1').style.display='none';
      document.getElementById('petrol2').style.display='none';
      $('#litrov').val('0');
      }
      $('#result').html(arr[0]);
 
      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });});
     return false;  
  });
  


 
$('#Save_drv_report').button();
$('#btnClose_drv_report').button();

</script>
<form method="post" id="form_drv_report">
<input type="hidden" name="card_id" id="card_id" value="<?php echo $card_id;?>">


<div id="result"></div>

<input type="hidden" name="trip" id="trip" value="<?php echo $trip;?>">

<fieldset style="width:93%;"><legend>Статьи расходов:</legend>


<div id="appoints" style="height: 20em; overflow: auto"></div>



</fieldset>

<fieldset style=""><legend><b>Добавить статью расходов:</b></legend>
<table><tr><td rowspan="3" width="130" valign="top"><input type="text" id="date_report" name="date_report" style="width:80px;" value="<?php echo date('d/m/Y');?>" class="input"><br><img src="data/img/secure-payment.png"></td><td align="right">Категория:</td><td><select name="report_way"  id="report_way" class="select">
   <option value="0">Выберите...</option>
   <!--- <option value="1">Суточные</option> --->
   <option value="2">Стоянка</option>
   <option value="3">Штрафы ГАИ</option>
   <option value="4">Платная дорога</option>
<option value="5">Охрана</option>
   <option value="6">Автозапчасти</option>
   <option value="7">Шиномонтаж</option>
   <option value="8">Услуги ремонта</option>
   <option value="9" onclick="document.getElementById('petrol1').style.display='inline';document.getElementById('petrol2').style.display='inline';">ГСМ (НАЛ)</option>
   <option value="10" onclick="document.getElementById('petrol1').style.display='inline';document.getElementById('petrol2').style.display='inline';document.getElementById('car_ls1').style.display='inline';document.getElementById('car_ls2').style.display='inline';$('#report_card_number').val('<?php echo $card_id;?>').change();">ГСМ (БезНАЛ)</option>

   <option value="12">Простой</option>
   <option value="13">Лизинг</option>
   <option value="14">Страховка</option>
   <option value="15">Мобильная связь</option>
   <option value="33">Возврат</option>
 </select></td><td align="right" width="80">Сумма:</td><td><input name="report_cash" id="report_cash" style="width: 70px;"  class="input" value="" onchange="$('#report_cash').val($('#report_cash').val().replace(/,+/,'.'));"> руб.</td></tr>
<tr><td align="right" rowspan="2">Комментарий:</td><td rowspan="2"><textarea cols="22" rows="4" name="report_notify"></textarea></td><td align="right"><div id="petrol1" style="display: none;">Топливо:</div></td><td><div id="petrol2" style="display: none;"><input name="litrov" id="litrov" style="width: 30px;"  value="" class="input" onchange="$('#litrov').val($('#litrov').val().replace(/,+/,'.'));"> л.</div></td></tr><tr><td align="right"><div id="car_ls1" style="display: none;">Карта:</div></td><td><div id="car_ls2" style="display: none;"><select name="report_card_number" id="report_card_number" style="width:60px;"  class="select"><option value="0">...</option>
<?php 

$query_card = "SELECT `id`,`card` FROM `vtl_oil_card` WHERE `delete`='0' ORDER BY `id` desc";
$result_card = mysql_query($query_card) or die(mysql_error());
while($card = mysql_fetch_row($result_card)) {
echo '<option value="'.$card[0].'">'.$card[1].'</option>';
}
?>
</select></div></td></tr></table>

<br>
<input type="submit" id="Save_drv_report" value="Добавить" style="margin-left:50px;width: 250px;">
<input type="button" id="btnClose_drv_report" value="Закрыть" onclick="$('#fa_report').dialog('close');jQuery('#table_tr_trip').trigger('reloadGrid');" style="width: 150px;">
</fieldset>
</form>