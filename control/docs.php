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


if ($_GET['mode'] == 'docs') {
    $manager = $_GET['user'];

//if($manager=='23') $dop_query=" OR (`manager`='6' AND (`client`='422' OR `client`='424'))"; else $dop_query="";

$group =$_GET['group'];
// Если не указано поле сортировки, то производить сортировку по первому полю
if(!$sidx) $sidx =1;



$query_clients = "SELECT `id`,`name` FROM `clients`";
$result_clients = mysql_query($query_clients) or die(mysql_error());

while($clients = mysql_fetch_row($result_clients)) {
$client[$clients[0]]= $clients[1];
}

$query_tr = "SELECT `id`,`name` FROM `transporters`";
$result_tr = mysql_query($query_tr) or die(mysql_error());

while($tr = mysql_fetch_row($result_tr)) {

$transporters[$tr[0]]= $tr[1];
}




if ($group=='3') {$result = mysql_query("SELECT COUNT(*) AS count FROM `docs` WHERE `order` IN (SELECT `id` FROM `orders` WHERE (`manager`='".$manager."' OR `tr_manager`='".$manager."'".$dop_query."))");} else {$result = mysql_query("SELECT COUNT(*) AS count FROM `docs` WHERE `order` IN (SELECT `id` FROM `orders`)");}



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

if ($group=='3') {
$query_search = "SELECT * FROM `docs` WHERE `order` IN (SELECT `id` FROM `orders` WHERE (`manager`='".$manager."' OR `tr_manager`='".$manager."' ".$dop_query.")) ORDER BY `Id` DESC";
} else {$query_search = "SELECT * FROM `docs` WHERE `order` IN (SELECT `id` FROM `orders`) ORDER BY `Id` DESC"; }
$result_search = mysql_query($query_search) or die(mysql_error());



while($row = mysql_fetch_array($result_search)) {
$query_order = "SELECT `client`,`cl_nds`,`transp`,`tr_nds`,`tr_event`,`international_number`  FROM `orders` WHERE `id`='".mysql_escape_string($row['order'])."'";
$result_order = mysql_query($query_order) or die(mysql_error());
$get_order = mysql_fetch_row($result_order);


	
if(mb_stristr($row['id'], $_GET['s_data'])||mb_stristr($get_order[5], $_GET['s_data'])||mb_stristr($client[$get_order[0]], $_GET['s_data'])||mb_stristr($transporters[$get_order[2]], $_GET['s_data'])||mb_stristr($row['order'], $_GET['s_data'])||mb_stristr($row['cl_bill'], $_GET['s_data'])||mb_stristr($row['tr_bill'], $_GET['s_data']))	{
$id_mass[$g]=$row['id'];$g++;    }
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
if ($group=='3') {
$query = "SELECT * FROM `docs` WHERE `id` IN (".implode(',' , $id_mass).") ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit;
} else {$query = "SELECT * FROM `docs` WHERE `id` IN (".implode(',' , $id_mass).") ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit;}


} else {$query = "SELECT * FROM `orders` WHERE `id`='0'";}
} else {
if ($group=='3') {
$query = "SELECT * FROM `docs` WHERE `order` IN (SELECT `id` FROM `orders` WHERE (`manager`='".$manager."' OR `tr_manager`='".$manager."')) ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit;
} else {if(@$_GET['search']=='true')$query = "SELECT * FROM `docs` WHERE `order` IN (SELECT `id` FROM `orders` ) ORDER BY `Id` DESC"; else $query = "SELECT * FROM `docs` WHERE `order` IN (SELECT `id` FROM `orders`) ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit;}





if (@$_GET["doc_id"]!='') {
if ($group=='1'||$group=='2'||$group=='4'){if(@$_GET['search']=='true')$query = "SELECT * FROM `docs` WHERE `order` IN (SELECT `id` FROM `orders`) AND `id`='".$_GET["doc_id"]."' ORDER BY `Id` DESC"; else $query = "SELECT * FROM `docs` WHERE `order` IN (SELECT `id` FROM `orders`) AND `id`='".$_GET["doc_id"]."' ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit;} else {$query = "SELECT * FROM `docs` WHERE `order` IN (SELECT `id` FROM `orders` WHERE (`manager`='".$manager."' OR `tr_manager`='".$manager."')) AND `id`='".$_GET["doc_id"]."' ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit;}
}

}

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

$query_order = "SELECT `client`,`cl_nds`,`transp`,`tr_nds`,`tr_event`,`cl_cash`,`tr_cash`,`cl_kop`,`international_number`,`cl_currency`,`tr_currency` FROM `orders` WHERE `id`='".mysql_escape_string($row['order'])."'";
$result_order = mysql_query($query_order) or die(mysql_error());
$get_order = mysql_fetch_row($result_order);
	
switch ($get_order[1]) {
case '0': $nds_cl='без НДС';break;
case '1': $nds_cl='с НДС';break;
case '2': $nds_cl='НАЛ';break;
}
switch ($get_order[3]) {
case '0': $nds_tr='без НДС';break;
case '1': $nds_tr='с НДС';break;
case '2': $tr_event='НАЛ';break;
}
switch ($get_order[4]) {
case '1': $tr_event='Загрузка';break;
case '2': $tr_event='Выгрузка';break;
case '3': $tr_event='Факс.';break;
case '4': $tr_event='Ориг.';break;
}

if($row['date_add_bill']!="0000-00-00"&&$row['date_add_bill']!="1970-01-01") {$date_add_bill=date("d/m/Y",strtotime($row['date_add_bill']));} else $date_add_bill="-";	
if($row['date_cl_receve']!="0000-00-00"&&$row['date_cl_receve']!="1970-01-01") {$date_cl_receve=date("d/m/Y",strtotime($row['date_cl_receve']));} else $date_cl_receve="-";

if($row['date_tr_receve']!="0000-00-00"&&$row['date_tr_receve']!="1970-01-01") {$date_tr_receve=date("d/m/Y",strtotime($row['date_tr_receve']));} else $date_tr_receve="-";


if($row['date_tr_bill']=="1970-01-01"||$row['date_tr_akt']=="1970-01-01"||$row['date_tr_ttn']=="1970-01-01") $status_doc="0"; else $status_doc="1";

if($row['cl_bill']=="0")$cl_bill="-"; else $cl_bill=$row['cl_bill'];

$cl_pay=0;
$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($row['order'])."' AND `delete`='0' AND `appoint`='1' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$cl_pay=(int)$pay[0]+(int)$cl_pay;
}
$cl_check=0;
if((int)$cl_pay>0)$cl_check=2;
if((int)$get_order[5]*100==(int)$cl_pay)$cl_check=1;


$tr_pay=0;
$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($row['order'])."' AND `delete`='0' AND `appoint`='2' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$tr_pay=(int)$pay[0]+(int)$tr_pay;
}
$tr_check=0;
if((int)$tr_pay>0)$tr_check=2;
if((int)$get_order[6]*100==(int)$tr_pay)$tr_check=1;


