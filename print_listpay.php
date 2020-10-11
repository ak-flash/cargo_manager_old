<script type="text/javascript">

$('#listpay').load('/control/printpay_load.php?mode=print');


// - - кнопка добавление назначений - сохранение - - >

  

 
 
$('#Print_listpay').button();
$('#btnClose_listpay').button();

</script>
<form method="post" id="form_listpay">

<div id="result"></div>

<fieldset><legend><b>Список перевозчиков, ожидающих оплаты:</b></legend>

<div id="listpay" style="height: 38em; overflow: auto">

</div>

</fieldset>
<br>
<input type="button" id="Print_listpay" value="Распечатать" onclick='window.location.href="/control/print_listpay.php?mode=print&app_id="+$("#pays_appoints").val();' style="width: 250px;">
<input type="button" id="btnClose_listpay" value="Закрыть" onclick="$('#fa_listpay').dialog('close');" style="width: 150px;">

</form>