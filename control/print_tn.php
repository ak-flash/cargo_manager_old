<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



$DOCS_TEMPLATES_DIR = 'docs_templates/';

function convert($temp)
{
    return iconv("UTF-8", "windows-1251", $temp);
}



if($_GET['mode']=="tn"){
	
	$id = base64_decode($_GET['id']);

	include "../config.php";
	require '../vendor/autoload.php';

	//load spreadsheet
	$spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($DOCS_TEMPLATES_DIR.'tn.xlsx');



	$query = "SELECT *, o.id, o.data, o.in_adress, o.out_adress, t.pref as tr_pref, t.name as tr_name, t.tr_adr_f as tr_adress, c.pref as cl_pref, c.name as cl_name, c.cl_adr_f as cl_adress  FROM orders AS o LEFT JOIN clients AS c ON o.client=c.id LEFT JOIN transporters AS t ON o.transp=t.id LEFT JOIN tr_autopark ta ON o.tr_auto=ta.id WHERE o.id='".(int)$id."'";
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($result);

	$pref_cl='';
	switch ($row['cl_pref']) {
		case '1': $pref_cl='ООО';break;
		case '2': $pref_cl='ОАО';break;
		case '3': $pref_cl='ИП';break;
		case '4': $pref_cl='ЗАО';break;
		case '5': $pref_cl='';break;
	}

	$pref_tr='';
	switch ($row['tr_pref']) {
		case '1': $pref_tr='ООО';break;
		case '2': $pref_tr='ОАО';break;
		case '3': $pref_tr='ИП';break;
		case '4': $pref_tr='ЗАО';break;
		case '5': $pref_cl='';break;
	}
	
	$adressIn = explode('&', $row['in_adress']);
	$adressOut = explode('&', $row['out_adress']);
	
	$adresses = array();

	$query_adress = "SELECT * FROM adress WHERE id IN (".implode(',', $adressIn).$row['cl_adress'].", ".implode(',', $adressOut).$row['tr_adress'].")";
	$result_adress = mysql_query($query_adress) or die(mysql_error());
	while($adress = mysql_fetch_array($result_adress)) {
		$postcode = $adress['postcode'] ? $adress['postcode'].', ' : '';
		$flat = $adress['flat'] ? ', офис '.$adress['flat'] : '';
		$domExtra = $adress['dom_extra'] ? ', строение '.$adress['dom_extra'] : '';

		$adresses[$adress['id']] = $postcode.$adress['city'].', '.$adress['street'].' ул., дом '.$adress['dom'].$domExtra.$flat;
	}
	//var_dump($adresses);
	
	//page 1
	$spreadsheet->setActiveSheetIndex(0);
	$sheet = $spreadsheet->getActiveSheet();
	
	$sheet->setCellValueByColumnAndRow(37, 9, $row['id']);
	$sheet->setCellValueByColumnAndRow(93, 9, $row['id']);

	$sheet->setCellValueByColumnAndRow(7, 9, date("d/m/Y",strtotime($row['data'])));
	$sheet->setCellValueByColumnAndRow(63, 9, date("d/m/Y",strtotime($row['data'])));

// Transporter
	$tranporter = $pref_tr.' «'.$row['tr_name'].'», '.$adresses[$row['tr_adress']].', ИНН/КПП: '.$row['tr_inn'].'/'.$row['tr_kpp'].', ОГРН: '.$row['tr_ogrn'].', Р/с: '.$row['tr_rs'].' в '.$row['tr_bank'].', К/с: '.$row['tr_ks'].', БИК: '.$row['tr_bik'].', Телефон/факс: '.$row['tr_phone'];


	$sheet->setCellValueByColumnAndRow(2, 14, $tranporter);



// Client
	$client = $pref_cl.' «'.$row['cl_name'].'», '.$adresses[$row['cl_adress']].', ИНН/КПП: '.$row['cl_inn'].'/'.$row['cl_kpp'].', ОГРН: '.$row['cl_ogrn'].', Р/с: '.$row['cl_rs'].' в '.$row['cl_bank'].', К/с: '.$row['cl_ks'].', БИК: '.$row['cl_bik'].', Телефон/факс: '.$row['cl_phone'];


	$sheet->setCellValueByColumnAndRow(2, 20, $client);


	$gruz_number = $row['gr_number'] ? $row['gr_number'].' шт. ' : '';
	
	$sheet->setCellValueByColumnAndRow(2, 24, $row['gruz']);
	$sheet->setCellValueByColumnAndRow(2, 45, $gruz_number.$row['gr_m'].' т, '.$row['gr_v'].' м3');


	foreach ($adressIn as $key) {
		$inAdressOutput .= ' '.$adresses[$key];
	}

	$sheet->setCellValueByColumnAndRow(2, 53, $client);
	$sheet->setCellValueByColumnAndRow(2, 55, $inAdressOutput);

	foreach ($adressOut as $key) {
		$outAdressOutput .= ' '.$adresses[$key];
	}

	//$sheet->setCellValueByColumnAndRow(58, 53, $client);
	$sheet->setCellValueByColumnAndRow(58, 55, $outAdressOutput);



// Page 2
	$spreadsheet->setActiveSheetIndex(1);
	$sheet2 = $spreadsheet->getActiveSheet();

	$sheet2->setCellValueByColumnAndRow(2, 7, $tranporter);

	$sheet2->setCellValueByColumnAndRow(3, 47, $client);
	$sheet2->setCellValueByColumnAndRow(58, 47, $tranporter);


	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ТН_№'.$row['id'].'.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
}