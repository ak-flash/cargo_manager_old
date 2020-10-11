<?php

include "../config.php";




if ($_GET['mode']=='pays') 
{
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



set_include_path(get_include_path() . PATH_SEPARATOR .
'PhpExcel/Classes/');
//подключаем и создаем класс PHPExcel
include_once 'PHPExcel.php';
$pExcel = new PHPExcel();



$boldFont = array(
	'font'=>array(
	
		'size'=>'16',
		'bold'=>true
	)
);
$hFont = array(
	'font'=>array(
		'name'=>'Arial Cyr',
		'size'=>'16',
		'bold'=>true
	)
);
$hiFont = array(
	'font'=>array(
		'name'=>'Arial Cyr',
		'size'=>'18'		
	)
);
//и позиционирование
$center = array(
	'alignment'=>array(
		'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical'=>PHPExcel_Style_Alignment::VERTICAL_CENTER
	)
);

$right = array(
	'alignment'=>array(
		'horizontal'=>PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
		'vertical'=>PHPExcel_Style_Alignment::VERTICAL_TOP
	)
);
$top = array(
	'alignment'=>array(
				'vertical'=>PHPExcel_Style_Alignment::VERTICAL_TOP
	)
);

$styleArray = array(
'borders' => array(
	'outline' => array(
		'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		),
	),
);
$styleArray2 = array(
'borders' => array(
	'inside' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN
		),
	),
);

$styleArray3 = array(
'borders' => array(
	'allborders' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN
		),
	),
);
$styleArray4 = array(
'borders' => array(
	'bottom' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN
		),
	),
);
$hr = array(
'borders' => array(
	'bottom' => array(
		'style' => PHPExcel_Style_Border::BORDER_THIN
		),
	),
);


$pExcel->setActiveSheetIndex(0);
$pExcel->createSheet();

$aSheet = $pExcel->getActiveSheet();

$aSheet->setTitle("Отчёт по платежам");
$aSheet->mergeCells('A1:F1');

$query_tr = "SELECT `id`,`name` FROM `transporters`";
$result_tr = mysql_query($query_tr) or die(mysql_error());

while($tr = mysql_fetch_row($result_tr)) {

$transporters[$tr[0]]= $tr[1];
}

$query_companys = "SELECT `id`,`name` FROM `company`";
$result_companys = mysql_query($query_companys) or die(mysql_error());
while($companys = mysql_fetch_row($result_companys)) {
$company_search[$companys[0]]= $companys[1];
}

$query_user = "SELECT `id`,`name` FROM `workers`";
$result_user = mysql_query($query_user) or die(mysql_error());



while($user = mysql_fetch_row($result_user)) {
$pieces = explode(" ", $user[1]);
$managers=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
$users[$user[0]]= $managers;
}


$q[]="";
$q[]="Январь"; 
$q[]="Февраль"; 
$q[]="Март"; 
$q[]="Апрель"; 
$q[]="Май";
$q[]="Июнь"; 
$q[]="Июль"; 
$q[]="Август"; 
$q[]="Сентябрь"; 
$q[]="Октябрь"; 
$q[]="Ноябрь";
$q[]="Декабрь";

$number[]="";
$number[]="B";
$number[]="C";
$number[]="D";
$number[]="E";
$number[]="F";	
$number[]="G";
$number[]="H";
$number[]="I";
$number[]="J";
$number[]="K";
$number[]="L";
$number[]="M";

	
if ($_GET['date_start']!=''){
$start_elements  = explode("/",$_GET['date_start']);
$date_start=date("Y-m-d",strtotime($start_elements[2]."-".$start_elements[1]."-".$start_elements[0]));
$date_start_month=$start_elements[1];
$date_start_year=$start_elements[2];
$end_elements  = explode("/",$_GET['date_end']);
$date_end=date("Y-m-d",strtotime($end_elements[2]."-".$end_elements[1]."-".$end_elements[0]));}
$date_end_month=$end_elements[1];
$date_end_year=$end_elements[2];

$app_id=(int)$_GET['app_id'];

