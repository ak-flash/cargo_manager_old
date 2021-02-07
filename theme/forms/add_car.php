<?php include "../../config.php";
if (@$_GET['tr']!=""){
$trans=$_GET['tr'];
$query = "SELECT `name` FROM `transporters` WHERE `Id`='".$trans."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_row($result);
}
?>

<script type="text/javascript" src="data/fileuploader.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="data/fileuploader.css" />

<script type="text/javascript">

$('#Save_car').button();
$('#btnClose_car').button();
$('.input-tab-1').focus(function(){
$(this).css({"border-color":"red","background-color":"#FFFEEF","color":"#000"});
});

$('.input-tab-1').focusout(function(){
if(!($(this).val()=="")){$(this).css({"border-color":"#187C22","background-color":"#FFFEEF","color":"#000"});}
});



$("#car_tabs").tabs({fx: {opacity:'toggle', duration:1}});  
$('#car_tabs').tabs({
select: function(event, ui) {

if($('#car_name').val()==""){$('#name_tab-1').html(' (!)');$('#car_name').css({"border-color":"red","background-color":"#FFEDED","color":"#000"});
} else {$('#name_tab-1').html('');$('#car_name').css({"border-color":"#187C22","background-color":"#92EF9B"});}

if($('#car_number').val()==""){$('#name_tab-1').html(' (!)');$('#car_number').css({"border-color":"red","background-color":"#FFEDED","color":"#000"});
} else {$('#name_tab-1').html('');$('#car_number').css({"border-color":"#187C22","background-color":"#92EF9B"});}


}
});
// - - кнопка добавление машины - сохранение - - >
$("#form_car").submit(function() {  
      
      
      var perfTimes = $("#form_car").serialize(); 
      $.post("control/car_add.php", perfTimes, function(data) {
      
      var arr = data.split(/[|]/);
      $('#result').html(arr[0]);
      if(arr[1]==1){$("#fa_car").dialog("close");$('#car_save').html('&nbsp;&nbsp;&nbsp;&nbsp;Автотранспорт успешно добавлен (обновлён)!');$('#car_save').fadeIn(5000);$('#car_save').fadeOut(10000);
     <?php if (!isset($_GET['m'])&&@$_GET['m']!="check") echo 'getCars();';  ?>   
      }

<?php if (@$_GET['mode']!="transp"){      
echo '$("#car_select").load("/control/car.php?id="+$("#tr").val(), function(data){	$("#car_select").html(data); });';
 } ?>    
      
      $("#result").dialog({ title: 'Готово' },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });}); 
  return false;});     

<?php if (@$_GET['mode']=="car"){
$car_id=(int)$_GET['car_id'];
$query_car = "SELECT * FROM `tr_autopark` WHERE `id`='".$car_id."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car_info = mysql_fetch_row($result_car);
     
echo '$("#car_name").val("'.addslashes($car_info[2]).'");$("#car_number").val("'.addslashes($car_info[3]).'");$("#car_m").val("'.$car_info[4].'");$("#car_v").val("'.$car_info[5].'");$("#car_kuzov").val("'.$car_info[12].'").change();$("#car_extra_name").val("'.$car_info[7].'");$("#car_extra_number").val("'.$car_info[8].'");$("#car_driver_name").val("'.$car_info[9].'");$("#car_driver_phone").val("'.$car_info[11].'");$("#car_driver_inn").val("'.$car_info[20].'");$("#car_owner").val("'.addslashes($car_info[14]).'");$("#car_owner_doc").val("'.addslashes($car_info[15]).'");$("#car_owner_type").val("'.$car_info[21].'").change();';

$car_doc = explode('|',$car_info[10]); 
echo '$("#car_driver_doc1").val("'.$car_doc[0].'");$("#car_driver_doc2").val("'.$car_doc[1].'");$("#car_driver_doc3").val("'.$car_doc[2].'");';
 } ?>    

var uploader = new qq.FileUploader({
    element: document.getElementById('file-uploader_sts'),
    action: '/control/upload.php?m=car',
    allowedExtensions: ['rtf','txt','doc','docx','xls','pdf','jpg','jpeg','png','tiff'],
    params: {
        mode: 'sts',
        carid: '<?php echo (int)$_GET['car_id'];?>'
       }
});
 
