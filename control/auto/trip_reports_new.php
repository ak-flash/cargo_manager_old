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






$pExcel->setActiveSheetIndex(0);
$pExcel->createSheet();

$aSheet = $pExcel->getActiveSheet();

$aSheet->setTitle("Отчет по рейсам");
$aSheet->mergeCells('A1:I1');



$start_elements  = explode("/",$_GET['date_start']);
$date_start=$start_elements[2]."-".$start_elements[1]."-".$start_elements[0];
$date_year=$start_elements[2];
$month_number=(int)$start_elements[1];
$date_start_d=mktime (0,0,0,$start_elements[1],$start_elements[0],$start_elements[2]);


$end_elements  = explode("/",$_GET['date_end']);
$date_end=$end_elements[2]."-".$end_elements[1]."-".$end_elements[0];
$date_end_d=mktime (0,0,0,$end_elements[1],$end_elements[0],$end_elements[2]);


$date1 = new DateTime($date_start);
$date2 = new DateTime($date_end);
$interval = $date1->diff($date2);

$aSheet->setCellValue('J1',($interval->m+1));



$query_car = "SELECT `name`,`number`,`dop_car` FROM `vtl_auto` WHERE `id`='".(int)$_GET['car_number']."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car = mysql_fetch_row($result_car);

$aSheet->mergeCells('K1:L1');
$aSheet->setCellValue('K1','месяц (шт.)');
	
