<?php

include "config.php";

function komissia($cl_cash,$cl_minus,$cl_plus,$cl_nds_inp,$tr_cash,$tr_minus,$tr_plus,$tr_nds_inp){
$cl_cash_all=0;
$tr_cash_all=0;
$cash=0;

$cl_cash_all=$cl_cash-$cl_minus+$cl_plus;
$tr_cash_all=$tr_cash+$tr_minus-$cl_plus;
$cl_nds=(int)$cl_nds_inp;
$tr_nds=(int)$tr_nds_inp;
if($cl_nds==0&&$tr_nds==0)$cash=$cl_cash_all-$tr_cash_all;
if($cl_nds==1&&$tr_nds==1)$cash=$cl_cash_all-$tr_cash_all;
if($cl_nds==1&&$tr_nds==0)$cash=$cl_cash_all-($tr_cash_all+$tr_cash_all*0.03);
if($cl_nds==1&&$tr_nds==2)$cash=$cl_cash_all-($tr_cash_all+$tr_cash_all*0.06);
if($cl_nds==0&&$tr_nds==1)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*0.03;
if($cl_nds==0&&$tr_nds==2)$cash=$cl_cash_all-($tr_cash_all+$tr_cash_all*0.03);
if($cl_nds==2&&$tr_nds==1)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*0.06;
if($cl_nds==2&&$tr_nds==0)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*0.03;
if($cl_nds==2&&$tr_nds==2)$cash=$cl_cash_all-$tr_cash_all;

return $cash;
}

