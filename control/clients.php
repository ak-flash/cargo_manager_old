<?php
// Подключение и выбор БД
include "../config.php";
session_start();

# ВНИМАНИЕ!!!
# Данный код не имеет проверок запрашиваемых данных
# что может стать причиной взлома! Обязательно проверяйте все данные
# поступающие от клиента
if (!isset($data)) $data = new stdClass();

$page = (int)$_GET['page'];      // Номер запришиваемой страницы
$limit = (int)$_GET['rows'];     // Количество запрашиваемых записей
$sidx = mysql_real_escape_string($_GET['sidx']);      // Номер элемента массива по котору следует производить сортировку // Проще говоря поле, по которому следует производить сортировку
$sord = mysql_real_escape_string($_GET['sord']);      // Направление сортировки


if ($_GET['mode'] == 'clients') {
    $manager = $_SESSION["user_id"];
    $group = $_SESSION["group"];

// Если не указано поле сортировки, то производить сортировку по первому полю
if(!$sidx) $sidx =1;

	// Managers can see only their clients - now disabled
	//if ($group=='3') $result = mysql_query("SELECT COUNT(*) AS count FROM `clients` WHERE `cl_manager`='$manager'"); else 

	$result = mysql_query("SELECT COUNT(*) AS count FROM `clients`");

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
if(@$_GET['search']=='true'){
mb_internal_encoding("UTF-8");
$g=0;

$query_clients = "SELECT `id` FROM `clients` WHERE `name` LIKE '%".mysql_escape_string($_GET['s_data'])."%' OR `id` LIKE '%".mysql_escape_string($_GET['s_data'])."%'";
$result_clients = mysql_query($query_clients) or die(mysql_error());

while($clients = mysql_fetch_row($result_clients)) {
$id_mass[$g]=$clients[0];
$g++;
}

if($id_mass){
$count=$g;



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

$query = "SELECT * FROM `clients` WHERE `id` IN (".implode(',' , $id_mass).") ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;



} else {

	// Managers can see only their clients - now disabled
	//if ($group=='3') $query = "SELECT * FROM `clients` WHERE `cl_manager`='$manager' ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit; else 
	$query = "SELECT * FROM `clients` ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;
}


} else {

	// Managers can see only their clients - now disabled
	//if ($group=='3') $query = "SELECT * FROM `clients` WHERE `cl_manager`='$manager' ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit; else 
	$query = "SELECT * FROM `clients` ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;
}

$result = mysql_query($query) or die(mysql_error());


// Начало формирование массива
// для последующего преобразоования
// в JSON объект



$query_user = "SELECT `id`,`name` FROM `workers`";
$result_user = mysql_query($query_user) or die(mysql_error());
while($users= mysql_fetch_row($result_user)) {
$managers[$users[0]]= $users[1];
}


	
// Строки данных для таблицы
$i = 0;
while($row = mysql_fetch_array($result)) {


switch ($row['pref']) {
case '1': $pref_cl='ООО';break;
case '2': $pref_cl='ОАО';break;
case '3': $pref_cl='ИП';break;
case '4': $pref_cl='ЗАО';break;
case '5': $pref_cl='';break;
case '6': $pref_cl='Физ.Л.';break;
case '7': $pref_cl='АО';break;
}

switch ($row['nds']) {
case '0': $nds_cl='без НДС';break;
case '1': $nds_cl='с НДС';break;
case '2': $nds_cl='НАЛ';break;
}



$data->rows[$i]['id'] = $row[0];
    $data->rows[$i]['cell'] = array($row['id'],$pref_cl,"<b>«".$row['name']."»</b>",$nds_cl,$managers[$row['cl_manager']],$row['cl_support'],"8 (".$row['cl_pref_phone'].") ".$row['cl_phone'],$row['block']);
    $i++;

}

$data->page       = $page;
$data->total      = $total_pages;
$data->records    = $count;

// Перед выводом не забывайте выставить header
// с типом контента и кодировкой
header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
	
}


