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


if ($_GET['mode'] == 'transp') {

// Если не указано поле сортировки, то производить сортировку по первому полю
    if (!$sidx) $sidx = 1;


$result = mysql_query("SELECT COUNT(*) AS count FROM `transporters`");

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
$query = "SELECT * FROM `transporters` ORDER BY ".$sidx." ".$sord;
} else {$query = "SELECT * FROM `transporters` ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;}

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

$query_car = "SELECT `car_name` FROM `tr_autopark` WHERE `transporter`='".$row['id']."' AND `delete`=0";
$result_car = mysql_query($query_car) or die(mysql_error());

$f = 0;
while($car = mysql_fetch_row($result_car)) {
$f++;
}
$pref_cl='';
switch ($row['pref']) {
case '1': $pref_tr='ООО';break;
case '2': $pref_tr='ОАО';break;
case '3': $pref_tr='ИП';break;
case '4': $pref_tr='ЗАО';break;
case '5': $pref_tr='';break;
case '6': $pref_tr='Физ.Л.';break;
case '7': $pref_tr='АО';break;
}

switch ($row['nds']) {
case '0': $nds_tr='без НДС';break;
case '1': $nds_tr='с НДС';break;
case '2': $nds_tr='НАЛ';break;
}

mb_internal_encoding("UTF-8");

if(@$_GET['search']=='true'){	
if(mb_stristr($row['id'], $_GET['s_data'])||mb_stristr($row['name'], $_GET['s_data'])||mb_stristr($row['tr_support'], $_GET['s_data'])||mb_stristr($row['tr_phone'], $_GET['s_data']))	{
 $data->rows[$i]['id'] = $row[0];
    $data->rows[$i]['cell'] = array($row['id'],$pref_tr,"<b>«".$row['name']."»</b>",$nds_tr,$f,$row['tr_support'],'8 ('.$row['tr_pref_phone'].') '.$row['tr_phone'],$row['block']);
    $i++;}$data->records=$i;
} else {
	
	if($f==0)$f="<b><font color=red>".$f."</font></b>"; else $f="<b><font color=green>".$f."</font></b>";
	
	if($row['tr_phone']==0)$tr_phone="-"; else $tr_phone='8 ('.$row['tr_pref_phone'].') '.$row['tr_phone'];
	
if($row['id']==1){
$data->rows[$i]['id'] = $row[0];
    $data->rows[$i]['cell'] = array($row['id'],"","<b>".$row['name']."</b>",$nds_tr,$f,"",'',"",$row['block']);
    $i++;
} else {
$data->rows[$i]['id'] = $row[0];
    $data->rows[$i]['cell'] = array($row['id'],$pref_tr,"<b>«".$row['name']."»</b>",$nds_tr,$f,$row['tr_support'],$tr_phone, '<b>'.$row['tr_code_ati'].'</b>',$row['block']);
    $i++;
}
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

$query_cont = "SELECT `id`,`name` FROM `company`";
$result_cont = mysql_query($query_cont) or die(mysql_error());

while($cont = mysql_fetch_row($result_cont)) {
$tr_cont[$cont[0]]= $cont[1];
}

$query_car = "SELECT * FROM `tr_autopark` WHERE `transporter`='".$id."' AND `delete`=0";
$result_car = mysql_query($query_car) or die(mysql_error());
$f=0;
$car="";
while($car_info = mysql_fetch_row($result_car)) {
switch ($car_info[6]) {
case '1': $car_load='верхняя';break;
case '2': $car_load='задняя';break;
case '3': $car_load='боковая';break;
} 


  
$car=$car.($f+1).") ".$car_info[2]." [<b>".$car_info[3]."</b>] (".$car_info[4]." кг, ".$car_info[5]." м3, ".$car_load.", ".$car_info[12].") - ".$car_info[9]." - (".$car_info[11].")<br>";
$f++;
}

$query_order= "SELECT * FROM `transporters` WHERE `Id` LIKE ".$id;
$result_order = mysql_query($query_order) or die(mysql_error());
if(mysql_num_rows($result_order) >= 1){
	


	            $i = 0;
            while($row = mysql_fetch_array($result_order)) {
 $query_adress = "SELECT * FROM `adress` WHERE `id`='".mysql_escape_string($row['tr_adr_f'])."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());
$adress_f = mysql_fetch_row($result_adress);

$query_adress = "SELECT * FROM `adress` WHERE `id`='".mysql_escape_string($row['tr_adr_u'])."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());
$adress_u = mysql_fetch_row($result_adress);          	

 switch ($row['tr_point']) {
case '1': $tr_point='Загрузка';break;
case '2': $tr_point='Выгрузка';break;
case '3': $tr_point='Поступление факсимильных документов';break;
case '4': $tr_point='Поступление оригинальных документов';break;
}   
 
 if($adress_f[8]=="0") $flat_f=""; else $flat_f=" - ".$adress_f[8];
if($adress_u[8]=="0") $flat_u=""; else $flat_u=" - ".$adress_u[8];
if($adress_f[1]=="0") $postcode_f=""; else $postcode_f=$adress_f[1];       	
if($adress_u[1]=="0") $postcode_u=""; else $postcode_u=$adress_u[1];     	


$query_order= "SELECT `in_adress`,`out_adress` FROM `orders` WHERE `transp`='".mysql_escape_string($row['id'])."' LIMIT 10";
$result_order = mysql_query($query_order) or die(mysql_error());
 if(mysql_num_rows($result_order) >= 1){
 while($row_ord= mysql_fetch_row($result_order)) {
$str_in= explode('&',$row_ord[0]);
$x=0;
while ($x<=(count($str_in)-2)) {$ways[]=$str_in[$x];$x++;}
$str_in= explode('&',$row_ord[1]);
$x=0;
while ($x<=(count($str_in)-2)) {$ways[]=$str_in[$x];$x++;}
 }
 }
  if(count($ways)>=1){
$query_adress = "SELECT `city`,`obl` FROM `adress` WHERE `id` IN (".implode(',' ,  $ways).") ";
$result_adress = mysql_query($query_adress) or die(mysql_error());
while($row_adr= mysql_fetch_row($result_adress)) {
$way.='<b>'.$row_adr[0].'</b> ('.$row_adr[1].');&nbsp;';
 }
  }
 
$query_user = "SELECT `name` FROM `workers` WHERE `id`='".mysql_escape_string($row['tr_manager'])."'";
$result_user = mysql_query($query_user) or die(mysql_error());
$user = mysql_fetch_row($result_user);
$pieces = explode(" ", $user[0]);
$tr_manager=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";



if($row['id']=='2'||$row['transp']=='-1') {

$query_driver = "SELECT `name`,`pref_phone`,`phone`,`passport` FROM `workers` WHERE `group`='5' AND `delete`='0'";
$result_driver = mysql_query($query_driver) or die(mysql_error());
while($driver = mysql_fetch_row($result_driver)){
$vtl_drivers.=$driver[0].' (8-'.$driver[1].'-'.$driver[2].') '.$driver[3].'<hr>';


}

} else $vtl_drivers='';




 if($row['id']==1){$data->rows[$i]['cell'] =array("<fieldset style=\"width:170px;\"><legend><b>Перевозчик:</b></legend><a class=\"button\" id=\"btnEdit\" href=\"javascript:;\" onclick=\"$('#fa_tr').load('theme/forms/add_transporter.php?tr=".$row['id']."');$('#fa_tr').dialog({ title: 'Редактировать перевозчика №".$row['id']."' },{width: 990,height: 560,modal: true,resizable: false});\">Редактировать</a></fieldset><br><fieldset style=\"width:170px;\"><legend><b>Направления:</b></legend><a class=\"button\" id=\"btnShowWay\" href=\"ways.php?tr_id=".$row['id']."&m=true\">Показать</a></fieldset><br><img src=\"data/img/lorrygreen.png\">","<fieldset><legend><b>Автотранспорт:</b></legend>".$car."</fieldset><fieldset><legend><b>Примечание:</b></legend>".$row['tr_notify']."</fieldset>");
	                $i++;} else {       
	              $data->rows[$i]['cell'] =array("<fieldset style=\"width:170px;\"><legend><b>Перевозчик:</b></legend><a class=\"button\" id=\"btnEdit\" href=\"javascript:;\" onclick=\"$('#fa_tr').load('theme/forms/add_transporter.php?tr=".$row['id']."');$('#fa_tr').dialog({ title: 'Редактировать перевозчика №".$row['id']."' },{width: 990,height: 560,modal: true,resizable: false});\">Редактировать</a></fieldset><br><fieldset style=\"width:170px;\"><legend><b>Распечатать:</b></legend>
<font size=2><input type=\"checkbox\" name=\"md\" id=\"md\" value=\"0\" onclick=\"if(this.checked){\$('#md').val(1);} else {\$('#md').val(0);}\" >&nbsp;Добавить пункт о штрафах в договор</font><hr>	              
<a class=\"button\" id=\"btnAdd_details\" href=\"javascript:;\" onclick=\"window.location.href='control/print_c.php?mode=tr&id=".base64_encode($row['id'])."&md='+$('#md').val();\">Договор</a><a class=\"button\" id=\"btnAdd_details\" href=\"".$row['id']."\">Реквизиты</a></fieldset><br><fieldset style=\"width:170px;\"><legend><b>Направления:</b></legend><a class=\"button\" id=\"btnShowWay\" href=\"ways.php?tr_id=".$row['id']."&m=true\">Показать</a></fieldset><br><img src=\"data/img/lorrygreen.png\">","<font size=4 style='margin-left:30px;'>Куратор перевозчика:</font> <font size=5 style='text-shadow: 0 1px 0 #DBDBDB;'><b>".$tr_manager."</b></font><hr><fieldset><legend><b>Адрес (фактич.):</b></legend>".$postcode_f.' '.$adress_f[2].' '.$adress_f[3].' обл. <b>'.$adress_f[4].'</b> ул.'.$adress_f[5].' д. '.$adress_f[6].' '.$adress_f[7].$flat_f."</fieldset><fieldset><legend><b>Адрес (юридич.):</b></legend>".$postcode_u.' '.$adress_u[2].' '.$adress_u[3].' обл. <b>'.$adress_u[4].'</b> ул.'.$adress_u[5].' д. '.$adress_u[6].' '.$adress_u[7].$flat_u."</fieldset><fieldset><legend><b>Водители:</b></legend>".$vtl_drivers."</fieldset><fieldset><legend><b>Автотранспорт:</b></legend>".$car."</fieldset><fieldset style=\"width:380px;float:left;\"><legend><b>Реквизиты:</b></legend><table><tr><td width=\"60\"><b>ИНН:</b></td><td >".$row['tr_inn']."</td></tr><tr><td><b>КПП:</b></td><td>".$row['tr_kpp']."</td></tr><tr><td><b>ОГРН:</b></td><td>".$row['tr_ogrn']."</td></tr><tr><td><b>р/сч:</b></td><td>".$row['tr_rs']."</td></tr><tr><td><b>Банк в:</b></td><td>".$row['tr_bank']."</td></tr><tr><td><b>БИК:</b></td><td>".$row['tr_bik']."</td></tr></table></fieldset><fieldset style=\"width:400px;float:right;\"><legend><b>Информация:</b></legend><b>Контактер:</b> <font size=\"4\">".$tr_cont[$row['tr_cont']]."</font><br><b>Период расчётов:</b> ".$row['tr_time']." дн.<br><b>Точка оплаты:</b> ".$tr_point."</fieldset><fieldset style=\"width:400px;float:right;margin-top:-5px;\"><legend><b>Контакты:</b></legend><b>Ответственное лицо:</b> ".$row['tr_chief']."<br><b>Должность ответственного лица:</b> ".$row['tr_dchief']."<br><b>Основание:</b> ".$row['tr_ochief']."<br><hr style=\"width: 100%; height: 2px;\" /><b>Контактное лицо:</b> ".$row['tr_support']."<br><b>Должность контактного лица:</b> ".$row['tr_dsupport']."<br><b>E-mail:</b> ".$row['tr_mail']."<br><b>ICQ:</b> ".$row['tr_icq']."</fieldset><fieldset><legend><b>Направления:</b></legend>".$way."</fieldset><fieldset><legend><b>Примечание:</b></legend>".$row['tr_notify']."</fieldset>");
	                $i++;
	        }}
	        }

header("Content-type: text/script;charset=utf-8");
echo json_encode($data);


}

?>