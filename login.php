<?php
include('config.php');

session_start();

/*
 * CREATE TABLE `authsessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT '0',
  `auth_code` varchar(50) DEFAULT NULL,
  `user_agent` varchar(400) DEFAULT NULL,
  `used` int(1) DEFAULT '0',
  `ip` varchar(50) DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `Индекс 1` (`session_id`),
  KEY `Индекс 2` (`user_id`),
  CONSTRAINT `FK_authsessions_workers` FOREIGN KEY (`user_id`) REFERENCES `workers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 */

// Function to get the client IP address
function get_client_ip()
{
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


if (@$_GET['mode'] == "logout") {
    session_unset();
    unset($_COOKIE["authcode"]);
    setcookie('authcode', null, -1, '/');
}

$error_message = '';
$js_script = '';
$success = '';

if (isset($_SESSION['user_id'])) {
    // юзер уже залогинен, перекидываем его отсюда на закрытую страницу

    if ($_SESSION["group"] == 3) header('Location: orders'); else header('Location: main');
    exit;

} else {


    if (!empty($_POST)) {
        if (@$_POST['password'] != '') {


            $username = (isset($_POST['username'])) ? (int)$_POST['username'] : '';
            $password = md5($_POST['password']);


            if ($username == 0) $username = 1;

            $query = "SELECT *
					FROM `workers`
					WHERE `id`='{$username}' AND `password`='{$password}' AND `group`<>'5'
					LIMIT 1";

            $sql = mysql_query($query) or die(mysql_error());


            // если такой пользователь нашелся
            if (mysql_num_rows($sql) > 0) {

                $row = mysql_fetch_assoc($sql);

                $_SESSION['user_id'] = $row['id'];
                $_SESSION['own_transporter_ids'] = $own_transporter_ids;
                $_SESSION["user"] = $row['name'];
                $_SESSION["group"] = $row['group'];
                $_SESSION["email"] = $row['email'];
                //$_SESSION["mail_pass"]=$row['mail_pass'];
                $_SESSION["voip"] = $row['voip'];
                        //$_SESSION["messages"]=$row['chat_mess'];
                        $_SESSION["user_ip"] = get_client_ip();
                        //$_SESSION["real_ip"]=$row['ip'];
                        $_SESSION["gruz_count"] = 0;

                        switch ($row['group']) {
                            case '1':
                                $_SESSION["group_name"] = 'Администратор';
                                break;
                            case '2':
                                $_SESSION["group_name"] = 'Директор';
                                break;
                            case '3':
                                $_SESSION["group_name"] = 'Менеджер';
                                break;
                            case '4':
                                $_SESSION["group_name"] = 'Бухгалтер';
                                break;
                        }


                        $query_company = "SELECT `id`,`name` 
                        FROM `company` 
                        WHERE `active`='1'";

                $result_company = mysql_query($query_company) or die(mysql_error());

                while ($company = mysql_fetch_row($result_company)) {
                    if ($company[0] != 1) {
                        $_SESSION["company"][$company[0]] = $company[1];
                    }
                }

                // Just to migrate to password_hash()
                /*
                 *
                 */
                $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $query_update_pass = "UPDATE workers SET pass_hash = '" . $hashed_password . "'
                                WHERE id = " . $row['id'];
                $result_update_pass = mysql_query($query_update_pass) or die(mysql_error());

                //if($_SESSION["group"]==3) header('Location: orders'); else header('Location: main');
                if ($row['email'] == '@eurasia-logistic.com' || $row['email'] == '') header('Location: profile'); else header('Location: orders');

                exit;


            } else {
                $error_message = 'Неправильный пароль!';
            }
        } else $error_message = 'Введите пароль!';


        $err = '<table align="center"><tr><td valign="middle"><img src="data/img/exclamation.png"></td><td height="100" valign="middle">&nbsp;&nbsp;&nbsp;<font size="4" color="red">Ошибка: </font>' . $error_message . '</td></tr> </table>';
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
margin-bottom:5px;
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
  	  </head>
<body><br>

<form action="login.php" id="form_login" method="post">
<div align="center" style="margin-top:10px;">
<img src="data/img/login.png"></div><br>	  <table class="frame" align="center"><tr><td colspan="2" align="center"><br></td></tr><tr><td align="right" width="85"><font size="4">Имя:</font>&nbsp;&nbsp;</td><td>

<select name="username" style="width:160px; font-size: 1em;" class="input"><option value="0">Выберите...</option>';
    $query = "SELECT `id`,`name` FROM `workers` WHERE `delete`='0' AND `group`<>'5' AND `group`<>'6' ORDER BY `name` ASC";
    $result = mysql_query($query) or die(mysql_error());
    while ($user = mysql_fetch_row($result)) {
        if ($user[0] != "1") {
            $pieces = explode(" ", $user[1]);
            $print_add_name = $pieces[0] . " " . substr($pieces[1], 0, 2) . ". " . substr($pieces[2], 0, 2) . ".";
            if (isset($_POST['username']) && @$_POST['username'] != '0' && @$_POST['username'] == $user[0]) $selected = ' selected'; else $selected = '';
            echo '<option value=' . $user[0] . $selected . '>' . $print_add_name . '</option>';
        }
    }
    echo '</select></td></tr><tr><td align="right" height="20"><font size="4">Пароль:</font>&nbsp;&nbsp;</td><td><input type="password" name="password" style="width:160px;height:32px;" class="input" value="';

    if (isset($_POST['password']) && @$_POST['password'] != '') echo $_POST['password'];

    echo '"></td></tr>
    <tr><td></td><td height="55"><button type="submit" class="b_login">войти</button></td></tr>   
    </table>  ';

    if ($error_message != '') echo $err;
    if ($success != '') echo $success;
    echo '</form>
</body>
</html>';
}

?>