if($app_id!=1033){


 
 
if($app_id!=1000){

$query = "SELECT `id`,`app` FROM `pays_appoints` WHERE `id`='".$app_id."'";
$result = mysql_query($query) or die(mysql_error());
 while($pays_app= mysql_fetch_row($result)) {$appoints[$pays_app[0]]=$pays_app[1];  }


$aSheet->setCellValue('A1',"Отчет по платежам - ".$appoints[$app_id].' с '.$_GET['date_start'].' по '.$_GET['date_end']);

$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($boldFont); 




$aSheet->setCellValue('A3','Выплаты');
$aSheet->getStyle('A3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->mergeCells('A3:F3');
$aSheet->getStyle('A3:F3')->applyFromArray($styleArray);
$aSheet->getStyle('A3:F3')->applyFromArray($styleArray2);

$aSheet->setCellValue('A4','№');
$aSheet->getStyle('A4')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('B4','Дата');
$aSheet->getStyle('B4')->applyFromArray($center)->applyFromArray($boldFont); $aSheet->setCellValue('C4','Сумма');
$aSheet->getStyle('C4')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('D4','Статус');
$aSheet->getStyle('D4')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('E4','Источник');
$aSheet->getStyle('E4')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('F4','Примечание');
$aSheet->getStyle('F4')->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->getStyle('A4:F4')->applyFromArray($styleArray);
$aSheet->getStyle('A4:F4')->applyFromArray($styleArray2);

if($app_id==2) {$aSheet->setCellValue('D4','Менеджер');
$aSheet->getStyle('D4')->applyFromArray($center)->applyFromArray($boldFont);} 

$p=5;

$query = "SELECT * FROM `pays` WHERE `way`='2' AND `delete`='0' AND `appoint`='$app_id' AND DATE(`date`) BETWEEN '".$date_start."' AND '".$date_end."' AND `add_name`!='0' ORDER BY `date` ASC";
$result = mysql_query($query) or die(mysql_error()); 
while($row = mysql_fetch_array($result)) {


switch ($row['status']) {
case '0': $status='Не проведен';break;
case '1': $status='Проведен';break;
} 
if($row['car_id']!=0){
$query_car = "SELECT `name`,`number`,`type` FROM `vtl_auto` WHERE `delete`='0' AND `id`='".(int)$row['car_id']."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car_id = mysql_fetch_row($result_car);
$car_name=$car_id[0].'-'.$car_id[1];
} else $car_name='';
		
		$query_с = "SELECT `name` FROM `company` WHERE `Id`='".mysql_escape_string($row['payment_source'])."'";
$result_с = mysql_query($query_с) or die(mysql_error());
$с_info = mysql_fetch_row($result_с);

$data=date("d/m/Y",strtotime($row['date']));

$cash=$row['cash']/100;

$aSheet->setCellValue('A'.$p,$row['order']);
$aSheet->getStyle('A'.$p)->applyFromArray($center);
$aSheet->setCellValue('B'.$p,$data);
$aSheet->getStyle('B'.$p)->applyFromArray($center);
$aSheet->setCellValue('C'.$p,$cash);
$aSheet->getStyle('C'.$p)->applyFromArray($center);
$aSheet->setCellValue('D'.$p,$status);
$aSheet->getStyle('D'.$p)->applyFromArray($center); 
$aSheet->setCellValue('E'.$p,$с_info[0]);


$aSheet->getStyle('E'.$p)->applyFromArray($center); 

if($app_id==2) {
$query_tr = "SELECT `transp`,`tr_manager`,`tr_cont` FROM `orders` WHERE `id`='".mysql_escape_string($row['order'])."'";
$result_tr = mysql_query($query_tr) or die(mysql_error());
$tr= mysql_fetch_row($result_tr);



$aSheet->setCellValue('F'.$p,$transporters[$tr[0]]);} else $aSheet->setCellValue('F'.$p,$car_name.' '.$row['notify']);
$aSheet->getStyle('A'.$p.':F'.$p)->applyFromArray($styleArray3);
if($с_info[0]=='Касса') $aSheet->setCellValue('E'.$p,$company_search[$tr[2]].' (НАЛ)'); else $aSheet->setCellValue('E'.$p,$company_search[$tr[2]]);



$aSheet->setCellValue('D'.$p,$users[$tr[1]]);



$p++;
}

$aSheet->setCellValue('E'.($p+1),($p-5).' платежей');
$aSheet->setCellValue('C'.($p+1),'=SUM(C5:C'.($p-1).')');
$aSheet->getStyle('C'.($p+1))->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->setCellValue('B'.($p+1),'Итого');
$aSheet->getStyle('B'.($p+1))->applyFromArray($right)->applyFromArray($boldFont);

$aSheet->setCellValue('A'.($p+3),'Поступления');
$aSheet->getStyle('A'.($p+3))->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->mergeCells('A'.($p+3).':F'.($p+3));
$aSheet->getStyle('A'.($p+3).':F'.($p+3))->applyFromArray($styleArray);
$aSheet->getStyle('A'.($p+3).':F'.($p+3))->applyFromArray($styleArray2);

$aSheet->setCellValue('A'.($p+4),'№');
$aSheet->getStyle('A'.($p+4))->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('B'.($p+4),'Дата');
$aSheet->getStyle('B'.($p+4))->applyFromArray($center)->applyFromArray($boldFont); $aSheet->setCellValue('C'.($p+4),'Сумма');
$aSheet->getStyle('C'.($p+4))->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('D'.($p+4),'Статус');
$aSheet->getStyle('D'.($p+4))->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('E'.($p+4),'Источник');
$aSheet->getStyle('E'.($p+4))->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('F'.($p+4),'Примечание');
$aSheet->getStyle('F'.($p+4))->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->getStyle('A'.($p+4).':F'.($p+4))->applyFromArray($styleArray);
$aSheet->getStyle('A'.($p+4).':F'.($p+4))->applyFromArray($styleArray2);

$m=5;

$query = "SELECT * FROM `pays` WHERE `way`='1' AND `delete`='0' AND `appoint`='$app_id' AND DATE(`date`) BETWEEN '".$date_start."' AND '".$date_end."' AND `add_name`!='0' ORDER BY `date` ASC";
$result = mysql_query($query) or die(mysql_error()); 
while($row = mysql_fetch_array($result)) {


switch ($row['status']) {
case '0': $status='Не проведен';break;
case '1': $status='Проведен';break;
} 
		
		$query_с = "SELECT `name` FROM `company` WHERE `Id`='".mysql_escape_string($row['payment_source'])."'";
$result_с = mysql_query($query_с) or die(mysql_error());
$с_info = mysql_fetch_row($result_с);

$data=date("d/m/Y",strtotime($row['date']));

$cash=$row['cash']/100;

$aSheet->setCellValue('A'.($m+$p),$row['id']);
$aSheet->getStyle('A'.($m+$p))->applyFromArray($center);
$aSheet->setCellValue('B'.($m+$p),$data);
$aSheet->getStyle('B'.($m+$p))->applyFromArray($center);
$aSheet->setCellValue('C'.($m+$p),$cash);
$aSheet->getStyle('C'.($m+$p))->applyFromArray($center);
$aSheet->setCellValue('D'.($m+$p),$status);
$aSheet->getStyle('D'.($m+$p))->applyFromArray($center); 
$aSheet->setCellValue('E'.($m+$p),$с_info[0]);

$aSheet->setCellValue('F'.($m+$p),$row['notify']);

$aSheet->getStyle('A'.($m+$p).':F'.($m+$p))->applyFromArray($styleArray3);

$m++;
}

$aSheet->setCellValue('E'.($m+$p+1),($m-5).' платежей');
$aSheet->setCellValue('C'.($m+$p+1),'=SUM(C'.($p+5).':C'.($m+$p-1).')');
$aSheet->getStyle('C'.($m+$p+1))->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->setCellValue('B'.($m+$p+1),'Итого');
$aSheet->getStyle('B'.($m+$p+1))->applyFromArray($right)->applyFromArray($boldFont);










 
$aSheet->getPageSetup()->setPrintArea('A1:F'.($m+$p+3));
$aSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
} else {
	
	
	
$aSheet->setCellValue('A1',"Отчет по всем платежам  за ".$date_start_year.' год');
$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('A2',"Поступления");
$aSheet->getStyle('A2')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->mergeCells('A2:N2');
$aSheet->setCellValue('A3','Категория');
$aSheet->getStyle('A3')->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->getStyle('A3:N3')->applyFromArray($styleArray);
$aSheet->getStyle('A3:N3')->applyFromArray($styleArray2);



$p=4;

$query = "SELECT `id`,`app` FROM `pays_appoints` WHERE `way`='1' OR `way`='0'";
$result = mysql_query($query) or die(mysql_error());



 while($pays_app= mysql_fetch_row($result)) {

$aSheet->setCellValue('A'.$p,$pays_app[1]); 

for($month=1;$month<13;$month++){
$cash_plus=0;
$aSheet->setCellValue($number[$month].'3',$q[$month]);
$aSheet->getStyle($number[$month].'3')->applyFromArray($center)->applyFromArray($boldFont); 

$query_cash = "SELECT `cash` FROM `pays` WHERE `way`='1' AND `delete`='0' AND `appoint`='$pays_app[0]' AND DATE(`date`) BETWEEN '".$date_start_year."-".$month."-01' AND '".$date_start_year."-".$month."-31' AND `status`='1' AND `add_name`!='0'";

$result_cash = mysql_query($query_cash) or die(mysql_error()); 
while($row_cash = mysql_fetch_array($result_cash)) {
$cash_plus=$cash_plus+$row_cash['cash'];
}
$aSheet->setCellValue($number[$month].$p,$cash_plus/100);


}



$aSheet->getStyle('A'.$p.':N'.$p)->applyFromArray($styleArray2);
$aSheet->getStyle('A'.$p.':N'.$p)->applyFromArray($styleArray3);
$aSheet->getStyle('A'.($p+1).':N'.($p+1))->applyFromArray($styleArray);
$aSheet->getStyle('A'.($p+1).':N'.($p+1))->applyFromArray($styleArray2);

$aSheet->setCellValue('N'.$p,'=SUM(B'.$p.':M'.$p.')');
$aSheet->getStyle('N'.$p)->applyFromArray($boldFont); 

 $p++;

 
 

 }



for($month=1;$month<13;$month++){
$aSheet->setCellValue($number[$month].$p,'=SUM('.$number[$month].'4:'.$number[$month].($p-1).')');
$aSheet->getStyle($number[$month].$p)->applyFromArray($center)->applyFromArray($boldFont);
}


$aSheet->setCellValue('A'.$p,'Итого');
$aSheet->getStyle('A'.$p)->applyFromArray($boldFont); 

$aSheet->setCellValue('N3','Итого');
$aSheet->getStyle('N3')->applyFromArray($boldFont); 

$aSheet->setCellValue('N'.$p,'=SUM(B'.$p.':M'.$p.')');
$aSheet->getStyle('N'.$p)->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('A'.($p+1),"Отчет по всем платежам  за ".$date_start_year.' год');
$aSheet->getStyle('A'.($p+1))->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->mergeCells('A'.($p+1).':F'.($p+1));
$aSheet->setCellValue('A'.($p+2),"Выплаты");
$aSheet->getStyle('A'.($p+2))->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->mergeCells('A'.($p+2).':N'.($p+2));

$aSheet->setCellValue('A'.($p+3),'Категория');
$aSheet->getStyle('A'.($p+3))->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->getStyle('A'.($p+3).':N'.($p+3))->applyFromArray($styleArray);
$aSheet->getStyle('A'.($p+3).':N'.($p+3))->applyFromArray($styleArray2);


$query = "SELECT `id`,`app` FROM `pays_appoints` WHERE `way`='2' OR `way`='0'";
$result = mysql_query($query) or die(mysql_error());


$s=4;

while($pays_app= mysql_fetch_row($result)) {

$aSheet->setCellValue('A'.($p+$s),$pays_app[1]); 

for($month=1;$month<13;$month++){
$cash_minus=0;
$aSheet->setCellValue($number[$month].($p+3),$q[$month]);
$aSheet->getStyle($number[$month].($p+3))->applyFromArray($center)->applyFromArray($boldFont); 

$query_cash = "SELECT `cash` FROM `pays` WHERE `way`='2' AND `delete`='0' AND `appoint`='$pays_app[0]' AND DATE(`date`) BETWEEN '".$date_start_year."-".$month."-01' AND '".$date_start_year."-".$month."-31' AND `status`='1' AND `add_name`!='0'";

$result_cash = mysql_query($query_cash) or die(mysql_error()); 
while($row_cash = mysql_fetch_array($result_cash)) {
$cash_minus=$cash_minus+$row_cash['cash'];
}
$aSheet->setCellValue($number[$month].($p+$s),$cash_minus/100);

 
}



$aSheet->getStyle('A'.($p+$s).':N'.($p+$s))->applyFromArray($styleArray2);
$aSheet->getStyle('A'.($p+$s).':N'.($p+$s))->applyFromArray($styleArray3);
$aSheet->getStyle('A'.(($p+$s)+1).':N'.(($p+$s)+1))->applyFromArray($styleArray);
$aSheet->getStyle('A'.(($p+$s)+1).':N'.(($p+$s)+1))->applyFromArray($styleArray2);

$aSheet->setCellValue('N'.($p+$s),'=SUM(B'.($p+$s).':M'.($p+$s).')');
$aSheet->getStyle('N'.($p+$s))->applyFromArray($boldFont); 

 $s++;

 
 

 }


for($month=1;$month<13;$month++){
$aSheet->setCellValue($number[$month].($p+$s),'=SUM('.$number[$month].($p+4).':'.$number[$month].($p+$s-1).')');
$aSheet->getStyle($number[$month].($p+$s))->applyFromArray($center)->applyFromArray($boldFont);
}


$aSheet->setCellValue('A'.($p+$s),'Итого');
$aSheet->getStyle('A'.($p+$s))->applyFromArray($boldFont); 

$aSheet->setCellValue('N'.($p+3),'Итого');
$aSheet->getStyle('N'.($p+3))->applyFromArray($boldFont); 

$aSheet->setCellValue('N'.($p+$s),'=SUM(B'.($p+$s).':M'.($p+$s).')');
$aSheet->getStyle('N'.($p+$s))->applyFromArray($center)->applyFromArray($boldFont); 


 
$aSheet->getColumnDimension('A')->setWidth(20);
//$aSheet->getColumnDimension('N')->setWidth(20);





$aSheet->getPageSetup()->setPrintArea('A1:N'.($p+$s));
$aSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
}

} else {

$aSheet->getDefaultStyle()->getFont()->setSize(14);


$aSheet->setCellValue('A1','Отчет по платежам - Наличные с '.$_GET['date_start'].' по '.$_GET['date_end']);

$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('A3','Поступление');
$aSheet->getStyle('A3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->mergeCells('A3:F3');
$aSheet->getStyle('A3:F3')->applyFromArray($styleArray);
$aSheet->getStyle('A3:F3')->applyFromArray($styleArray2);

$aSheet->setCellValue('A4','№');
$aSheet->getStyle('A4')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('B4','Дата');
$aSheet->getStyle('B4')->applyFromArray($center)->applyFromArray($boldFont); $aSheet->setCellValue('C4','Заявка');
$aSheet->getStyle('C4')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('D4','Клиент');
$aSheet->getStyle('D4')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('E4','Статус');
$aSheet->getStyle('E4')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('F4','Сумма (руб.)');
$aSheet->getStyle('F4')->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->getStyle('A4:F4')->applyFromArray($styleArray);
$aSheet->getStyle('A4:F4')->applyFromArray($styleArray2);


$m=5;

$query = "SELECT * FROM `pays` WHERE `way`='1' AND `delete`='0' AND `appoint`='1' AND `nds`='2' AND DATE(`date`) BETWEEN '".$date_start."' AND '".$date_end."' AND `add_name`!='0' ORDER BY `date` ASC";
$result = mysql_query($query) or die(mysql_error()); 
while($row = mysql_fetch_array($result)) {


switch ($row['status']) {
case '0': $status='Не проведен';break;
case '1': $status='Проведен';break;
} 
		
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

$data=date("d/m/Y",strtotime($row['date']));

$cash=$row['cash']/100;
$cash_full=(int)$cash_full+(int)$row['cash'];
	
$aSheet->setCellValue('A'.$m,$row['id']);
$aSheet->getStyle('A'.$m)->applyFromArray($center);
$aSheet->setCellValue('B'.$m,$data);
$aSheet->getStyle('B'.$m)->applyFromArray($center);
$aSheet->setCellValue('C'.$m,$row['order']);
$aSheet->getStyle('C'.$m)->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->setCellValue('D'.$m,$pref.' «'.$client[1].'» ('.$print_add_name.')');

$aSheet->setCellValue('E'.$m,$status);
$aSheet->getStyle('E'.$m)->applyFromArray($center); 
$aSheet->setCellValue('F'.$m,$cash);
$aSheet->getStyle('F'.$m)->applyFromArray($boldFont)->applyFromArray($center); 
$aSheet->getStyle('A'.$m.':F'.$m)->applyFromArray($styleArray3);

$m++;
}
$aSheet->setCellValue('A'.($m+1),($m-5));
$aSheet->getStyle('A'.($m+1))->applyFromArray($center);
$aSheet->setCellValue('B'.($m+1),'Итого');
$aSheet->getStyle('B'.($m+1))->applyFromArray($right);
$aSheet->getStyle('B'.($m+1))->applyFromArray($boldFont)->applyFromArray($center); 
$aSheet->setCellValue('C'.($m+1),'=SUM(F5:F'.($m-1).')');
$aSheet->getStyle('C'.($m+1))->applyFromArray($boldFont)->applyFromArray($center); 

$aSheet->setCellValue('D'.($m+1),'руб. ('.num2str(number_format($cash_full/100, 2, '.', '')).')');

$aSheet->setCellValue('B'.($m+6),'Сдал');
$aSheet->getStyle('B'.($m+6))->applyFromArray($right)->applyFromArray($boldFont);
$aSheet->setCellValue('C'.($m+7),'(Гришкевич И.А.)');
$aSheet->mergeCells('C'.($m+7).':D'.($m+7));

$aSheet->setCellValue('D'.($m+6),'Принял');
$aSheet->getStyle('D'.($m+6))->applyFromArray($right)->applyFromArray($boldFont);

$aSheet->setCellValue('E'.($m+7),'(Коробко Д.И.)');


$aSheet->getStyle('C'.($m+6))->applyFromArray($styleArray4);
$aSheet->getStyle('E'.($m+6))->applyFromArray($styleArray4);

}

$aSheet->getColumnDimension('A')->setAutoSize(t​rue);
$aSheet->getColumnDimension('B')->setAutoSize(t​rue);
$aSheet->getColumnDimension('C')->setAutoSize(t​rue);
$aSheet->getColumnDimension('D')->setAutoSize(t​rue);
$aSheet->getColumnDimension('E')->setAutoSize(t​rue);
$aSheet->getColumnDimension('F')->setAutoSize(t​rue);
$aSheet->getColumnDimension('G')->setAutoSize(t​rue);
$aSheet->getColumnDimension('N')->setAutoSize(t​rue);

$aSheet->getPageSetup()->setFitToPage(true);
$aSheet->getPageSetup()->setFitToWidth(1);
$aSheet->getPageSetup()->setFitToHeight(0);

//отдаем пользователю в браузер
include("PHPExcel/Writer/Excel5.php");
$pExcel->setActiveSheetIndex(0);
$pExcel->removeSheetByIndex(1);


$objWriter = new PHPExcel_Writer_Excel5($pExcel);
header('Content-Type: application/vnd.ms-excel');


header('Content-Disposition: attachment;filename="Платежи_'.$appoints[$app_id].'.xls"');

if($app_id==1000)header('Content-Disposition: attachment;filename="Платежи_'.$date_start_year.'.xls"'); 

if($app_id==1033)header('Content-Disposition: attachment;filename="Наличные_'.$date_start_year.'.xls"');

header('Cache-Control: max-age=0');
$objWriter->save('php://output');

}

?> 

     			
