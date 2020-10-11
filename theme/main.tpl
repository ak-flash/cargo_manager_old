<?php 
    include "config.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<title>Автоматизированная система регистрации и учёта транспортных перевозок</title>
<?php include_once("data/header.html");?>
    

<script type="text/javascript">



$(function(){

<?php 
//if($_SESSION["group"]!=3)echo '$("#info_text").load("control/load_info_text.php?mode=notify", function(data) {if(data!="") $("#information").fadeIn(1000);});';
?>

$('.phone').tabSlideOut({							//Класс панели
		tabHandle: '.handle_p',						//Класс кнопки
		pathToTabImage: '/data/button_phone.gif',				//Путь к изображению кнопки
		imageHeight: '122px',						//Высота кнопки
		imageWidth: '30px',						//Ширина кнопки
		tabLocation: 'right',						//Расположение панели top - выдвигается сверху, right - выдвигается справа, bottom - выдвигается снизу, left - выдвигается слева
		speed: 300,								//Скорость анимации
		action: 'click',								//Метод показа click - выдвигается по клику на кнопку, hover - выдвигается при наведении курсора
		leftPos: '10px',							//Отступ сверху
		fixedPosition: false						//Позиционирование блока false - position: absolute, true - position: fixed
	});



$('#btnStartDay').button();
$('#btnReport_new').button();
$('#btnRynga').button();
$('#btnBirthdays').button();
$('#btnCall').button();
$("#icq_status").load('/control/status_icq.php');
$("#count_day").load('/control/admin.php?mode=day&count_day=1',function(data) {
var arr = data.split(/[|]/);
$("#count_day").html('&nbsp;<b>'+arr[0]+'</b>&nbsp;');
$("#all_day").html('&nbsp;<b>'+arr[1]+'</b>&nbsp;');
});

$("#main_menu").css({"background": "#BCBCBC", "color":"#000","text-shadow": "1px 2px 2px #FFF"});


});



function Komiss(){
var cl_cash=parseInt(document.getElementById('cl_cash').value, 10);
var tr_cash=parseInt(document.getElementById('tr_cash').value, 10);
var cl_nds=parseInt(document.getElementById('cl_nds').value, 10);
var tr_nds=parseInt(document.getElementById('tr_nds').value, 10);


<?php 

$query_motive = "SELECT * FROM `settings`";
$result_motive = mysql_query($query_motive) or die(mysql_error());
$motive = mysql_fetch_array($result_motive);
?>


var cl_cash_all=cl_cash;
var tr_cash_all=tr_cash;
cash=0;
if(cl_nds==0 && tr_nds==0){var data=cl_cash_all-tr_cash_all;}
if(cl_nds==1 && tr_nds==1){var data=cl_cash_all-tr_cash_all;}
if(cl_nds==1 && tr_nds==0){var data=((cl_cash_all-cl_cash_all/<?php echo $motive['motive_2'];?>)-tr_cash_all).toFixed(2);}
if(cl_nds==1 && tr_nds==2){var data=(cl_cash_all-cl_cash_all*<?php echo $motive['motive_3'];?>)-tr_cash_all;}
if(cl_nds==0 && tr_nds==1){var data=(cl_cash_all-(tr_cash_all-tr_cash_all/<?php echo $motive['motive_4'];?>)).toFixed(2);}
if(cl_nds==0 && tr_nds==2){var data=(cl_cash_all-cl_cash_all*<?php echo $motive['motive_6'];?>)-tr_cash_all;}
if(cl_nds==2 && tr_nds==1){var data=cl_cash_all-tr_cash_all+tr_cash_all*<?php echo $motive['motive_7'];?>;}
if(cl_nds==2 && tr_nds==0){var data=cl_cash_all-tr_cash_all+tr_cash_all*<?php echo $motive['motive_8'];?>;}
if(cl_nds==2 && tr_nds==2){var data=cl_cash_all-tr_cash_all;}

var cash=data*100/cl_cash;
if(cash.toPrecision(2)>=20){$quality='отличная';$cash_ok=2;}
if(cash.toPrecision(2)<20&&cash.toPrecision(2)>=10){$quality='хорошая';$cash_ok=2;}
if(cash.toPrecision(2)<10&&cash.toPrecision(2)>=6){$quality='удовлетворительная';$cash_ok=2;}
if(cash.toPrecision(2)<6&&cash.toPrecision(2)>=0){$quality='заявка сомнительной рентабельности';$cash_ok=1;}
if(cash.toPrecision(2)<0){$quality='Внимание! Доход отсутствует!';$cash_ok=0;}

$('#quality').html(cash.toPrecision(3)+'% - '+$quality);
$('#komissia').html(data);
}

