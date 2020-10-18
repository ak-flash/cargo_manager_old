<?php
include "../config.php";
 header("Content-type: text/script;charset=utf-8");
$s_query = $_GET['q'];
if (!isset($data)) $data = new stdClass();
  
  //подключаемся к базе
$query = "SELECT `id`,`name`,`nds`,`pref`,`tr_time`,`tr_point`,`tr_cont`,`tr_manager` FROM `transporters` WHERE `name` LIKE '%".$s_query."%'";
$result = mysql_query($query) or die(mysql_error());

  
 if (mysql_num_rows($result)>0)
		{ 
	         
	       
            while($row = mysql_fetch_row($result)) {

switch ($row[3]) {
case '1': $pref_tr='&nbsp;&nbsp;&nbsp;ООО&nbsp;';break;
case '2': $pref_tr='&nbsp;&nbsp;&nbsp;ОАО&nbsp;';break;
case '3': $pref_tr='&nbsp;&nbsp;&nbsp;ИП&nbsp;&nbsp;';break;
case '4': $pref_tr='&nbsp;&nbsp;&nbsp;ЗАО';break;
case '5': $pref_tr='&nbsp;&nbsp;&nbsp;';break;
case '6': $pref_tr='&nbsp;&nbsp;&nbsp;Физ.Л.';break;
case '7': $pref_tr='&nbsp;&nbsp;&nbsp;АО';break;
}

$query_user = "SELECT `id`,`name` FROM `workers` WHERE `id`='$row[7]' AND `delete`='0'";
$result_user = mysql_query($query_user) or die(mysql_error());
$users= mysql_fetch_row($result_user);
$pieces = explode(" ", $users[1]);
$print_user=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
            	
if(@$_GET['mmm']=='tr_add') $data->results[]=array('id'=>$row[1],'name'=>$row[1]); else $data->results[]=array('id'=>$row[0].'|'.$row[2].'|'.$row[3].'|'.$pref_tr.'|'.$row[4].'|'.$row[5].'|'.$row[6].'|'.$row[7].'|'.$print_user,'name'=>$row[1]);
	     	        }
	         
  echo json_encode($data);
} else {if(@$_GET['mmm']=='tr_add') echo '{"results":[{"id":"0","name":"Перевозчик отсутствует в базе..."}]}'; else echo '{"results":[{"id":"0","name":"Ничего не найдено..."}]}';}
?>