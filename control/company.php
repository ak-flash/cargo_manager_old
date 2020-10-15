<?php
// Подключение и выбор БД
include "../config.php";

if (!isset($data)) $data = new stdClass();

$page = (int)$_GET['page'];      // Номер запришиваемой страницы
$limit = (int)$_GET['rows'];     // Количество запрашиваемых записей
$sidx = mysql_real_escape_string($_GET['sidx']);      // Номер элемента массива по котору следует производить сортировку // Проще говоря поле, по которому следует производить сортировку
$sord = mysql_real_escape_string($_GET['sord']);      // Направление сортировки


if ($_GET['mode'] == 'company') {

// Если не указано поле сортировки, то производить сортировку по первому полю
    if (!$sidx) $sidx = 1;


$result = mysql_query("SELECT COUNT(*) AS count FROM `company` WHERE `delete`='0'");

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

$query = "SELECT * FROM `company` WHERE `delete`='0' ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;
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


switch ($row['pref']) {
case '1': $pref_company='ООО';break;
case '2': $pref_company='ОАО';break;
case '3': $pref_company='ИП';break;
case '4': $pref_company='ЗАО';break;}

switch ($row['nds']) {
case '0': $nds='без НДС';break;
case '1': $nds='с НДС';break;
case '2': $nds='НАЛ';break;
}

if($row['id']!=1){
mb_internal_encoding("UTF-8");


if(@$_GET['search']=='true'){	
if(mb_stristr($row['id'], $_GET['s_data'])||mb_stristr($row['name'], $_GET['s_data'])||mb_stristr($row['chief'], $_GET['s_data']))	{
 $data->rows[$i]['id'] = $row[0];
    $data->rows[$i]['cell'] = array($row['id'],$pref_company,"<b><font size=3>«".$row['name']."»</font></b>",$nds,"<b>".$row['chief']."</b>",$row['chief_status'],"8 (".$row['pref_phone'].") ".$row['phone'],$row['email']);
    $i++;}
} else {

$data->rows[$i]['id'] = $row[0];
    $data->rows[$i]['cell'] = array($row['id'],$pref_company,"<b><font size=3>«".$row['name']."»</font></b>",$nds,"<b>".$row['chief']."</b>",$row['chief_status'],"8 (".$row['pref_phone'].") ".$row['phone'],$row['email']);
    $i++;
}
}


}
// Перед выводом не забывайте выставить header
// с типом контента и кодировкой
    header("Content-type: application/json");
echo json_encode($data);
	
}


if ($_GET['mode']=='desc') 
{
$id =$_GET['id'];



$query_company= "SELECT * FROM `company` WHERE `Id` LIKE ".$id;
$result_company = mysql_query($query_company) or die(mysql_error());
if(mysql_num_rows($result_company) >= 1){



	            $i = 0;
            while($row = mysql_fetch_array($result_company)) {
if($row['id']!=1){         
	              $data->rows[$i]['cell'] =array("<fieldset><legend><b>Компания:</b></legend><a class=\"button\" id=\"btnEdit\" href=\"#\" onclick=\"$('#fa_c_bill').load('show_bill.html?company=".$row['id']."&c_name=".urlencode ($row['name'])."');$('#fa_c_bill').dialog({ title: 'Счета компании №".$row['id']."' },{width: 1100,height: 330,modal: true,resizable: false});\">Счета</a><br><a class=\"button\" id=\"btnEdit\" href=\"#\" onclick=\"$('#fa_company').load('add_company.html?company=".$row['id']."');$('#fa_company').dialog({ title: 'Редактировать компанию №".$row['id']."' },{width: 870,height: 435,modal: true,resizable: false});\">Редактировать</a><br><a class=\"button\" id=\"btnDelete\" href=\"#\" onclick=\"$('#result').html('Удалить выбранную компанию?');$('#result').dialog({ title: 'Внимание' },{ modal: true },{ resizable: false },{ buttons: [{text: 'Да',click: function() {\$.post('control/company.php?mode=delete&id=".$row['id']."', function(data) {jQuery('#table').trigger('reloadGrid');$('#result').dialog('close');$('#result_temp').html(data);$('#result_temp').dialog({ title: 'Внимание'},{ modal: true },{ resizable: false },{ buttons: { 'Ok': function() { $(this).dialog('close');}}});});}},{text: 'Нет',click: function() {\$(this).dialog('close');}}] });\" >Удалить</a></fieldset><br><fieldset><legend><b>Распечатать:</b></legend><a class=\"button\" id=\"btnAdd_details\" href=\"#\" onclick=\"window.location.href='control/print_c.php?mode=cl&id=".base64_encode($row['id'])."';\">Реквизиты</a></fieldset>","<fieldset><legend><b>Адрес (фактич.):</b></legend>".$row['adr_f']."</fieldset><fieldset><legend><b>Адрес (юридич.):</b></legend>".$row['adr_u']."</fieldset><fieldset style=\"width:450px;float:left;margin-right:10px;\"><legend><b>Реквизиты:</b></legend><table><tr><td align=\"right\"><b>ИНН:</b></td><td> ".$row['inn']."</td></tr><tr><td><b>КПП:</b></td><td>".$row['kpp']."</td></tr><tr><td><b>ОГРН:</b></td><td> ".$row['ogrn']."</td></tr><tr><td><b>р/сч:</b></td><td>".$row['rs']."</td></tr><tr><td><b>Банк:</b></td><td> ".$row['bank']."</td></tr><tr><td><b>БИК:</b></td><td>".$row['bik']."</td></tr><tr><td><b>к/с:</b></td><td> ".$row['ks']."</td></tr></table></fieldset><fieldset><legend><b>Контакты:</b></legend><b>Ответственное лицо:</b> ".$row['chief']."<br><b>Должность ответственного лица:</b> ".$row['chief_status']."<br><b>Действует на основании:</b> ".$row['ochief_contract']."</fieldset><fieldset><legend><b>Примечание:</b></legend>".$row['notify']."</fieldset>");
	                $i++;
	        
}
	        }
	        }

    header("Content-type: application/json");
echo json_encode($data);


}


if ($_GET['mode']=='delete') 
{
$id =$_GET['id'];

$query = "UPDATE `company` SET `delete`='1' WHERE Id='".$id."'";
$result = mysql_query($query) or die(mysql_error());
echo '<font color="red" size="3">Компания помечена на удаление!</font>';

}
?>