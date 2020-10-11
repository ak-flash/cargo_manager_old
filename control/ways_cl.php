<?php
// Подключение и выбор БД
include "../config.php";	
session_start();

# ВНИМАНИЕ!!!
# Данный код не имеет проверок запрашиваемых данных
# что может стать причиной взлома! Обязательно проверяйте все данные
# поступающие от клиента

$page = $_GET['page'];      // Номер запришиваемой страницы
$limit = $_GET['rows'];     // Количество запрашиваемых записей
$sidx = $_GET['sidx'];      // Номер элемента массива по котору следует производить сортировку
                            // Проще говоря поле, по которому следует производить сортировку
$sord = $_GET['sord'];      // Направление сортировки




if ($_GET['mode']=='ways') 
{
$manager =$_SESSION["user_id"];
$group =$_SESSION["group"];
// Если не указано поле сортировки, то производить сортировку по первому полю
if(!$sidx) $sidx =1;


if ($group=='3') $result = mysql_query("SELECT COUNT(*) AS count FROM `orders`,`clients` WHERE `delete`='0' AND `orders`.`client`=`clients`.`id` AND `clients`.`cl_manager`='".mysql_escape_string($manager)."'"); else $result = mysql_query("SELECT COUNT(*) AS count FROM `orders` WHERE `delete`='0'");
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
if(@$_GET['s_data']!=''){
if ($group=='3') $query_clients = "SELECT `orders`.`id` from `orders`,`clients` WHERE (`clients`.`name` like '%".mysql_escape_string($_GET['s_data'])."%') AND  `orders`.`client`=`clients`.`id` AND `clients`.`cl_manager`='".mysql_escape_string($manager)."'"; else $query_clients = "SELECT `orders`.`id` from `orders`,`clients` WHERE (`clients`.`name` like '%".mysql_escape_string($_GET['s_data'])."%') AND `orders`.`client`=`clients`.`id`";

$result_clients = mysql_query($query_clients) or die(mysql_error());
while($clients = mysql_fetch_row($result_clients)) {
$id_mass_cl[$g]=$clients[0];
$g++;
}
}

$g=0;
$query_clients_m="SELECT `id` FROM `clients` WHERE `cl_manager`='".mysql_escape_string($manager)."'";
$result_clients_m = mysql_query($query_clients_m) or die(mysql_error());
while($clients_m = mysql_fetch_row($result_clients_m)) {
$id_mass_cl_m[$g]=$clients_m[0];
$g++;}

$g=0;
if(@$_GET['s_data_in']!=''){
if ($group=='3') $query_city_in = "SELECT `orders`.`id` FROM `orders`,`adress` WHERE `adress`.`city` like '%".mysql_escape_string($_GET['s_data_in'])."%' AND `orders`.`in_adress`=`adress`.`id` AND  `orders`.`client` IN (".implode(',' , $id_mass_cl_m).")"; else $query_city_in = "SELECT `orders`.`id` FROM `orders`,`adress` WHERE `adress`.`city` like '%".mysql_escape_string($_GET['s_data_in'])."%' AND `orders`.`in_adress`=`adress`.`id`";
$result_city_in = mysql_query($query_city_in) or die(mysql_error());
while($city_in = mysql_fetch_row($result_city_in)) {
$id_mass_in[$g]=$city_in[0];
$g++;
}	
}
	
$g=0;	
if(@$_GET['s_data_out']!=''){
if ($group=='3') $query_city_out = "SELECT `orders`.`id` FROM `orders`,`adress` WHERE `adress`.`city` like '%".mysql_escape_string($_GET['s_data_out'])."%' AND `orders`.`out_adress`=`adress`.`id` AND `orders`.`client` IN (".implode(',' , $id_mass_cl_m).")"; else $query_city_out = "SELECT `orders`.`id` FROM `orders`,`adress` WHERE `adress`.`city` like '%".mysql_escape_string($_GET['s_data_out'])."%' AND `orders`.`out_adress`=`adress`.`id`";
$result_city_out = mysql_query($query_city_out) or die(mysql_error());
while($city_out = mysql_fetch_row($result_city_out)) {
$id_mass_out[$g]=$city_out[0];
$g++;
}	
}

if($id_mass_in&&$id_mass_out)$id_mass_city=array_intersect($id_mass_in,$id_mass_out);
if($id_mass_in&&!$id_mass_out)$id_mass_city=$id_mass_in;
if(!$id_mass_in&&$id_mass_out)$id_mass_city=$id_mass_out;

if($id_mass_cl&&$id_mass_city)$id_mass=array_intersect($id_mass_cl,$id_mass_city);
if($id_mass_cl&&!$id_mass_city)$id_mass=$id_mass_cl;
if(!$id_mass_cl&&$id_mass_city)$id_mass=$id_mass_city;


if($id_mass){

$count=count($id_mass);
sort($id_mass);


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


if ($group=='3') $query = "SELECT `orders`.`id`,`in_adress`,`out_adress`,`clients`.`name`,`cl_cash`,`cl_pref`,`cl_nds`,`orders`.`data`,`orders`.`manager` FROM `orders`,`clients` WHERE `orders`.`id` IN (".implode(',' , $id_mass).") AND  `orders`.`client`=`clients`.`id` AND `clients`.`cl_manager`='".mysql_escape_string($manager)."' ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit; else $query = "SELECT `orders`.`id`,`in_adress`,`out_adress`,`clients`.`name`,`cl_cash`,`cl_pref`,`cl_nds`,`orders`.`data`,`orders`.`manager` FROM `orders`,`clients` WHERE `orders`.`id` IN (".implode(',' , $id_mass).") AND  `orders`.`client`=`clients`.`id` ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;

} else $query = "SELECT `id` FROM `orders` WHERE `id`='0'";

} else {

if ($group=='3') $query = "SELECT `orders`.`id`,`in_adress`,`out_adress`,`clients`.`name`,`cl_cash`,`cl_pref`,`cl_nds`,`orders`.`data`,`orders`.`manager` FROM `orders`,`clients` WHERE `delete`='0' AND  `orders`.`client`=`clients`.`id` AND `clients`.`cl_manager`='".mysql_escape_string($manager)."' ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit; else $query = "SELECT `orders`.`id`,`in_adress`,`out_adress`,`clients`.`name`,`cl_cash`,`cl_pref`,`cl_nds`,`orders`.`data`,`orders`.`manager` FROM `orders`,`clients` WHERE `delete`='0' AND  `orders`.`client`=`clients`.`id` ORDER BY ".$sidx." ".$sord." LIMIT ".$start.", ".$limit;

}