</script>



</head>
<body style="overflow:hidden;">	
<?php include "data/menu.html";?>
<div id="fa_rynga" style="background:#F8F8F8;"></div>
<table><tr><td valign="top">
<div  style="float:left;margin-left:30px;margin-right:30px;">
<img src="data/img/cargo1.png">
<br>

<!-- <br><a href="webmail/mail/login-to-account.php?mode=main" target="_new" class="button" style="width: 110px;font-size:23px;height:33px;">Почта</a> -->
<br><a href="https://ati.su/" target="_new" class="button" style="width: 110px;font-size:25px;height:33px;">АТИ</a>


</div>



</td><td valign="top">
<table style="margin-top: -20px;"><tr>
<td>

<div class="info">


<?php 
$q[]="";
$q[]="Января"; 
$q[]="Февраля"; 
$q[]="Марта"; 
$q[]="Апреля"; 
$q[]="Мая";
$q[]="Июня"; 
$q[]="Июля"; 
$q[]="Августа"; 
$q[]="Сентября"; 
$q[]="Октября"; 
$q[]="Ноября";
$q[]="Декабря";

$e[0]="Воскресенье"; 
$e[1]="Понедельник"; 
$e[2]="Вторник"; 
$e[3]="Среда"; 
$e[4]="Четверг";
$e[5]="Пятница"; 
$e[6]="Суббота";

$m=date('m');
if ($m=="01") $m=1; 
if ($m=="02") $m=2;
if ($m=="03") $m=3;
if ($m=="04") $m=4; 
if ($m=="05") $m=5;
if ($m=="06") $m=6;
if ($m=="07") $m=7;
if ($m=="08") $m=8; 
if ($m=="09") $m=9;

$we=date('w');

$chislo=date('d');

$den_nedeli = $e[$we];

$mesyac = $q[$m];



echo '<div style="margin-right:50px;"><font size="5"><b>Сегодня:</b> <font size="6">'.$den_nedeli.' '.$chislo.' '.$mesyac.' '.date("Y").' г.</font><br>

<!---- <div id="count_day" style="float:left;"></div>
<div style="float:left;">рабочий день из </div><div id="all_day" style="float:left;"></div>в этом месяце </font></div>---!>

</td><td>';
?>

</div>
</td></tr>

<tr><td>
<div id="result" style="display: none;"></div><div id="result_temp" style="display: none;"></div>

<table style="margin:2px;width:650px;font-size:18px;background: #eeeeee;
	" cellspacing="10"><tr><td colspan="3"><div style="padding:10px;background: #808080;color:#FFFFFF;font-size:26px;"><b>Рассчет комиссии</b></div></td></tr>
	
	<tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Ставка</b></td><td></td></tr>           
               
<tr><td align="right" width="200" bgcolor="#FFFFFF">от Клиента&nbsp;&nbsp;&nbsp;&nbsp;</td><td width="170"><input name="cl_cash" id="cl_cash" style="width: 80px;" placeholder="0" class="input" onchange="Komiss()"> руб.</td><td align="left"><select name="cl_nds" id="cl_nds" class="select">
  <option value="0" onclick="Komiss();">без НДС</option>
  <option value="1" onclick="Komiss();">с НДС</option>
  <option value="2" onclick="Komiss();">НАЛ</option></select></td></tr> 
