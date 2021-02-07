<?php 

include "../../config.php";

if (@$_GET['doc_id']!=""){
$doc_id=$_GET['doc_id'];

$query = "SELECT * FROM `docs` WHERE `Id`='".mysql_escape_string($doc_id)."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);

}
?>
<script type="text/javascript" src="data/fileuploader.js"></script>
<link rel="stylesheet" type="text/css" media="screen" href="data/fileuploader.css" />
<script type="text/javascript">
$(function(){

$('#btnClose_doc').button();$('#btcl1').button();$('#btcl2').button();$('#bttr1').button();$('#bttr2').button();$('#bttr3').button();$('#bttr4').button();$('#btcl3').button();
$('#btnSave_doc').button();
$("#docs_tabs").tabs({fx: {opacity:'toggle', duration:100}}); 
$('#add_date_tr_all').mask('99/99/9999');
$('#add_date_cl_all').mask('99/99/9999');
$('#add_date_cl_bill').mask('99/99/9999');$('#add_date_cl_sent').mask('99/99/9999');
$('#add_date_tr_bill').mask('99/99/9999');
$('#add_date_tr_akt').mask('99/99/9999');
$('#add_date_tr_ttn').mask('99/99/9999');
});

<?php  
if ($doc_id!="") {

if($row['date_add_bill']=='0000-00-00'||$row['date_add_bill']=='1970-01-01') $add_date_cl_bill=""; else $add_date_cl_bill=date("d/m/Y",strtotime($row['date_add_bill']));
if($row['date_cl_receve']=='0000-00-00'||$row['date_cl_receve']=='1970-01-01') $date_cl_receve=""; else $date_cl_receve=date("d/m/Y",strtotime($row['date_cl_receve']));

if($row['add_date_cl_sent']=='0000-00-00'||$row['add_date_cl_sent']=='1970-01-01') $add_date_cl_sent=""; else $add_date_cl_sent=date("d/m/Y",strtotime($row['add_date_cl_sent']));

if($row['date_tr_bill']=='0000-00-00'||$row['date_tr_bill']=='1970-01-01') $add_date_tr_bill=""; else $add_date_tr_bill=date("d/m/Y",strtotime($row['date_tr_bill']));	
if($row['date_tr_akt']=='0000-00-00'||$row['date_tr_akt']=='1970-01-01') $add_date_tr_akt=""; else $add_date_tr_akt=date("d/m/Y",strtotime($row['date_tr_akt']));	
if($row['date_tr_ttn']=='0000-00-00'||$row['date_tr_ttn']=='1970-01-01') $add_date_tr_ttn=""; else $add_date_tr_ttn=date("d/m/Y",strtotime($row['date_tr_ttn']));	

if($row['date_tr_receve']=='0000-00-00'||$row['date_tr_receve']=='1970-01-01') $add_date_tr_all=""; else $add_date_tr_all=date("d/m/Y",strtotime($row['date_tr_receve']));



echo "$('#add_date_cl_bill').val('".$add_date_cl_bill."');$('#add_bill_tr').val('".$row['tr_bill']."');$('#add_date_tr_bill').val('".$add_date_tr_bill."');$('#add_date_tr_akt').val('".$add_date_tr_akt."');$('#add_date_tr_ttn').val('".$add_date_tr_ttn."');$('#add_akt_tr').val('".$row['tr_akt']."');$('#add_ttn_tr').val('".$row['tr_ttn']."');$('#add_date_cl_all').val('".$date_cl_receve."');$('#cl_doc_list').val('".$row['cl_event']."').change();$('#tr_doc_list').val('".$row['tr_event']."').change();$('#add_date_tr_all').val('".$add_date_tr_all."');$('#add_date_cl_sent').val('".$add_date_cl_sent."');$('#send_type_cl').val('".$row['s_type']."').change();";

if($row['cl_bill']=="0"||$row['cl_bill']=="") echo "$('#add_bill_cl').val('');"; else echo "$('#add_bill_cl').val('".$row['cl_bill']."');";
}

?>


// - - кнопка добавление направления - сохранение - - >
$("#form_add_docs").submit(function() {  
      var perfTimes = $("#form_add_docs").serialize(); 
      $.post("control/add_doc.php", perfTimes, function(data) {
      
      var arr = data.split(/[|]/);
      if(arr[1]==1){$('#form_add_docs').unbind();$('#fa_add_docs').dialog('close');}
      $('#result').html(arr[0]);
 
      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ resizable: false },{ width: 430 },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });jQuery("#table").trigger("reloadGrid");}); 
  return false;});
  

var uploader = new qq.FileUploader({
    element: document.getElementById('file-uploader_cl'),
    action: '/control/upload.php',
    allowedExtensions: ['rtf','txt','doc','docx','xls','pdf','jpg','jpeg','png','tiff'],
    params: {
        mode: 'cl',
        order: '<?php echo $row['order'];?>'
       }
});
 
