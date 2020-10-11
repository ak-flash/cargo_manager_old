<?php
// Подключение и выбор БД
include "../config.php";	




$page = (int)$_GET['page'];      // Номер запришиваемой страницы
$limit = (int)$_GET['rows'];     // Количество запрашиваемых записей
$sidx = $_GET['sidx'];      // Номер элемента массива по котору следует производить сортировку
                            // Проще говоря поле, по которому следует производить сортировку
$sord = $_GET['sord'];      // Направление сортировки




if ($_GET['mode']=='workers') 
{

// Если не указано поле сортировки, то производить сортировку по первому полю
if(!$sidx) $sidx =1;

// Выполним запрос, который вернет суммарное кол-во записей в таблице
$result = mysql_query("SELECT COUNT(*) AS count FROM `workers` WHERE `delete`='0'");

if($_GET['m']=='managers')$result = mysql_query("SELECT COUNT(*) AS count FROM `workers` WHERE `group`='3' AND `delete`='0'");
if($_GET['m']=='drivers')$result = mysql_query("SELECT COUNT(*) AS count FROM `workers` WHERE `group`='5' AND `delete`='0'");
if($_GET['m']=='arhiv')$result = mysql_query("SELECT COUNT(*) AS count FROM `workers` WHERE `delete`='1'");


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
$query = "SELECT * FROM `workers` WHERE `delete`='0' ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit;


if($_GET['m']=='managers')$query = "SELECT * FROM `workers` WHERE `delete`='0' AND `group`='3' ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit;
if($_GET['m']=='drivers')$query = "SELECT * FROM `workers` WHERE `delete`='0' AND `group`='5' ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit;
if($_GET['m']=='arhiv')$query = "SELECT * FROM `workers` WHERE `delete`='1' ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit;



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

switch ($row['group']) {
case '1': $group='Администратор';$color='red';break;
case '2': $group='Директор';$color='red';break;
case '3': $group='Менеджер';$color='black';break;
case '4': $group='Бухгалтер';$color='green';break;
case '5': $group='Водитель';$color='blue';break;
case '6': $group='Складской работник';$color='green';break;
}

if($row['id']=="51") {$group='Механик';$color='red';}

  if($row['motive']=="0")$motive="";
  if($row['motive']=="1")$motive="";  
  if($row['motive']=="2")$motive="";  
  if($row['motive']=="3")$motive="<br><font size='2'>(по направлению)</font>";  
  if($row['motive']=="4")$motive="<br><font size='2'>(по перевозкам)</font>";  
    
    $data->rows[$i]['id'] = $row['id'];
    $data->rows[$i]['cell'] = array($row['id'],"<b><font size=\"3\">".$row['name']."</font></b>",'<font size="3" color="'.$color.'"><b>'.$group."</b>".$motive."</font>","8 (".$row['pref_phone'].") ".$row['phone'],"<font size=\"4\">".date('d/m/Y',strtotime($row['date_start']))."</font>","<font size=\"4\">".$row['zarplata']."</font> руб.");
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

$query_order= "SELECT * FROM `workers` WHERE `Id` LIKE ".$id;
$result_order = mysql_query($query_order) or die(mysql_error());
if(mysql_num_rows($result_order) >= 1){



	            $i = 0;
            while($row = mysql_fetch_array($result_order)) {
 $w_doc = explode('|',$row['passport']); 

if($row['login']=='')$login="-"; else $login=$row['login'];
if($row['email']=='@vtl-stroy.ru')$email="-"; else $email=$row['email'];
if($row['voip']=='')$voip="-"; else $voip=$row['voip'];
if($row['delete']==1)$del="<a class=\"button\" id=\"btnDelete\" href=\"#\" onclick=\"$('#result').html('Восстановить в должности выбранного сотрудника?');$('#result').dialog({ title: 'Внимание' },{ modal: true },{ resizable: false },{ buttons: [{text: 'Да',click: function() {\$.post('control/workers.php?mode=undelete&id=".$row['id']."', function(data) {jQuery('#table').trigger('reloadGrid');$('#result').dialog('close');$('#result_temp').html(data);$('#result_temp').dialog({ title: 'Внимание' },{ height: 90 },{ width: 400 },{ modal: true },{ resizable: false });});}},{text: 'Нет',click: function() {\$(this).dialog('close');}}] });\">Восстановить</a>"; else $del="<a class=\"button\" id=\"btnDelete\" href=\"#\" onclick=\"$('#result').html('Переместить выбранного сотрудника в Архив?');$('#result').dialog({ title: 'Внимание' },{ modal: true },{ resizable: false },{ buttons: [{text: 'Да',click: function() {\$.post('control/workers.php?mode=delete&id=".$row['id']."', function(data) {jQuery('#table').trigger('reloadGrid');$('#result').dialog('close');$('#result_temp').html(data);$('#result_temp').dialog({ title: 'Внимание' },{ height: 90 },{ width: 400 },{ modal: true },{ resizable: false });});}},{text: 'Нет',click: function() {\$(this).dialog('close');}}] });\">Удалить</a>";
 

if((int)date("m")==1){$month_zarp=12;$year_zarp=(date("Y")-1);} else {$month_zarp=((int)date("m")-1);$year_zarp=date("Y");}


$data->rows[$i]['cell'] =array("<fieldset style=\"width:170px;\"><legend><b>Управление:</b></legend><a class=\"button\" id=\"btnEdit\" href=\"#\" onclick=\"$('#fa_worker').load('add_worker.html?worker_id=".$row['id']."');$('#fa_worker').dialog({ title: 'Редактировать сотрудника №".$row['id']."' },{width: 920,height: 730,modal: true,resizable: false});\">Редактировать</a>".$del."<a class=\"button\" id=\"btnEdit\" href=\"#\" onclick='window.location.href=\"/control/zarplata_manager.php?mode=zarplata&user=".$row['id']."&month=".$month_zarp."&year=".$year_zarp."\";'>Зарплата</a></fieldset><br><fieldset><legend><b>Распечатать:</b></legend><a class=\"button\" id=\"btnAdd_details\" href=\"#\" onclick=\"alert('Нужна эта функция?');\">Данные</a></fieldset>","<fieldset><legend><b>Адрес проживания:</b></legend>".$row['adress']."</fieldset><fieldset><legend><b>Паспортные данные:</b></legend><b>".$w_doc[0]."</b><br>Кем выдан: ".$w_doc[1]." (".$w_doc[2].")"."</fieldset><fieldset style=\"float:left;\"><legend><b>Контакты:</b></legend><table><tr><td><b>Логин:</b></td><td> <b><font size='4'>".$login."</font></b></td></tr><tr><td><b>Телефон:</b></td><td> 8 (".$row['pref_phone'].") ".$row['phone']."</td></tr><tr><td><b>E-mail:</b></td><td> ".$email."</td></tr><tr><td><b>Voip:</b></td><td> ".$voip."</td></tr><tr><td><b>IP адрес:</b></td><td> ".$row['ip']."</td></tr></table></fieldset><fieldset><legend><b>Зарплата:</b></legend><b>Оклад:</b> <font size='5'>".$row['zarplata']."</font> руб. <b>НДФЛ:</b> ".$row['ndfl']." руб.</fieldset><fieldset><legend><b>Примечание:</b></legend>".$row['notify']."</fieldset>");
	                $i++;
	        }
	        }



header("Content-type: text/script;charset=utf-8");
echo json_encode($data);


}




if ($_GET['mode']=='delete') 
{
$id =$_GET['id'];
$query = "UPDATE `workers` SET `delete`='1' WHERE Id='".$id."'";
$result = mysql_query($query) or die(mysql_error());
echo '<font color="red" size="3">Сотрудник перемещён в Архив...</font>';
	
}
if ($_GET['mode']=='undelete') 
{
$id =$_GET['id'];
$query = "UPDATE `workers` SET `delete`='0' WHERE Id='".$id."'";
$result = mysql_query($query) or die(mysql_error());
echo '<font color="red" size="3">Сотрудник восстановлен...</font>';
	
}
//$fh = fopen("somefile.txt", "a+"); 
//fwrite($fh, $id); 
//fclose($fh);
?>