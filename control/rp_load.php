<?php

include "../config.php";


if (!isset($data)) $data = new stdClass();

$page = (int)$_GET['page'];      // Номер запришиваемой страницы
$limit = (int)$_GET['rows'];     // Количество запрашиваемых записей
$sidx = mysql_real_escape_string($_GET['sidx']);      // Номер элемента массива по котору следует производить сортировку // Проще говоря поле, по которому следует производить сортировку
$sord = mysql_real_escape_string($_GET['sord']);      // Направление сортировки

$ord_cash = [];

$query_pays = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while ($pay = mysql_fetch_row($result_pays)) {
    if ($ord_cash[$pay[1]] == "")
        $ord_cash[$pay[1]] = (int)$pay[0]; else $ord_cash[$pay[1]] = (int)$ord_cash[$pay[1]] + (int)$pay[0];
}


if ($_GET['mode'] == 'rp_load') {

    $query_user = "SELECT `id`,`name` FROM `workers`";
    $result_user = mysql_query($query_user) or die(mysql_error());

    while ($user = mysql_fetch_row($result_user)) {
        $pieces = explode(" ", $user[1]);
        $managers = $pieces[0] . " " . substr($pieces[1], 0, 2) . ". " . substr($pieces[2], 0, 2) . ".";
        $users[$user[0]] = $managers;
    }



$cl_id =strip_tags($_GET['cl_id']); 
if($cl_id) $query_temp = "SELECT `id` FROM `clients` WHERE `id`='".mysql_escape_string($cl_id)."'"; else $query_temp = "SELECT `id` FROM `clients`";
$result_temp = mysql_query($query_temp) or die(mysql_error());

	
// Строки данных для таблицы
$f = 0;
while($row_temp = mysql_fetch_array($result_temp)) {




if ($_GET['date_start_cl']!=''){
$start_elements  = explode("/",$_GET['date_start_cl']);
$date_start=$start_elements[2]."-".$start_elements[1]."-".$start_elements[0];
$end_elements  = explode("/",$_GET['date_end_cl']);
$date_end=$end_elements[2]."-".$end_elements[1]."-".$end_elements[0];

$query_ord = "SELECT `id`,`cl_cash` FROM `orders` WHERE `client`='".$row_temp['id']."' AND DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' ORDER BY `Id` DESC";
} else $query_ord = "SELECT `id`,`cl_cash` FROM `orders` WHERE `client`='".$row_temp['id']."' ORDER BY `Id` DESC";

$result_ord = mysql_query($query_ord) or die(mysql_error());

while($orders= mysql_fetch_row($result_ord)) {
$cl_pay_all_temp=(int)$cl_pay_all_temp+(int)$ord_cash[$orders[0]];
$cl_pay_need_temp=(int)$cl_pay_need_temp+(int)$orders[1];
}


$cash_all_temp=(int)$cl_pay_need_temp-(int)$cl_pay_all_temp/100;
if($cash_all_temp!=0){
$id_mass[$f]=$row_temp['id'];$f++;
}

$cl_pay_all_temp=0;
$cl_pay_need_temp=0;

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
$cl_pay_all=0;
$cl_pay_need=0;
$cash_el=0;
$cash_el_plan=0;
switch ($row['pref']) {
case '1': $pref='ООО';break;
case '2': $pref='ОАО';break;
case '3': $pref='ИП';break;
case '4': $pref='ЗАО';break;
case '5': $pref='';break;}


    if ($date_start != '') {
        $query_ord = "SELECT `id`,`cl_cash`,`cl_tfpay`,`cl_event`,`date_in1`,`date_in2`,`date_out1`,`date_out2`,`date_plan`,`cl_rashod_sb`,`tr_gruz_worker`,`cl_rashod_na_cl` FROM `orders` WHERE `client`='" . $row['id'] . "' AND DATE(`data`) BETWEEN '" . $date_start . "' AND '" . $date_end . "' ORDER BY `Id` DESC";
    } else $query_ord = "SELECT `id`,`cl_cash`,`cl_tfpay`,`cl_event`,`date_in1`,`date_in2`,`date_out1`,`date_out2`,`date_plan`,`cl_rashod_sb`,`tr_gruz_worker`,`cl_rashod_na_cl` FROM `orders` WHERE `client`='" . $row['id'] . "' ORDER BY `Id` DESC";


$result_ord = mysql_query($query_ord) or die(mysql_error());



while($orders= mysql_fetch_row($result_ord)) {

$cl_event="";





switch ($orders[3]) {
case '1': if($orders[5]!="0000-00-00"&&$orders[5]!="1970-01-01"&&$orders[5]!="")$cl_event=$orders[5]; else $cl_event=$orders[4];break;
case '2': if($orders[7]!="0000-00-00"&&$orders[7]!="1970-01-01"&&$orders[7]!="")$cl_event=$orders[7]; else $cl_event=$orders[6];break;
case '3': $query_docs = "SELECT `date_cl_receve` FROM `docs` WHERE `order`='".mysql_escape_string($orders[0])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());$cl_events=mysql_fetch_row($result_docs);if($cl_events[0]!="0000-00-00"&&$cl_events[0]!="1970-01-01"&&$cl_events[0]!=""){
$cl_event=$cl_events[0];}break;
case '4': $query_docs = "SELECT `date_cl_receve` FROM `docs` WHERE `order`='".mysql_escape_string($orders[0])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());$cl_events=mysql_fetch_row($result_docs);if($cl_events[0]!="0000-00-00"&&$cl_events[0]!="1970-01-01"&&$cl_events[0]!=""){
$cl_event=$cl_events[0];}break;
}


if($cl_event!="0000-00-00"&&$cl_event!="1970-01-01"&&$cl_event!=""){

$cl_event_date=date('d/m/Y', strtotime('+'.(int)$orders[2].' day', strtotime($cl_event)));

$elements  = explode("/",$cl_event_date);
$current_date = mktime (0,0,0,date("m") ,date("d"),date("Y")); //дата сегодня
$old_date = mktime (0,0,0,$elements[1],$elements[0],$elements[2]); 
$difference = ($current_date - $old_date);
$difference_in_days = floor($difference / 86400); //разница в днях

if($difference_in_days>0)$cash_el=(int)$cash_el+((int)$orders[1]-(int)$ord_cash[$orders[0]]/100);
} else {


$current_date = mktime (0,0,0,date("m") ,date("d"),date("Y")); //дата сегодня

$elements  = explode("-",$orders[8]);
$old_date = mktime (0,0,0,$elements[1],$elements[2],$elements[0]); 
$difference = ($current_date - $old_date);
$difference_in_days = floor($difference / 86400); //разница в днях

if($difference_in_days>0)$cash_el_plan=(int)$cash_el_plan+((int)$orders[1]-(int)$ord_cash[$orders[0]]/100);
}

$cl_pay_all=(int)$cl_pay_all+(int)$ord_cash[$orders[0]];

    $cl_pay_need = (int)$cl_pay_need + (int)$orders[1] - (int)$orders[9] - (int)$orders[10] - (int)$orders[11];

} 




