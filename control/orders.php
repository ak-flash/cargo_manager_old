<?php
// Подключение и выбор БД
include "../config.php";
include "komissia.php";

session_start();

if (!isset($data)) $data = new stdClass();

$page = (int)$_GET['page'];      // Номер запришиваемой страницы
$limit = (int)$_GET['rows'];     // Количество запрашиваемых записей
$sidx = mysql_real_escape_string($_GET['sidx']);      // Номер элемента массива по котору следует производить сортировку // Проще говоря поле, по которому следует производить сортировку
$sord = mysql_real_escape_string($_GET['sord']);      // Направление сортировки

$month = 0;

if (isset($_GET['international'])) {
    if (@$_GET['international'] == 0) $international = " WHERE international_number='0'";
    if (@$_GET['international'] == 1) $international = " WHERE international_number!='0'";
} else $international = '';

if (isset($_GET['date_start'])) {
    $start_elements = explode("/", $_GET['date_start']);
if ($start_elements[0]=='01'){$month=1;}
$date_start=$start_elements[2]."-".$start_elements[1]."-".$start_elements[0];
$end_elements  = explode("/",$_GET['date_end']);
$date_end=$end_elements[2]."-".$end_elements[1]."-".$end_elements[0];}




$manager = $_SESSION["user_id"];
$group = $_SESSION["group"];

if ($_GET['mode']=='orders') 
{

// Если не указано поле сортировки, то производить сортировку по первому полю
if(!$sidx) $sidx =1;


if (isset($date_start)){
$start_elements  = explode("/",$_GET['date_start']);
$date_start=date("Y-m-d",strtotime($start_elements[2]."-".$start_elements[1]."-".$start_elements[0]));
$end_elements  = explode("/",$_GET['date_end']);
$date_end=date("Y-m-d",strtotime($end_elements[2]."-".$end_elements[1]."-".$end_elements[0]));


// Managers can see only their clients - now disabled
//if ($group=='3') { $result = mysql_query("SELECT COUNT(*) AS count FROM `orders` WHERE (`manager`='".$manager."' OR `tr_manager`='".$manager."'".$dop_query.") AND DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."'");} else 
	if($international=='') {
		$extra = "WHERE DATE(data) BETWEEN '".$date_start."' AND '".$date_end."'";
	} else {
		$extra = " AND DATE(data) BETWEEN '".$date_start."' AND '".$date_end."'";
	}

	$result = mysql_query("SELECT COUNT(*) AS count FROM orders ".$international.$extra);

} else 
{
// Managers can see only their clients - now disabled	
//if ($group=='3') {$result = mysql_query("SELECT COUNT(*) AS count FROM `orders` WHERE (`manager`='".$manager."' OR `tr_manager`='".$manager."'".$dop_query.")");} else 
$result = mysql_query("SELECT COUNT(*) AS count FROM orders ".$international);

}

if(@$_GET['showdel']=='true') $result = mysql_query("SELECT COUNT(*) AS count FROM `deleted_orders`");
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




$query_user = "SELECT `id`,`name` FROM `workers`";
$result_user = mysql_query($query_user) or die(mysql_error());



while($user = mysql_fetch_row($result_user)) {
$pieces = explode(" ", $user[1]);
$managers=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
$users[$user[0]]= $managers;
}


if(@$_GET['search']=='true'){
mb_internal_encoding("UTF-8");
$g=0;

$query_clients = "SELECT `id` FROM `clients` WHERE `name` LIKE '%".mysql_escape_string($_GET['s_data'])."%'";
$result_clients = mysql_query($query_clients) or die(mysql_error());
$client=array();
while($clients = mysql_fetch_row($result_clients)) {
$client[]= $clients[0];
}

$query_tr = "SELECT `id` FROM `transporters` WHERE `name` LIKE '%".mysql_escape_string($_GET['s_data'])."%'";
$result_tr = mysql_query($query_tr) or die(mysql_error());
$transporters=array();
while($tr = mysql_fetch_row($result_tr)) {
$transporters[]= $tr[0];
}

$query_tr_inn = "SELECT `id` FROM `transporters` WHERE `tr_inn` LIKE '%".mysql_escape_string($_GET['s_data'])."%'";
$result_tr_inn = mysql_query($query_tr_inn) or die(mysql_error());
$tr_inn=array();
while($tr_i = mysql_fetch_row($result_tr_inn)) {
$tr_inn[]= $tr_i[0];
}

$query_companys = "SELECT `id` FROM `company` WHERE `name` LIKE '%".mysql_escape_string($_GET['s_data'])."%'";
$result_companys = mysql_query($query_companys) or die(mysql_error());
$company_search=array();
while($companys = mysql_fetch_row($result_companys)) {
$company_search[]= $companys[0];
}

$query_tr_drv = "SELECT `id` FROM `tr_autopark` WHERE `car_driver_name` LIKE '%".mysql_escape_string($_GET['s_data'])."%'";
$result_tr_drv = mysql_query($query_tr_drv) or die(mysql_error());
$tr_drv=array();
while($tr_driver = mysql_fetch_row($result_tr_drv)) {
$tr_drv[]= $tr_driver[0];
}

$query_tr_drv_vtl = "SELECT `id` FROM `workers` WHERE `name` LIKE '%".mysql_escape_string($_GET['s_data'])."%' AND `group`='5'";
$result_tr_drv_vtl = mysql_query($query_tr_drv_vtl) or die(mysql_error());
$tr_drv_vtl=array();
while($tr_driver_vtl = mysql_fetch_row($result_tr_drv_vtl)) {
$tr_drv_vtl[]= $tr_driver_vtl[0];
}




$query_adress = "SELECT `id` FROM `adress` WHERE `city` LIKE '%".mysql_escape_string($_GET['s_data'])."%'";
$result_adress = mysql_query($query_adress) or die(mysql_error());
$adresses=array();
while($adress = mysql_fetch_row($result_adress)) {
$adresses[]= $adress[0];
}

// Managers can see only their clients - now disabled
//if ($group=='3') {$query_search = "SELECT `id`,`client`,`notify`,`in_adress`,`out_adress`,`transp`,`tr_auto`,`tr_cont`,`agat_number` FROM `orders` WHERE (`manager`='".$manager."' OR `tr_manager`='".$manager."'".$dop_query.")";} else 

$query_search = "SELECT `id`,`client`,`notify`,`in_adress`,`out_adress`,`transp`,`tr_auto`,`tr_cont`,`international_number` FROM `orders`";
$result_search = mysql_query($query_search) or die(mysql_error());

while($row = mysql_fetch_array($result_search)) {	
$str_in = explode('&',$row['in_adress']);
$str_adr_in = (int)sizeof($str_in)-2;
$res_in = $str_in[$str_adr_in];

$str_out = explode('&',$row['out_adress']);
$res_out = $str_out[0];


if($row['transp']=='2') $str_auto_vtl = explode('&',$row['tr_auto']); else $str_auto_vtl ="";


if(mb_stristr($row['id'], $_GET['s_data'])||mb_stristr($row['international_number'], $_GET['s_data'])||mb_stristr($row['notify'], $_GET['s_data'])||in_array($row['client'], $client)||in_array($row['transp'], $transporters)||in_array($row['transp'], $tr_inn)||in_array($row['tr_auto'], $tr_drv)||in_array((int)$str_auto_vtl[2], $tr_drv_vtl)||in_array($res_in, $adresses)||in_array($res_out, $adresses)||in_array($row['tr_cont'], $company_search))	{
$id_mass[$g]=$row['id'];$g++;}
}
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

$row=0;

if($id_mass){
	
// Managers can see only their clients - now disabled	
//if ($group=='3') {$query = "SELECT * FROM `orders` WHERE (`manager`='".$manager."' OR `tr_manager`='".$manager."'".$dop_query.") AND `Id` IN (".implode(',' , $id_mass).") ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit;} else 
$query = "SELECT * FROM orders WHERE id IN (".implode(',',$id_mass).") ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;

} else {$query = "SELECT * FROM orders WHERE id=0";}

}
else
{
	if (isset($date_start)&&isset($date_end)){

// Managers can see only their clients - now disabled
//if ($group=='3') {$query = "SELECT * FROM `orders` WHERE (`manager`='".$manager."' OR `tr_manager`='".$manager."'".$dop_query.") AND DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit;} else 

if($international=='') {
	$extra = "WHERE DATE(data) BETWEEN '".$date_start."' AND '".$date_end."'";
} else {
	$extra = " AND DATE(data) BETWEEN '".$date_start."' AND ".$date_end."'";
}

$query = "SELECT * FROM orders ".$international.$extra." ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;

} else {

// Managers can see only their clients - now disabled	
//if ($group=='3') {$query = "SELECT * FROM `orders` WHERE (`manager`='".$manager."' OR `tr_manager`='".$manager."'".$dop_query.") ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit;} else 
$query = "SELECT * FROM orders ".$international." ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;

if(@$_GET['showdel']=='true'){
	$query = "SELECT * FROM `deleted_orders` ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;
}

}

if (@$_GET["order_id"]!='') {
    
	// Managers can see only their clients - now disabled
    //if($_SESSION["group"]==1) {  
    
	$query = "SELECT * FROM orders WHERE id=".$_GET["order_id"]." ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;

    //} else $query = "SELECT * FROM `orders` WHERE `id`='".$_GET["order_id"]."' AND (`manager`='".$manager."' OR `tr_manager`='".$manager."') ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit;

}


}
    $result = mysql_query($query) or die(mysql_error());
// Начало формирование массива
// для последующего преобразоования
// в JSON объект
    $data->page = $page;
    $data->total = $total_pages;
    $data->records = $count;


// Строки данных для таблицы
    $i = 0;
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {



	
$str_in = explode('&',$row['in_adress']);
$str_adr_in = (int)sizeof($str_in)-2;
$res_in = $str_in[$str_adr_in];

$str_out = explode('&',$row['out_adress']);
$res_out = $str_out[0];

$query_adress = "SELECT `id`,`city` FROM `adress` WHERE `id`='".$res_in."' OR `id`='".$res_out."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());

while($adress = mysql_fetch_row($result_adress)) {
$adresses[$adress[0]]= $adress[1];
}

$query_clients = "SELECT `id`,`name`,`cl_manager` FROM `clients` WHERE `id`='".$row['client']."'";
$result_clients = mysql_query($query_clients) or die(mysql_error());
while($clients = mysql_fetch_row($result_clients)) {
$client[$clients[0]]= $clients[1];
$cl_manager[$clients[0]]= $clients[2];
}

$query_tr = "SELECT `id`,`name` FROM `transporters` WHERE `id`='".$row['transp']."'";
$result_tr = mysql_query($query_tr) or die(mysql_error());
while($tr = mysql_fetch_row($result_tr)) {
$transporters[$tr[0]]= $tr[1];
}


$cl_pay=0;
$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($row[0])."' AND `delete`='0' AND `appoint`='1' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$cl_pay=(int)$pay[0]+(int)$cl_pay;
}

$cl_pay_plus=0;
$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($row[0])."' AND `delete`='0' AND `appoint`='4' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$cl_pay_plus=(int)$pay[0]+(int)$cl_pay_plus;
}

