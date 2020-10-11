<?php
session_start();
//set_time_limit(0); 
if ($_GET['mode']=='report') 
{
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


if ($_GET['date_start']!=''){
$start_elements  = explode("/",$_GET['date_start']);
$date_start=$start_elements[2]."-".$start_elements[1]."-".$start_elements[0];
$end_elements  = explode("/",$_GET['date_end']);
$date_end=$end_elements[2]."-".$end_elements[1]."-".$end_elements[0];
}


$pExcel->setActiveSheetIndex(0);
$pExcel->createSheet();

$aSheet = $pExcel->getActiveSheet();
$aSheet->setTitle("Отчет по документам");
$aSheet->setCellValue('A1',"Отчет по документообороту за период с ".$_GET['date_start']." по ".$_GET['date_end']);
$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($hiFont);

$aSheet->mergeCells('A1:K1');

$aSheet->getStyle('A3:P4')->applyFromArray($styleArray);


$aSheet->freezePane('B5');


$aSheet->setCellValue('A3',"Заявка");
$aSheet->getStyle('A3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->mergeCells('A3:B3');
$aSheet->setCellValue('A4',"№");
$aSheet->getStyle('A4')->applyFromArray($center);
$aSheet->setCellValue('B4',"Дата");
$aSheet->getStyle('B4')->applyFromArray($center);
 
$aSheet->setCellValue('C3',"Дата послупления документов от Перевозчика");
$aSheet->getStyle('C3')->getAlignment()->setWrapText(true);
$aSheet->getStyle('C3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->mergeCells('C3:C4');


$aSheet->setCellValue('D3',"ПЕРЕВОЗЧИК");
$aSheet->getStyle('D3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->mergeCells('D3:G3');
$aSheet->setCellValue('D4',"Условия оплаты");
$aSheet->getStyle('D4')->getAlignment()->setWrapText(true);
$aSheet->getStyle('D4')->applyFromArray($center);
$aSheet->setCellValue('E4',"Дата оплаты (план)");
$aSheet->getStyle('E4')->getAlignment()->setWrapText(true);
$aSheet->getStyle('E4')->applyFromArray($center);
$aSheet->setCellValue('F4',"Дата оплаты (факт)");
$aSheet->getStyle('F4')->getAlignment()->setWrapText(true);
$aSheet->getStyle('F4')->applyFromArray($center);
$aSheet->setCellValue('G4',"Ставка (руб.)");
$aSheet->getStyle('G4')->getAlignment()->setWrapText(true);
$aSheet->getStyle('G4')->applyFromArray($center);


$aSheet->setCellValue('H3',"Отправка документов Заказчику");
$aSheet->getStyle('H3')->getAlignment()->setWrapText(true);
$aSheet->getStyle('H3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->mergeCells('H3:I3');
$aSheet->setCellValue('H4',"Дата");
$aSheet->getStyle('H4')->getAlignment()->setWrapText(true);
$aSheet->getStyle('H4')->applyFromArray($center);
$aSheet->setCellValue('I4',"Способ");
$aSheet->getStyle('I4')->getAlignment()->setWrapText(true);
$aSheet->getStyle('I4')->applyFromArray($center);

$aSheet->setCellValue('J3',"Дата получения документов Заказчиком");
$aSheet->getStyle('J3')->getAlignment()->setWrapText(true);
$aSheet->getStyle('J3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->mergeCells('J3:J4');

$aSheet->setCellValue('K3',"ЗАКАЗЧИК");
$aSheet->getStyle('K3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->mergeCells('K3:N3');
$aSheet->setCellValue('K4',"Условия оплаты");
$aSheet->getStyle('K4')->getAlignment()->setWrapText(true);
$aSheet->getStyle('K4')->applyFromArray($center);
$aSheet->setCellValue('L4',"Дата оплаты (план)");
$aSheet->getStyle('L4')->getAlignment()->setWrapText(true);
$aSheet->getStyle('L4')->applyFromArray($center);
$aSheet->setCellValue('M4',"Дата оплаты (факт)");
$aSheet->getStyle('M4')->getAlignment()->setWrapText(true);
$aSheet->getStyle('M4')->applyFromArray($center);
$aSheet->setCellValue('N4',"Ставка (руб.)");
$aSheet->getStyle('N4')->getAlignment()->setWrapText(true);
$aSheet->getStyle('N4')->applyFromArray($center);

$aSheet->mergeCells('O3:O4');
$aSheet->setCellValue('O3',"Заказчик");
$aSheet->getStyle('O3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->mergeCells('P3:P4');
$aSheet->setCellValue('P3',"Менеджер");
$aSheet->getStyle('P3')->applyFromArray($center)->applyFromArray($boldFont);

$query_clients = "SELECT `id`,`name` FROM `clients`";
$result_clients = mysql_query($query_clients) or die(mysql_error());
while($clients = mysql_fetch_row($result_clients)) {
$client[$clients[0]]= $clients[1];
}

$query_user = "SELECT `id`,`name` FROM `workers` WHERE `group`='3'";
$result_user = mysql_query($query_user) or die(mysql_error());
while($user = mysql_fetch_row($result_user)) {

$pieces = explode(" ", $user[1]);
$name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";

$users[$user[0]]= $name;
}

$p=5;

if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4) $query = "SELECT `id`,`data`,`cl_event`,`cl_tfpay`,`tr_event`,`tr_tfpay`,`date_plan`,`agat_number`,`transp`,`cl_pref`,`client`,`manager`,`cl_cash`,`tr_cash` FROM `orders` WHERE  DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' ORDER BY `Id` DESC"; else $query = "SELECT `id`,`data`,`cl_event`,`cl_tfpay`,`tr_event`,`tr_tfpay`,`date_plan`,`agat_number`,`transp`,`cl_pref`,`client`,`cl_cash`,`tr_cash` FROM `orders` WHERE `manager`='".mysql_escape_string($_SESSION['user_id'])."' AND DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' ORDER BY `Id` DESC"; 

$result = mysql_query($query) or die(mysql_error()); 
while($row = mysql_fetch_array($result)) {

switch ($row['cl_pref']) {
case '1': $pref_cl='ООО';break;
case '2': $pref_cl='ОАО';break;
case '3': $pref_cl='ИП';break;
case '4': $pref_cl='ЗАО';break;
}




$query_docs = "SELECT `add_date_cl_sent`,`s_type`,`date_cl_receve`,`date_tr_receve`,`date_add_bill`,`cl_bill` FROM `docs` WHERE `order`='".mysql_escape_string($row['id'])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());
$docs=mysql_fetch_array($result_docs);

switch ($docs['s_type']) {
case '0': $s_type='';break;
case '1': $s_type='Нарочным';break;
case '2': $s_type='Курьер служба';break;
case '3': $s_type='Почта России';break;
}

if($row['agat_number']!="") $order_number=$row['id'].' (A)'; else $order_number=$row['id'];
$aSheet->setCellValue('A'.$p,$order_number);
$aSheet->getStyle('A'.$p)->applyFromArray($center)->applyFromArray($boldFont);

$aSheet->setCellValue('B'.$p,date("d/m/Y",strtotime($row['data'])));
$aSheet->getStyle('B'.$p)->applyFromArray($center); 



if($docs['date_cl_receve']!="1970-01-01"&&$docs['date_cl_receve']!="0000-00-00"&&$docs['date_cl_receve']!="")
{
$date_cl_receve=date('d/m/Y', strtotime($docs['date_cl_receve']));
$cl_plan_date=date('d/m/Y', strtotime('+'.(int)$row['cl_tfpay'].' day', strtotime($docs['date_cl_receve'])));
} else {$date_cl_receve="-";$cl_plan_date="-";}

$aSheet->setCellValue('J'.$p,$date_cl_receve);
$aSheet->getStyle('J'.$p)->applyFromArray($center); 

if($docs['add_date_cl_sent']!="1970-01-01"&&$docs['add_date_cl_sent']!="0000-00-00"&&$docs['add_date_cl_sent']!="")
{
$date_cl_sent=date('d/m/Y', strtotime($docs['add_date_cl_sent']));
$aSheet->setCellValue('I'.$p,$s_type);
$aSheet->getStyle('I'.$p)->applyFromArray($center); 
	
} else {$date_cl_sent="-";}

$aSheet->setCellValue('H'.$p,$date_cl_sent);
$aSheet->getStyle('H'.$p)->applyFromArray($center); 


if($docs['date_tr_receve']!="1970-01-01"&&$docs['date_tr_receve']!="0000-00-00"&&$docs['date_tr_receve']!="")
{
$date_tr_receve=date('d/m/Y', strtotime($docs['date_tr_receve']));
$tr_plan_date=date('d/m/Y', strtotime('+'.(int)$row['tr_tfpay'].' day', strtotime($docs['date_tr_receve'])));	
} else {
$date_tr_receve="-";
if((int)$row['transp']!=2) $tr_plan_date="-"; else $tr_plan_date=""; 
}

$aSheet->setCellValue('C'.$p,$date_tr_receve);
$aSheet->getStyle('C'.$p)->applyFromArray($center); 

$cl_event="-"; 
switch ($row['cl_event']) {
case '1': $cl_event='Загрузка';break;
case '2': $cl_event='Выгрузка';break;
case '3': $cl_event='Факс.';break;
case '4': $cl_event='Ориг.';break;}

if((int)$row['cl_tfpay']!=0) $cl_event=$cl_event.'+'.(int)$row['cl_tfpay'].' дн.';

$cl_bill="";
// Кока-Кола
if((int)$row['client']==627||(int)$row['client']==630||(int)$row['client']==632||(int)$row['client']==639) {
$cl_event="Счёт+35 дн.";
$aSheet->getStyle('K'.$p)->applyFromArray($boldFont);
if($docs['cl_bill']!="")$cl_bill="(Счёт №".$docs['cl_bill']." от ".date('d/m/Y', strtotime($docs['date_add_bill'])).")"; else $cl_bill="";
if($docs['date_add_bill']!="1970-01-01"&&$docs['date_add_bill']!="0000-00-00"&&$docs['date_add_bill']!="") $cl_plan_date=date('d/m/Y', strtotime('+35 day', strtotime($docs['date_add_bill'])));
}

$aSheet->setCellValue('K'.$p,$cl_event);
$aSheet->getStyle('K'.$p)->applyFromArray($center); 

$aSheet->setCellValue('N'.$p,$row['cl_cash']);
$aSheet->getStyle('N'.$p)->applyFromArray($center); 

$aSheet->setCellValue('O'.$p, $pref_cl.' «'.$client[$row['client']].'»'.$cl_bill);

$aSheet->setCellValue('P'.$p, $users[$row['manager']]);

$aSheet->setCellValue('L'.$p,$cl_plan_date);
$aSheet->getStyle('L'.$p)->applyFromArray($center); 

$cl_pay_date="";
$query_pays_cl = "SELECT `date` FROM `pays` WHERE `order`='".mysql_escape_string($row['id'])."' AND `delete`='0' AND `appoint`='1' AND `status`='1'";
$result_pays_cl = mysql_query($query_pays_cl) or die(mysql_error());
if(mysql_num_rows($result_pays_cl)>0){
while($pay_cl = mysql_fetch_row($result_pays_cl)) $cl_pay_date.=date('d/m/Y', strtotime($pay_cl[0])).', ';
$cl_pay_date=substr($cl_pay_date, 0, -2);
} else $cl_pay_date="-";


$aSheet->setCellValue('M'.$p,$cl_pay_date);
$aSheet->getStyle('M'.$p)->applyFromArray($center); 
$aSheet->getStyle('M'.$p)->getAlignment()->setWrapText(true);

$tr_event="-"; 

switch ($row['tr_event']) {
case '1': $tr_event='Загрузка';break;
case '2': $tr_event='Выгрузка';break;
case '3': $tr_event='Факс.';break;
case '4': $tr_event='Ориг.';break;}

if((int)$row['tr_tfpay']!=0) $tr_event=$tr_event.'+'.(int)$row['tr_tfpay'].' дн.';
if((int)$row['transp']==2) {
$tr_event="собственный транспорт"; 
$aSheet->getStyle('C'.$p.':F'.$p)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C7FFA3');
}
$aSheet->setCellValue('D'.$p,$tr_event);
$aSheet->getStyle('D'.$p)->applyFromArray($center); 

$aSheet->setCellValue('G'.$p,$row['tr_cash']);
$aSheet->getStyle('G'.$p)->applyFromArray($center); 

$aSheet->setCellValue('E'.$p,$tr_plan_date);
$aSheet->getStyle('E'.$p)->applyFromArray($center); 

$tr_pay_date="";

if((int)$row['transp']!=2) {
$query_pays_tr = "SELECT `date` FROM `pays` WHERE `order`='".mysql_escape_string($row['id'])."' AND `delete`='0' AND `appoint`='2' AND `status`='1'";
$result_pays_tr = mysql_query($query_pays_tr) or die(mysql_error());
if(mysql_num_rows($result_pays_tr)>0){
while($pay_tr = mysql_fetch_row($result_pays_tr)) $tr_pay_date.=date('d/m/Y', strtotime($pay_tr[0])).', ';
$tr_pay_date=substr($tr_pay_date, 0, -2);
} else $tr_pay_date="-";
}

$aSheet->setCellValue('F'.$p,$tr_pay_date);
$aSheet->getStyle('F'.$p)->applyFromArray($center); 
$aSheet->getStyle('F'.$p)->getAlignment()->setWrapText(true);


$p++;
}


$aSheet->setCellValue('B'.($p+1),"Обработано заявок: ".($p-5));
$aSheet->getStyle('B'.($p+1))->applyFromArray($boldFont); 

$aSheet->getStyle('A5:P'.($p-1))->applyFromArray($styleArray2);

$aSheet->getColumnDimension('A')->setWidth(10);
$aSheet->getColumnDimension('B')->setWidth(12);

$aSheet->getColumnDimension('C')->setWidth(15);

$aSheet->getColumnDimension('D')->setWidth(15);
$aSheet->getColumnDimension('E')->setWidth(12);
$aSheet->getColumnDimension('F')->setWidth(12);
$aSheet->getColumnDimension('G')->setWidth(8);

$aSheet->getColumnDimension('H')->setWidth(12);
$aSheet->getColumnDimension('I')->setWidth(20);

$aSheet->getColumnDimension('J')->setWidth(15);

$aSheet->getColumnDimension('K')->setWidth(15);
$aSheet->getColumnDimension('L')->setWidth(12);
$aSheet->getColumnDimension('M')->setWidth(12);
$aSheet->getColumnDimension('N')->setWidth(8);

$aSheet->getColumnDimension('O')->setAutoSize(t​rue);
$aSheet->getColumnDimension('P')->setAutoSize(t​rue);

//$aSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$aSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$aSheet->getPageSetup()->setPrintArea('A1:N'.($p+1));
$aSheet->getPageSetup()->setFitToPage(true);
$aSheet->getPageSetup()->setFitToWidth(1);
$aSheet->getPageSetup()->setFitToHeight(0);





//отдаем пользователю в браузер
include("PHPExcel/Writer/Excel5.php");
$pExcel->setActiveSheetIndex(0);
$pExcel->removeSheetByIndex(1);


$objWriter = new PHPExcel_Writer_Excel5($pExcel);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Отчет_по_документам.xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');




}








?>