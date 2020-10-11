<?php include "../../config.php";?>

<script type="text/javascript">

$('#Save_vtl_card_refill').button();
$('#btnClose_vtl_card_refill').button();

$("#date_refill").datepicker();
$('#date_refill').mask('99/99/9999'); 




// - - кнопка добавление машины - сохранение - - >
$("#form_vtl_card_refill").submit(function() {  
      
      
      var perfTimes = $("#form_vtl_card_refill").serialize(); 
      $.post("control/auto/vtl_card_refill.php", perfTimes, function(data) {
      
      var arr = data.split(/[|]/);
      $('#result').html(arr[0]);
      if(arr[1]==1){$("#card_load_info").load("/control/auto/vtl_card.php?mode=card&card_id=<?php echo $_GET['card_id'];?>");
      $("#fa_vtl_card_refill").dialog("close");}

 
      
      $("#result").dialog({ title: 'Готово' },{ width: 270 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });}); 
  return false;});     

<?php if(@$_GET['card_id']!=""){
$card_id=(int)$_GET['card_id'];
$query_card = "SELECT `card` FROM `vtl_oil_card` WHERE `id`='".mysql_escape_string($card_id)."'";
$result_card = mysql_query($query_card) or die(mysql_error());
$card = mysql_fetch_row($result_card);
}

if(@$_GET['card_p_id']!=""){
$card_p_id=(int)$_GET['card_p_id'];
$query_refill = "SELECT `id`,`date`,`trip`,`way`,`cash`,`l`,`card_cash` FROM `drivers_report` WHERE `id`='".mysql_escape_string($card_p_id)."'";
$result_refill = mysql_query($query_refill) or die(mysql_error());
$card_refill = mysql_fetch_row($result_refill);

  } 

?>    
 
 
</script>
<form method="post" id="form_vtl_card_refill">






<?php 
if (@$_GET['edit']=="1"){echo '<input type="hidden" name="edit" id="edit" value="1"><input type="hidden" name="card_p_id" id="card_p_id" value="'.(int)$_GET['card_p_id'].'"><input type="hidden" name="cash_refill_old" id="cash_refill_old" value="'.($card_refill[4]/100).'">';}


echo '<input type="hidden" name="card_id" id="card_id" value="'.(int)$_GET['card_id'].'">';
?>

<table cellspacing="10">
<tr><td align="right">Номер карты:</td><td><b><?php echo $card[0];?></b></td>
</tr>
<tr><td align="right">Дата:</td><td><input type="text" id="date_refill" name="date_refill" style="width:80px;" value="<?php if($card_p_id==''){echo date('d/m/Y');} else {echo date('d/m/Y',strtotime($card_refill[1]));}?>" class="input"></td>
</tr>
<tr><td align="right">Сумма:</td><td><input type="text" id="cash_refill" name="cash_refill" style="width:70px;" type="number" value="<?php if($card_p_id!=''){echo ($card_refill[4]/100);}?>" onchange="$('#cash_refill').val($('#cash_refill').val().replace(/,+/,'.'));" placeholder="0" class="input"/> руб.</td>
</tr>
</table>




<hr>&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" id="Save_vtl_card_refill" value="Сохранить" style="width: 160px;">
<input type="button" id="btnClose_vtl_card_refill" onclick="$('#fa_vtl_card_refill').dialog('close');" value="Закрыть" style="width: 100px;">	

</form>