var uploader = new qq.FileUploader({
    element: document.getElementById('file-uploader_tr'),
    action: '/control/upload.php',
    allowedExtensions: ['rtf','txt','doc','docx','xls','pdf','jpg','jpeg','png','tiff'],
    params: {
        mode: 'tr',
        order: '<?php echo $row['order'];?>'
       }
});

</script>

<div id="docs_tabs">
<ul style="font-size: 18px;height: 28px;">
		
		
		<li><a href="#docs_tabs-1">Док. Клиента<div id="name_tab-1" style="display:inline;"></div></a></li>
		<li><a href="#docs_tabs-2">Док. Перевозчика<div id="name_tab-2" style="display:inline;"></div></a></li>
<li><a href="#docs_tabs-3">Загрузить док.<div id="name_tab-3" style="display:inline;"></div></a></li>
	</ul>

<form method="post" id="form_add_docs">
<?php  if ($doc_id!="") {
echo '<input type="hidden" name="doc_id" value="'.$row['id'].'"><input type="hidden" name="order_id" value="'.$row['order'].'"><input type="hidden" name="edit" value="1">';
} ?>

<div id="docs_tabs-1">
<div style="height: 32em;width: 101%; overflow: auto">

Форма документов, отправленных Клиенту: <select name="cl_doc_list" style="width:140px;" id="cl_doc_list" class="select">
  <option value="0">Выберите...</option>
  <option value="3">Факсимильные</option>
<option value="4">Оригинальные</option>
  </select><br><br>
<fieldset style="width:93%;"><legend>Счет, выставленный Клиенту:</legend>
<table cellpadding="5"><tr><td align="right"><b>Номер счета:</b>&nbsp;</td><td width="135">№<input type="text" id="add_bill_cl" name="add_bill_cl" style="width:95px;" class="input" value="" placeholder="0"/></td>
<td align="right">Дата выставл.:&nbsp;</td><td><input type="text" id="add_date_cl_bill" name="add_date_cl_bill" style="width:80px;" class="input" value="" />&nbsp;<input type="button" id="btcl1" style="height:28px;" value="<" onclick="$('#add_date_cl_bill').val('<?php echo date("d/m/Y");?>');"></td></tr></table>
</fieldset>

<fieldset  style="width:93%;"><legend>Документы, отправленные Клиенту:</legend>
<table cellpadding="5">
<tr><td align="right">Способ отправки:&nbsp;&nbsp;</td><td colspan="3">

<select name="send_type_cl" style="width:140px;" id="send_type_cl" class="select">
  <option value="0">Выберите...</option>
  <option value="1">Нарочным</option>
<option value="2">Курьер служба</option>
<option value="3">Почта России</option>
 </select>

</td></tr>
<tr><td align="right">Дата отправления:&nbsp;&nbsp;</td><td width="145"><input type="text" id="add_date_cl_sent" name="add_date_cl_sent" style="width:80px;" class="input" value="" />&nbsp;<input type="button" id="btcl3" style="height:28px;" value="<" onclick="$('#add_date_cl_sent').val('<?php echo date("d/m/Y");?>');"></td><td colspan="2" height="33">* Формат даты: ДД/ММ/ГГГГ</td></tr>
<tr><td align="right">Дата получения:&nbsp;&nbsp;</td><td width="145"><input type="text" id="add_date_cl_all" name="add_date_cl_all" style="width:80px;" class="input" value="" />&nbsp;<input type="button" id="btcl2" style="height:28px;" value="<" onclick="$('#add_date_cl_all').val('<?php echo date("d/m/Y");?>');"></td><td colspan="2" height="33"> <b>* ВСЕХ документов Клиентом</b></td></tr>
</table>
</fieldset>




</div></div>
<div id="docs_tabs-2">
<div style="height: 32em;width: 101%; overflow: auto">

Форма документов, поступивших от Перевозчика: <select name="tr_doc_list" style="width:140px;" id="tr_doc_list" class="select">
  <option value="0">Выберите...</option>
  <option value="3">Факсимильные</option>
<option value="4">Оригинальные</option>
  </select>

<fieldset  style="width:93%;"><legend><b>Счет</b> от Перевозчика:</legend>
<table cellpadding="5"><tr><td align="right"><b>Номер счета:</b>&nbsp;&nbsp;</td><td width="135">№<input type="text" id="add_bill_tr" name="add_bill_tr" style="width:95px;" class="input" value="" placeholder="0"/></td><td align="right">Дата выставл.:&nbsp;&nbsp;</td><td><input type="text" id="add_date_tr_bill" name="add_date_tr_bill" style="width:80px;" class="input" value="" />&nbsp;<input type="button" id="bttr1" style="height:28px;" value="<" onclick="$('#add_date_tr_bill').val('<?php echo date("d/m/Y");?>');"></td></tr></table>
</fieldset>