$aSheet->setCellValue('A1',"Отчет по автомобилю - ".$car[0].' с гос.номером - '.$car[1].' за период с '.$_GET['date_start'].' по '.$_GET['date_end']);
$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('A3','№');
$aSheet->getStyle('A3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('B3','Заявка');
$aSheet->getStyle('B3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('C3','Клиент');
$aSheet->getStyle('C3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('D3','Ставка кл.');
$aSheet->getStyle('D3')->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->setCellValue('E3','Форма');
$aSheet->getStyle('E3')->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('F3','Километраж из заявки');
$aSheet->getStyle('F3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('F3')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('G3','Километраж');
$aSheet->getStyle('G3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('G3')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('H3','Расход топлива (л)');
$aSheet->getStyle('H3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('H3')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('I3','Расход на 100 км (л)');
$aSheet->getStyle('I3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('I3')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('J3','Кол-во дней командировки');
$aSheet->getStyle('J3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('J3')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('K3','Стоимость 1 км (руб.) по заявке');
$aSheet->getStyle('K3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('K3')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('L3','Стоимость 1 км (руб.)');
$aSheet->getStyle('L3')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('L3')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('M3','П/прицеп');
$aSheet->getStyle('M3')->applyFromArray($center)->applyFromArray($boldFont);

$aSheet->setCellValue('N3','П/лист');
$aSheet->getStyle('N3')->applyFromArray($center)->applyFromArray($boldFont);

$aSheet->getStyle('A3:N3')->applyFromArray($styleArray);
$aSheet->getStyle('A3:N3')->applyFromArray($styleArray2);
//$aSheet->getStyle('A3:I3')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('FFE97C');

$query_clients = "SELECT `id`,`name` FROM `clients`";
$result_clients = mysql_query($query_clients) or die(mysql_error());
while($clients = mysql_fetch_row($result_clients)) {
$client[$clients[0]]= $clients[1];
}

//полуприцеп
$query_car_name = "SELECT `id`,`name`,`number` FROM `vtl_auto` WHERE `type`='2' OR `type`='4'";
$result_car_name = mysql_query($query_car_name) or die(mysql_error());
$dop_name=array();
while($car_dop_t= mysql_fetch_row($result_car_name)){
$dop_name[$car_dop_t[0]]=$car_dop_t[1];
}

// получение номеров всех заявок
$query_check = "SELECT `id` FROM `orders` WHERE `tr_auto` LIKE '".(int)$_GET['car_number']."&%' AND `delete`='0' AND DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."'";
$result_check = mysql_query($query_check) or die(mysql_error()); 
while($row_check = mysql_fetch_array($result_check)) {
$id_mass_order_check[]=$row_check[0];
}

mb_internal_encoding("UTF-8");


$query_parts = array();
foreach ($id_mass_order_check as $val) {
    $query_parts[] = "'%".mysql_real_escape_string($val)."%'";
}

$string = implode(' OR `order` LIKE ', $query_parts);

$query_s = "SELECT `id`,`order`,`tr_auto`,`p_list` FROM `vtl_trip` WHERE `tr_auto` LIKE '".(int)$_GET['car_number']."&%' AND `order` LIKE {$string}  AND `delete`='0'";
$result_s = mysql_query($query_s) or die(mysql_error());

if (mysql_num_rows($result_s)>0){
	
while($row_s = mysql_fetch_array($result_s)){
$str_ord = explode('&',$row_s['order']);
$str_p_list = explode('&',$row_s['p_list']);

$str_ord_list =(int)sizeof($str_ord)-2;
for($m=0; $m<=$str_ord_list; $m++){
$mass[]=$str_ord[($str_ord_list-$m)];
$str_p_list_ord[$str_ord[($str_ord_list-$m)]]=$str_p_list[($str_ord_list-$m)];
}
}

//$ord_check = array_diff($id_mass_order_check,$mass);
//$lost_ord=implode(', ' , $ord_check);
if(array_intersect($id_mass_order_check,$mass)){

//var_dump($id_mass_order_check);


$query_ord = "SELECT `id`,`client`,`cl_pref`,`cl_cash`,`tr_auto`,`transp`,`cl_nds`,`km` FROM `orders` WHERE `id` IN (".implode(',' , $mass).") AND `delete`='0' ORDER BY Field(`id`,".implode(',' , $mass).")";
$f=0;
$result_ord = mysql_query($query_ord) or die(mysql_error()); 
while($row_ord = mysql_fetch_array($result_ord)) {
unset($str_auto);

$str_auto=array();	
$str_auto = explode('&',$row_ord['tr_auto']);


switch ($row_ord['cl_pref']) {
case '1': $pref_cl='ООО';break;
case '2': $pref_cl='ОАО';break;
case '3': $pref_cl='ИП';break;
case '4': $pref_cl='ЗАО';break;
case '5': $pref_cl='';break;}	
	
switch ($row_ord['cl_nds']) {
case '0': $nds_cl='без НДС';break;
case '1': $nds_cl='с НДС';break;
case '2': $nds_cl='НАЛ';break;
}
$ord_id[$f]=$row_ord['id'];

//echo $row_ord['id'].'<br>';

$cl[$f]=$pref_cl.' «'.$client[$row_ord['client']].'»';
$cl_cash[$f]=$row_ord['cl_cash'];
$nds[$f]=$nds_cl;
$str_dop[$f]=$str_auto[1];
$str_car_id[$f]=$str_auto[0];

$str_ord_km[$f]=$row_ord['km'];


$query_search = "SELECT `id`,`order`,`km`,`days`,`start_petrol`,`end_petrol`,`cash_day` FROM `vtl_trip` WHERE `order` LIKE '%".$row_ord['id']."&%' AND `delete`='0'";
$result_search = mysql_query($query_search) or die(mysql_error());
if (mysql_num_rows($result_search)>0){
$row_search = mysql_fetch_array($result_search);
$str_order = explode('&',$row_search['order']);
$str_order_list =(int)sizeof($str_order)-2;




for($m=0; $m<=$str_order_list; $m++){
if($str_order[($str_order_list-$m)]==$row_ord['id']){$ord_check[$f]=1;$id_mass[$f]=$row_search['id'];$km_ord[$f]=$row_search['km'];$days_ord[$f]=$row_search['days'];$days_cash[$f]=$row_search['cash_day'];$petrol_start[$f]=$row_search['start_petrol'];$petrol_end[$f]=$row_search['end_petrol'];}

}

}

$f++;
} 


//$query = "SELECT `km`,`days`,`start_petrol`,`end_petrol` FROM `vtl_trip` WHERE `delete`='0' AND `id` IN (".implode(',' , $id_mass).") ORDER BY `id` DESC";
//$result = mysql_query($query) or die(mysql_error());
//while($row = mysql_fetch_array($result)) {}

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


if($ord_check[($p-4)]!=1){$aSheet->getStyle('B'.$p.':K'.$p)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E2E2E2');
$aSheet->getStyle('B'.$p.':L'.$p)->applyFromArray($styleArray2);$petrol_sum='';} else $petrol_sum=$petrol_start[($p-4)]-$petrol_end[($p-4)]+$petrol_nal[$id_mass[($p-4)]]/100+$petrol_beznal[$id_mass[($p-4)]]/100;


	
$aSheet->setCellValue('B'.$p,$ord_id[($p-4)]);

$aSheet->setCellValue('C'.$p,$cl[($p-4)]);
$aSheet->setCellValue('D'.$p,$cl_cash[($p-4)]);
$aSheet->getStyle('D'.$p)->applyFromArray($center)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)');; 
$aSheet->setCellValue('E'.$p,$nds[($p-4)]);
$aSheet->getStyle('E'.$p)->applyFromArray($center); 
$aSheet->setCellValue('F'.$p,$str_ord_km[($p-4)]);
$aSheet->getStyle('F'.$p)->applyFromArray($center)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)'); 
$aSheet->setCellValue('M'.$p,$dop_name[$str_dop[($p-4)]]);
$aSheet->getStyle('M'.$p)->applyFromArray($center);


$aSheet->setCellValue('K'.$p,'=ROUND(D'.$p.'/F'.$p.',2)');
$aSheet->getStyle('K'.$p)->applyFromArray($center);


if($str_p_list_ord[$ord_id[($p-4)]]==1) $p_list="+"; else $p_list="";
$aSheet->setCellValue('N'.$p,$p_list);
$aSheet->getStyle('N'.$p)->applyFromArray($center);


if($str_car_id[($p-4)]!=(int)$_GET['car_number'])$aSheet->getStyle('B'.$p.':E'.$p)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('E2E2E2');



if($trip_ch==$id_mass[($p-4)]&&$trip_ch!=0){
$aSheet->mergeCells('A'.($p-1).':A'.$p);
$aSheet->mergeCells('G'.($p-1).':G'.$p);
$aSheet->mergeCells('J'.($p-1).':J'.$p);
$aSheet->mergeCells('H'.($p-1).':H'.$p);
$aSheet->mergeCells('I'.($p-1).':I'.$p);
//$aSheet->setCellValue('J'.($p-1),'=ROUND((D'.$p.'+D'.($p-1).')/F'.($p-1).',2)');
$aSheet->mergeCells('L'.($p-1).':L'.$p);
$num=$id_mass[($p-4)];
} else {$aSheet->setCellValue('A'.$p,$id_mass[($p-4)]);
$aSheet->getStyle('A'.$p)->applyFromArray($center);
$aSheet->setCellValue('G'.$p,$km_ord[($p-4)]);
$aSheet->getStyle('G'.$p)->applyFromArray($center)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)');
$aSheet->setCellValue('J'.$p,$days_ord[($p-4)]);
$aSheet->getStyle('J'.$p)->applyFromArray($center);
$aSheet->setCellValue('H'.$p,$petrol_sum);
$aSheet->getStyle('H'.$p)->applyFromArray($center);
$aSheet->setCellValue('I'.$p,'=ROUND(H'.$p.'*100/G'.$p.',2)');
$aSheet->getStyle('I'.$p)->applyFromArray($center);
$aSheet->setCellValue('L'.$p,'=ROUND(D'.$p.'/G'.$p.',2)');
$aSheet->getStyle('L'.$p)->applyFromArray($center);



$ch++;}


if($num!=$id_mass[($p-4)]){$num_del=$p;$num_d='D'.$p;}

if($num==$id_mass[($p-4)]){
$num_d=$num_d.'+D'.$p;
$aSheet->setCellValue('L'.$num_del,'=ROUND(('.$num_d.')/G'.$num_del.',2)');
}




//if($lost_ord!=''){
//$aSheet->mergeCells('B2:V2');
//$aSheet->setCellValue('B2','Для заявок: '.$lost_ord.' отсутствуют рейсы');
//}



$aSheet->getStyle('A'.$p.':N'.$p)->applyFromArray($styleArray3);

$trip_ch=$id_mass[($p-4)];
$p++;
}

$aSheet->setCellValue('B'.$p,($p-4));
$aSheet->getStyle('B'.$p)->applyFromArray($center); 

$aSheet->setCellValue('C'.$p,'ИТОГО');
$aSheet->getStyle('C'.$p)->applyFromArray($right)->applyFromArray($boldFont); 
$aSheet->setCellValue('D'.$p,'=SUM(D4:D'.($p-1).')');
$aSheet->getStyle('D'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)');

$aSheet->setCellValue('F'.$p,'=SUM(F4:F'.($p-1).')');
$aSheet->getStyle('F'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)'); 

$aSheet->setCellValue('G'.$p,'=SUM(G4:G'.($p-1).')');
$aSheet->getStyle('G'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)'); 

$aSheet->setCellValue('J'.$p,'=SUM(J4:J'.($p-1).')');
$aSheet->getStyle('J'.$p)->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('K'.$p,'=ROUND(D'.$p.'/F'.$p.',2)');
$aSheet->getStyle('K'.$p)->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->setCellValue('H'.$p,'=SUM(H4:H'.($p-1).')');
$aSheet->getStyle('H'.$p)->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)'); 

$aSheet->setCellValue('I'.$p,'=ROUND(H'.$p.'*100/G'.$p.',2)');
$aSheet->getStyle('I'.$p)->applyFromArray($center)->applyFromArray($boldFont);

$aSheet->setCellValue('L'.$p,'=ROUND(D'.$p.'/G'.$p.',2)');
$aSheet->getStyle('L'.$p)->applyFromArray($center)->applyFromArray($boldFont); 

$aSheet->getStyle('B'.$p.':L'.$p)->applyFromArray($styleArray);
$aSheet->getStyle('B'.$p.':L'.$p)->applyFromArray($styleArray2);

$aSheet->mergeCells('A'.($p+2).':D'.($p+2));
$aSheet->setCellValue('A'.($p+2),'Расходная часть в рублях');
$aSheet->getStyle('A'.($p+2))->applyFromArray($center)->applyFromArray($boldFont); 
$aSheet->getStyle('A'.($p+2))->getAlignment()->setWrapText(true);
$aSheet->getStyle('A'.($p+2).':T'.($p+2))->applyFromArray($styleArray);
$aSheet->getStyle('A'.($p+2).':T'.($p+2))->applyFromArray($styleArray2);


$query_report = "SELECT `way`,`cash`,`l` FROM `drivers_report` WHERE `trip` IN (".implode(',' , $id_mass).") AND `delete`='0'";
$result_report = mysql_query($query_report) or die(mysql_error());


while($report = mysql_fetch_row($result_report)) {
switch ($report[0]) {
case '1': $cash[1]=(int)$cash[1]+(int)$report[1];break;
case '2': $cash[2]=(int)$cash[2]+(int)$report[1];break;
case '3': $cash[3]=(int)$cash[3]+(int)$report[1];break;
case '4': $cash[4]=(int)$cash[4]+(int)$report[1];break;
case '5': $cash[5]=(int)$cash[5]+(int)$report[1];break;
case '6': $cash[6]=(int)$cash[6]+(int)$report[1];break;
case '7': $cash[7]=(int)$cash[7]+(int)$report[1];break;
case '8': $cash[8]=(int)$cash[8]+(int)$report[1];break;
case '9': $cash[9]=(int)$cash[9]+(int)$report[1];break;
case '10': $cash[10]=(int)$cash[10]+(int)$report[1];break;
case '11': $cash[11]=(int)$cash[11]+(int)$report[1];break;

} 	


}

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


$query_car_dop = "SELECT `name`,`number`,`id` FROM `vtl_auto` WHERE `id` IN (".implode(',' , array_unique($str_dop)).")";
$result_car_dop = mysql_query($query_car_dop) or die(mysql_error());
$sss=0;
while($car_dop = mysql_fetch_row($result_car_dop)){
$aSheet->setCellValue('C'.($p+4+$sss),'Отчет по полу/прицепу '.$car_dop[0].' - '.$car_dop[1]);
$aSheet->getStyle('C'.($p+4+$sss))->applyFromArray($right)->applyFromArray($boldFont);


 

$query_pay = "SELECT `appoint`,`cash` FROM `pays` WHERE `way`='2' AND `category`='2' AND `delete`='0' AND `appoint` IN (25,28,33,34,35,37) AND `status`='1' AND DATE(`date`) BETWEEN '".$date_start."' AND '".$date_end."' AND `car_id`='".(int)$car_dop[2]."'";
$result_pay = mysql_query($query_pay) or die(mysql_error());
$cash_pay_dop="";
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

$query_pay = "SELECT `appoint`,`cash` FROM `pays` WHERE `way`='2' AND `category`='2' AND `delete`='0' AND `appoint`='32' AND `status`='1' AND DATE(`date`) BETWEEN '".$date_year."-01-01' AND '".$date_year."-12-31' AND `car_id`='".(int)$car_dop[2]."'";
$result_pay = mysql_query($query_pay) or die(mysql_error());
$cash_pay_dop="";
while($pay = mysql_fetch_row($result_pay)){
$cash_pay_dop[14]=$cash_pay_dop[14]+((int)$pay[1]/12);
}

$query_repair = "SELECT `area`,`cash`,`auto` FROM `vtl_repair` WHERE `auto`='".(int)$car_dop[2]."' AND `delete`='0' AND DATE(`date`) BETWEEN '".$date_start."' AND '".$date_end."'";
$result_repair = mysql_query($query_repair) or die(mysql_error());
$cash_dop="";
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
$way[12]='Связь';
$way[13]='Лизинг';
$way[14]='Страховка ОСАГО';
$way[15]='Налог';
$way[16]='Мотивация водителей межгород';
$way[17]='Мотивация водителей прочее';
$way[18]='Оклад';
$number[0]="";
$number[1]="E";
$number[2]="F";	
$number[3]="G";
$number[4]="H";
$number[6]="I";
$number[7]="J";
$number[8]="K";
$number[9]="L";
$number[10]="M";
$number[12]="N";
$number[13]="O";
$number[14]="P";
$number[15]="Q";
$number[16]="R";
$number[17]="S";
$number[18]="T";

$query_drv = "SELECT `id`,`name`,`z_day`,`z_city`,`z_repair`,`z_km`,`zarplata` FROM `workers` WHERE `id`='".(int)$drv[0]."'";
$result_drv = mysql_query($query_drv) or die(mysql_error());
$drv = mysql_fetch_row($result_drv);

//$days = floor(($date_end_d - $date_start_d)/(3600*24));
$liz[1][1]='41834';
$liz[1][2]='41370';
$liz[1][3]='40903';
$liz[1][4]='39974';
$liz[1][5]='78553';

$liz[7][1]='47008';
$liz[7][2]='46436';
$liz[7][3]='45911';
$liz[7][4]='45387';
$liz[7][5]='44861';
$liz[7][6]='44336';
$liz[7][7]='43812';
$liz[7][7]='1720';

$liz[9][1]='36710';
$liz[9][2]='36264';
$liz[9][3]='35854';
$liz[9][4]='35443';
$liz[9][5]='35035';
$liz[9][6]='34625';
$liz[9][7]='34214';
$liz[9][8]='1344';

$liz[15][1]='51626';
$liz[15][2]='51626';
$liz[15][3]='51626';
$liz[15][4]='51626';
$liz[15][5]='51626';
$liz[15][6]='51626';
$liz[15][7]='51626';
$liz[15][8]='51626';
$liz[15][9]='51626';
$liz[15][10]='49185';

$liz[2][1]='13354';
$liz[2][2]='13194';
$liz[2][3]='13047,5';
$liz[2][4]='12900';
$liz[2][5]='12754,5';
$liz[2][6]='12607';
$liz[2][7]='12460,5';
$liz[2][8]='12314';

$liz[14][1]='13354';
$liz[14][2]='13194';
$liz[14][3]='13047,5';
$liz[14][4]='12900';
$liz[14][5]='12754,5';
$liz[14][6]='12607';
$liz[14][7]='12460,5';
$liz[14][8]='12314';



for($s=1; $s<=18; $s++){
if($s!=5&&$s!=11){
$aSheet->setCellValue($number[$s].($p+2),$way[$s]);
if($s==1) {
$aSheet->getStyle($number[$s].($p+2))->applyFromArray($center)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)');
$aSheet->getStyle($number[$s].($p+3))->applyFromArray($center)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)');

$aSheet->mergeCells('E'.($p+3).':E'.($p+4));
$aSheet->setCellValue($number[$s].($p+3),'=G'.$p.'*1'); 
}
else {
if($s==12){

$aSheet->mergeCells('N'.($p+3).':N'.($p+4));
$aSheet->setCellValue($number[$s].($p+3),'=J1*3000');
} else 
	if($s==14){
$aSheet->setCellValue($number[$s].($p+4),'=J1*1000');
$aSheet->setCellValue($number[$s].($p+3),'=J1*6000');

	
} else 
if($s==13){
$car_liz=0;
$car_dop_liz=0;
for($mn=$month_number; $mn<=($month_number+($interval->m+1)-1); $mn++){	
$car_liz=(int)$car_liz+(int)$liz[(int)$_GET['car_number']][$mn];
$car_dop_liz=(int)$car_dop_liz+(int)$liz[(int)$car_dop[2]][$mn];
}
$aSheet->setCellValue($number[$s].($p+3),$car_liz);
$aSheet->setCellValue($number[$s].($p+4),$car_dop_liz);

	
} else 

if($s==15){

$aSheet->mergeCells('Q'.($p+3).':Q'.($p+4));
$aSheet->setCellValue($number[$s].($p+3),'=J1*2095');
} else 	
	
if($s==16){

$aSheet->mergeCells('R'.($p+3).':R'.($p+4));
$aSheet->setCellValue($number[$s].($p+3),'=G'.$p.'*3.5');
} else 

if($s==17){

$aSheet->mergeCells('S'.($p+3).':S'.($p+4));
$aSheet->setCellValue($number[$s].($p+3),'0');
} else 

 if($s==18){$aSheet->setCellValue($number[$s].($p+3),'=J1*10000');$aSheet->mergeCells('T'.($p+3).':T'.($p+4));} else $aSheet->setCellValue($number[$s].($p+3),round((($cash[$s]+$cash_pay[$s])/100),2));
 $aSheet->getStyle('B'.($p+3).':T'.($p+3))->applyFromArray($styleArray);
$aSheet->getStyle('B'.($p+3).':T'.($p+3))->applyFromArray($styleArray2);

if($s!=14&&$s!=13){$aSheet->setCellValue($number[$s].($p+4+$sss),round((($cash_dop[$s]+$cash_pay_dop[$s])/100),2));}
$aSheet->setCellValue('D'.($p+4+$sss),'=SUM(E'.($p+4+$sss).':T'.($p+4+$sss).')');
$aSheet->getStyle('D'.($p+4+$sss))->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)');

$aSheet->setCellValue('D'.($p+4+$sss),'=SUM(E'.($p+4+$sss).':T'.($p+4+$sss).')');
$aSheet->getStyle('D'.($p+4+$sss))->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)');
 $aSheet->getStyle('D'.($p+4+$sss))->applyFromArray($styleArray);
$aSheet->getStyle('D'.($p+4+$sss))->applyFromArray($styleArray2);

$aSheet->getStyle('B'.($p+4+$sss).':T'.($p+4+$sss))->applyFromArray($styleArray);
$aSheet->getStyle('B'.($p+4+$sss).':T'.($p+4+$sss))->applyFromArray($styleArray2);
$aSheet->getStyle($number[$s].($p+4+$sss))->applyFromArray($center);

$aSheet->getStyle($number[$s].($p+2))->getAlignment()->setWrapText(true);
$aSheet->getStyle($number[$s].($p+2))->applyFromArray($center)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)');
$aSheet->getStyle($number[$s].($p+3))->applyFromArray($center)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)');}

}
}
$sss++;
}

$aSheet->mergeCells('B'.($p+3).':C'.($p+3));
$aSheet->setCellValue('B'.($p+3),'Отчет по тягачу');
$aSheet->getStyle('B'.($p+3))->applyFromArray($right)->applyFromArray($boldFont); 
$aSheet->setCellValue('D'.($p+3),'=SUM(E'.($p+3).':T'.($p+3).')');
$aSheet->getStyle('D'.($p+3))->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)'); 

