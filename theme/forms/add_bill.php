<?php 
session_start();include "../../config.php";
if (@$_GET['c_bill']!=""||intval($_GET['c_bill'])){
$c_bill=$_GET['c_bill'];
} 


?>
<script type="text/javascript" src="data/jquery.maskedinput-1.3.min.js"></script>

<script type="text/javascript">
$.mask.definitions['~']='[+-]';



$('#company_bik').mask('999999999');

$('#company_rs').mask('99999999999999999999');
$('#company_ks').mask('99999999999999999999');


<?php  if ($c_bill!="") {

$query = "SELECT * FROM `bill` WHERE `id`='".mysql_escape_string($c_bill)."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);



echo '$("#company_rs").val("'.$row['c_bill'].'");$("#company_bank").val("'.addslashes($row['c_bank']).'");$("#company_bik").val("'.$row['c_bik'].'");$("#company_ks").val("'.$row['c_ks'].'");$("#company_cash").val("'.((int)$row['c_cash']/100).'");';

} 

if(@$_GET['company']!="") $company=$_GET['company']; else $company=$row['company'];
?>   





// - - форма добавления - сохранение  - - >
$("#form_company_bill").submit(function() {
 var perfTimes = $("#form_company_bill").serialize();


$.post("control/company_add_bill.php", perfTimes, function(data) {
     
     var arr = data.split(/[|]/);
      if(arr[1]==1){$("#fa_add_bill").dialog("close");$('#form_company_bill').unbind();$('#c_bill_show').load('/control/c_bill.php?mode=all');}
      

      $('#result').html(arr[0]);
      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ width: 400 },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");jQuery("#table").trigger("reloadGrid");
      
       } } });
      
   


      }); 

     
      
  return false;
   });   
  
  

 $('#btnClose_add_bill').button(); 
$('#save_bill').button(); 
   
</script>

<form method="post" id="form_company_bill">

<?php  




echo '<input type="hidden" name="company_id" value="2">';

if ($c_bill!="") {
echo '<input type="hidden" name="c_bill" value="'.$c_bill.'"><input type="hidden" name="edit" value="1">';
}

$query_c = "SELECT `name` FROM `company` WHERE `id`='".mysql_escape_string($company)."'";
$result_c = mysql_query($query_c) or die(mysql_error());
$row_c = mysql_fetch_row($result_c);
echo '<b><font size="5">'.$row_c[0].'</font></b>';
 ?>
<br>
<fieldset style="width:300px;"><legend>Реквизиты:</legend>
<table border="0">
<tr><td align="right">р/сч:</td><td><input type="number" name="company_rs" id="company_rs" style="width: 200px;"  placeholder="" class="input"></td></tr>
<tr><td align="right">Банк:</td><td><input name="company_bank" id="company_bank" style="width: 360px;"  placeholder="" class="input"></td></tr>
<tr><td align="right">БИК:</td><td><input type="number" name="company_bik" id="company_bik" style="width: 100px;"  placeholder="" class="input"></td></tr>
<tr><td align="right">к/сч:</td><td><input type="number" name="company_ks" id="company_ks" style="width: 200px;"  placeholder="" class="input"></td></tr>
<tr><td align="right"><br>Баланс:</td><td><br><input name="company_cash" id="company_cash" style="width: 100px;"  placeholder="0" class="input"> руб.</td></tr>
</table></fieldset>





<br><div align="center"><input type="submit" id="save_bill" value="<?php if ($c_bill!="") echo 'Сохранить'; else echo 'Добавить'; ?>" style="width: 250px;">
<input type="button" id="btnClose_add_bill" onclick="$('#fa_add_bill').dialog('close');" value="Закрыть" style="width: 150px;">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>

</form>