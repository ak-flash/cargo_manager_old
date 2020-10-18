<?php
// Подключение и выбор БД
include "config.php";


$n=0;



$query_pays = "SELECT * FROM `pays` ORDER BY `id` ASC";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {

$arr1=array($pay[1],$pay[2],$pay[3],$pay[4],$pay[5],$pay[6],$pay[7],$pay[8],$pay[9],$pay[10],$pay[11],$pay[12],$pay[13],$pay[14],$pay[15],$pay[16],$pay[17]);
$id=$pay[0];

$result = array_diff($arr1, $arr2);
if(empty($result)) echo $last_id.'='.$id.'<br>';

$arr2=array($pay[1],$pay[2],$pay[3],$pay[4],$pay[5],$pay[6],$pay[7],$pay[8],$pay[9],$pay[10],$pay[11],$pay[12],$pay[13],$pay[14],$pay[15],$pay[16],$pay[17]);
$last_id=$pay[0];




$n++;
}

	
	
echo "Обработано - ".$n."<br>";	







?>