<?php
session_start();

$validate = true;

include "../config.php";


$query = "SELECT `id`,`name` FROM `company` WHERE `active`='1'";
$result = mysql_query($query) or die(mysql_error());
while($company= mysql_fetch_row($result)) {
    if($company[0]!=1) $tr_cont_name[$company[0]] = $company[1];
}


set_include_path(get_include_path() . PATH_SEPARATOR .
'PhpExcel/Classes/');
//подключаем и создаем класс PHPExcel
include_once 'PHPExcel.php';
$pExcel = new PHPExcel();


$boldFont = array(
	'font'=>array(
		'name'=>'Arial Cyr',
		'size'=>'12',
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
		'size'=>'16'		
	)
);
$minFont = array(
	'font'=>array(
		'name'=>'Arial Cyr',
		'size'=>'12'		
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
	'allborders' => array(
		'style' => PHPExcel_Style_Border::BORDER_MEDIUM
		),
	),
);
$styleArray2 = array(
'borders' => array(
	'allborders' => array(
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

if($_GET['mode']=='print'){
switch (date('m')) {
case '1': $month='Января';break;
case '2': $month='Февраля';break;
case '3': $month='Марта';break;
case '4': $month='Апреля';break;
case '5': $month='Мая';break;
case '6': $month='Июня';break;
case '7': $month='Июля';break;
case '8': $month='Августа';break;
case '9': $month='Сентября';break;
case '10': $month='Октября';break;
case '11': $month='Ноября';break;
case '12': $month='Декабря';break;}

if ($_GET['date_start']!=''){
$start_elements  = explode("/",$_GET['date_start']);
$date_start=date("Y-m-d",strtotime($start_elements[2]."-".$start_elements[1]."-".$start_elements[0]));
$end_elements  = explode("/",$_GET['date_end']);
$date_end=date("Y-m-d",strtotime($end_elements[2]."-".$end_elements[1]."-".$end_elements[0]));}




$aSheet->setTitle("Отчет по платежам");
$aSheet->setCellValue('A1',"Планируемые выплаты за период с ".$_GET['date_start']." по ".$_GET['date_end']);
$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($hiFont);
$aSheet->mergeCells('A1:H1');
$aSheet->setCellValue('A3',"№");
$aSheet->getStyle('A3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('B3',"№ Заявки");
$aSheet->getStyle('B3')->getAlignment()->setWrapText(true);
$aSheet->getStyle('B3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('C3',"Перевозчик");
$aSheet->getStyle('C3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('D3',"Форма");
$aSheet->getStyle('D3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('E3',"План.сумма");
$aSheet->getStyle('E3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('F3',"Сумма платежа");
$aSheet->getStyle('F3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->getStyle('F3')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('G3',"Пр.(дн.)");
$aSheet->getStyle('G3')->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('H3',"Автор");
$aSheet->getStyle('H3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->getStyle('A3:R3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('EAEAEA');
$aSheet->getStyle('A3:H3')->applyFromArray($styleArray2);

	
if($validate)
{
$query_user = "SELECT `id`,`name` FROM `workers` WHERE `group`='2' OR `group`='4'";
$result_user = mysql_query($query_user) or die(mysql_error());

while($user = mysql_fetch_row($result_user)) {
$pieces = explode(" ", $user[1]);
$print_add_name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
$users[$user[0]]=$print_add_name;
}

$query_pay = "SELECT `id`,`order`,`cash`,`add_name` FROM `pays` WHERE `way`='2' AND `category`='1' AND `appoint`='2' AND `status`='0' AND `delete`='0' AND DATE(`date`) BETWEEN '".$date_start."' AND '".$date_end."'";
$result_pay = mysql_query($query_pay) or die(mysql_error());
$p=4;
while($ps= mysql_fetch_row($result_pay)) {

    $query = "SELECT `id`,`transp`,`tr_nds`,`tr_cash`,`tr_tfpay`,`tr_event`,`date_in1`,`date_out2`,`date_in2`,`client`,`cl_cash`,`tr_cont` FROM `orders` WHERE `id`='".mysql_escape_string($ps[1])."' ORDER BY `Id` DESC";


$result = mysql_query($query) or die(mysql_error());
while($orders= mysql_fetch_row($result)) {

$query_tr = "SELECT `name` FROM `transporters` WHERE `id`='".mysql_escape_string($orders[1])."'";
$result_tr = mysql_query($query_tr) or die(mysql_error());
$tr = mysql_fetch_row($result_tr);

switch ($orders[2]) {
case '0': $nds_tr='без НДС';$mass_nds_0[]=$p;break;
case '1': $nds_tr='с НДС';$mass_nds_1[]=$p;break;
case '2': $nds_tr='НАЛ';$mass_nds_2[]=$p;break;
}


switch ($orders[11]) {
    case '2': $mass_tr_cont_2[]=$p;break;
    case '3': $mass_tr_cont_3[]=$p;break;
    case '9': $mass_tr_cont_4[]=$p;break;
    case '10': $mass_tr_cont_5[]=$p;break;
    case '6': $mass_tr_cont_6[]=$p;break;
    case '7': $mass_tr_cont_7[]=$p;break;
    case '8': $mass_tr_cont_8[]=$p;break;
}


$query_cl = "SELECT `name` FROM `clients` WHERE `id`='".mysql_escape_string($orders[9])."'";
$result_cl = mysql_query($query_cl) or die(mysql_error());
$cl = mysql_fetch_row($result_cl);



$tr_pay=0;

$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($orders[0])."' AND `delete`='0' AND `appoint`='2' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
    $tr_pay=(int)$pay[0]+(int)$tr_pay;
}
if((int)$tr_pay/100!=(int)$orders[3]){
$tr_event="";
switch ($orders[5]) {
    case '1': $tr_event=$orders[6];break;
    case '2': $tr_event=$orders[7];break;

    case '3': $query_docs = "SELECT `date_tr_receve` FROM `docs` WHERE `order`='".mysql_escape_string($orders[0])."'";
    $result_docs = mysql_query($query_docs) or die(mysql_error());
    $tr_events=mysql_fetch_row($result_docs);
    if(mysql_num_rows($result_docs)>0&&$tr_events[0]!="0000-00-00"&&$tr_events[0]!="1970-01-01"&&$tr_events[0]!=""){
    $tr_event=$tr_events[0];};break;

    case '4': $query_docs = "SELECT `date_tr_receve` FROM `docs` WHERE `order`='".mysql_escape_string($orders[0])."'";
    $result_docs = mysql_query($query_docs) or die(mysql_error());
    $tr_events=mysql_fetch_row($result_docs);
    if(mysql_num_rows($result_docs)>0&&$tr_events[0]!="0000-00-00"&&$tr_events[0]!="1970-01-01"&&$tr_events[0]!=""){
    $tr_event=$tr_events[0];};break;
}
if($tr_event!="0000-00-00"&&$tr_event!="1970-01-01"&&$tr_event!=""){
    $tr_event_date=date('d/m/Y', strtotime('+'.(int)$orders[4].' day', strtotime($tr_event)));

    $elements  = explode("/",$tr_event_date);
    $current_date = mktime (0,0,0,date("m") ,date("d"),date("Y")); //дата сегодня
    $old_date = mktime (0,0,0,$elements[1],$elements[0],$elements[2]);
    $difference = ($current_date - $old_date);
    $difference_in_days = floor($difference / 86400); //разница в днях
} 
} else $difference_in_days=0;

$cash_all = number_format((int)$ps[2]/100, 2, '.', '');



$aSheet->setCellValue('A'.$p,$ps[0]);
    $aSheet->getStyle('A'.$p)->applyFromArray($center);
$aSheet->setCellValue('B'.$p,$orders[0]);
    $aSheet->getStyle('B'.$p)->applyFromArray($center);
$aSheet->setCellValue('C'.$p,$tr[0]);
    $aSheet->getStyle('C'.$p)->applyFromArray($boldFont)->applyFromArray($center);
//$aSheet->setCellValue('D'.$p,$nds_tr);
$aSheet->setCellValue('D'.$p,$nds_tr.' ('.$tr_cont_name[$orders[11]].')');
    $aSheet->getStyle('D'.$p)->applyFromArray($center);

if($tr_pay!=0)$cash=" (Опл.: ".((int)$tr_pay/100).")"; else $cash="";

$aSheet->setCellValue('E'.$p,$orders[3].$cash);
$aSheet->getStyle('E'.$p)->applyFromArray($center); 
$aSheet->setCellValue('F'.$p,(float)$cash_all);
$aSheet->getStyle('F'.$p)->applyFromArray($boldFont)->applyFromArray($center);
$aSheet->setCellValue('G'.$p,$difference_in_days);

$aSheet->getStyle('G'.$p)->applyFromArray($center); 

$aSheet->setCellValue('H'.$p,$users[$ps[3]]);
$aSheet->getStyle('H'.$p)->applyFromArray($center); 

$aSheet->getStyle('A'.$p.':H'.$p)->applyFromArray($styleArray2);

if(((int)$tr_pay+(int)$ps[2])/100>(int)$orders[3])$aSheet->getStyle('A'.$p.':H'.$p)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FF7575');

$aSheet->getStyle('F'.$p)->getNumberFormat()->setFormatCode('#.##0,0;');
$aSheet->getRowDimension($p)->setRowHeight(25);
$p++;
}

}




for($i = 1; $i <= (count($company)+1); $i++) {

switch ($i) {
    case '1': $mass_cont_temp=$mass_tr_cont_2;break;
    case '2': $mass_cont_temp=$mass_tr_cont_3;break;
    case '3': $mass_cont_temp=$mass_tr_cont_4;break;
    case '4': $mass_cont_temp=$mass_tr_cont_5;break;
    case '5': $mass_cont_temp=$mass_tr_cont_6;break;
    case '6': $mass_cont_temp=$mass_tr_cont_7;break;
    case '7': $mass_cont_temp=$mass_tr_cont_8;break;
}

$aSheet->setCellValue('E'.($p+$i),$tr_cont_name[($i+1)].':');
$aSheet->getStyle('E'.($p+$i))->applyFromArray($right);

if($mass_cont_temp)$aSheet->setCellValue('F'.($p+$i),'=F'.implode('+F', $mass_cont_temp)); else $aSheet->setCellValue('F'.($p+$i),'0');
$aSheet->getStyle('F'.($p+$i))->applyFromArray($center);

}


//$aSheet->setCellValue('E'.($p+2),"Капитал-Транс:");
//$aSheet->getStyle('E'.($p+2))->applyFromArray($right);

//if($mass_tr_cont_3)$aSheet->setCellValue('F'.($p+2),'=F'.implode('+F', $mass_tr_cont_3)); else $aSheet->setCellValue('F'.($p+2),'0');
//$aSheet->getStyle('F'.($p+2))->applyFromArray($center);





$aSheet->setCellValue('E'.($p+1+$i),"Итого:");
$aSheet->getStyle('E'.($p+1+$i))->applyFromArray($right)->applyFromArray($boldFont); 

if(($p-4)!=0)$counts=($p-4); else $counts=0;

$aSheet->setCellValue('B'.($p+1),"Обработано: ".$counts." платеж(а)");
$aSheet->getStyle('B'.($p+1))->applyFromArray($boldFont); 




$aSheet->setCellValue('F'.($p+1+$i),'=SUM(F2:F'.($p-1).')');
$aSheet->getStyle('F'.($p+1+$i))->applyFromArray($center)->applyFromArray($boldFont); 



$aSheet->setCellValue('C'.($p+6+$i),'____________________ ');$aSheet->setCellValue('E'.($p+6+$i),'____________________ ');
//$aSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$aSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$aSheet->getPageSetup()->setPrintArea('A1:G'.($p+10));
$aSheet->getPageSetup()->setFitToPage(true);
$aSheet->getPageSetup()->setFitToWidth(1);
$aSheet->getPageSetup()->setFitToHeight(0);

$aSheet->getColumnDimension('A')->setWidth(10);
$aSheet->getColumnDimension('B')->setWidth(13);
$aSheet->getColumnDimension('C')->setWidth(40);
$aSheet->getColumnDimension('D')->setWidth(30);
$aSheet->getColumnDimension('E')->setWidth(20);
$aSheet->getColumnDimension('F')->setWidth(12);
$aSheet->getColumnDimension('G')->setWidth(9);
$aSheet->getColumnDimension('H')->setWidth(20);





//отдаем пользователю в браузер
include("PHPExcel/Writer/Excel5.php");
$pExcel->setActiveSheetIndex(0);
$pExcel->removeSheetByIndex(1);


$objWriter = new PHPExcel_Writer_Excel5($pExcel);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Отчет.xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');


}



}







?>