<?php
include "../config.php";
header("Content-type: text/script;charset=utf-8");

$s_query = $_GET['q'];


$query_adress = "SELECT * FROM `adress` WHERE `postcode` LIKE '".$s_query."%' OR `city` LIKE '".$s_query."%' OR `street` LIKE '".$s_query."%' OR `dom` LIKE '".$s_query."%'";
$result_adress = mysql_query($query_adress) or die(mysql_error());



 if (mysql_num_rows($result_adress)>0)
		{ 

	         
	       
            while($adress = mysql_fetch_row($result_adress)) {
	               $data->results[]=array('id'=>$adress[0],'name'=>$adress[3].' '.$adress[4].' '.$adress[5].' '.$adress[6].' '.$adress[7].' '.$adress[8]);
	     	}        
	     	

 echo json_encode($data);
  
 } else {echo '{"results":[{"id":"0","name":"Ничего не найдено..."}]}';}
 
?>