<tr><td align="right" bgcolor="#FFFFFF">от Перевозчика&nbsp;&nbsp;&nbsp;&nbsp;</td><td><input name="tr_cash" id="tr_cash" style="width: 80px;" placeholder="0" class="input" onchange="Komiss()"> руб.</td><td><select name="tr_nds" id="tr_nds" class="select">
  <option value="0" onclick="Komiss();">без НДС</option>
  <option value="1" onclick="Komiss();">с НДС</option>
  <option value="2" onclick="Komiss();">НАЛ</option></select></td></tr> 
<tr><td colspan="3"><fieldset style="width:88%;"><legend><b>Итого:</b></legend>Комиссия: <div id="komissia" style="display:inline;font-size:20px;font-weight: bold; ">0</div> рублей<br>
Уровень рентабельности: <div id="quality" style="display:inline;font-size:18px;font-weight: bold; ">0 %</div>
</fieldset></td></tr> 
</table>
<br><br>

<div  id="information" style="background:#606060;display: none;border: 2px solid #A0A0A0;border-radius: 5px;padding: 5px;text-align:center;margin-top:-15px;width: 650px;" >



<div style="padding: 5px;color:#FFFFFF;text-align:left;" id="info_text"></div>
</div>


 

</td>

<td valign="top">


<input type="button" id="btnBirthdays" onclick='$("#fa_rynga").load("birthdays.php");$("#fa_rynga").dialog({ title: "Дни рождения сотрудников" },{width: "auto",position:[($(window).width()/3),100],modal: true,resizable: false});' value="Дни рождения" style="margin-left:20px;width: 215px;height:45px;">


<?php

$query = "SELECT `date_birth` FROM `workers` WHERE `delete`='0'";
$result = mysql_query($query) or die(mysql_error());
$birth=0;
while($row = mysql_fetch_array($result)) {
$str_temp=explode('-',$row['date_birth']);
$datas=date("Y").'-'.trim($str_temp[1]).'-'.trim($str_temp[2]);
if(strtotime('-7 day', strtotime($datas))<=strtotime('now')&&strtotime($datas)>strtotime('now'))$birth++;
}

if($birth>0) echo '<span class="amNumber" style="top:-20px;left:-15px;"><div id="count_pay" style="display:inline;">+'.$birth.'</div><div class="amAngle"></div></span>';	


?>



<br><br><input type="button" id="btn-slide" onclick='$("#panel").load("control/info.php");$("#panel").slideToggle("fast");$(this).toggleClass("active");' value="Информация" style="font-size: 15px;margin-left:20px;width: 215px;">
<br><br>
<a class="button" id="btnWays_search" href="#" onclick="window.location.href='ways_cl.php?group_id=0';" style="font-size: 15px;margin-left:20px;width: 215px;">Направления</a>

<?php 


$url = 'http://www.cbr.ru/scripts/XML_daily.asp';
$xml = simplexml_load_file($url);

echo '<fieldset style="width:200px;"><legend>Курс от <b>'.$xml->attributes()->Date.'</b></legend>';

// EUR
$EUR=str_replace(",", ".", $xml->Valute[10]->Value);
echo '<b>'.$xml->Valute[10]->CharCode.'</b>: 1 = <font size="5">'.round($EUR, 2).'</font> руб.<br><br>';


// USD
$USD=str_replace(",", ".", $xml->Valute[11]->Value);
echo '<b>'.$xml->Valute[11]->CharCode.'</b>: 1 = <font size="5">'.round($USD, 2).'</font> руб.<br></fieldset>';

 ?>



</td>

</tr></table> 


</td>



</tr></table> 





</body>
</html>