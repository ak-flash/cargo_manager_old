<?php
if(@$_GET['mode']=='car'){
include "../config.php";

set_include_path(get_include_path() . PATH_SEPARATOR .
'PhpExcel/Classes/');
//подключаем и создаем класс PHPExcel
include_once 'PHPExcel.php';
$pExcel = new PHPExcel();


$boldFont = array(
	'font'=>array(
		'name'=>'Arial Cyr',
		'size'=>'10',
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

$aSheet->setTitle("Отчет по рейсам");
$aSheet->mergeCells('A1:F1');


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


$query_car = "SELECT `name`,`number` FROM `vtl_auto` WHERE `id`='".(int)$_GET['car_number']."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car = mysql_fetch_row($result_car);


$start_elements  = explode("/",$_GET['date_start']);
$date_start=date("Y-m-d",strtotime($start_elements[2]."-".$start_elements[1]."-".$start_elements[0]));
$end_elements  = explode("/",$_GET['date_end']);
$date_end=date("Y-m-d",strtotime($end_elements[2]."-".$end_elements[1]."-".$end_elements[0]));
	
$aSheet->setCellValue('A1',"Отчет по автомобилю с гос.номером - ".$car[0].' - '.$car[1].' за период с '.$_GET['date_start'].' по '.$_GET['date_end']);
$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('A3','Заявка');
$aSheet->getStyle('A3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('B3','Загрузка');
$aSheet->getStyle('B3')->applyFromArray($center)->applyFromArray($boldFont); $aSheet->setCellValue('C3','Выгрузка');
$aSheet->getStyle('C3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('D3','Клиент');
$aSheet->getStyle('D3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('E3','Ставка кл.');
$aSheet->getStyle('E3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('F3','Форма');
$aSheet->getStyle('F3')->applyFromArray($center)->applyFromArray($boldFont); 



$query_clients = "SELECT `id`,`name` FROM `clients`";
$result_clients = mysql_query($query_clients) or die(mysql_error());
while($clients = mysql_fetch_row($result_clients)) {
$client[$clients[0]]= $clients[1];
}



mb_internal_encoding("UTF-8");








$check=true;

if($check){
$p=4;
$query = "SELECT `id`,`client`,`cl_pref`,`cl_cash`,`tr_auto`,`transp`,`cl_nds`,`date_in1`,`date_out1` FROM `orders` WHERE DATE(`date_in1`) BETWEEN '".$date_start."' AND '".$date_end."' AND `tr_auto` LIKE '".$_GET['car_number']."&%' ORDER BY `id` DESC";

$result = mysql_query($query) or die(mysql_error()); 
while($row = mysql_fetch_array($result)) {

switch ($row['cl_pref']) {
case '1': $pref_cl='ООО';break;
case '2': $pref_cl='ОАО';break;
case '3': $pref_cl='ИП';break;
case '4': $pref_cl='ЗАО';break;
case '5': $pref_cl='';break;}	
	
switch ($row['cl_nds']) {
case '0': $nds_cl='без НДС';break;
case '1': $nds_cl='с НДС';break;
case '2': $nds_cl='НАЛ';break;
}
	
$aSheet->setCellValue('A'.$p,$row['id']);
$aSheet->setCellValue('B'.$p,date('d/m/Y',strtotime($row['date_in1'])));
$aSheet->getStyle('B'.$p)->applyFromArray($center);
$aSheet->setCellValue('C'.$p,date('d/m/Y',strtotime($row['date_out1'])));
$aSheet->getStyle('C'.$p)->applyFromArray($center);

$aSheet->setCellValue('D'.$p,$pref_cl.' «'.$client[$row['client']].'»');
$aSheet->setCellValue('E'.$p,$row['cl_cash']);
$aSheet->getStyle('E'.$p)->applyFromArray($center); 
$aSheet->setCellValue('F'.$p,$nds_cl);
$aSheet->getStyle('F'.$p)->applyFromArray($center); 

$p++;
}

$aSheet->setCellValue('B'.($p+1),'Количество заявок: '.($p-4));
$aSheet->setCellValue('E'.($p+1),'=SUM(E4:E'.($p-1).')');
$aSheet->getStyle('E'.($p+1))->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->getColumnDimension('A')->setWidth(7);
$aSheet->getColumnDimension('B')->setWidth(13);
$aSheet->getColumnDimension('C')->setWidth(13);
$aSheet->getColumnDimension('D')->setWidth(35);
$aSheet->getColumnDimension('E')->setWidth(10);
$aSheet->getColumnDimension('F')->setWidth(10);

//$aSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$aSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$aSheet->getPageSetup()->setPrintArea('A1:E'.($p+3));
$aSheet->getPageSetup()->setFitToPage(true);
$aSheet->getPageSetup()->setFitToWidth(1);
$aSheet->getPageSetup()->setFitToHeight(0);

//отдаем пользователю в браузер
include("PHPExcel/Writer/Excel5.php");
$pExcel->setActiveSheetIndex(0);
$pExcel->removeSheetByIndex(1);


$objWriter = new PHPExcel_Writer_Excel5($pExcel);
header('Content-Type: application/vnd.ms-excel');


header('Content-Disposition: attachment;filename="car_'.$car[0].'_'.$_GET['month']."-".$_GET['year'].'.xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');
}
}
?> 