<?php session_start();
if (isset($_SESSION['user_id']))
{
	include "theme/clients.tpl";
} else { 
header('Location: index.php');
	exit;
}
?>
