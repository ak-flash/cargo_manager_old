<?php
session_start();
set_time_limit(250); 

include "../config.php";
include_once "komissia.php";


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

if(@$_GET['mode']=='cl'){
$query_clients = "SELECT `id`,`name` FROM `clients`";
$result_clients = mysql_query($query_clients) or die(mysql_error());
while($clients = mysql_fetch_row($result_clients)) {
	$client[$clients[0]]= $clients[1];
	}
}

if(@$_GET['mode']=='tr'){
$query_tr = "SELECT `id`,`name` FROM `transporters`";
$result_tr = mysql_query($query_tr) or die(mysql_error());
while($tr = mysql_fetch_row($result_tr)) {
	$transporters[$tr[0]]= $tr[1];
	}
}

$query_user = "SELECT `id`,`name` FROM `workers`";
$result_user = mysql_query($query_user) or die(mysql_error());
while($user = mysql_fetch_row($result_user)) {

	$pieces = explode(" ", $user[1]);
	$name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";

	$users[$user[0]]= $name;
}

$query_company = "SELECT `id`,`name` FROM `company`";
$result_company = mysql_query($query_company) or die(mysql_error());
while($company = mysql_fetch_row($result_company)) {
	$companys[$company[0]]= $company[1];
}


$pExcel->setActiveSheetIndex(0);
$pExcel->createSheet();

$aSheet = $pExcel->getActiveSheet();

$aSheet->setTitle("Отчет по заявкам");

$aSheet->freezePane('A1');
$aSheet->freezePane('A2');
$aSheet->freezePane('B1');
$aSheet->freezePane('B2');

$aSheet->setCellValue('A1',"№ заявки");
$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('B1',"Дата загрузки");
$aSheet->getStyle('B1')->getAlignment()->setWrapText(true);
$aSheet->getStyle('B1')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('C1',"Город загрузки");
$aSheet->getStyle('C1')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('D1',"Город выгрузки");
$aSheet->getStyle('D1')->applyFromArray($center)->applyFromArray($boldFont); 