$cl_pay_minus=0;
$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($row[0])."' AND `delete`='0' AND `appoint`='3' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$cl_pay_minus=(int)$pay[0]+(int)$cl_pay_minus;
}

$tr_pay=0;
$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($row[0])."' AND `delete`='0' AND `appoint`='2' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$tr_pay=(int)$pay[0]+(int)$tr_pay;
}

$tr_pay_plus=0;
$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($row[0])."' AND `delete`='0' AND `appoint`='5' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$tr_pay_plus=(int)$pay[0]+(int)$tr_pay_plus;
}

            $tr_pay_minus = 0;
            $query_pays = "SELECT `cash` FROM `pays` WHERE `order`='" . mysql_escape_string($row[0]) . "' AND `delete`='0' AND `appoint`='6' AND `status`='1'";
            $result_pays = mysql_query($query_pays) or die(mysql_error());
            while ($pay = mysql_fetch_row($result_pays)) {
                $tr_pay_minus = (int)$pay[0] + (int)$tr_pay_minus;
            }

            $status_tr = 0;
            $status_cl = 0;

            if (($cl_pay == ((int)['cl_cash'] * 100 + (int)$row['cl_kop'])) || (substr($cl_pay, 0, -2) == (int)$row['cl_cash'])) $status_cl = 1;
            if ($cl_pay == ((int)$row['cl_cash'] * 100 + (int)$row['cl_kop']) && $cl_pay_plus == $row['cl_plus'] * 100 && $cl_pay_minus == $row['cl_minus'] * 100) $status_cl = 2;

            if ($tr_pay == $row['tr_cash'] * 100) $status_tr = 1;
            if ($tr_pay == $row['tr_cash'] * 100 && $tr_pay_plus == $row['tr_minus'] * 100 && $tr_pay_minus == $row['tr_plus'] * 100) $status_tr = 2;


            if ((($cl_pay == $row['cl_cash'] * 100) || (substr($cl_pay, 0, -2) == $row['cl_cash'])) && $tr_pay == $row['tr_cash'] * 100) $status_all = 1; else $status_all = 0;

            $cont_cl = '';

            if ($row['transp'] == '2') {
                $status_tr = 1;
                $row['tr_pref'] = 1;

switch ($row['cl_cont']) {
case '3': $cont_cl='Транспортная компания';break;
}
}

