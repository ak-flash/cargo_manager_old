<?php
include ('config.php');

session_start();

if (@$_GET['mode']=="logout") {
	session_unset();
}


if (isset($_SESSION['user_id']))
{
	// юзер уже залогинен, перекидываем его отсюда на закрытую страницу
	
	if($_SESSION["group"]==3) header('Location: orders'); else header('Location: main');
	exit;

} else {


if (!empty($_POST))
{	
	$username = (isset($_POST['username'])) ? mysql_real_escape_string($_POST['username']) : '';
	$password = md5($_POST['password']);
	$user_ip =(isset($_POST['user_ip'])) ? mysql_real_escape_string($_POST['user_ip']) : '';
	
	if($username == "0") $username = "admin";

		$query = "SELECT `id`,`name`,`ip`,`group`
					FROM `workers`
					WHERE `login`='{$username}' AND `password`='{$password}' AND `group`<>'5'
					LIMIT 1";
					
		$sql = mysql_query($query) or die(mysql_error());

		// если такой пользователь нашелся
		if (mysql_num_rows($sql) == 1)
		{	

			$row = mysql_fetch_assoc($sql);
			// то мы ставим об этом метку в сессии (допустим мы будем ставить ID пользователя)
			$query = "SELECT * FROM `workers` WHERE Id=".$row['id'];
			$result = mysql_query($query) or die(mysql_error());
			$line = mysql_fetch_array($result);

////
//if($user_ip!=$line['ip']&&$row['id']!=1&&$row['id']!=11&&$row['id']!=9&&$row['id']!=13&&$row['id']!=23&&$row['group']!=1&&$row['group']!=2&&$user_ip!="192.168.0.33"){
//$query_logs = "INSERT INTO `logs` (`user`,`log_message`) VALUES ('".$row['id']."','Заход с чужого компьютера - ".$user_ip."')";
//$result_logs = mysql_query($query_logs) or die(mysql_error());


//valign="middle">&nbsp;&nbsp;&nbsp;<font size="4" color="red">Ошибка:</font> <font size="4">Вы попытались зайти в систему с чужого компьютера, уведомление директору отправлено!</font></td></tr> </table>';
//} else 
//{
			$_SESSION['user_id'] = $row['id'];
			$_SESSION['own_transporter_ids'] = $own_transporter_ids;
			$_SESSION["user"]=$line['name'];
			$_SESSION["group"]=$line['group'];
			$_SESSION["email"]=$line['email'];
			$_SESSION["mail_pass"]=$line['mail_pass'];
			$_SESSION["voip"]=$line['voip'];
			$_SESSION["messages"]=$line['chat_mess'];
			$_SESSION["user_ip"]=$user_ip;
			$_SESSION["real_ip"]=$line['ip'];
			$_SESSION["gruz_count"]=0;

			switch ($line['group']) {
				case '1': $_SESSION["group_name"]='Администратор';break;
				case '2': $_SESSION["group_name"]='Директор';break;
				case '3': $_SESSION["group_name"]='Менеджер';break;
				case '4': $_SESSION["group_name"]='Бухгалтер';break;
			}
			
			
		$query = "SELECT `id`,`name` 
					FROM `company` 
					WHERE `active`='1'";
		
		$result = mysql_query($query) or die(mysql_error());
			
			while($company= mysql_fetch_row($result)) {
				if($company[0]!=1) {
					$_SESSION["company"][$company[0]] = $company[1];
				}
			}

			

			
			if($_SESSION["group"]==3) header('Location: orders'); else header('Location: main');
			
			exit;

		}
			else
		{
			$err='<table align="center"><tr><td valign="middle"><img src="data/img/exclamation.png"></td><td height="100" valign="middle">&nbsp;&nbsp;&nbsp;<font size="5" color="red">Ошибка:</font> <font size="4">Неправильный пароль!</font></td></tr> </table>';
		}
	

}




print '<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<title>Информационная система логистической обработки перевозок</title>
<style type="text/css">
.frame{

width:285px;
border-radius: 10px;
background:url(data/images/login.png);

}
.b_login{
width:100px;
height:35px;
font-size:1.3em;
color: #FFF;
background:#5E820D;
border-radius: 5px;
margin-top:5px;
margin-bottom:15px;
    padding-bottom: 5px;
 }
.input{

    border: 2px solid #A0A0A0;

border-radius: 5px;

    padding: 3px;

	height:35px;
}

.input:focus{
    -moz-box-shadow:0px 0px 3px #aaa;
    -webkit-box-shadow:0px 0px 3px #aaa;
    box-shadow:0px 0px 3px #aaa;
    background-color:#FFFEEF;
}
body {
margin:0;padding:0;
background:url(data/body-bg.gif) top left repeat;
}
  </style>
  	  <script type="text/javascript">$(function(){$("#btnEnter").button();});</script>
  	  </head>
<body><br>

<form action="index.php" id="form_login" method="post">
	<input type="hidden" name="user_ip" value="'.$_SERVER["REMOTE_ADDR"].'"> 
<div align="center" style="margin-top:30px;">
<img src="data/img/login.png"></div><br>	  <table class="frame" align="center"><tr><td colspan="2" align="center"><br></td></tr><tr><td align="right" width="85"><font size="4">Имя:</font>&nbsp;&nbsp;</td><td>

<select name="username" style="width:160px; font-size: 1em;" class="input"><option value="0">Выберите...</option>';
$query = "SELECT `login`,`name`,`id` FROM `workers` WHERE `delete`='0' AND `group`<>'5' AND `group`<>'6' ORDER BY `name` ASC";
$result = mysql_query($query) or die(mysql_error());
while($user= mysql_fetch_row($result)) {
if($user[2]!="1"){
$pieces = explode(" ", $user[1]);
$print_add_name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
echo '<option value='.$user[0].'>'.$print_add_name.'</option>';
}
}
print '</select>
	</td></tr>
   <tr><td align="right" height="20"><font size="4">Пароль:</font>&nbsp;&nbsp;</td><td><input type="password" name="password" style="width:160px;height:32px;" class="input"/></td></tr>
    <tr><td></td><td height="55"><button type="submit" class="b_login">войти</button></td></tr>
  
    
    </table>  '.$err.'
</form>

</body>
</html>';
}

?>