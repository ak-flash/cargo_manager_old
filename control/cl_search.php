<?php
include "../config.php";
session_start();
 header("Content-type: text/script;charset=utf-8");
$s_query = $_GET['q'];
if (!isset($data)) $data = new stdClass();
  

// Managers can see only their clients - now disabled
  //if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4){$query = "SELECT `id`,`name`,`nds`,`pref`,`cl_time`,`cl_point`,`cl_cont`,`cl_manager` FROM `clients` WHERE `name` LIKE '%".mysql_escape_string($s_query)."%'";} else {$query = "SELECT `id`,`name`,`nds`,`pref`,`cl_time`,`cl_point`,`cl_cont`,`cl_manager` FROM `clients` WHERE `cl_manager`='".$_SESSION["user_id"]."' AND `name` LIKE '%".mysql_escape_string($s_query)."%'";}

$query = "SELECT `id`,`name`,`nds`,`pref`,`cl_time`,`cl_point`,`cl_cont`,`cl_manager` FROM `clients` WHERE `name` LIKE '%".mysql_escape_string($s_query)."%'";

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
	       
            while($row = mysql_fetch_row($result)) {
				
// Managers can see only their clients - now disabled				
//if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4)
	$n=$row[1]." (".$users[$row[7]].")";
//else $n=$row[1];          	
            	
            	
            	switch ($row[3]) {
case '1': $pref_cl='&nbsp;&nbsp;&nbsp;ООО';break;
case '2': $pref_cl='&nbsp;&nbsp;&nbsp;ОАО';break;
case '3': $pref_cl='&nbsp;&nbsp;&nbsp;ИП&nbsp;';break;
case '4': $pref_cl='&nbsp;&nbsp;&nbsp;ЗАО';break;
case '5': $pref_cl='&nbsp;&nbsp;&nbsp;';break;
case '6': $pref_cl='&nbsp;&nbsp;&nbsp;Физ.Л.';break;
case '7': $pref_cl='&nbsp;&nbsp;&nbsp;АО';break;
}
	               if(@$_GET['mmm']=='cl_add') {
                       $data->results[]=array('id'=>$row[0],'name'=>$row[1]);
	               } else {
	                   $data->results[]=array('id'=>$row[0].'|'.$row[2].'|'.$row[3].'|'.$pref_cl.'|'.$row[4].'|'.$row[5].'|'.$row[6],'name'=>$n);
	               }
	     	        }
	         
  echo json_encode($data);
} else {
     if(@$_GET['mmm']=='cl_add') echo '{"results":[{"id":"0","name":"Клиент отсутствует в базе..."}]}'; else echo '{"results":[{"id":"0","name":"Ничего не найдено..."}]}';}
?>