switch ($row['nds']) {
case '0': $nds_cl='без НДС';break;
case '1': $nds_cl='с НДС';break;
case '2': $nds_cl='НАЛ';break;
}

$cash_all=(int)$cl_pay_need-(int)$cl_pay_all/100;
$cash_now=(int)$cash_all-(int)$cash_el-(int)$cash_el_plan;
if($cash_all!=0){
$cash_all=$cash_all." руб.";
if($cash_now!=0)$cash_now=$cash_now." руб."; else $cash_now="-";
if($cash_el!=0)$cash_el=$cash_el." руб."; else $cash_el="-";
if($cash_el_plan!=0)$cash_el_plan=$cash_el_plan." руб."; else $cash_el_plan="-";  

$data->rows[$i]['id'] = $row['id'];
    $data->rows[$i]['cell'] = array($row['id'],"<font size=3>".$pref." <b>«".$row['name']."»</font></b> (".$nds_cl.")<br>(".$users[$row['cl_manager']].")",$cash_all,$cash_now,$cash_el,$cash_el_plan);
    $i++;
}




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
if ($_GET['date_start_cl']!=''){
$start_elements  = explode("/",$_GET['date_start_cl']);
$date_start=$start_elements[2]."-".$start_elements[1]."-".$start_elements[0];
$end_elements  = explode("/",$_GET['date_end_cl']);
$date_end=$end_elements[2]."-".$end_elements[1]."-".$end_elements[0];}