$result = mysql_query($query) or die(mysql_error());


$query_user = "SELECT `id`,`name` FROM `workers` WHERE `group`!='5'";
$result_user = mysql_query($query_user) or die(mysql_error());

while($user = mysql_fetch_row($result_user)) {
$pieces = explode(" ", $user[1]);
$managers=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
$users[$user[0]]= $managers;
}

// Строки данных для таблицы
$i = 0;
while($row = mysql_fetch_row($result)) {

switch ($row[5]) {
case '1': $pref_cl='ООО';break;
case '2': $pref_cl='ОАО';break;
case '3': $pref_cl='ИП';break;
case '4': $pref_cl='ЗАО';break;
case '5': $pref_cl='';break;}

switch ($row[6]) {
case '0': $nds_cl='без НДС';break;
case '1': $nds_cl='с НДС';break;
case '2': $nds_cl='НАЛ';break;
}

$str_in = explode('&',$row[1]);
$str_adr_in = (int)sizeof($str_in)-2;
$res_in = $str_in[$str_adr_in];

$str_out = explode('&',$row[2]);
$res_out = $str_out[0];

$query_adress = "SELECT `id`,`city` FROM `adress` WHERE `id`='".$res_in."' OR `id`='".$res_out."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());

while($adress = mysql_fetch_row($result_adress)) {
$adresses[$adress[0]]= $adress[1];
}




$data->rows[$i]['id'] = $row[0];
    $data->rows[$i]['cell'] = array($row[0],date("d/m/Y",strtotime($row[7])),$pref_cl.' <b><i>«'.$row[3].'»</b> ('.$users[$row[8]].')',$adresses[$res_in],$adresses[$res_out],'<b>'.$row[4].'</b> руб. ('.$nds_cl.')');
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



$query_order= "SELECT * FROM `orders` WHERE `id`='".mysql_escape_string($id)."'";
$result_order = mysql_query($query_order) or die(mysql_error());
if(mysql_num_rows($result_order) >= 1){
	


	            $i = 0;
            while($row = mysql_fetch_array($result_order)) {
            	
$res_adr_in='';
$str_in = explode('&',$row['in_adress']);
$str_adr_in =(int)sizeof($str_in)-2;
$f=0;
while ($f<=$str_adr_in) {
$query_adress = "SELECT * FROM `adress` WHERE `id`='".$str_in[($str_adr_in-$f)]."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());
$adress = mysql_fetch_row($result_adress);
if($adress[8]=="0") $flat=""; else $flat=' кв.'.$adress[8];
if($adress[3]==""||$adress[3]=="0") $obl=""; else $obl=$adress[3]." обл.";	
if($adress[6]==""||$adress[6]=="0") $dom=""; else $dom=" д.".$adress[6];
if($adress[7]==""||$adress[7]=="0") $dom_extra=""; else $dom_extra='/'.$adress[7];
	
$res_adr_in =$res_adr_in."<div style='padding: 5px;margin: 10px;margin-left: 40px;background: #ddd;border: 1px solid #bbb;width: 90%;'>".($f+1).") ".$adress[2].', '.$obl.' '.$adress[4].' ул. '.$adress[5].$dom.$dom_extra.$flat.' ('.$adress[9].' - '.$adress[10].')</div>';




$f++;
}



$str_out = explode('&',$row['out_adress']);
$str_adr_out =(int)sizeof($str_out)-2;
$f=0;
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
$f++;
}

        
	              $data->rows[$i]['cell'] =array("<img src=\"data/img/lorrygreen.png\">",'<fieldset><legend><b>Загрузка:</b></legend>'.$res_adr_in.'</fieldset><fieldset><legend><b>Выгрузка:</b></legend>'.$res_adr_out.'</fieldset><fieldset><legend><b>Примечание:</b></legend>'.$row['notify'].'</fieldset>');
	                $i++;
	        }
	        }

header("Content-type: text/script;charset=utf-8");
echo json_encode($data);


}

?>