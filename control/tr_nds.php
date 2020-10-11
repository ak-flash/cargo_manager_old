<?php

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



if(@$_GET['mode']=='nds'){
$query_tr = "SELECT `id`,`name` FROM `transporters`";
$result_tr = mysql_query($query_tr) or die(mysql_error());
while($tr = mysql_fetch_row($result_tr)) {
$transporters[$tr[0]]= $tr[1];
}




$pExcel->setActiveSheetIndex(0);
$pExcel->createSheet();

$aSheet = $pExcel->getActiveSheet();

$aSheet->setTitle("Отчет по заявкам");


$aSheet->setCellValue('A1',"№ заявки");
$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('B1',"Дата заявки");
$aSheet->getStyle('B1')->getAlignment()->setWrapText(true);
$aSheet->getStyle('B1')->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('D1',"Форма оплаты");
$aSheet->getStyle('D1')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->getStyle('D1')->getAlignment()->setWrapText(true);



$aSheet->setCellValue('E1',"Сумма по счету (руб.)");
$aSheet->getStyle('E1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('E1')->getAlignment()->setWrapText(true);







$aSheet->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('EAEAEA');

$aSheet->getStyle('A1:E1')->applyFromArray($styleArray2);




$aSheet->setCellValue('C1',"ПЕРЕВОЗЧИК");
$aSheet->getStyle('C1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('C1')->getAlignment()->setWrapText(true);
$check=true;




if($_GET['date_start']!=""&&$_GET['date_end']!=""){	


$start_elements  = explode("/",$_GET['date_start']);
$date_start=$start_elements[2]."-".$start_elements[1]."-".$start_elements[0];
$end_elements  = explode("/",$_GET['date_end']);
$date_end=$end_elements[2]."-".$end_elements[1]."-".$end_elements[0];
 
$query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' AND `tr_nds`='1' ORDER BY `Id` DESC";
} else {$check=false;echo "Выберите период";}

if($check){
$p=2;
$result = mysql_query($query) or die(mysql_error()); 
while($row = mysql_fetch_array($result)) {

switch ($row['tr_pref']) {
case '1': $pref_tr='ООО';break;
case '2': $pref_tr='ОАО';break;
case '3': $pref_tr='ИП';break;
case '4': $pref_tr='ЗАО';break;
}	
switch ($row['tr_nds']) {
case '0': $nds_tr='без НДС';break;
case '1': $nds_tr='с НДС';break;
case '2': $nds_tr='НАЛ';break;
}






 
 
 $aSheet->setCellValue('A'.$p,$row['id']);
$aSheet->getStyle('A'.$p)->applyFromArray($center); 
$aSheet->setCellValue('B'.$p,date("d/m/Y",strtotime($row['data'])));
$aSheet->getStyle('B'.$p)->applyFromArray($center); 


$aSheet->getStyle('C'.$p)->applyFromArray($boldFont); 



$aSheet->getStyle('D'.$p)->applyFromArray($center);

$aSheet->getStyle('E'.$p)->applyFromArray($center);





 
 
 



if($difference_tr_days>0)$days=$difference_tr_days; else $days="-";	


$aSheet->setCellValue('C'.$p,$pref_tr.' '.$transporters[$row['transp']]);
$aSheet->setCellValue('D'.$p,$nds_tr);

$aSheet->setCellValue('E'.$p,$row['tr_cash']);


$tr=$transporters[$row['transp']];




$p++;



}


$aSheet->setCellValue('D'.($p+1),"Итого:");
$aSheet->getStyle('D'.($p+1))->applyFromArray($right)->applyFromArray($boldFont); 

$aSheet->setCellValue('B'.($p+1),"Обработано: ".($p-2)." заявок(ки)");
$aSheet->getStyle('B'.($p+1))->applyFromArray($boldFont); 




$aSheet->setCellValue('E'.($p+1),'=SUM(E2:E'.($p-1).')');
$aSheet->getStyle('E'.($p+1))->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->getColumnDimension('A')->setWidth(10);
$aSheet->getColumnDimension('B')->setWidth(13);
$aSheet->getColumnDimension('C')->setWidth(35);
$aSheet->getColumnDimension('D')->setWidth(15);
$aSheet->getColumnDimension('E')->setWidth(15);




//$aSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$aSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
//$aSheet->getPageSetup()->setPrintArea('A1:G25');
$aSheet->getPageSetup()->setFitToPage(true);
$aSheet->getPageSetup()->setFitToWidth(1);
$aSheet->getPageSetup()->setFitToHeight(0);

//отдаем пользователю в браузер
include("PHPExcel/Writer/Excel5.php");
$pExcel->setActiveSheetIndex(0);
$pExcel->removeSheetByIndex(1);


$objWriter = new PHPExcel_Writer_Excel5($pExcel);
header('Content-Type: application/vnd.ms-excel');

header('Content-Disposition:attachment;filename="report_tr_'.$date_start.'_'.$date_end.'.xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');




}
}
?> 

     			
