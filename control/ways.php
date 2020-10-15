<?php
// Подключение и выбор БД
include "../config.php";


# ВНИМАНИЕ!!!
# Данный код не имеет проверок запрашиваемых данных
# что может стать причиной взлома! Обязательно проверяйте все данные
# поступающие от клиента
if (!isset($data)) $data = new stdClass();

$page = (int)$_GET['page'];      // Номер запришиваемой страницы
$limit = (int)$_GET['rows'];     // Количество запрашиваемых записей
$sidx = mysql_real_escape_string($_GET['sidx']);      // Номер элемента массива по котору следует производить сортировку // Проще говоря поле, по которому следует производить сортировку
$sord = mysql_real_escape_string($_GET['sord']);      // Направление сортировки


// Если не указано поле сортировки, то производить сортировку по первому полю
if (!$sidx) $sidx = 1;

if ($_GET['mode'] == 'ways') {





// Выполним запрос, который вернет суммарное кол-во записей в таблице

if($_GET["tr_id"]==""){$result = mysql_query("SELECT COUNT(*) AS count FROM `ways`");} else {$result = mysql_query("SELECT COUNT(*) AS count FROM `ways` WHERE `tr`='".mysql_escape_string($_GET["tr_id"])."'");}


$row = mysql_fetch_array($result,MYSQL_ASSOC);
$count = $row['count'];     // Теперь эта переменная хранит кол-во записей в таблице


if(@$_GET['search']=='true'){
mb_internal_encoding("UTF-8");
$g=0;

$query = "SELECT * FROM `ways` WHERE `cl`='0'";
$result = mysql_query($query) or die(mysql_error());

while($row = mysql_fetch_array($result)) {	



if(@$_GET['s_data_in']!=""&&@$_GET['s_data_out']!=""){
if(mb_stristr($row['in_city'], $_GET['s_data_in'])&&mb_stristr($row['out_city'], $_GET['s_data_out']))	{
$id_mass[$g]=$row['id'];$g++;}
}

if(@$_GET['s_data_in']!=""&&@$_GET['s_data_out']==""){
if(mb_stristr($row['in_city'], $_GET['s_data_in']))	{
$id_mass[$g]=$row['id'];$g++;}
}

if(@$_GET['s_data_in']==""&&@$_GET['s_data_out']!=""){
if(mb_stristr($row['out_city'], $_GET['s_data_out']))	{
$id_mass[$g]=$row['id'];$g++;}
}	
	
}
$count=$g;



}

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
if($id_mass){
$query = "SELECT * FROM `ways` WHERE `Id` IN (".implode(',' , $id_mass).") ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;

} else {if($_GET["tr_id"]!=""){$query = "SELECT * FROM `ways` WHERE `tr`='".mysql_escape_string($_GET["tr_id"])."' AND `cl`='0' ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;} else {$query = "SELECT * FROM `ways` WHERE `cl`='0' ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;}}

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

if($row['tr']!=0){$query_way = "SELECT `name`,`pref` FROM `transporters` WHERE `id`='".mysql_escape_string($row['tr'])."'";
$result_way = mysql_query($query_way) or die(mysql_error());
$row_info = mysql_fetch_array($result_way);
}


switch ($row['in_load']) {
case '1': $in_load='верхняя';break;
case '2': $in_load='задняя';break;
case '3': $in_load='боковая';break;
} 

switch ($row['out_load']) {
case '1': $out_load='верхняя';break;
case '2': $out_load='задняя';break;
case '3': $out_load='боковая';break;
}

switch ($row_info[1]) {
case '1': $pref_info='ООО';break;
case '2': $pref_info='ОАО';break;
case '3': $pref_info='ИП';break;
case '4': $pref_info='ЗАО';break;
case '5': $pref_info='';break;
case '6': $pref_info='';break;}







$data->rows[$i]['id'] = $row[0];
    $data->rows[$i]['cell'] = array($row['id'],$pref_info.' <b>«'.$row_info[0].'»</b>',$row['in_city'],$row['out_city'],$row['times']." раз(а) в месяц",$in_load,$out_load,$row['block']);
    $i++;

}

// Перед выводом не забывайте выставить header
// с типом контента и кодировкой
header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
	
}


if ($_GET['mode']=='desc') 
{
$id =$_GET['id'];



$query_order= "SELECT * FROM `ways` WHERE `Id`='".mysql_escape_string($id)."'";
$result_order = mysql_query($query_order) or die(mysql_error());
if(mysql_num_rows($result_order) >= 1){
	


	            $i = 0;
            while($row = mysql_fetch_array($result_order)) {
            	
$query_car= "SELECT * FROM `tr_autopark` WHERE `Id`='".mysql_escape_string($row['auto_id'])."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car_info = mysql_fetch_row($result_car); 
 switch ($car_info[6]) {
case '1': $car_load='верхняя';break;
case '2': $car_load='задняя';break;
case '3': $car_load='боковая';break;
case '4': $car_load='верхняя,задняя';break;
case '5': $car_load='задняя,боковая';break;
case '6': $car_load='верхняя,боковая';break;
case '7': $car_load='верхняя,боковая,задняя';break;
} 


$query_way = "SELECT `tr_support`,`tr_pref_phone`,`tr_phone`,`tr_dsupport`,`tr_mail`,`tr_notify` FROM `transporters` WHERE `id`='".mysql_escape_string($row['tr'])."'";
$result_way = mysql_query($query_way) or die(mysql_error());
$row_info = mysql_fetch_array($result_way);
        
	              $data->rows[$i]['cell'] =array("<a class=\"button\" id=\"btnEdit\" href=\"#\" onclick=\"$('#fa_way').load('add_way.html?way_id=".$row['id']."');$('#fa_way').dialog({ title: 'Редактировать направление №".$row['id']."' },{width: 530,height: 330,modal: true,resizable: false});\">Редактировать</a><br><br><img src=\"data/img/lorrygreen.png\">",'<fieldset><legend><b>Перевозчик:</b></legend>Контактное лицо: <font size="4"><b>'.$row_info[3]." - ".$row_info[0].'</b></font><br>Номер телефона: <b><font size="4">8 ('.$row_info[1].') '.$row_info[2].'</b></font><br>E-mail: <b><font size="4">'.$row_info[4].'</font></b>	</fieldset><fieldset><legend><b>Автотранспорт:</b></legend>Марка: <b>'.$car_info[2].'</b> [Г/н: <b>'.$car_info[3].'</b>] ('.$car_info[4].' т; '.$car_info[5].' м3; загрузка: <b>'.$car_load.'</b>; тип кузова: <b>'.$car_info[12].'</b>)<br>Контактные данные водителя: <b><font size="4">'.$car_info[9].'</b> ('.$car_info[11].')</font></fieldset><fieldset><legend><b>Примечание:</b></legend>'.$row_info[5].'</fieldset>');
	                $i++;
	        }
	        }

header("Content-type: text/script;charset=utf-8");
echo json_encode($data);


}

?>