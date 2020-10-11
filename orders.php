<?php session_start();
if (isset($_SESSION['user_id']))
{
	include "theme/orders.tpl";
	
} else { 
header('Location: index.php');
	exit;
}
?>
