<?php
include ('.env');

# Запрос подключение
$db = mysql_connect($db_host, $db_user, $db_pass) or die ("Ошибка связи с компьютером БД");
mysql_select_db ($db_base) or die ("Ошибка связи с базой");
mysql_query('SET NAMES utf8'); 

?>