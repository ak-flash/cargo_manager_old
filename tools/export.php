<?php

include "config.php";	

$query = "SELECT `id` FROM `orders` WHERE `client` IN (27,511)";
$result = mysql_query($query) or die(mysql_error()); 
while($row_ord = mysql_fetch_array($result)) {
$id_mass[]=$row_ord['id'];
}



$query = "SELECT `id`,`date`,`order`,`cash` FROM `pays` WHERE `way`='1' AND `delete`='0' AND `appoint`='1' AND `order` IN (".implode(',',$id_mass).") AND DATE(`date`) BETWEEN '2012-01-01' AND '2012-12-31' ORDER BY `date`,`order` ASC";

$result = mysql_query($query) or die(mysql_error()); 
while($row = mysql_fetch_array($result)) {

$date_pay=date("d/m/Y",strtotime($row['date']));	
	
echo $row['id'].';'.$date_pay.';'.$row['order'].';'.((int)$row['cash']/100).';'.'<br>';

}	
	
	
	
	?>