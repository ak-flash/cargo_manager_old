<?php 
session_start();
include "../../config.php";

if (!empty($_GET['order'])){

  $order = (int) $_GET['order'];

$query = "SELECT * FROM `orders` WHERE `id`='".mysql_escape_string($order)."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);
$str_auto = explode('&',$row['tr_auto']);
}
?>

<script type="text/javascript">
$("#cl_currency").val(3).change();

$.mask.definitions['~']='[+-]';
$('#in_data1').mask('99/99/9999');
$('#in_data2').mask('99/99/9999');
$('#out_data1').mask('99/99/9999');
$('#out_data2').mask('99/99/9999');

$('#in_time11').mask('99:99');
$('#in_time12').mask('99:99');
$('#in_time21').mask('99:99');
$('#in_time22').mask('99:99');
$('#out_time1').mask('99:99');
$('#out_time2').mask('99:99');
$('#data').mask('99/99/9999');


$('#save').button();
$('#btnClose').button();
$('#btnRenewOrd').button();

    var base64EncodeChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
    function base64encode(str){
        var out, i, len;
        var c1, c2, c3;
        len = str.length;
        i = 0;
        out = '';
        while(i < len){
        c1 = str.charCodeAt(i++) & 0xff;
        if(i == len){
            out += base64EncodeChars.charAt(c1 >> 2);
            out += base64EncodeChars.charAt((c1 & 0x3) << 4);
            out += '==';
            break;
        }
        c2 = str.charCodeAt(i++);
        if(i == len){
            out += base64EncodeChars.charAt(c1 >> 2);
            out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
            out += base64EncodeChars.charAt((c2 & 0xF) << 2);
            out += '=';
            break;
        }
        c3 = str.charCodeAt(i++);
        out += base64EncodeChars.charAt(c1 >> 2);
        out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4));
        out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >>6));
        out += base64EncodeChars.charAt(c3 & 0x3F);
        }
        return out;
    }





$("#order_tabs").tabs({fx: {opacity:'toggle', duration:100}});       
document.getElementById('car_add').style.visibility = "hidden";
document.getElementById('security_code').style.visibility = "hidden";
$("#order_code").val("");
$("#tr_autopark").attr("disabled","disabled");
$("#in_data1").datepicker({'dateFormat':'dd/mm/yy'});
$("#in_data2").datepicker({'dateFormat':'dd/mm/yy'});
$("#out_data1").datepicker({'dateFormat':'dd/mm/yy'});
$("#out_data2").datepicker({'dateFormat':'dd/mm/yy'});
$("#data").datepicker({'dateFormat':'dd/mm/yy'});


function Komiss(){

var cl_cash=parseInt(document.getElementById('cl_cash').value, 10);
var tr_cash=parseInt(document.getElementById('tr_cash').value, 10);
var cl_nds=parseInt(document.getElementById('cl_nds').value, 10);
var tr_nds=parseInt(document.getElementById('tr_nds').value, 10);

if(document.getElementById('cl_cash_min').value==""){cl_cash_min=0;} else {var cl_cash_min=parseInt(document.getElementById('cl_cash_min').value, 10);}
if(document.getElementById('cl_cash_plus').value==""){cl_cash_plus=0;} else {var cl_cash_plus=parseInt(document.getElementById('cl_cash_plus').value, 10);}
if(document.getElementById('tr_cash_min').value==""){tr_cash_min=0;} else {var tr_cash_min=parseInt(document.getElementById('tr_cash_min').value, 10);}
if(document.getElementById('tr_cash_plus').value==""){tr_cash_plus=0;} else {var tr_cash_plus=parseInt(document.getElementById('tr_cash_plus').value, 10);}


<?php 

$query_motive = "SELECT * FROM `settings`";
$result_motive = mysql_query($query_motive) or die(mysql_error());
$motive = mysql_fetch_array($result_motive);
?>

var cl_cash_all=cl_cash-cl_cash_min+cl_cash_plus;
var tr_cash_all=tr_cash-tr_cash_min+tr_cash_plus;
cash=0;
if(cl_nds==0 && tr_nds==0){var data=cl_cash_all-tr_cash_all;}
if(cl_nds==1 && tr_nds==1){var data=cl_cash_all-tr_cash_all;}
if(cl_nds==1 && tr_nds==0){var data=((cl_cash_all-cl_cash_all/<?php echo $motive['motive_2'];?>)-tr_cash_all);}
if(cl_nds==1 && tr_nds==2){var data=(cl_cash_all-cl_cash_all*<?php echo $motive['motive_3'];?>)-tr_cash_all;}
if(cl_nds==0 && tr_nds==1){var data=(cl_cash_all-(tr_cash_all-tr_cash_all/<?php echo $motive['motive_4'];?>));}
if(cl_nds==0 && tr_nds==2){var data=(cl_cash_all-cl_cash_all*<?php echo $motive['motive_6'];?>)-tr_cash_all;}
if(cl_nds==2 && tr_nds==1){var data=cl_cash_all-tr_cash_all+tr_cash_all*<?php echo $motive['motive_7'];?>;}
if(cl_nds==2 && tr_nds==0){var data=cl_cash_all-tr_cash_all+tr_cash_all*<?php echo $motive['motive_8'];?>;}
if(cl_nds==2 && tr_nds==2){var data=cl_cash_all-tr_cash_all;}




var cash=data*100/cl_cash;
if(cash.toPrecision(2)>=20){$quality='отличная';$cash_ok=2;}
if(cash.toPrecision(2)<20&&cash.toPrecision(2)>=10){$quality='хорошая';$cash_ok=2;document.getElementById('security_code').style.visibility = "hidden";}
if(cash.toPrecision(2)<10&&cash.toPrecision(2)>=6){$quality='удовлетворительная';$cash_ok=2;document.getElementById('security_code').style.visibility = "hidden";}
if(cash.toPrecision(2)<6&&cash.toPrecision(2)>=0){$quality='заявка сомнительной рентабельности';$cash_ok=2;document.getElementById('security_code').style.visibility = "hidden";}
if(cash.toPrecision(2)<0){$quality='Внимание! Доход отсутствует!';$cash_ok=2;document.getElementById('security_code').style.visibility = "hidden";}

$('#quality').html(cash.toPrecision(3)+'% - '+$quality);
$('#komissia').html(data.toFixed(0));
$('#cash_ok').html('<input type="hidden" name="cash_ok" id="cash_ok" value="'+$cash_ok+'">');

}




$("#btnAdd_adress_in").click(function(){
$("#fa_adr").load("theme/forms/add_adr.php?adr_mode=1&fmod=0", function() {
  $("#fa_adr").dialog({ title: 'Новый адрес' },{
			width: 510,height: 650,
			modal: true,resizable: false
});

});    
});

$("#btnAdd_adress_out").click(function(){
$("#fa_adr").load("theme/forms/add_adr.php?adr_mode=2&fmod=0", function() {
  $("#fa_adr").dialog({ title: 'Новый адрес' },{
			width: 510,height: 650,
			modal: true,resizable: false
});
});
});




function getCar(){

$("#car_details").load('control/car_details.php?id='+$('#tr_autopark').val(), function(data){

var car_arr = data.split(/[|]/); 
	  $('#hidden_car_value').html('<input type="hidden" name="car" id="car" value="'+car_arr[0]+'">');
	$("#car_details").html('<fieldset><legend><b>Транспорт:</b></legend><table><tr><td><b style="margin-left:10px;">Тягач:</b></td><td align="center">'+car_arr[1]+'  '+car_arr[2]+'</td><td><b style="margin-left:20px;">Полуприцеп:</b></td><td>'+car_arr[3]+' '+car_arr[4]+'</td><td><b style="margin-left:10px;">Статус:</b></td></td><td align="center">'+car_arr[11]+'</td></tr></table><fieldset><legend style="margin-bottom:-10px;"><b>Водитель:</b></legend><table><b>Ф.И.О.:</b>&nbsp;&nbsp;'+car_arr[5]+'&nbsp;&nbsp;&nbsp;&nbsp;<b>Контактный телефон:</b>&nbsp;&nbsp;'+car_arr[9]+'<br><b>Паспорт:</b>&nbsp;&nbsp;'+car_arr[6]+'&nbsp;&nbsp;&nbsp;&nbsp;<b>Выдан:</b>&nbsp;&nbsp;'+car_arr[7]+' ('+car_arr[8]+' г.)&nbsp;<b>Комментарий:</b>&nbsp;'+car_arr[10]+'</td></tr></table></fieldset></fieldset>');
	 
	});




}

