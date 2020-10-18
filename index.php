<?php
include('config.php');

session_start();

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


        $username = (isset($_POST['username'])) ? mysql_real_escape_string($_POST['username']) : '';
        $password = md5($_POST['password']);

        $auth_code_from_email = (isset($_POST['authcode'])) ? mysql_real_escape_string($_POST['authcode']) : '';
        $auth_code = isset($_COOKIE['authcode']) ? mysql_real_escape_string($_COOKIE['authcode']) : "";

        if ($username == "0") $username = "admin";

        $query = "SELECT *
					FROM `workers`
					WHERE `login`='{$username}' AND `password`='{$password}' AND `group`<>'5'
					LIMIT 1";

        $sql = mysql_query($query) or die(mysql_error());

        // если такой пользователь нашелся
        if (mysql_num_rows($sql) == 1) {

            $row = mysql_fetch_assoc($sql);

            if ($auth_code != "") {


                $query_auth = "SELECT * FROM authsessions
                                    WHERE user_id = " . $row['id'] . " AND auth_code = " . $auth_code;
                $result = mysql_query($query_auth) or die(mysql_error());
                //$auth = mysql_fetch_array($result);

                if (mysql_num_rows($result) > 0) {


                    ////
                    //if($user_ip!=$line['ip']&&$row['id']!=1&&$row['id']!=11&&$row['id']!=9&&$row['id']!=13&&$row['id']!=23&&$row['group']!=1&&$row['group']!=2&&$user_ip!="192.168.0.33"){
                    //$query_logs = "INSERT INTO `logs` (`user`,`log_message`) VALUES ('".$row['id']."','Заход с чужого компьютера - ".$user_ip."')";
                    //$result_logs = mysql_query($query_logs) or die(mysql_error());


                    //valign="middle">&nbsp;&nbsp;&nbsp;<font size="4" color="red">Ошибка:</font> <font size="4">Вы попытались зайти в систему с чужого компьютера, уведомление директору отправлено!</font></td></tr> </table>';
                    //} else
                    //{
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

                    //if($_SESSION["group"]==3) header('Location: orders'); else header('Location: main');
                    header('Location: orders');

                    exit;

                } else {


                }

            } else {

                if ($auth_code_from_email != "") {

                    $query_auth = "SELECT session_id FROM authsessions
                                    WHERE user_id = " . $row['id'] . " AND used = 0 AND auth_code = " . $auth_code_from_email;
                    $result = mysql_query($query_auth) or die(mysql_error());

                    if (mysql_num_rows($result) > 0) {

                        $auth = mysql_fetch_array($result);
                        $query_auth = "UPDATE authsessions SET used = 1
                                    WHERE session_id = " . $auth['session_id'];
                        $result = mysql_query($query_auth) or die(mysql_error());

                        setcookie("authcode", $auth_code_from_email);
                        $js_script = 'document.getElementById("form_login").submit();';
                        $success = '<table align="center"><tr><td valign="middle"><td height="100" valign="middle"><font size="4" color="green"><b>Успешный вход!</b></font> Перенаправляем...</td></tr> </table>';

                    } else $error_message = 'Неправильный проверочный код!';

                } else {

                    if (isset($_SERVER['HTTP_USER_AGENT'])) {
                        $user_agent = htmlspecialchars(strip_tags($_SERVER['HTTP_USER_AGENT']));
                    } else {
                        $user_agent = 'n/a';
                    }

                    $user_ip = get_client_ip();

                    $query_auth_code_select = "SELECT auth_code FROM authsessions
                                    WHERE user_id = " . $row['id'] . " AND used = 0";
                    $result_code_select = mysql_query($query_auth_code_select) or die(mysql_error());

                    if (mysql_num_rows($result_code_select) > 0) {
                        $auth_code_select = mysql_fetch_array($result_code_select);

                        $authcode_otp = $auth_code_select['auth_code'];

                    } else {
                        $authcode_otp = rand(100000, 999999);


                        $query_auth = "INSERT INTO authsessions SET user_id = " . $row['id'] . ", auth_code = " . $authcode_otp . ", user_agent = '" . mysql_real_escape_string($user_agent) . "', ip = '" . $user_ip . "'";
                        $result = mysql_query($query_auth) or die(mysql_error());
                    }

                    include_once("send_authcode.php");

                    $subject = 'Необходима авторизация устройства';
                    $body = 'Здравствуйте, <b>' . $row['name'] . '</b><br><br>Ваша учетная запись недавно была использована для входа на этом устройстве: ' . $user_agent . ' (IP: ' . $user_ip . ')<br><br><b>Ваш проверочный код</b>: <h2>' . $authcode_otp . '</h2>';

                    send_email($row['email'], $subject, $body);

                    $authcode_input = '<tr height="90"><td align="right" style="padding-top: 13px;margin-right: 3px;">Код:&nbsp;&nbsp;<br><small>из почты</small>&nbsp;&nbsp;</td><td style="padding-top: 13px;"><input type="text" class="input" id="authcode" name="authcode" value="" style=""></td></tr>';
                }

            }
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
        <script type="text/javascript">
            window.addEventListener("load", function(event) {
                ' . $js_script . '
            });
        </script>
  	  </head>
<body><br>

<form action="index.php" id="form_login" method="post">
<div align="center" style="margin-top:10px;">
<img src="data/img/login.png"></div><br>	  <table class="frame" align="center"><tr><td colspan="2" align="center"><br></td></tr><tr><td align="right" width="85"><font size="4">Имя:</font>&nbsp;&nbsp;</td><td>

<select name="username" style="width:160px; font-size: 1em;" class="input"><option value="0">Выберите...</option>';
    $query = "SELECT `login`,`name`,`id` FROM `workers` WHERE `delete`='0' AND `group`<>'5' AND `group`<>'6' ORDER BY `name` ASC";
    $result = mysql_query($query) or die(mysql_error());
    while ($user = mysql_fetch_row($result)) {
        if ($user[2] != "1") {
            $pieces = explode(" ", $user[1]);
            $print_add_name = $pieces[0] . " " . substr($pieces[1], 0, 2) . ". " . substr($pieces[2], 0, 2) . ".";
            if (isset($_POST['username']) && @$_POST['username'] != '0' && @$_POST['username'] == $user[0])  $selected = ' selected'; else $selected = '';
                echo '<option value=' . $user[0] .$selected. '>' . $print_add_name . '</option>';
        }
    }
    echo '</select></td></tr><tr><td align="right" height="20"><font size="4">Пароль:</font>&nbsp;&nbsp;</td><td><input type="password" name="password" style="width:160px;height:32px;" class="input" value="';

    if (isset($_POST['password']) && @$_POST['password'] != '') echo $_POST['password'];

    echo '"></td></tr>
    <tr><td></td><td height="55"><button type="submit" class="b_login">войти</button></td></tr>
  ' . $authcode_input . '    
    </table>  ';

    if ($error_message != '') echo $err;
    if ($success != '') echo $success;
    echo '</form>
</body>
</html>';
}

?>