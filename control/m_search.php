<?php
include "../config.php";
 
  //÷èòàåì ïàðàìåòðû
  $s_query = $_GET['query'];
  
  //ïîäêëþ÷àåìñÿ ê áàçå
$query = "SELECT `name` FROM `workers` WHERE `name` LIKE '".$s_query."%'";
$result = mysql_query($query) or die(mysql_error());

  
$data->query=$s_query;
	         
	       
            while($row = mysql_fetch_row($result)) {
	               $data->suggestions[]=$row[0];
	            	        }
	         
  echo json_encode($data);

?>