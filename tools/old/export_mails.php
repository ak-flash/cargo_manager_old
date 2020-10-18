<?php

include "../config.php";	

$query_gorod = "SELECT `Id` FROM `adress` WHERE `obl` LIKE 'Волгоградская'";
$result_gorod = mysql_query($query_gorod) or die(mysql_error());
while($row = mysql_fetch_array($result_gorod)) {
$id_mass[]=$row['Id'];
}
	


$query = "SELECT `name`,`tr_mail` FROM `transporters` WHERE `tr_adr_f` IN (".implode(',',$id_mass).") AND `tr_mail`!=''";
$result = mysql_query($query) or die(mysql_error()); 
while($row = mysql_fetch_array($result)) {
echo $row['name'].';'.$row['tr_mail'].'<br>';
}	
	
	
	
	?>