switch ($row['tr_cont']) {
case '2': $cont_tr='';break;
}

switch ($row['cl_pref']) {
case '1': $pref_cl='ООО';break;
case '2': $pref_cl='ОАО';break;
case '3': $pref_cl='ИП';break;
case '4': $pref_cl='ЗАО';break;
case '5': $pref_cl='';break;
case '6': $pref_cl='Физ.Л.';break;
}

$pref_tr="";

switch ($row['tr_pref']) {
case '1': $pref_tr='ООО';break;
case '2': $pref_tr='ОАО';break;
case '3': $pref_tr='ИП';break;
case '4': $pref_tr='ЗАО';break;
case '6': $pref_tr='Физ.Л';break;}

switch ($row['cl_nds']) {
case '0': $nds_cl='без НДС';break;
case '1': $nds_cl='с НДС';break;
case '2': $nds_cl='НАЛ';break;
}
switch ($row['tr_nds']) {
case '0': $nds_tr='без НДС';break;
case '1': $nds_tr='с НДС';break;
case '2': $nds_tr='НАЛ';break;
}

if($row['pretenzia']==1) $pretenzia='<br><font size="2" color="blue">Претензия</font>'; else $pretenzia='';
if($row['vzaimozachet']==1) $vzaimozachet='<br><font size="2" color="blue">Взаимозачет</font>'; else $vzaimozachet='';

if($row['international_number']!="0") $order_number='<font size="4"><b>'.$row['international_number'].'</b></font><br><font size="1">('.$row['id'].')</font>'; else $order_number='<font size="4"><b>'.$row['id'].'</b></font>';


if($row['cl_kop']!=""&&(int)$row['cl_kop']!=0) $cl_kop='</font>.<font size="2">'.$row['cl_kop']; else $cl_kop="";

$cl_pay_show=0;

if(substr($cl_pay, -2, 1)!=0) $cl_pay_show=number_format($cl_pay/100, 2, ',', ' '); else $cl_pay_show=number_format($cl_pay/100, 0, ',', ' ');



if($row['cl_plus']!=0 || $row['cl_minus']!=0) $dop_cl_rashod_info_1 = '(+'.$row['cl_plus'].'/-'.$row['cl_minus'].')'; else $dop_cl_rashod_info_1 = '';

if($dop_cl_rashod_info_1 != '') {
	$dop_cl_rashod_info_1 .= '<br>'; 
} else {
	$dop_cl_rashod_info_2 = '<br>'; 
}

if($row['cl_rashod_sb']!=0 || $row['cl_rashod_na_cl']!=0) $dop_cl_rashod_info_2 .= 'СБ - '.$row['cl_rashod_sb'].'; Доп.р -'.$row['cl_rashod_na_cl']; else $dop_cl_rashod_info_2 = '';

if($row['tr_plus']!=0 || $row['tr_minus']!=0) $dop_tr_rashod_info_1 = '(+'.$row['tr_plus'].'/-'.$row['tr_minus'].')'; else $dop_tr_rashod_info_1 = '';

$cl_cash = number_format($row['cl_cash'], 0 , "", " " );
            $tr_cash = number_format($row['tr_cash'], 0, "", " ");

            if ($cl_pay_plus != 0 || $cl_pay_minus != 0 || $row['cl_plus'] != 0 || $row['cl_minus'] != 0) {
                $cl_pay_plus_minus = '<br><font size="1">(+' . number_format($cl_pay_plus / 100, 0, ',', '') . '/-' . number_format($cl_pay_minus / 100, 0, ',', '') . ')</font>';
            } else $cl_pay_plus_minus = '';

            if ($tr_pay_plus != 0 || $tr_pay_minus != 0 || $row['tr_plus'] != 0 || $row['tr_minus'] != 0) {
                $tr_pay_plus_minus = '<br><font size="1">(+' . number_format($tr_pay_plus / 100, 0, ',', '') . '/-' . number_format($tr_pay_minus / 100, 0, ',', '') . ')</font>';
            } else $tr_pay_plus_minus = '';

if($row['group_id']!='') $group_id = '<br><small><b>Группа:</b> '.$row['group_id'].'</small>'; else $group_id = '';


            $data->rows[$i]['id'] = $row['id'];
            $data->rows[$i]['cell'] = array($row['id'], $order_number . '<br>' . date("d/m/Y", strtotime($row['data'])) . $vzaimozachet, '<font size="3">' . $adresses[$res_in] . '</font>', '<font size="3">' . $adresses[$res_out] . '</font>', $pref_cl . ' <b><i>«' . $client[$row['client']] . '»</b></i><br>(' . $users[$row['manager']] . ')', $row['data'], '<font size="4">' . $cl_cash . $cl_kop . '</font> ' . $row['cl_currency'] . '<br><b>' . $nds_cl . '</b> <font size="1">' . $dop_cl_rashod_info_1 . $dop_cl_rashod_info_2 . '</font>', '<b>' . $cl_pay_show . '</b> ' . $row['cl_currency'] . $cl_pay_plus_minus . $pretenzia, $pref_tr . ' <b><i>«' . $transporters[$row['transp']] . '»</i></b><br>(' . $users[$row['tr_manager']] . ')<font size="1">' . $cont_cl . $cont_tr . '</font>'.$group_id, '<font size="4">' . $tr_cash . '</font> ' . $row['tr_currency'] . '<br><b>' . $nds_tr . '</b> <font size="1">' . $dop_tr_rashod_info_1 . '</font>', '<b>' . number_format($tr_pay / 100, 0, ',', ' ') . '</b> ' . $row['tr_currency'] . $tr_pay_plus_minus, $row['block'], $row['manager'], $row['rent'], $status_cl, $status_tr, $status_all, $row['vzaimozachet'], $row['pretenzia'], $row['group_id']);
            $i++;


        }
}
// Перед выводом не забывайте выставить header
// с типом контента и кодировкой
echo json_encode($data);
	
}


