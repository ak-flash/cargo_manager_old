<script type="text/javascript">
$(function(){


$('#date_end_car_report').mask('99/99/9999');
$('#date_start_car_report').mask('99/99/9999');


 
   // - - работа с датами - - >
$("#date_start_car_report").datepicker({
   onSelect: function(dateText, inst) {$("#date_end_car_report").val(dateText);}

});
$("#date_end_car_report").datepicker();
 
$('#Report_car').button();
$('#btnClose_car_report').button();

});

</script>


<div id="result"></div>
<fieldset><legend>Дополнительно: </legend><table cellpadding="5"><tr><td colspan="2">За период: с <input type="text" id="date_start_car_report" name="date_start" style="width:80px;" value="" class="input"> по <input type="text" id="date_end_car_report" name="date_end" style="width:80px;" class="input"></td><td rowspan="2">&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="Report_car" value="Отчёт" style="width: 100px;height:60px;" onclick='window.location.href="control/car_report.php?mode=car&date_start="+$("#date_start_car_report").val()+"&date_end="+$("#date_end_car_report").val()+"&car_number="+$("#car_number").val();'></td></tr>
<tr><td align="right">Машина:</td><td><select name="car_number" style="width:165px;" id="car_number" onchange="" class="select"><option value="0">Выберите...</option>
<?php 
include "config.php";
$query_car = "SELECT `id`,`name`,`number`,`type` FROM `vtl_auto` WHERE `delete`='0' AND (`type`='1' OR `type`='3')";
$result_car = mysql_query($query_car) or die(mysql_error());
while($car = mysql_fetch_row($result_car)) {
echo '<option value='.$car[0].'>'.$car[1].' - '.$car[2].'</option>';
}
?>
</select></td></tr>
</table>




</fieldset>

<input type="button" id="btnClose_car_report" value="Закрыть" onclick="$('#fa_car_report').dialog('close');" style="width: 150px;float:right;">