if(@$_GET['mode']=='zarplata'&&@$_GET['month']>0)
{
$query_settings = "SELECT `day_last_month` FROM `settings`";
$result_settings = mysql_query($query_settings) or die(mysql_error()); 
$settings= mysql_fetch_row($result_settings);
	
	
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
		'vertical'=>PHPExcel_Style_Alignment::VERTICAL_TOP
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
$hr_dot = array(
'borders' => array(
	'bottom' => array(
		'style' => PHPExcel_Style_Border::BORDER_DOTTED
		),
	),
);
$i=0;
$query = "SELECT `id`,`name`,`group`,`zarplata`,`motive`,`ndfl` FROM `workers` WHERE `delete`='0' AND `group`!='5' AND `group`!='2' ORDER BY `Id` ASC";
$result = mysql_query($query) or die(mysql_error()); 

while($row = mysql_fetch_array($result)) {

	
$pieces = explode(" ", $row['name']);
$name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";

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



$pExcel->setActiveSheetIndex($i);
$pExcel->createSheet();

$aSheet = $pExcel->getActiveSheet();
$aSheet->setTitle($name);
        // Добавляем новый документ

switch ($row['group']) {
case '1': $group='Администратор';break;
case '2': $group='Директор';break;
case '3': $group='Менеджер';break;
case '4': $group='Бухгалтер';break;
case '5': $group='Другое';break;
}        
$aSheet->setCellValue('B1','Зарплатная ведомость за '.$q[$_GET['month']].' месяц '.$_GET['year'].' года');
$aSheet->getStyle('B1')->applyFromArray($hFont);        
$aSheet->getStyle('A3')->applyFromArray($boldFont)->applyFromArray($center);       
$aSheet->getStyle('B3')->applyFromArray($boldFont)->applyFromArray($center);   
$aSheet->getStyle('C3')->applyFromArray($boldFont)->applyFromArray($center);   
$aSheet->getStyle('D3')->applyFromArray($center);  
$aSheet->getStyle('E3')->applyFromArray($boldFont)->applyFromArray($center);  
$aSheet->getStyle('F3')->applyFromArray($boldFont)->applyFromArray($center);  
  $aSheet->getStyle('G3')->applyFromArray($center);          
$aSheet->setCellValue('A3','№');
$aSheet->setCellValue('B3','Ф.И.О');
$aSheet->setCellValue('C3','Должность');
$aSheet->setCellValue('D3','Всего(дн.)');
$aSheet->setCellValue('E3','Отраб.(дн.)');

$aSheet->setCellValue('F3','Оклад(руб)');
$aSheet->setCellValue('G3','НДФЛ(руб)');

$aSheet->setCellValue('A4',$row['id']);
$aSheet->getStyle('A4')->applyFromArray($center); 
$aSheet->setCellValue('B4',$row['name']);
$aSheet->setCellValue('C4',$group);
$aSheet->getStyle('C4')->applyFromArray($center); 
$aSheet->setCellValue('D4',$settings[0]);
$aSheet->getStyle('D4')->applyFromArray($center); 
$aSheet->setCellValue('E4',0);
$aSheet->getStyle('E4')->applyFromArray($center); 
$aSheet->setCellValue('F4',(int)$row['zarplata']);
$aSheet->getStyle('F4')->applyFromArray($center); 
$aSheet->setCellValue('G4',$row['ndfl']);
$aSheet->getStyle('G4')->applyFromArray($center); 

$zarp=0;
$aSheet->getStyle('A3:G3')->applyFromArray($styleArray);
$aSheet->getStyle('A4:G4')->applyFromArray($styleArray2);

if($row['group']==3)
{
$total=0;
$total_last1=0;
$total_last2=0;
$tr_last1=0;
$tr_last2=0;
$tr_dop_last1=0;
$tr_dop_last2=0;

$total_tr=0;
$total_tr_dop=0;
$total_tr_dop_nlk=0;
$total_info=0;	
$total_info_nlk=0;

$total_nlk_last1=0;
$total_nlk_last2=0;
	
$t_order="";
$t_order_last1="";
$tr_order_last1="";
$tr_dop_order_last1="";
$t_order_last2="";
$tr_order_last2="";
$tr_dop_order_last2="";

$tr_nlk_last1="";
$tr_nlk_last2="";

$tr_order="";
$tr_dop_order="";
$tr_dop_order_nlk="";
$output="";
$output_d="";
$manager=$row['id'];

$total_all_last1=0;
$total_all_last2=0;

$zarp_last1=0;
$zarp_last2=0;

$zarp=0;

if((int)$_GET['month']>=1&&(int)$_GET['month']<=9){
$date_start=(int)$_GET['year']."-0".(int)$_GET['month']."-01";
$date_end=(int)$_GET['year']."-0".(int)$_GET['month']."-".date("t", strtotime((int)$_GET['year']."-0".(int)$_GET['month']));
} else {
$date_start=(int)$_GET['year']."-".(int)$_GET['month']."-01";
$date_end=(int)$_GET['year']."-".(int)$_GET['month']."-".date("t", strtotime((int)$_GET['year']."-".(int)$_GET['month']));
}


// Общая за месяц НЛК
$query_nlk = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`client` FROM `orders` WHERE `delete`='0' AND `tr_manager`='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."' AND (`client`='16' OR `client`='76')";
$result_nlk = mysql_query($query_nlk) or die(mysql_error());



$s=6;
$d=6;

while($row_nlk = mysql_fetch_array($result_nlk)) {
$cash=0;
$pays_nlk=0;

$query_pays_nlk = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_nlk['id']."'";
$result_pays_nlk = mysql_query($query_pays_nlk) or die(mysql_error());
while($pay_nlk = mysql_fetch_row($result_pays_nlk)) {$pays_nlk=(int)$pays_nlk+(int)$pay_nlk[0];}


$cash=komissia($row_nlk['cl_cash'],$row_nlk['cl_minus'],$row_nlk['cl_plus'],$row_nlk['cl_nds'],$row_nlk['tr_cash'],$row_nlk['tr_minus'],$row_nlk['tr_plus'],$row_nlk['tr_nds']);



$total_info_nlk=(int)$total_info_nlk+(int)$cash;

if(((int)$pays_nlk/100)==(int)$row_nlk['cl_cash']){
$s++;} else {$cash=0;$d++;}


$tr_dop_order_nlk[$row_nlk['id']]=$cash;
$total_tr_dop_nlk=(int)$total_tr_dop_nlk+(int)$cash;

}

// Общая за предыдущий месяц НЛК
$date_start_last=date('Y-m',strtotime('-1 month',strtotime($date_start)))."-01";
$date_end_last=date('Y-m',strtotime('-1 month',strtotime($date_start)))."-".date("t", strtotime(date('Y-m',strtotime('-1 month',strtotime($date_start)))));

$query_nlk = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`client` FROM `orders` WHERE `delete`='0' AND `tr_manager`='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start_last)."' AND '".mysql_escape_string($date_end_last)."' AND (`client`='16' OR `client`='76')";
$result_nlk = mysql_query($query_nlk) or die(mysql_error());



while($row_nlk = mysql_fetch_array($result_nlk)) {
$cash=0;
$pays_nlk=0;

$part=explode("-",$date_start);
$date_start_pays_last=$part[0]."-".$part[1]."-14";
$date_start_pays_last_temp=date('Y-m-d',strtotime('-2 month',strtotime($date_start_pays_last)));
$date_end_pays_last=date("Y-m-d");

$query_pays_nlk = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_nlk['id']."' AND DATE(`date`) BETWEEN '".mysql_escape_string($date_start_pays_last_temp)."' AND '".mysql_escape_string($date_end_pays_last)."'";
$result_pays_nlk = mysql_query($query_pays_nlk) or die(mysql_error());
while($pay_nlk = mysql_fetch_row($result_pays_nlk)) {$pays_nlk=(int)$pays_nlk+(int)$pay_nlk[0];}


$cash=komissia($row_nlk['cl_cash'],$row_nlk['cl_minus'],$row_nlk['cl_plus'],$row_nlk['cl_nds'],$row_nlk['tr_cash'],$row_nlk['tr_minus'],$row_nlk['tr_plus'],$row_nlk['tr_nds']);



$total_all_last1=(int)$total_all_last1+(int)$cash;

if(((int)$pays_nlk/100)==(int)$row_nlk['cl_cash']){
$s++;$tr_nlk_last1[$row_nlk['id']]=$cash;$total_nlk_last1=(int)$total_nlk_last1+(int)$cash;}

}
// Общая за предпредыдущий месяц НЛК
$date_start_last=date('Y-m',strtotime('-2 month',strtotime($date_start)))."-01";
$date_end_last=date('Y-m',strtotime('-2 month',strtotime($date_start)))."-".date("t", strtotime(date('Y-m',strtotime('-2 month',strtotime($date_start)))));

$query_nlk = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`client` FROM `orders` WHERE `delete`='0' AND `tr_manager`='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start_last)."' AND '".mysql_escape_string($date_end_last)."' AND (`client`='16' OR `client`='76')";
$result_nlk = mysql_query($query_nlk) or die(mysql_error());



while($row_nlk = mysql_fetch_array($result_nlk)) {
$cash=0;
$pays_nlk=0;

$part=explode("-",$date_start);
$date_start_pays_last=$part[0]."-".$part[1]."-14";
$date_start_pays_last_temp=date('Y-m-d',strtotime('-2 month',strtotime($date_start_pays_last)));
$date_end_pays_last=date("Y-m-d");

$query_pays_nlk = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_nlk['id']."' AND DATE(`date`) BETWEEN '".mysql_escape_string($date_start_pays_last_temp)."' AND '".mysql_escape_string($date_end_pays_last)."'";
$result_pays_nlk = mysql_query($query_pays_nlk) or die(mysql_error());
while($pay_nlk = mysql_fetch_row($result_pays_nlk)) {$pays_nlk=(int)$pays_nlk+(int)$pay_nlk[0];}


$cash=komissia($row_nlk['cl_cash'],$row_nlk['cl_minus'],$row_nlk['cl_plus'],$row_nlk['cl_nds'],$row_nlk['tr_cash'],$row_nlk['tr_minus'],$row_nlk['tr_plus'],$row_nlk['tr_nds']);



$total_all_last2=(int)$total_all_last2+(int)$cash;

if(((int)$pays_nlk/100)==(int)$row_nlk['cl_cash']){
$s++;$tr_nlk_last2[$row_nlk['id']]=$cash;$total_nlk_last2=(int)$total_nlk_last2+(int)$cash;}

}

// Общая за месяц		
$query_total = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`client` FROM `orders` WHERE `delete`='0' AND `manager`='".$manager."' AND `tr_manager`='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."' AND `client` not in (16,76)";
$result_total = mysql_query($query_total) or die(mysql_error());

while($row_t = mysql_fetch_array($result_total)) {
$cash=0;
$pays_t=0;

$query_pays_t = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_t['id']."'";
$result_pays_t = mysql_query($query_pays_t) or die(mysql_error());
while($pay_t = mysql_fetch_row($result_pays_t)) {$pays_t=(int)$pays_t+(int)$pay_t[0];}

$cash=komissia($row_t['cl_cash'],$row_t['cl_minus'],$row_t['cl_plus'],$row_t['cl_nds'],$row_t['tr_cash'],$row_t['tr_minus'],$row_t['tr_plus'],$row_t['tr_nds']);


$total_info=(int)$total_info+(int)$cash;

if(((int)$pays_t/100)==(int)$row_t['cl_cash']){
$s++;} else {$cash=0;$d++;}

$t_order[$row_t['id']]=$cash;$total=(int)$total+(int)$cash;

}

// Общая за предыдущий месяц
$date_start_last=date('Y-m',strtotime('-1 month',strtotime($date_start)))."-01";
$date_end_last=date('Y-m',strtotime('-1 month',strtotime($date_start)))."-".date("t", strtotime(date('Y-m',strtotime('-1 month',strtotime($date_start)))));


		
$query_total = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`client`,`data` FROM `orders` WHERE `delete`='0' AND `manager`='".$manager."' AND `tr_manager`='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start_last)."' AND '".mysql_escape_string($date_end_last)."' AND `client` not in (16,76) ORDER BY `id` ASC";
$result_total = mysql_query($query_total) or die(mysql_error());


while($row_t = mysql_fetch_array($result_total)) {
$cash=0;
$pays_t=0;
$pays_temp=0;


$part=explode("-",$date_start);
$date_start_pays_last=$part[0]."-".$part[1]."-14";
$date_start_pays_last_temp=date('Y-m-d',strtotime('-2 month',strtotime($date_start_pays_last)));

$date_end_pays_last=date("Y-m-d");

$query_pays_t = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_t['id']."' AND DATE(`date`) BETWEEN '".mysql_escape_string($date_start_pays_last)."' AND '".mysql_escape_string($date_end_pays_last)."'";
$result_pays_t = mysql_query($query_pays_t) or die(mysql_error());


while($pay_t = mysql_fetch_row($result_pays_t)) {
$pays_t=(int)$pays_t+(int)$pay_t[0];
}

if(mysql_num_rows($result_pays_t)>0){
$query_pays_temp = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_t['id']."' AND DATE(`date`) BETWEEN '".mysql_escape_string($date_start_pays_last_temp)."' AND '".mysql_escape_string($date_start_pays_last)."'";
$result_pays_temp = mysql_query($query_pays_temp) or die(mysql_error());
$pays_temp=0;
while($pay_temp = mysql_fetch_row($result_pays_temp)) {
$pays_temp=(int)$pays_temp+(int)$pay_temp[0];
}
}

$pays_t=(int)$pays_t+(int)$pays_temp;


$cash=komissia($row_t['cl_cash'],$row_t['cl_minus'],$row_t['cl_plus'],$row_t['cl_nds'],$row_t['tr_cash'],$row_t['tr_minus'],$row_t['tr_plus'],$row_t['tr_nds']);

$total_all_last1=(int)$total_all_last1+(int)$cash;

if(((int)$pays_t/100)==(int)$row_t['cl_cash']){
$s++;$t_order_last1[$row_t['id']]=$cash;$total_last1=(int)$total_last1+(int)$cash;}


}

// Общая за предпредыдущий месяц
$date_start_last=date('Y-m',strtotime('-2 month',strtotime($date_start)))."-01";
$date_end_last=date('Y-m',strtotime('-2 month',strtotime($date_start)))."-".date("t", strtotime(date('Y-m',strtotime('-2 month',strtotime($date_start)))));




		
$query_total = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`client`,`data` FROM `orders` WHERE `delete`='0' AND `manager`='".$manager."' AND `tr_manager`='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start_last)."' AND '".mysql_escape_string($date_end_last)."' AND `client` not in (16,76) ORDER BY `id` ASC";
$result_total = mysql_query($query_total) or die(mysql_error());


while($row_t = mysql_fetch_array($result_total)) {

$cash=0;
$pays_t=0;
$pays_temp=0;


$part=explode("-",$date_start);
$date_start_pays_last=$part[0]."-".$part[1]."-14";
$date_start_pays_last_temp=date('Y-m-d',strtotime('-2 month',strtotime($date_start_pays_last)));

$date_end_pays_last=date("Y-m-d");

$query_pays_t = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_t['id']."' AND DATE(`date`) BETWEEN '".mysql_escape_string($date_start_pays_last)."' AND '".mysql_escape_string($date_end_pays_last)."'";
$result_pays_t = mysql_query($query_pays_t) or die(mysql_error());


while($pay_t = mysql_fetch_row($result_pays_t)) {
$pays_t=(int)$pays_t+(int)$pay_t[0];
}

//if($row_t['id']=='2458')echo $date_start_pays_last.'<br>'.$date_start_pays_last_temp;

if(mysql_num_rows($result_pays_t)>0){
$query_pays_temp = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_t['id']."' AND DATE(`date`) BETWEEN '".mysql_escape_string($date_start_pays_last_temp)."' AND '".mysql_escape_string($date_start_pays_last)."'";
$result_pays_temp = mysql_query($query_pays_temp) or die(mysql_error());
$pays_temp=0;
while($pay_temp = mysql_fetch_row($result_pays_temp)) {
$pays_temp=(int)$pays_temp+(int)$pay_temp[0];
}
}

$pays_t=(int)$pays_t+(int)$pays_temp;


$cash=komissia($row_t['cl_cash'],$row_t['cl_minus'],$row_t['cl_plus'],$row_t['cl_nds'],$row_t['tr_cash'],$row_t['tr_minus'],$row_t['tr_plus'],$row_t['tr_nds']);

$total_all_last2=(int)$total_all_last2+(int)$cash;

if(((int)$pays_t/100)==(int)$row_t['cl_cash']){
$s++;$t_order_last2[$row_t['id']]=$cash;$total_last2=(int)$total_last2+(int)$cash;}


}

// Общая за месяц
$query_tr = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`client` FROM `orders` WHERE `delete`='0' AND `manager`='".$manager."' AND `tr_manager`!='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."' AND `client` not in (16,76)";
$result_tr = mysql_query($query_tr) or die(mysql_error());


while($row_tr = mysql_fetch_array($result_tr)) {
$cash=0;
$pays_tr=0;

$query_pays_tr = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_tr['id']."'";
$result_pays_tr = mysql_query($query_pays_tr) or die(mysql_error());
while($pay_tr = mysql_fetch_row($result_pays_tr)) {$pays_tr=(int)$pays_tr+(int)$pay_tr[0];}

$cash=komissia($row_tr['cl_cash'],$row_tr['cl_minus'],$row_tr['cl_plus'],$row_tr['cl_nds'],$row_tr['tr_cash'],$row_tr['tr_minus'],$row_tr['tr_plus'],$row_tr['tr_nds']);

$total_info=(int)$total_info+(int)$cash/2;

if(((int)$pays_tr/100)==(int)$row_tr['cl_cash']){
$s++;} else {$cash=0;$d++;}


$tr_order[$row_tr['id']]=$cash;$total_tr=(int)$total_tr+(int)$cash;

}

// Общая за предыдущий месяц
$date_start_last=date('Y-m',strtotime('-1 month',strtotime($date_start)))."-01";
$date_end_last=date('Y-m',strtotime('-1 month',strtotime($date_start)))."-".date("t", strtotime(date('Y-m',strtotime('-1 month',strtotime($date_start)))));

$query_tr = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`client` FROM `orders` WHERE `delete`='0' AND `manager`='".$manager."' AND `tr_manager`!='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start_last)."' AND '".mysql_escape_string($date_end_last)."' AND `client` not in (16,76)";
$result_tr = mysql_query($query_tr) or die(mysql_error());


while($row_tr = mysql_fetch_array($result_tr)) {
$cash=0;
$pays_tr=0;
$pays_temp=0;

$part=explode("-",$date_start);
$date_start_pays_last=$part[0]."-".$part[1]."-14";
$date_start_pays_last_temp=date('Y-m-d',strtotime('-2 month',strtotime($date_start_pays_last)));

$date_end_pays_last=date("Y-m-d");


$query_pays_tr = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_tr['id']."' AND DATE(`date`) BETWEEN '".mysql_escape_string($date_start_pays_last)."' AND '".mysql_escape_string($date_end_pays_last)."'";
$result_pays_tr = mysql_query($query_pays_tr) or die(mysql_error());
while($pay_tr = mysql_fetch_row($result_pays_tr)) {$pays_tr=(int)$pays_tr+(int)$pay_tr[0];}

if(mysql_num_rows($result_pays_tr)>0){
$query_pays_temp = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_tr['id']."' AND DATE(`date`) BETWEEN '".mysql_escape_string($date_start_pays_last_temp)."' AND '".mysql_escape_string($date_start_pays_last)."'";
$result_pays_temp = mysql_query($query_pays_temp) or die(mysql_error());

while($pay_temp = mysql_fetch_row($result_pays_temp)) {
$pays_temp=(int)$pays_temp+(int)$pay_temp[0];
}
}

$pays_tr=(int)$pays_tr+(int)$pays_temp;

$cash=komissia($row_tr['cl_cash'],$row_tr['cl_minus'],$row_tr['cl_plus'],$row_tr['cl_nds'],$row_tr['tr_cash'],$row_tr['tr_minus'],$row_tr['tr_plus'],$row_tr['tr_nds']);

$total_all_last1=(int)$total_all_last1+(int)$cash/2;

if(((int)$pays_tr/100)==(int)$row_tr['cl_cash']){
$s++;$tr_order_last1[$row_tr['id']]=(int)$cash/2;$tr_last1=(int)$tr_last1+(int)$cash/2;}



}

// Общая за предпредыдущий месяц
$date_start_last=date('Y-m',strtotime('-2 month',strtotime($date_start)))."-01";
$date_end_last=date('Y-m',strtotime('-2 month',strtotime($date_start)))."-".date("t", strtotime(date('Y-m',strtotime('-2 month',strtotime($date_start)))));

$query_tr = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`client` FROM `orders` WHERE `delete`='0' AND `manager`='".$manager."' AND `tr_manager`!='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start_last)."' AND '".mysql_escape_string($date_end_last)."' AND `client` not in (16,76)";
$result_tr = mysql_query($query_tr) or die(mysql_error());


while($row_tr = mysql_fetch_array($result_tr)) {
$cash=0;
$pays_tr=0;
$pays_temp=0;

$part=explode("-",$date_start);
$date_start_pays_last=$part[0]."-".$part[1]."-14";
$date_start_pays_last_temp=date('Y-m-d',strtotime('-2 month',strtotime($date_start_pays_last)));

$date_end_pays_last=date("Y-m-d");


$query_pays_tr = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_tr['id']."' AND DATE(`date`) BETWEEN '".mysql_escape_string($date_start_pays_last)."' AND '".mysql_escape_string($date_end_pays_last)."'";
$result_pays_tr = mysql_query($query_pays_tr) or die(mysql_error());
while($pay_tr = mysql_fetch_row($result_pays_tr)) {$pays_tr=(int)$pays_tr+(int)$pay_tr[0];}

if(mysql_num_rows($result_pays_tr)>0){
$query_pays_temp = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_tr['id']."' AND DATE(`date`) BETWEEN '".mysql_escape_string($date_start_pays_last_temp)."' AND '".mysql_escape_string($date_start_pays_last)."'";
$result_pays_temp = mysql_query($query_pays_temp) or die(mysql_error());

while($pay_temp = mysql_fetch_row($result_pays_temp)) {
$pays_temp=(int)$pays_temp+(int)$pay_temp[0];
}
}

$pays_tr=(int)$pays_tr+(int)$pays_temp;

$cash=komissia($row_tr['cl_cash'],$row_tr['cl_minus'],$row_tr['cl_plus'],$row_tr['cl_nds'],$row_tr['tr_cash'],$row_tr['tr_minus'],$row_tr['tr_plus'],$row_tr['tr_nds']);

$total_all_last2=(int)$total_all_last2+(int)$cash/2;

if(((int)$pays_tr/100)==(int)$row_tr['cl_cash']){
$s++;$tr_order_last2[$row_tr['id']]=(int)$cash/2;$tr_last2=(int)$tr_last2+(int)$cash/2;}



}

// Общая за месяц
$query_tr_dop = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`client` FROM `orders` WHERE `delete`='0' AND `manager`!='".$manager."' AND `tr_manager`='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."' AND `client` not in (16,76)";
$result_tr_dop = mysql_query($query_tr_dop) or die(mysql_error());

while($row_tr_dop = mysql_fetch_array($result_tr_dop)) {
$cash=0;
$pays_tr_dop=0;

$query_pays_tr_dop = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_tr_dop['id']."'";
$result_pays_tr_dop = mysql_query($query_pays_tr_dop) or die(mysql_error());
while($pay_tr_dop = mysql_fetch_row($result_pays_tr_dop)) {$pays_tr_dop=(int)$pays_tr_dop+(int)$pay_tr_dop[0];}

$cash=komissia($row_tr_dop['cl_cash'],$row_tr_dop['cl_minus'],$row_tr_dop['cl_plus'],$row_tr_dop['cl_nds'],$row_tr_dop['tr_cash'],$row_tr_dop['tr_minus'],$row_tr_dop['tr_plus'],$row_tr_dop['tr_nds']);

$total_info=(int)$total_info+(int)$cash/2;

if(((int)$pays_tr_dop/100)==(int)$row_tr_dop['cl_cash']){
$s++;} else {$cash=0;$d++;}

$tr_dop_order[$row_tr_dop['id']]=$cash;$total_tr_dop=(int)$total_tr_dop+(int)$cash;

}

// Общая за предыдущий месяц
$date_start_last=date('Y-m',strtotime('-1 month',strtotime($date_start)))."-01";
$date_end_last=date('Y-m',strtotime('-1 month',strtotime($date_start)))."-".date("t", strtotime(date('Y-m',strtotime('-1 month',strtotime($date_start)))));

$query_tr_dop = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`client` FROM `orders` WHERE `delete`='0' AND `manager`!='".$manager."' AND `tr_manager`='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start_last)."' AND '".mysql_escape_string($date_end_last)."' AND `client` not in (16,76)";
$result_tr_dop = mysql_query($query_tr_dop) or die(mysql_error());

while($row_tr_dop = mysql_fetch_array($result_tr_dop)) {
$cash=0;
$pays_tr_dop=0;
$pays_temp=0;

$part=explode("-",$date_start);
$date_start_pays_last=$part[0]."-".$part[1]."-14";
$date_start_pays_last_temp=date('Y-m-d',strtotime('-2 month',strtotime($date_start_pays_last)));

$date_end_pays_last=date("Y-m-d");

$query_pays_tr_dop = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_tr_dop['id']."' AND DATE(`date`) BETWEEN '".mysql_escape_string($date_start_pays_last)."' AND '".mysql_escape_string($date_end_pays_last)."'";
$result_pays_tr_dop = mysql_query($query_pays_tr_dop) or die(mysql_error());
while($pay_tr_dop = mysql_fetch_row($result_pays_tr_dop)) {$pays_tr_dop=(int)$pays_tr_dop+(int)$pay_tr_dop[0];}

if(mysql_num_rows($result_pays_tr_dop)>0){
$query_pays_temp = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_tr_dop['id']."' AND DATE(`date`) BETWEEN '".mysql_escape_string($date_start_pays_last_temp)."' AND '".mysql_escape_string($date_start_pays_last)."'";
$result_pays_temp = mysql_query($query_pays_temp) or die(mysql_error());
$pays_temp=0;
while($pay_temp = mysql_fetch_row($result_pays_temp)) {
$pays_temp=(int)$pays_temp+(int)$pay_temp[0];
}
}

$pays_tr_dop=(int)$pays_tr_dop+(int)$pays_temp;


$cash=komissia($row_tr_dop['cl_cash'],$row_tr_dop['cl_minus'],$row_tr_dop['cl_plus'],$row_tr_dop['cl_nds'],$row_tr_dop['tr_cash'],$row_tr_dop['tr_minus'],$row_tr_dop['tr_plus'],$row_tr_dop['tr_nds']);

$total_all_last1=(int)$total_all_last1+(int)$cash/2;

if(((int)$pays_tr_dop/100)==(int)$row_tr_dop['cl_cash']){
$s++;$tr_dop_order_last1[$row_tr_dop['id']]=(int)$cash/2;$tr_dop_last1=(int)$tr_dop_last1+(int)$cash/2;}




}

// Общая за предпредыдущий месяц
$date_start_last=date('Y-m',strtotime('-2 month',strtotime($date_start)))."-01";
$date_end_last=date('Y-m',strtotime('-2 month',strtotime($date_start)))."-".date("t", strtotime(date('Y-m',strtotime('-2 month',strtotime($date_start)))));



$query_tr_dop = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`client` FROM `orders` WHERE `delete`='0' AND `manager`!='".$manager."' AND `tr_manager`='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start_last)."' AND '".mysql_escape_string($date_end_last)."' AND `client` not in (16,76)";
$result_tr_dop = mysql_query($query_tr_dop) or die(mysql_error());

while($row_tr_dop = mysql_fetch_array($result_tr_dop)) {
$cash=0;
$pays_tr_dop=0;
$pays_temp=0;

$part=explode("-",$date_start);
$date_start_pays_last=$part[0]."-".$part[1]."-14";
$date_start_pays_last_temp=date('Y-m-d',strtotime('-2 month',strtotime($date_start_pays_last)));

$date_end_pays_last=date("Y-m-d");

$query_pays_tr_dop = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_tr_dop['id']."' AND DATE(`date`) BETWEEN '".mysql_escape_string($date_start_pays_last)."' AND '".mysql_escape_string($date_end_pays_last)."'";
$result_pays_tr_dop = mysql_query($query_pays_tr_dop) or die(mysql_error());
while($pay_tr_dop = mysql_fetch_row($result_pays_tr_dop)) {$pays_tr_dop=(int)$pays_tr_dop+(int)$pay_tr_dop[0];}

if(mysql_num_rows($result_pays_tr_dop)>0){
$query_pays_temp = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_tr_dop['id']."' AND DATE(`date`) BETWEEN '".mysql_escape_string($date_start_pays_last_temp)."' AND '".mysql_escape_string($date_start_pays_last)."'";
$result_pays_temp = mysql_query($query_pays_temp) or die(mysql_error());
$pays_temp=0;
while($pay_temp = mysql_fetch_row($result_pays_temp)) {
$pays_temp=(int)$pays_temp+(int)$pay_temp[0];
}
}

$pays_tr_dop=(int)$pays_tr_dop+(int)$pays_temp;


$cash=komissia($row_tr_dop['cl_cash'],$row_tr_dop['cl_minus'],$row_tr_dop['cl_plus'],$row_tr_dop['cl_nds'],$row_tr_dop['tr_cash'],$row_tr_dop['tr_minus'],$row_tr_dop['tr_plus'],$row_tr_dop['tr_nds']);

$total_all_last2=(int)$total_all_last2+(int)$cash/2;

if(((int)$pays_tr_dop/100)==(int)$row_tr_dop['cl_cash']){
$s++;$tr_dop_order_last2[$row_tr_dop['id']]=(int)$cash/2;$tr_dop_last2=(int)$tr_dop_last2+(int)$cash/2;}




}


$proc="";

if((int)$total_info<100000){$zarp=((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2))*0.1;$proc=" (10%)";$pr=0.1;}
if((int)$total_info>=100000){$zarp=((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2))*0.15;$proc=" (15%)";$pr=0.15;}

$proc_last1="";

if((int)$total_all_last1<100000){$zarp_last1=((int)$total_last1+((int)$tr_last1/2)+((int)$tr_dop_last1/2))*0.1;$proc_last1=" (10%)";}
if((int)$total_all_last1>=100000){$zarp_last1=((int)$total_last1+((int)$tr_last1/2)+((int)$tr_dop_last1/2))*0.15;$proc_last1=" (15%)";}

$proc_last2="";

if((int)$total_all_last2<100000){$zarp_last2=((int)$total_last2+((int)$tr_last2/2)+((int)$tr_dop_last2/2))*0.1;$proc_last2=" (10%)";}
if((int)$total_all_last2>=100000){$zarp_last2=((int)$total_last2+((int)$tr_last2/2)+((int)$tr_dop_last2/2))*0.15;$proc_last2=" (15%)";}


if (!empty($t_order))
{foreach ($t_order as $index=>$value) {if($value!=0)$output.= $index." (".($value)." р), "; else $output_d.= $index." (".($value)." р), ";} }


if (!empty($tr_order))
{foreach ($tr_order as $index=>$value) {if($value!=0)$output.= $index." (".($value/2)." р - п.), "; else $output_d.= $index." (".($value/2)." р - п.), ";}}


if (!empty($tr_dop_order))
{foreach ($tr_dop_order as $index=>$value) {if($value!=0)$output.= $index." (".($value/2)." р - п.п.), "; else $output_d.= $index." (".($value/2)." р - п.п.), ";}}

if (!empty($tr_dop_order_nlk))
{foreach ($tr_dop_order_nlk as $index=>$value) {if($value!=0)$output.= $index." (".$value." р - НЛК), "; else $output_d.= $index." (".$value." р - НЛК), ";}}


if (!empty($t_order_last1))
{foreach ($t_order_last1 as $index=>$value) {if($value!=0)$output.= "!".$index." (".($value)." р), ";} }

if (!empty($t_order_last2))
{foreach ($t_order_last2 as $index=>$value) {if($value!=0)$output.= "!!".$index." (".($value)." р), ";} }

if (!empty($tr_order_last1))
{foreach ($tr_order_last1 as $index=>$value) {if($value!=0)$output.= "!".$index." (".($value)." р - п.), ";} }

if (!empty($tr_order_last2))
{foreach ($tr_order_last2 as $index=>$value) {if($value!=0)$output.= "!!".$index." (".($value)." р - п.), ";} }

if (!empty($tr_dop_order_last1))
{foreach ($tr_dop_order_last1 as $index=>$value) {if($value!=0)$output.= "!".$index." (".($value)." р - п.п.), ";} }

if (!empty($tr_dop_order_last2))
{foreach ($tr_dop_order_last2 as $index=>$value) {if($value!=0)$output.= "!!".$index." (".($value)." р - п.п.), ";} }


if (!empty($tr_nlk_last1))
{foreach ($tr_nlk_last1 as $index=>$value) {if($value!=0)$output.= "!".$index." (".$value." р - НЛК), ";}}

if (!empty($tr_nlk_last2))
{foreach ($tr_nlk_last2 as $index=>$value) {if($value!=0)$output.= "!!".$index." (".$value." р - НЛК), ";}}


$aSheet->mergeCells('A5:B5');
$aSheet->setCellValue('A5',"Оплаченные заявки:");
$aSheet->getStyle('A5')->applyFromArray($boldFont);

$aSheet->mergeCells('B6:G6');
$aSheet->getRowDimension('6')->setRowHeight($s*2.5);
$aSheet->getStyle('B6')->getAlignment()->setWrapText(true);
$aSheet->setCellValue('B6',substr_replace($output ,"",-2));
$aSheet->getStyle('B6')->applyFromArray($top);


$aSheet->mergeCells('A7:B7');
$aSheet->setCellValue('A7',"Неплаченные заявки:");
$aSheet->getStyle('A7')->applyFromArray($boldFont);	
$aSheet->mergeCells('B8:G8');
$aSheet->getRowDimension('8')->setRowHeight($d+50);
$aSheet->getStyle('B8')->getAlignment()->setWrapText(true);
$aSheet->setCellValue('B8',substr_replace($output_d ,"",-2));
$aSheet->getStyle('B8')->applyFromArray($top); 


$aSheet->setCellValue('B10',"Переменная часть: ");
$aSheet->getStyle('B10')->applyFromArray($boldFont)->applyFromArray($right);
$aSheet->setCellValue('C10',$zarp);
$aSheet->getStyle('C10')->applyFromArray($boldFont);
$aSheet->setCellValue('D10'," руб.".$proc);

if((int)$_GET['month']>=1&&(int)$_GET['month']<=9){
$date_d=(int)$_GET['year']."-0".(int)$_GET['month']."-01";
} else {
$date_d=(int)$_GET['year']."-".(int)$_GET['month']."-01";
}


$last_month_name=$q[(int)date("m",strtotime("-1 month",strtotime($date_d)))];
$last_last_month_name=$q[(int)date("m",strtotime("-2 month",strtotime($date_d)))];

$aSheet->setCellValue('B11',"Переменная часть за ".$last_month_name.": ");
$aSheet->getStyle('B11')->applyFromArray($boldFont)->applyFromArray($right);
$aSheet->setCellValue('C11',(int)$zarp_last1);
$aSheet->getStyle('C11')->applyFromArray($boldFont);
$aSheet->setCellValue('D11'," руб.".$proc_last1);

$aSheet->setCellValue('B12',"Переменная часть за ".$last_last_month_name.": ");
$aSheet->getStyle('B12')->applyFromArray($boldFont)->applyFromArray($right);
$aSheet->setCellValue('C12',(int)$zarp_last2);
$aSheet->getStyle('C12')->applyFromArray($boldFont);
$aSheet->setCellValue('D12'," руб.".$proc_last2);



$aSheet->setCellValue('E10',"Выполнение:");
$aSheet->getStyle('E10')->applyFromArray($boldFont);

$aSheet->setCellValue('E11',"по заявкам:");
$aSheet->setCellValue('F11',$total_info);
$aSheet->getStyle('F11')->applyFromArray($boldFont);
$aSheet->setCellValue('G11',"руб.");

$aSheet->setCellValue('E12',"по оплате:");
$aSheet->setCellValue('F12',((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2)));
$aSheet->getStyle('F12')->applyFromArray($boldFont);
$aSheet->setCellValue('G12',"руб.");

if (!empty($tr_dop_order_nlk)){
$aSheet->setCellValue('B13',"НЛК: ");
$aSheet->getStyle('B13')->applyFromArray($boldFont)->applyFromArray($right);


$aSheet->setCellValue('C13',(int)$total_tr_dop_nlk*0.05);
$aSheet->setCellValue('D13'," руб.");


$aSheet->setCellValue('E13',"Выполнение НЛК:");
$aSheet->getStyle('E13')->applyFromArray($boldFont);

$aSheet->setCellValue('E14',"по заявкам:");
$aSheet->setCellValue('F14',$total_info_nlk);
$aSheet->getStyle('F14')->applyFromArray($boldFont);
$aSheet->setCellValue('G14',"руб.");

$aSheet->setCellValue('E15',"по оплате:");
$aSheet->setCellValue('F15',$total_tr_dop_nlk);
$aSheet->getStyle('F15')->applyFromArray($boldFont);
$aSheet->setCellValue('G15',"руб.");

}

if (!empty($tr_nlk_last1)){
$aSheet->setCellValue('B14',"Переменная часть (НЛК) за ".$last_month_name.": ");
$aSheet->getStyle('B14')->applyFromArray($boldFont)->applyFromArray($right);


$aSheet->setCellValue('C14',(int)$total_nlk_last1*0.05);
$aSheet->setCellValue('D14'," руб.(5%)");
$aSheet->getStyle('C14')->applyFromArray($boldFont)->applyFromArray($right);
}
if (!empty($tr_nlk_last2)){
$aSheet->setCellValue('B15',"Переменная часть (НЛК) за ".$last_last_month_name.": ");
$aSheet->getStyle('B15')->applyFromArray($boldFont)->applyFromArray($right);


$aSheet->setCellValue('C15',(int)$total_nlk_last2*0.05);
$aSheet->setCellValue('D15'," руб.(5%)");
$aSheet->getStyle('C15')->applyFromArray($boldFont)->applyFromArray($right);
}

$dolg='';

if((int)$_GET['month']>=1&&(int)$_GET['month']<=9){
		
	
$date = $_GET['year']."-0".$_GET['month']."-01";
$time = strtotime('-12 months', strtotime($date));
$date_start_dolg=date('Y-m-d', $time);

$date_end_dolg=(int)$_GET['year']."-0".((int)$_GET['month']-1)."-31";



} else {
$date = $_GET['year']."-".$_GET['month']."-01";
$time = strtotime('-12 months', strtotime($date));
$date_start_dolg=date('Y-m-d', $time);

$date_end_dolg=(int)$_GET['year']."-".((int)$_GET['month']-1)."-31";
}


$query_dolg = "SELECT `id`,`cl_cash`,`tr_cash`,`data` FROM `orders` WHERE `delete`='0' AND `manager`='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start_dolg)."' AND '".mysql_escape_string($date_end_dolg)."' ORDER BY `data` ASC";
$result_dolg = mysql_query($query_dolg) or die(mysql_error());

$dolg_date='';
while($row_dolg = mysql_fetch_array($result_dolg)) {
$cash_dolg=0;
$cl_cash_dolg=0;
$tr_cash_dolg=0;
$pays_dolg=0;

$query_pays_dolg = "SELECT `cash`,`order` FROM `pays` WHERE `delete`='0' AND `appoint`='1' AND `status`='1' AND `order`='".$row_dolg['id']."'";
$result_pays_dolg = mysql_query($query_pays_dolg) or die(mysql_error());
while($pay_dolg = mysql_fetch_row($result_pays_dolg)) {$pays_dolg=(int)$pays_dolg+(int)$pay_dolg[0];}

$dolg_elements  = explode("-",$row_dolg['data']);


if(((int)$pays_dolg/100)!=(int)$row_dolg['cl_cash']){
if($dolg_date!=$dolg_elements[1]){
switch ($dolg_elements[1]) {
case '01': $month='Январь';break;
case '02': $month='Февраль';break;
case '03': $month='Март';break;
case '04': $month='Апрель';break;
case '05': $month='Май';break;
case '06': $month='Июнь';break;
case '07': $month='Июль';break;
case '08': $month='Август';break;
case '09': $month='Сентябрь';break;
case '10': $month='Октябрь';break;
case '11': $month='Ноябрь';break;
case '12': $month='Декабрь';break;}

$dolg.=$month.' '.date('Y', strtotime($row_dolg[3]))."г.: ";$dolg_date=$dolg_elements[1];
}
$dolg.=' '.$row_dolg['id'].', ';}


}

$aSheet->setCellValue('B26','Неоплаченные заявки прошлых периодов');
$aSheet->getStyle('B26')->applyFromArray($boldFont);
$aSheet->setCellValue('B27',substr_replace($dolg,"",-2));
$aSheet->mergeCells('B27:G27');
$aSheet->getRowDimension('27')->setRowHeight(50);
$aSheet->getStyle('B27')->getAlignment()->setWrapText(true);
$aSheet->getStyle('B27')->applyFromArray($top);

$oplata='';
	
if(!empty($t_order_last1)||!empty($tr_order_last1)||!empty($tr_dop_order_last1)||!empty($tr_nlk_last1))$oplata=$last_month_name;

if(!empty($t_order_last1))$oplata=$oplata.':'.join(',', array_keys($t_order_last1));	

if(!empty($tr_order_last1))$oplata=$oplata.':'.join(',', array_keys($tr_order_last1));

if(!empty($tr_dop_order_last1))$oplata=$oplata.':'.join(',', array_keys($tr_dop_order_last1));

if(!empty($tr_nlk_last1))$oplata=$oplata.':'.join(',', array_keys($tr_nlk_last1));

if(!empty($t_order_last2)||!empty($tr_order_last2)||!empty($tr_dop_order_last2)||!empty($tr_nlk_last2))$oplata=$oplata.' '.$last_last_month_name;

if(!empty($t_order_last2))$oplata=$oplata.':'.join(',', array_keys($t_order_last2));

if(!empty($tr_order_last2))$oplata=$oplata.':'.join(',', array_keys($tr_order_last2));

if(!empty($tr_dop_order_last2))$oplata=$oplata.':'.join(',', array_keys($tr_dop_order_last2));

if(!empty($tr_nlk_last2))$oplata=$oplata.':'.join(',', array_keys($tr_nlk_last2));


$aSheet->setCellValue('B29','Оплаченные заявки прошлых периодов');
$aSheet->getStyle('B29')->applyFromArray($boldFont);
$aSheet->setCellValue('B30',$oplata);
$aSheet->mergeCells('B30:G30');
$aSheet->getRowDimension('30')->setRowHeight(50);
$aSheet->getStyle('B30')->getAlignment()->setWrapText(true);
$aSheet->getStyle('B30')->applyFromArray($top);
}



if($s!=0) {if (!empty($tr_dop_order_nlk))$cell=17; else $cell=16;}  else $cell=6;

	
	
$aSheet->setCellValue('B'.$cell,"Оклад: ");
$aSheet->getStyle('B'.$cell)->applyFromArray($boldFont)->applyFromArray($right);


$aSheet->setCellValue('C'.$cell,'=ROUND(F4*E4/D4,1)');
$aSheet->getStyle('C'.$cell)->applyFromArray($boldFont)->applyFromArray($right);

$aSheet->setCellValue('D'.$cell,"руб.");
$aSheet->setCellValue('B'.($cell+1),"Премия: ");
$aSheet->getStyle('B'.($cell+1))->applyFromArray($boldFont)->applyFromArray($right);
$aSheet->setCellValue('C'.($cell+1),0);
$aSheet->setCellValue('D'.($cell+1),"руб.");


if((int)$_GET['month']>=1&&(int)$_GET['month']<=9){
$date_start=(int)$_GET['year']."-0".(int)$_GET['month']."-01";
$date_end=(int)$_GET['year']."-0".(int)$_GET['month']."-31";
} else {
$date_start=(int)$_GET['year']."-".(int)$_GET['month']."-01";
$date_end=(int)$_GET['year']."-".(int)$_GET['month']."-31";
}

$aSheet->setCellValue('C'.($cell+4),"0");
$aSheet->setCellValue('C'.($cell+5),"0");

$query_zpay = "SELECT * FROM `zpay` WHERE `worker`='".(int)$row['id']."' AND DATE(`time`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."' ORDER BY `way` ASC";
$result_zpay = mysql_query($query_zpay) or die(mysql_error());
while($zbill= mysql_fetch_row($result_zpay)) {

if($zbill[2]==3){
if($zbill[4]!=0) $aSheet->setCellValue('C'.($cell+5),$zbill[4]/100);

}

if($zbill[2]==2)$aSheet->setCellValue('C'.($cell+1),$zbill[4]/100);

if($zbill[2]==4){if($zbill[4]!=0) $aSheet->setCellValue('C'.($cell+4),$zbill[4]/100);}

}


$aSheet->setCellValue('B'.($cell+3),"Итого: ");
$aSheet->getStyle('B'.($cell+3))->applyFromArray($hFont)->applyFromArray($right);

$aSheet->setCellValue('B'.($cell+4),"Зачислено на карту: ");
$aSheet->getStyle('B'.($cell+4))->applyFromArray($right);

$aSheet->setCellValue('D'.($cell+4),"руб.");

$aSheet->setCellValue('B'.($cell+5),"Выдан аванс: ");
$aSheet->getStyle('B'.($cell+5))->applyFromArray($right);

$aSheet->setCellValue('D'.($cell+5),"руб.");

$aSheet->setCellValue('B'.($cell+6),"Удержания: ");
$aSheet->getStyle('B'.($cell+6))->applyFromArray($right);

$aSheet->setCellValue('C'.($cell+6),"0");
$aSheet->setCellValue('D'.($cell+6),"руб.");
$aSheet->setCellValue('B'.($cell+7),"К выдаче: ");
$aSheet->getStyle('B'.($cell+7))->applyFromArray($hFont)->applyFromArray($right);

$aSheet->setCellValue('C'.($cell+7),'=ROUND(C'.($cell+3).'-C'.($cell+4).'-C'.($cell+5).'-C'.($cell+6).', 2)');
$aSheet->getStyle('C'.($cell+7))->applyFromArray($hFont)->applyFromArray($right);

$aSheet->setCellValue('D'.($cell+7),"руб.");

if($row['group']==3)$aSheet->setCellValue('C'.($cell+3),'=ROUND(C'.$cell.'+C'.($cell+1).'+C11+C12+C14+C15+C13+C10-G4-C'.($cell+2).', 2)'); else $aSheet->setCellValue('C'.($cell+3),'=C'.$cell.'+C'.($cell+1).'-G4-C'.($cell+2));
$aSheet->getStyle('C'.($cell+3))->applyFromArray($hiFont)->applyFromArray($right);
$aSheet->setCellValue('D'.($cell+3)," руб.");
$aSheet->getStyle('A'.($cell+2).':G'.($cell+2))->applyFromArray($hr);
$aSheet->getStyle('A'.($cell+4).':G'.($cell+4))->applyFromArray($hr_dot);
$aSheet->getColumnDimension('B')->setWidth(35);
$aSheet->getColumnDimension('C')->setWidth(20);
$aSheet->getColumnDimension('D')->setWidth(12);
$aSheet->getColumnDimension('E')->setWidth(12);
$aSheet->getColumnDimension('F')->setWidth(12);
$aSheet->getColumnDimension('G')->setWidth(12);
//$aSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$aSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$aSheet->getPageSetup()->setPrintArea('A1:G30');
$aSheet->getPageSetup()->setFitToPage(true);
$aSheet->getPageSetup()->setFitToWidth(1);
$aSheet->getPageSetup()->setFitToHeight(0);
$i++;
$f++;


}


//устанавливаем ширину

//отдаем пользователю в браузер
include("PHPExcel/Writer/Excel5.php");
$pExcel->setActiveSheetIndex(0);
$pExcel->removeSheetByIndex($i);


$objWriter = new PHPExcel_Writer_Excel5($pExcel);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Зарплата_'.$q[$_GET['month']].'_'.$_GET['year'].'.xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');



} else echo 'Не выбран отчетный месяц!';
?> 

     			