if ($_GET['mode']=='desc') 
{
$id =$_GET['id'];


$query_order= "SELECT * FROM `orders` WHERE `Id`=".$id;
$result_order = mysql_query($query_order) or die(mysql_error());
if(mysql_num_rows($result_order) >= 1){

	            $i = 0;
            while($row = mysql_fetch_array($result_order)) {
 $query_tr = "SELECT `name`,`tr_code_ati` FROM `transporters` WHERE `id`='".mysql_escape_string($row['tr_receive'])."'";
$result_tr = mysql_query($query_tr) or die(mysql_error());
$transp = mysql_fetch_row($result_tr);

if($row['transp']=='2'){
$str_auto = explode('&',$row['tr_auto']);	
$query_car = "SELECT `name`,`number` FROM `vtl_auto` WHERE `id`='".(int)$str_auto[0]."' OR `id`='".(int)$str_auto[1]."' ORDER BY `type` ASC";
$result_car = mysql_query($query_car) or die(mysql_error());
$iii=0;
while($car = mysql_fetch_row($result_car)){$car_info[$iii]=$car[0];$iii++;$car_info[$iii]=$car[1];$iii++;}
$car_info[4]=(int)$str_auto[2];

$query_drv = "SELECT `pref_phone`,`phone` FROM `workers` WHERE `Id`='".(int)$str_auto[2]."'";
$result_drv = mysql_query($query_drv) or die(mysql_error());
$drv = mysql_fetch_row($result_drv);
$car_info[5]='8('.$drv[0].')'.$drv[1];
$car_check="<fieldset style='margin-bottom:-5px;'>Собственник: Транспортная компания</b></fieldset>";
} else {$query_car = "SELECT `car_name`,`car_number`,`car_driver_name`,`car_driver_phone`,`car_extra_name`,`car_extra_number`,`car_owner`,`car_owner_doc`,`id`,`check_mail`,`check_date`,`check` FROM `tr_autopark` WHERE `id`='".$row['tr_auto']."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car_info = mysql_fetch_row($result_car);

// Проверка машины
//if($car_info[9]==0){
//if($car_info[6]!=""&&$car_info[7]!="") $check_button="<a class='button' id='btnCheck' href='#' onclick=\"$('#result').html('Вы действительно хотите отправить электронный запрос о транспортном средстве <b>".$car_info[0]." - ".$car_info[1]."</b>?<br><font size=1 color=red>* За ложный запрос с Вашей зарплаты будет удерживаться сумма в 500 руб.</font>');$('#result').dialog({ title: 'Внимание' },{ modal: true },{ width: 430 },{ resizable: false }, {position: 'top'},{ buttons: [{text: 'Да',click: function() {\$('#result').dialog('close');$.post('control/car_check.php?mode=check&car_id=".$car_info[8]."', function(data) {\$('#result').dialog('close');$('#table').toggleSubGridRow(".$id.");$('#table').expandSubGridRow(".$id.");$('#result_temp').html(data);$('#result_temp').dialog({ title: 'Внимание' },{ height: 150 },{ width: 300 },{ modal: true },{ resizable: false },{ buttons: [{text: 'закрыть',click: function() {\$('#result_temp').dialog('close');}}]}   );});}},{text: 'Нет',click: function() {\$(this).dialog('close');}}] });\">Отправить запрос</a>"; else $check_desc=" (указажите собственника и номер СТС)";
//}
//if($car_info[9]==1) $check_desc=" Запрос был отправлен: <b>".date("d/m/Y H:i", strtotime($car_info[10]))."</b>";

    switch ($car_info[11]) {
        case '0':
            $check_info = "<font color='grey'>не проверялся";
            break;
        case '1':
            $check_info = "<font color='#5954FF'>ожидает проверки";
            break;
        case '2':
            $check_info = "<font color='green'>подтверждено";
            break;
        case '3':
            $check_info = "<font color='red'>недостоверные данные";
            break;
    }


    $check_desc = "";
    $check_button = "";

    $car_check = "<br><fieldset style='margin-bottom:15px;'><table style='float:left;border-width: 1px;border-collapse: collapse;' cellpadding='0'>
<tr><td>Собственник:&nbsp;<b>" . $car_info[6] . "</b></td><td>Свидетельство ТС:&nbsp;<b>" . $car_info[7] . "</b></td></tr>
<tr><td>Статус: <b>" . $check_info . "</font></b></td><td>" . $check_desc . "</td></tr></table><div align='center'>" . $check_button . "</div></fieldset>";

}
        	
            	switch ($row['cl_nds']) {
case '0': $nds_cl='без НДС';break;
case '1': $nds_cl='с НДС';break;
case '2': $nds_cl='НАЛ';break;
}

switch ($row['gr_load']) {
case '1': $gr_load='верхняя';break;
case '2': $gr_load='задняя';break;
case '3': $gr_load='боковая';break;
} 



$print_text='';
if($row['print_cl'])$print_text='<fieldset style=\"float:left\"><legend><b>Доп. условия договора (клиент):</b></legend>'.$row['print_cl'].'</fieldset>';
if($row['print_tr'])$print_text='<fieldset style=\"float:left\"><legend><b>Доп. условия договора (перевозчик):</b></legend>'.$row['print_tr'].'</fieldset>';

switch ($row['tr_nds']) {
case '0': $nds_tr='без НДС';break;
case '1': $nds_tr='с НДС';break;
case '2': $nds_tr='НАЛ';break;
}


$res_adr_in='';
$bill_adr_in='';
$str_in = explode('&',$row['in_adress']);
$str_adr_in =(int)sizeof($str_in)-2;
$f=0;

$city='';

while ($f<=$str_adr_in) {
$query_adress = "SELECT * FROM `adress` WHERE `id`='".$str_in[($str_adr_in-$f)]."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());
$adress = mysql_fetch_row($result_adress);
if($adress[8]=="0") $flat=""; else $flat=' кв.'.$adress[8];
if($adress[3]==""||$adress[3]=="0") $obl=""; else $obl=$adress[3]." обл.";	
if($adress[6]==""||$adress[6]=="0") $dom=""; else $dom=" д.".$adress[6];
if($adress[7]==""||$adress[7]=="0") $dom_extra=""; else $dom_extra='/'.$adress[7];
	
$res_adr_in =$res_adr_in."<div style='padding: 5px;margin: 10px;margin-left: 40px;background: #ddd;border: 1px solid #bbb;width: 90%;'>".($f+1).") ".$adress[2].', '.$obl.' '.$adress[4].' ул. '.$adress[5].$dom.$dom_extra.$flat.' ('.$adress[9].' - '.$adress[10].')</div>';


if($city!=$adress[4])$bill_adr_in.=$obl.", ".$adress[4]." - ";

$city=$adress[4];

$f++;
}



$str_out = explode('&',$row['out_adress']);
$str_adr_out =(int)sizeof($str_out)-2;
$f=0;
$city='';

$res_adr_out ='';
while ($f<=$str_adr_out) {
$query_adress = "SELECT * FROM `adress` WHERE `id`='".$str_out[($str_adr_out-$f)]."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());
$adress = mysql_fetch_row($result_adress);
if($adress[8]=="0") $flat=""; else $flat=' кв.'.$adress[8];
if($adress[3]==""||$adress[3]=="0") $obl=""; else $obl=$adress[3]." обл.";	
if($adress[6]==""||$adress[6]=="0") $dom=""; else $dom=" д.".$adress[6];
if($adress[7]==""||$adress[7]=="0") $dom_extra=""; else $dom_extra='/'.$adress[7];


	
$res_adr_out =$res_adr_out."<div style='padding: 5px;margin:10px;margin-left: 40px;background: #ddd;border: 1px solid #bbb;width: 90%;'>".($f+1).") ".$adress[2].', '.$obl.' '.$adress[4].' ул. '.$adress[5].$dom.$dom_extra.$flat.' ('.$adress[9].' - '.$adress[10].')</div>';

if($city!=$adress[4])$bill_adr_in.=$obl.", ".$adress[4]." - ";

$city=$adress[4];

$f++;
}

$bill_adr_in=substr($bill_adr_in, 0, -3);

$q["01"]="января"; 
$q["02"]="февраля"; 
$q["03"]="марта"; 
$q["04"]="апреля"; 
$q["05"]="мая";
$q["06"]="июня"; 
$q["07"]="июля"; 
$q["08"]="августа"; 
$q["09"]="сентября"; 
$q["10"]="октября"; 
$q["11"]="ноября";
$q["12"]="декабря";


$data_temp= explode('-',$row['data']);
if($data_temp[2]<10)$data_temp[2]=substr($data_temp[2],1);
$data_order=$data_temp[2]." ".$q[$data_temp[1]]." ".$data_temp[0];


if($row['date_in1']!="1970-01-01"&&$row['date_in1']!="0000-00-00") {$date_in1="<b>".date("d/m/Y",strtotime($row['date_in1']))."</b>";
if($row['time_in11']!='00:00:00')$date_in1.=" с ".date("H:i",strtotime($row['time_in11']));

if($row['time_in12']!='00:00:00')$date_in1.=" до ".date("H:i",strtotime($row['time_in12']));

} else $date_in1="";

if($row['date_in2']!="1970-01-01"&&$row['date_in2']!="0000-00-00") {$date_in2=" по <b>".date("d/m/Y",strtotime($row['date_in2']))."</b>"; 

if($row['time_in21']!='00:00:00')$date_in2.=" с ".date("H:i",strtotime($row['time_in21']));
if($row['time_in22']!='00:00:00')$date_in2.=" до ".date("H:i",strtotime($row['time_in22']));
	
$date_in=" c ";} else {$date_in2="";$date_in="";};


if($row['date_out1']!="0000-00-00"&&$row['date_out1']!="1970-01-01") {$date_out1='<b>'.date("d/m/Y",strtotime($row['date_out1'])).'</b>';
if($row['time_out1']!='00:00:00')$date_out1.=' с '.date('H:s',strtotime($row['time_out1']));
} else $date_out1="";
if($row['date_out2']!="0000-00-00"&&$row['date_out2']!="1970-01-01") {$date_out2=' по <b>'.date("d/m/Y",strtotime($row['date_out2'])).'</b>';
if($row['time_out2']!='00:00:00')$date_out2.=' до '.date('H:s',strtotime($row['time_out2']));

} else $date_out2="";

if(!empty($date_out2))$date_out=" c "; else $date_out="";




$cash=komissia($row['cl_cash'],$row['cl_minus'],$row['cl_plus'],$row['cl_nds'],$row['tr_cash'],$row['tr_minus'],$row['tr_plus'],$row['tr_nds']);
	


switch ($row['cl_event']) {
case '1': $cl_event='Загрузка';break;
case '2': $cl_event='Выгрузка';break;
case '3': $cl_event='Поступление факсимильных документов';break;
case '4': $cl_event='Поступление оригинальных документов';break;}

switch ($row['tr_event']) {
case '1': $tr_eventsss='Загрузка';break;
case '2': $tr_eventsss='Выгрузка';break;
case '3': $tr_eventsss='Поступление факсимильных документов';break;
case '4': $tr_eventsss='Поступление оригинальных документов';break;}

$cash_ok=$cash*100/(int)$row['cl_cash'];	

$query_user = "SELECT `id`,`name` FROM `workers` WHERE `id`='".$row['manager']."' OR `id`='".$row['tr_manager']."'";
$result_user = mysql_query($query_user) or die(mysql_error());

while($user = mysql_fetch_row($result_user)) {

if($user[0]==$row['manager']){$pieces = explode(" ", $user[1]);
$order_manager=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";}

if($user[0]==$row['tr_manager']){$pieces = explode(" ", $user[1]);
$tr_manager=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";}
}




$tr_event="";
switch ($row['tr_event']) {
case '1': $tr_event=$row['date_in1'];break;
case '2': $tr_event=$row['date_out1'];break;

case '3': $query_docs = "SELECT `date_tr_receve` FROM `docs` WHERE `order`='".mysql_escape_string($row['Id'])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());
$tr_events=mysql_fetch_row($result_docs);
if(mysql_num_rows($result_docs)>0&&$tr_events[0]!="0000-00-00"&&$tr_events[0]!="1970-01-01"&&$tr_events[0]!=""){
$tr_event=$tr_events[0];};break;

case '4': $query_docs = "SELECT `date_tr_receve` FROM `docs` WHERE `order`='".mysql_escape_string($row['id'])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());
$tr_events=mysql_fetch_row($result_docs);
if(mysql_num_rows($result_docs)>0&&$tr_events[0]!="0000-00-00"&&$tr_events[0]!="1970-01-01"&&$tr_events[0]!=""){
$tr_event=$tr_events[0];};break;
}
if($tr_event!="0000-00-00"&&$tr_event!="1970-01-01"&&$tr_event!=""){
$tr_event_date=" (".date('d/m/Y', strtotime('+'.(int)$row['tr_tfpay'].' day', strtotime($tr_event)))."г.)";
} else $tr_event_date='';
if($row['transp']=='2'){$pr_vtl="";
$query_driver = "SELECT `id`,`name` FROM `workers` WHERE `id`='".$car_info[4]."'";
$result_driver = mysql_query($query_driver) or die(mysql_error());
$drivers = mysql_fetch_row($result_driver);

$driver=$drivers[1];
$drv_phone=$car_info[5];
$dop_car=$car_info[3];
$dop_car_name=$car_info[2];
} else {$pr_vtl=",{text: 'Перевозчику',click: function() {window.open('control/print.php?mode=tr&id=".base64_encode($row['id'])."');\$(this).dialog('close');}}";
$driver=$car_info[2];
$drv_phone=$car_info[3];
$dop_car=$car_info[5];
$dop_car_name=$car_info[4];}

$query_docs = "SELECT `id` FROM `docs` WHERE `order`='".mysql_escape_string($row['id'])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());
$docs=mysql_fetch_row($result_docs);

//Для бухгалтеров
if ($_SESSION["group"] != 5) {
    $navigation = "<a class=\"button\" id=\"btnDocs\" href=\"#\" onclick=\"window.location.href='docs.php?doc_id=" . $docs[0] . "'\">Документы</a><br><button class=\"button\" id=\"btnEdit\" onclick=\"$('#fa').load('theme/forms/add_order.php?order=" . $row['id'] . "');$('#fa').dialog({ title: 'Редактировать заявку №" . $row['id'] . "' },{width: 970,position:[150,50],modal: true,resizable: false});\" style=\"height: 45px;\"><b>Редактировать</b></button><br><a  class=\"button6\" id=\"btnPrint\" href=\"javascript:;\" onclick=\"$('#dialogpr').dialog({ buttons: [{text: 'Клиенту',click: function() {window.open('control/print.php?mode=cl&id=" . base64_encode($row['id']) . "');\$(this).dialog('close');}}" . $pr_vtl . "] },{ modal:true,resizable: false });\">Распечатать</a><br><a  class=\"button\" id=\"btnPrint_dov\" href=\"javascript:;\" onclick=\"window.open('control/print_dov.php?mode=dov&id=" . base64_encode($row['id']) . "');\">Доверенность</a><hr style=\"width: 100%; height: 2px;\" /><button class=\"button3\" id=\"btnDelete\" onclick=\"$('#result').html('Удалить выбранную заявку?');$('#result').dialog({ title: 'Внимание' },{ modal: true },{ resizable: false },{ buttons: [{text: 'Да',click: function() {\$.post('control/orders.php?mode=delete&id=" . $row['id'] . "', function(data) {jQuery('#table').trigger('reloadGrid');$('#result').dialog('close');$('#result_temp').html(data);$('#result_temp').dialog({ title: 'Внимание' },{ height: 90 },{ width: 400 },{ modal: true },{ resizable: false });});}},{text: 'Нет',click: function() {\$(this).dialog('close');}}] });\" style=\"font-size: 1.2em;width:140px;\">Удалить</button><br>";


} else {
    $navigation = "";
}

if($row['km']!="0"&&$row['km']!=NULL)$km="<br><br><hr><fieldset style='width:160px;'><legend><b>Расстояние:</b></legend><b><font size='5'>".$row['km']."</font></b> км</fieldset>"; else {$km="";
	
//$str_in = explode('&',$row['in_adress']);
//$str_adr_in = (int)sizeof($str_in)-2;
//$res_in = $str_in[$str_adr_in];

//$str_out = explode('&',$row['out_adress']);
//$res_out = $str_out[0];

//$query_adress = "SELECT `id`,`city` FROM `adress` WHERE `id`='".$res_in."' OR `id`='".$res_out."'";
//$result_adress = mysql_query($query_adress) or die(mysql_error());

//while($adress = mysql_fetch_row($result_adress)) {
//$adresses[$adress[0]]= $adress[1];
//}
 	//$str_in = explode('г.',$adresses[$res_in]);
 	//if($str_in[1]!="")$in_city=$str_in[1]; else $in_city=$str_in[0];
 	
 	//$str_out = explode('г.',$adresses[$res_out]);
 	//if($str_out[1]!="")$out_city=$str_out[1]; else $out_city=$str_out[0];

//$km="<br><br><hr><fieldset style='width:160px;'><legend><b>Расстояние:</b></legend><b>".$in_city."</b>-<b>".$out_city."</b><br><div id='km_v_".$row['id']."'>[<a href='#' onclick=\"$('#km_v_".$row['id']."').load('/control/km.php?mode=km&start_city=".trim($in_city)."&end_city=".trim($out_city)."');\">рассчитать</a>]</div></fieldset>";
}





$query_company = "SELECT `id`,`name` FROM `company` WHERE `id`='".$row['cl_cont']."' OR `id`='".$row['tr_cont']."'";
$result_company = mysql_query($query_company) or die(mysql_error());

while($company = mysql_fetch_row($result_company)) {
if($company[0]==$row['cl_cont'])$cl_company=$company[1];
if($company[0]==$row['tr_cont'])$tr_company=$company[1];
}
if ($row['krugoreis']=='1')$krugoreis='&nbsp;&nbsp;<img src="data/img/gtk-refresh.png" style="width:20px;float:right;display:inline;margin-top:-10px;"><div style="margin-top:-10px;float:right;display:inline;"><b>кругорейс</b>&nbsp;&nbsp;</div>'; else $krugoreis='';

if($row['gr_number']==0)$row['gr_number']='-';
if($row['gr_m']==0)$row['gr_m']='-';
if($row['gr_v']==0)$row['gr_v']='-';

//if($agat_order=="") $ag_ord=$row['id']; else $ag_ord=$agat_order;

if($dop_car=="") $d_car=""; else $d_car=", п/пр. ".$dop_car;

$query_pays = "SELECT `date`,`cash` FROM `pays` WHERE `order`='".mysql_escape_string($row[0])."' AND `delete`='0' AND `appoint`='1' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$cl_pay.=''.date( 'd/m/Y',strtotime($pay[0])).'-'.($pay[1]/100).' руб. ; ';
} 
if($cl_pay=='') $cl_pay='отсутствуют';

$query_pays = "SELECT `date`,`cash` FROM `pays` WHERE `order`='".mysql_escape_string($row[0])."' AND `delete`='0' AND `appoint`='2' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$tr_pay.=''.date( 'd/m/Y',strtotime($pay[0])).'-'.($pay[1]/100).' руб. ; ';
} 
if($tr_pay=='') $tr_pay='отсутствуют';
if($row['transp']=='2') $tr_pay='-';

