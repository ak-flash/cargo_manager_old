<?php
// Подключение и выбор БД
include "../config.php";	
session_start();


if ($_GET['date_start']!=''){
$start_elements  = explode("/",$_GET['date_start']);
if ($start_elements[0]=='01'){$month=1;}
$date_start = date("Y-m-d", strtotime($start_elements[2] . "-" . $start_elements[1] . "-" . $start_elements[0]));


    $end_elements = explode("/", $_GET['date_end']);
    $date_end = date("Y-m-d", strtotime($end_elements[2] . "-" . $end_elements[1] . "-" . $end_elements[0]));
}


if (!isset($data)) $data = new stdClass();

$page = (int)$_GET['page'];      // Номер запришиваемой страницы
$limit = (int)$_GET['rows'];     // Количество запрашиваемых записей
$sidx = mysql_real_escape_string($_GET['sidx']);      // Номер элемента массива по котору следует производить сортировку // Проще говоря поле, по которому следует производить сортировку
$sord = mysql_real_escape_string($_GET['sord']);      // Направление сортировки


$query_clients = "SELECT `id`,`name` FROM `clients`";
$result_clients = mysql_query($query_clients) or die(mysql_error());

while ($clients = mysql_fetch_row($result_clients)) {
    $client[$clients[0]] = $clients[1];
}

$query_tr = "SELECT `id`,`name` FROM `transporters`";
$result_tr = mysql_query($query_tr) or die(mysql_error());

while($tr = mysql_fetch_row($result_tr)) {

$transporters[$tr[0]]= $tr[1];
}