function SetVTL(){
document.getElementById('tr_select').style.display='none';
document.getElementById('tr_select_vtl').style.display='inline';
document.getElementById('tr_edit_vtl').style.display='none';
document.getElementById('tr_receive_select').style.display='none';
document.getElementById('tr_nds_temp').style.display='none';
document.getElementById('vtl_nds').style.display='inline';
document.getElementById('vtl_cont').style.display='inline';
document.getElementById('tr_cont').style.display='none';
document.getElementById('show_tr_dpay1').style.display='none';
document.getElementById('show_tr_dpay2').style.display='none';
$("#vtl_nds").html(':&nbsp;&nbsp;<b>С НДС</b>');

$("#transporter").val(2);
$("#tr_receive").val(2);

$("#tr_nds").val(1).change();



$("#tr_pref").val("1");
$("#tr_tfpay").val("0");
$("#tr_event").val(2).change();
$("#temp_tr_nds").val(1);
$("#tr_cont").val(2);

$("#tr_event").attr("disabled","disabled");
$("#tr_cash_min").attr("disabled","disabled");
$("#tr_cash_plus").attr("disabled","disabled");

$("#tr_autopark").attr("disabled","");
$("#car_select").load("control/auto/vtl_car.php?mode=auto_load"<?php if($row['transp']==2){echo ',function(data){$("#tr_autopark").val('.$str_auto[0].').change();}';}?>);
$("#dop_select").load("control/auto/vtl_car.php?mode=dop_load"<?php if($row['transp']==2){echo ',function(data){$("#dop_autopark").val('.$str_auto[1].').change();}';}?>);
$("#drv_select").load("control/auto/vtl_car.php?mode=drv_load"<?php if($row['transp']==2){echo ',function(data){$("#drv_autopark").val('.$str_auto[2].').change();}';}?>);

$("#car_details").html('<fieldset><legend><b>Транспорт:</b></legend><table><tr><td align="right" width="90"><b>Тягач:</b></td><td align="center" width="80"><div id="auto_name"></div></td><td align="right" width="70"><b>Г/н:</b></td><td align="center" width="200"><div id="auto_number"></div></td><td rowspan="2" width="400"><fieldset style="float:right;"><legend><b>Водитель:</b></legend>Ф.И.О.:&nbsp;&nbsp;<b><div id="drv_name" style="font-size:16px;display:inline;"></div></b></fieldset></td></tr><tr><td align="right"><b>Полуприцеп:</b></td><td align="center" width="50"><div id="dop_name"></div></td><td align="right"><b>Г/н:</b></td><td align="center"><div id="dop_number"></div></td></tr></table></fieldset>');



<?php if($_SESSION["group"]==3||$_SESSION["group"]==4)
echo '$("#tr_cash").attr("readonly","readonly");'; ?>


$("#ati_km").html('<fieldset><legend><b>Расстояние:</b></legend>Километраж по «ati»: <input type="text" name="ati_km" id="km" value="0" style="width:50px;" class="input"> км</fieldset>');

var cl_cash_vtl=parseInt(document.getElementById('cl_cash').value, 10);

if(document.getElementById('cl_cash_min').value==""){
  cl_cash_min=0;
} else {
  var cl_cash_min=parseInt(document.getElementById('cl_cash_min').value, 10);
}
if(document.getElementById('cl_cash_plus').value==""){
  cl_cash_plus=0;
} else {
  var cl_cash_plus=parseInt(document.getElementById('cl_cash_plus').value, 10);
}

//if(cl_cash_vtl<=20000)$('#tr_cash').val(cl_cash_vtl-cl_cash_min+cl_cash_plus-4000);
//if(cl_cash_vtl>20000)$('#tr_cash').val(cl_cash_vtl-cl_cash_min+cl_cash_plus-5000);

<?php if ($order!="") {echo '$("#tr_cash").val('.$row['tr_cash'].');$("#vtl_cont_select").val('.$row['cl_cont'].').change();';}?>


Komiss();


}

function UnSetVTL(){
document.getElementById('tr_select').style.display='inline';
document.getElementById('tr_receive_select').style.display='inline';
document.getElementById('tr_edit_vtl').style.display='inline';
document.getElementById('tr_select_vtl').style.display='none';
document.getElementById('tr_nds_temp').style.display='inline';
document.getElementById('vtl_cont').style.display='none';
document.getElementById('tr_cont').style.display='inline';
document.getElementById('show_tr_dpay1').style.display='inline';
document.getElementById('show_tr_dpay2').style.display='inline';
$("#tr_nds").removeAttr('readonly');
$("#tr_cash").removeAttr('readonly');

$("#tr_cash_min").attr("disabled","");
$("#tr_cash_plus").attr("disabled","");
$("#tr_event").attr("disabled","");

$("#transporter").val(0);
$("#tr_receive").val(0);
$("#tr_select").setValue("");
$("#tr_nds").val(0).change();
$("#tr_pref_temp").html("ООО");
$("#tr_tfpay").val("");
$("#tr_event").val(0).change();
$("#temp_tr_nds").val(0);
$("#hidden_value_tr").html('');
$("#car_details").html('');

$("#vtl_nds").html('');

$("#car_select").html('<select name="tr_autopark" style="width:170px;" id="tr_autopark" class="select" onchange="$(\'#img\').attr(\'src\',\'data/img/pencil.png\');"><option value="0">Выберите...</option></select>');
$("#tr_autopark").attr("disabled","disabled");
$("#dop_select").html("");
$("#drv_select").html("");

$('#tr_receive_select').setValue("");
$('#hidden_value_tr_receive').html('');
}


