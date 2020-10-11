<?php session_start();
if (isset($_SESSION['user_id']))
{
	include "theme/international.tpl";
	
} else { 
header('Location: index.php');
	exit;
}
?>