if ($_GET['mode']=='pays') 
{

// Если не указано поле сортировки, то производить сортировку по первому полю
if(!$sidx) $sidx =1;

if ($_GET['del_show']=='true')$result = mysql_query("SELECT COUNT(*) AS count FROM `pays` WHERE `delete`='1' AND `add_name`!='0'"); else
$result = mysql_query("SELECT COUNT(*) AS count FROM `pays` WHERE `delete`='0' AND `add_name`!='0'");

if ($_GET['date_start']!=''){$result = mysql_query("SELECT COUNT(*) AS count FROM `pays` WHERE `delete`='0' AND DATE(`date`) BETWEEN '".$date_start."' AND '".$date_end."' AND `add_name`!='0'");}

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
$query_search = "SELECT `date`,`cash`,`order`,`id`,`way` FROM `pays` WHERE `delete`='0' AND `add_name`!='0' ORDER BY `Id` DESC";
$result_search = mysql_query($query_search) or die(mysql_error());

$query_docs = "SELECT `cl_bill`,`order` FROM `docs`";
$result_docs = mysql_query($query_docs) or die(mysql_error());

while($get_docs = mysql_fetch_row($result_docs)) {$docs[$get_docs[1]]=$get_docs[0];}

while($row = mysql_fetch_array($result_search)) {



if($row['order']!='0') {
    
    $order=$row['order'];
    $query_transp = "SELECT `transporters`.`name` FROM `transporters`,`orders` WHERE `transporters`.`id`=`orders`.`transp` AND `orders`.`id`='".mysql_escape_string($row['order'])."'";
    $result_transp = mysql_query($query_transp) or die(mysql_error());
    $row_transp = mysql_fetch_row($result_transp);

} else $order="-";



if (mb_stristr($row['id'], $_GET['s_data']) || mb_stristr($order, $_GET['s_data']) || mb_stristr($row['date'], $_GET['s_data']) || mb_stristr($row['cash'], $_GET['s_data']) || $docs[$row['order']] == $_GET['s_data'] || (mb_stristr($row_transp[0], $_GET['s_data']) && $row['way'] == '2')) {
    $id_mass[$g] = $row['id'];
    $g++;
}


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

if($id_mass){
$query = "SELECT * FROM `pays` WHERE `Id` IN (".implode(',' , $id_mass).") ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;	
} else {$query = "SELECT * FROM `pays` WHERE `Id`='0'";}	
} else {
if ($_GET['del_show']=='true') 
{
$query = "SELECT * FROM `pays` WHERE `delete`='1' AND `add_name`!='0' ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;
}
else {$query = "SELECT * FROM `pays` WHERE `delete`='0' AND `add_name`!='0' ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;}



if ($_GET['date_start']!=''){
$query = "SELECT * FROM `pays` WHERE `delete`='0'  AND `add_name`!='0' AND DATE(`date`) BETWEEN '".$date_start."' AND '".$date_end."' ORDER BY `date`,`id` desc, `Id` LIMIT ".$start.", ".$limit;}

}

$result = mysql_query($query) or die(mysql_error());


// Начало формирование массива
// для последующего преобразоования
// в JSON объект
$data->page       = $page;
$data->total      = $total_pages;
$data->records    = $count;


$query_user = "SELECT `id`,`name` FROM `workers`";
$result_user = mysql_query($query_user) or die(mysql_error());

while($user = mysql_fetch_row($result_user)) {
$pieces = explode(" ", $user[1]);
$print_add_name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
$users[$user[0]]=$print_add_name;
}

	
// Строки данных для таблицы
$i = 0;
while($row = mysql_fetch_array($result)) {

switch ($row['way']) {
case '1': $way='Поступление';break;
case '2': $way='Выплата';break;
} 

switch ($row['category']) {
case '1': $category='Осн.';break;
case '2': $category='Доп.';break;
}

switch ($row['nds']) {
case '0': $nds='без НДС';break;
case '1': $nds='с НДС';break;
case '2': $nds='НАЛ';break;
}

switch ($row['status']) {
case '0': $status='-';break;
case '1': $status='Проведен';break;
}

$query_app = "SELECT `id`,`app` FROM `pays_appoints`";
$result_app = mysql_query($query_app) or die(mysql_error());
while($pays_app= mysql_fetch_row($result_app))
{
$pays_a[$pays_app[0]] = $pays_app[1];
}

if($_SESSION['user_id']==$row['add_name']) $owner=1; else $owner=0;
	
$pretenzia='';

$cash_el= explode(",",number_format($row['cash']/100, 2, ',', ' '));
$cash_all="<font size=\"4\">".$cash_el[0]." </font>".$row['currency'];
//$cash_all="<font size=\"4\">".$cash_el[0]." </font>руб.".$cash_el[1]." коп.";

$cl_tr_name="";

if($row['order']!='0'&&$row['way']==1) {$query_cl = "SELECT `client`,`cl_pref`,`pretenzia` FROM `orders` WHERE `id`='".mysql_escape_string($row['order'])."'";
$result_cl = mysql_query($query_cl) or die(mysql_error());
$cl= mysql_fetch_row($result_cl);

switch ($cl[1]) {
case '1': $pref_cl='ООО';break;
case '2': $pref_cl='ОАО';break;
case '3': $pref_cl='ИП';break;
case '4': $pref_cl='ЗАО';break;
case '6': $pref_cl='Физ.Л';break;}

$cl_tr_name='<br><font size="1">'.$pref_cl.' '.$client[$cl[0]].'</font>';

if($cl[2]==1) $pretenzia='<br><font size="2" color="blue">Претензия</font>';
}

if($row['order']!='0'&&$row['way']==2) {
    $query_tr = "SELECT `transp`,`tr_pref` FROM `orders` WHERE `id`='".mysql_escape_string($row['order'])."'";
$result_tr = mysql_query($query_tr) or die(mysql_error());
$tr= mysql_fetch_row($result_tr);

switch ($tr[1]) {
case '1': $pref_tr='ООО';break;
case '2': $pref_tr='ОАО';break;
case '3': $pref_tr='ИП';break;
case '4': $pref_tr='ЗАО';break;
case '6': $pref_tr='Физ.Л';break;}

$cl_tr_name='<br><font size="1">'.$pref_tr.' '.$transporters[$tr[0]].'</font>';
}



$query_auth = "SELECT `auth_id` FROM `pays_appoints` WHERE `id`='".mysql_escape_string($row['appoint'])."'";
$result_auth = mysql_query($query_auth) or die(mysql_error());
$auth= mysql_fetch_row($result_auth);

if(($_SESSION["group"]==4&&$auth[0]!=1)||$_SESSION["group"]==2||$_SESSION["group"]==1){


if($row['order']!='0') $order=$row['order']; else $order="-";

$query_docs = "SELECT `cl_bill` FROM `docs` WHERE `order`='".mysql_escape_string($order)."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());
$get_docs = mysql_fetch_row($result_docs);

if($get_docs[0]==""||$get_docs[0]=="0")$doc=""; else $doc=" (".$get_docs[0].")";
$notify="";
if($row['notify']!="")$notify='<br><font size="1">'.$row['notify'].'</font>';
if($row['car_id']!="0"){
$query_car = "SELECT `name`,`number`,`type` FROM `vtl_auto` WHERE `delete`='0' AND `id`='".(int)$row['car_id']."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car_id = mysql_fetch_row($result_car);
switch ($car_id[2]) {
case '1': $type='Т';break;
case '2': $type='П';break;
case '3': $type='Г';break;
case '4': $type='П';break;

} 



$car='<br><font size="1"><b>'.$type.'.</b> '.$car_id[0].'-'.$car_id[1].'</font>';} else $car="";



if($row['add_name']==1000) $user_name = '<b>Авто Платеж</b>'; else $user_name = $users[$row['add_name']]; 

$query_settings = "SELECT `international_number` FROM `orders` WHERE `id`='".mysql_escape_string($order)."'";
$result_settings = mysql_query($query_settings) or die(mysql_error());
$settings = mysql_fetch_array($result_settings);

if($settings['international_number']!="0") $order_number = '<font size="4"><b>'.$settings['international_number'].'</b></font> ('.$order.')'; else $order_number = '<font size="4"><b>'.$order.'</b></font>';
	
$data->rows[$i]['id'] = $row['id'];
    $data->rows[$i]['cell'] = array($row['id'],"<font size=\"4\">".date("d/m/Y",strtotime($row['date']))."</font>","<b><font size=\"3\">".$way."</font></b>",$nds,$category,"<b>".$pays_a[$row['appoint']]."</b>".$pretenzia,$order_number.$doc.$cl_tr_name,$cash_all.$notify.$car,"<font size=\"3\"><b>".$status."</b></font>",$user_name,$row['del_id'],$row['delete'],$owner);
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



$query_order= "SELECT * FROM `pays` WHERE `Id`='".$id."'";
$result_order = mysql_query($query_order) or die(mysql_error());
if(mysql_num_rows($result_order) >= 1){
	


	            $i = 0;
            while($row = mysql_fetch_array($result_order)) {
            	
if($row['status']==1){$del='<font color=red size=4>Платёж проведён.</font>Удалить выбранный платёж и создать новый?';} else {$del='Удалить выбранный платёж?';}

$query_company= "SELECT `name` FROM `company` WHERE `Id`='".mysql_escape_string($row['payment_source'])."'";
$result_company = mysql_query($query_company) or die(mysql_error());
$company = mysql_fetch_row($result_company);

if($row['payment_source']!=1){
$query_bill_pay= "SELECT `c_bill`,`c_bank` FROM `bill` WHERE `Id`='".mysql_escape_string($row['pay_bill'])."'";
$result_bill_pay = mysql_query($query_bill_pay) or die(mysql_error());
$bill_pays = mysql_fetch_row($result_bill_pay);
$bill_pay=" ( Счет № <b>".$bill_pays[0]."</b> в ".$bill_pays[1].")";
}


if($row['del_id']!=0){
$query = "SELECT `date` FROM `pays` WHERE `Id`='".mysql_escape_string($row['del_id'])."'";
$result = mysql_query($query) or die(mysql_error());
$del_row = mysql_fetch_row($result);
$del_id="Создан на основе удаленного платежа <b>№".$row['del_id']."</b> от <b>".date('d/m/Y',strtotime($del_row[0]))."</b> г.";}

if($row['nds']==2&&$row['way']==1&&$row['delete']!=1){
if($row['status']!=1)$alert='alert("Платеж не проведен!");'; else $alert='window.open("control/print_pay.php?mode=print&id='.$row['id'].'");';
$blank='<a class="button" id="btnPrint" href="#" onclick=\''.$alert.'\'>Квитанция</a>';

}




if($row['delete']!=1){$control="<a class=\"button\" id=\"btnEdit\" href=\"#\" onclick=\"$('#fa_pay').load('theme/forms/add_pay.php?pay_id=".$row['id']."');$('#fa_pay').dialog({ title: 'Редактировать платеж №".$row['id']."' },{width: 650,height: 750,modal: true,resizable: false});\">Редактировать</a><br>".$blank."<br><a class=\"button\" id=\"btnDelete\" href=\"#\" onclick=\"$('#result').html('$del');$('#result').dialog({ title: 'Внимание' },{ modal: true },{ resizable: false },{ buttons: [{text: 'Да',click: function() {\$.post('control/pays.php?mode=delete&id=".$row['id']."&group_id=".$_GET['group_id']."&status=".$row['status']."', function(data) {jQuery('#table').trigger('reloadGrid');$('#result').dialog('close');$('#result_temp').html(data);$('#result_temp').dialog({ title: 'Внимание' },{ height: 90 },{ width: 300 },{ modal: true },{ resizable: false });});}},{text: 'Нет',click: function() {\$(this).dialog('close');}}] });\">Удалить</a>";
}
if($row['delete']==1){$query = "SELECT `date`,`id` FROM `pays` WHERE `del_id`='".mysql_escape_string($row['id'])."'";
$result = mysql_query($query) or die(mysql_error());

if (mysql_num_rows($result)> 0){
$del_row = mysql_fetch_row($result);
$del_id="На основе данного удаленного платежа создан новый платёж <b>№".$del_row[1]."</b> от <b>".date('d/m/Y',strtotime($del_row[0]))."</b> г.";}
} 
        
	              $data->rows[$i]['cell'] =array($control."<img src=\"data/img/checkout.png\">","<fieldset><legend>Информация:</legend>Плательщик/Получатель: <b>«".$company[0]."»</b>".$bill_pay." <br>".$del_id."</fieldset><br><fieldset><legend>Комментарий:</legend>".$row['notify']."</fieldset>");
	                $i++;
	        }
	        }

header("Content-type: text/script;charset=utf-8");
echo json_encode($data);


}

if ($_GET['mode']=='delete') 
{
$id =$_GET['id'];
$group_id=$_GET['group_id'];
$status=$_GET['status'];

$query_status = "SELECT `status` FROM `pays` WHERE `delete`='0' AND `id`='".mysql_escape_string($id)."'";
$result_status = mysql_query($query_status) or die(mysql_error());	
$stats = mysql_fetch_row($result_status);

if($group_id==2||$group_id==4||$group_id==1){
if($status=='0'&&$stats[0]=='0'){	
$query = "UPDATE `pays` SET `delete`='1' WHERE Id='".mysql_escape_string($id)."'";
$result = mysql_query($query) or die(mysql_error());
echo '<font color="red" size="3">Платёж помечен на удаление!</font>';
} 
if($status=='1'&&$stats[0]=='1'){
echo '<font color="red" size="3">Платёж будет помечен на удаление после создания нового!</font><script type="text/javascript">$(function(){$(\'#fa_pay\').load(\'add_pay.html?delete_id='.$id.'\');$(\'#fa_pay\').dialog({ title: \'Новый платеж на основе удаленно платежа №'.$id.'\' },{width: 650,height: 750,modal: true,resizable: false});});</script>';}

} else {echo '<font color="red" size="3">Удаление платежа невозможно!</font>';}
}
?>