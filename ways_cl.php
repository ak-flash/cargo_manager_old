<?php session_start();
if (isset($_SESSION['user_id']))
{
	include "theme/ways_cl.tpl";
} else { 
header('Location: index.php');
	exit;
}
?>
