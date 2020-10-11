<?php  
include "../config.php";
ini_set("max_execution_time","0");
set_time_limit (0); 
ignore_user_abort (true);

function convert($temp) {
return iconv("windows-1251", "UTF-8", $temp);
}


$search_receiver=addslashes($_POST['receiver']);



if($_FILES["filename"]["size"] > 1024*3*1024)
   {
     echo ("Размер файла превышает три мегабайта");
     exit;
   }
   // Проверяем загружен ли файл
   if(is_uploaded_file($_FILES["filename"]["tmp_name"]))
   {
     // Если файл загружен успешно, перемещаем его
     // из временной директории в конечную
     move_uploaded_file($_FILES["filename"]["tmp_name"], $_FILES["filename"]["name"]);
   } 
if (file_exists($_FILES["filename"]["name"])){
$data_array = file($_FILES["filename"]["name"]);



$i=1;
foreach($data_array as $l)
{
$l=convert($l);

$str = explode('Номер=',$l);
$res = $str[1];
$str = explode('Дата=',$res);
if($str[0]!=''){$num=$str[0];}

$str = explode('Дата=',$l);
$res = $str[1];
$str = explode('Сумма=',$res);
if($str[0]!=''){$data=$str[0];}

$str = explode('ПлательщикСчет=',$l);
$res = $str[1];
$str = explode('ДатаСписано=',$res);
if($str[0]!=''){$payer_rs=trim($str[0]);}

//$str = explode('Плательщик1=',$l);
//$res = $str[1];
//$str = explode('ПлательщикБИК=',$res);
//if($str[0]!=''){$payer=$str[0];}

$str = explode('Сумма=',$l);
$res = $str[1];
$str = explode('ПлательщикСчет=',$res);
if($str[0]!=''){$cash=$str[0];}

$str = explode('Получатель1=',$l);
$res = $str[1];
$str = explode('ПолучательБИК=',$res);
if($str[0]!=''){$reciver=$str[0];}

$str = explode('НазначениеПлатежа=',$l);
$res = $str[1];
$str = explode('КонецДокумента',$res);
if($str[0]!=''){$ends=$str[0];}

if(strpos($l, $search_receiver)){
//echo $data."|".$payer."|".$payer_rs."|".$cash."|".$reciver.$ends;

$query_bill = "SELECT `company`,`Id` FROM `bill` WHERE `c_bill`='".mysql_escape_string($payer_rs)."'";
$result_bill = mysql_query($query_bill) or die(mysql_error());
if(mysql_num_rows($result_bill)!=0)
		{
		$bill_info = mysql_fetch_row($result_bill);
		
		$query_с = "SELECT `name` FROM `company` WHERE `delete`='0' AND `id`='".mysql_escape_string($bill_info[0])."'";
$result_с = mysql_query($query_с) or die(mysql_error());
$с_info = mysql_fetch_row($result_с);
		
		
		}
		
		
		
echo '<tr><td align=center bgcolor=#F2F5F7><input type="checkbox" name="list_pay_id[]" id="list_pay_id" value="'.$num.'|'.$data.'|'.$bill_info[1].'|'.$cash.'|'.addslashes($ends).'|'.$bill_info[0].'"></td><td align=center><font size=4>'.$num.'</font></td><td align=center bgcolor=#F2F5F7><b>'.$data.'</b></td><td align=center><b><font size=4>'.$cash.'</b></font></td><td bgcolor=#F9F9F9 align=center>'.$с_info[0].' ('.$payer_rs.')</td><td align=center>'.$reciver.'</td><td  align=center>'.$ends.'</td></tr>';


}

$i++;
}
	
} else {die("Ошибка загрузки файла!");} 


?>