<?php
session_start();
//set_time_limit(0); 
if ($_GET['mode']=='report') 
{

	include "../config.php";
	include_once "komissia.php";

$query_user = "SELECT `id`,`name` FROM `workers`";
$result_user = mysql_query($query_user) or die(mysql_error());



while($user = mysql_fetch_row($result_user)) {
$pieces = explode(" ", $user[1]);
$managers=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
$users[$user[0]]= $managers;
}


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
$aSheet->setTitle("Отчет по комиссиям");
$aSheet->setCellValue('A1',"Отчет по комиссиям за ".$_GET['date_komiss']." год");
$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($hiFont);

$aSheet->mergeCells('A1:K1');

$aSheet->getStyle('A3:N3')->applyFromArray($styleArray);


$aSheet->freezePane('A4');

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




for ($x=1; $x<=12; $x++) {
$aSheet->setCellValue($number[$x].'3',$q[$x]);
$aSheet->getStyle($number[$x].'3')->applyFromArray($center)->applyFromArray($boldFont);
}

$aSheet->getStyle('A3:N3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C7FFA3');



if ((int)$_GET['type']==1){

$aSheet->setCellValue('N3',"Менеджер");
$aSheet->getStyle('N3')->applyFromArray($center)->applyFromArray($boldFont);
	
$aSheet->setCellValue('A3',"Клиент");
$aSheet->getStyle('A3')->applyFromArray($center)->applyFromArray($hFont);





$p=4;
$orders=0;

//cl_group
$cl_group="";
$query_group = "SELECT * FROM `cl_group`";
$result_group = mysql_query($query_group) or die(mysql_error());
while($group = mysql_fetch_array($result_group)) {
$cl_group.=$group['group_cl'].',';

for ($x=1; $x<=12; $x++) {
$komiss_sum_group=0;
$query_clients = "SELECT `id`,`name` FROM `clients` WHERE `id` IN (".mysql_escape_string($group['group_cl']).") ORDER BY `Id` DESC";
$result_clients = mysql_query($query_clients) or die(mysql_error());
while($clients = mysql_fetch_row($result_clients)) {


$query ="SELECT `cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`cl_pref` FROM `orders` WHERE MONTH(`data`)='".$x."' and YEAR(`data`)='".(int)$_GET['date_komiss']."' and `client`='".$clients[0]."'";	
$result = mysql_query($query) or die(mysql_error()); 
while($row = mysql_fetch_array($result)) {
$orders++;

$komiss_sum_group=$komiss_sum_group+komissia($row['cl_cash'],$row['cl_minus'],$row['cl_plus'],$row['cl_nds'],$row['tr_cash'],$row['tr_minus'],$row['tr_plus'],$row['tr_nds']);

}


}
$aSheet->setCellValue($number[$x].$p,$komiss_sum_group);
$aSheet->getStyle($number[$x].$p)->applyFromArray($center);
}



$aSheet->setCellValue('A'.$p,$group['group_name']);
$aSheet->getStyle('A'.$p)->getAlignment()->setWrapText(true);
$aSheet->getStyle('A'.$p)->applyFromArray($right)->applyFromArray($boldFont);



$p++;

}

//end

$date_start=(int)$_GET['date_komiss'].'-01-01';
$date_end=(int)$_GET['date_komiss'].'-12-31';

$cl_group=substr($cl_group, 0, -1);

$query_cl ="SELECT `client` FROM `orders` WHERE `client` NOT IN (".mysql_escape_string($cl_group).") AND DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."'";	
$result_cl = mysql_query($query_cl) or die(mysql_error()); 
while($row_cl = mysql_fetch_array($result_cl)) {
$id_mass[]=$row_cl['client'];
}
$id_mass=array_unique($id_mass);

$query_clients = "SELECT `id`,`name`,`cl_manager` FROM `clients` WHERE `id` IN (".implode(',' , $id_mass).") ORDER BY `name` ASC";
$result_clients = mysql_query($query_clients) or die(mysql_error());
while($clients = mysql_fetch_row($result_clients)) {
for ($x=1; $x<=12; $x++) {
$komiss_sum=0;
$query ="SELECT `cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds` FROM `orders` WHERE MONTH(`data`)='".$x."' and YEAR(`data`)='".(int)$_GET['date_komiss']."' and `client`='".$clients[0]."'";	
$result = mysql_query($query) or die(mysql_error()); 
while($row = mysql_fetch_array($result)) {
$orders++;



$komiss_sum=$komiss_sum+komissia($row['cl_cash'],$row['cl_minus'],$row['cl_plus'],$row['cl_nds'],$row['tr_cash'],$row['tr_minus'],$row['tr_plus'],$row['tr_nds']);


}
$aSheet->setCellValue($number[$x].$p,$komiss_sum);
$aSheet->getStyle($number[$x].$p)->applyFromArray($center);
}

$aSheet->setCellValue('A'.$p,$clients[1]);
$aSheet->getStyle('A'.$p)->getAlignment()->setWrapText(true);
$aSheet->getStyle('A'.$p)->applyFromArray($right)->applyFromArray($boldFont);

$aSheet->setCellValue('N'.$p,$users[$clients[2]]);

$p++;
}


$aSheet->setCellValue('A'.($p+1),"Обработано клиентов: ".($p-5));
$aSheet->getStyle('A'.($p+1))->applyFromArray($boldFont); 
$aSheet->getColumnDimension('A')->setWidth(50);
$aSheet->getColumnDimension('N')->setWidth(20);
}


if ((int)$_GET['type']==2){


	
$aSheet->setCellValue('A3',"Менеджер");
$aSheet->getStyle('A3')->applyFromArray($center)->applyFromArray($boldFont);

$p=4;
$orders=0;

$query_workers = "SELECT `id`,`name` FROM `workers` WHERE `group`='3' and `delete`='0' ORDER BY `Id` ASC";
$result_workers = mysql_query($query_workers) or die(mysql_error());
while($workers = mysql_fetch_row($result_workers)) {

for ($x=1; $x<=12; $x++) {
$komiss_sum=0;
$query ="SELECT `tr_manager`,`manager`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`cl_pref` FROM `orders` WHERE MONTH(`data`)='".$x."' and YEAR(`data`)='".(int)$_GET['date_komiss']."' and `manager`='".$workers[0]."'";	
$result = mysql_query($query) or die(mysql_error()); 
while($row = mysql_fetch_array($result)) {
$orders++;

if($row['tr_manager']!=$row['manager']) $komiss_sum=$komiss_sum+komissia($row['cl_cash'],$row['cl_minus'],$row['cl_plus'],$row['cl_nds'],$row['tr_cash'],$row['tr_minus'],$row['tr_plus'],$row['tr_nds'])/2; else $komiss_sum=$komiss_sum+komissia($row['cl_cash'],$row['cl_minus'],$row['cl_plus'],$row['cl_nds'],$row['tr_cash'],$row['tr_minus'],$row['tr_plus'],$row['tr_nds']);
}

$query ="SELECT `tr_manager`,`manager`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`cl_pref` FROM `orders` WHERE MONTH(`data`)='".$x."' and YEAR(`data`)='".(int)$_GET['date_komiss']."' and `tr_manager`='".$workers[0]."' and `manager`!='".$workers[0]."'";	
$result = mysql_query($query) or die(mysql_error()); 
while($row = mysql_fetch_array($result)) {
$orders++;

// Рассчёт комиссии из файла komission.php
$komiss_sum=$komiss_sum+komissia($row['cl_cash'],$row['cl_minus'],$row['cl_plus'],$row['cl_nds'],$row['tr_cash'],$row['tr_minus'],$row['tr_plus'],$row['tr_nds'])/2; 
}


$aSheet->setCellValue($number[$x].$p,$komiss_sum);
$aSheet->getStyle($number[$x].$p)->applyFromArray($center);
}



$pieces = explode(" ", $workers[1]);
$name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";

$aSheet->setCellValue('A'.$p,$name);
$aSheet->getStyle('A'.$p)->getAlignment()->setWrapText(true);
$aSheet->getStyle('A'.$p)->applyFromArray($right)->applyFromArray($boldFont);

$p++;
}


$aSheet->getColumnDimension('A')->setWidth(25);
}

$aSheet->setCellValue('A'.$p,'ИТОГО');
$aSheet->getStyle('A'.$p)->applyFromArray($right)->applyFromArray($boldFont); 

$aSheet->setCellValue('B'.$p,'=SUM(B4:B'.($p-1).')');
$aSheet->getStyle('B'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)'); 
$aSheet->setCellValue('C'.$p,'=SUM(C4:C'.($p-1).')');
$aSheet->getStyle('C'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)'); 
$aSheet->setCellValue('D'.$p,'=SUM(D4:D'.($p-1).')');
$aSheet->getStyle('D'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)'); 
$aSheet->setCellValue('E'.$p,'=SUM(E4:E'.($p-1).')');
$aSheet->getStyle('E'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)'); 
$aSheet->setCellValue('F'.$p,'=SUM(F4:F'.($p-1).')');
$aSheet->getStyle('F'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)'); 
$aSheet->setCellValue('G'.$p,'=SUM(G4:G'.($p-1).')');
$aSheet->getStyle('G'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)'); 
$aSheet->setCellValue('H'.$p,'=SUM(H4:H'.($p-1).')');
$aSheet->getStyle('H'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)'); 
$aSheet->setCellValue('I'.$p,'=SUM(I4:I'.($p-1).')');
$aSheet->getStyle('I'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)'); 
$aSheet->setCellValue('J'.$p,'=SUM(J4:J'.($p-1).')');
$aSheet->getStyle('J'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)'); 
$aSheet->setCellValue('K'.$p,'=SUM(K4:K'.($p-1).')');
$aSheet->getStyle('K'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)'); 
$aSheet->setCellValue('L'.$p,'=SUM(L4:L'.($p-1).')');
$aSheet->getStyle('L'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)'); 
$aSheet->setCellValue('M'.$p,'=SUM(M4:M'.($p-1).')');
$aSheet->getStyle('M'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)'); 





$aSheet->getColumnDimension('B')->setWidth(12);
$aSheet->getColumnDimension('C')->setWidth(12);
$aSheet->getColumnDimension('D')->setWidth(12);
$aSheet->getColumnDimension('E')->setWidth(12);
$aSheet->getColumnDimension('F')->setWidth(12);
$aSheet->getColumnDimension('G')->setWidth(12);
$aSheet->getColumnDimension('H')->setWidth(12);
$aSheet->getColumnDimension('I')->setWidth(12);
$aSheet->getColumnDimension('J')->setWidth(12);
$aSheet->getColumnDimension('K')->setWidth(12);
$aSheet->getColumnDimension('L')->setWidth(12);
$aSheet->getColumnDimension('M')->setWidth(12);

$aSheet->setCellValue('B'.($p+2),"Обработано заявок: ".$orders);
$aSheet->getStyle('B'.($p+2))->applyFromArray($boldFont); 

$aSheet->getStyle('A4:M'.($p-1))->applyFromArray($styleArray2);



//$aSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$aSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$aSheet->getPageSetup()->setPrintArea('A1:M'.($p+1));
$aSheet->getPageSetup()->setFitToPage(true);
$aSheet->getPageSetup()->setFitToWidth(1);
$aSheet->getPageSetup()->setFitToHeight(0);





//отдаем пользователю в браузер
include("PHPExcel/Writer/Excel5.php");
$pExcel->setActiveSheetIndex(0);
$pExcel->removeSheetByIndex(1);


$objWriter = new PHPExcel_Writer_Excel5($pExcel);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Отчет_по_комиссия.xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');




}








?>