var uploader = new qq.FileUploader({
    element: document.getElementById('file-uploader_sts_dop'),
    action: '/control/upload.php?m=car',
    allowedExtensions: ['rtf','txt','doc','docx','xls','pdf','jpg','jpeg','png','tiff'],
    params: {
        mode: 'sts_dop',
        carid: '<?php echo (int)$_GET['car_id'];?>'
       }
});
</script>
<form method="post" id="form_car">
<div id="hidden_car_value"></div>
<div id="car_tabs">
<ul>
		<li><a href="#car_tabs-1">Авто<div id="name_tab-1" style="display:inline;"></div></a></li>
		<li><a href="#car_tabs-2">Водитель<div id="name_tab-2" style="display:inline;"></div></a></li>

	</ul>

<div id="car_tabs-1">
<table align="center">

<tr><td>Перевозчик:</td><td>

<?php 
if ($trans!=""){
echo '<input type="hidden" name="tr" id="tr" value="'.$trans.'">';
echo '<b><font size="4">'.$row[0].'</font></b>';
if (@$_GET['edit']=="1"){echo '<input type="hidden" name="edit" id="edit" value="1"><input type="hidden" name="car_idd" id="car_idd" value="'.(int)$_GET['car_id'].'"><input type="hidden" name="car_check_mail" id="car_check_mail" value="'.(int)$_GET['car_check_mail'].'">';}

}?>
</td></tr>
<tr><td><b>Марка машины:</b></td><td>
<input name="car_name" id="car_name" style="width: 150px;" placeholder="Укажите название" class="input" value="">
</td></tr>
<tr><td><b>Гос. номер грузовика:</b></td><td>
<input name="car_number" id="car_number" style="width: 150px;"  placeholder="формат А123ВС/34" class="input">
</td></tr>
<tr><td>Грузоподъёмность:</td><td>
<input name="car_m" id="car_m" style="width: 70px;"  placeholder="0" class="input" value=""> тонн
</td></tr>
<tr><td>Обьем:</td><td>
<input name="car_v" id="car_v" style="width: 70px;"  placeholder="0" class="input" value=""> м3
</td></tr>
<tr><td>Виды погрузки:</td><td height="40">
<input type="checkbox" name="car_load1" value="1" <?php if($car_info[6]==1||$car_info[6]==4||$car_info[6]==6||$car_info[6]==7) echo "checked";?>>верхняя
<input type="checkbox" name="car_load2" value="2" <?php if($car_info[6]==2||$car_info[6]==4||$car_info[6]==5||$car_info[6]==7) echo "checked";?>>задняя
<input type="checkbox" name="car_load3" value="3" <?php if($car_info[6]==3||$car_info[6]==5||$car_info[6]==6||$car_info[6]==7) echo "checked";?>>боковая
</td></tr>
<tr><td>Тип кузова:</td><td>
<select name="car_kuzov" id="car_kuzov" class="select" onchange="$('#car_kuzov_other').val(this.value);">
<option value="">Выберите...</option>
<option value="тент">тент</option>
<option value="борт">борт</option>
<option value="реф">реф</option>
<option value="термос">термос</option>
<option value="штора">штора</option>
<option value="меб.фургон">меб.фургон</option>
<option value="контейнер">контейнер</option>
<option value="площадка">площадка</option>
<option value="укажите">другое</option>
</select><input type="input" class="input" name="car_kuzov_other" id="car_kuzov_other" value="" style="width: 100px;" >
</td></tr>
</table>
<fieldset style="margin-left:-5px;"><legend>Собственник:</legend>
<table>
<tr>
  <td align="right" width="150">Тип владения:</td><td>
    <select name="car_owner_type" id="car_owner_type" class="select">
      <option value="1">Собственность</option>
      <option value="2">Аренда</option>
      <option value="3">Лизинг</option>
    </select>
</td>
</tr>

  <tr><td align="right" width="150">
Ф.И.О.:</td><td><input name="car_owner" id="car_owner" style="width: 200px;"  placeholder="Укажите Ф.И.О. полностью" class="input"></td></tr><tr>
<td align="right">Свидетельство ТС:</td><td><input name="car_owner_doc" id="car_owner_doc" style="width: 200px;"  placeholder="Укажите номер и серию" class="input"></td>
</tr>


