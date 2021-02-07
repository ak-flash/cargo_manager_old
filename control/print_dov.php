<?php
header('Content-Type: application/msword;');

include "../config.php";

$DOCS_TEMPLATES_DIR = 'docs_templates/';


$query_dov = "SELECT `dov_count` FROM `settings`";
$result_dov = mysql_query($query_dov) or die(mysql_error());
$row_dov = mysql_fetch_row($result_dov);

header('Content-Disposition: inline; filename=Доверенность_№'.$row_dov[0].'.doc');

function convert($temp) {
return iconv("UTF-8", "windows-1251", $temp);
}

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
         1=> array('тысяча', 'тысячи', 'тысяч'),   
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
    
     protected static function _ending($value, array $endings = array()) {
         $result = '';
         if ($value < 2) $result = $endings[0];
         elseif ($value < 5) $result = $endings[1];
         else $result = $endings[2];
        
         return $result;   
     }
    
     protected static function _triade($value, $mode = self::MALE, array $endings = array()) {
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
    
     public static function asString($value, $mode = self::MALE, array $endings = array()) {
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



if($_GET['mode']=="dov"){
$id=base64_decode($_GET['id']);


$filename = $DOCS_TEMPLATES_DIR.'tm_dov.rtf';

$output = file_get_contents($filename);






$query = "SELECT * FROM `orders` WHERE Id=".mysql_escape_string($id);
$result = mysql_query($query) or die(mysql_error());    
$row = mysql_fetch_array($result);

$query_clients = "SELECT `id`,`name`,`cl_cont` FROM `clients` WHERE Id=".mysql_escape_string($row['client']);
$result_clients = mysql_query($query_clients) or die(mysql_error());
$client = mysql_fetch_row($result_clients);

$print_pref=$row['cl_pref'];
$print_name=$client[1];
switch ($print_pref) {
case '1': $pref_print='ООО';break;
case '2': $pref_print='ОАО';break;
case '3': $pref_print='ИП';break;
case '4': $pref_print='ЗАО';break;
case '5': $pref_print='';break;
case '6': $pref_print='';break;
case '7': $pref_print='АО';break;
}

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
}

} else {
$query_car = "SELECT * FROM `tr_autopark` WHERE Id='".mysql_escape_string($row['tr_auto'])."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car = mysql_fetch_row($result_car);
$car_info=explode('|',$car[10]);}

$q[]="";
$q[]="Января"; 
$q[]="февраля"; 
$q[]="Марта"; 
$q[]="Апреля"; 
$q[]="мая";
$q[]="июня"; 
$q[]="июля"; 
$q[]="августа"; 
$q[]="сентября"; 
$q[]="октября"; 
$q[]="ноября";
$q[]="декабря";
$m=date('m');
if ($m=="01") $m=1; 
if ($m=="02") $m=2;
if ($m=="03") $m=3;
if ($m=="04") $m=4; 
if ($m=="05") $m=5;
if ($m=="06") $m=6;
if ($m=="07") $m=7;
if ($m=="08") $m=8; 
if ($m=="09") $m=9;

$we=date('w');

$chislo=date('d');


$mesyac = $q[$m];


$m2=date('m',strtotime('+10 day'));
if ($m2=="01") $m2=1; 
if ($m2=="02") $m2=2;
if ($m2=="03") $m2=3;
if ($m2=="04") $m2=4; 
if ($m2=="05") $m2=5;
if ($m2=="06") $m2=6;
if ($m2=="07") $m2=7;
if ($m2=="08") $m2=8; 
if ($m2=="09") $m2=9;

$we2=date('w',strtotime('+10 day'));

$chislo2=date('d',strtotime('+10 day'));


$mesyac2 = $q[$m2];




$output = str_replace("<<id>>",$row_dov[0],$output);


$query_company = "SELECT * FROM `company` WHERE `Id`='".mysql_escape_string($row['cl_cont'])."'";
$result_company = mysql_query($query_company) or die(mysql_error());
$company = mysql_fetch_array($result_company);




//if($client[0]=='27'||$client[0]=='35'||$client[0]=='124'||$client[0]=='151'||$client[0]=='172'){
//	$source_print='ООО "Мечел-Сервис", ИНН 7704555837, КПП 770401001, 103104, Москва, Курсовой пер., д. 12/5, стр.5';
//$output = str_replace("<<source>>",convert($source_print),$output);

//$source_print='ООО «'.$company['name'].'», ИНН '.$company['inn'].', КПП '.$company['kpp'].', '.$company['adr_f'];
//$output = str_replace("<<source_head>>",convert($source_print),$output);

//$output = str_replace("<<dogovor>>",convert("дог. №90930059 от 14.10.2009г."),$output);

//} else {

$source_print='ООО «'.$company['name'].'», ИНН '.$company['inn'].', КПП '.$company['kpp'].', '.$company['adr_f'];
	
$output = str_replace("<<source>>",convert($source_print),$output);
$output = str_replace("<<source_head>>",convert($source_print),$output);
//if($client[2]==2)$output = str_replace("<<dogovor>>",convert("дог. №90930059 от 14.10.2009г."),$output); else 
$output = str_replace("<<dogovor>>","",$output);
//}

$pieces = explode(" ", $company['chief']);
$chief=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";

$output = str_replace("<<chief>>",convert($chief),$output);

$output = str_replace("<<buh>>",convert($chief),$output);

$output = str_replace("<<rs>>",convert($company['rs']),$output);
$output = str_replace("<<bank>>",convert($company['bank']),$output);
$output = str_replace("<<bik>>",convert($company['bik']),$output);
$output = str_replace("<<ks>>",convert($company['ks']),$output);


$output = str_replace("<<client>>",convert($pref_print.' «'.$print_name.'»'),$output);

$output = str_replace("<<car_driver>>",convert($car[9]),$output);

$output = str_replace("<<date1>>",convert($chislo.' '.$mesyac.' '.date("Y").' г.'),$output);
$output = str_replace("<<date2>>",convert($chislo2.' '.$mesyac2.' '.date("Y",strtotime('+10 day')).' г.'),$output);

$output = str_replace("<<dates1>>",convert($chislo.'.'.$m.'.'.date("Y")),$output);
$output = str_replace("<<dates2>>",convert($chislo2.'.'.$m2.'.'.date("Y",strtotime('+10 day'))),$output);


$output = str_replace("<<driver_doc1>>",convert($car_info[0]),$output);
$output = str_replace("<<driver_doc2>>",convert($car_info[1]),$output);
$output = str_replace("<<driver_doc3>>",convert($car_info[2].'г.'),$output);

$output = str_replace("<<car_gruz>>",convert($row['gruz']),$output);
$output = str_replace("<<car_gruz_m>>",(int)$row['gr_m'],$output);

$output = str_replace("<<car_gruz_r>>",convert(plural::asString((int)$row['gr_m'],plural::FEMALE,array('','',''))),$output);

$query_dov = "UPDATE `settings` SET `dov_count`='".((int)$row_dov[0]+1)."'";
$result_dov = mysql_query($query_dov) or die(mysql_error());




    echo $output;
}
?>