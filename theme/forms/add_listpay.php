<script type="text/javascript" src="data/jquery.printElement.js"></script>
<script type="text/javascript">

$('#listpay').load('control/listpay_load.php?load_listpay=1');


// - - кнопка добавление назначений - сохранение - - >
$("#form_listpay").submit(function() {  
      var perfTimes = $("#form_listpay").serialize(); 
      $.post("control/add_listpay.php", perfTimes, function(data) {
      
      var arr = data.split(/[|]/);
      //if(arr[1]==1){}
      $('#result').html(arr[0]);
      $("#fa_listpay").dialog("close");
 jQuery("#table").trigger("reloadGrid");
      
      $("#result").dialog({ title: 'Готово' },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close"); } } });});
     return false;  
  });
  

 
 
$('#Save_listpay').button();
$('#btnClose_listpay').button();
$('#btnPrintList').button();

</script>
<form method="post" id="form_listpay">

<div id="result"></div>

<fieldset><legend><b>Список заявок с наступившим сроком оплаты:</b></legend>

<div id="listpay" style="height: 38em; overflow: auto;">
<br><br><br><br><br><br><br><br><br><br><br><p align="center"><b><font size="5">Идёт загрузка...Ожидайте...</font></b><br><br><img src="data/progressbar.gif"></p>
</div>

<div id="listpay_print" style=""></div>

</fieldset>
<br>
<input type="submit" id="Save_listpay" value="Создать платежи" style="width: 250px;">
<input type="button" id="btnClose_listpay" value="Закрыть" onclick="$('#fa_listpay').dialog('close');" style="width: 150px;"><input type="button" id="btnPrintList" onclick='var perf = $("#form_listpay").serialize();$.post("/control/listpay_load.php?load_listpay=33",perf, function(data) {$("#listpay_print").html(data);$("#listpay_print").printElement();$("#fa_listpay").dialog("close");});$("#listpay").html("<br><br><br><br><br><br><br><br><br><br><br><p align=center><b><font size=5>Идёт подготовка документа для печати...Ожидайте...</font></b><br><br><img src=\"/data/progressbar.gif\"></p>");' value="Распечатать" style="font-size: 16px;width: 180px;float:right">

</form>