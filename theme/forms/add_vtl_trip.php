<?php 
include "../../config.php";


if (@$_GET['trip_id']!=""||intval($_GET['trip_id'])){
$trip_id=$_GET['trip_id'];

$query = "SELECT `id`,`data`,`order`,`notify`,`p_list` FROM `vtl_trip` WHERE `id`='".mysql_escape_string($trip_id)."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);


} else {session_start();}
?>
<script type="text/javascript" src="data/jquery.maskedinput-1.3.min.js"></script>
<script type="text/javascript">
$('#save_trip').button();
$('#btnClose_trip').button();
$("#date_trip").datepicker();

$.mask.definitions['~']='[+-]';
$('#date').mask('99/99/9999'); 

function number_format( number, decimals, dec_point, thousands_sep ) {	// Format a number with grouped thousands
	// 
	// +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
	// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +	 bugfix by: Michael White (http://crestidg.com)

	var i, j, kw, kd, km;

	// input sanitation & defaults
	if( isNaN(decimals = Math.abs(decimals)) ){
		decimals = 2;
	}
	if( dec_point == undefined ){
		dec_point = ",";
	}
	if( thousands_sep == undefined ){
		thousands_sep = ".";
	}

	i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

	if( (j = i.length) > 3 ){
		j = j % 3;
	} else{
		j = 0;
	}

	km = (j ? i.substr(0, j) + thousands_sep : "");
	kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
	//kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
	kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


	return km + kw + kd;
}





$("#form_trip").submit(function() {  
  validate=true;    

   
   if(validate){ 
   
     
      var perfTimes = $("#form_trip").serialize(); 
      $.post("control/auto/add_vtl_trip.php", perfTimes, function(data) {
      jQuery("#table_tr_trip").trigger("reloadGrid");
      var arr = data.split(/[|]/);
      $('#result').html(arr[0]);
      if(arr[1]==1){$("#fa_vtl_trip").dialog("close");}
      
      $("#result").dialog({ title: 'Готово' },{ width: 410 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });}); 
 }
  
  return false;
    
  }); 


</script>
<form method="post" id="form_trip">



<?php  if ($trip_id!="") {
echo '<input type="hidden" name="trip_id" value="'.$row['id'].'"><input type="hidden" name="edit" value="1">';

} ?>

<fieldset style="width:90%;"><legend>Рейс:</legend>
<table><tr><td align="center" width="40">Дата:</td><td width="100"><input type="text" id="date_trip" name="date_trip" style="width:75px;" value="<?php if($trip_id==''){echo date('d/m/Y');} else {echo date('d/m/Y',strtotime($row['data']));}?>" class="input"/></td><td><b>Заявки:</b></td><td><input type="text" id="order_trip" name="order_trip" style="width:185px;" placeholder="номера ч/з запятую" value="<?php if($trip_id!=''){$str_order = explode('&',$row['order']);
$str_order_list =(int)sizeof($str_order)-1;
$f=1;
while ($f<$str_order_list) {
echo $str_order[($str_order_list-$f)].', ';
$f++;
}echo $str_order[($str_order_list-$f)];}?>" class="input"/></td></tr>
</table>
</fieldset>

<fieldset style="width:90%;"><legend>Путевые листы:</legend>

<?php if($trip_id!=''){$str_order = explode('&',$row['order']);
$str_p_list= explode('&',$row['p_list']);
$str_order_list =(int)sizeof($str_order)-1;
$f=1;
while ($f<=$str_order_list) {
if ($str_p_list[($str_order_list-$f)]=='1') $check_list='checked'; else $check_list='';

echo '<input type="checkbox" name="p_list_'.$f.'" id="p_list_'.$f.'" value="'.$str_p_list[($str_order_list-$f)].'"  onclick="if(this.checked){$(\'#p_list_'.$f.'\').val(1);} else {$(\'#p_list_'.$f.'\').val(0);};" '.$check_list.'><b>'.$str_order[($str_order_list-$f)].'</b>&nbsp;&nbsp;&nbsp;';
$f++;
}}?>


</fieldset>

<fieldset style="width:90%;"><legend>Комментарий:</legend>
<textarea cols="32" rows="4" name="trip_notify"><?php echo $row['notify'];?></textarea>
</fieldset>

<div align="center"><input type="submit" id="save_trip" value="<?php if($trip_id!=''){echo 'Сохранить';} else {echo 'Создать';}?>" style="width: 220px;">
<input type="button" id="btnClose_trip" onclick="$('#fa_vtl_trip').dialog('close');" value="Отмена" style="width: 120px;">
</div>
</form>