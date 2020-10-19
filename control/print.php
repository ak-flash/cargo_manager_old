<?php
include "../config.php";

$DOCS_TEMPLATES_DIR = 'docs_templates/';

function convert($temp) {
return iconv("UTF-8", "windows-1251", $temp);
}

if(@$_GET['id']!="")
{

$id=base64_decode($_GET['id']);


$query = "SELECT * FROM `orders` WHERE `Id`=".mysql_escape_string($id);
$result = mysql_query($query) or die(mysql_error());    
$row = mysql_fetch_array($result);



if ($_GET['mode']=='cl') 
{
$query_clients = "SELECT `id`,`name`,`cl_inn`,`cl_chief`,`cl_dchief`,`cl_adr_u`,`contract`,`cl_orderform` FROM `clients` WHERE `Id`=".mysql_escape_string($row['client']);
$result_clients = mysql_query($query_clients) or die(mysql_error());
$client = mysql_fetch_row($result_clients);
$print_adr=$client[5];

$print_currency = $row['cl_currency'];

$print_cont=$row['cl_cont'];
$print_pref=$row['cl_pref'];
$print_nds=$row['cl_nds'];
$print_tfpay=$row['cl_tfpay'];
$print_event=$row['cl_event'];
$print_contract=$client[6];
$print_id=$client[0];
$print_name=$client[1];
$print_inn=$client[2];
if($row['cl_kop']!=""&&(int)$row['cl_kop']!=0) $cl_kop='.'.$row['cl_kop']; else $cl_kop="";	
$print_cash=$row['cl_cash'].$cl_kop;
$pieces = explode(" ", $client[3]);

$print_chief=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
if($row['cl_pref']==3) $print_dchief="Индивидуальный предприниматель"; else $print_dchief=$client[4];
//$orderform=$client[7];
}

if ($_GET['mode']=='tr') 
{
$query_trans = "SELECT `id`,`name`,`tr_inn`,`tr_chief`,`tr_dchief`,`tr_adr_u`,`contract`,`tr_orderform`,`tr_ochief`,`tr_code_ati`,`tr_support`,`tr_phone`,`tr_pref_phone` FROM `transporters` WHERE Id=".mysql_escape_string($row['transp']);
$result_trans = mysql_query($query_trans) or die(mysql_error());
$trans = mysql_fetch_row($result_trans);
$print_adr=$trans[5];

$print_currency = $row['tr_currency'];

$print_cont=$row['tr_cont'];
$print_pref=$row['tr_pref'];
$print_support=$trans[10];
$print_phone='8 ('.$trans[12].') '.$trans[11];
$print_nds=$row['tr_nds'];
$print_tfpay=$row['tr_tfpay'];
$print_event=$row['tr_event'];
$print_contract=$trans[6];
$print_id=$trans[0];
$print_name=$trans[1];
$print_inn=$trans[2];
$print_tr_ati=$trans[9];
$print_cash=$row['tr_cash'];
$pieces = explode(" ", $trans[3]);

$print_chief=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
if($row['tr_pref']==3) $print_dchief="Индивидуальный предприниматель"; else $print_dchief=$trans[4];
//if($row['tr_cont']==7||$row['tr_cont']==8) $orderform='3'; else $orderform=$trans[7];
}

$query_adress = "SELECT * FROM `adress` WHERE `id`='".mysql_escape_string($print_adr)."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());
$adress_cl_tr = mysql_fetch_row($result_adress);


$query_cont = "SELECT `id`,`name`,`inn`,`phone`,`adr_u`,`chief_status`,`chief`,`pref`,`chief_contract`,`dchief_contract`,`ochief_contract`,`code_ati`,`adr_f` FROM `company` WHERE `Id`=".mysql_escape_string($print_cont);
$result_cont = mysql_query($query_cont) or die(mysql_error()); 
$cont = mysql_fetch_row($result_cont);


if ($_GET['mode']=='cl') $query_user = "SELECT `id`,`name`,`phone`,`email`,`pref_phone`,`fake_name` FROM `workers` WHERE Id=".$row['manager'];
if ($_GET['mode']=='tr') $query_user = "SELECT `id`,`name`,`phone`,`email`,`pref_phone`,`fake_name` FROM `workers` WHERE Id=".$row['tr_manager']; 

$result_user = mysql_query($query_user) or die(mysql_error());
$user = mysql_fetch_array($result_user);

//if($row['tr_manager']=='13'&&$row['tr_cont']==2&&$_GET['mode']=='tr')$user[3]='vtl@vtl-stroy.ru';
//if($row['tr_manager']=='13'&&$row['cl_cont']==2&&$_GET['mode']=='cl')$user[3]='vtl@vtl-stroy.ru';

$orderform='4';





if($row['transp']=='2'||$row['transp']=='-1'){
$car_id=explode('&',$row['tr_auto']);
if($car_id[0]!=0){
$query_car = "SELECT `name`,`number` FROM `vtl_auto` WHERE `Id`='".mysql_escape_string($car_id[0])."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car_vtl = mysql_fetch_row($result_car);
$car[2]=$car_vtl[0];
$car[3]=$car_vtl[1];
}

if($car_id[1]!=0){
$query_car = "SELECT `name`,`number` FROM `vtl_auto` WHERE `Id`='".mysql_escape_string($car_id[1])."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car_vtl = mysql_fetch_row($result_car);
$car[7]=$car_vtl[0];
$car[8]=$car_vtl[1];
}

if($car_id[2]!=0){
$query_drv = "SELECT `name`,`pref_phone`,`phone`,`passport` FROM `workers` WHERE `Id`='".mysql_escape_string($car_id[2])."'";
$result_drv = mysql_query($query_drv) or die(mysql_error());
$drv_vtl = mysql_fetch_row($result_drv);
$car[9]=$drv_vtl[0];
$car_info=explode('|',$drv_vtl[3]);
$car[10]=$drv_vtl[3];
$car[11]='8 ('.$drv_vtl[1].') '.$drv_vtl[2];
}

} else {
$query_car = "SELECT * FROM `tr_autopark` WHERE Id='".$row['tr_auto']."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car = mysql_fetch_row($result_car);
$car_info=explode('|',$car[10]);

}





while($adress = mysql_fetch_row($result_adress)) {
$adresses[$adress[0]]= $adress[1];
}

switch ($cont[7]) {
case '1': $pref_cont='ООО';break;
case '2': $pref_cont='ОАО';break;
case '3': $pref_cont='ИП';break;
case '4': $pref_cont='ЗАО';break;
case '5': $pref_cont='';break;
case '6': $pref_cont='Физ.Л.';break;
case '7': $pref_cont='АО';break;
}

switch ($print_pref) {
case '1': $pref_print='ООО';break;
case '2': $pref_print='ОАО';break;
case '3': $pref_print='ИП';break;
case '4': $pref_print='ЗАО';break;
case '5': $pref_print='';break;
case '6': $pref_print='Физ.Л.';break;
case '7': $pref_print='АО';break;
}

switch ($print_nds) {
case '0': $nds='(без НДС)';break;
case '1': $nds='(с НДС)';break;
case '2': $nds='(НАЛ)';break;
}
if($row['days_tfpay']==0||$_GET['mode']=='cl')$days_tfpay='календарных'; else $days_tfpay='банковских';


if($print_tfpay==0){$cl_tfpay='';} else {$cl_tr_tfpay=' + '.$print_tfpay.'  дн. ('.$days_tfpay.')';}



switch ($print_event) {
case '1': $cl_tr_event='Загрузка'.$cl_tr_tfpay;break;
case '2': $cl_tr_event='Выгрузка'.$cl_tr_tfpay;break;
case '3': $cl_tr_event='Поступление&ensp;факсимильных&ensp;документов'.$cl_tr_tfpay;break;
case '4': $cl_tr_event='Поступление&ensp;оригинальных&ensp;документов'.$cl_tr_tfpay;break;}

switch ($row['gr_load']) {
case '1': $car_load='верхняя';break;
case '2': $car_load='задняя';break;
case '3': $car_load='боковая';break;
}
if($print_contract!=''){$cl_tr_contract=$print_contract;} else {$cl_tr_contract=$print_id;}

if($row['date_in1']!="1970-01-01"&&$row['date_in1']!="0000-00-00") {$date_in1="<b>".date("d/m/Y",strtotime($row['date_in1']))."</b>";
if($row['time_in11']!='00:00:00')$date_in1.=" с ".date("H:i",strtotime($row['time_in11']));

if($row['time_in12']!='00:00:00')$date_in1.=" до ".date("H:i",strtotime($row['time_in12']));

} else $date_in1="";

if($row['date_in2']!="1970-01-01"&&$row['date_in2']!="0000-00-00") {$date_in2=" по <b>".date("d/m/Y",strtotime($row['date_in2']))."</b>"; 

if($row['time_in21']!='00:00:00')$date_in2.=" с ".date("H:i",strtotime($row['time_in21']));
if($row['time_in22']!='00:00:00')$date_in2.=" до ".date("H:i",strtotime($row['time_in22']));
	
$date_in=" c ";} else {$date_in2="";$date_in="";};




if($orderform=='2'||$orderform=='3'||$orderform=='4'){
$d = getdate(strtotime($row['data']));
    $month_d = $d['mon'];

    switch ($d['mon']) {
        case '1':
            $month = 'января';
            break;
        case '2':
            $month = 'февраля';
            break;
        case '3':
            $month = 'марта';
            break;
        case '4':
            $month = 'апреля';
            break;
        case '5':
            $month = 'мая';
            break;
        case '6':
            $month = 'июня';
            break;
        case '7':
            $month = 'июля';
            break;
        case '8':
            $month = 'августа';
            break;
        case '9':
            $month = 'сентября';
            break;
        case '10':
            $month = 'октября';
            break;
        case '11':
            $month = 'ноября';
            break;
        case '12':
            $month = 'декабря';
            break;
    }

// Печать номера заявки
    if ($row['international_number'] != "0") $id = $row['international_number'] . '-' . $row['id']; else $id = $row['id'];

// Gruz info
    $gruz = '';
    $gr_number = 0;
    $gr_m = 0;
    $gr_v = 0;
    $in_adress = '';
    $out_adress = '';

    if ($row['group_id'] != '') {
        $id = $row['group_id'];
        $print_cash = 0;

        $query_group = "SELECT in_adress, out_adress, tr_cash, gruz, gr_number, gr_m, gr_v FROM orders 
                        WHERE id IN (" . $row['group_id'] . ")";
        $result_group = mysql_query($query_group) or die(mysql_error());

        while ($group = mysql_fetch_array($result_group)) {
            $print_cash += $group['tr_cash'];
            if ($gruz != '') $gruz = $gruz . ', ' . $group['gruz']; else $gruz = $group['gruz'];
            if ($group['gr_number'] != 0) $gr_number += $group['gr_number'];
            $gr_m += $group['gr_m'];
            $gr_v += $group['gr_v'];
            $in_adress .= $group['in_adress'];
            $out_adress .= $group['out_adress'];
        }
    } else {
        $gruz = $row['gruz'];
        $gr_number = $row['gr_number'];
        $gr_m = $row['gr_m'];
        $gr_v = $row['gr_v'];
        $in_adress = $row['in_adress'];
        $out_adress = $row['out_adress'];
    }

//if($orderform=='4'){
//$query_settings = "SELECT `agat_number` FROM `orders` WHERE `id`='".mysql_escape_string($id)."'";
//$result_settings = mysql_query($query_settings) or die(mysql_error());
//$settings = mysql_fetch_row($result_settings);
//$id=$settings[0];
//}


//if($orderform=='3')$filename = 'tm_order_tr.rtf';


if($orderform=='4'&&$_GET['mode']=='tr') $filename = $DOCS_TEMPLATES_DIR.'tm_order_tr_'.$row['tr_cont'].'.rtf';
if($orderform=='4'&&$_GET['mode']=='cl') $filename = $DOCS_TEMPLATES_DIR.'tm_order_cl_'.$row['cl_cont'].'.rtf';

//if($orderform=='4'&&$_GET['mode']=='tr'&&($row['client']==627||$row['client']==630||$row['client']==632||$row['client']==639))$filename = 'tm_order_tr_agat_cc.rtf';



$output = file_get_contents($filename);

switch ($print_event) {
case '1': $cl_tr_event='Загрузка'.$cl_tr_tfpay;break;
case '2': $cl_tr_event='Выгрузка'.$cl_tr_tfpay;break;
case '3': $cl_tr_event='Поступление факсимильных документов'.$cl_tr_tfpay;break;
case '4': $cl_tr_event='Поступление оригинальных документов'.$cl_tr_tfpay;break;}


    $output = str_replace("<<id>>", convert($id . '/' . $month_d), $output);
    $output = str_replace("<<mday>>", $d['mday'], $output);
    $output = str_replace("<<month>>", convert($month), $output);
    $output = str_replace("<<year>>", $d['year'], $output);

if ($_GET['mode']=='cl') {$output = str_replace("<<cont_name>>",convert($pref_print." «".$print_name."»"),$output);
$output = str_replace("<<name>>",convert($pref_cont." «".$cont[1]."»"),$output);}

if ($_GET['mode']=='tr') {$output = str_replace("<<cont_name>>",convert($pref_cont." «".$cont[1]."»"),$output);
$output = str_replace("<<name>>",convert($pref_print." «".$print_name."»"),$output);}

$output = str_replace("<<tr_adr>>",convert($adress_cl_tr[1].', '.$adress_cl_tr[2].', '.$obl.' '.$adress_cl_tr[4].' ул. '.$adress_cl_tr[5].$dom.$dom_extra.$flat),$output);


$pieces = explode(" ", $cont[8]);
$print_chief_contract=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";


$output = str_replace("<<cont_adr>>",convert($cont[4]),$output);
$output = str_replace("<<cont_adr_u>>",convert($cont[12]),$output);

$output = str_replace("<<cont_inn>>",$cont[2],$output);
$output = str_replace("<<cont_phone>>",convert($cont[3]),$output);


if ($_GET['mode']=='cl') {
$output = str_replace("<<print_cont_ochief>>",convert($trans[8]),$output);
$output = str_replace("<<cont_chief_contract>>",convert($print_chief_contract),$output);
$output = str_replace("<<cont_dchief_contract>>",convert($cont[9]),$output);
$output = str_replace("<<print_ochief>>",convert($cont[10]),$output);

$pieces = explode(" ", $cont[6]);
$cont_chief=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
if($print_dchief=='Индивидуальный предприниматель')$print_dchief='ИП';
$output = str_replace("<<print_chief>>",convert($cont_chief),$output);
$output = str_replace("<<print_dchief>>",convert($cont[5]),$output);
$output = str_replace("<<cont_chief>>",convert($print_chief),$output);
$output = str_replace("<<cont_dchief>>",convert($print_dchief),$output);
}

if ($_GET['mode']=='tr') {
$output = str_replace("<<print_cont_ochief>>",convert($cont[10]),$output);
$output = str_replace("<<cont_chief_contract>>",convert($print_chief_contract),$output);
$output = str_replace("<<cont_dchief_contract>>",convert($cont[9]),$output);
$output = str_replace("<<print_ochief>>",convert($trans[8]),$output);

$pieces = explode(" ", $cont[6]);
$cont_chief=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
if($print_dchief=='Индивидуальный предприниматель')$print_dchief='ИП';
$output = str_replace("<<print_chief>>",convert($print_chief),$output);
$output = str_replace("<<print_dchief>>",convert($print_dchief),$output);
$output = str_replace("<<cont_chief>>",convert($cont_chief),$output);
$output = str_replace("<<cont_dchief>>",convert($cont[5]),$output);
}

$manager_phone=convert("8 (".$user['pref_phone'].") ".$user['phone']);

if($user['fake_name'] != '') {
	$manager_name =	 $user['fake_name'];
}
	else {
	$pieces = explode(" ", $user['name']);
	$manager_name=$pieces[1];
}

$output = str_replace("<<manger>>",convert($manager_name),$output);
$output = str_replace("<<manger_phone>>",$manager_phone,$output);


$output = str_replace("<<tr_>>",convert($print_adr),$output);
$output = str_replace("<<tr_nds>>",convert($nds),$output);
$output = str_replace("<<tr_inn>>",convert($print_inn),$output);

$output = str_replace("<<car_driver>>",convert($car[9]),$output);
$car_info=explode('|',$car[10]);
$output = str_replace("<<driver_doc>>",convert($car_info[0].'   Выдан: '.$car_info[1].' ('.$car_info[2].'г.)'),$output);
$output = str_replace("<<car>>",convert($car[2]." - ".$car[3]),$output);
if($car[7]!=""||$car[8]!="") $output = str_replace("<<car_pp>>",convert(" (П/п: ".$car[7]." - ".$car[8].")"),$output); else $output = str_replace("<<car_pp>>","",$output);

$output = str_replace("<<car_gruz_kuz>>",convert($car[12]),$output);

    $output = str_replace("<<driver_phone>>", convert('тел. ' . $car[11]), $output);

//$output = str_replace("<<tr_event>>",convert($cl_tr_event.'. Обязательное наличие 2-х экземпляров ТТН и ТН'),$output);
    $output = str_replace("<<tr_event>>", convert($cl_tr_event), $output);

    $output = str_replace("<<car_load>>", convert($car_load), $output);


    $output = str_replace("<<cash>>", convert($print_cash . ' ' . $print_currency), $output);
    $output = str_replace("<<car_gruz_num>>", convert($gr_number), $output);
    $output = str_replace("<<car_gruz>>", convert($gruz), $output);

    $output = str_replace("<<car_gruz_m>>", convert($gr_m), $output);
    $output = str_replace("<<car_gruz_v>>", convert($gr_v), $output);


    $output = str_replace("<<car_info>>", convert($row['car_notify']), $output);
    $output = str_replace("<<dop_info>>", convert(''), $output);


    $str_in = explode('&', $in_adress);
    $str_adr_in = (int)sizeof($str_in) - 2;
$f=0;
while ($f<=$str_adr_in) {
$res_adr_in="";
$adr_in_cont="";
$adr_in_phone="";

$query_adress = "SELECT * FROM `adress` WHERE `id`='".$str_in[($str_adr_in-$f)]."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());

$adress = mysql_fetch_row($result_adress);

if($adress[8]!=""&&$adress[8]!=0)$flat=' - '.$adress[8]; else $flat="";
if($adress[6]!=0) $dom=' д.'.$adress[6]; else $dom='';
$res_adr_in=$adress[2].', '.$adress[3].' обл., '.$adress[4].' ул. '.$adress[5].$dom.$adress[7].$flat;$adr_in_cont=$adr_in_cont." ".$adress[9];
$city_start=$adress[4];
$adr_in_phone=$adr_in_phone." ".$adress[10];$f++;

if($orderform=='3'||$orderform=='4'){
$output = preg_replace( '/<<adr_in>>/si', convert($res_adr_in), $output,1);
$output = preg_replace( '/<<adr_in_org>>/si', convert($adress[15]), $output,1);
$output = preg_replace( '/<<adr_in_cont>>/si', convert('('.$adr_in_cont), $output,1);
$output = preg_replace( '/<<adr_in_phone>>/si', convert($adr_in_phone.')\par'), $output,1);

}
}




if($row['date_in1']!="1970-01-01"&&$row['date_in1']!="0000-00-00") {$date_in1=date("d/m/Y",strtotime($row['date_in1']));
if($row['time_in11']!='00:00:00')$date_in1.=" с ".date("H:i",strtotime($row['time_in11']));

if($row['time_in12']!='00:00:00')$date_in1.=" до ".date("H:i",strtotime($row['time_in12']));

} else $date_in1="";

if($row['date_in2']!="1970-01-01"&&$row['date_in2']!="0000-00-00") {$date_in2=" по ".date("d/m/Y",strtotime($row['date_in2'])); 

if($row['time_in21']!='00:00:00')$date_in2.=" с ".date("H:i",strtotime($row['time_in21']));
if($row['time_in22']!='00:00:00')$date_in2.=" до ".date("H:i",strtotime($row['time_in22']));
	
$date_in=" c ";} else {$date_in2="";$date_in="";};


$output = str_replace("<<date_in1>>",convert($date_in.$date_in1.$date_in2),$output);
if($orderform=='3'||$orderform=='4'){
	if($print_support!='')$print_support=''.$print_support;
$output = str_replace("<<tr_support>>",convert($print_support),$output);	
$output = str_replace("<<tr_phone>>",convert($print_phone),$output);	
$output = str_replace("<<adr_in>>","",$output);
$output = str_replace("<<adr_in_org>>","",$output);
$output = str_replace("<<adr_in_cont>>","",$output);
$output = str_replace("<<adr_in_phone>>","",$output);} else {
$output = str_replace("<<adr_in>>",convert($res_adr_in),$output);
$output = str_replace("<<adr_in_cont>>",convert('('.$adr_in_cont),$output);
$output = str_replace("<<adr_in_phone>>",convert($adr_in_phone.')'),$output);
}


    $str_out = explode('&', $out_adress);
    $str_adr_out = (int)sizeof($str_out) - 2;
$f=0;
while ($f<=$str_adr_out) {
$res_adr_out="";
$adr_out_cont="";
$adr_out_phone="";
$query_adress = "SELECT * FROM `adress` WHERE `id`='".$str_out[($str_adr_out-$f)]."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());

$adress = mysql_fetch_row($result_adress);
if($adress[8]!=""&&$adress[8]!=0)$flat=' - '.$adress[8]; else $flat="";
if($adress[6]!=0) $dom=' д.'.$adress[6]; else $dom='';
$res_adr_out=$res_adr_out." ".$adress[2].', '.$adress[3].' обл., '.$adress[4].' ул. '.$adress[5].$dom.$adress[7].$flat;
$city_end=$adress[4];
$adr_out_cont=$adr_out_cont." ".$adress[9];
$adr_out_phone=$adr_out_phone." ".$adress[10];
$f++;
if($orderform=='3'||$orderform=='4'){
$output = preg_replace( '/<<adr_out>>/si', convert($res_adr_out), $output,1);
$output = preg_replace( '/<<adr_out_org>>/si', convert($adress[15]), $output,1);
$output = preg_replace( '/<<adr_out_cont>>/si', convert('('.$adr_out_cont), $output,1);
$output = preg_replace( '/<<adr_out_phone>>/si', convert($adr_out_phone.')\par'), $output,1);
}
}



if($row['date_out1']!="0000-00-00"&&$row['date_out1']!="1970-01-01") {$date_out1=date("d/m/Y",strtotime($row['date_out1']));
if($row['time_out1']!='00:00:00')$date_out1.=' с '.date('H:i',strtotime($row['time_out1']));
} else $date_out1="";
if($row['date_out2']!="0000-00-00"&&$row['date_out2']!="1970-01-01") {$date_out2=' по '.date("d/m/Y",strtotime($row['date_out2']));
if($row['time_out2']!='00:00:00')$date_out2.=' до '.date('H:i',strtotime($row['time_out2']));

} else $date_out2="";

if(!empty($date_out2))$date_out=" c "; else $date_out="";




$output = str_replace("<<date_out1>>",convert($date_out.$date_out1.' '.$date_out2),$output);

if($orderform=='3'||$orderform=='4'){$output = str_replace("<<adr_out>>","",$output);
$output = str_replace("<<adr_out_org>>","",$output);
$output = str_replace("<<adr_out_cont>>","",$output);
$output = str_replace("<<adr_out_phone>>","",$output);
$output = str_replace("<<city_start>>",convert($city_start),$output);
$output = str_replace("<<city_end>>",convert($city_end),$output);
} else {
$output = str_replace("<<adr_out>>",convert($res_adr_out),$output);
$output = str_replace("<<adr_out_cont>>",convert($adr_out_cont),$output);
$output = str_replace("<<adr_out_phone>>",convert($adr_out_phone),$output);

}

if($orderform=='4'){

// -----------
class Plural {
 
     const MALE = 1;
     const FEMALE = 2;
     const NEUTRAL = 3;
    
     protected static $_digits = array(
         self::MALE => array('ноль', 'один', 'два', 'три', 'четыре','пять', 'шесть', 'семь', 'восемь', 'девять'),
         self::FEMALE => array('ноль', 'одна', 'две', 'три', 'четыре','пять', 'шесть', 'семь', 'восемь', 'девять'),
         self::NEUTRAL => array('ноль', 'одно', 'два', 'три', 'четыре','пять', 'шесть', 'семь', 'восемь', 'девять')
         );
    
     protected static $_ths = array(
         0 => array('','',''),
         1=> array('тысяч', 'тысячи', 'тысяч'),   
         2 => array('миллион', 'миллиона', 'миллионов'),
         3 => array('миллиард','миллиарда','миллиардов'),
         4 => array('триллион','триллиона','триллионов'),
         5 => array('квадриллион','квадриллиона','квадриллионов')
         );
    
     protected static $_ths_g = array(self::NEUTRAL, self::FEMALE, self::MALE, self::MALE, self::MALE, self::MALE); // hack 4 thsds
    
     protected static $_teens = array(
         0=>'десять',
         1=>'одиннадцать',
         2=>'двенадцать',
         3=>'тринадцать',
         4=>'четырнадцать',
         5=>'пятнадцать',
         6=>'шестнадцать',
         7=>'семнадцать',
         8=>'восемнадцать',
         9=>'девятнадцать'
         );
 
     protected static $_tens = array(
         2=>'двадцать',
         3=>'тридцать',
         4=>'сорок',
         5=>'пятьдесят',
         6=>'шестьдесят',
         7=>'семьдесят',
         8=>'восемьдесят',
         9=>'девяносто'
         );
    
     protected static $_hundreds = array(
         1=>'сто',
         2=>'двести',
         3=>'триста',
         4=>'четыреста',
         5=>'пятьсот',
         6=>'шестьсот',
         7=>'семьсот',
         8=>'восемьсот',
         9=>'девятьсот'
         );
    
     protected function _ending($value, array $endings = array()) {
         $result = '';
         if ($value < 2) $result = $endings[0];
         elseif ($value < 5) $result = $endings[1];
         else $result = $endings[2];
        
         return $result;   
     }
    
     protected function _triade($value, $mode = self::MALE, array $endings = array()) {
         $result = '';
         if ($value == 0) { return $result; }
         $triade = str_split(str_pad($value,3,'0',STR_PAD_LEFT));
         if ($triade[0]!=0) { $result.= (self::$_hundreds[$triade[0]].' '); }
         if ($triade[1]==1) { $result.= (self::$_teens[$triade[2]].' '); }
         elseif(($triade[1]!=0)) { $result.= (self::$_tens[$triade[1]].' '); }
         if (($triade[2]!=0)&&($triade[1]!=1)) { $result.= (self::$_digits[$mode][$triade[2]].' '); }
         if ($value!=0) { $ends = ($triade[1]==1?'1':'').$triade[2]; $result.= self::_ending($ends,$endings).' '; }
         return $result;
     }
    
     public function asString($value, $mode = self::MALE, array $endings = array()) {
         if (empty($endings)) { $endings = array('','',''); }
         $result = '';
         $steps = ceil(strlen($value)/3);
         $sv = str_pad($value, $steps*3, '0', STR_PAD_LEFT);
         for ($i=0; $i<$steps; $i++) {
             $triade = substr($sv, $i*3, 3);
             $iter = $steps - $i;
             $ends = ($iter!=1)?(self::$_ths[$iter-1]):($endings);
             $gender = ($iter!=1)?(self::$_ths_g[$iter-1]):($mode);
             $result.= self::_triade($triade,$gender, $ends);
         }
         return $result;
     }
    
 }
// -----------
    $plural_info = new Plural();

    $output = str_replace("<<cash_word>>", convert('( ' . $plural_info->asString((int)$print_cash, plural::FEMALE, array('', '', '')) . ')'), $output);
if ($_GET['mode']=='tr') $output = str_replace("<<tr_code_ati>>",convert('(АТИ: '.$trans[9].')'),$output);
if ($_GET['mode']=='cl') $output = str_replace("<<tr_code_ati>>",convert('(АТИ: '.$cont [11].')'),$output);


$output = str_replace("<<email>>",convert($user['email']),$output);


$output = str_replace("<<manger_n>>",convert($manager_name),$output);
//$output = str_replace("<<manger_f>>",convert($manager_f),$output);
$output = str_replace("<<manger_phone_a>>",$manager_phone,$output);

}


header('Content-Type: application/msword;');
if ($_GET['mode']=='cl') header('Content-Disposition: inline; filename="Заявка-Клиент-№'.$id.'.rtf"');
if ($_GET['mode']=='tr') header('Content-Disposition: inline; filename="Заявка-Перевозчик-№'.$id.'.rtf"');
header('Cache-Control: max-age=0');

echo $output; 
}
}
?>