<?php
include "../config.php";

$DOCS_TEMPLATES_DIR = 'docs_templates/';

function convert($temp) {
return iconv("UTF-8", "windows-1251", $temp);
}

if(@$_GET['id']!="")
{

if(!mb_ereg("[^0-9]",$_GET['id'])){$id=(int)$_GET['id'];} else 
{$id=base64_decode($_GET['id']);}

if(@$_GET['mode']=="tr"&&@$_GET['change']!=""){
$query = "UPDATE `transporters` SET `nds`='".mysql_escape_string((int)$_GET['change'])."' WHERE Id=".mysql_escape_string($id);
$result = mysql_query($query) or die(mysql_error());
}


if($_GET['mode']=="cl"){
$query = "SELECT * FROM `clients` WHERE Id=".mysql_escape_string($id);
$result = mysql_query($query) or die(mysql_error());    
$row = mysql_fetch_array($result);
$print_adr_u=$row['cl_adr_u'];
$print_adr_f=$row['cl_adr_f'];
$print_cont=$row['cl_cont'];

$print_name=" «".$row['name']."»";
$print_inn=$row['cl_inn'];
$print_kpp=$row['cl_kpp'];
$print_bik=$row['cl_bik'];
$print_rs=$row['cl_rs'];
$print_ks=$row['cl_ks'];
$print_ogrn=$row['cl_ogrn'];
$print_bank=$row['cl_bank'];

$print_phone=$row['cl_phone'];
$print_pref_phone=$row['cl_pref_phone'];
$pieces = explode(" ", $row['cl_chief']);

$print_chief=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
$print_chief_contract=$row['cl_chief_contract'];
$print_dchief_contract=$row['cl_dchief_contract'];
$print_ochief=$row['cl_ochief'];
if($row['pref']==3) $print_dchief="ИП"; else $print_dchief=$row['cl_dchief'];

}

if($_GET['mode']=="tr"){
$query = "SELECT * FROM `transporters` WHERE Id=".mysql_escape_string($id);
$result = mysql_query($query) or die(mysql_error());    
$row = mysql_fetch_array($result);
$print_adr_u=$row['tr_adr_u'];
$print_adr_f=$row['tr_adr_f'];
$print_cont=$row['tr_cont'];

$print_name=" «".$row['name']."»";
$print_inn=$row['tr_inn'];
$print_kpp=$row['tr_kpp'];
$print_bik=$row['tr_bik'];
$print_rs=$row['tr_rs'];
$print_ks=$row['tr_ks'];
$print_ogrn=$row['tr_ogrn'];
$print_bank=$row['tr_bank'];
$print_phone=$row['tr_phone'];
$print_pref_phone=$row['tr_pref_phone'];
$pieces = explode(" ", $row['tr_chief']);

$print_chief=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
$print_chief_contract=$row['tr_chief_contract'];
$print_dchief_contract=$row['tr_dchief_contract'];

$print_ochief=$row['tr_ochief'];
if($row['pref']==3) $print_dchief="ИП"; else $print_dchief=$row['tr_dchief'];
}


$query_cont = "SELECT * FROM `company` WHERE Id=".$print_cont;
$result_cont = mysql_query($query_cont) or die(mysql_error()); 
$cont = mysql_fetch_array($result_cont);





$query_adress = "SELECT * FROM `adress` WHERE `id`='".$print_adr_u."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());
$adress_u = mysql_fetch_row($result_adress); 

$query_adress = "SELECT * FROM `adress` WHERE `id`='".$print_adr_f."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());
$adress_f = mysql_fetch_row($result_adress);

if($adress_f[8]=="0") $flat_f=""; else $flat_f=" - ".$adress_f[8];
if($adress_u[8]=="0") $flat_u=""; else $flat_u=" - ".$adress_u[8];

if($row['contract']==""){$contract=$id;} else {$contract=$row['contract'];}

if($_GET['mode']=="cl"){switch ($cont['pref']) {
case '1': $pref_cont='ООО';break;
case '2': $pref_cont='ОАО';break;
case '3': $pref_cont='ИП';break;
case '4': $pref_cont='ЗАО';break;
case '5': $pref_cont='';break;
case '6': $pref_cont='';break;}}

if($_GET['mode']=="tr"){switch ($cont['pref']) {
case '1': $pref_cont='Общество с ограниченной ответственностью';$pref_cont_s='ООО';break;
case '2': $pref_cont='Открытое акционерное общество';$pref_cont_s='ОАО';break;
case '3': $pref_cont='Индивидуальный предприниматель';$pref_cont_s='ИП';break;
}}


if($print_okpo==0)$print_okpo="";

$d = getdate();
switch ($d["mon"]) {
case '1': $month='января';break;
case '2': $month='февраля';break;
case '3': $month='марта';break;
case '4': $month='апреля';break;
case '5': $month='мая';break;
case '6': $month='июня';break;
case '7': $month='июля';break;
case '8': $month='августа';break;
case '9': $month='сентября';break;
case '10': $month='октября';break;
case '11': $month='ноября';break;
case '12': $month='декабря';break;
}



switch ($row['pref']) {
case '1': $pref_temp='ООО';break;
case '2': $pref_temp='ОАО';break;
case '3': $pref_temp='ИП';break;
case '4': $pref_temp='ЗАО';break;}

$print_dchief_company=$cont['chief_status'];

$pieces = explode(" ", $cont['chief']);
$print_chief_company=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
			
header('Content-Type: application/msword;');

if($_GET['mode']=="cl"){
	header('Content-Disposition: inline; filename="Договор-Клиент-№'.$id.'.rtf"');
	$filename = $DOCS_TEMPLATES_DIR.'tm_contract_cl_'.$row['cl_cont'].'.rtf';
}

if($_GET['mode']=="tr"){
	header('Content-Disposition: inline; filename="Договор-Перевозчик-№'.$id.'.rtf"');
	$filename = $DOCS_TEMPLATES_DIR.'tm_contract_tr_'.$row['tr_cont'].'.rtf';
}



$output = file_get_contents($filename);


$output = str_replace("<<id>>",convert($contract),$output);
$output = str_replace("<<date>>",convert($d["mday"].$d["mon"].$d["year"]),$output);
$output = str_replace("<<mday>>",$d["mday"],$output);
$output = str_replace("<<month>>",convert($month),$output);
$output = str_replace("<<year>>",$d["year"],$output);


$output = str_replace("<<year>>",$d["year"],$output);
$output = str_replace("<<next_year>>",'2021',$output);


$output = str_replace("<<name>>",convert($pref_temp.$print_name),$output);
$output = str_replace("<<chief_contract>>",convert($print_chief_contract),$output);
$output = str_replace("<<dchief_contract>>",convert($print_dchief_contract),$output);
$output = str_replace("<<print_ochief>>",convert($print_ochief),$output);
$output = str_replace("<<print_cont_ochief>>",convert($cont['ochief_contract']),$output);
$output = str_replace("<<cont_chief_contract>>",convert($cont['chief_contract']),$output);
$output = str_replace("<<cont_dchief_contract>>",convert($cont['dchief_contract']),$output);

$output = str_replace("<<name>>",convert($pref_temp.$print_name),$output);

$output = str_replace("<<adr_u>>",convert($adress_u[1].' '.$adress_u[2].' '.$adress_u[3].' обл. '.$adress_u[4].' ул.'.$adress_u[5].' д. '.$adress_u[6].' '.$adress_u[7].$flat_u),$output);

$output = str_replace("<<adr_f>>",convert($adress_f[1].' '.$adress_f[2].' '.$adress_f[3].' обл. '.$adress_f[4].' ул.'.$adress_f[5].' д. '.$adress_f[6].' '.$adress_f[7].$flat_f),$output);

$output = str_replace("<<phone>>","8 (".$print_pref_phone.") ".$print_phone,$output);

$output = str_replace("<<inn>>",$print_inn,$output);
$output = str_replace("<<kpp>>",$print_kpp,$output);
$output = str_replace("<<bik>>",$print_bik,$output);
$output = str_replace("<<rs>>",$print_rs,$output);
if($print_ks!="")$output = str_replace("<<ks>>",$print_ks,$output); else $output = str_replace("<<ks>>","",$output);
$output = str_replace("<<ogrn>>",$print_ogrn,$output);
$output = str_replace("<<bank>>",convert($print_bank),$output);

$output = str_replace("<<cont_name>>",convert($pref_cont."  «".$cont['name']."»"),$output);
$output = str_replace("<<cont_name_s>>",convert($pref_cont_s."  «".$cont['name']."»"),$output);

$output = str_replace("<<cont_adr_f>>",convert($cont['adr_f']),$output);
$output = str_replace("<<cont_adr_u>>",convert($cont['adr_u']),$output);
$output = str_replace("<<cont_inn>>",$cont['inn'],$output);
$output = str_replace("<<cont_kpp>>",$cont['kpp'],$output);
$output = str_replace("<<cont_bik>>",$cont['bik'],$output);
$output = str_replace("<<cont_rs>>",$cont['rs'],$output);
$output = str_replace("<<cont_ks>>",$cont['ks'],$output);
$output = str_replace("<<cont_okpo>>",$cont['okpo'],$output);
$output = str_replace("<<cont_ogrn>>",$cont['ogrn'],$output);
$output = str_replace("<<cont_bank>>",convert($cont['bank']),$output);
$output = str_replace("<<cont_phone>>", '('.$cont['pref_phone'].') '.$cont['phone'],$output);
$output = str_replace("<<cont_email>>",$cont['email'],$output);

$output = str_replace("<<chief>>",convert($print_chief),$output);
$output = str_replace("<<dchief>>",convert($print_dchief),$output);
$output = str_replace("<<cont_chief>>",convert($print_chief_company),$output);
$output = str_replace("<<cont_dchief>>",convert($print_dchief_company),$output);

//$output = str_replace("<<client>>",convert($pref_cl.' '.$client[1]),$output);


echo $output; 
}
?> 

     			
