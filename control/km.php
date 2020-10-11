<?php
if(@$_GET['mode']=='km'){
ini_set("max_execution_time","0");
set_time_limit (0); 
ignore_user_abort (true);


$ch=curl_init();

curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT
6.0; en-US; rv:1.8.1.11) Gecko/20071127 Firefox/2.0.0.11");
curl_setopt($ch, CURLOPT_REFERER, 'http://www.sit-trans.com/export_distance.php');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

if($_GET['start_city']=='Волжский')$_GET['start_city']='Волжский(Волгоград)';
if($_GET['end_city']=='Волжский')$_GET['end_city']='Волжский(Волгоград)';

$start_city=urlencode(mb_convert_encoding($_GET['start_city'],"cp1251", "UTF-8"));
$end_city=urlencode(mb_convert_encoding($_GET['end_city'],"cp1251", "UTF-8"));

curl_setopt($ch, CURLOPT_URL, "http://www.sit-trans.com/export_distance.php?start_city=".$start_city."&end_city=".$end_city."&price_type=time&inter_city=&exclude_city=&speed_road1=110&speed_road2=90&speed_road3=70&speed_road4=60&fuel_charge=30&fuel_price=4&action=distance&submit=%D0%E0%F1c%F7%E8%F2%E0%F2%FC&action=distance");
$result = curl_exec($ch);
curl_close($ch);

$str = explode(mb_convert_encoding("Длина Пути: <font size=4><b>","cp1251", "UTF-8"),$result);
$res = explode(mb_convert_encoding(" км;</b></font>","cp1251", "UTF-8"),$str[1]);


if($res[0]!="") echo mb_convert_encoding($res[0],"UTF-8", "cp1251").' км'; else echo '<font color="red">маршрут не найден</font>';
}


?>