if($get_order[7]!=0)$cl_kop='.'.$get_order[7]; else $cl_kop='';

if($get_order[8]!="0") $order_number='<font size="4"><b>'.$get_order[8].'</b></font><br><font size="1">('.$row['order'].')</font>'; else $order_number='<font size="4"><b>'.$row['order'].'</b></font>';

$data->rows[$i]['id'] = $row['id'];
    $data->rows[$i]['cell'] = array($row['id'],$order_number,' <b>«'.$client[$get_order[0]]."»</b>",' <b>'.$get_order[5].$cl_kop.'</b> '.$get_order[9].'<br>'.$nds_cl,"<b>".$cl_bill."</b>",$date_add_bill,$date_cl_receve,"",' <b>«'.$transporters[$get_order[2]]."»</b>",' <b>'.$get_order[6].'</b> '.$get_order[10].'<br>'.$nds_tr,$date_tr_receve,$tr_event,$status_doc,$cl_check,$tr_check);
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

$query_docs= "SELECT * FROM `docs` WHERE `Id`=".$id;
$result_docs = mysql_query($query_docs) or die(mysql_error());


if(mysql_num_rows($result_docs) >= 1){

	            $i = 0;
            while($row = mysql_fetch_array($result_docs)) {
 	
if($row['date_add_bill']!="0000-00-00"&&$row['date_add_bill']!="1970-01-01") {$date_add_bill="получен ".date("d/m/Y",strtotime($row['date_add_bill']));} else $date_add_bill="<font color='red'>отсутствует</font>";	
if($row['date_cl_receve']!="0000-00-00"&&$row['date_cl_receve']!="1970-01-01") {$date_cl_receve=date("d/m/Y",strtotime($row['date_cl_receve']));} else $date_cl_receve="<font color='red'>отсутствует</font>";
if($row['date_tr_bill']!="0000-00-00"&&$row['date_tr_bill']!="1970-01-01") {$date_tr_bill="получен ".date("d/m/Y",strtotime($row['date_tr_bill']));} else $date_tr_bill="<font color='red'>отсутствует</font>";	
if($row['date_tr_akt']!="0000-00-00"&&$row['date_tr_akt']!="1970-01-01") {$date_tr_akt="получен ".date("d/m/Y",strtotime($row['date_tr_akt']));} else $date_tr_akt="<font color='red'>отсутствует</font>";
if($row['date_tr_ttn']!="0000-00-00"&&$row['date_tr_ttn']!="1970-01-01") {$date_tr_ttn="получен ".date("d/m/Y",strtotime($row['date_tr_ttn']));} else $date_tr_ttn="<font color='red'>отсутствует</font>";

if($row['date_tr_receve']!="0000-00-00"&&$row['date_tr_receve']!="1970-01-01") {$date_tr_receve=date("d/m/Y",strtotime($row['date_tr_receve']));} else $date_tr_receve="<font color='red'>отсутствует</font>";

if($row['add_date_cl_sent']!="0000-00-00"&&$row['add_date_cl_sent']!="1970-01-01") {$date_add_all_sent=date("d/m/Y",strtotime($row['add_date_cl_sent']));} else $date_add_all_sent="<font color='red'>отсутствует</font>";

switch ($row['13']) {
case '0': $cl_event='-';break;
case '3': $cl_event='Поступление факсимильных документов';break;
case '4': $cl_event='Поступление оригинальных документов';break;}
switch ($row['14']) {
case '0': $tr_event='-';break;
case '3': $tr_event='Поступление факсимильных документов';break;
case '4': $tr_event='Поступление оригинальных документов';break;}

 if(is_dir("../Uploads/".$row['1']."/tr/")){
$dir = opendir("../Uploads/".$row['1']."/tr");
while(($file = readdir($dir))) 
  { 
   if($file!="."&&$file!="..") $tr_file=$tr_file.'<a href="../Uploads/'.$row['1'].'/tr/'.$file.'" target="_blank">'.$file."</a><br>";
  } 
  closedir($dir);
 }

 if(is_dir("../Uploads/".$row['1']."/cl/")){
$dir = opendir("../Uploads/".$row['1']."/cl");
while(($file = readdir($dir))) 
  { 
   if($file!="."&&$file!="..") $cl_file=$cl_file.'<a href="../Uploads/'.$row['1'].'/cl/'.$file.'" target="_blank">'.$file."</a><br>";
  } 
  closedir($dir);
 }
		
	              $data->rows[$i]['cell'] =array("<fieldset style='text-align:center;width:170px;'><legend>Пакет документов:</legend>
	             <a class=\"button\" id=\"btnEdit\" href=\"#\" onclick=\"$('#fa_add_docs').load('theme/forms/add_docs.php?doc_id=".$row['id']."');$('#fa_add_docs').dialog({ title: 'Редактировать пакет документов №".$row['id']."' },{width: 700,height: 810,modal: true,resizable: false});return false\">Редактировать</a></fieldset><fieldset style='text-align:center;width:170px;'><legend>Примечание:</legend>".$row['notify']."</fieldset>","<fieldset style=''><legend>Отправленные <b>Клиенту</b> документы:</legend>
<table cellpadding='5'><tr><td align='right'><b>Номер счета:</b>&nbsp;&nbsp;</td><td width='240' align='center'>№<b>".$row['cl_bill']."</b> (".$date_add_bill.")</td><td rowspan='4'><img src='data/img/money_bag.png' style='float:right;'></td></tr>
<tr><td align='right'><b>Дата отправки всех документов:</b>&nbsp;&nbsp;</td><td width='240' align='center'>".$date_add_all_sent."</td></tr>
<tr><td align='right'><font color='green'><b>Дата получения всех документов:</b></font>&nbsp;&nbsp;</td><td align='center'>".$cl_event."<br><br><font size='5'>".$date_cl_receve."</font></td></tr><tr><td align='right'><b>Загружены файлы:</b>&nbsp;&nbsp;</td><td>".$cl_file."</td></tr></table></fieldset><fieldset style=''><legend>Полученные от <b>Перевозчика</b> документы:</legend>
<table cellpadding='5'><tr><td align='right'><b>Номер счета:</b>&nbsp;&nbsp;</td><td width='240' align='center'>№<b>".$row['tr_bill']."</b> (".$date_tr_bill.")</td><td rowspan='5'><img src='data/img/lorrygreen.png' style='float:right;'></td></tr>

<tr><td align='right'>Номер акта:&nbsp;&nbsp;</td><td align='center'>№<b>".$row['tr_akt']."</b> (".$date_tr_akt.")</td></tr>
	<tr><td align='right'>Номер ТТН:&nbsp;&nbsp;</td><td align='center'>№<b>".$row['tr_ttn']."</b> (".$date_tr_ttn.")</td></tr><tr><td align='right'><font color='green'><b>Дата получения всех документов:</b></font>&nbsp;&nbsp;</td><td align='center'>".$tr_event."<br><br><font size='5'>".$date_tr_receve."</font></td></tr><tr><td align='right'><b>Загружены файлы:</b>&nbsp;&nbsp;</td><td>".$tr_file."</td></tr></table></fieldset>
");
	                $i++;
	        }
	        }

header("Content-type: text/script;charset=utf-8");
echo json_encode($data);


}



