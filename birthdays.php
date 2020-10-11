<table cellpadding="5" id="birthdays" style="border-collapse: collapse;border-style: solid;border-color: black;font-size:130%;" border="1" bgcolor="#eeeeee">
<?php 
// Подключение и выбор БД
include "config.php";

$m['01']="января"; 
$m['02']="февраля"; 
$m['03']="марта"; 
$m['04']="апреля"; 
$m['05']="мая";
$m['06']="июня"; 
$m['07']="июля"; 
$m['08']="августа"; 
$m['09']="сентября"; 
$m['10']="октября"; 
$m['11']="ноября";
$m['12']="декабря";


$query = "SELECT `name`,`date_birth` FROM `workers` WHERE `delete`='0'";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) {
$str_temp=explode('-',$row['date_birth']);
$datas=date("Y").'-'.trim($str_temp[1]).'-'.trim($str_temp[2]);


$name=$row['name'];

if(strtotime('-7 day', strtotime($datas))<=strtotime('now')&&strtotime($datas)>strtotime('now'))$color=' bgcolor="#74C2E1"'; else $color='';
echo '<tr'.$color.'><td width="250"><b>'.$name.'<td align="left"><b>'.date('d',strtotime($row['date_birth'])).'</b> '.$m[date('m',strtotime($row['date_birth']))].' ('.(date("Y")-$str_temp[0]).')</td></tr>';

}





?>


</table>