<?php
if(@$_GET['mode']=='auto_report'){
include "../../config.php";

set_include_path(get_include_path() . PATH_SEPARATOR .
'../PhpExcel/Classes/');
//подключаем и создаем класс PHPExcel
include_once '../PHPExcel.php';
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

$aSheet->setTitle("Отчет по автомобилю");
$aSheet->mergeCells('A1:K1');



$start_elements  = explode("/",$_GET['date_start']);
$date_start=$start_elements[2]."-".$start_elements[1]."-".$start_elements[0];
$date_year=$start_elements[2];

$date_start_d=mktime (0,0,0,$start_elements[1],$start_elements[0],$start_elements[2]);


$end_elements  = explode("/",$_GET['date_end']);
$date_end=$end_elements[2]."-".$end_elements[1]."-".$end_elements[0];
$date_end_d=mktime (0,0,0,$end_elements[1],$end_elements[0],$end_elements[2]);

$query_car = "SELECT `name`,`number`,`dop_car` FROM `vtl_auto` WHERE `id`='".(int)$_GET['car_number']."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car = mysql_fetch_row($result_car);



	
$aSheet->setCellValue('A1',"Отчет по автомобилю - ".$car[0].' с гос.номером - '.$car[1].' за период с '.$_GET['date_start'].' по '.$_GET['date_end']);
$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($boldFont); 



$query_repair = "SELECT `area`,`cash` FROM `vtl_repair` WHERE `auto`='".(int)$_GET['car_number']."' AND `delete`='0' AND DATE(`date`) BETWEEN '".$date_start."' AND '".$date_end."'";
$result_repair = mysql_query($query_repair) or die(mysql_error());


while($repair = mysql_fetch_row($result_repair)) {
switch ($repair[0]) {
case '6': $cash[6]=(int)$cash[6]+(int)$repair[1];break;
case '7': $cash[7]=(int)$cash[7]+(int)$repair[1];break;
case '8': $cash[8]=(int)$cash[8]+(int)$repair[1];break;
case '11': $cash[11]=(int)$cash[11]+(int)$repair[1];break;


} 	


}


$query_pay = "SELECT `appoint`,`cash` FROM `pays` WHERE `way`='2' AND `category`='2' AND `delete`='0' AND `appoint` IN (25,28,33,34,35,37) AND `status`='1' AND DATE(`date`) BETWEEN '".$date_start."' AND '".$date_end."' AND `car_id`='".(int)$_GET['car_number']."'";
$result_pay = mysql_query($query_pay) or die(mysql_error());
while($pay = mysql_fetch_row($result_pay)) {
switch ($pay[0]) {
case '25': $cash_pay[6]=(int)$cash_pay[6]+(int)$pay[1];break;
case '28': $cash_pay[13]=(int)$cash_pay[13]+(int)$pay[1];break;
case '33': $cash_pay[8]=(int)$cash_pay[8]+(int)$pay[1];break;
case '34': $cash_pay[7]=(int)$cash_pay[7]+(int)$pay[1];break;
case '35': $cash_pay[11]=(int)$cash_pay[11]+(int)$pay[1];break;
case '37': $cash_pay[12]=(int)$cash_pay[12]+(int)$pay[1];break;
} 

}




$query_pay = "SELECT `appoint`,`cash` FROM `pays` WHERE `way`='2' AND `category`='2' AND `delete`='0' AND `appoint`='32' AND `status`='1' AND DATE(`date`) BETWEEN '".$date_year."-01-01' AND '".$date_year."-12-31' AND `car_id`='".(int)$_GET['car_number']."'";
$result_pay = mysql_query($query_pay) or die(mysql_error());
$pay = mysql_fetch_row($result_pay);
$cash_pay[14]=(int)$pay[1]/12;


switch ((int)$_GET['car_number']) {
case '3': $cash_pay[15]=100*19500/12;break;
case '1': $cash_pay[15]=100*31395/12;break;
case '9': $cash_pay[15]=100*21255/12;break;
case '7': $cash_pay[15]=100*26650/12;break;
case '15': $cash_pay[15]=100*16315/12;break;
}

//полуприцеп
$query_car_dop = "SELECT `name`,`number`,`id` FROM `vtl_auto` WHERE `id`='".(int)$car[2]."'";
$result_car_dop = mysql_query($query_car_dop) or die(mysql_error());
$sss=0;
while($car_dop = mysql_fetch_row($result_car_dop)){
$aSheet->setCellValue('C'.($p+4+$sss),'П/прицеп'.$car_dop[0].' - '.$car_dop[1]);
$aSheet->getStyle('C'.($p+4+$sss))->applyFromArray($right)->applyFromArray($boldFont);
$sss++;
}
 

$query_pay = "SELECT `appoint`,`cash` FROM `pays` WHERE `way`='2' AND `category`='2' AND `delete`='0' AND `appoint` IN (25,28,33,34,35,37) AND `status`='1' AND DATE(`date`) BETWEEN '".$date_start."' AND '".$date_end."' AND `car_id`='".(int)$car[2]."'";
$result_pay = mysql_query($query_pay) or die(mysql_error());
while($pay = mysql_fetch_row($result_pay)) {
switch ($pay[0]) {
case '25': $cash_pay_dop[6]=(int)$cash_pay_dop[6]+(int)$pay[1];break;
case '28': $cash_pay_dop[13]=(int)$cash_pay_dop[13]+(int)$pay[1];break;
case '33': $cash_pay_dop[8]=(int)$cash_pay_dop[8]+(int)$pay[1];break;
case '34': $cash_pay_dop[7]=(int)$cash_pay_dop[7]+(int)$pay[1];break;
case '35': $cash_pay_dop[11]=(int)$cash_pay_dop[11]+(int)$pay[1];break;
case '37': $cash_pay_dop[12]=(int)$cash_pay_dop[12]+(int)$pay[1];break;
} 

}

$query_pay = "SELECT `appoint`,`cash` FROM `pays` WHERE `way`='2' AND `category`='2' AND `delete`='0' AND `appoint`='32' AND `status`='1' AND DATE(`date`) BETWEEN '".$date_year."-01-01' AND '".$date_year."-12-31' AND `car_id`='".(int)$car[2]."'";
$result_pay = mysql_query($query_pay) or die(mysql_error());
while($pay = mysql_fetch_row($result_pay)){
$cash_pay_dop[14]=$cash_pay_dop[14]+((int)$pay[1]/12);
}

$query_repair = "SELECT `area`,`cash`,`auto` FROM `vtl_repair` WHERE `auto`='".(int)$car[2]."' AND `delete`='0' AND DATE(`date`) BETWEEN '".$date_start."' AND '".$date_end."'";
$result_repair = mysql_query($query_repair) or die(mysql_error());

while($repair = mysql_fetch_row($result_repair)) {
switch ($repair[0]) {
case '6': $cash_dop[6]=(int)$cash_dop[6]+(int)$repair[1];break;
case '7': $cash_dop[7]=(int)$cash_dop[7]+(int)$repair[1];break;
case '8': $cash_dop[8]=(int)$cash_dop[8]+(int)$repair[1];break;
case '11': $cash_dop[11]=(int)$cash_dop[11]+(int)$repair[1];break;
} 	
}
//end




$way[1]='Суточные';
$way[2]='Стоянка';
$way[3]='Штрафы ГАИ';
$way[4]='Платная дорога';
$way[5]='Охрана';
$way[6]='Автозапчасти';
$way[7]='Шиномонтаж';
$way[8]='Услуги ремонта';
$way[9]='ГСМ (НАЛ)';
$way[10]='ГСМ (БезНАЛ)';
$way[11]='Инструменты';
$way[12]='Госпошлина';
$way[13]='Лизинг';
$way[14]='Страховка';
$way[15]='Налог';

$number[]="";
$number[]="E";
$number[]="F";	
$number[]="G";
$number[]="H";
$number[]="I";
$number[]="J";
$number[]="K";
$number[]="L";
$number[]="M";
$number[]="N";
$number[]="O";
$number[]="P";
$number[]="Q";
$number[]="R";
$number[]="S";


$query_drv = "SELECT `id`,`name`,`z_day`,`z_city`,`z_repair`,`z_km`,`zarplata` FROM `workers` WHERE `id`='".(int)$drv[0]."'";
$result_drv = mysql_query($query_drv) or die(mysql_error());
$drv = mysql_fetch_row($result_drv);

//$days = floor(($date_end_d - $date_start_d)/(3600*24));

for($s=1; $s<=15; $s++){
$aSheet->setCellValue($number[$s].($p+2),$way[$s]);

if($s==16)$aSheet->setCellValue($number[$s].($p+3),'=F'.$p.'*3'); else 
//if($s==17)$aSheet->setCellValue($number[$s].($p+3),$drv[6]*floor($days/30));
 if($s==18)$aSheet->setCellValue($number[$s].($p+3),$drv[6]); else $aSheet->setCellValue($number[$s].($p+3),round((($cash[$s]+$cash_pay[$s])/100),2));
 $aSheet->getStyle('B'.($p+3).':S'.($p+3))->applyFromArray($styleArray);
$aSheet->getStyle('B'.($p+3).':S'.($p+3))->applyFromArray($styleArray2);


$aSheet->setCellValue($number[$s].($p+4),round((($cash_dop[$s]+$cash_pay_dop[$s])/100),2));
$aSheet->setCellValue('D'.($p+4),'=SUM(E'.($p+4).':V'.($p+4).')');
$aSheet->getStyle('D'.($p+4))->applyFromArray($center)->applyFromArray($boldFont);


$aSheet->setCellValue('D'.($p+4),'=SUM(E'.($p+4).':V'.($p+4).')');
$aSheet->getStyle('D'.($p+4))->applyFromArray($center)->applyFromArray($boldFont);
 $aSheet->getStyle('D'.($p+4))->applyFromArray($styleArray);
$aSheet->getStyle('D'.($p+4))->applyFromArray($styleArray2);

$aSheet->getStyle('B'.($p+4).':S'.($p+4))->applyFromArray($styleArray);
$aSheet->getStyle('B'.($p+4).':S'.($p+4))->applyFromArray($styleArray2);
$aSheet->getStyle($number[$s].($p+4))->applyFromArray($center);

$aSheet->getStyle($number[$s].($p+2))->getAlignment()->setWrapText(true);
$aSheet->getStyle($number[$s].($p+2))->applyFromArray($center);
$aSheet->getStyle($number[$s].($p+3))->applyFromArray($center);}


$aSheet->mergeCells('B'.($p+3).':C'.($p+3));
$aSheet->setCellValue('B'.($p+3),'Тягач');
$aSheet->getStyle('B'.($p+3))->applyFromArray($right)->applyFromArray($boldFont); 
$aSheet->setCellValue('D'.($p+3),'=SUM(E'.($p+3).':S'.($p+3).')');
$aSheet->getStyle('D'.($p+3))->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('C'.($p+5),'ИТОГО');
$aSheet->getStyle('C'.($p+5))->applyFromArray($right)->applyFromArray($boldFont); 
$aSheet->setCellValue('D'.($p+5),'=D'.($p+3).'+D'.($p+4));
$aSheet->getStyle('D'.($p+5))->applyFromArray($center)->applyFromArray($boldFont); 













$aSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
//$aSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$aSheet->getPageSetup()->setPrintArea('A1:V'.($p+6+$k));
$aSheet->getPageSetup()->setFitToPage(true);
$aSheet->getPageSetup()->setFitToWidth(1);
$aSheet->getPageSetup()->setFitToHeight(0);

//отдаем пользователю в браузер
include("../PHPExcel/Writer/Excel5.php");
$pExcel->setActiveSheetIndex(0);
$pExcel->removeSheetByIndex(1);


$objWriter = new PHPExcel_Writer_Excel5($pExcel);
header('Content-Type: application/vnd.ms-excel');


header('Content-Disposition: attachment;filename="car_'.$car[0].'_'.$date_start."_".$date_end.'.xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');
}
?>