<?php

### Database
$db_host = "127.0.0.1";
$db_name = "base";
$db_user = "root";
$db_pass = "";

### Email
# Запрос подключение
$db = mysql_connect($db_host, $db_user, $db_pass) or die ("Ошибка связи с компьютером БД");
mysql_select_db ("$db_name") or die ("Ошибка связи с базой");
mysql_query('SET NAMES utf8'); 

?>