$(document).ready(function() {


  
$('#cl_select').flexbox('control/cl_search.php', {

    paging: {  
        summaryTemplate: 'Показать {start}-{end} из {total} клиентов',
        pageSize: 6  
    } , 
    width: 300,watermark: 'Выберите...',

hiddenValue: 'id',
onSelect: function() {

var arr = $('#cl_select_hidden').val().split(/[|]/);
$('#cl_nds').val(arr[1]).change();
$('#cl_pref_temp').html(arr[3]);
if(arr[0]==651){$('#tr_days_input').css('display','');}
$('#cl_tfpay').val(arr[4]);
$('#cl_event').val(arr[5]).change();
$('#cl_cont').val(arr[6]).change();
$('#cl_select_info').html($('.ffb-sel').html());
$('#hidden_value_cl').html('<input type="hidden" name="client" id="client" value="'+arr[0]+'"><input type="hidden" name="cl_pref" id="cl_pref" value="'+arr[2]+'">');}
});    

<?php  
if ($order!="") {echo '$("#tr_manager").val('.$row['tr_manager'].').change();';} else {echo '$("#tr_manager").val('.$_SESSION["user_id"].').change();';}

if ($order!="") {
$query_cl = "SELECT `id`,`name` FROM `clients` WHERE `Id`='".$row['client']."'";
$result_cl = mysql_query($query_cl) or die(mysql_error());
$client = mysql_fetch_row($result_cl);
echo '$("#cl_select").setValue("'.addslashes($client[1]).'");';
echo '$("#hidden_value_cl").html("<input type=\"hidden\" name=\"client\" id=\"client\" value=\"'.$client[0].'\">");if('.$client[0].'==651){$("#tr_days_input").css("display","");$("#tr_days").val('.$row['tr_days'].')}';
}?>


$('#tr_select').flexbox('control/tr_search.php', {

    paging: {  
        summaryTemplate: 'Показать {start}-{end} из {total} клиентов',
        pageSize: 6  
    } , 
    width: 240,watermark: 'Выберите...',

hiddenValue: 'id',
onSelect: function() {

var arr = $('#tr_select_hidden').val().split(/[|]/);
$('#tr_nds').val(arr[1]).change();
$('#tr_pref_temp').html(arr[3]);
$('#tr_tfpay').val(arr[4]);
$('#tr_event').val(arr[5]).change();
$('#tr_cont').val(arr[6]).change();
$('#temp_tr_nds').val(arr[1]);
$('#tr_manager').val(arr[7]).change();;

//$('#tr_manager_name').html('&nbsp;&nbsp;Менеджер, предост. перевозчика: <font size="4"><b>'+arr[8]+'</b></font>');

document.getElementById('car_add').style.visibility = "visible";
$('#hidden_value_tr').html('<input type="hidden" name="transporter" id="transporter" value="'+arr[0]+'"><input type="hidden" name="tr_pref" id="tr_pref" value="'+arr[2]+'">');
$("#tr_autopark").attr("disabled","");

$("#car_select").load("control/car.php?id="+arr[0]);

 
$('#tr_receive_select').setValue(this.value);
$('#hidden_value_tr_receive').html('<input type="hidden" name="tr_receive" id="tr_receive" value="'+arr[0]+'">');

}
}); 


$('#tr_receive_select').flexbox('control/tr_search.php', {

    paging: {  
        summaryTemplate: 'Показать {start}-{end} из {total} клиентов',
        pageSize: 6  
    } , 
    width: 240,watermark: '...',

hiddenValue: 'id',
onSelect: function() {

var arr = $('#tr_receive_select_hidden').val().split(/[|]/);

$('#hidden_value_tr_receive').html('<input type="hidden" name="tr_receive" id="tr_receive" value="'+arr[0]+'">');
}
}); 


<?php  if ($order!="") {
$query_tr = "SELECT `id`,`name`,`nds` FROM `transporters` WHERE `Id`='".$row['transp']."'";
$result_tr = mysql_query($query_tr) or die(mysql_error());
$transp = mysql_fetch_row($result_tr);
echo '$("#tr_select").setValue("'.$transp[1].'");$("#temp_tr_nds").val('.$transp[2].');';
echo '$("#hidden_value_tr").html("<input type=\"hidden\" name=\"transporter\" id=\"transporter\" value=\"'.$transp[0].'\">");';

$query_tr = "SELECT `id`,`name` FROM `transporters` WHERE `Id`='".$row['tr_receive']."'";
$result_tr = mysql_query($query_tr) or die(mysql_error());
$transp = mysql_fetch_row($result_tr);
echo '$("#tr_receive_select").setValue("'.$transp[1].'");';
echo '$("#hidden_value_tr_receive").html("<input type=\"hidden\" name=\"tr_receive\" id=\"tr_receive\" value=\"'.$transp[0].'\">");';
}?>

      


  
$('#in_adr_select').flexbox('control/adr_search.php?mode=1', {
watermark: 'Поиск по адресам загрузки...',
    paging: {  
        summaryTemplate: 'Показать {start}-{end} из {total} адресов',
        pageSize: 6  
    } , 
    width: 750,
    

hiddenValue: 'id',
onSelect: function() {
var $value=$('#in_adr_select_hidden').val();
$('#adr_in_validate').html('');

$('#adr_in_value').append('<div style="padding: 10px 10px 10px 10px;background: #ddd;border: 1px solid #bbb;width: 99%; " id="adr'+$value+'"><input type="hidden" name="in_adr[]" id="in_adr'+$value+'" value="'+$value+'">'+this.value+'&nbsp;&nbsp;<div style="float:right;"><a href="#" onClick=\'javascript: $("#fa_adr").load("theme/forms/add_adr.php?adr_id='+$value+'");$("#fa_adr").dialog({ title: "Редактировать адрес №'+$value+'" },{width: 470,height: 650,modal: true,resizable: false});$("#adr'+$value+'").remove();$("#in_adr'+$value+'").remove();$(this).remove();\'>[редактировать]</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="#" onClick=\'javascript: $("#adr'+$value+'").remove();$("#in_adr'+$value+'").remove();$(this).remove();\'>[удалить]</a></div></div>');

$('#in_adr_select').setValue('');


}
});    
    
    
$('#out_adr_select').flexbox('control/adr_search.php?mode=2', {
watermark: 'Поиск по адресам загрузки...',
    paging: {  
        summaryTemplate: 'Показать {start}-{end} из {total} адресов',
        pageSize: 6  
    } , 
    width: 750,
    

hiddenValue: 'id',
onSelect: function() {
var $value=$('#out_adr_select_hidden').val();
$('#adr_out_validate').html('');

$('#adr_out_value').append('<div style="padding: 10px 10px 10px 10px;background: #ddd;border: 1px solid #bbb;width: 99%; " id="adr'+$value+'"><input type="hidden" name="out_adr[]" id="out_adr'+$value+'" value="'+$value+'">'+this.value+'&nbsp;&nbsp;<div style="float:right;"><a href="#" onClick=\'javascript: $("#fa_adr").load("theme/forms/add_adr.php?adr_id='+$value+'");$("#fa_adr").dialog({ title: "Редактировать адрес №'+$value+'" },{width: 470,height: 650,modal: true,resizable: false});$("#adr'+$value+'").remove();$("#out_adr'+$value+'").remove();$(this).remove();\'>[редактировать]</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href="#" onClick="javascript: $(\'#adr'+$value+'\').remove();$(\'#out_adr'+$value+'\').remove();$(this).remove();">[удалить]</a></div></div>');

$('#out_adr_select').setValue('');

}
});       
    
    });
 
 
    
// - - форма добавления заявки - сохранение  - - >      
 $("#form_order").submit(function() { 
  $('#result_temp').html('');
  validate=true;
  if($("#client").val()==''){ 
 	$('#result_temp').append('Выберите клиента!<br>');validate=false;}
 
 if($("#cl_cash").val()==''){ 
 	$('#result_temp').append('Заполните ставку клиента!<br>');validate=false;}
 

 
 
 if($("#in_adr").val()==''){ 
 	$('#result_temp').append('Выберите адрес загрузки!<br>');validate=false;} 
 
if($("#out_adr").val()==''){  	$('#result_temp').append('Выберите адрес выгрузки!<br>');validate=false;} 
 
 if($("#transporter").val()==''){ 
 	$('#result_temp').append('Выберите перевозчика!<br>');validate=false;}
  
  if($("#tr_receive").val()=='0'){ 
 	$('#result_temp').append('Выберите получателя для перевозчика!<br>');validate=false;}
 
    if($("#cl_event").val()=='0'){ 
 	$('#result_temp').append('Укажите точку оплаты для клиента!<br>');validate=false;}
 	
   if($("#tr_event").val()=='0'){ 
 	$('#result_temp').append('Укажите точку оплаты для перевозчика!<br>');validate=false;}
 	   		
    if($("#tr_cash").val()==''){ 
 	$('#result_temp').append('Заполните ставку перевозчика!<br>');validate=false;} 
 	
 	 if($("#out_data1").val()==''){ 
 	$('#result_temp').append('Заполните дату выгрузки!<br>');validate=false;} 
 	
<?php if($_SESSION["group"]==3){
echo "if(\$('#tr_autopark').val()=='0'){\$('#result_temp').append('Выберите автотранспорт!<br>');validate=false;}";
 }?>

if($(':radio[name=transp_mode]').filter(':checked').val()=='vtl'&&($("select#tr_autopark").val()==0||$("select#dop_autopark").val()==0||$("select#drv_autopark").val()==0)){$('#result_temp').append('Выберите тягач, полуприцеп и водителя!<br>');validate=false;}


  if(!validate){$("#result_temp").dialog({ title: 'Внимание' },{width: 330 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");}}});} 

   

    
    
 if(validate){
      var perfTimes = $("#form_order").serialize(); 
  
      $.post("control/order_add.php", perfTimes, function(data) {
      $("input").unbind();
      var arr = data.split(/[|]/);
      
      if(arr[1]==1){$("#fa").dialog('close');$('#form_order').unbind();document.getElementById('security_code').style.visibility = "hidden";}
      
    $('#result').html(arr[0]);
   
      

      $("#result").dialog({ title: 'Сообщение' },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); if(arr[1]==2){
document.getElementById('security_code').style.visibility = "visible";
$("#order_code").val("");
      } else {if(arr[1]==1){if($("#transporter").val()=='2'||$("#transporter").val()=='462'){$('#dialogpr').dialog({ buttons: [{text: 'Клиенту',click: function() { 
window.open('control/print.php?order_type=1&mode=cl&id='+base64encode(arr[2]));$(this).dialog('close');}}] },{ resizable: false });}else {$('#dialogpr').dialog({ buttons: [{text: 'Клиенту',click: function() { 
window.open('control/print.php?order_type=1&mode=cl&id='+base64encode(arr[2]));$(this).dialog('close');}},{text: 'Перевозчику',click: function() {window.open('control/print.php?order_type=1&mode=tr&id='+base64encode(arr[2]));$(this).dialog('close');}}] },{ resizable: false });}}}
      
      
      } } });
      
      
      
      jQuery("#table").trigger("reloadGrid");}); 
  
  }
  return false;
  
  });
  
  $("#cl_currency").val("руб").change;
    