if($id!="") {
if($_GET['date_start_cl']!='')
$query= "SELECT `id`,`cl_cash`,`cl_tfpay`,`cl_event`,`date_in1`,`date_in2`,`date_out1`,`date_out2`,`manager`,`date_plan` FROM `orders` WHERE `client`='".mysql_escape_string($id)."' AND DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' ORDER BY `Id` DESC"; else $query= "SELECT `id`,`cl_cash`,`cl_tfpay`,`cl_event`,`date_in1`,`date_in2`,`date_out1`,`date_out2`,`manager`,`date_plan` FROM `orders` WHERE `client`='".mysql_escape_string($id)."' ORDER BY `Id` DESC";}
$result = mysql_query($query) or die(mysql_error());

$cl_pay_all_temp=0;
$cl_pay_need_temp=0;

while($orders= mysql_fetch_row($result)) {
$cl_event="";
$cl_ev="";
$days="";
switch ($orders[3]) {
case '1': $cl_ev='Загрузка';break;
case '2': $cl_ev='Выгрузка';break;
case '3': $cl_ev='Поступление факсимильных документов';break;
case '4': $cl_ev='Поступление оригинальных документов';break;}



if((int)$ord_cash[$orders[0]]/100!=(int)$orders[1]){
switch ($orders[3]) {
case '1': if($orders[5]!="0000-00-00"&&$orders[5]!="1970-01-01"&&$orders[5]!="")$cl_event=$orders[5]; else $cl_event=$orders[4];break;
case '2': if($orders[7]!="0000-00-00"&&$orders[7]!="1970-01-01"&&$orders[7]!="")$cl_event=$orders[7]; else $cl_event=$orders[6];break;
case '3': $query_docs = "SELECT `date_cl_receve` FROM `docs` WHERE `order`='".mysql_escape_string($orders[0])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());$cl_events=mysql_fetch_row($result_docs);if($cl_events[0]!="0000-00-00"&&$cl_events[0]!="1970-01-01"&&$cl_events[0]!=""){
$cl_event=$cl_events[0];};break;
case '4': $query_docs = "SELECT `date_cl_receve` FROM `docs` WHERE `order`='".mysql_escape_string($orders[0])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());$cl_events=mysql_fetch_row($result_docs);if($cl_events[0]!="0000-00-00"&&$cl_events[0]!="1970-01-01"&&$cl_events[0]!=""){
$cl_event=$cl_events[0];};break;
}

if($orders[9]!="1970-01-01"&&$orders[9]!=""&&$orders[9]!="0000-00-00")$d_plan=date('d/m/Y', strtotime($orders[9])); else $d_plan='-';

if($cl_event!="0000-00-00"&&$cl_event!="1970-01-01"&&$cl_event!=""){

$cl_event_date=date('d/m/Y', strtotime('+'.(int)$orders[2].' day', strtotime($cl_event)));

$elements  = explode("/",$cl_event_date);
$current_date = mktime (0,0,0,date("m") ,date("d"),date("Y")); //дата сегодня
$old_date = mktime (0,0,0,$elements[1],$elements[0],$elements[2]); 
$difference = ($current_date - $old_date);
$difference_in_days = floor($difference / 86400); //разница в днях
$status="(".$cl_event_date.")";
if($difference_in_days>0)$days=" - <font color='red'>Задержка оплаты в ".$difference_in_days." дн.</font>";
} else {$status="(<font color='red'>Счёт не отправлен</font>)";}

$cl_need="<tr><td align='center'><font size='4'><b>".$orders[0]."</b></font></td><td align='center'>".$orders[1]." руб.</td><td align='center'>".((int)$ord_cash[$orders[0]]/100)." руб.</td><td align='center'><font size='4'><b>".((int)$orders[1]-(int)$ord_cash[$orders[0]]/100)."</b></font> руб.</td><td><u>".$cl_ev." +".$orders[2]." дн.</u> ".$status." ".$days."</td><td align='center'>".$d_plan."</td></tr>".$cl_need;
}









}


$data->rows[0]['cell'] =array("<table width='100%' style='border-collapse: collapse;border-style: solid;border-color: black;' border='1'><tr><td width='50' bgcolor='#F2F2F2'><b>Заявка №</b></td><td width='70' bgcolor='#F2F2F2'><b>Ставка клиента</b></td><td width='50' bgcolor='#F2F2F2'><b><font size='2'>Оплачено клиентом</font></b></td><td width='60' bgcolor='#F2F2F2'><font size='2'><b>Клиент должен</font></b></td><td width='350' bgcolor='#F2F2F2'><b><font size='3'>Условия оплаты</font></b></td><td width='100'><b><font size='3'>Плановая дата оплаты</font></b></td></tr>".$cl_need."</table>");

header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
}

?> 

     			
