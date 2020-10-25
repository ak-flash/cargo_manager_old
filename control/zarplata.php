<?php
session_start();
set_time_limit(250);

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


include "../config.php";
// Load Composer's autoloader
require '../vendor/autoload.php';

function countDays($y, $m, $ignore = false)
{
    $result = 0;
    $loop = strtotime("$y-$m-01");
    do if (!$ignore or !in_array(strftime("%u", $loop), $ignore))
        $result++;
    while (strftime("%m", $loop = strtotime("+1 day", $loop)) == $m);
    return $result;
}

if(@$_GET['mode']=='zarplata'&&@$_GET['month']>0) {


    $pExcel = new Spreadsheet();


    $boldFont = array(
        'font' => array(
            'name' => 'Arial Cyr',
            'size' => '10',
            'bold' => true
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
	'alignment'=> array(
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
    )
);

$right = array(
	'alignment'=> array(
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
    )
);
$top = array(
	'alignment'=> array(
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP
    )
);

$styleArray = array(
'borders' => array(
	'allborders' => array(
        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM
    ),
	),
);
$styleArray2 = array(
'borders' => array(
	'allborders' => array(
        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
    ),
	),
);

$hr = array(
'borders' => array(
	'bottom' => array(
        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
    ),
	),
);
$hr_dot = array(
'borders' => array(
	'bottom' => array(
        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED
    ),
	),
);
    $i = 0;
    $query = "SELECT `id`,`name`,`group`,`zarplata`,`motive`,`ndfl` FROM `workers` WHERE `delete`='0' ORDER BY `id` ASC";
    $result = mysql_query($query) or die(mysql_error());

while($row = mysql_fetch_array($result)) {
$pieces = explode(" ", $row['name']);
$name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";

switch ($_GET['month']) {
case '1': $month='Январь';break;
case '2': $month='Февраль';break;
case '3': $month='Март';break;
    case '4':
        $month = 'Апрель';
        break;
    case '5':
        $month = 'Май';
        break;
    case '6':
        $month = 'Июнь';
        break;
    case '7':
        $month = 'Июль';
        break;
    case '8':
        $month = 'Август';
        break;
    case '9':
        $month = 'Сентябрь';
        break;
    case '10':
        $month = 'Октябрь';
        break;
    case '11':
        $month = 'Ноябрь';
        break;
    case '12':
        $month = 'Декабрь';
        break;
}

    $working_days = countDays($_GET['year'], $_GET['month'], array(6, 7));

    $pExcel->setActiveSheetIndex($i);
    $pExcel->createSheet();

    $aSheet = $pExcel->getActiveSheet();
    $aSheet->setTitle($name);
    // Добавляем новый документ

    switch ($row['group']) {
        case '1':
            $group = 'Администратор';
            break;
case '2': $group='Директор';break;
case '3': $group='Менеджер';break;
case '4': $group='Бухгалтер';break;
case '5': $group='Другое';break;
}        
$aSheet->setCellValue('B1','Зарплатная ведомость за '.$month.' месяц '.$_GET['year'].' года');
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
    $aSheet->setCellValue('D4', $working_days);
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

if($row['group']==3) {
    $total = 0;
    $total_tr = 0;
    $total_tr_dop = 0;
    $t_order = array();
    $tr_order = array();
    $tr_dop_order = array();
    $output = "";

    $manager = $row['id'];

    if ((int)$_GET['month'] >= 1 && (int)$_GET['month'] <= 9) {
        $date_start = (int)$_GET['year'] . "-0" . (int)$_GET['month'] . "-01";
        $date_end = (int)$_GET['year'] . "-0" . (int)$_GET['month'] . "-31";
    } else {
        $date_start = (int)$_GET['year'] . "-" . (int)$_GET['month'] . "-01";
        $date_end = (int)$_GET['year'] . "-" . (int)$_GET['month'] . "-31";
}



		
$query_total = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`client` FROM `orders` WHERE `manager`='".$manager."' AND `tr_manager`='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."'";
$result_total = mysql_query($query_total) or die(mysql_error());



$s=6;
while($row_t = mysql_fetch_array($result_total)) {
$cash=0;
$cl_cash_all=0;
$tr_cash_all=0;


$cl_cash_all=$row_t['cl_cash']-$row_t['cl_minus']+$row_t['cl_plus'];
$tr_cash_all=$row_t['tr_cash']+$row_t['tr_minus']-$row_t['tr_plus'];
$cl_nds=(int)$row_t['cl_nds'];
$tr_nds=(int)$row_t['tr_nds'];
    if ($cl_nds == 0 && $tr_nds == 0) $cash = $cl_cash_all - $tr_cash_all;
    if ($cl_nds == 1 && $tr_nds == 1) $cash = $cl_cash_all - $tr_cash_all;
    if ($cl_nds == 1 && $tr_nds == 0) $cash = $cl_cash_all - ($tr_cash_all + $tr_cash_all * 0.03);
    if ($cl_nds == 1 && $tr_nds == 2) $cash = $cl_cash_all - ($tr_cash_all + $tr_cash_all * 0.06);
    if ($cl_nds == 0 && $tr_nds == 1) $cash = $cl_cash_all - $tr_cash_all + $tr_cash_all * 0.03;
    if ($cl_nds == 0 && $tr_nds == 2) $cash = $cl_cash_all - ($tr_cash_all + $tr_cash_all * 0.03);
    if ($cl_nds == 2 && $tr_nds == 1) $cash = $cl_cash_all - $tr_cash_all + $tr_cash_all * 0.06;
    if ($cl_nds == 2 && $tr_nds == 0) $cash = $cl_cash_all - $tr_cash_all + $tr_cash_all * 0.03;
    if ($cl_nds == 2 && $tr_nds == 2) $cash = $cl_cash_all - $tr_cash_all;

    $t_order[$row_t['id']] = $cash;
    $total = (int)$total + (int)$cash;


    $s++;
}

$query_tr = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`client` FROM `orders` WHERE `manager`='".$manager."' AND `tr_manager`!='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."'";
$result_tr = mysql_query($query_tr) or die(mysql_error());


while($row_tr = mysql_fetch_array($result_tr)) {
$cash=0;
$cl_cash_all=0;
$tr_cash_all=0;

$cl_cash_all=$row_tr['cl_cash']-$row_tr['cl_minus']+$row_tr['cl_plus'];
$tr_cash_all=$row_tr['tr_cash']+$row_tr['tr_minus']-$row_tr['tr_plus'];
$cl_nds=$row_tr['cl_nds'];
$tr_nds=$row_tr['tr_nds'];
if($cl_nds==0&&$tr_nds==0)$cash=$cl_cash_all-$tr_cash_all;
if($cl_nds==1&&$tr_nds==1)$cash=$cl_cash_all-$tr_cash_all;
    if ($cl_nds == 1 && $tr_nds == 0) $cash = $cl_cash_all - ($tr_cash_all + $tr_cash_all * 0.03);
    if ($cl_nds == 1 && $tr_nds == 2) $cash = $cl_cash_all - ($tr_cash_all + $tr_cash_all * 0.06);
    if ($cl_nds == 0 && $tr_nds == 1) $cash = $cl_cash_all - $tr_cash_all + $tr_cash_all * 0.03;
    if ($cl_nds == 0 && $tr_nds == 2) $cash = $cl_cash_all - ($tr_cash_all + $tr_cash_all * 0.03);
    if ($cl_nds == 2 && $tr_nds == 1) $cash = $cl_cash_all - $tr_cash_all + $tr_cash_all * 0.06;
    if ($cl_nds == 2 && $tr_nds == 0) $cash = $cl_cash_all - $tr_cash_all + $tr_cash_all * 0.03;
    if ($cl_nds == 2 && $tr_nds == 2) $cash = $cl_cash_all - $tr_cash_all;


//$t_order=$row_tr['id']." (".$cash."р - п), ".$t_order;
    $tr_order[$row_tr['id']] = $cash;
    $total_tr = (int)$total_tr + (int)$cash;

}

$query_tr_dop = "SELECT `id`,`cl_cash`,`cl_minus`,`cl_plus`,`cl_nds`,`tr_cash`,`tr_minus`,`tr_plus`,`tr_nds`,`client` FROM `orders` WHERE `manager`!='".$manager."' AND `tr_manager`='".$manager."' AND DATE(`data`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."'";
$result_tr_dop = mysql_query($query_tr_dop) or die(mysql_error());

while($row_tr_dop = mysql_fetch_array($result_tr_dop)) {
$cash=0;
$cl_cash_all=0;
$tr_cash_all=0;

$cl_cash_all=$row_tr_dop['cl_cash']-$row_tr_dop['cl_minus']+$row_tr_dop['cl_plus'];
$tr_cash_all=$row_tr_dop['tr_cash']+$row_tr_dop['tr_minus']-$row_tr_dop['tr_plus'];
$cl_nds=$row_tr_dop['cl_nds'];
$tr_nds=$row_tr_dop['tr_nds'];
if($cl_nds==0&&$tr_nds==0)$cash=$cl_cash_all-$tr_cash_all;
if($cl_nds==1&&$tr_nds==1)$cash=$cl_cash_all-$tr_cash_all;
if($cl_nds==1&&$tr_nds==0)$cash=$cl_cash_all-($tr_cash_all+$tr_cash_all*0.03);
if($cl_nds==1&&$tr_nds==2)$cash=$cl_cash_all-($tr_cash_all+$tr_cash_all*0.06);
if($cl_nds==0&&$tr_nds==1)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*0.03;
if($cl_nds==0&&$tr_nds==2)$cash=$cl_cash_all-($tr_cash_all+$tr_cash_all*0.03);
if($cl_nds==2&&$tr_nds==1)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*0.06;
if($cl_nds==2&&$tr_nds==0)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*0.03;
if($cl_nds==2&&$tr_nds==2)$cash=$cl_cash_all-$tr_cash_all;

//$t_order=$row_tr_dop['id']." (".$cash."р - п.п), ".$t_order;
if($row_tr_dop['client']!=16&&$row_tr_dop['client']!=76){$tr_dop_order[$row_tr_dop['id']]=$cash;$total_tr_dop=(int)$total_tr_dop+(int)$cash;}


}

if(((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2))<100000){$zarp=((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2))*0.1;$proc=" (10%)";$pr=0.1;}
if(((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2))>=100000){$zarp=((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2))*0.15;$proc=" (15%)";$pr=0.15;}

$aSheet->mergeCells('A6:G6');
$aSheet->getRowDimension('6')->setRowHeight($s*2);
$aSheet->getStyle('A6')->getAlignment()->setWrapText(true);
//$aSheet->setCellValue('A6',substr_replace($t_order ,"",-2));

if (!empty($t_order)) {
    foreach ($t_order as $index => $value) $output .= $index . "(" . ($value) . "р), ";
}
if (!empty($tr_order))
{foreach ($tr_order as $index=>$value) $output.= $index."(".($value/2)."р - п.), ";}
if (!empty($tr_dop_order))
{foreach ($tr_dop_order as $index=>$value) $output.= $index."(".($value/2)."р - п.п.), ";}


$aSheet->setCellValue('A6',substr_replace($output ,"",-2));
$aSheet->getStyle('A6')->applyFromArray($top); 

$aSheet->setCellValue('B8',"Переменная часть: ");
$aSheet->getStyle('B8')->applyFromArray($boldFont)->applyFromArray($right);




$aSheet->setCellValue('C8',$zarp);
$aSheet->setCellValue('D8'," руб.".$proc);
$aSheet->setCellValue('E8',"(Выполнение: ".((int)$total+((int)$total_tr/2)+((int)$total_tr_dop/2))." руб.)");
}


    if ($s != 0) $cell = 10; else $cell = 6;

	
	
$aSheet->setCellValue('B'.$cell,"Оклад: ");
$aSheet->getStyle('B'.$cell)->applyFromArray($boldFont)->applyFromArray($right);


$aSheet->setCellValue('C'.$cell,'=ROUND(F4*E4/D4,1)');


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
$query_zpay = "SELECT * FROM `zpay` WHERE `worker`='".(int)$row['id']."' AND DATE(`time`) BETWEEN '".mysql_escape_string($date_start)."' AND '".mysql_escape_string($date_end)."' ORDER BY `way` ASC";
$result_zpay = mysql_query($query_zpay) or die(mysql_error());
while($zbill= mysql_fetch_row($result_zpay)) {

if($zbill[2]==3){$aSheet->setCellValue('B'.($cell+2),"Аванс: ");
$aSheet->getStyle('B'.($cell+2))->applyFromArray($boldFont)->applyFromArray($right);
$aSheet->setCellValue('C'.($cell+2),$zbill[4]/100);
$aSheet->setCellValue('D'.($cell+2),"руб.");}

if($zbill[2]==2)$aSheet->setCellValue('C'.($cell+1),$zbill[4]/100);
}


$aSheet->setCellValue('B'.($cell+3),"Итого: ");
$aSheet->getStyle('B'.($cell+3))->applyFromArray($hFont)->applyFromArray($right);



if($row['group']==3)$aSheet->setCellValue('C'.($cell+3),'=C'.$cell.'+C'.($cell+1).'+C9+C8-G4-C'.($cell+2)); else $aSheet->setCellValue('C'.($cell+3),'=C'.$cell.'+C'.($cell+1).'-G4-C'.($cell+2));
    $aSheet->getStyle('C' . ($cell + 3))->applyFromArray($hiFont)->applyFromArray($right);
    $aSheet->setCellValue('D' . ($cell + 3), " руб.");
    $aSheet->getStyle('A' . ($cell + 2) . ':G' . ($cell + 2))->applyFromArray($hr);
    $aSheet->getStyle('A' . ($cell + 4) . ':G' . ($cell + 4))->applyFromArray($hr_dot);
    $aSheet->getColumnDimension('B')->setWidth(35);
    $aSheet->getColumnDimension('C')->setWidth(20);
    $aSheet->getColumnDimension('D')->setWidth(12);
    $aSheet->getColumnDimension('E')->setWidth(12);
    $aSheet->getColumnDimension('F')->setWidth(12);
    $aSheet->getColumnDimension('G')->setWidth(12);
//$aSheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
    $aSheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    $aSheet->getPageSetup()->setPrintArea('A1:G25');
    $aSheet->getPageSetup()->setFitToPage(true);
    $aSheet->getPageSetup()->setFitToWidth(1);
    $aSheet->getPageSetup()->setFitToHeight(0);
    $i++;
    $f++;
}


    $pExcel->setActiveSheetIndex(0);
    $pExcel->removeSheetByIndex($i);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

    header('Content-Disposition: attachment;filename="Зарплата_' . $month . '_' . $_GET['year'] . '.xlsx"');

    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($pExcel, 'Xlsx');
    $writer->save('php://output');


} else echo 'Не выбран отчетный месяц!';