<?php  
if ($order!="") {

if($row['international_number']!="0") echo '$("#international").val(1).change;'; else echo '$("#international").val(0).change;';

//echo 'document.getElementById("order_agat_new").style.display="inline";$("#order_agat_new").html("<input type=\"button\" id=\"btnRenewOrd\" onclick=\'$.post(\"control/admin.php?mode=renew_agat&ord_agat_id='.$row['id'].'\", function(data) {$(\"#order_agat_new\").html(data);}); \' value=\"Присвоить номер\" style=\"width: 140px;color:#000000;\" class=\"button4\">");';

switch ($row['cl_pref']) {
case '1': $pref_cl='ООО';break;
case '2': $pref_cl='ОАО';break;
case '3': $pref_cl='ИП';break;
case '4': $pref_cl='ЗАО';break;
case '5': $pref_cl='';break;
case '6': $pref_cl='Физ.Л.';break;
case '7': $pref_cl='АО';break;
}
echo '$("#cl_pref_temp").html("&nbsp;&nbsp;&nbsp;'.$pref_cl.'&nbsp;");$("#hidden_value_cl").html("<input type=\"hidden\" name=\"client\" id=\"client\" value=\"'.$row['client'].'\"><input type=\"hidden\" name=\"cl_pref\" id=\"cl_pref\" value=\"'.$row['cl_pref'].'\">");';
$pref_tr='';
switch ($row['tr_pref']) {
case '1': $pref_tr='ООО';break;
case '2': $pref_tr='ОАО';break;
case '3': $pref_tr='ИП';break;
case '4': $pref_tr='ЗАО';break;
case '5': $pref_tr='';break;
case '6': $pref_tr='Физ.Л.';break;
case '7': $pref_tr='АО';break;
}
echo '$("#tr_pref_temp").html("&nbsp;&nbsp;&nbsp;'.$pref_tr.'&nbsp;");$("#hidden_value_tr").html("<input type=\"hidden\" name=\"transporter\" id=\"transporter\" value=\"'.$row['transp'].'\"><input type=\"hidden\" name=\"tr_pref\" id=\"tr_pref\" value=\"'.$row['tr_pref'].'\">");';

//
echo '$("#cl_tfpay").val('.$row['cl_tfpay'].');$("#cl_event").val('.$row['cl_event'].').change();$("#cl_cont").val('.$row['cl_cont'].').change();$("#cl_currency").val("'.$row['cl_currency'].'").change();';
echo '$("#tr_tfpay").val('.$row['tr_tfpay'].');$("#tr_event").val('.$row['tr_event'].').change();$("#tr_cont").val('.$row['tr_cont'].').change();$("#tr_currency").val("'.$row['tr_currency'].'").change();';

echo '$("#cl_cash").val('.$row['cl_cash'].');$("#cl_kop").val('.$row['cl_kop'].');$("#cl_cash_min").val('.$row['cl_minus'].');$("#cl_cash_plus").val('.$row['cl_plus'].');';
echo '$("#tr_cash").val('.$row['tr_cash'].');$("#tr_cash_min").val('.$row['tr_minus'].');$("#tr_cash_plus").val('.$row['tr_plus'].');';

echo '$("#cl_rashod_na_cl").val('.$row['cl_rashod_na_cl'].');$("#cl_rashod_sb").val('.$row['cl_rashod_sb'].');';
echo '$("#cl_komissia").val('.$row['cl_komissia'].');$("#tr_komissia").val('.$row['tr_komissia'].');';

if($row['date_in1']=="0000-00-00"||$row['date_in1']=="1970-01-01")$date_in1=""; else $date_in1=date("d/m/Y",strtotime($row['date_in1']));
if($row['date_in2']=="0000-00-00"||$row['date_in2']=="1970-01-01")$date_in2=""; else $date_in2=date("d/m/Y",strtotime($row['date_in2']));
if($row['date_out1']=="0000-00-00"||$row['date_out1']=="1970-01-01")$date_out1=""; else $date_out1=date("d/m/Y",strtotime($row['date_out1']));
if($row['date_out2']=="0000-00-00"||$row['date_out2']=="1970-01-01")$date_out2=""; else $date_out2=date("d/m/Y",strtotime($row['date_out2']));
if($row['data']=="0000-00-00"||$row['data']=="1970-01-01")$data=""; else $data=date("d/m/Y",strtotime($row['data']));



echo '$("#gruz_name").val("'.addslashes($row['gruz']).'");$("#gruz_m").val("'.$row['gr_m'].'");$("#gruz_v").val("'.$row['gr_v'].'");$("#gruz_num").val("'.(int)$row['gr_number'].'");$("#tr_receive").val("'.$row['tr_receive'].'");$("#in_data1").val("'.$date_in1.'");$("#in_data2").val("'.$date_in2.'");$("#out_data1").val("'.$date_out1.'");$("#out_data2").val("'.$date_out2.'");$("#in_time11").val("'.$row['time_in11'].'");$("#out_time1").val("'.$row['time_out1'].'");$("#out_time2").val("'.$row['time_out2'].'");$("#in_time12").val("'.$row['time_in12'].'");$("#in_time22").val("'.$row['time_in22'].'");$("#in_time21").val("'.$row['time_in21'].'");$("#order_id").val("'.$row['id'].'");$("#order_id_show").html("'.$row['id'].'");$("#tr_gruz_worker").val("'.$row['tr_gruz_worker'].'");';


if($_SESSION["group"]==1||$_SESSION["group"]==2){
  echo '$("#data").val("'.$data.'");';
}

if($row['days_tfpay']==1)echo '$("#days_tfpay").attr("checked","checked");';

$str_in = explode('&',$row['in_adress']);
$str_adr_in = (int)sizeof($str_in)-2;

$i=0;
while ($i <= $str_adr_in) {
echo '$("#adr_temp").load("control/adr_search.php?adr_id='.$str_in[($str_adr_in-$i)].'",function(data){$("#adr_in_value").append("<div style=\"padding: 10px 10px 10px 10px;background: #ddd;border: 1px solid #bbb;width: 99%;\" id=\"adr'.$str_in[($str_adr_in-$i)].'\">'.($i+1).') <input type=\"hidden\" name=\"in_adr[]\" id=\"in_adr'.$str_in[($str_adr_in-$i)].'\" value=\"'.$str_in[($str_adr_in-$i)].'\">"+data+"&nbsp;&nbsp;<div style=\"float:right;\"><a href=\"#\" onClick=\'javascript: $(\"#fa_adr\").load(\"theme/forms/add_adr.php?adr_id='.$str_in[($str_adr_in-$i)].'\");$(\"#fa_adr\").dialog({ title: \"Редактировать адрес №'.$str_in[($str_adr_in-$i)].'\" },{width: 470,height: 650,modal: true,resizable: false});$(\"#adr'.$str_in[($str_adr_in-$i)].'\").remove();$(\"#in_adr'.$str_in[($str_adr_in-$i)].'\").remove();$(this).remove();\'>[редактировать]</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href=\"#\" onClick=\'javascript: $(\"#adr'.$str_in[($str_adr_in-$i)].'\").remove();$(\"#in_adr'.$str_in[($str_adr_in-$i)].'\").remove();$(this).remove();\'>[удалить]</a></div></div>"); });';
$i++;
}

$str_out = explode('&',$row['out_adress']);
$str_adr_out = (int)sizeof($str_out)-2;

$i=0;
while ($i <= $str_adr_out) {
echo '$("#adr_temp").load("control/adr_search.php?adr_id='.$str_out[$i].'",function(data){$("#adr_out_value").append("<div style=\"padding: 10px 10px 10px 10px;background: #ddd;border: 1px solid #bbb;width: 99%;\" id=\"adr'.$str_out[$i].'\">'.($i+1).') <input type=\"hidden\" name=\"out_adr[]\" id=\"out_adr'.$str_out[$i].'\" value=\"'.$str_out[$i].'\">"+data+"&nbsp;&nbsp;<div style=\"float:right;\"><a href=\"#\" onClick=\'javascript: $(\"#fa_adr\").load(\"theme/forms/add_adr.php?adr_id='.$str_out[$i].'\");$(\"#fa_adr\").dialog({ title: \"Редактировать адрес №'.$str_out[$i].'\" },{width: 470,height: 650,modal: true,resizable: false});$(\"#adr'.$str_out[$i].'\").remove();$(\"#out_adr'.$str_out[$i].'\").remove();$(this).remove();\'>[редактировать]</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;<a href=\"#\" onClick=\'javascript: $(\"#adr'.$str_out[$i].'\").remove();$(\"#out_adr'.$str_out[$i].'\").remove();$(this).remove();\'>[удалить]</a></div></div>"); });';
$i++;
}
if($row['transp']==2||$row['transp']==462){echo 'SetVTL();$("#km").val('.$row['km'].');$("#tr_sel_vtl").val('.$row['transp'].').change;$("#tr_sel_r_vtl").val('.$row['tr_receive'].').change;';}
else {
echo '$("#tr_autopark").attr("disabled","");$("#car_select").load("control/car.php?id='.$row['transp'].'&car='.$row['tr_auto'].'", function(data){$("#car_select").html(data);$("#tr_autopark").val('.$row['tr_auto'].').change();getCar();});';
	
echo 'document.getElementById("car_add").style.visibility = "visible";';
}

echo '$("#adr_out_validate").html("");$("#adr_in_validate").html("");$("#cl_pref").val('.$row['cl_pref'].').change();$("#cl_nds").val('.$row['cl_nds'].').change();$("#tr_pref").val('.$row['tr_pref'].').change();$("#tr_nds").val('.$row['tr_nds'].').change();$("#gruz_load").val('.$row['gr_load'].').change();Komiss();$("#select_manager").val('.$row['manager'].').change();';


}
?>     

