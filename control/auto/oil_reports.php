<?php
if(@$_GET['mode']=='trip'){
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


$query_adress = "SELECT `id`,`city` FROM `adress`";
$result_adress = mysql_query($query_adress) or die(mysql_error());

while($adress = mysql_fetch_row($result_adress)) {
$adresses[$adress[0]]= $adress[1];
}


$pExcel->setActiveSheetIndex(0);
$pExcel->createSheet();

$aSheet = $pExcel->getActiveSheet();

$aSheet->setTitle("Отчет по рейсам");
$aSheet->mergeCells('A1:I1');



$start_elements  = explode("/",$_GET['date_start']);
$date_start=$start_elements[2]."-".$start_elements[1]."-".$start_elements[0];
$end_elements  = explode("/",$_GET['date_end']);
$date_end=$end_elements[2]."-".$end_elements[1]."-".$end_elements[0];


$query_car = "SELECT `name`,`number`,`lpk` FROM `vtl_auto` WHERE `id`='".(int)$_GET['car_number']."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car = mysql_fetch_row($result_car);
	
$aSheet->setCellValue('A1',"Отчет по автомобилю - ".$car[0].' с гос.номером  - '.$car[1].' за период с '.$_GET['date_start'].' по '.$_GET['date_end']);
$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('A3','№');
$aSheet->getStyle('A3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('B3','Заявка');
$aSheet->getStyle('B3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('C3','Дата');
$aSheet->getStyle('C3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('D3','Загрузка');
$aSheet->getStyle('D3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('E3','Выгрузка');
$aSheet->getStyle('E3')->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('F3','Км');
$aSheet->getStyle('F3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('F3')->getAlignment()->setWrapText(true);


$aSheet->setCellValue('G3','Всего км');
$aSheet->getStyle('G3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('G3')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('H3','Остаток при выезде');
$aSheet->getStyle('H3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('H3')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('I3','По карте');
$aSheet->getStyle('I3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('I3')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('J3','За НАЛ.');
$aSheet->getStyle('J3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('J3')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('K3','Остаток при возвр.');
$aSheet->getStyle('K3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('K3')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('L3','Израсх.');
$aSheet->getStyle('L3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('L3')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('M3','Норма');
$aSheet->getStyle('M3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('M3')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('N3','Перерасход');
$aSheet->getStyle('N3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('N3')->getAlignment()->setWrapText(true);

$aSheet->getStyle('A3:N3')->applyFromArray($styleArray);
$aSheet->getStyle('A3:N3')->applyFromArray($styleArray2);
//$aSheet->getStyle('A3:I3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFE97C');




mb_internal_encoding("UTF-8");
$query_s = "SELECT `id`,`order` FROM `vtl_trip` WHERE `tr_auto` LIKE '".(int)$_GET['car_number']."&%' AND `delete`='0' AND DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."'";
$result_s = mysql_query($query_s) or die(mysql_error());
if (mysql_num_rows($result_s)>0){
while($row_s = mysql_fetch_array($result_s)){
$str_ord = explode('&',$row_s['order']);
$str_ord_list =(int)sizeof($str_ord)-2;
for($m=0; $m<=$str_ord_list; $m++){
$mass[]=$str_ord[($str_ord_list-$m)];
}
}



$query = "SELECT `id`,`in_adress`,`out_adress`,`date_in1`,`date_out1`,`date_out2`,`tr_auto`,`transp`,`cl_nds`,`km` FROM `orders` WHERE `id` IN (".implode(',' , $mass).") AND `delete`='0' ORDER BY Field(`id`,".implode(',' , $mass).")";
$f=0;
$result = mysql_query($query) or die(mysql_error()); 


while($row = mysql_fetch_array($result)) {

$aSheet->setCellValue('F'.($f+4),$row['km']);
$aSheet->getStyle('F'.($f+4))->applyFromArray($center);
	
$str_auto = explode('&',$row['tr_auto']);

$str_in = explode('&',$row['in_adress']);
$str_adr_in = (int)sizeof($str_in)-2;
$res_in[$f] = $str_in[$str_adr_in];

$str_out = explode('&',$row['out_adress']);
$res_out[$f] = $str_out[0];

$date_in[$f]=$row['date_in1'];

if($row['date_out2']!='1970-01-01'&&$row['date_out2']!='0000-00-00')$date_out[$f]=$row['date_out2']; else $date_out[$f]=$row['date_out1'];

$ord_id[$f]=$row['id'];

$query_search = "SELECT `id`,`order`,`km`,`days`,`start_petrol`,`end_petrol` FROM `vtl_trip` WHERE `order` LIKE '%".$row['id']."&%' AND `delete`='0'";
$result_search = mysql_query($query_search) or die(mysql_error());
if (mysql_num_rows($result_search)>0){
$row_search = mysql_fetch_array($result_search);
$str_order = explode('&',$row_search['order']);
$str_order_list =(int)sizeof($str_order)-2;




for($m=0; $m<=$str_order_list; $m++){
if($str_order[($str_order_list-$m)]==$row['id']){$ord_check[$f]=1;$id_mass[$f]=$row_search['id'];$km_ord[$f]=$row_search['km'];$days_ord[$f]=$row_search['days'];$petrol_start[$f]=$row_search['start_petrol'];$petrol_end[$f]=$row_search['end_petrol'];}

}

}

$f++;
}


$query = "SELECT `km`,`days`,`start_petrol`,`end_petrol` FROM `vtl_trip` WHERE `delete`='0' AND `id` IN (".implode(',' , $id_mass).") ORDER BY `id` DESC";
$result = mysql_query($query) or die(mysql_error());
while($row = mysql_fetch_array($result)) {}

$query_report = "SELECT `way`,`cash`,`l`,`trip` FROM `drivers_report` WHERE `trip` IN (".implode(',' , $id_mass).") AND `delete`='0'";
$result_report = mysql_query($query_report) or die(mysql_error());
while($report = mysql_fetch_row($result_report)) {
switch ($report[0]) {
case '9': $petrol_nal[$report[3]]=(int)$petrol_nal[$report[3]]+(int)$report[2];break;
case '10': $petrol_beznal[$report[3]]=(int)$petrol_beznal[$report[3]]+(int)$report[2];break;

} 
}

$check=true;

if($check){
$p=4;

$ch=1;
$trip_ch=0;
while (($p-4)<$f) {


if($ord_check[($p-4)]!=1){$aSheet->getStyle('A'.$p.':N'.$p)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E2E2E2');
$aSheet->getStyle('A'.$p.':N'.$p)->applyFromArray($styleArray2);$petrol_sum='';}


	
$aSheet->setCellValue('B'.$p,$ord_id[($p-4)]);
$aSheet->getStyle('B'.$p)->applyFromArray($center); 
$aSheet->setCellValue('C'.$p,date("d.m",strtotime($date_in[($p-4)])).' - '.date("d.m",strtotime($date_out[($p-4)])));
$aSheet->getStyle('C'.$p)->applyFromArray($center); 
$aSheet->setCellValue('D'.$p,$adresses[$res_in[($p-4)]]);
$aSheet->getStyle('D'.$p)->applyFromArray($center); 
$aSheet->setCellValue('E'.$p,$adresses[$res_out[($p-4)]]);
$aSheet->getStyle('E'.$p)->applyFromArray($center); 

if($trip_ch==$id_mass[($p-4)]&&$trip_ch!=0){
$aSheet->mergeCells('A'.($p-1).':A'.$p);
$aSheet->mergeCells('I'.($p-1).':I'.$p);
$aSheet->mergeCells('K'.($p-1).':K'.$p);
$aSheet->mergeCells('J'.($p-1).':J'.$p);
$aSheet->mergeCells('L'.($p-1).':L'.$p);
$aSheet->mergeCells('M'.($p-1).':M'.$p);
$aSheet->mergeCells('G'.($p-1).':G'.$p);
$aSheet->mergeCells('H'.($p-1).':H'.$p);
$aSheet->mergeCells('N'.($p-1).':N'.$p);



} else {$aSheet->setCellValue('A'.$p,$id_mass[($p-4)]);
$aSheet->getStyle('A'.$p)->applyFromArray($center);


$aSheet->setCellValue('G'.$p,$km_ord[($p-4)]);
$aSheet->getStyle('G'.$p)->applyFromArray($center);
$aSheet->setCellValue('H'.$p,$petrol_start[($p-4)]);
$aSheet->getStyle('H'.$p)->applyFromArray($center);
$aSheet->setCellValue('I'.$p,$petrol_beznal[$id_mass[($p-4)]]/100);
$aSheet->getStyle('I'.$p)->applyFromArray($center);
$aSheet->setCellValue('J'.$p,$petrol_nal[$id_mass[($p-4)]]/100);
$aSheet->getStyle('J'.$p)->applyFromArray($center);
$aSheet->setCellValue('K'.$p,$petrol_end[($p-4)]);
$aSheet->getStyle('K'.$p)->applyFromArray($center);
$aSheet->setCellValue('L'.$p,'=H'.$p.'-K'.$p.'+I'.$p.'+J'.$p);
$aSheet->getStyle('L'.$p)->applyFromArray($center);
$aSheet->setCellValue('M'.$p,'=ROUND(G'.$p.'*'.$car[2].'/100,2)');
$aSheet->getStyle('M'.$p)->applyFromArray($center);
$aSheet->setCellValue('N'.$p,'=L'.$p.'-M'.$p);
$aSheet->getStyle('N'.$p)->applyFromArray($center);
$ch++;}

$aSheet->getStyle('A'.$p.':N'.$p)->applyFromArray($styleArray3);

$trip_ch=$id_mass[($p-4)];
$p++;
}

$aSheet->setCellValue('B'.$p,($p-4));
$aSheet->getStyle('B'.$p)->applyFromArray($center); 


$aSheet->setCellValue('G'.$p,'=SUM(G4:G'.($p-1).')');
$aSheet->getStyle('G'.$p)->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('L'.$p,'=SUM(L4:L'.($p-1).')');
$aSheet->getStyle('L'.$p)->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('M'.$p,'=SUM(M4:M'.($p-1).')');
$aSheet->getStyle('M'.$p)->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('I'.$p,'=SUM(I4:I'.($p-1).')');
$aSheet->getStyle('I'.$p)->applyFromArray($center)->applyFromArray($boldFont);

$aSheet->setCellValue('J'.$p,'=SUM(J4:J'.($p-1).')');
$aSheet->getStyle('J'.$p)->applyFromArray($center)->applyFromArray($boldFont);

$aSheet->setCellValue('N'.$p,'=SUM(N4:N'.($p-1).')');
$aSheet->getStyle('N'.$p)->applyFromArray($center)->applyFromArray($boldFont);

$aSheet->getStyle('N3:N'.$p)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E2E2E2');

$aSheet->getColumnDimension('A')->setWidth(7);
$aSheet->getColumnDimension('B')->setWidth(8);
$aSheet->getColumnDimension('C')->setWidth(15);
$aSheet->getColumnDimension('D')->setWidth(20);
$aSheet->getColumnDimension('E')->setWidth(20);
$aSheet->getColumnDimension('F')->setWidth(8);
$aSheet->getColumnDimension('G')->setWidth(10);

























$aSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
//$aSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$aSheet->getPageSetup()->setPrintArea('A1:P'.($p+5));
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

}} else echo '<br><br><br><br><div align="center"><b><font size="5">По данной машине не обнаружено ни одного рейса!</font></b></div>';
}
?> 