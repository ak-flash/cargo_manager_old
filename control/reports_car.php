<?php
set_time_limit(250); 
include "../config.php";
session_start();
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


$query_transp = "SELECT `id`,`name` FROM `transporters`";
$result_transp = mysql_query($query_transp) or die(mysql_error());
while($transp = mysql_fetch_row($result_transp)) {
$transporters[$transp[0]]= $transp[1];
}




$pExcel->setActiveSheetIndex(0);
$pExcel->createSheet();

$aSheet = $pExcel->getActiveSheet();

$aSheet->setTitle("Отчет по автотранспорту");

$aSheet->freezePane('A1');
$aSheet->freezePane('A2');
$aSheet->freezePane('B1');
$aSheet->freezePane('B2');

$aSheet->setCellValue('A1',"№");
$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('B1',"Перевозчик");
$aSheet->getStyle('B1')->getAlignment()->setWrapText(true);
$aSheet->getStyle('B1')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('C1',"Тягач");
$aSheet->getStyle('C1')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('D1',"Номер");
$aSheet->getStyle('D1')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('E1',"Прицеп");
$aSheet->getStyle('E1')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('F1',"Номер");
$aSheet->getStyle('F1')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('G1',"Объем");
$aSheet->getStyle('G1')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('H1',"Грузо-ть");
$aSheet->getStyle('H1')->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('I1',"Кузов");
$aSheet->getStyle('I1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->setCellValue('J1',"Владелец");
$aSheet->getStyle('J1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->setCellValue('K1',"СТС");
$aSheet->getStyle('K1')->applyFromArray($center)->applyFromArray($boldFont);

$aSheet->setCellValue('L1',"Документы");
$aSheet->getStyle('L1')->applyFromArray($center)->applyFromArray($boldFont);

$aSheet->setCellValue('M1',"Водитель");
$aSheet->getStyle('M1')->applyFromArray($center)->applyFromArray($boldFont);

$p=2;
$query = "SELECT * FROM `tr_autopark` ORDER BY `id` DESC";



$result = mysql_query($query) or die(mysql_error()); 
while($row = mysql_fetch_array($result)) {



$aSheet->getStyle('A'.$p)->applyFromArray($center);

$aSheet->getStyle('C'.$p)->applyFromArray($center);
$aSheet->getStyle('D'.$p)->applyFromArray($center);
$aSheet->getStyle('E'.$p)->applyFromArray($center);
$aSheet->getStyle('F'.$p)->applyFromArray($center);
$aSheet->getStyle('G'.$p)->applyFromArray($center);
$aSheet->getStyle('H'.$p)->applyFromArray($center);
$aSheet->getStyle('I'.$p)->applyFromArray($center);

$aSheet->getStyle('I'.$p)->applyFromArray($center);
$aSheet->getStyle('L'.$p)->applyFromArray($center);

$aSheet->getStyle('K'.$p)->applyFromArray($center);

$aSheet->setCellValue('A'.$p,$row['id']);

$aSheet->setCellValue('B'.$p,$transporters[$row['transporter']]);
$aSheet->setCellValue('C'.$p,$row['car_name']);
$aSheet->setCellValue('D'.$p,$row['car_number']);
$aSheet->setCellValue('E'.$p,$row['car_extra_name']);
$aSheet->setCellValue('F'.$p,$row['car_extra_number']);
$aSheet->setCellValue('G'.$p,$row['car_v']);
$aSheet->setCellValue('H'.$p,$row['car_m']);
$aSheet->setCellValue('I'.$p,$row['car_kuzov']); 	

$aSheet->setCellValue('J'.$p,$row['car_owner']); 
$aSheet->setCellValue('K'.$p,$row['car_owner_doc']); 

switch ($row['check']) {
case '0': $check_info="не проверялся";break;
case '1': $check_info="ожидает проверки";break;
case '2': $check_info="подтверждено";break;
case '3': $check_info="недостоверные данные";break;
}


 	$aSheet->setCellValue('L'.$p,$check_info); 


$aSheet->setCellValue('M'.$p,$row['car_driver_name']); 



$aSheet->setCellValue('N'.$p,$row['car_driver_doc']); 

$p++;

}





$aSheet->setCellValue('B'.($p+1),"Обработано: ".($p-2)." машин");
$aSheet->getStyle('B'.($p+1))->applyFromArray($boldFont); 






$aSheet->getColumnDimension('A')->setWidth(5);
$aSheet->getColumnDimension('B')->setWidth(35);
$aSheet->getColumnDimension('C')->setWidth(15);
$aSheet->getColumnDimension('D')->setWidth(15);
$aSheet->getColumnDimension('E')->setWidth(15);
$aSheet->getColumnDimension('F')->setWidth(15);
$aSheet->getColumnDimension('G')->setWidth(10);
$aSheet->getColumnDimension('H')->setWidth(10);
$aSheet->getColumnDimension('I')->setWidth(20);
$aSheet->getColumnDimension('J')->setWidth(20);
$aSheet->getColumnDimension('K')->setWidth(15);
$aSheet->getColumnDimension('L')->setWidth(15);
$aSheet->getColumnDimension('M')->setWidth(30);
$aSheet->getColumnDimension('N')->setWidth(40);

$aSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
//$aSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$aSheet->getPageSetup()->setPrintArea('A1:G25');
$aSheet->getPageSetup()->setFitToPage(true);
$aSheet->getPageSetup()->setFitToWidth(1);
$aSheet->getPageSetup()->setFitToHeight(0);

//отдаем пользователю в браузер
include("PHPExcel/Writer/Excel5.php");
$pExcel->setActiveSheetIndex(0);
$pExcel->removeSheetByIndex(1);


$objWriter = new PHPExcel_Writer_Excel5($pExcel);
header('Content-Type: application/vnd.ms-excel');


header('Content-Disposition: attachment;filename="report_car.xls"');

header('Cache-Control: max-age=0');
$objWriter->save('php://output');






?> 

     			
