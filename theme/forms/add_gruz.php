<?php 
include "../../config.php";
if (@$_GET['cl_id']!=""||intval($_GET['cl_id'])){
$cl_id=$_GET['cl_id'];
}

if (@$_GET['gruz_id']!=""||intval($_GET['gruz_id'])){
$gruz_id=$_GET['gruz_id'];

$query_check = "SELECT * FROM `cl_gruz` WHERE `id`='".mysql_escape_string($gruz_id)."'";
$result_check = mysql_query($query_check) or die(mysql_error());
$gruz = mysql_fetch_array($result_check);


} else {session_start();}
?>

<script type="text/javascript">

$('#save_gruz').button();
$('#btnClose_gruz').button();

<?php if ($gruz_id!="") {echo '$("#status").val('.$gruz['status'].').change();$("#s_manager").val("'.$gruz['s_man'].'").change();';}
if($gruz['status']==3)echo 'document.getElementById("order").style.visibility = "visible";';
if($gruz['s_contract']==1) echo '$("#s_contract").attr("checked", "checked");';

?>



$("#form_gruz").submit(function() {  
  

   

   
     
      var perfTimes = $("#form_gruz").serialize(); 
      $.post("control/auto/add_gruz.php", perfTimes, function(data) {

      var arr = data.split(/[|]/);
      $('#result').html(arr[0]);
      if(arr[1]==1){$("#fa_gruz").dialog("close");
      $("#gruz_show-<?php echo $cl_id;?>").load("/control/auto/gruz_show.php?mode=show&cl_id=<?php echo $cl_id;?>");}
      
      $("#result").dialog({ title: 'Готово' },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });}); 

  
  return false;
    
  }); 


</script>
<form method="post" id="form_gruz">



<?php  if ($gruz_id!="") {
echo '<input type="hidden" name="gruz_id" value="'.$gruz['id'].'">
<input type="hidden" name="edit" value="1">';


} 
echo '<input type="hidden" name="cl_id" value="'.$cl_id.'">';
?>

<fieldset style="width:90%;">
<table><tr><td align="right" width="100">Загрузка:</td><td width="100"><input type="text" id="city_in" name="city_in" style="width:225px;" value="<?php if($gruz_id!=0){echo $gruz['city_in'];}?>" class="input"/></td></tr>
<tr><td align="right">Выгрузка:</td><td width="100"><input type="text" id="city_out" name="city_out" style="width:225px;" value="<?php if($gruz_id!=0){echo $gruz['city_out'];}?>" class="input"/></td></tr>



<tr><td align="right">Ставка клиента:</td><td><input type="text" id="cash_cl" name="cash_cl" style="width:85px;" value="<?php if($gruz_id!=0){echo $gruz['cash_cl'];}?>" class="input"/> руб.</td></tr>
<tr><td align="right">Ставка Транп.компании:</td><td><input type="text" id="cash_vtl" name="cash_vtl" style="width:85px;" value="<?php if($gruz_id!=0){echo $gruz['cash_vtl'];}?>" class="input"/> руб.</td></tr>

<?php if($cl_id==1) {echo '<tr><td align="right"><b>Сельта менедж.:</b></td><td><select name="s_manager" id="s_manager" style="width:160px; font-size: 18px;" class="select"><option value="0">Выберите...</option>';
$query = "SELECT `id`,`s_name` FROM `cl_selta` ORDER BY `s_name` ASC";
$result = mysql_query($query) or die(mysql_error());
while($s_user= mysql_fetch_row($result)) {
$pieces = explode(" ", $s_user[1]);
$print_add_name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
echo '<option value="'.$s_user[0].'">'.$print_add_name.'</option>';
}

echo '</select>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="s_contract" id="s_contract" value="'.$gruz['s_contract'].'" onclick=\'if(this.checked)$("#s_contract").val(1); else $("#s_contract").val(0);\'>&nbsp;<b>контракт</b></td></tr>';
}
?>

<tr><td align="right"><b>Ответственный:</b></td><td>

<select name="manager" id="manager" style="width:200px; font-size: 18px;" class="select"><option value="">Выберите...</option>
<?php $query = "SELECT `id`,`name` FROM `workers` WHERE `delete`='0' AND `group`='3' ORDER BY `name` ASC";
$result = mysql_query($query) or die(mysql_error());
while($user= mysql_fetch_row($result)) {
$pieces = explode(" ", $user[1]);
$print_add_name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
if($gruz['manager']==$print_add_name) echo '<option value="'.$print_add_name.'" selected
>'.$print_add_name.'</option>'; else echo '<option value="'.$print_add_name.'">'.$print_add_name.'</option>';
}
?>

</select></td></tr>

<tr><td align="right"><b>Статус:</b></td><td>

<select name="status" id="status" style="width:160px; font-size: 18px;" class="select">
<option value="0" onclick='document.getElementById("order").style.visibility = "hidden";'>не актуальна</option>
<option value="1" onclick='document.getElementById("order").style.visibility = "hidden";' selected>активна</option>
<option value="2" onclick='document.getElementById("order").style.visibility = "hidden";'>в процессе</option>
<option value="3" onclick='document.getElementById("order").style.visibility = "visible";'>закрыта</option>
</select></td></tr>

<tr><td align="right"><b>Заявка:</b></td><td><input type="text" id="order" name="order" style="width:85px;visibility:hidden;" value="<?php if($gruz_id!=0&&$gruz['order']!=0){echo $gruz['order'];}?>" class="input"/></td></tr>

</table>
</fieldset>

<fieldset style="width:90%;"><legend>Комментарий:</legend>
<textarea cols="52" rows="3" name="notify"><?php echo $gruz['notify'];?></textarea>
</fieldset>

<div align="center"><input type="submit" id="save_gruz" value="<?php if($gruz_id!=''){echo 'Сохранить';} else {echo 'Создать';}?>" style="width: 220px;">
<input type="button" id="btnClose_gruz" onclick="$('#fa_gruz').dialog('close');" value="Отмена" style="width: 120px;">
</div>
</form>


