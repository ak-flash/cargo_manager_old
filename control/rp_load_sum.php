<?php

include "../config.php";
include "komissia.php";

$page = $_GET['page'];      // Номер запришиваемой страницы
$limit = $_GET['rows'];     // Количество запрашиваемых записей
$sidx = $_GET['sidx'];      // Номер элемента массива по котору следует производить сортировку
                            // Проще говоря поле, по которому следует производить сортировку
$sord = $_GET['sord'];      // Направление сортировки

function mediana($arr)
{
sort($arr);
$count = count($arr);
$n1 = floor($count / 2);
$n2 = $n1 + 1;
return ($arr[$n1] + $arr[$n2]) / 2;
}


if ($_GET['mode']=='rp_load') 
{

$query_user = "SELECT `id`,`name` FROM `workers`";
$result_user = mysql_query($query_user) or die(mysql_error());

while($user = mysql_fetch_row($result_user)) {
$pieces = explode(" ", $user[1]);
$managers=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
$users[$user[0]]= $managers;
}

$query_temp = "SELECT `id` FROM `clients`";
$result_temp = mysql_query($query_temp) or die(mysql_error());

	
// Строки данных для таблицы
$f = 0;
while($row_temp = mysql_fetch_array($result_temp)) {




if ($_GET['date_start']!=''){
$start_elements  = explode("/",$_GET['date_start']);
$date_start=$start_elements[2]."-".$start_elements[1]."-".$start_elements[0];
$end_elements  = explode("/",$_GET['date_end']);
$date_end=$end_elements[2]."-".$end_elements[1]."-".$end_elements[0];

$query_ord = "SELECT `id`,`cl_cash`,`cl_nds`,`tr_cash`,`tr_nds`,`cl_minus`,`cl_plus`,`tr_minus`,`tr_plus` FROM `orders` WHERE `delete`='0' AND `client`='".$row_temp['id']."' AND DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' ORDER BY `Id` DESC";
} else $query_ord = "SELECT `id`,`cl_cash`,`cl_nds`,`tr_cash`,`tr_nds`,`cl_minus`,`cl_plus`,`tr_minus`,`tr_plus` FROM `orders` WHERE `delete`='0' AND `client`='".$row_temp['id']."' ORDER BY `Id` DESC";

$result_ord = mysql_query($query_ord) or die(mysql_error());

while($orders= mysql_fetch_array($result_ord)) {

$cash=komissia($orders['cl_cash'],$orders['cl_minus'],$orders['cl_plus'],$orders['cl_nds'],$orders['tr_cash'],$orders['tr_minus'],$orders['tr_plus'],$orders['tr_nds']);


$cash_ok=$cash*100/(int)$orders['cl_cash'];
if ($_GET['start_rent']!=''){
$start_rent=$_GET['start_rent'];
$end_rent=$_GET['end_rent'];
if($cash_ok>=$start_rent&&$cash_ok<=$end_rent){$id_mass[$f]=$row_temp['id'];$f++;}
} else {$id_mass[$f]=$row_temp['id'];$f++;}

}



$cash=0;$cl_cash_all=0;$tr_cash_all=0;
$cash_ok=0;
}

// Если не указано поле сортировки, то производить сортировку по первому полю
if(!$sidx) $sidx =1;


if($id_mass){
$result = mysql_query("SELECT COUNT(*) AS count FROM `clients` WHERE `Id` IN (".implode(',' , $id_mass).")");

// Выполним запрос, который вернет суммарное кол-во записей в таблице

$row = mysql_fetch_array($result,MYSQL_ASSOC);
$count = $row['count'];     // Теперь эта переменная хранит кол-во записей в таблице

// Рассчитаем сколько всего страниц займут данные в БД
if( $count > 0 && $limit > 0) {
    $total_pages = ceil($count/$limit);
} else {
    $total_pages = 0;
}
// Если по каким-то причинам клиент запросил
if ($page > $total_pages) $page=$total_pages;

// Рассчитываем стартовое значение для LIMIT запроса
$start = $limit*$page - $limit;
// Зашита от отрицательного значения
if($start <0) $start = 0;

// Запрос выборки данных

$query = "SELECT * FROM `clients` WHERE `Id` IN (".implode(',' , $id_mass).") ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit;
$result = mysql_query($query) or die(mysql_error());


// Начало формирование массива
// для последующего преобразоования
// в JSON объект
$data->page       = $page;
$data->total      = $total_pages;
$data->records    = $count;


// Строки данных для таблицы
$i = 0;
while($row = mysql_fetch_array($result)) {
$cash=0;
$cl_cash_all=0;
$tr_cash_all=0;
$cash_ok=0;unset($sum_array);
$c_ar=0;$sum_ar=0;$min_max=0;unset($cash_array);
switch ($row['pref']) {
case '1': $pref='ООО';break;
case '2': $pref='ОАО';break;
case '3': $pref='ИП';break;
case '4': $pref='ЗАО';break;
case '5': $pref='';break;}



if ($date_start!='')
$query_ord = "SELECT `id`,`cl_cash`,`cl_nds`,`tr_cash`,`tr_nds`,`cl_minus`,`cl_plus`,`tr_minus`,`tr_plus` FROM `orders` WHERE `delete`='0' AND `client`='".$row['id']."' AND DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' ORDER BY `Id` DESC"; else $query_ord = "SELECT `id`,`cl_cash`,`cl_nds`,`tr_cash`,`tr_nds`,`cl_minus`,`cl_plus`,`tr_minus`,`tr_plus` FROM `orders` WHERE `delete`='0' AND `client`='".$row['id']."' ORDER BY `Id` DESC";


$result_ord = mysql_query($query_ord) or die(mysql_error());


$s=0;
while($orders= mysql_fetch_array($result_ord)) {
$cash=komissia($orders['cl_cash'],$orders['cl_minus'],$orders['cl_plus'],$orders['cl_nds'],$orders['tr_cash'],$orders['tr_minus'],$orders['tr_plus'],$orders['tr_nds']);

$cash_ok=$cash*100/(int)$orders['cl_cash'];

if ($_GET['start_rent']!=''){
$start_rent=$_GET['start_rent'];
$end_rent=$_GET['end_rent'];
if($cash_ok>=$start_rent&&$cash_ok<=$end_rent){$cash_array[$s]=$cash_ok;$sum_array[$s]=$cash;$c_ar=(float)$c_ar+(float)$cash_ok;$sum_ar=(int)$sum_ar+(int)$cash;$s++;}
} else {$cash_array[$s]=$cash_ok;$sum_array[$s]=$cash;$c_ar=(float)$c_ar+(float)$cash_ok;$sum_ar=(int)$sum_ar+(int)$cash;$s++;}

}

switch ($row['nds']) {
case '0': $nds_cl='без НДС';break;
case '1': $nds_cl='с НДС';break;
case '2': $nds_cl='НАЛ';break;
}

if($s!=1){$cash_mediana=round(mediana($cash_array),1);
$sum_mediana=round(mediana($sum_array),1);
$min_max="<font color='green'>".round(min($cash_array),1)."%</font> - <font color='red'>".round(max($cash_array),1)."%</font>";
} else {$cash_mediana=round($c_ar/$s,1);
$sum_mediana=round($sum_ar/$s);
$min_max=round(max($cash_array),1)."%";
}

$data->rows[$i]['id'] = $row['id'];
    $data->rows[$i]['cell'] = array($row['id'],"<font size=3>".$pref." <b>«".$row['name']."»</font></b> (".$nds_cl.")<br>(".$users[$row['cl_manager']].")","&nbsp;&nbsp;&nbsp;&nbsp;<font size='4'><b>".round($c_ar/$s,1)."</font>%</b> - ".round($sum_ar/$s)." руб. (".$s.")","&nbsp;&nbsp;&nbsp;&nbsp;<b>".$cash_mediana."%</b> - ".$sum_mediana." руб.",$min_max);
    $i++;
}




} else {$data->rows[0]['id'] = 0;
    $data->rows[0]['cell'] = array(0,"<font size=3><b>Не найдено задолженностей</font></b>","","",""); }

// Перед выводом не забывайте выставить header
// с типом контента и кодировкой
header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
	
}


