<?php 
    session_start();
    include "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Автоматизированная система регистрации и учёта транспортных перевозок</title>
<?php include_once("data/header.html");?>
<script type="text/javascript">
$(function(){
$("#notify_menu").css({"background": "#BCBCBC", "color":"#000","text-shadow": "1px 2px 2px #FFF"});
$("#notify_tabs").tabs({fx: {opacity:'toggle', duration:100}});  
document.getElementById('notify_tabs').scrollHeight = 10;
	
$('#tr_notify').load('/control/notify.php?mode=show_tr');	
$('#cl_notify').load('/control/notify.php?mode=show_cl');
});
</script>

</head>
<body>	
<?php include "data/menu.html";?>
<div id="result" style="display: none;"></div><div id="result_temp" style="display: none;"></div>


<div id="notify_tabs" style="margin:10px;background:#F8F8F8;">
<ul>
		<li><a href="#notify_tabs-1">Контроль дебиторской задолженности</a></li>
		<li><a href="#notify_tabs-2" onclick="$('#show').load('/control/notify.php?mode=show');">Контроль документооборота</a><div id="number" align="center" style="position: relative;top:-5px;left:15px;z-index:1001;float: left;background:url('data/img/notify.png');width: 30px;height: 22px;font-family: Calibri;"><b><div id="count_doc" style="font-size:18px;"></div></b></div></li>


	</ul>

<div id="notify_tabs-1">



<h3><img src="data/img/money_transportation.png" style="float:left;margin-top:-20px;">&nbsp;&nbsp;&nbsp;&nbsp;Примите меры по ликвидации данных задолженностей!</h3>

<fieldset style="border-collapse: collapse;border-color: black;font-size: 16px;margin:10px;"><legend><b>Клиенты</b></legend>
<div style="height: 11em;width: 101%; overflow: auto" id="cl_notify">Идет загрузка данных...Ожидайте.</div>
</fieldset>


<fieldset style="border-collapse: collapse;border-color: black;font-size: 16px;margin:10px;margin-top:30px;"><legend><b>Перевозчики</b></legend>
<div style="height: 17em;width: 101%; overflow: auto" id="tr_notify">Идет загрузка данных...Ожидайте.</div>
</fieldset>

</div>

<div id="notify_tabs-2">


<fieldset style="width: 97%;border-collapse: collapse;border-color: black;font-size: 16px;margin:10px;margin-top:30px;"><legend><b>Неоплаченные Клиентом заявки</b></legend>
<div style="height: 35em;width: 101%; overflow: auto" id="show">Идет загрузка данных...Ожидайте.</div>
</fieldset>

</div>


</div>
</body>
</html>