<?php
include "../config.php";
session_start();
 header("Content-type: text/script;charset=utf-8");
$s_query = $_GET['q'];

  
  //ïîäêëþ÷àåìñÿ ê áàçå
if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4){$query = "(SELECT `id`,`name`,`cl_manager` FROM `clients` WHERE `name` LIKE '%".mysql_escape_string($s_query)."%') UNION ALL (SELECT `group_id`,`group_name`,`group_cl` FROM `cl_group` WHERE `group_name` LIKE '%".mysql_escape_string($s_query)."%')";} else {$query = "SELECT `id`,`name`,`cl_manager` FROM `clients` WHERE `cl_manager`='".$_SESSION["user_id"]."' AND `name` LIKE '%".mysql_escape_string($s_query)."%'";}


$result = mysql_query($query) or die(mysql_error());

  
 if (mysql_num_rows($result)>0)
		{ 
$query_user = "SELECT `id`,`name` FROM `workers`";
$result_user = mysql_query($query_user) or die(mysql_error());
while($user = mysql_fetch_row($result_user)) {

$pieces = explode(" ", $user[1]);
$name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";

$users[$user[0]]= $name;
}	         
	       
            while($row = mysql_fetch_array($result)) {
//if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4)$n=$row[1]." (".$users[$row[7]].")"; else $n=$row[1];          	
            	
            	
            
	               $data->results[]=array('id'=>$row['id'].'|'.$row['name'].'|'.$row[5].'|'.$row[6],'name'=>$n);
	     	        }
	         
  echo json_encode($data);
} else {echo '{"results":[{"id":"0","name":"Íè÷åãî íå íàéäåíî..."}]}';}
?>