if($transp[1]!='')$code_ati=$transp[1]; else $code_ati='-';

//if($row['client']==627||$row['client']==630||$row['client']==632||$row['client']==639) $bill_text="Транспортные услуги по маршруту ".$bill_adr_in.",водитель ".$driver.", ".$car_info[0]." ".$car_info[1].$d_car.", торг-12 № от , по доверенностии № от г."; else 

$bill_text="Транспортно-экспедиционные услуги по маршруту ".$bill_adr_in.", водитель ".$driver.", ".$car_info[0]." ".$car_info[1].$d_car.", заявка №".$row['id']." от ".date("d.m.Y",strtotime($row['data']))." г.";


if($row['tr_days']!=0) $tr_days='&nbsp;&nbsp;&nbsp;<b>Простой:</b> '.$row['tr_days'].' сут.'; else $tr_days='';

	              $data->rows[$i]['cell'] =array("<b><div style=\"font-size: 26px;float:left;\">".$cash."</div> руб.</b> (".round($cash_ok,1)."%)".$km."<hr style=\"width: 100%; height: 2px;float:right;\" />".$navigation."<br><br><img src=\"data/img/parcel.png\"><br><button class=\"button3\" id=\"btnCopy\" onclick=\"$('#fa').load('theme/forms/add_order.php?mode=copy&order=".$row['id']."');$('#fa').dialog({ title: 'Новая заявка' },{width: 970,position:[150,50],modal: true,resizable: false});return false\" style=\"height: 45px;\"><b>Скопировать</b></button>","<b>Дата заявки: <font color=\"red\" size=\"4\">".$data_order."</font></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Менеджер заявки:</b> <font color=green>".$order_manager."</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Менеджер по транспорту:</b> <font color=green>".$tr_manager."</font><hr><fieldset><legend>Груз:</legend><b>Наименование груза:</b> ".$row['gruz'].'&nbsp;&nbsp;&nbsp;'."<b>Вес:</b> ".$row['gr_m'].' т&nbsp;&nbsp;&nbsp;'."<b>Обьем:</b> ".$row['gr_v']." м3&nbsp;&nbsp;&nbsp;<b>Количество:</b> ".$row['gr_number'].' шт.&nbsp;&nbsp;&nbsp;'."<b>Вид погрузки:</b> ".$gr_load."
</fieldset><fieldset><legend><b>Загрузка:</b></legend><b>Дата загрузки:</b> ".$date_in.$date_in1.$date_in2.$krugoreis."<hr>".$res_adr_in."</fieldset><fieldset><legend><b>Выгрузка:</b></legend><b>Дата выгрузки:</b> ".$date_out.$date_out1.$date_out2.$krugoreis."<hr>".$res_adr_out."</fieldset>

<fieldset><legend>Автотранспорт</legend><b>".$car_info[0]."</b> - Г/н: <b>".$car_info[1]."</b> (П/п: ".$dop_car_name." - <b>".$dop_car."</b>)&nbsp;&nbsp;&nbsp;Водитель: <b>".$driver."</b>&nbsp;&nbsp;&nbsp;Телефон: <font color=green>".$drv_phone."</font>".$car_check."</fieldset>	<fieldset><legend>Требования к машине (Особые условия):</legend>".$row['car_notify']."</fieldset>
<fieldset><legend>Клиент:</legend><b>Получатель:</b> «".$cl_company."»&nbsp;&nbsp;&nbsp;<b>Плановая дата оплаты:</b> ".date("d/m/Y",strtotime($row['date_plan']))." г.&nbsp;&nbsp;&nbsp; <b>Ставка клиента:</b> ".$row['cl_cash']." руб. (<b>".$nds_cl."</b>)<hr><b>Срок оплаты:</b> ".$cl_event." + <b>".$row['cl_tfpay']." дн.</b>&nbsp;&nbsp;&nbsp;<b>Платежи:</b> ".$cl_pay."</fieldset><fieldset><legend>Перевозчик (Код в АТИ: <font size='4'>".$code_ati."</font>)</legend><b>Плательщик:</b> «".$tr_company."»&nbsp;&nbsp;&nbsp;<b>Получатель:</b> «".$transp[0]."»&nbsp;&nbsp;&nbsp;<b>Ставка перевозчика:</b> ".$row['tr_cash']." руб. (<b>".$nds_tr."</b>)<hr><b>Срок оплаты:</b> ".$tr_eventsss." + <b>".$row['tr_tfpay']." дн.</b> ".$tr_event_date."&nbsp;&nbsp;&nbsp;<b>Платежи:</b> ".$tr_pay.$tr_days."</fieldset>".$print_text."<fieldset style='background: #aed2d9;color:#000;'><legend>Счёт:</legend><textarea style='width:97%;' rows='3'>".$bill_text."</textarea></fieldset><fieldset><legend>Примечание:</legend>".$row['notify']."</fieldset>");
	                $i++;
	        }
	        } else {
            
         
         $data->rows[0]['cell'] =array("<a class=\"button\" id=\"btnRepair\" href=\"#\" onclick=\"$('#result').html('Восстановить выбранную заявку?');$('#result').dialog({ title: 'Внимание' },{ modal: true },{ resizable: false },{ buttons: [{text: 'Да',click: function() {\$.post('control/orders.php?mode=repair&id=".$id."', function(data) {jQuery('#table').trigger('reloadGrid');$('#result').dialog('close');$('#result_temp').html(data);$('#result_temp').dialog({ title: 'Внимание' },{ height: 90 },{ width: 400 },{ modal: true },{ resizable: false });});}},{text: 'Нет',click: function() {\$(this).dialog('close');}}] });\">Восстановить</a>","");   
            
            
            
            
            }