$aSheet->setCellValue('C'.($p+5+$sss),'ИТОГО');
$aSheet->getStyle('C'.($p+5+$sss))->applyFromArray($right)->applyFromArray($boldFont); 
$aSheet->setCellValue('D'.($p+5+$sss),'=D'.($p+3).'+D'.($p+3+$sss));
$aSheet->getStyle('D'.($p+5+$sss))->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)'); 

$aSheet->setCellValue('C'.($p+7+$sss),'Прибыль');
$aSheet->getStyle('C'.($p+7+$sss))->applyFromArray($right)->applyFromArray($boldFont);

$aSheet->setCellValue('D'.($p+7+$sss),'=D'.$p.'-D'.($p+5+$sss));

$aSheet->getStyle('D'.($p+7+$sss))->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)'); 
$aSheet->getStyle('D'.($p+7+$sss))->applyFromArray($styleArray);




$aSheet->getColumnDimension('A')->setWidth(7);
$aSheet->getColumnDimension('B')->setWidth(8);
$aSheet->getColumnDimension('C')->setWidth(35);
$aSheet->getColumnDimension('D')->setWidth(13);
$aSheet->getColumnDimension('E')->setWidth(10);
$aSheet->getColumnDimension('F')->setWidth(14);
$aSheet->getColumnDimension('G')->setWidth(13);
$aSheet->getColumnDimension('I')->setWidth(15);
$aSheet->getColumnDimension('J')->setWidth(15);
$aSheet->getColumnDimension('O')->setWidth(15);
$aSheet->getColumnDimension('P')->setWidth(15);
$aSheet->getColumnDimension('Q')->setWidth(10);
$aSheet->getColumnDimension('R')->setWidth(15);
$aSheet->getColumnDimension('S')->setWidth(15);
$aSheet->getColumnDimension('T')->setWidth(10);
$aSheet->getColumnDimension('L')->setWidth(13);
$aSheet->getColumnDimension('K')->setWidth(13);



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
} else echo '<br><br><br><br><div align="center"><b><font size="5">По данной машине имеются заявки: '.implode(',' , $id_mass_order_check).', но не обнаружено ни одного рейса!</font></b></div>';
} else echo '<br><br><br><br><div align="center"><b><font size="5">По данной машине не обнаружено ни одного рейса!</font></b></div>';
}
?> 