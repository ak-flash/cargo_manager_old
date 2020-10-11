<?php
// Подключение и выбор БД
include "../../config.php";	

if ($_GET['mode']=='repair') 
{


$page = $_GET['page'];      // Номер запришиваемой страницы
$limit = $_GET['rows'];     // Количество запрашиваемых записей
$sidx = $_GET['sidx'];      // Номер элемента массива по котору следует производить сортировку
                            // Проще говоря поле, по которому следует производить сортировку
$sord = $_GET['sord'];      // Направление сортировки


// Если не указано поле сортировки, то производить сортировку по первому полю
if(!$sidx) $sidx =1;







// Выполним запрос, который вернет суммарное кол-во записей в таблице

$result = mysql_query("SELECT COUNT(*) AS count FROM `vtl_repair` WHERE `delete`='0'");


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

$query = "SELECT * FROM `vtl_repair` WHERE `delete`='0' ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;

$result = mysql_query($query) or die(mysql_error());



// Начало формирование массива
// для последующего преобразоования
// в JSON объект
$data->page       = $page;
$data->total      = $total_pages;
$data->records    = $count;

$query_car = "SELECT `id`,`name`,`type`,`number` FROM `vtl_auto` WHERE `delete`='0'";
$result_car = mysql_query($query_car) or die(mysql_error());

while($car = mysql_fetch_row($result_car)) {
switch ($car[2]) {
case '1': $type='Тягач';break;
case '2': $type='Полуприцеп';break;
case '3': $type='Грузовик';break;
case '4': $type='Прицеп';break;

} 

$auto[$car[0]]=$type.'<br><b>'.$car[1].'</b> ('.$car[3].')';
}


	
	
// Строки данных для таблицы
$i = 0;
while($row = mysql_fetch_array($result)) {
switch ($row['area']) {
case '6': $area='Автозапчасти';break;
case '7': $area='Шиномонтаж';break;
case '8': $area='Услуги ремонта';break;
case '11': $area='Инструменты';break;

} 
 
if($row['cash']==0)$cash="-"; else $cash=($row['cash']/100).' руб.';

if($row['driver']!=0){
$query_drv = "SELECT `name` FROM `workers` WHERE `id`='".(int)$row['driver']."'";
$result_drv = mysql_query($query_drv) or die(mysql_error());
$drv = mysql_fetch_row($result_drv);
$pieces = explode(" ", $drv[0]);
$driver=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";	
} else $driver='-';

$data->rows[$i]['id'] = $row['id'];
    $data->rows[$i]['cell'] = array($row['id'],'<font size="4">'.date('d/m/Y',strtotime($row['date'])).'</font>','<font size="3">'.$auto[$row['auto']].'</font>','<font size="3">'.$driver.'</font>','<b>'.$area.'</b>',addslashes($row['details']),$cash);
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

$query_trip = "UPDATE `vtl_repair` SET `delete`='1' WHERE `id`='".(int)$id."'";
$result_trip = mysql_query($query_trip) or die(mysql_error());

echo '<font color="red" size="4">Лист ремонтных работ удалён!</font>';

}


?>