<?php


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

$aSheet->setCellValue('A1',"Перечень подвижного состава, располагаемый Транспортной компанией");
$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($hiFont);

$aSheet->mergeCells('A1:K1');


$aSheet->setCellValue('B3',"Владелец ТС");
$aSheet->getStyle('B3')->getAlignment()->setWrapText(true);
$aSheet->getStyle('B3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->mergeCells('B3:B4');
 
$aSheet->setCellValue('C3',"Статус");
$aSheet->getStyle('C3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->mergeCells('C3:C4');
 
$aSheet->setCellValue('D3',"Тягач");
$aSheet->getStyle('D3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->mergeCells('D3:G3');

$aSheet->setCellValue('D4',"Марка тягача");
$aSheet->getStyle('D4')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('E4',"№ ПТС");
$aSheet->getStyle('E4')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->getStyle('E4')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('F4',"Год выпуска");
$aSheet->getStyle('F4')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->getStyle('F4')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('G4',"Гос номер тягача");
$aSheet->getStyle('G4')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('G4')->getAlignment()->setWrapText(true);



$aSheet->setCellValue('H3',"П/прицеп");
$aSheet->getStyle('H3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('H3')->getAlignment()->setWrapText(true);
$aSheet->mergeCells('H3:K3');

$aSheet->setCellValue('H4',"Марка п/прицепа");
$aSheet->getStyle('H4')->applyFromArray($center)->applyFromArray($boldFont);
 
$aSheet->setCellValue('I4',"№ ПТС");
$aSheet->getStyle('I4')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->getStyle('I4')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('J4',"Год выпуска");
$aSheet->getStyle('J4')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->getStyle('J4')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('K4',"Гос номер п/прицепа");
$aSheet->getStyle('K4')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('K4')->getAlignment()->setWrapText(true);


$query_tr = "SELECT `id`,`name`,`pref` FROM `transporters`";
$result_tr = mysql_query($query_tr) or die(mysql_error());
while($tr = mysql_fetch_row($result_tr)) {
$transporters[$tr[0]]= $tr[1];
$tr_pref_array[$tr[0]]= $tr[2];
}

$query = "SELECT `transporter`,`car_name`,`car_number`,`car_extra_name`,`car_extra_number` FROM `tr_autopark` WHERE `delete`='0' ORDER BY `Id` DESC";
$result = mysql_query($query) or die(mysql_error()); 
if (mysql_num_rows($result)>0){
$p=5;
while($row = mysql_fetch_array($result)) {

$pref_tr='';
switch ($tr_pref_array[$row['transporter']]) {
case '1': $pref_tr='ООО';break;
case '2': $pref_tr='ОАО';break;
case '3': $pref_tr='ИП';break;
case '4': $pref_tr='ЗАО';break;}

$aSheet->setCellValue('A'.$p,($p-4));

$aSheet->setCellValue('B'.$p,$transporters[$row['transporter']].', '.$pref_tr);


$aSheet->setCellValue('D'.$p,$row['car_name']);


$aSheet->setCellValue('G'.$p,$row['car_number']);


$aSheet->setCellValue('H'.$p,$row['car_extra_name']);


$aSheet->setCellValue('K'.$p,$row['car_extra_number']);




$p++;

}
}




//$aSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$aSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
//$aSheet->getPageSetup()->setPrintArea('A1:K');
$aSheet->getPageSetup()->setFitToPage(true);
$aSheet->getPageSetup()->setFitToWidth(1);
$aSheet->getPageSetup()->setFitToHeight(0);





//отдаем пользователю в браузер
include("PHPExcel/Writer/Excel5.php");
$pExcel->setActiveSheetIndex(0);
$pExcel->removeSheetByIndex(1);


$objWriter = new PHPExcel_Writer_Excel5($pExcel);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Отчет.xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');













?>