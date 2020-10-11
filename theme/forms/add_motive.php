<?php 
include "../../config.php";

$query = "SELECT * FROM `settings`";
$result = mysql_query($query) or die(mysql_error());
$motive = mysql_fetch_array($result);
?>

<script type="text/javascript">

$('#save_motive').button();
$('#btnClose_motive').button();





$("#form_motive").submit(function() {  
  

   

   
     
      var perfTimes = $("#form_motive").serialize(); 
      $.post("control/add_motive.php", perfTimes, function(data) {

      var arr = data.split(/[|]/);
      $('#result').html(arr[0]);
      if(arr[1]==1){$("#fa_worker").dialog("close");}
      
      $("#result").dialog({ title: 'Готово' },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });}); 

  
  return false;
    
  }); 


</script>
<form method="post" id="form_motive">



<fieldset style="width:90%;">
<table cellpadding="5" border="1" style="width:99%;border-collapse: collapse;border-style: solid;border-color: black;">
<tr><td width="100" bgcolor="#DBDBDB" align="center">Клиент (S<b>к</b>)</td><td width="150" bgcolor="#DBDBDB" align="center">Перевозчик (S<b>п</b>)</td><td width="250" bgcolor="#DBDBDB">Комиссия (<b>К</b>)</td></tr>

<tr><td align="center">с НДС</td><td align="center">с НДС</td><td><b>К</b>=S<b>к</b>-S<b>п</b></td></tr>
<tr><td align="center">c НДС</td><td align="center">без НДС</td><td><b>К</b>=(S<b>к</b>-S<b>к</b>/<b><input type="text" name="motive_2" id="motive_2" value="<?php echo $motive['motive_2'];?>" style="width:40px;"></b>)-S<b>п</b></td></tr>
<tr><td align="center">c НДС</td><td align="center">НАЛ</td><td><b>К</b>=S<b>к</b>-S<b>к</b>*<input type="text" name="motive_3" id="motive_3" value="<?php echo $motive['motive_3'];?>" style="width:40px;">-S<b>п</b></td></tr>
<tr><td align="center">без НДС</td><td align="center">с НДС</td><td><b>К</b>=S<b>к</b>-S<b>п</b>-(S<b>п</b>/<input type="text" name="motive_4" id="motive_4" value="<?php echo $motive['motive_4'];?>" style="width:40px;">)</td></tr>
<tr><td align="center">без НДС</td><td align="center">без НДС</td><td><b>К</b>=S<b>к</b>-S<b>п</b></td></tr>

<tr><td align="center">без НДС</td><td align="center">НАЛ</td><td><b>К</b>=S<b>к</b>-S<b>к</b>*<input type="text" name="motive_6" id="motive_6" value="<?php echo $motive['motive_6'];?>" style="width:40px;">-S<b>п</b></td></tr>

<tr><td align="center">НАЛ</td><td align="center">с НДС</td><td><b>К</b>=S<b>к</b>-S<b>п</b>+S<b>п</b>*<input type="text" name="motive_7" id="motive_7" value="<?php echo $motive['motive_7'];?>" style="width:40px;"></td></tr>
<tr><td align="center">НАЛ</td><td align="center">без НДС</td><td><b>К</b>=S<b>к</b>-S<b>п</b>+S<b>п</b>*<input type="text" name="motive_8" id="motive_8" value="<?php echo $motive['motive_8'];?>" style="width:40px;"></td></tr>

<tr><td align="center">НАЛ</td><td align="center">НАЛ</td><td><b>К</b>=S<b>к</b>-S<b>п</b></td></tr>

</table>

</fieldset>

<div align="center"><input type="submit" id="save_motive" value="Сохранить" style="width: 220px;">
<input type="button" id="btnClose_motive" onclick="$('#fa_worker').dialog('close');" value="Отмена" style="width: 120px;">
</div>
</form>