if ($_GET['mode']=='desc') 
{
$id =$_GET['id'];

$query_cont = "SELECT `id`,`name` FROM `company`";
$result_cont = mysql_query($query_cont) or die(mysql_error());

while($cont = mysql_fetch_row($result_cont)) {
$cl_cont[$cont[0]]= $cont[1];
}


$query_order= "SELECT * FROM `clients` WHERE `Id` LIKE ".$id;
$result_order = mysql_query($query_order) or die(mysql_error());
if(mysql_num_rows($result_order) >= 1){



	            $i = 0;
            while($row = mysql_fetch_array($result_order)) {
 $query_adress = "SELECT * FROM `adress` WHERE `id`='".$row['cl_adr_f']."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());
$adress_f = mysql_fetch_row($result_adress);

$query_adress = "SELECT * FROM `adress` WHERE `id`='".$row['cl_adr_u']."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());
$adress_u = mysql_fetch_row($result_adress);
           	
 switch ($row['cl_order']) {
case '0': $cl_order='не указан';break;
case '1': $cl_order='e-mail';break;
case '2': $cl_order='телефон';break;
}  

 switch ($row['cl_point']) {
case '1': $cl_point='Загрузка';break;
case '2': $cl_point='Выгрузка';break;
case '3': $cl_point='Поступление факсимильных документов';break;
case '4': $cl_point='Поступление оригинальных документов';break;
}  
 
if($adress_f[8]=="0") $flat_f=""; else $flat_f=" - ".$adress_f[8];
if($adress_u[8]=="0") $flat_u=""; else $flat_u=" - ".$adress_u[8];
if($adress_u[1]=="0") $post_u=""; else $post_u=$adress_u[1];
 if($adress_f[1]=="0") $post_f=""; else $post_f=$adress_f[1]; 

        
	              $data->rows[$i]['cell'] =array("<fieldset style=\"width:170px;\"><legend><b>Клиент:</b></legend><a class=\"button\" id=\"btnEdit\" href=\"javascript:;\" onclick=\"$('#fa_cl').load('theme/forms/add_client.php?client=".$row['id']."');$('#fa_cl').dialog({ title: 'Редактировать клиента №".$row['id']."' },{width: 990,height: 560,modal: true,resizable: false});\">Редактировать</a></fieldset><br><fieldset style=\"width:170px;\"><legend><b>Распечатать:</b></legend><a class=\"button\" id=\"btnAdd_details\" href=\"javascript:;\" onclick=\"window.location.href='control/print_c.php?mode=cl&id=".base64_encode($row['id'])."';\">Договор</a><a class=\"button\" id=\"btnAdd_details\" href=\"".$row['id']."\">Реквизиты</a></fieldset><fieldset style=\"width:170px;\"><legend><b>Направления:</b></legend><a class=\"button\" id=\"btnShowWay\" href=\"ways_cl.html?cl_id=".$row['id']."&m=true\">Показать</a></fieldset><br><img src=\"data/img/coins.png\">","<fieldset><legend><b>Адрес (фактич.):</b></legend><div style='padding: 5px;margin: 5px;margin-left: 20px;background: #ddd;border: 1px solid #bbb;width: 90%;'>".$post_f.' '.$adress_f[2].' '.$adress_f[3].' обл. <b>'.$adress_f[4].'</b> ул.'.$adress_f[5].' д. '.$adress_f[6].' '.$adress_f[7].$flat_f."</div></fieldset><fieldset><legend><b>Адрес (юридич.):</b></legend><div style='padding: 5px;margin: 5px;margin-left: 20px;background: #ddd;border: 1px solid #bbb;width: 90%;'>".$post_u.' '.$adress_u[2].' '.$adress_u[3].' обл. <b>'.$adress_u[4].'</b> ул.'.$adress_u[5].' д. '.$adress_u[6].' '.$adress_u[7].$flat_u."</div></fieldset><fieldset style=\"width:380px;float:left;margin-right:10px;\"><legend><b>Реквизиты:</b></legend><table><tr><td align=\"right\" width=\"60\"><b>ИНН:</b></td><td> ".$row['cl_inn']."</td></tr><tr><td align=\"right\"><b>КПП:</b></td><td>".$row['cl_kpp']."</td></tr><tr><td align=\"right\"><b>ОГРН:</b></td><td> ".$row['cl_ogrn']."</td></tr><tr><td align=\"right\"><b>р/сч:</b></td><td>".$row['cl_rs']."</td></tr><tr><td align=\"right\"><b>Банк в :</b></td><td> ".$row['cl_bank']."</td></tr><tr><td align=\"right\"><b>БИК:</b></td><td>".$row['cl_bik']."</td></tr><tr><td align=\"right\"><b>к/с:</b></td><td> ".$row['cl_ks']."</td></tr></table></fieldset><fieldset style=\"width:450px;float:right;\"><legend><b>Информация:</b></legend><b>Контактер:</b> <font size=\"4\">".$cl_cont[$row['cl_cont']]."</font><br><b>Период расчётов:</b> ".$row['cl_time']." дн.<br><b>Точка оплаты:</b> ".$cl_point."<br><b>Способ получения заказов:</b> ".$cl_order."<br><br><div style='font-size:18px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Лимит:</b> <b>".(int)$row['cl_limit']."</b> руб.&nbsp;(<b>".(int)$row['cl_limit_order']."</b> шт)</div></fieldset><fieldset style=\"width:450px;float:right;margin-top:-5px;\"><legend><b>Контакты:</b></legend><b>Ответственное лицо:</b> ".$row['cl_chief']."<br><b>Должность ответственного лица:</b> ".$row['cl_dchief']."<br><b>Основание:</b> ".$row['cl_ochief']."<br><hr style=\"width: 100%; height: 2px;\" /><b>Контактное лицо:</b> ".$row['cl_support']."<br><b>Должность контактного лица:</b> ".$row['cl_dsupport']."<br><b>E-mail:</b> ".$row['cl_mail']."<br><b>ICQ:</b> ".$row['cl_icq']."</fieldset><fieldset><legend><b>Примечание:</b></legend>".$row['notify']."</fieldset>");
	                $i++;
	        }
	        }

header("Content-type: text/script;charset=utf-8");
echo json_encode($data);


}

?>