<?php

include "../config.php";

function convert($temp) {
return iconv("UTF-8", "windows-1251", $temp);
}

function num2str($inn, $stripkop=false) {
    $nol = 'ноль';
    $str[100]= array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот', 'восемьсот','девятьсот');
    $str[11] = array('','десять','одиннадцать','двенадцать','тринадцать', 'четырнадцать','пятнадцать','шестнадцать','семнадцать', 'восемнадцать','девятнадцать','двадцать');
    $str[10] = array('','десять','двадцать','тридцать','сорок','пятьдесят', 'шестьдесят','семьдесят','восемьдесят','девяносто');
    $sex = array(
        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),// m
        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять') // f
    );
    $forms = array(
        array('копейка', 'копейки', 'копеек', 1), // 10^-2
        array('рубль', 'рубля', 'рублей',  0), // 10^ 0
        array('тысяча', 'тысячи', 'тысяч', 1), // 10^ 3
        array('миллион', 'миллиона', 'миллионов',  0), // 10^ 6
       array('миллиард', 'миллиарда', 'миллиардов',  0), // 10^ 9
        array('триллион', 'триллиона', 'триллионов',  0), // 10^12
    );
    $out = $tmp = array();
    // Поехали!
    $tmp = explode('.', str_replace(',','.', $inn));
    $rub = number_format($tmp[ 0], 0,'','-');
    if ($rub== 0) $out[] = $nol;
    // нормализация копеек
    $kop = isset($tmp[1]) ? substr(str_pad($tmp[1], 2, '0', STR_PAD_RIGHT), 0,2) : '00';
    $segments = explode('-', $rub);
    $offset = sizeof($segments);
    if ((int)$rub== 0) { // если 0 рублей
        $o[] = $nol;
        $o[] = morph( 0, $forms[1][ 0],$forms[1][1],$forms[1][2]);
    }
    else {
        foreach ($segments as $k=>$lev) {
            $sexi= (int) $forms[$offset][3]; // определяем род
            $ri = (int) $lev; // текущий сегмент
            if ($ri== 0 && $offset>1) {// если сегмент==0 & не последний уровень(там Units)
                $offset--;
                continue;
            }
            // нормализация
            $ri = str_pad($ri, 3, '0', STR_PAD_LEFT);
            // получаем циферки для анализа
            $r1 = (int)substr($ri, 0,1); //первая цифра
            $r2 = (int)substr($ri,1,1); //вторая
            $r3 = (int)substr($ri,2,1); //третья
            $r22= (int)$r2.$r3; //вторая и третья
            // разгребаем порядки
            if ($ri>99) $o[] = $str[100][$r1]; // Сотни
            if ($r22>20) {// >20
                $o[] = $str[10][$r2];
                $o[] = $sex[ $sexi ][$r3];
            }
            else { // <=20
                if ($r22>9) $o[] = $str[11][$r22-9]; // 10-20
                elseif($r22> 0) $o[] = $sex[ $sexi ][$r3]; // 1-9
            }
            // Рубли
            $o[] = morph($ri, $forms[$offset][ 0],$forms[$offset][1],$forms[$offset][2]);
            $offset--;
        }
    }
    // Копейки
    if (!$stripkop) {
        $o[] = $kop;
        $o[] = morph($kop,$forms[ 0][ 0],$forms[ 0][1],$forms[ 0][2]);
    }
    return preg_replace("/\s{2,}/",' ',implode(' ',$o));
}

function morph($n, $f1, $f2, $f5) {
    $n = abs($n) % 100;
    $n1= $n % 10;
    if ($n>10 && $n<20) return $f5;
    if ($n1>1 && $n1<5) return $f2;
    if ($n1==1) return $f1;
    return $f5;
} 



if(@$_GET['id']!=""&&@$_GET['mode']=="print")
{
$id=(int)$_GET['id'];
$query = "SELECT `date`,`order`,`cash` FROM `pays` WHERE `Id`=".mysql_escape_string($id);
$result = mysql_query($query) or die(mysql_error());    
$row = mysql_fetch_array($result);

$query_order= "SELECT `manager`,`client` FROM `orders` WHERE `Id`=".mysql_escape_string($row['order']);
$result_order = mysql_query($query_order) or die(mysql_error());
$row_order = mysql_fetch_array($result_order);
  
$query_cl = "SELECT `pref`,`name` FROM `clients` WHERE `id`='".mysql_escape_string($row_order['client'])."'";
$result_cl = mysql_query($query_cl) or die(mysql_error());
$client = mysql_fetch_row($result_cl);

switch ($client[0]) {
case '1': $pref='ООО';break;
case '2': $pref='ОАО';break;
case '3': $pref='ИП';break;
case '4': $pref='ЗАО';break;
}



$query_user = "SELECT `id`,`name` FROM `workers` WHERE `id`='".mysql_escape_string($row_order['manager'])."'";
$result_user = mysql_query($query_user) or die(mysql_error());
$user = mysql_fetch_row($result_user);
$pieces = explode(" ", $user[1]);
$print_add_name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";



header('Content-Type: application/msword;');

header('Content-Disposition: inline, filename=Квитанция для платежа - №'.$id.'.rtf');
$filename = 'tm_pay.rtf';




$output = file_get_contents($filename);


$end_elements  = explode("-",$row['date']);
$date_pay=date("d.m.Y",strtotime($row['date']));

switch ($end_elements[1]) {
case '01': $month='января';break;
case '02': $month='февраля';break;
case '03': $month='марта';break;
case '04': $month='апреля';break;
case '05': $month='мая';break;
case '06': $month='июня';break;
case '07': $month='июля';break;
case '08': $month='августа';break;
case '09': $month='сентября';break;
case '10': $month='октября';break;
case '11': $month='ноября';break;
case '12': $month='декабря';break;
}


$output = str_replace("<<id>>",$id,$output);
$output = str_replace("<<date>>",$date_pay,$output);
$output = str_replace("<<date_full>>",convert($end_elements[2].' '.$month.' '.$end_elements[0].' г.'),$output);

$output = str_replace("<<cl>>",convert($pref.' «'.$client[1].'» (менеджер '.$print_add_name.')'),$output);
$output = str_replace("<<manager>>",convert($print_add_name),$output);

$output = str_replace("<<order>>",$row['order'],$output);
$output = str_replace("<<cash>>",convert(number_format($row['cash']/100, 2, '.', ' ').' руб.'),$output);
$output = str_replace("<<cash_full>>",convert(num2str(number_format($row['cash']/100, 2, '.', ''))),$output);

echo $output; 
}


?> 

     			
