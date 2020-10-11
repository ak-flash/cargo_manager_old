<?php session_start();include "config.php";?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Рабочее место менеджера Транспортной компании</title>
	<?php include_once("data/header.html");?>
	<script type="text/javascript">
	$(function(){

$("#gruz_tabs").tabs({fx: {opacity:'toggle', duration:1}}); 
$('#orders_show-33').load('control/auto/inn_show.php?mode=show');

});
	</script>
</head>
<body>	
<?php include "data/menu.html";?>




	
<div id="result" style="display: none;"></div><div id="result_temp" style="display: none;"></div>
	
<div style="width: 98%;margin: 10px;POSITION: absolute;Z-INDEX: 1;">
<div id="gruz_tabs">
	
	<ul style="font-size: 18px;height: 28px;">


<li><a href="#gruz_tabs-33">Перевозчики-ИНН<div id="name_tab-33" style="display:inline;"></div></a></li>
		
	
	</ul>
		

		
	<div id="gruz_tabs-33">


		<div id="orders_show-33" style="height: 39em; overflow: auto;"></div>
		
		
		</div>
	
		
		
			
</div>
</body>
</html>					