$aSheet->getStyle('E1')->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->getStyle('F1')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->getStyle('F1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('G1',"Форма оплаты");
$aSheet->getStyle('G1')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->getStyle('G1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('H1',"Номер счета");
$aSheet->getStyle('H1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('H1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('I1',"Сумма по счету (руб.)");
$aSheet->getStyle('I1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('I1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('J1',"Оплачена сумма (руб.)");
$aSheet->getStyle('J1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('J1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('K1',"Дата оплаты по документам");
$aSheet->getStyle('K1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('K1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('L1',"Дата оплаты плановая");
$aSheet->getStyle('L1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('L1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('M1',"Дата оплаты фактич.");
$aSheet->getStyle('M1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('M1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('N1',"Просрочка (дн.)");
$aSheet->getStyle('N1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('N1')->getAlignment()->setWrapText(true);



$aSheet->setCellValue('O1',"Комиссия (руб.)");
$aSheet->getStyle('O1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('O1')->getAlignment()->setWrapText(true);

$aSheet->getStyle('A1:O1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('EAEAEA');

$aSheet->getStyle('A1:O1')->applyFromArray($styleArray2);



if(@$_GET['mode']=='cl'){
$aSheet->setCellValue('E1',"КЛИЕНТ");
$aSheet->setCellValue('F1',"Чей клиент");
}

if(@$_GET['mode']=='tr'){
$aSheet->setCellValue('E1',"ПЕРЕВОЗЧИК");
$aSheet->setCellValue('F1',"Чей перевозчик");
}

$check=true;

if(@$_GET['mode']=='cl'){
if($_GET['date_start_cl']==""&&$_GET['date_end_cl']==""){	

if(@$_GET['mode_id']==3){if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4)$query = "SELECT * FROM `orders` WHERE `client`='".mysql_escape_string($_GET['cl_id'])."' ORDER BY `Id` DESC"; else 
if($_SESSION['user_id']==11) $query = "SELECT * FROM `orders` WHERE `client`='".mysql_escape_string($_GET['cl_id'])."' ORDER BY `Id` DESC"; else $query = "SELECT * FROM `orders` WHERE `client`='".mysql_escape_string($_GET['cl_id'])."' AND `manager`='".mysql_escape_string($_SESSION['user_id'])."' ORDER BY `Id` DESC";

}
if(@$_GET['mode_id']==1){if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4)$query = "SELECT * FROM `orders` ORDER BY `Id` DESC"; else $query = "SELECT * FROM `orders` WHERE `manager`='".mysql_escape_string($_SESSION['user_id'])."' ORDER BY `Id` DESC";}

} else {	
$start_elements  = explode("/",$_GET['date_start_cl']);
$date_start=$start_elements[2]."-".$start_elements[1]."-".$start_elements[0];
$end_elements  = explode("/",$_GET['date_end_cl']);
$date_end=$end_elements[2]."-".$end_elements[1]."-".$end_elements[0];
 
if(@$_GET['mode_id']==3){if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4)$query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' AND `client`='".mysql_escape_string($_GET['cl_id'])."' ORDER BY `Id` DESC"; else 
if($_SESSION['user_id']==11) $query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' AND `client`='".mysql_escape_string($_GET['cl_id'])."' ORDER BY `Id` DESC"; else $query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' AND `client`='".mysql_escape_string($_GET['cl_id'])."' AND `manager`='".mysql_escape_string($_SESSION['user_id'])."' ORDER BY `Id` DESC";


}
if(@$_GET['mode_id']==1){if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4)$query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' ORDER BY `Id` DESC"; else $query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' AND `manager`='".mysql_escape_string($_SESSION['user_id'])."' ORDER BY `Id` DESC";}
}
}

if(@$_GET['mode']=='tr'){
if($_GET['date_start_tr']==""&&$_GET['date_end_tr']==""){	

if(@$_GET['mode_id']==3){if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4)$query = "SELECT * FROM `orders` WHERE `transp`='".mysql_escape_string($_GET['tr_id'])."' ORDER BY `Id` DESC"; else $query = "SELECT * FROM `orders` WHERE `transp`='".mysql_escape_string($_GET['tr_id'])."' AND `manager`='".mysql_escape_string($_SESSION['user_id'])."' ORDER BY `Id` DESC";}
if(@$_GET['mode_id']==1){if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4)$query = "SELECT * FROM `orders` ORDER BY `Id` DESC"; else $query = "SELECT * FROM `orders` WHERE `manager`='".mysql_escape_string($_SESSION['user_id'])."' ORDER BY `Id` DESC";}

} else {	
$start_elements  = explode("/",$_GET['date_start_tr']);
$date_start=$start_elements[2]."-".$start_elements[1]."-".$start_elements[0];
$end_elements  = explode("/",$_GET['date_end_tr']);
$date_end=$end_elements[2]."-".$end_elements[1]."-".$end_elements[0];
 
if(@$_GET['mode_id']==3){if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4)$query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' AND `transp`='".mysql_escape_string($_GET['tr_id'])."' ORDER BY `Id` DESC"; else $query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' AND `transp`='".mysql_escape_string($_GET['tr_id'])."' AND `manager`='".mysql_escape_string($_SESSION['user_id'])."' ORDER BY `Id` DESC";}
if(@$_GET['mode_id']==1){if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4)$query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' ORDER BY `Id` DESC"; else $query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' AND `manager`='".mysql_escape_string($_SESSION['user_id'])."' ORDER BY `Id` DESC";}
}
}

if($check) {
$p=2;
$result = mysql_query($query) or die(mysql_error()); 
while($row = mysql_fetch_array($result)) 
{


if(@$_GET['mode']=='cl'){
$pref_cl='';
switch ($row['cl_pref']) {
	case '1': $pref_cl='ООО';break;
	case '2': $pref_cl='ОАО';break;
	case '3': $pref_cl='ИП';break;
	case '4': $pref_cl='ЗАО';break;
	case '5': $pref_cont='';break;
}

switch ($row['cl_nds']) {
	case '0': $nds_cl='без НДС';break;
	case '1': $nds_cl='с НДС';break;
	case '2': $nds_cl='НАЛ';break;
}
}

if(@$_GET['mode']=='tr'){
$pref_tr='';
if($row['transp']==2||$row['transp']=='-1') $row['tr_pref']=1;
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
}


$query_docs = "SELECT `date_cl_receve`,`date_tr_receve`,`cl_bill`,`tr_bill` FROM `docs` WHERE `order`='".mysql_escape_string($row['id'])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());
$docs=mysql_fetch_row($result_docs);


if(@$_GET['mode']=='cl'){
$cl_event_date="-";
$difference_cl_days="-";
$cl_plan_date="-";
if($docs[0]!="1970-01-01"&&$docs[0]!="0000-00-00"&&$docs[0]!="")
{
	$cl_event_date=date('d/m/Y', strtotime('+'.(int)$row['cl_tfpay'].' day', strtotime($docs[0])));
	$elements  = explode("/",$cl_event_date);
	$current_date = mktime (0,0,0,date("m") ,date("d"),date("Y")); //дата сегодня
	$old_date = mktime (0,0,0,$elements[1],$elements[0],$elements[2]); 
	$difference = ($current_date - $old_date);
	$difference_cl_days = ($difference / 86400); //разница в днях

} else {
	
	$cl_event_date="-";
	
	if($row['date_plan']!="1970-01-01"&&$row['date_plan']!="0000-00-00")
	{
		$cl_plan_date=date("d/m/Y",strtotime($row['date_plan']));
		$elements  = explode("/",$cl_plan_date);
		$current_date = mktime (0,0,0,date("m") ,date("d"),date("Y")); //дата сегодня
		$old_date = mktime (0,0,0,$elements[1],$elements[0],$elements[2]); 
		$difference = ($current_date - $old_date);
		$difference_cl_days = ($difference / 86400); //разница в днях
	} else $cl_plan_date="-";

}

if($docs[2]!="0"&&$docs[2]!="") $cl_bill=$docs[2]; else $cl_bill="-";
}


if(@$_GET['mode']=='tr'){
$tr_event_date="-";
$difference_tr_days="-";

if($docs[1]!="1970-01-01"&&$docs[1]!="0000-00-00"&&$docs[1]!=""){$tr_event_date=date('d/m/Y', strtotime('+'.(int)$row['tr_tfpay'].' day', strtotime($docs[1])));
$elements  = explode("/",$tr_event_date);
$current_date = mktime (0,0,0,date("m") ,date("d"),date("Y")); //дата сегодня
$old_date = mktime (0,0,0,$elements[1],$elements[0],$elements[2]); 
$difference = ($current_date - $old_date);
$difference_tr_days = ($difference / 86400); //разница в днях
} else $tr_event_date="-";
if($docs[3]!="0"&&$docs[3]!="") $tr_bill=$docs[3]; else $tr_bill="-";
}



$cl_pay=0;
$date_pay_cl='';
$query_pays = "SELECT `cash`,`date` FROM `pays` WHERE `order`='".mysql_escape_string($row['id'])."' AND `delete`='0' AND `appoint`='1' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
	$cl_pay=(int)$pay[0]+(int)$cl_pay;
	$date_pay_cl=date('d/m/Y', strtotime($pay[1]));
}


if($cl_pay!="0") $cl_p=($cl_pay/100); else $cl_p="-";

$tr_pay=0;
$date_pay_tr='';
$query_pays = "SELECT `cash`,`date` FROM `pays` WHERE `order`='".mysql_escape_string($row['id'])."' AND `delete`='0' AND `appoint`='2' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
	$tr_pay=(int)$pay[0]+(int)$tr_pay;
	$date_pay_tr=date('d/m/Y', strtotime($pay[1]));
}


if($tr_pay!="0") $tr_p=($tr_pay/100); else $tr_p="-";


// Запрос выборки данных
$query_adress = "SELECT `id`,`city` FROM `adress`";
$result_adress = mysql_query($query_adress) or die(mysql_error());

while($adress = mysql_fetch_row($result_adress)) {
	$adresses[$adress[0]]= $adress[1];
}

$str_in = explode('&',$row['in_adress']);
$str_adr_in = (int)sizeof($str_in)-2;
$res_in = $str_in[$str_adr_in];

$str_out = explode('&',$row['out_adress']);
$res_out = $str_out[0];

if($cl_pay==$row['cl_cash']*100&&$tr_pay==$row['tr_cash']*100){$aSheet->getStyle('A'.$p.':O'.$p)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C7FFA3');$difference_cl_days=0;}


// Рассчёт комиссии из файла komission.php
$komissia=0;

$komissia=komissia($row['cl_cash'],$row['cl_minus'],$row['cl_plus'],$row['cl_nds'],$row['tr_cash'],$row['tr_minus'],$row['tr_plus'],$row['tr_nds']);

 
 
 $aSheet->setCellValue('A'.$p,$row['id']);
$aSheet->getStyle('A'.$p)->applyFromArray($center); 
$aSheet->setCellValue('B'.$p,date("d/m/Y",strtotime($row['date_in1'])));
$aSheet->getStyle('B'.$p)->applyFromArray($center); 
$aSheet->setCellValue('C'.$p,$adresses[$res_in]);
$aSheet->getStyle('C'.$p)->applyFromArray($center);
//$aSheet->getStyle('C'.$p)->getAlignment()->setWrapText(true); 
$aSheet->setCellValue('D'.$p,$adresses[$res_out]);
$aSheet->getStyle('D'.$p)->applyFromArray($center); 
//$aSheet->getStyle('D'.$p)->getAlignment()->setWrapText(true); 




$aSheet->getStyle('G'.$p)->applyFromArray($center); 

$aSheet->getStyle('H'.$p)->applyFromArray($center);

$aSheet->getStyle('I'.$p)->applyFromArray($center);

$aSheet->getStyle('J'.$p)->applyFromArray($center);
$aSheet->getStyle('K'.$p)->applyFromArray($center);
$aSheet->getStyle('L'.$p)->applyFromArray($center);
$aSheet->getStyle('M'.$p)->applyFromArray($center);



if($row['tr_manager']==$row['manager']) {
	$aSheet->setCellValue('O'.$p,$komissia);
} else {

if($_SESSION["group"]==3) $komissia = $komissia/2; 

	$aSheet->setCellValue('O'.$p,$komissia);
	$aSheet->getStyle('O'.$p)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FF765E');
}


$aSheet->getStyle('O'.$p)->applyFromArray($center);
 
 
 
if(@$_GET['mode']=='cl'){
	
if($difference_cl_days>0)$days=$difference_cl_days; else $days="-";	
$aSheet->setCellValue('E'.$p,$pref_cl.' '.$client[$row['client']].' ('.$companys[$row['cl_cont']].')');
$aSheet->setCellValue('G'.$p,$nds_cl);
$aSheet->setCellValue('H'.$p,$cl_bill);
$aSheet->setCellValue('I'.$p,$row['cl_cash']);
$aSheet->setCellValue('J'.$p,$cl_p);
$aSheet->setCellValue('K'.$p,$cl_event_date);
$aSheet->setCellValue('L'.$p,$cl_plan_date);
$aSheet->setCellValue('F'.$p,$users[$row['manager']]);
$cl=$client[$row['client']];
$aSheet->setCellValue('N'.$p,$days);
$aSheet->setCellValue('M'.$p,$date_pay_cl);


}

if(@$_GET['mode']=='tr'){
if($difference_tr_days>0)$days=$difference_tr_days; else $days="-";	


$aSheet->setCellValue('E'.$p,$pref_tr.' '.$transporters[$row['transp']].' ('.$companys[$row['tr_cont']].')');
$aSheet->setCellValue('G'.$p,$nds_tr);
$aSheet->setCellValue('H'.$p,$tr_bill);
$aSheet->setCellValue('I'.$p,$row['tr_cash']);
$aSheet->setCellValue('J'.$p,$tr_p);
$aSheet->setCellValue('K'.$p,$tr_event_date);
$aSheet->setCellValue('F'.$p,$users[$row['tr_manager']]);
$aSheet->setCellValue('M'.$p,$date_pay_tr);
$aSheet->setCellValue('N'.$p,$days);
$tr=$transporters[$row['transp']];
}



$p++;



}


$aSheet->setCellValue('N'.($p+1),"Итого:");
$aSheet->getStyle('N'.($p+1))->applyFromArray($right)->applyFromArray($boldFont); 

$aSheet->setCellValue('B'.($p+1),"Обработано: ".($p-2)." заявок(ки)");
$aSheet->getStyle('B'.($p+1))->applyFromArray($boldFont); 




$aSheet->setCellValue('O'.($p+1),'=SUM(O2:O'.($p-1).')');
$aSheet->getStyle('O'.($p+1))->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->getColumnDimension('A')->setWidth(10);
$aSheet->getColumnDimension('B')->setWidth(13);
$aSheet->getColumnDimension('C')->setWidth(25);
$aSheet->getColumnDimension('D')->setWidth(25);
$aSheet->getColumnDimension('E')->setWidth(35);
$aSheet->getColumnDimension('F')->setWidth(20);
$aSheet->getColumnDimension('G')->setWidth(10);
$aSheet->getColumnDimension('H')->setWidth(13);
$aSheet->getColumnDimension('I')->setWidth(10);
$aSheet->getColumnDimension('J')->setWidth(13);
$aSheet->getColumnDimension('K')->setWidth(13);
$aSheet->getColumnDimension('L')->setWidth(13);
$aSheet->getColumnDimension('M')->setWidth(13);
$aSheet->getColumnDimension('N')->setWidth(10);
$aSheet->getColumnDimension('O')->setWidth(10);
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



header('Content-Type: application/vnd.ms-excel');

if(@$_GET['mode']=='cl'){

	switch ((int)$_GET['mode_id']) {
		case '1': $report_file_name= '_'.$pref_cl.'_'.str_replace(' ','_',$cl).'.xls"' ; break;
		case '2': $report_file_name= '_'.$_GET['date_start'].'_'.$_GET['date_end'].'.xls"' ; break;
		case '3': $report_file_name= '_'.$pref_cl.'_'.str_replace(' ','_',$cl).'.xls"' ; break;
}

header('Content-Disposition: attachment;filename="report_cl'.$report_file_name);
}

if(@$_GET['mode']=='tr'){
if(@$_GET['mode_id']==3){header('Content-Disposition: attachment;filename="report_tr_'.$pref_tr.'_'.str_replace(' ','_',$tr).'.xls"');}if(@$_GET['mode_id']==2){header('Content-Disposition: attachment;filename="report_tr_'.$_GET['date_start'].'_'.$_GET['date_end'].'.xls"');}
if(@$_GET['mode_id']==1){header('Content-Disposition: attachment;filename="report_tr.xls"');}
}

header('Cache-Control: max-age=0');

$objWriter = new PHPExcel_Writer_Excel5($pExcel);
$objWriter->save('php://output');




}

?> 

     			
