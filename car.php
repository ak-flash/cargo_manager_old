<?php


include "config.php";
$query_tr = "SELECT `id`,`name`,`pref` FROM `transporters`";
$result_tr = mysql_query($query_tr) or die(mysql_error());
while($tr = mysql_fetch_row($result_tr)) {
$transporters[$tr[0]]= $tr[1];
$tr_pref_array[$tr[0]]= $tr[2];
}


$query = "SELECT `transporter`,`car_name`,`car_number`,`car_extra_name`,`car_extra_number`,`car_driver_name`,`car_driver_doc`,`car_m`,`car_v`,`car_kuzov`,`car_owner_doc` FROM `tr_autopark` WHERE `delete`='0' ORDER BY `Id` DESC";
$result = mysql_query($query) or die(mysql_error()); 
$i=1;
echo "<table>";
while($row = mysql_fetch_array($result)) {
$car_doc = explode('|',$row['car_driver_doc']); 

$pref_tr='';
switch ($tr_pref_array[$row['transporter']]) {
case '1': $pref_tr='ООО';break;
case '2': $pref_tr='ОАО';break;
case '3': $pref_tr='ИП';break;
case '4': $pref_tr='ЗАО';break;}

echo "<tr><td>".$transporters[$row['transporter']].', '.$pref_tr."</td><td>".$row['car_name']."</td><td>".$row['car_number']."</td><td>".$row['car_extra_number']."</td><td>".$row['car_kuzov']."</td><td>".$row['car_m']."/".$row['car_v']."</td><td>".$row['car_driver_name']."</td><td>".$row['car_owner_doc']."</td><td>&nbsp;</td><td>".$car_doc[0]." ".$car_doc[1]." ".$car_doc[2]."</td></tr>";


	
	
$i++;
}
echo "</table>";
?>