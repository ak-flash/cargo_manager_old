<?php

if ($_GET['mode']=='logs') 
{
$page=(int)$_GET['view'];
if(@$_GET['user'])$user=(int)$_GET['user'];	
if(@$_GET['l_search'])$l_search=$_GET['l_search'];	


require_once('../config.php');

$m['01']="янв"; 
$m['02']="фев"; 
$m['03']="мар"; 
$m['04']="апр"; 
$m['05']="мая";
$m['06']="июн"; 
$m['07']="июл"; 
$m['08']="авг"; 
$m['09']="сен"; 
$m['10']="окт"; 
$m['11']="ноя";
$m['12']="дек";

if($user!=0)$result = mysql_query("SELECT COUNT(*) AS count FROM `logs` WHERE `user`='$user'"); else $result = mysql_query("SELECT COUNT(*) AS count FROM `logs`");

if($l_search!='')$result = mysql_query("SELECT COUNT(*) AS count FROM `logs` WHERE `log_message` LIKE '%$l_search%'");

$row = mysql_fetch_array($result,MYSQL_ASSOC);
$rows_max = $row['count'];

$show_mess = 11;

if ($page){$offset = (($show_mess * $page) - $show_mess);}
else
{
$page = 1;
$offset = 0;
}

if($user!=0) $query = "SELECT * FROM `logs` WHERE `user`='$user' ORDER BY `Id` DESC LIMIT $offset, $show_mess"; else $query = "SELECT * FROM `logs` ORDER BY `Id` DESC LIMIT $offset, $show_mess";


if($l_search!='')$query = "SELECT * FROM `logs` WHERE `log_message` LIKE '%$l_search%' ORDER BY `Id` DESC LIMIT $offset, $show_mess";

$result = mysql_query($query) or die(mysql_error());

$query_user = "SELECT `id`,`name` FROM `workers`";
$result_user = mysql_query($query_user) or die(mysql_error());

while($user = mysql_fetch_row($result_user)) {
$users[$user[0]]= $user[1];
}

echo '<fieldset style="border-collapse: collapse;border-color: black;font-size: 16px;margin:10px;"><legend><b>Действия пользователей</b></legend><div style="height: 31em;width: 101%;overflow: auto;">';

while($log= mysql_fetch_row($result)) {

$pieces = explode(" ", $users[$log[2]]);
$who=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";

echo '<img src="data/img/exclamation.png" style="float:left;margin:5px;margin-left:10px;"><div style="padding: 10px;background: #ddd;border: 1px solid #bbb;width: 96%;">&nbsp;&nbsp;<font size=4><b>'.date('d',strtotime($log[1])).' '.$m[date('m',strtotime($log[1]))].' '.date('Y',strtotime($log[1])).'</b> '.date('H:i',strtotime($log[1])).'</font> - <b><font size=4>'.$who.'</font></b>:  '.$log[3].' </div>';
}

echo '</div></fieldset>';

echo '<a onclick=\'$("#logs").load("/control/logs.php?mode=logs&view=1&user="+$("#user").val());\' style="color: #1E1E1E;text-decoration: none;cursor: pointer;margin-left:20px;margin-right:20px;">В начало</a>';

if($rows_max>$show_mess&&$page>1) $paginator.='<div style="margin:10px;display:inline;"><a onclick=\'$("#logs").load("/control/logs.php?mode=logs&view='.($page-1).'&user="+$("#user").val());\' style="text-decoration: none;color: #1E1E1E;cursor: pointer;font-size:120%;">Назад</a>&nbsp;&nbsp;<b><font size="5">'; else $paginator.='<b><font size="5">';
$paginator.=$page;
if($rows_max>$show_mess&&$page<ceil($rows_max/$show_mess)) $paginator.='</b></font>&nbsp;&nbsp;<a onclick=\'$("#logs").load("/control/logs.php?mode=logs&view='.($page+1).'&user="+$("#user").val());\' style="color: #1E1E1E;text-decoration: none;cursor: pointer;font-size:120%;">Вперед</a>'; else $paginator.='</b></font></div>';

echo $paginator;
}
?>