<?php 
if($_GET['gruz_id']!='') {

 
switch ((int)$_GET['gruz_cl_id']) {
case '1': $cl_gruz_id='422';$cl_gruz_cont='2';$cl_gruz_cl_pref='1';$cl_gruz_manager='23';break;
case '2': $cl_gruz_id='522';$cl_gruz_cont='2';$cl_gruz_cl_pref='1';$cl_gruz_manager='9';break;
case '3': $cl_gruz_id='549';$cl_gruz_cont='2';$cl_gruz_cl_pref='1';$cl_gruz_manager='7';break;
}



echo '$("#hidden_value_cl").html("<input type=\"hidden\" name=\"client\" id=\"client\" value=\"'.$cl_gruz_id.'\"><input type=\"hidden\" name=\"cl_pref\" id=\"cl_pref\" value=\"'.$cl_gruz_cl_pref.'\">");';
echo '$("#manager").val('.(int)$cl_gruz_manager.');$("#cl_cash").val('.(int)$_GET['gruz_cl_cash'].');$("#cl_tfpay").val(10);$("#cl_event").val(4).change();$("#cl_nds").val(1).change();';



}?>


</script>
<form method="post" id="form_order">

<table width="100%" style="margin-bottom:5px;">
<tr><td>
Дата: <b><font size="5"><?php 
if ($order==""){
echo date("d/m/Y");} else { echo date("d/m/Y",strtotime($row['data']));}
?> г.</font>
</b></td>
<td>
Тип заявки:
  <select name="international" style="width:150px;" id="international" class="select">
    <option value="0">Российская</option>
    <option value="1">Международная</option>
  </select>

</td><td >
  <div style="float:right;">Менеджер: 
  <b><font size="4">
      <?php if ($order==""){ 
        echo $_SESSION["user"];
      } else {
        $query_manager = "SELECT name FROM workers WHERE id=".$row['manager'];
        $result_manager = mysql_query($query_manager) or die(mysql_error()); 
        $manager = mysql_fetch_row($result_manager);
        echo $manager[0];
        //$order_owner_name = explode(" ", $manager[0]);
        //echo $order_owner_name[0]." ".$order_owner_name[1];
      }?>
    </font></b>
    </div>
</td><td align="center"><div id="order_agat_new" style="display: none;"></div></td>
</tr>
</table>
<div id="adr_temp" style="display: none;"></div>
<div id="cash_ok" style="display: none;"><input type="hidden" name="cash_ok" id="cash_ok" value="2"></div>

<input type="hidden" name="gruz_id" id="gruz_id" value="<?php echo $_GET['gruz_id'];?>">

<div id="hidden_value_cl" style="display: none;"><input type="hidden" name="client" id="client" value=""></div>
<div id="hidden_value_tr" style="display: none;"><input type="hidden" name="transporter" id="transporter" value="">
</div>
<div id="hidden_value_tr_receive" style="display: none;"><input type="hidden" name="tr_receive" id="tr_receive" value="0"></div>
<div id="hidden_car_value" style="display: none;"><input type="hidden" name="car" id="car" value="0"></div>
<div id="hidden_value">
<?php  if ($order!="") {
if($row['block']=='1'||($_SESSION["user_id"]!=$row['manager']&&$_SESSION["group"]=='3')){
	
	// Managers can edit only their orders - now disabled
	//echo '<input type="hidden" name="block" value="1"><script type="text/javascript">$("#block_info").html("Заблокирована");</script>';
}


echo '<input type="hidden" name="order" value="'.$row['id'].'"><input type="hidden" name="data" value="'.$row['data'].'"><input type="hidden" id="manager" name="manager" value="'.$row['manager'].'"><input type="hidden" name="edit" value="1">';
} else {
echo '<input type="hidden" name="manager" id="manager" value="'.$_SESSION['user_id'].'">';
} ?>




</div>

<div id="order_tabs">
<ul>
		<li><a href="#order_tabs-1" <?php if($_GET['gruz_id']!='') echo 'style="display:none;"';?>>Клиент</a></li>
<li><a href="#order_tabs-2">Перевозчик</a></li>
<li><a href="#order_tabs-3">Адреса</a></li>
<li><a href="#order_tabs-4">Груз</a></li>
<li><a href="#order_tabs-6">Даты/Номер</a></li>
<li><a href="#order_tabs-5">Дополнительно</a></li>
	</ul>


