<?php session_start();
if (isset($_SESSION['user_id']))
{
if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4){include "theme/cash.tpl";} else {echo "<b><h1>&nbsp;&nbsp;&nbsp;&nbsp;Доступ запрещен</h1></b>";}
} else { 
header('Location: index.php');
	exit;
}
?>