<?php  
include "../../config.php";
session_start();



if(@$_GET['mode']=="count"){
$result = mysql_query("SELECT COUNT(*) AS count FROM `cl_gruz` WHERE `status`='1'");	
$row = mysql_fetch_array($result,MYSQL_ASSOC);

if($_SESSION["gruz_count"]<$row['count']){$count = $row['count']-$_SESSION["gruz_count"];echo "+".$count;} else echo '0';

}

if(@$_GET['mode']=="status"){
$result = mysql_query("SELECT COUNT(*) AS count FROM `cl_gruz` WHERE `status`='1'");	
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$_SESSION["gruz_count"]=$row['count'];	
}
?>