<div id="order_tabs-1" style="height: 31em; overflow: auto;<?php if($_GET['gruz_id']!='') echo 'display:none;';?>">



<table><tr><td width="120">
<b>Клиент:</b><div id="cl_pref_temp" style="display:inline;"><div>
  </td>
  <td width="310">
<div id="cl_select"></div>
 </td>
<td>
  
<a href="#" style="font-size: 20px;text-decoration: none;float:left;" onclick='$("#fa_cl").load("theme/forms/add_client.php");$("#fa_cl").dialog({ title: "Новый клиент" },{width: 980,height: 560,modal: true,resizable: false});'>&nbsp;&nbsp;<img src="data/img/plus.png"></a>
<a href="#" style="font-size: 20px;text-decoration: none;float:left;" onclick='if($("#client").val()!=""){$("#fa_cl").load("theme/forms/add_client.php?client="+$("#client").val());
$("#fa_cl").dialog({ title: "Редактировать клиента №"+$("#client").val() },{width: 980,height: 560,modal: true,resizable: false});} else {alert("Выберите клиента!");}'>&nbsp;&nbsp;<img src="data/img/pencil.png">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>

<select name="cl_nds"  id="cl_nds" class="select">
<option value="0" onclick="Komiss();">без НДС</option>
  <option value="1" onclick="Komiss();">с НДС</option>
  <option value="2" onclick="Komiss();">НАЛ</option></select>
</td></tr>
<tr>
<td align="right" height="40">
Выбран Клиент:
  </td><td>
<font size="4"><div id="cl_select_info"></div> </font>

</td><td>&nbsp;&nbsp;&nbsp;Контактёр:&nbsp;&nbsp;
<select name="cl_cont"  id="cl_cont" class="select">
<?php  foreach ($_SESSION["company"] as $key => $value)  {
        echo '<option value='.$key.'>'.$value.'</option>';
    } 
?> 
</select>

</td></tr>
</table>
 <fieldset><legend><b>Оплата:</b></legend> 
<table>
<tr><td align="right">
<b>Ставка:</b></td><td width="200"> <input name="cl_cash" id="cl_cash" style="width: 70px;" placeholder="0" class="input" onchange="Komiss();"> 

<!-- 
  if($(':radio[name=transp_mode]').filter(':checked').val()=='vtl'){var cl_cash_vtl=parseInt(document.getElementById('cl_cash').value, 10);
  if(cl_cash_vtl<=20000)$('#tr_cash').val(cl_cash_vtl-4000);
  if(cl_cash_vtl>20000)$('#tr_cash').val(cl_cash_vtl-5000);
}
-->

<select name="cl_currency" style="width:70px;" id="cl_currency" class="select" onchange="$('#currency_info1').html($(this).val());$('#currency_info2').html($(this).val());$('#currency_info3').html($(this).val());$('#currency_info4').html($(this).val());$('#currency_info5').html($(this).val());">
  <option value="руб">руб</option>
  <option value="USD">USD</option>
  <option value="Euro">Euro</option>
</select>

</td>

<!--
&nbsp;&nbsp;<input name="cl_kop" id="cl_kop" style="width: 20px;" placeholder="0" class="input" > коп.&nbsp;&nbsp;</td>
-->

<td width="110"><b>Срок оплаты:</b>&nbsp;</td><td width="110"><input name="cl_tfpay" id="cl_tfpay" style="width: 50px;" placeholder="0" class="input" value="">&nbsp;дней&nbsp;
</td>

<td rowspan="2"><b>Точка отсчета:</b>&nbsp;&nbsp;<select name="cl_event" style="width:180px;" id="cl_event" class="select">
  <option value="0">Выберите...</option><option value="1">Загрузка</option>
  <option value="2">Выгрузка</option>
  <option value="3">Поступление факсимильных документов</option>
  <option value="4">Поступление оригинальных документов</option></select>
</td>

</tr>
<tr>
    <td align="right" width="100"><br>
        Надбавка(+):</td><td><br><input name="cl_cash_plus" id="cl_cash_plus" style="width: 60px;" placeholder="0" class="input" onchange="Komiss()"></td>

    <td align="right"><br>
        <b>Комиссия</b></td><td><br><input name="cl_komissia" id="cl_komissia" style="width: 40px;" placeholder="0" class="input" onchange=""> %</td>
</tr>
</table>



</fieldset>

    <fieldset><legend><b>Дополнительные расходы:</b></legend>
    <table>
        <tr>

        <td align="right">
                Вычет(-):</td>
                <td>
                  <input name="cl_cash_min" id="cl_cash_min" style="width: 60px;" placeholder="0" class="input" onchange="Komiss()"> <div id="currency_info1" style="display: inline;"></div>
                </td>
        
        <td align="right" width="150">
        Расход СБ:</td>
        <td>
          <input name="cl_rashod_sb" id="cl_rashod_sb" style="width: 60px;" placeholder="0" class="input" onchange="Komiss()"> <div id="currency_info2" style="display: inline;"></div>
        </td>

        <td align="right" width="250">
        Доп. Расходы на клиента:</td>
        <td>
          <input name="cl_rashod_na_cl" id="cl_rashod_na_cl" style="width: 60px;" placeholder="0" class="input" onchange="Komiss()"> <div id="currency_info3" style="display: inline;"></div>
        </td>
        </tr>
    </table>

    </fieldset>



</div>
<div id="order_tabs-3" style="height: 31em; overflow: auto">



<b>Адреса загрузки:</b>
<hr style="width: 100%; height: 2px;" />
<div id="in_adr_select">&nbsp;&nbsp;&nbsp;&nbsp;<a  id="btnAdd_adress_in" class="btnAdress" href="#">новый</a></div><br>
<div id="adr_in_value"></div>
<div id="adr_in_validate"><input type="hidden" name="in_adr" id="in_adr" value=""></div>

<br>

<b>Адреса выгрузки:</b>
<hr style="width: 100%; height: 2px;" />
<div id="out_adr_select">&nbsp;&nbsp;&nbsp;&nbsp;<a  id="btnAdd_adress_out" href="#" class="btnAdress">новый</a></div><br>
<div id="adr_out_value"></div>
<div id="adr_out_validate"><input type="hidden" name="out_adr" id="out_adr" value=""></div>


<fieldset><legend>Дополнительно:</legend><input type="checkbox" name="krugoreis" id="krugoreis" <?php if ($row['krugoreis']=='1'){echo 'checked';}?> value="<?php echo $row['krugoreis'];?>" onclick="if(this.checked){$('#krugoreis').val(1);} else {$('#krugoreis').val(0);}"> кругорейс <div id="ati_km"></div></fieldset>


</div>
<div id="order_tabs-4" style="height: 31em; overflow: auto;">

<fieldset><legend><b>Груз:</b></legend>
<table cellpadding="5"><tr><td>Наименование:</td><td>
<input name="gruz_name" id="gruz_name" style="width: 140px;" placeholder="Укажите название" class="input">
</td><td>Вес:</td><td>
<input name="gruz_m" id="gruz_m" style="width: 40px;" placeholder="0 т" class="input">
</td>
<td>Обьем:</td><td>
<input name="gruz_v" id="gruz_v" style="width: 40px;" placeholder="0 м3" class="input">
</td>
<td>Кол-во:</td><td align="right">
<input name="gruz_num" id="gruz_num" style="width: 40px;" placeholder="0 ед." class="input">
</td>
<td>Загрузка:</td><td>
<select name="gruz_load" style="width:130px;" id="gruz_load" class="select">


  <option value="0">Выберите...</option>
  <option value="1">верхняя</option>
<option value="2">задняя</option>
<option value="3">боковая</option>
  </select>
</td>
</tr>
</table>
</fieldset>

