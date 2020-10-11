<?php session_start();
if (isset($_SESSION['user_id']))
{
	include "theme/notify.tpl";
} else { 
header('Location: index.php?mode=logout');
	exit;
}
?>