if ($_GET['mode']=='desc') 
{
$id =$_GET['id'];

if ($_GET['date_start']!=''){
$start_elements  = explode("/",$_GET['date_start']);
$date_start=$start_elements[2]."-".$start_elements[1]."-".$start_elements[0];
$end_elements  = explode("/",$_GET['date_end']);
$date_end=$end_elements[2]."-".$end_elements[1]."-".$end_elements[0];
$query= "SELECT `id`,`cl_cash`,`cl_nds`,`tr_cash`,`tr_nds`,`cl_minus`,`cl_plus`,`tr_minus`,`tr_plus` FROM `orders` WHERE `delete`='0' AND `client`='".mysql_escape_string($id)."' AND DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' ORDER BY `cl_cash` ASC";}
else $query= "SELECT `id`,`cl_cash`,`cl_nds`,`tr_cash`,`tr_nds`,`cl_minus`,`cl_plus`,`tr_minus`,`tr_plus` FROM `orders` WHERE `delete`='0' AND `client`='".mysql_escape_string($id)."' ORDER BY `cl_cash` ASC";


$result = mysql_query($query) or die(mysql_error());



while($orders= mysql_fetch_array($result)) {
$cash=komissia($orders['cl_cash'],$orders['cl_minus'],$orders['cl_plus'],$orders['cl_nds'],$orders['tr_cash'],$orders['tr_minus'],$orders['tr_plus'],$orders['tr_nds']);

$cash_ok=$cash*100/(int)$orders['cl_cash'];

switch ($orders['tr_nds']) {
case '0': $nds_tr='без НДС';break;
case '1': $nds_tr='с НДС';break;
case '2': $nds_tr='НАЛ';break;
}
switch ($orders['cl_nds']) {
case '0': $nds_cl='без НДС';break;
case '1': $nds_cl='с НДС';break;
case '2': $nds_cl='НАЛ';break;
}

if ($_GET['start_rent']!=''){
$start_rent=$_GET['start_rent'];
$end_rent=$_GET['end_rent'];
if($cash_ok>=$start_rent&&$cash_ok<=$end_rent){
$cl_order="<tr><td align='center'><font size='4'><b>".$orders['id']."</b></font></td><td align='center'><b>".$orders['cl_cash']."</b> руб. (".$nds_cl.")</td><td align='center'><b>".$orders['tr_cash']."</b> руб. (".$nds_tr.")</td><td align='center'><font size='5'><b>".round($cash_ok,1)."%</b></font> (".$cash." руб.)</td></tr>".$cl_order;
}
} else $cl_order="<tr><td align='center'><font size='4'><b>".$orders['id']."</b></font></td><td align='center'><b>".$orders['cl_cash']."</b> руб. (".$nds_cl.")</td><td align='center'><b>".$orders['tr_cash']."</b> руб. (".$nds_tr.")</td><td align='center'><font size='5'><b>".round($cash_ok,1)."%</b></font> (".$cash." руб.)</td></tr>".$cl_order;

}




$data->rows[0]['cell'] =array("<table width='100%' style='border-collapse: collapse;border-style: solid;border-color: black;' border='1'><tr><td width='10' bgcolor='#F2F2F2' align='center'><b>Заявка №</b></td><td width='40' bgcolor='#F2F2F2' align='center'><b><font size='3'>Ставка клиента</font></b></td><td width='40' bgcolor='#F2F2F2' align='center'><b><font size='3'>Ставка перевозчика</font></b></td><td width='60' bgcolor='#F2F2F2' align='center'><font size='3'><b>Комиссия</font></b></td></tr>".$cl_order."</table>");

header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
}

?> 

     			
