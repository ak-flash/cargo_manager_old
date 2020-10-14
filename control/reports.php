<?php
set_time_limit(0);
session_start();
if (isset($_SESSION['user_id']))
{
if($_SESSION["group"]==1||$_SESSION["group"]==2||$_SESSION["group"]==4){


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
$mFont = array(
	'font'=>array(
		'name'=>'Arial Cyr',
		'size'=>'8',
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


$query_clients = "SELECT `id`,`name` FROM `clients`";
$result_clients = mysql_query($query_clients) or die(mysql_error());
while($clients = mysql_fetch_row($result_clients)) {
$client[$clients[0]]= $clients[1];
}

$query_tr = "SELECT `id`,`name` FROM `transporters`";
$result_tr = mysql_query($query_tr) or die(mysql_error());
while($tr = mysql_fetch_row($result_tr)) {
$transporters[$tr[0]]= $tr[1];
}

$query_cont = "SELECT `id`,`name` FROM `company`";
$result_cont = mysql_query($query_cont) or die(mysql_error());
while($cont = mysql_fetch_row($result_cont)) {
$company[$cont[0]]= $cont[1];
}

$query_adress = "SELECT `id`,`city` FROM `adress`";
$result_adress = mysql_query($query_adress) or die(mysql_error());
while($adress = mysql_fetch_row($result_adress)) {
$adresses[$adress[0]]= $adress[1];
}

$query_user = "SELECT `id`,`name` FROM `workers`";
$result_user = mysql_query($query_user) or die(mysql_error());
while($user = mysql_fetch_row($result_user)) {

$pieces = explode(" ", $user[1]);
$name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";

$users[$user[0]]= $name;
}

$pExcel->setActiveSheetIndex(0);
$pExcel->createSheet();

$aSheet = $pExcel->getActiveSheet();
$aSheet->freezePane('D2');


$aSheet->setTitle("Отчет по заявкам");

$aSheet->setCellValue('A1',"№");
$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($boldFont);




if(@$_GET['mode_id']==6){
$aSheet->setCellValue('B1',"КЛИЕНТ");
$aSheet->getStyle('B1')->getAlignment()->setWrapText(true);
$aSheet->getStyle('B1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->setCellValue('C1',"Ставка К");
$aSheet->getStyle('C1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->setCellValue('D1',"Форма оплаты К");
$aSheet->getStyle('D1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('D1')->getAlignment()->setWrapText(true);
$aSheet->setCellValue('E1',"ПЕРЕВОЗЧИК");
$aSheet->getStyle('E1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->setCellValue('F1',"Ставка П");
$aSheet->getStyle('F1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->setCellValue('G1',"Форма оплаты П");
$aSheet->getStyle('G1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('G1')->getAlignment()->setWrapText(true);
$aSheet->setCellValue('H1',"НДС к уплате");
$aSheet->getStyle('H1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('H1')->getAlignment()->setWrapText(true);
$aSheet->setCellValue('I1',"Налог на прибыль к уплате");
$aSheet->getStyle('I1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('I1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('J1',"Итого");
$aSheet->getStyle('J1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('J1')->getAlignment()->setWrapText(true);
$aSheet->getStyle('A1:J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('EAEAEA');

$aSheet->getStyle('A1:H1')->applyFromArray($styleArray2);

} else {
$aSheet->setCellValue('A1',"Менеджер");
$aSheet->getStyle('A1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('A1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('B1',"№");
$aSheet->getStyle('B1')->getAlignment()->setWrapText(true);
$aSheet->getStyle('B1')->applyFromArray($center)->applyFromArray($boldFont);


$aSheet->setCellValue('C1',"№ заявки для перевозчика");
$aSheet->getStyle('C1')->getAlignment()->setWrapText(true);
$aSheet->getStyle('C1')->applyFromArray($center)->applyFromArray($boldFont);

$aSheet->setCellValue('D1',"Клиент");
$aSheet->getStyle('D1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->setCellValue('E1',"Маршрут");
$aSheet->getStyle('E1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('E1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('F1',"Дата загрузки");
$aSheet->getStyle('F1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('F1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('G1',"Месяц загрузки");
$aSheet->getStyle('G1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('G1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('H1',"№ а/м");
$aSheet->getStyle('H1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('H1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('I1',"№ прицепа");
$aSheet->getStyle('I1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('I1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('J1',"Перевозчик");
$aSheet->getStyle('J1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('J1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('K1',"Ставка перевозчика");
$aSheet->getStyle('K1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('K1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('L1',"НДС/без НДС");
$aSheet->getStyle('L1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('L1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('M1',"Дата получения док-в");
$aSheet->getStyle('M1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('M1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('N1',"планируемая/фактическая/оплата перевозчику, дата");
$aSheet->getStyle('N1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('N1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('O1',"Характер груза");
$aSheet->getStyle('O1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('O1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('P1',"вес (кг)");
$aSheet->getStyle('P1')->applyFromArray($center)->applyFromArray($boldFont);

$aSheet->setCellValue('Q1',"объем (м3)");
$aSheet->getStyle('Q1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('Q1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('R1',"Доп. Расходы на клиента");
$aSheet->getStyle('R1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('R1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('S1',"Расходы на грузчиков");
$aSheet->getStyle('S1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('S1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('T1',"Расход СБ");
$aSheet->getStyle('T1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('T1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('U1',"Ставка заказчика");
$aSheet->getStyle('U1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('U1')->getAlignment()->setWrapText(true);

	$aSheet->setCellValue('V1',"НДС/без НДС");
	$aSheet->getStyle('V1')->applyFromArray($center)->applyFromArray($boldFont);
	$aSheet->getStyle('V1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('W1',"Итого ставка заказчика");
$aSheet->getStyle('W1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('W1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('X1',"ПРИБЫЛЬ");
$aSheet->getStyle('X1')->applyFromArray($center)->applyFromArray($boldFont);

$aSheet->setCellValue('Y1',"Форма оплаты клиента, нал / б.нал");
$aSheet->getStyle('Y1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('Y1')->getAlignment()->setWrapText(true);
$aSheet->setCellValue('Z1',"Плательщик");
$aSheet->getStyle('Z1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->setCellValue('AA1',"№ счёта");
$aSheet->getStyle('AA1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->setCellValue('AB1',"Дата выставления счёта клиенту");
$aSheet->getStyle('AB1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('AB1')->getAlignment()->setWrapText(true);
$aSheet->setCellValue('AC1',"Счет выставлен от:");
$aSheet->getStyle('AC1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('AC1')->getAlignment()->setWrapText(true);
$aSheet->setCellValue('AD1',"Дата отправки док-ов клиенту");
$aSheet->getStyle('AD1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('AD1')->getAlignment()->setWrapText(true);
$aSheet->setCellValue('AE1',"Дата оплаты счёта клиентом");
$aSheet->getStyle('AE1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('AE1')->getAlignment()->setWrapText(true);
$aSheet->setCellValue('AF1',"Оплачено клиентом");
$aSheet->getStyle('AF1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('AF1')->getAlignment()->setWrapText(true);
$aSheet->setCellValue('AH1',"Оплачено перевозчику");
$aSheet->getStyle('AH1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('AH1')->getAlignment()->setWrapText(true);
$aSheet->setCellValue('AG1',"Долг клиента");
$aSheet->getStyle('AG1')->applyFromArray($center)->applyFromArray($boldFont);
$aSheet->getStyle('AG1')->getAlignment()->setWrapText(true);

$aSheet->getStyle('A1:AH1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('EAEAEA');

$aSheet->getStyle('A1:AH1')->applyFromArray($styleArray2);
$aSheet->getStyle('A1:AH1')->applyFromArray($mFont);

}

if($_GET['date_start']==""&&$_GET['date_end']==""){
if(@$_GET['mode_id']==1||@$_GET['mode_id']==6)$query = "SELECT * FROM `orders` ORDER BY `Id` DESC";

if(@$_GET['mode_id']==3)$query = "SELECT * FROM `orders` WHERE `client`='".mysql_escape_string($_GET['cl_all'])."' ORDER BY `Id` DESC";
if(@$_GET['mode_id']==4)$query = "SELECT * FROM `orders` WHERE `manager`='".mysql_escape_string($_GET['user'])."' OR `tr_manager`='".mysql_escape_string($_GET['user'])."' ORDER BY `Id` DESC";

if(@$_GET['mode_id']==5)$query = "SELECT * FROM `orders` WHERE `client` IN (".mysql_escape_string($_GET['group']).") ORDER BY `Id` DESC";


} else {
$start_elements  = explode("/",$_GET['date_start']);
$date_start=$start_elements[2]."-".$start_elements[1]."-".$start_elements[0];
$end_elements  = explode("/",$_GET['date_end']);
$date_end=$end_elements[2]."-".$end_elements[1]."-".$end_elements[0];

if((int)$_GET['group_cont']!=0)$group_cont=" AND `tr_cont`=".mysql_escape_string((int)$_GET['group_cont'])." "; else $group_cont="";

if(@$_GET['mode_id']==1)$query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."' ".$group_cont." ORDER BY `Id` DESC";

if(@$_GET['mode_id']==3)$query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' AND `client`='".mysql_escape_string($_GET['cl_all'])."'".$group_cont." ORDER BY `Id` DESC";

if(@$_GET['mode_id']==4)$query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' AND (`manager`='".mysql_escape_string($_GET['user'])."' OR `tr_manager`='".mysql_escape_string($_GET['user'])."')".$group_cont." ORDER BY `Id` DESC";

if(@$_GET['mode_id']==5)$query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' AND `client` IN (".mysql_escape_string($_GET['group']).")".$group_cont." ORDER BY `Id` DESC";

if(@$_GET['mode_id']==6)$query = "SELECT * FROM `orders` WHERE DATE(`data`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."' AND `transp`!='2' ORDER BY `Id` DESC";
}



$p=2;
$result = mysql_query($query) or die(mysql_error());
if (mysql_num_rows($result)>0){

while($row = mysql_fetch_array($result)) {

$str_in = explode('&',$row['in_adress']);
$res_in = $str_in[0];

$str_out = explode('&',$row['out_adress']);
$str_adr_out = (int)sizeof($str_out)-2;

$res_out = $str_out[$str_adr_out];
$pref_cl = '';

switch ($row['cl_pref']) {
case '1': $pref_cl='ООО';break;
case '2': $pref_cl='ОАО';break;
case '3': $pref_cl='ИП';break;
case '4': $pref_cl='ЗАО';break;
case '5': $pref_cl='';break;}
$pref_tr='';
switch ($row['tr_pref']) {
case '1': $pref_tr='ООО';break;
case '2': $pref_tr='ОАО';break;
case '3': $pref_tr='ИП';break;
case '4': $pref_tr='ЗАО';break;}

switch ($row['cl_nds']) {
case '0': $nds_cl='без НДС';break;
case '1': $nds_cl='с НДС';break;
case '2': $nds_cl='НАЛ';break;
}

switch ($row['tr_nds']) {
case '0': $nds_tr='без НДС';break;
case '1': $nds_tr='с НДС';break;
case '2': $nds_tr='НАЛ';break;
}


// Рассчёт комиссии из файла komission.php
$komissia = 0;


$komissia = komissia($row['cl_cash'],$row['cl_minus'],$row['cl_plus'],$row['cl_nds'],$row['tr_cash'],$row['tr_minus'],$row['tr_plus'],$row['tr_nds']);

$cash_ok=$komissia*100/(int)$row['cl_cash'];




if($row['date_in1']!="0000-00-00"&&$row['date_in1']!="1970-01-01"&&$row['date_in1']!="") {$date_in=date("d/m/Y",strtotime($row['date_in1']));$date_in_d=date("d",strtotime($row['date_in1']));$date_in_m=date("m",strtotime($row['date_in1']));} else $date_in="-";

$query_docs = "SELECT `cl_bill`,`date_tr_receve`,`date_add_bill`,`date_cl_receve`,`add_date_cl_sent` FROM `docs` WHERE `order`='".mysql_escape_string($row['id'])."'";
$result_docs = mysql_query($query_docs) or die(mysql_error());
$docs=mysql_fetch_row($result_docs);
if($docs[0]!="0"&&$docs[0]!="") $doc=$docs[0]; else $doc="-";

if($docs[1]!="1970-01-01"&&$docs[1]!="0000-00-00"&&$docs[1]!="") {
	$tr_event_date=date('d/m/Y', strtotime('+'.(int)$row['tr_tfpay'].' day', strtotime($docs[1])));
	$tr_docs_date_receive = date('d/m/Y', strtotime($docs[1]));
} else {
	$tr_event_date = "-";
	$tr_docs_date_receive = "-";
}

$cl_addbill_date="";

if($docs[2]!="1970-01-01"&&$docs[2]!="0000-00-00"&&$docs[2]!="") {$cl_addbill_date=$docs[2];$cl_addbill_date_p=date("d/m/Y",strtotime($docs[2]));} else {$cl_addbill_date="";$cl_addbill_date_p="-";}

	if($docs[3]!="1970-01-01"&&$docs[3]!="0000-00-00"&&$docs[3]!="") {
		$cl_docs_date_receive = date("d/m/Y",strtotime($docs[3]));
	} else {
		$cl_docs_date_receive = "-";
	}

	if($docs[4]!="1970-01-01"&&$docs[4]!="0000-00-00"&&$docs[4]!="") {
		$cl_docs_date_sent = date("d/m/Y",strtotime($docs[4]));
	} else {
		$cl_docs_date_sent = "-";
	}

$cl_pay=0;
$cl_pay_date="";

$query_pays = "SELECT `cash`,`date` FROM `pays` WHERE `order`='".mysql_escape_string($row['id'])."' AND `delete`='0' AND `appoint`='1' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$cl_pay=(int)$pay[0]+(int)$cl_pay;
$cl_pay_date=$pay[1];
}
if($cl_pay!="0") $cl_p=($cl_pay/100); else $cl_p="-";

// days
if($cl_addbill_date!=""&&$cl_pay_date!="") {
$elements  = explode("-",$cl_addbill_date);
$cl_addbill_date= mktime (0,0,0,$elements[1],$elements[2],$elements[0]);
$elements  = explode("-",$cl_pay_date);
$cl_pay_date_temp= mktime (0,0,0,$elements[1],$elements[2],$elements[0]);
$difference = ($cl_pay_date_temp - $cl_addbill_date);
$difference_cl_days = ($difference / 86400); //разница в днях
} else $difference_cl_days="-";

	if($cl_pay_date!="1970-01-01"&&$cl_pay_date!="0000-00-00"&&$cl_pay_date!="") {
		$cl_pay_date_show = date("d/m/Y",strtotime($cl_pay_date));
	} else {
		$cl_pay_date_show = "-";
	}


$tr_pay=0;

$query_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($row['id'])."' AND `delete`='0' AND `appoint`='2' AND `status`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {
$tr_pay=(int)$pay[0]+(int)$tr_pay;
}
if($tr_pay!="0") $tr_p=($tr_pay/100); else $tr_p="-";

if($cl_pay==$row['cl_cash']*100&&$tr_pay==$row['tr_cash']*100) $aSheet->getStyle('A'.$p.':AH'.$p)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('C7FFA3');


if($row['pretenzia']==1) $pretenzia='Претензия'; else $pretenzia='';

$aSheet->setCellValue('C'.$p,$row['id']);
	$aSheet->getStyle('C'.$p)->applyFromArray($center);



if(@$_GET['mode_id']==6){

$aSheet->setCellValue('B'.$p,$pref_cl.' '.$client[$row['client']]);

$aSheet->setCellValue('C'.$p,$row['cl_cash']);
	$aSheet->getStyle('C'.$p)->applyFromArray($center);

if($row['cl_nds']==1){$nds_cl_amount=round((((int)$row['cl_cash']*18)/118),2);$unnds_cl_amount=$row['cl_cash']-$nds_cl_amount;$nds_cl_text=" (".$nds_cl_amount.")";} else {$nds_cl_text="";$nds_cl_amount=0;$unnds_cl_amount=$row['cl_cash'];}

$aSheet->setCellValue('D'.$p,$nds_cl.$nds_cl_text);
	$aSheet->getStyle('D'.$p)->applyFromArray($center);

$aSheet->setCellValue('E'.$p,$pref_tr.' '.$transporters[$row['transp']]);

$aSheet->setCellValue('F'.$p,$row['tr_cash']);
	$aSheet->getStyle('F'.$p)->applyFromArray($center);

if($row['tr_nds']==1){$nds_tr_amount=round((((int)$row['tr_cash']*18)/118),2);$unnds_tr_amount=$row['tr_cash']-$nds_tr_amount;$nds_tr_text=" (".$nds_tr_amount.")";} else {$nds_tr_text="";$nds_tr_amount=0;$unnds_tr_amount=$row['tr_cash'];}

$aSheet->setCellValue('G'.$p,$nds_tr.$nds_tr_text);
	$aSheet->getStyle('G'.$p)->applyFromArray($center);

$aSheet->setCellValue('H'.$p,$nds_cl_amount-$nds_tr_amount);
	$aSheet->getStyle('H'.$p)->applyFromArray($center);


if(($unnds_cl_amount-$unnds_tr_amount)*0.2<0) $nalog_na_p=0; else $nalog_na_p=($unnds_cl_amount-$unnds_tr_amount)*0.2;
$aSheet->setCellValue('I'.$p,round($nalog_na_p,2));

	$aSheet->getStyle('I'.$p)->applyFromArray($center);

$aSheet->setCellValue('J'.$p,'=(H'.$p.'+I'.$p.')');
	$aSheet->getStyle('J'.$p)->applyFromArray($center);

} else {

$aSheet->setCellValue('A'.$p,$users[$row['manager']]);
	$aSheet->getStyle('A'.$p)->applyFromArray($center);

$aSheet->setCellValue('B'.$p,($p-1));
	$aSheet->getStyle('B'.$p)->applyFromArray($center);

$aSheet->setCellValue('E'.$p,$adresses[$res_in].' - '.$adresses[$res_out]);
	$aSheet->getStyle('E'.$p)->getAlignment()->setWrapText(true);
//$aSheet->getStyle('C'.$p)->applyFromArray($center);
//$aSheet->setCellValue('D'.$p,$adresses[$res_out]);


$aSheet->setCellValue('D'.$p,$pref_cl.' '.$client[$row['client']]);
	$aSheet->getStyle('D'.$p)->applyFromArray($center);

//$aSheet->setCellValue('F'.$p,$users[$row['manager']]);

$aSheet->setCellValue('F'.$p,$date_in_d);
	$aSheet->getStyle('F'.$p)->applyFromArray($center);

$_monthsList = array(
"01"=>"январь","02"=>"февраль","03"=>"март",
"04"=>"апрель","05"=>"май", "06"=>"июнь",
"07"=>"июль","08"=>"август","09"=>"сентябрь",
"10"=>"октябрь","11"=>"ноябрь","12"=>"декабрь");


$aSheet->setCellValue('G'.$p,$_monthsList[$date_in_m]);
	$aSheet->getStyle('G'.$p)->applyFromArray($center);

// Auto-car number
	$query_car = "SELECT * FROM `tr_autopark` WHERE `id`='".$row['tr_auto']."'";
	$result_car = mysql_query($query_car) or die(mysql_error());
	$car=mysql_fetch_array($result_car);

$aSheet->setCellValue('H'.$p,$car['car_number']);
	$aSheet->getStyle('H'.$p)->applyFromArray($center);

if($car['car_extra_number']=='') $car['car_extra_number'] == '-';

$aSheet->setCellValue('I'.$p,$car['car_extra_number']);
	$aSheet->getStyle('I'.$p)->applyFromArray($center);

$aSheet->setCellValue('M'.$p,$tr_docs_date_receive);
	$aSheet->getStyle('M'.$p)->applyFromArray($center);

$aSheet->setCellValue('O'.$p,$row['gruz']);
	$aSheet->getStyle('O'.$p)->applyFromArray($center);

$aSheet->setCellValue('S'.$p,$row['tr_gruz_worker']);
	$aSheet->getStyle('S'.$p)->applyFromArray($center);

$aSheet->setCellValue('P'.$p,$row['gr_v']);
	$aSheet->getStyle('P'.$p)->applyFromArray($center);

$aSheet->setCellValue('Q'.$p,$row['gr_m']);
	$aSheet->getStyle('Q'.$p)->applyFromArray($center);

$aSheet->setCellValue('T'.$p,$row['cl_rashod_sb']);
	$aSheet->getStyle('T'.$p)->applyFromArray($center);

$aSheet->setCellValue('V'.$p,$nds_cl);
	$aSheet->getStyle('V'.$p)->applyFromArray($center);
//$aSheet->setCellValue('L'.$p,$users[$row['tr_manager']]);

$aSheet->setCellValue('U'.$p,$row['cl_cash']);
	$aSheet->getStyle('U'.$p)->getNumberFormat()->setFormatCode('[Red][<1]#,##0;[>0]#,##0');
	$aSheet->getStyle('U'.$p)->applyFromArray($center);



//$aSheet->setCellValue('J'.$p,$cl_p);
//$aSheet->getStyle('J'.$p)->applyFromArray($center);

$aSheet->setCellValue('J'.$p,$pref_tr.' '.$transporters[$row['transp']]);
	$aSheet->getStyle('J'.$p)->applyFromArray($center);

$aSheet->setCellValue('L'.$p,$nds_tr);
	$aSheet->getStyle('L'.$p)->applyFromArray($center);

// ЕслиСпособ оплаты перевозчика = нал
if($nds_tr=='НАЛ') $tr_cash = $row['tr_cash']/(1-$row['tr_komissia']/100); else $tr_cash  = $row['tr_cash'];

$aSheet->setCellValue('K'.$p, $tr_cash);
	$aSheet->getStyle('K'.$p)->getNumberFormat()->setFormatCode('#,##0');
	$aSheet->getStyle('K'.$p)->applyFromArray($center);


$aSheet->setCellValue('AD'.$p,$tr_p);
	$aSheet->getStyle('AD'.$p)->applyFromArray($center);

$aSheet->setCellValue('N'.$p,$tr_event_date);
	$aSheet->getStyle('N'.$p)->applyFromArray($center);

$aSheet->setCellValue('R'.$p,$row['cl_rashod_na_cl']/(1-$row['cl_komissia']/100));
	$aSheet->getStyle('R'.$p)->applyFromArray($center);
	$aSheet->getStyle('R'.$p)->getNumberFormat()->setFormatCode('#,##0.00');

	// Calculate final cash amount
	if($nds_cl=='с НДС'&&$nds_tr=='с НДС') $formula_cash = '=U'.$p.'/1.2-K'.$p.'/1.2-R'.$p.'-T'.$p.'-S'.$p;
	if($nds_cl=='с НДС'&&$nds_tr=='без НДС') $formula_cash = '=U'.$p.'/1.2-K'.$p.'-R'.$p.'-T'.$p.'-S'.$p;
	if($nds_cl=='без НДС'&&$nds_tr=='с НДС') $formula_cash = '=U'.$p.'-K'.$p.'/1.2-R'.$p.'-T'.$p.'-S'.$p;
	if($nds_cl=='без НДС'&&$nds_tr=='без НДС') $formula_cash = '=U'.$p.'-K'.$p.'-R'.$p.'-T'.$p.'-S'.$p;
	if($nds_cl=='НАЛ'&&$nds_tr=='с НДС') $formula_cash = '=U'.$p.'/1.2-K'.$p.'-R'.$p.'-T'.$p.'-S'.$p;
	if($nds_cl=='с НДС'&&$nds_tr=='НАЛ') $formula_cash = '=U'.$p.'-K'.$p.'-R'.$p.'-T'.$p.'-S'.$p;
	if($nds_cl=='НАЛ'&&$nds_tr=='НАЛ') $formula_cash = '=U'.$p.'-K'.$p.'-R'.$p.'-T'.$p.'-S'.$p;
	if($nds_cl=='без НДС'&&$nds_tr=='НАЛ') $formula_cash = '=U'.$p.'-K'.$p.'-R'.$p.'-T'.$p.'-S'.$p;
	if($nds_cl=='НАЛ'&&$nds_tr=='без НДС') $formula_cash = '=U'.$p.'-K'.$p.'-R'.$p.'-T'.$p.'-S'.$p;


$aSheet->setCellValue('X'.$p,$formula_cash);
	$aSheet->getStyle('X'.$p)->getNumberFormat()->setFormatCode('[Red][<1]#,##0;[>0]#,##0');
	$aSheet->getStyle('X'.$p)->applyFromArray($center);

$aSheet->setCellValue('W'.$p,'=U'.$p.'-R'.$p.'-T'.$p.'-S'.$p);
	$aSheet->getStyle('W'.$p)->getNumberFormat()->setFormatCode('[Red][<1]#,##0;[>0]#,##0');
	$aSheet->getStyle('W'.$p)->applyFromArray($center);

$aSheet->setCellValue('AA'.$p,$doc);
	$aSheet->getStyle('AA'.$p)->applyFromArray($center);

$aSheet->setCellValue('AB'.$p,$cl_addbill_date_p);
	$aSheet->getStyle('AB'.$p)->applyFromArray($center);

$aSheet->setCellValue('Z'.$p, $company[$row['cl_cont']]);
	$aSheet->getStyle('Z'.$p)->applyFromArray($center);

$aSheet->setCellValue('AD'.$p,$cl_docs_date_sent);
	$aSheet->getStyle('AD'.$p)->applyFromArray($center);

$aSheet->setCellValue('AF'.$p,$cl_p);
	$aSheet->getStyle('AF'.$p)->applyFromArray($center);

$aSheet->setCellValue('AE'.$p, $cl_pay_date_show);
	$aSheet->getStyle('AE'.$p)->applyFromArray($center);

$aSheet->setCellValue('AH'.$p, $tr_p);
	$aSheet->getStyle('AH'.$p)->applyFromArray($center);

$aSheet->setCellValue('AG'.$p, '=W'.$p.'-'.(int)$cl_p);
	$aSheet->getStyle('AG'.$p)->getNumberFormat()->setFormatCode('#,##0');
	$aSheet->getStyle('AG'.$p)->applyFromArray($center);

//Мотивационная схема оплаты плана
//if($row['tr_manager']==$row['manager']||($_SESSION["group"]==1||$_SESSION["group"]==2))$aSheet->setCellValue('U'.$p,$komissia); else $aSheet->setCellValue('U'.$p,($komissia/2));


$aSheet->getStyle('U'.$p)->applyFromArray($center);

//$aSheet->setCellValue('R'.$p,round($cash_ok));
//$aSheet->getStyle('R'.$p)->applyFromArray($center);

//$aSheet->setCellValue('S'.$p,$difference_cl_days);
//$aSheet->getStyle('S'.$p)->applyFromArray($center);

//$aSheet->setCellValue('W'.$p,$company[$row['tr_cont']]);
//$aSheet->getStyle('W'.$p)->applyFromArray($center);

//$aSheet->setCellValue('AF'.$p,$pretenzia);
//$aSheet->getStyle('AF'.$p)->applyFromArray($center);
}
$p++;

}

$aSheet->setCellValue('W'.($p+1),"Итого:");
	$aSheet->getStyle('W'.($p+1))->applyFromArray($right)->applyFromArray($boldFont);

$aSheet->setCellValue('Y'.($p+1),$row['cl_currency']);

$aSheet->setCellValue('A'.($p+1),"Обработано: ".($p-2));
	$aSheet->getStyle('A'.($p+1))->applyFromArray($boldFont);
	$aSheet->mergeCells('A'.($p+1).':C'.($p+1));


if(@$_GET['mode_id']==6){
$aSheet->setCellValue('C'.($p+1),'=SUM(C2:C'.($p-1).')');
$aSheet->getStyle('C'.($p+1))->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)');

$aSheet->setCellValue('F'.($p+1),'=SUM(F2:F'.($p-1).')');
$aSheet->getStyle('F'.($p+1))->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)');

$aSheet->setCellValue('I'.($p+1),'=SUM(I2:I'.($p-1).')');
$aSheet->getStyle('I'.($p+1))->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)');

$aSheet->setCellValue('J'.($p+1),'=SUM(J2:J'.($p-1).')');
$aSheet->getStyle('J'.($p+1))->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)');

$aSheet->setCellValue('H'.($p+1),'=SUM(H2:H'.($p-1).')');
$aSheet->getStyle('H'.($p+1))->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0.0;[Red](#,##0.0)');
} else {
$aSheet->setCellValue('X'.($p+1),'=SUM(X2:X'.($p-1).')');
$aSheet->getStyle('X'.($p+1))->applyFromArray($center)->applyFromArray($boldFont)->getNumberFormat()->setFormatCode('#,##0;[Red](#,##0)');
}

$aSheet->getColumnDimension('A')->setWidth(15);


if(@$_GET['mode_id']==6){
$aSheet->getColumnDimension('B')->setWidth(30);
$aSheet->getColumnDimension('C')->setWidth(13);
$aSheet->getColumnDimension('D')->setWidth(20);
$aSheet->getColumnDimension('E')->setWidth(30);
$aSheet->getColumnDimension('F')->setWidth(13);
$aSheet->getColumnDimension('G')->setWidth(20);
$aSheet->getColumnDimension('H')->setWidth(13);
$aSheet->getColumnDimension('I')->setWidth(13);
$aSheet->getColumnDimension('J')->setWidth(13);
} else {
$aSheet->getColumnDimension('B')->setWidth(5);
$aSheet->getColumnDimension('C')->setWidth(10);
$aSheet->getColumnDimension('D')->setWidth(30);
$aSheet->getColumnDimension('E')->setWidth(30);
$aSheet->getColumnDimension('F')->setWidth(5);
$aSheet->getColumnDimension('G')->setWidth(10);
$aSheet->getColumnDimension('H')->setWidth(13);
$aSheet->getColumnDimension('I')->setWidth(10);
$aSheet->getColumnDimension('J')->setWidth(40);
$aSheet->getColumnDimension('K')->setWidth(10);
$aSheet->getColumnDimension('L')->setWidth(10);
$aSheet->getColumnDimension('M')->setWidth(13);
$aSheet->getColumnDimension('N')->setWidth(13);
$aSheet->getColumnDimension('O')->setWidth(20);
$aSheet->getColumnDimension('P')->setWidth(7);
$aSheet->getColumnDimension('Q')->setWidth(7);
$aSheet->getColumnDimension('R')->setWidth(10);
$aSheet->getColumnDimension('S')->setWidth(10);
$aSheet->getColumnDimension('T')->setWidth(10);
$aSheet->getColumnDimension('U')->setWidth(10);
$aSheet->getColumnDimension('V')->setWidth(10);
$aSheet->getColumnDimension('W')->setWidth(10);
$aSheet->getColumnDimension('X')->setWidth(10);
$aSheet->getColumnDimension('Y')->setWidth(15);
$aSheet->getColumnDimension('Z')->setWidth(23);
$aSheet->getColumnDimension('AA')->setWidth(10);
$aSheet->getColumnDimension('AB')->setWidth(15);
$aSheet->getColumnDimension('AC')->setWidth(15);
$aSheet->getColumnDimension('AD')->setWidth(15);
$aSheet->getColumnDimension('AE')->setWidth(15);
$aSheet->getColumnDimension('AF')->setWidth(15);
	$aSheet->getColumnDimension('AG')->setWidth(10);
	$aSheet->getColumnDimension('AH')->setWidth(15);
}
$aSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
//$aSheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
$aSheet->getPageSetup()->setPrintArea('A1:AH'.($p+1));
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



} else {echo 'НЕ найдено ни одной заявки, удовлетворяющей требованиям!';}


} else {echo "<b><h1>&nbsp;&nbsp;&nbsp;&nbsp;Доступ запрещен</h1></b>";}
} else {
header('Location: index.php');
	exit;
}
?>