<fieldset><legend><b>Грузчики:</b></legend>
<table cellpadding="5"><tr><td>Стоимость грузчиков:</td><td>
<input name="tr_gruz_worker" id="tr_gruz_worker" style="width:50px;" placeholder="0" class="input">
<div id="currency_info4" style="display: inline;"></div>
</td>
</tr>
</table>
</fieldset>
 



<fieldset><legend><b>Требования к машине (Особые условия):</b></legend>
<textarea rows="9" name="car_notify" style="width:98%;">
<?php if($row['car_notify']!="") echo $row['car_notify']; else echo 'Чистый кузов, возможность пломбировки, БЕЗ догрузов, Водитель гражданин РФ паспорт с собой, без судимости';?>
</textarea>
</fieldset>
</div>

<div id="order_tabs-2" style="height: 31em; overflow: auto">



<input type="radio" name="transp_mode" id="tr_mode" value="tr" <?php if($row['transp']!=2||$row['transp']!=462)
echo 'checked';?> onclick="UnSetVTL();">&nbsp;&nbsp;<b>Перевозчик:</b>

<input type="radio" name="transp_mode" id="vtl_mode" value="vtl" <?php if($row['transp']==2||$row['transp']==462)
echo 'checked';?> onclick='SetVTL();'>&nbsp;&nbsp;&nbsp;<b>Автопарк</b>

<table height="80"><tr><td width="100">



<div id="tr_pref_temp" style="display:inline;"><div>
  
</td>
  <td width="250"><div id="tr_select" style="float:left; width:255px;"></div><div id="tr_select_vtl" style="display:none;"></div>
  <div id="vtl_cont" style="display:none;">
<select name="vtl_cont_select" style="width:230px;" id="vtl_cont_select" class="select">
<option value="2">Собственный  транспорт</option>
  </select>
</div>
  
  </td><td width="100">

<div id="tr_edit_vtl" style="display:inline;">
  
<a href="#" style="font-size: 20px;text-decoration: none;float:left;" onclick='$("#fa_tr").load("theme/forms/add_transporter.php");$("#fa_tr").dialog({ title: "Новый перевозчик" },{width: 990,height: 560,modal: true,resizable: false});'>&nbsp;&nbsp;<img src="data/img/plus.png"></a>
<a href="#" style="font-size: 20px;text-decoration: none;float:left;" onclick='if($("#transporter").val()!=""){$("#fa_tr").load("theme/forms/add_transporter.php?tr="+$("#transporter").val());$("#fa_tr").dialog({ title: "Редактировать перевозчика №"+$("#transporter").val()},{width: 990,height: 560,modal: true,resizable: false});} else {alert("Выберите Перевозчика!");}'>&nbsp;&nbsp;<img src="data/img/pencil.png">&nbsp;&nbsp;</a>  
</div>
<input type="hidden" name="temp_tr_nds" id="temp_tr_nds" value="">


</td>


<td rowspan="2" width="20"></td><td align="right">&nbsp;&nbsp;&nbsp;<b>Транспорт</b></td><td>

<div id="car_select" style="display:inline;">
<select name="tr_autopark" style="width:130px;" id="tr_autopark" class="select"><option value="0">Выберите...</option>
</select></div>
</td><td align="left" valign="middle">&nbsp;&nbsp;<div id="car_add" style="display:inline;"><img id="img" src="data/img/car_add.png" style="cursor: pointer;margin-top:5px;" onclick='if($("#tr_autopark").val()==0){$("#fa_car").load("theme/forms/add_car.php?tr="+$("#transporter").val());$("#fa_car").dialog({ title: "Новый транспорт" },{width: 580,height: 740,modal: true,resizable: false});} else {$("#fa_car").load("theme/forms/add_car.php?tr="+$("#transporter").val()+"&mode=car&edit=1&car_id="+$("#tr_autopark").val()); $("#fa_car").dialog({ title: "Редактировать транспорт" },{width: 580,height: 790,modal: true,resizable: false});}'>&nbsp;&nbsp;<a href="#" style="font-size: 20px;color:black;text-decoration: none;float:right;display:inline;margin-top:5px;" onclick='$("#fa_show_car").load("show_car.php?tr="+$("#transporter").val());$("#fa_show_car").dialog({ title: "Автопарк" },{width: 880,height: 560,modal: true,resizable: false});'>[Все]</a></div> </td></tr><tr><td align="right">Получатель:&nbsp;</td><td><div id="tr_receive_select"></div></td>

<td>

<div align="right" id="tr_days_input" style="display:none;"><b>Простой</b>:&nbsp;<input name="tr_days" id="tr_days" style="width: 30px;" placeholder="0" class="input" value="0"> сут.</div>

</td><td><div id="drv_select" style="display:inline;"></div><td><div id="dop_select" style="display:inline;"></div></td>


</td></tr></table>

<fieldset><legend><b>Оплата:</b></legend> 
<table cellspacing="5">
<tr><td width="90">
<b>Ставка:</b></td><td width="160"> <input name="tr_cash" id="tr_cash" style="width: 70px;" placeholder="0" class="input" onchange="Komiss()"> 

<select name="tr_currency" style="width:70px;" id="tr_currency" class="select">
    <option value="руб">руб</option>
    <option value="USD">USD</option>
    <option value="Euro">Euro</option>
</select>

</td>
<td>&nbsp;&nbsp;&nbsp;Способ опл.<div id="vtl_nds" style="display:none;"></div></td><td align="left"><div id="tr_nds_temp" style="display:inline;"><select name="tr_nds" id="tr_nds" class="select">
  <option value="0" onclick="if($('#temp_tr_nds').val()==2) {$('#result').html('Сформировать новый договор?');$('#result').dialog({ title: 'Сообщение' },{ modal: true },{ width: 400 },{ resizable: false },{ buttons: [{text: 'Да',click: function() {window.location.href='control/print_c.php?order_type=1&mode=tr&id='+$('#transporter').val()+'&change=0'; $(this).dialog('close');}},{text: 'Нет',click: function() {$(this).dialog('close');}}] },{width:300},{ resizable: false });}
  
  if($('#temp_tr_nds').val()==1) {$('#result').html('Перевозчик работает с НДС, для сохранения необходимо:');$('#result').dialog({ buttons: [{text: 'Добавить нового перевозчика',click: function() {$('#fa_tr').load('theme/forms/add_transporter.php');$('#fa_tr').dialog({ title: 'Новый перевозчик' },{width: 980,height: 485,modal: true,resizable: false});$(this).dialog('close');}},{text: 'Указать получателя',click: function() {$(this).dialog('close');}}] },{width:500},{ resizable: false });}
Komiss();
">без НДС</option>
  <option value="1" onclick="if($('#temp_tr_nds').val()==2) {$('#result').html('Сформировать новый договор?');$('#result').dialog({ title: 'Сообщение' },{ modal: true },{ width: 400 },{ resizable: false },{ buttons: [{text: 'Да',click: function() {window.location.href='control/print_c.php?order_type=1&mode=tr&id='+$('#transporter').val()+'&change=0'; $(this).dialog('close');}},{text: 'Нет',click: function() {$(this).dialog('close');}}] },{width:300},{ resizable: false });}
  
  if($('#temp_tr_nds').val()==0) {$('#result').html('Перевозчик работает без НДС, для сохранения необходимо:');$('#result').dialog({ buttons: [{text: 'Добавить нового перевозчика',click: function() {$('#fa_tr').load('theme/forms/add_transporter.php');$('#fa_tr').dialog({ title: 'Новый перевозчик' },{width: 980,height: 485,modal: true,resizable: false});$(this).dialog('close');}},{text: 'Указать получателя',click: function() {$(this).dialog('close');}}] },{width:500},{ resizable: false });}

 Komiss();
">с НДС</option>
  <option value="2" onclick="Komiss();">НАЛ</option></select></div></td>
