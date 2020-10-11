<?php include "../../config.php";?>

<script type="text/javascript">

$('#Save_vtl_card').button();
$('#btnClose_vtl_card').button();

$('#card').mask('9999'); 




// - - кнопка добавление машины - сохранение - - >
$("#form_vtl_card").submit(function() {  
      
      
      var perfTimes = $("#form_vtl_card").serialize(); 
      $.post("control/auto/vtl_card_add.php", perfTimes, function(data) {
      
      var arr = data.split(/[|]/);
      $('#result').html(arr[0]);
      if(arr[1]==1){$("#card_number").load('/control/auto/load_card.php?mode=card');
      $("#fa_vtl_card").dialog("close");}

 
      
      $("#result").dialog({ title: 'Готово' },{ width: 270 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });}); 
  return false;});     

<?php if(@$_GET['card_id']!=""){
$card_id=(int)$_GET['card_id'];
$query_card = "SELECT * FROM `vtl_oil_card` WHERE `id`='".mysql_escape_string($card_id)."'";
$result_card = mysql_query($query_card) or die(mysql_error());
$card = mysql_fetch_row($result_card);
    
echo "$('#card').val('".$card[1]."');$('#auto').val(".$card[2].").change;";
}
?>    
 
 
</script>
<form method="post" id="form_vtl_card">






<?php 
if (@$_GET['edit']=="1"){echo '<input type="hidden" name="edit" id="edit" value="1"><input type="hidden" name="card_id" id="card_id" value="'.(int)$_GET['card_id'].'">';}
?>

<table align="center" cellspacing="10">
<tr><td align="right">Номер карты:</td><td><input type="text" id="card" name="card" style="width:50px;" type="number" value=""  placeholder="0000" class="input"></td>
</tr>
<tr><td><b>Автотранспорт:</b></td><td><select name="auto" style="width:140px;" id="auto" onchange="" class="select"><option value="0">Выберите...</option>
<?php $query_car = "SELECT `id`,`name`,`number` FROM `vtl_auto` WHERE (`type`='1' OR `type`='3')AND `delete`='0'";
$result_car = mysql_query($query_car) or die(mysql_error());
while($car = mysql_fetch_row($result_car)) {
echo '<option value='.$car[0].'>'.$car[1].' ('.$car[2].')</option>';

}



?>
</select></td></tr>
</table>



<hr>
&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" id="Save_vtl_card" value="Сохранить" style="width: 160px;">
<input type="button" id="btnClose_vtl_card" onclick="$('#fa_vtl_card').dialog('close');" value="Закрыть" style="width: 100px;">	

</form>