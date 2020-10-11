<?php 
    session_start();
    include "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Настройки АСТП</title>
<?php include_once("data/header.html");?>
<script type="text/javascript">
$(function(){
$("#notify_menu").css({"background-color":"#99BFAD","color":"#000"});
$('#importpays').button();

$("#inbase_insert").submit(function() {  
      var perfTimes = $("#inbase_insert").serialize(); 
      $.post("control/inbase.php", perfTimes, function(data) {
      
      var arr = data.split(/[|]/);
      if(arr[1]==1){window.location.href='pays.html'}
      $('#result').html(arr[0]);
     
     
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");  } } });});
     return false;  
  });
  
});
</script>

</head>
<body>	
<?php include "data/menu.html";?>
<hr style="width: 99%; height: 2px;" />
<div id="result"></div>

<form action="inbase.php" id="inbase_insert" method="post">
<fieldset style="margin:30px;font-size:15px;"><legend>Импорт платежей</legend>
<?php $appoint=(int)$_POST['pays_appoints'];
if($appoint!=0){
$query = "SELECT `app`,`Id` FROM `pays_appoints` WHERE `Id`='".$appoint."'";
$result = mysql_query($query) or die(mysql_error());
$pays_app= mysql_fetch_row($result);
echo '<font size="5">Назначение платежей: <b>'.$pays_app[0].'</b></font><br><br><input type="hidden" name="app" value="'.$pays_app[1].'">';
} else echo "Выберите категорию!"


?>
<div id="listpay" style="height: 38em; overflow: auto">


<table cellpadding="5"  style="width:100%;overflow:auto;border-collapse: collapse;border-style: solid;border-color: black;" border="1"><tr><td bgcolor="#edf1f3" align=center><b>Упр.</b></td><td bgcolor="#edf1f3" align=center><b>№</b></td><td bgcolor="#edf1f3" align=center><b>Дата</b></td><td bgcolor="#edf1f3" align=center><b>Сумма (руб.)</b></td><td bgcolor="#edf1f3" align=center><b>Плательщик</b></td><td bgcolor="#edf1f3" align=center><b>Получатель</b></td><td bgcolor="#edf1f3" align=center width="350"><b>Примечание</b></td></tr>

<?php include "control/import_pays.php";?> 

</table>
<input type="hidden" name="create" value="true">
<input type="submit" id="importpays" value="Создать" style="width:20%;margin:10px;font-size:14px;">
 </form>
</div> 
</fieldset>

</body>
</html>