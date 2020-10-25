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


if ($_GET['mode'] == 'adress') {

// Если не указано поле сортировки, то производить сортировку по первому полю
    if (!$sidx) $sidx = 1;


$result = mysql_query("SELECT COUNT(*) AS count FROM `adress`");

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
if ($_GET['mode_type']!="") 
{$mode=(int)$_GET['mode_type'];
$query = "SELECT * FROM `adress` WHERE `delete`='0' AND `adr_mode`='".$mode."' ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;} else {$query = "SELECT * FROM `adress` WHERE `delete`='0' ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;} 

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
if($row['postcode']=="0") $postcode="-"; else $postcode=$row['postcode'];	
if($row['flat']=="0") $flat="-"; else $flat=$row['flat'];
if($row['dom_extra']=="0"||$row['dom_extra']=="") $dom_extra=""; else $dom_extra="/".$row['dom_extra'];
if($row['dom']=="0") $dom="-"; else $dom=$row['dom'];
if($row['street']=="По ТТН"||$row['street']=="") $street="По ТТН"; else $street=$row['street'];

mb_internal_encoding("UTF-8");

if(@$_GET['search']=='true'){	
if(mb_stristr($row['id'], $_GET['s_data'])||mb_stristr($row['city'], $_GET['s_data'])||mb_stristr($row['street'], $_GET['s_data']))	{
 $data->rows[$i]['id'] = $row[0];
    $data->rows[$i]['cell'] = array($row['id'],$postcode,$row['country'],$row['obl'],$row['city'],$street,$dom.$dom_extra,$flat,$row['block'],$row['adr_mode']);
    $i++;}
} else {

$data->rows[$i]['id'] = $row[0];
    $data->rows[$i]['cell'] = array($row['id'],$postcode,$row['country'],$row['obl'],'<b>'.$row['city'].'</b>',$street,$dom.$dom_extra,$flat,$row['block'],$row['adr_mode']);
    $i++;
}
}

// Перед выводом не забывайте выставить header
// с типом контента и кодировкой
header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
	
}


if ($_GET['mode']=='desc') 
{
$id =$_GET['id'];



$query_order= "SELECT * FROM `adress` WHERE `Id` LIKE ".$id;
$result_order = mysql_query($query_order) or die(mysql_error());
if(mysql_num_rows($result_order) >= 1){
	


	            $i = 0;
            while($row = mysql_fetch_array($result_order)) {

                if ($row['adr_mode'] == 1 || $row['adr_mode'] == 2) {
                    $place = "<b>Место выгрузки/загрузки:</b> <font size=5>" . stripslashes($row['adr_place']) . "</font><br><b>Контактное лицо:</b> <font size=4>" . stripslashes($row['contact_name']) . "</font><br><b>Номер телефона:</b> <font size=4>" . stripslashes($row['contact_phone']) . "</font>";
                }


                $data->rows[$i]['cell'] = array("<a class=\"button\" id=\"btnEdit\" href=\"#\" onclick=\"$('#fa_adr').load('theme/forms/add_adr.php?adr_id=" . $row['id'] . "');$('#fa_adr').dialog({ title: 'Редактировать адрес №" . $row['id'] . "' },{width: 470,height: 650,modal: true,resizable: false});\">Редактировать</a></fieldset><br><a class=\"button\" id=\"btnDelete\" href=\"#\" onclick=\"$('#result').html('Удалить выбранный адрес?');$('#result').dialog({ title: 'Внимание' },{ modal: true },{ resizable: false },{ buttons: [{text: 'Да',click: function() {\$.post('control/adress.php?mode=delete&id=" . $row['id'] . "', function(data) {jQuery('#table').trigger('reloadGrid');$('#result').dialog('close');$('#result_temp').html(data);$('#result_temp').dialog({ title: 'Внимание'},{ modal: true },{ resizable: false },{ buttons: { 'Ok': function() { $(this).dialog('close');}}});});}},{text: 'Нет',click: function() {\$(this).dialog('close');}}] });\">Удалить</a></fieldset>", $place);
                $i++;
            }
	        }

header("Content-type: text/script;charset=utf-8");
echo json_encode($data);


}

if ($_GET['mode']=='delete') 
{
$id =$_GET['id'];
$query = "SELECT `block` FROM `adress` WHERE Id='".$id."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_row($result);

if ($row[0]=='1'){echo '<font color="red" size="3"><div alegn="center">Адрес заблокирован! Обратитесь к директору...</div></font>';}
else {$query = "UPDATE `adress` SET `delete`='1' WHERE Id='".$id."'";
$result = mysql_query($query) or die(mysql_error());
echo '<font color="red" size="3">Адрес помечен на удаление!</font>';	}

}
?>