header("Content-type: text/script;charset=utf-8");
echo json_encode($data);


}


if ($_GET['mode']=='repair') 
{
$id =$_GET['id'];
$query = "INSERT INTO `orders` SELECT * FROM `deleted_orders` WHERE `id`='".mysql_escape_string($id)."'";
$result = mysql_query($query) or die(mysql_error());


$query = "DELETE FROM `deleted_orders` WHERE `id`='".$id."'";
$result = mysql_query($query) or die(mysql_error());

echo '<font color="red" size="3">Заявка №'.$id.' восстановлена!</font>';
}

if ($_GET['mode']=='delete') 
{
$id = (int) $_GET['id'];

$query = "SELECT block, notify FROM orders WHERE id=".mysql_escape_string($id);
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_row($result);


if ($row[0]=='1'){echo '<font size="3"><div alegn="center">Заявка заблокирована! Обратитесь к директору...</div></font>';}
else {
$query_pay = "SELECT status FROM `pays` WHERE `delete`='0' AND `order`='".mysql_escape_string($id)."'";
$result_pay = mysql_query($query_pay) or die(mysql_error());
if(mysql_num_rows($result_pay)==0){

$query = "INSERT `deleted_orders` SELECT * FROM orders WHERE id=".mysql_escape_string($id);
$result = mysql_query($query) or die(mysql_error());

$query = "DELETE FROM orders WHERE id=".mysql_escape_string($id);
$result = mysql_query($query) or die(mysql_error());

$query_gruz = "UPDATE `cl_gruz` SET `order`='0', `status`='0' WHERE `order`='".mysql_escape_string($id)."'";
$result_gruz = mysql_query($query_gruz) or die(mysql_error());

echo '<font color="red" size="3">Заявка помечена на удаление!</font>';

$manager =$_SESSION["user_id"];

$query = "INSERT INTO `logs` (`user`,`log_message`) VALUES ('$manager','Удалена заявка №".$id." (".$row[1].")')";
$result = mysql_query($query) or die(mysql_error());

} else {
$query_ord = "SELECT transp FROM orders WHERE id=".mysql_escape_string($id);
$result_ord = mysql_query($query_ord) or die(mysql_error());	
$row_ord = mysql_fetch_row($result_ord);

if((int)$row_ord[0]==2){
$query = "INSERT deleted_orders SELECT * FROM orders WHERE id=".$id;
$result = mysql_query($query) or die(mysql_error());

$query = "DELETE FROM orders WHERE id=".$id;
$result = mysql_query($query) or die(mysql_error());

$query = "DELETE FROM `pays` WHERE `order`='".$id."' AND `way`='2'";
$result = mysql_query($query) or die(mysql_error());

$query_gruz = "UPDATE `cl_gruz` SET `order`='0',`status`='0' WHERE `order`='".mysql_escape_string($id)."'";
$result_gruz = mysql_query($query_gruz) or die(mysql_error());
	
} else
echo '<font size="3"><div alegn="center">Невозможно удалить! В заявке имеются платежи...</font> Обратитесь к директору...</div>';
}
	}

}

