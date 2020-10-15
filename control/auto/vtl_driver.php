<?php
// Подключение и выбор БД
include "../../config.php";	

if ($_GET['mode']=='driver') 
{
    if (!isset($data)) $data = new stdClass();

    $page = (int)$_GET['page'];      // Номер запришиваемой страницы
    $limit = (int)$_GET['rows'];     // Количество запрашиваемых записей
    $sidx = mysql_real_escape_string($_GET['sidx']);      // Номер элемента массива по котору следует производить сортировку // Проще говоря поле, по которому следует производить сортировку
    $sord = mysql_real_escape_string($_GET['sord']);      // Направление сортировки


// Если не указано поле сортировки, то производить сортировку по первому полю
    if (!$sidx) $sidx = 1;







// Выполним запрос, который вернет суммарное кол-во записей в таблице

$result = mysql_query("SELECT COUNT(*) AS count FROM `workers` WHERE `group`='5' AND `delete`='0'");


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

$query = "SELECT `id`,`name`,`zarplata`,`z_day`,`z_city`,`z_repair`,`z_km` FROM `workers` WHERE `group`='5' AND `delete`='0' ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;

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

$auto='-';$dop_auto='-';


$query_car = "SELECT `id`,`name`,`number`,`dop_car` FROM `vtl_auto` WHERE `driver`='".mysql_escape_string($row['id'])."' AND (`type`='1' OR `type`='3')";
$result_car = mysql_query($query_car) or die(mysql_error());
$car = mysql_fetch_row($result_car);

$auto='<b>'.$car[1].'</b><br>('.$car[2].')';



$query_car = "SELECT `id`,`name`,`number` FROM `vtl_auto` WHERE `id`='".mysql_escape_string($car[3])."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car = mysql_fetch_row($result_car);

$dop_auto='<b>'.$car[1].'</b><br>('.$car[2].')';

$query_repair = "SELECT `cash` FROM `vtl_repair` WHERE `driver`='".(int)$row['id']."' AND `delete`='0'";
$result_repair = mysql_query($query_repair) or die(mysql_error());
$cash_repair=0;
while($repair = mysql_fetch_row($result_repair)) {
$cash_repair=(int)$cash_repair+(int)$repair[0];
}

$query_fr = "SELECT `o_cash` FROM `vtl_trip` WHERE `tr_auto` LIKE '%&".(int)$row['id']."'";
$result_fr = mysql_query($query_fr) or die(mysql_error());
$cash_o=0;	
while($fr = mysql_fetch_row($result_fr)) {
$cash_o=(int)$cash_o+(int)$fr[0];
}

if(($cash_o-$cash_repair)<0)$color='red'; else $color='green';




$data->rows[$i]['id'] = $row['id'];
    $data->rows[$i]['cell'] = array($row['id'],'<font size="4">'.$row['name'].'</font>','<font size="4">'.$auto.'</font>','<font size="4">'.$dop_auto.'</font>',$row['zarplata'],$row['z_day'],$row['z_city'],$row['z_repair'],$row['z_km'],'<b><font size="4" color="'.$color.'">'.(($cash_o-$cash_repair)/100).'</font></b> руб.');
    $i++;

}

// Перед выводом не забывайте выставить header
// с типом контента и кодировкой
header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
	
}



if ($_GET['mode']=='delete') 
{
	$id =$_GET['id'];

}

?>