<td colspan="4"><b>Точка отсчета:</b>&nbsp;&nbsp;<select name="tr_event" style="width:200px;" id="tr_event" class="select">
<option value="0">Выберите...</option><option value="1">Загрузка</option>
  <option value="2">Выгрузка</option>
  <option value="3">Поступление факсимильных документов</option>
  <option value="4">Поступление оригинальных документов</option></select></td>

</tr>
<tr>
<td><div id="show_tr_dpay1"><b>Срок опл.:</b>&nbsp;</div></td><td width="130"><div id="show_tr_dpay2"><input name="tr_tfpay" class="input" id="tr_tfpay" style="width: 30px;" placeholder="0">&nbsp;дн&nbsp;<input type="checkbox" name="days_tfpay" id="days_tfpay" value="1">Банк.</div></td>
<td colspan="2" width="260">

&nbsp;&nbsp;Менеджер: 
<select name="tr_manager" id="tr_manager" style="width:130px;" class="select">

<?php 
if($_SESSION["group"]==3)
$query_user = "SELECT `id`,`name` FROM `workers` WHERE `group`='3' AND `delete`='0'";
 else $query_user = "SELECT `id`,`name` FROM `workers` WHERE  `group`!='5' AND `delete`='0'";
$result_user = mysql_query($query_user) or die(mysql_error());
while($users= mysql_fetch_row($result_user)) {
$pieces = explode(" ", $users[1]);
$print_user=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
echo '<option value="'.$users[0].'">'.$print_user.'</option>';}
 ?> 
</select>

</select>
</td></td><td align="right">
Вычет(+):</td><td><input name="tr_cash_min" id="tr_cash_min" style="width: 60px;" placeholder="0" class="input" onchange="Komiss()"></td>
<td align="right">
Надбавка(-):</td><td><input name="tr_cash_plus" id="tr_cash_plus" style="width: 60px;" placeholder="0" class="input" onchange="Komiss()"></td>

</tr>
<tr><td colspan="4">
<b>Контактёр:</b>&nbsp; &nbsp;<select name="tr_cont"  id="tr_cont" class="select">

 <?php 
 foreach ($_SESSION["company"] as $key => $value)  {
        echo '<option value='.$key.'>'.$value.'</option>';
    } 
?> 
</select>

</td>
<td align="right">
        <b>Комиссия</b></td><td><input name="tr_komissia" id="tr_komissia" style="width: 40px;" placeholder="0" class="input" onchange=""> %</td>
</tr>
</tr>
</table>

</fieldset>



<div id="car_details"></div>


</div>


<div id="order_tabs-5" style="height: 31em; overflow: auto">

<fieldset><legend>Дополнения к заявке:</legend>
 <b>Клиенту</b><br><textarea  style="width:98%;" rows="3" name="print_cl"><?php echo $row['print_cl'];?></textarea><br><b>Перевозчику</b><br><textarea  style="width:98%;" rows="3" name="print_tr"><?php echo $row['print_tr'];?></textarea>
</fieldset>
<fieldset><legend>Примечание к заявке:</legend>
<textarea rows="8" name="order_notify" style="width:98%;"><?php echo addslashes($row['notify']);?></textarea>
</fieldset>
</div>
<div id="order_tabs-6" style="height: 31em; overflow: auto">


  
<?php 
if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4){
  
echo '<fieldset><legend>Дата заявки:</legend>
<table><tr> 
<td align="right" width="80">
  <b>Дата:</b>&nbsp;&nbsp;</td><td><input name="data" id="data" style="width: 80px;"  placeholder="-" class="input" value="'.date('d-m-Y').'"></td>';

//<input name="order_id" id="order_id" style="width: 80px;" onchange=\'$("#fa_show_car").load("control/ord_check.php?ord_id="+$(this).val(),function(data){if(data!="")$("#fa_show_car").dialog({ title: "Внимание!" },{width: 410,height: 80,modal: true,resizable: false});});\' placeholder="-" class="input" value="">

echo '<td align="right" width="210">
<b>Менеджер заявки:</b>&nbsp;&nbsp;</td><td><select name="select_manager" id="select_manager" style="width:174px;" class="select">';
$query_user = "SELECT `id`,`name` FROM `workers` WHERE `group`!='5' AND `delete`='0'";
$result_user = mysql_query($query_user) or die(mysql_error());
while($users= mysql_fetch_row($result_user)) {
$pieces = explode(" ", $users[1]);
$print_user=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
echo '<option value="'.$users[0].'" onclick=\'$("#manager").val($("select#select_manager").val());\'>'.$print_user.'</option>';}
echo '</select></td></tr>
</table>
</fieldset>';

} else echo '<input name="data" id="data" type="hidden" value="">';

?>



      
      <input name="order_id" id="order_id" type="hidden" value="">

<fieldset><legend>Загрузка:</legend>
<table><tr><td align="right" width="100">
с Дата:&nbsp;&nbsp;</td><td>
<input name="in_data1" id="in_data1" style="width: 80px;"  placeholder="-" class="input">
</td><td align="right" width="80">
с Время:&nbsp;&nbsp;</td><td>
<input name="in_time11" id="in_time11" style="width: 38px;"  placeholder="-" class="input">&nbsp;&nbsp;по Время:&nbsp;&nbsp;<input name="in_time12" id="in_time12" style="width: 38px;"  placeholder="-" class="input">&nbsp;&nbsp;&nbsp;*формат: дд/мм/гггг
</td></tr>
<tr><td align="right" width="100">
по Дата:&nbsp;&nbsp;</td><td>
<input name="in_data2" id="in_data2" style="width: 80px;"  placeholder="-" class="input">
</td><td align="right" width="80">
с Время:&nbsp;&nbsp;</td><td>
<input name="in_time21" id="in_time21" style="width: 38px;"  placeholder="-" class="input">&nbsp;&nbsp;по Время:&nbsp;&nbsp;<input name="in_time22" id="in_time22" style="width: 38px;"  placeholder="-" class="input">&nbsp;&nbsp;&nbsp;*формат: дд/мм/гггг
</td></tr>
</table>
</fieldset>

<fieldset><legend>Выгрузка:</legend>
<table><tr><td align="right" width="100">
c Дата:</td><td>
<input name="out_data1" id="out_data1" style="width: 80px;"  placeholder="-" class="input" value="">
</td><td align="right" width="80">
Время:&nbsp;&nbsp;</td><td>
<input name="out_time1" id="out_time1" style="width: 38px;"  placeholder="-" class="input">&nbsp;&nbsp;&nbsp;*формат: дд/мм/гггг
</td></tr>
<tr><td align="right" width="100">
по Дата:</td><td>
<input name="out_data2" id="out_data2" style="width: 80px;"  placeholder="-" class="input" value="">
</td><td align="right" width="80">
Время:&nbsp;&nbsp;</td><td>
<input name="out_time2" id="out_time2" style="width: 38px;"  placeholder="-" class="input">&nbsp;&nbsp;&nbsp;*формат: дд/мм/гггг
</td></tr>
</table>
</fieldset>

</div>
</div>




<!--
  
<fieldset>
<div style="margin-right:30px;">
  Комиссия: <div id="komissia" style="display:inline;font-size:20px;font-weight: bold; ">0</div> <div id="currency_info4" style="display: inline;"></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  Уровень рентабельности: <div id="quality" style="display:inline;font-size:18px;font-weight: bold; ">0 %</div>
</div>
</fieldset>

-->

<div id="security_code" style="float:left;">&nbsp;&nbsp;&nbsp;Пароль: <input type="password" name="order_code" id="order_code" style="width: 140px;" value="" class="input"></div><div id="block_info" style="float:right;font-size: 24px;color:red;"></div>

<div style="float:right;display:inline;margin-right:30px;margin:10px;">
    <input type="submit" id="save" value="Сохранить" style="width: 200px;height:40px;margin-right:10px;">
    <a class="button4" id="btnClose" href="#"  onclick='$("#fa").dialog("close");' style="width:120px;">Закрыть</a>
</div>




</form>