if ($_GET['mode']=='delete') 
{
$id =$_GET['id'];


$query = "SELECT `block` FROM `orders` WHERE Id='".mysql_escape_string($id)."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_row($result);

if ($row[0]=='1'){echo '<font color="red" size="3"><div alegn="center">Заявка заблокирована! Обратитесь к директору...</div></font>';}
else {$query = "UPDATE `orders` SET `delete`='1' WHERE Id='".$id."'";
$result = mysql_query($query) or die(mysql_error());
echo '<font color="red" size="3">Заявка помечена на удаление!</font>';	}

}

if ($_GET['mode']=='total') 
{
$manager =$_GET['user'];
$group =$_GET['group'];
$total=0;
if ($_GET['date_start']<>''){
$date_start =$_GET['date_start'];
$date_end =$_GET['date_end'];

if($group=='3'){$query = "SELECT * FROM `orders` WHERE `manager`='".$manager."' AND DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."'";} else {$query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."'";}
} else {
if($group=='3'){$query = "SELECT * FROM `orders` WHERE `manager`='".$manager."'";} else {$query = "SELECT * FROM `orders`";}
}




$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) {
$total=$total+$row['cl_cash'];
}

echo $total;




}

$fh = fopen("somefile.txt", "a+"); 
fwrite($fh, $date_end); 
fclose($fh);
?>