if ($_GET['mode']=='total') 
{

$total=0;
$total_tr=0;




if($group=='3'){

// Managers can see only their clients - now disabled
//$query_total = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds` FROM `orders` WHERE `manager`='".$manager."' AND `tr_manager`='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."'";
$query_total = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds` FROM `orders` WHERE DATE(`data`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."'";


$result_total = mysql_query($query_total) or die(mysql_error());




while($row_t = mysql_fetch_array($result_total)) {

$cash=komissia($row_t['cl_cash'],$row_t['cl_minus'],$row_t['cl_plus'],$row_t['cl_nds'],$row_t['tr_cash'],$row_t['tr_minus'],$row_t['tr_plus'],$row_t['tr_nds']);



$total=(int)$total+(int)$cash;
}

$query_tr = "SELECT `cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds` FROM `orders` WHERE `manager`='".$manager."' AND `tr_manager`!='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."'";
$result_tr = mysql_query($query_tr) or die(mysql_error());


while($row_tr = mysql_fetch_array($result_tr)) {

$cash=komissia($row_tr['cl_cash'],$row_tr['cl_minus'],$row_tr['cl_plus'],$row_tr['cl_nds'],$row_tr['tr_cash'],$row_tr['tr_minus'],$row_tr['tr_plus'],$row_tr['tr_nds']);



$total_tr=(int)$total_tr+(int)$cash;
}

$query_tr_dop = "SELECT `cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds` FROM `orders` WHERE `manager`!='".$manager."' AND `tr_manager`='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."'";
$result_tr_dop = mysql_query($query_tr_dop) or die(mysql_error());

while($row_tr_dop = mysql_fetch_array($result_tr_dop)) {

$cash=komissia($row_tr_dop['cl_cash'],$row_tr_dop['cl_minus'],$row_tr_dop['cl_plus'],$row_tr_dop['cl_nds'],$row_tr_dop['tr_cash'],$row_tr_dop['tr_minus'],$row_tr_dop['tr_plus'],$row_tr_dop['tr_nds']);


$total_tr_dop=(int)$total_tr_dop+(int)$cash;
}

// Managers salary
echo '<font size="4">'.($total+$total_tr/2+$total_tr_dop/2).'</font> руб.&nbsp;&nbsp;&nbsp;';
//if($month==1){
//echo 'Переменная часть оклада: <font size="4">';

//if(((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2))<100000)echo ((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2))*0.1;
//if(((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2))>100000)echo ((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2))*0.15;
//echo '</font> руб.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
//}


}




if($group=='2'||$group=='1'||$group=='4'){$query = "SELECT `cl_cash`,`cl_minus`,`cl_plus`,`tr_cash`,`tr_minus`,`tr_plus`,`cl_nds`,`tr_nds` FROM `orders` WHERE DATE(`data`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."'";

$result = mysql_query($query) or die(mysql_error());

$total=0;
while($row = mysql_fetch_array($result)) {

$cash=komissia($row['cl_cash'],$row['cl_minus'],$row['cl_plus'],$row['cl_nds'],$row['tr_cash'],$row['tr_minus'],$row['tr_plus'],$row['tr_nds']);


$total=(int)$total+(int)$cash;
}

echo '<font size="4">'.$total.'</font> руб.&nbsp;&nbsp;&nbsp;';
}







}

?>