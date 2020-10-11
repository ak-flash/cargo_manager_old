<?php  
include "../config.php";
session_start();

if(@$_GET['mode']=="PostMessage"){

$author=(int)$_POST['author'];
$text=addslashes($_POST['chatText']);

$query = "INSERT INTO `webchat` (`author`,`text`) VALUES ('$author','$text')";
$result = mysql_query($query) or die(mysql_error());

}

if(@$_GET['mode']=="count"){
$result = mysql_query("SELECT COUNT(*) AS count FROM `webchat`");	
$row = mysql_fetch_array($result,MYSQL_ASSOC);
if($_SESSION["messages"]<$row['count']){$count = $row['count']-$_SESSION["messages"];
echo "+".$count;} else echo '0';

}

if(@$_GET['mode']=="GetMessage"){

$q[]="";
$q[]="Января"; 
$q[]="февраля"; 
$q[]="Марта"; 
$q[]="Апреля"; 
$q[]="мая";
$q[]="июня"; 
$q[]="июля"; 
$q[]="августа"; 
$q[]="сентября"; 
$q[]="октября"; 
$q[]="ноября";
$q[]="декабря";

$result = mysql_query("SELECT COUNT(*) AS count FROM `webchat`");	
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$_SESSION["messages"]= $row['count'];

$query = "UPDATE `workers` SET `chat_mess`='".(int)$_SESSION["messages"]."' WHERE `Id`='".(int)$_SESSION["user_id"]."'";
$result = mysql_query($query) or die(mysql_error());


$query = "SELECT * FROM `webchat` ORDER BY `ts` DESC LIMIT 13";
$result = mysql_query($query) or die(mysql_error());
while($messages = mysql_fetch_row($result)) {


$query_user = "SELECT `id`,`name` FROM `workers` WHERE `delete`='0'";
$result_user = mysql_query($query_user) or die(mysql_error());
while($user= mysql_fetch_row($result_user)) {
if($user[0]!=1){$pieces = explode(" ", $user[1]);
$print_user=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";

$users[$user[0]]="<font color='green'>".$print_user."</font>";}
else $users[1]="<font color='red'>Администратор</font>";
}

if($data!=date('dm',strtotime($messages[3]))) {
$chislo=date('d',strtotime($messages[3]));
$m=date('m',strtotime($messages[3]));
if ($m=="01") $m=1; 
if ($m=="02") $m=2;
if ($m=="03") $m=3;
if ($m=="04") $m=4; 
if ($m=="05") $m=5;
if ($m=="06") $m=6;
if ($m=="07") $m=7;
if ($m=="08") $m=8; 
if ($m=="09") $m=9;
$mesyac = $q[$m];
if($chislo<10)$chislo=substr($chislo,1);
echo "&nbsp;&nbsp;&nbsp;&nbsp;<b>".$chislo." ".$mesyac." ".date('Y',strtotime($messages[3]))." г.</b><hr>";}
echo "<div style='margin-bottom:-5px;padding:5px;width:97%;border-radius: 3px;background: #ddd;border: 1px solid #bbb;'>".date('H:i',strtotime($messages[3]))." - <b>".$users[$messages[1]].":</b> ".$messages[2]."</div><br>";
$data=date('dm',strtotime($messages[3]));

}

}

?>