<fieldset style="width:93%;"><legend><b>Акт</b> от Перевозчика:</legend>
<table cellpadding="5">
<tr><td align="right"><b>Номер:</b>&nbsp;&nbsp;</td><td width="155">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;№<input type="text" id="add_akt_tr" name="add_akt_tr" style="width:95px;" class="input" value="" placeholder="0"/></td><td align="right">&nbsp;&nbsp;&nbsp;Дата формир.:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;<input type="text" id="add_date_tr_akt" name="add_date_tr_akt" style="width:80px;" class="input" alue="" />&nbsp;<input type="button" id="bttr2" style="height:28px;" value="<" onclick="$('#add_date_tr_akt').val('<?php echo date("d/m/Y");?>');"></td></tr>
</table>
</fieldset>

<fieldset style="width:93%;"><legend><b>ТТН</b> от перевозчика:</legend>
<table cellpadding="5">
<tr><td align="right"><b>Номер:</b>&nbsp;&nbsp;</td><td width="155">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;№<input type="text" id="add_ttn_tr" name="add_ttn_tr" style="width:95px;" class="input" value="" placeholder="0"/></td><td align="right">&nbsp;&nbsp;&nbsp;Дата формир.:&nbsp;&nbsp;</td><td>&nbsp;&nbsp;&nbsp;<input type="text" id="add_date_tr_ttn" name="add_date_tr_ttn" style="width:80px;" class="input" value="" />&nbsp;<input type="button" id="bttr3" style="height:28px;" value="<" onclick="$('#add_date_tr_ttn').val('<?php echo date("d/m/Y");?>');"></td></tr>
</table>
</fieldset>

<fieldset style="width:93%;"><legend>Документы от перевозчика:</legend>
<table cellpadding="5">

<tr><td align="right" width="40">Дата получения:<br>(наступления события "Загрузка"/"Выгрузка")&nbsp;&nbsp;</td><td><input type="text" id="add_date_tr_all" name="add_date_tr_all" style="width:80px;" class="input" value="" />&nbsp;<input type="button" value="<" id="bttr4" style="height:28px;" onclick="$('#add_date_tr_all').val('<?php echo date("d/m/Y");?>');"></td><td align="center" colspan="2"> <font size="2"><b>* ВСЕХ документов от Перевозч. </b><br>* Формат даты: ДД/ММ/ГГГГ</font></td></tr></table>
</fieldset>









</div></div>
<div id="docs_tabs-3">
<div style="height: 32em;width: 101%; overflow: auto">

<fieldset style="width:93%;"><legend>Загрузить документы, отправленные Клиенту:</legend>
<table cellspacing="5"><tr><td valign="top"><div id="file-uploader_cl" style="width:240px;"></div></td><td valign="top">
<div id="file_cl" style="height: 8em; overflow: auto;width:240px;"><?php if ($doc_id!="") {
if(is_dir("../../Uploads/".$row['order']."/cl/")){
$dir = opendir("../../Uploads/".$row['order']."/cl");
while(($file = readdir($dir))) 
  { 
   if($file!="."&&$file!="..") echo '<a href="../../Uploads/'.$row['order'].'/cl/'.$file.'" target="_blank" style="color:#000;">'.$file."</a><br><br>";
  } 
  closedir($dir);    
}
}
?></div></td></tr></table>
</fieldset>
<fieldset style="width:93%;"><legend>Загрузить документы, полученный от Перевозчика:</legend>
<table cellspacing="5"><tr><td valign="top"><div id="file-uploader_tr" style="width:240px;"></div></td><td valign="top" align="left">
<div id="file_tr" style="height: 8em; overflow: auto;width:240px;"><?php if ($doc_id!="") {
if(is_dir("../../Uploads/".$row['order']."/tr/")){
$dir = opendir("../../Uploads/".$row['order']."/tr");
while(($file = readdir($dir))) 
  { 
   if($file!="."&&$file!="..") echo '<a href="../../Uploads/'.$row['order'].'/tr/'.$file.'" target="_blank" style="color:#000;">'.$file."</a><br>";
  } 
  closedir($dir);    
}
}
?></div></td></tr></table>
</fieldset>
</div>
</div>


<fieldset style="width:89%;"><legend>Примечание:</legend><textarea cols="60" rows="2" name="doc_notify"><?php echo $row['notify'];?></textarea></fieldset>
<input type="submit" id="btnSave_doc" value="Сохранить" style="width: 250px;float:left;margin-top:10px;margin-left:100px;height:40px;">
<input type="button" id="btnClose_doc" class="button2" value="Закрыть" onclick="$('#fa_add_docs').dialog('close');" style="width: 150px;height:30px;">
</form>
<br>
</div>