<tr><td colspan="2">
<?php if ($car_id!=0){
echo '<table cellspacing="5" width="290"><tr><td valign="middle"><b>СТС:</b></td><td valign="middle" width="170">
<div id="file_sts" style="width:40px;">';
if ($car_id!="") {
if(is_dir("Uploads/car/".$car_id."/sts")){
$dir = opendir("Uploads/car/".$car_id."/sts");
while(($file = readdir($dir))) 
  { 
   if($file!="."&&$file!="..") {echo '<a href="Uploads/car/'.$car_id.'/sts/'.iconv("windows-1251", "UTF-8", $file).'" TARGET="_blank">'.iconv("windows-1251", "UTF-8", $file)."</a><br>";$noshow="display:none;";} else $noshow="";
  } 
  closedir($dir);    
}
}
echo '</div></td><td valign="top" width="100"><div id="file-uploader_sts" style="width:40px;height:15px;'.$noshow.'"></div></td></tr></table>';}?>

</td></tr>
</table></fieldset>
<fieldset style="margin-left:-5px;"><legend>Полуприцеп:</legend>
<table><tr><td align="right" width="100">
Марка:</td><td><input name="car_extra_name" id="car_extra_name" style="width: 150px;"  placeholder="Укажите название" class="input"></td></tr><tr>
<td align="right">Гос. номер:</td><td><input name="car_extra_number" id="car_extra_number" style="width: 150px;"  placeholder="Укажите номер" class="input"></td>
</tr>

<tr><td colspan="2">
<?php if ($car_id!=0){
echo '<table cellspacing="5" width="290"><tr><td valign="middle"><b>СТС:</b></td><td valign="middle" width="170">
<div id="file_sts_dop" style="width:40px;">';
if ($car_id!="") {
if(is_dir("Uploads/car/".$car_id."/sts_dop")){
$dir = opendir("Uploads/car/".$car_id."/sts_dop");
while(($file = readdir($dir))) 
  { 
   if($file!="."&&$file!="..") {echo '<a href="Uploads/car/'.$car_id.'/sts_dop/'.iconv("windows-1251", "UTF-8", $file).'" TARGET="_blank">'.iconv("windows-1251", "UTF-8", $file)."</a><br>";$noshow_dop="display:none;";} else $noshow_dop="";
  } 
  closedir($dir);    
}
}
echo '</div></td><td valign="top" width="100"><div id="file-uploader_sts_dop" style="width:40px;height:15px;'.$noshow_dop.'"></div></td></tr></table>';}?>

</td></tr>
</table></fieldset>

</div>
<div id="car_tabs-2">
<!--- <img src="data/img/driver.jpg" style="margin-left:-15px;width:410px;"> --->

<fieldset style="margin-left:-10px;"><legend><b>Водитель:</b></legend>
<table><tr><td align="right" width="100">
<b>ФИО:</b></td><td>
<input name="car_driver_name" id="car_driver_name" style="width: 250px;"  placeholder="Укажите Ф.И.О." class="input">
</td></tr>
<tr><td align="right"><b>Паспорт:</b></td><td>
<input name="car_driver_doc1" id="car_driver_doc1" style="width: 150px;"  placeholder="Укажите номер" class="input">
</td></tr>
<tr><td align="right">Кем выдан:</td><td>
<input name="car_driver_doc2" id="car_driver_doc2" style="width: 250px;"  placeholder="Паспорт" class="input">
</td></tr>
<tr><td align="right">Когда выдан:</td><td>
<input name="car_driver_doc3" id="car_driver_doc3" style="width: 100px;"  placeholder="Паспорт" class="input">
</td></tr>
<tr><td align="right"><b>ИНН:</b></td><td>
<input name="car_driver_inn" id="car_driver_inn" style="width: 150px;"  placeholder="Укажите Инн" class="input">
</td></tr>
<tr><td align="right"><b>Телефон:</b></td><td>
<input name="car_driver_phone" id="car_driver_phone" style="width: 150px;"  placeholder="Укажите телефон" class="input">
</td></tr></table><br>
</fieldset>




<fieldset style="margin-left:-10px;"><legend>Комментарий:</legend>
<textarea rows="10" name="car_notify" style="width:98%;"><?php echo $car_info[19];?></textarea>
</fieldset>

</div>
<div align="center" style="margin-top:-14px;margin-bottom:5px;"><input type="submit" id="Save_car" value="Сохранить" style="width: 200px;height:45px;">
<input type="button" id="btnClose_car" onclick="$('#fa_car').dialog('close');" value="Закрыть" style="width: 120px;"></div>	
</div>
</form>