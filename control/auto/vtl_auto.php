<?php
// Подключение и выбор БД
include "../../config.php";	

if ($_GET['mode']=='auto') 
{


$page = $_GET['page'];      // Номер запришиваемой страницы
$limit = $_GET['rows'];     // Количество запрашиваемых записей
$sidx = $_GET['sidx'];      // Номер элемента массива по котору следует производить сортировку
                            // Проще говоря поле, по которому следует производить сортировку
$sord = $_GET['sord'];      // Направление сортировки


// Если не указано поле сортировки, то производить сортировку по первому полю
if(!$sidx) $sidx =1;







// Выполним запрос, который вернет суммарное кол-во записей в таблице

$result = mysql_query("SELECT COUNT(*) AS count FROM `vtl_auto`");


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

$query = "SELECT * FROM `vtl_auto` ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;

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

switch ($row['type']) {
case '1': $type='Тягач';break;
case '2': $type='Полу/прицеп';break;
case '3': $type='Грузовик';break;
case '4': $type='Прицеп';break;

} 


$query_trip = "SELECT `end_petrol` FROM `vtl_trip` WHERE `tr_auto` LIKE '".(int)$row['id']."&%' AND `delete`='0' ORDER BY `data` ASC";
$result_trip = mysql_query($query_trip) or die(mysql_error());
$trip = mysql_fetch_row($result_trip); 


if($trip[0]==0)$petrol="-"; else $petrol='<b>'.$trip[0].' л</b>';

if($row['lpk']==0)$lpk="-"; else $lpk=$row['lpk'].' л';
if($row['v']==0)$v="-"; else $v=$row['v'].' м3';
if($row['driver']==0)$driver="-"; else {
$query_drv = "SELECT `name` FROM `workers` WHERE `id`='".mysql_escape_string($row['driver'])."' and `delete`='0'";
$result_drv = mysql_query($query_drv) or die(mysql_error());
$user= mysql_fetch_row($result_drv);
$pieces = explode(" ", $user[0]);
$print_add_name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
$driver=$print_add_name;
}

if($row['date_s']!='0000-00-00'&&$row['date_s']!='1970-01-01')$date_s=date('d/m/Y',strtotime($row['date_s'])); else $date_s='-';
if($row['date_to']!='0000-00-00'&&$row['date_to']!='1970-01-01')$date_to=date('d/m/Y',strtotime($row['date_to'])); else $date_to='-';

$data->rows[$i]['id'] = $row['id'];
    $data->rows[$i]['cell'] = array($row['id'],'<b><font size="3">'.$type.'</b></font>','<font size="3">'.$row['name'].'</font>','<b><font size="3">'.$row['number'].'</font></b>','<b>'.$driver.'</b>',date('d/m/Y',strtotime($row['date_ve'])),$date_to,$date_s,$lpk,$petrol,$v);
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
$query = "DELETE FROM `vtl_auto` WHERE `Id`='".(int)$id."'";
$result = mysql_query($query) or die(mysql_error());

echo '<font color="red" size="3">Транспорт удалён!</font>';
}

?>