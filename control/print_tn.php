<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

include "../config.php";

$id = base64_decode($_GET['id']);
	
$DOCS_TEMPLATES_DIR = 'docs_templates/';


function getCompanyPrefix($prefix)
{
	switch ($prefix) {
		case '1': $pref = 'ООО'; break;
		case '2': $pref = 'ОАО'; break;
		case '3': $pref = 'ИП'; break;
		case '4': $prefr = 'ЗАО'; break;
		case '5': $pref = ''; break;
	}

	return $pref;
}

if($_GET['mode']=="load_adress"){

	$query = "SELECT in_adress, out_adress FROM orders WHERE id='".(int)$id."'";
	
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($result);

	$adressIn = explode('&', $row['in_adress']);
	$adressIn = array_filter($adressIn);

	$adressOut = explode('&', $row['out_adress']);
	$adressOut = array_filter($adressOut);

	$adresses = array();
	


	$query_adress = "SELECT * FROM adress WHERE id IN (".implode(',', $adressIn).", ".implode(',', $adressOut).")";
	$result_adress = mysql_query($query_adress) or die(mysql_error());
	while($adress = mysql_fetch_array($result_adress)) {
		
		
		$adresses[$adress['id']] = $adress['obl'].', '.$adress['city'].', '.$adress['street'].' ул., дом '.$adress['dom'];
		
	}

	echo '<table cellpadding="5">
	<tr>
	<td>
		Адрес <b>загрузки</b>
	</td>
	<td>
	    <select name="tnAdressIn" id="tnAdressIn" class="select" style="width: 300px;">';
	        
	    
		foreach ($adressIn as $key => $value) {
			echo '<option value="'.$value.'">'.$adresses[$value].'</option>';
		}
		
		echo '</select>
	</td>
	</tr>
	<tr>
		<td>
	    	Адрес <b>выгрузки</b>
	    </td>
		<td>
	    <select name="tnAdressOut" id="tnAdressOut" class="select" style="width: 300px;">';

		    foreach ($adressOut as $key => $value) {
				echo '<option value="'.$value.'">'.$adresses[$value].'</option>';
			}

	    echo '</select>
	    </td>
    </tr>
    </table>';
}



if($_GET['mode']=="tn"){
	
	
	require '../vendor/autoload.php';

	//load spreadsheet
	$spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($DOCS_TEMPLATES_DIR.'tn.xlsx');



	$query = "SELECT *, o.id, o.data, o.in_adress, o.out_adress, t.pref as tr_pref, t.name as tr_name, t.tr_adr_f as tr_adress, t.tr_chief as tr_chief, cl.pref as cl_pref, cl.name as cl_name, cl.cl_adr_f as cl_adress, ta.car_driver_name, ta.car_driver_inn, ta.car_driver_phone, ta.car_name, ta.car_number, ta.car_v, ta.car_m, ta.car_owner_type, c.pref as company_pref, c.name as company_name, c.adr_f as company_adress  FROM orders AS o LEFT JOIN clients AS cl ON o.client=cl.id LEFT JOIN company AS c ON o.cl_cont=c.id LEFT JOIN transporters AS t ON o.transp=t.id LEFT JOIN tr_autopark ta ON o.tr_auto=ta.id WHERE o.id='".(int)$id."'";
	
	$result = mysql_query($query) or die(mysql_error());
	$row = mysql_fetch_array($result);

	
	
	$adressIn = (int)$_GET['adress_in'];
	$adressOut = (int)$_GET['adress_out'];
	
	$adresses = array();
	$adr_places = array();

	$query_adress = "SELECT * FROM adress WHERE id IN (".$adressIn.", ".$row['cl_adress'].", ".$adressOut.", ".$row['tr_adress'].")";
	$result_adress = mysql_query($query_adress) or die(mysql_error());
	while($adress = mysql_fetch_array($result_adress)) {
		$postcode = $adress['postcode'] ? $adress['postcode'].', ' : '';
		$flat = $adress['flat'] ? ', офис '.$adress['flat'] : '';
		$domExtra = $adress['dom_extra'] ? ', строение '.$adress['dom_extra'] : '';

		$adresses[$adress['id']] = $postcode.$adress['city'].', '.$adress['street'].' ул., дом '.$adress['dom'].$domExtra.$flat;

		if(!empty($adress['adr_place'])){
			$adr_places[$adress['id']] = $adress['adr_place'];	
		}

		if(!empty($adress['adr_place_info'])){
			$adr_places_info[$adress['id']] = $adress['adr_place_info'];	
		}
		
	}
	//var_dump($adr_places);
	
	//page 1
	$spreadsheet->setActiveSheetIndex(0);
	$sheet = $spreadsheet->getActiveSheet();
	
	$sheet->setCellValueByColumnAndRow(37, 9, $row['id']);
	$sheet->setCellValueByColumnAndRow(93, 9, $row['id']);

	$sheet->setCellValueByColumnAndRow(7, 9, date("d/m/Y",strtotime($row['data'])));
	$sheet->setCellValueByColumnAndRow(63, 9, date("d/m/Y",strtotime($row['data'])));


	// Company info
	$pref_company = getCompanyPrefix($row['company_pref']);

	$company = $pref_company.' «'.$row['company_name'].'», '.$row['company_adress'].', ИНН/КПП: '.$row['inn'].'/'.$row['kpp'].', ОГРН: '.$row['ogrn'].', Р/с: '.$row['rs'].' в '.$row['bank'].', К/с: '.$row['ks'].', БИК: '.$row['bik'].', Телефон/факс: +7 ('.$row['pref_phone'].') '.$row['phone'];

	$sheet->setCellValueByColumnAndRow(2, 14, $company);
	$sheet->setCellValueByColumnAndRow(2, 53, $company);


	// Transporter
	$pref_tr = getCompanyPrefix($row['tr_pref']);

	$tranporter = $pref_tr.' «'.$row['tr_name'].'», '.$adresses[$row['tr_adress']].', ИНН/КПП: '.$row['tr_inn'].'/'.$row['tr_kpp'].', ОГРН: '.$row['tr_ogrn'].', Р/с: '.$row['tr_rs'].' в '.$row['tr_bank'].', К/с: '.$row['tr_ks'].', БИК: '.$row['tr_bik'].', Телефон/факс: +7 ('.$row['tr_pref_phone'].') '.$row['tr_phone'];
	
	$tranporterChief = $row['tr_chief'];

	//$sheet->setCellValueByColumnAndRow(2, 14, $tranporter);
	


	// Client
	$pref_cl = getCompanyPrefix($row['cl_pref']);

	$client = $pref_cl.' «'.$row['cl_name'].'», '.$adresses[$row['cl_adress']].', ИНН/КПП: '.$row['cl_inn'].'/'.$row['cl_kpp'].', ОГРН: '.$row['cl_ogrn'].', Р/с: '.$row['cl_rs'].' в '.$row['cl_bank'].', К/с: '.$row['cl_ks'].', БИК: '.$row['cl_bik'].', Телефон/факс: +7 ('.$row['cl_pref_phone'].') '.$row['cl_phone'];


	$sheet->setCellValueByColumnAndRow(2, 20, $client);
	


	//$gruz_number = $row['gr_number'] ? $row['gr_number'].' шт. ' : '';
	$gruz_number = $row['gr_number']==0 ? '':$row['gr_number'];

	$sheet->setCellValueByColumnAndRow(2, 24, $row['gruz']);
	$sheet->setCellValueByColumnAndRow(2, 27, $gruz_number);
	$sheet->setCellValueByColumnAndRow(2, 30, $row['gr_m'].' т, '.$row['gr_v'].' м3');

	$sheet->setCellValueByColumnAndRow(30, 63, $gruz_number);
	$sheet->setCellValueByColumnAndRow(86, 63, $gruz_number);

	$sheet->setCellValueByColumnAndRow(2, 63, $row['gr_m'].' т');
	$sheet->setCellValueByColumnAndRow(58, 63, $row['gr_m'].' т');


	$sheet->setCellValueByColumnAndRow(2, 45, $row['car_m'].' т, '.$row['car_v'].' м3');

	$sheet->setCellValueByColumnAndRow(2, 69, $row['car_driver_name']);
	$sheet->setCellValueByColumnAndRow(58, 69, $row['car_driver_name']);


	$sheet->setCellValueByColumnAndRow(2, 55, $adresses[$adressIn]);



	//$sheet->setCellValueByColumnAndRow(58, 53, $client);
	$sheet->setCellValueByColumnAndRow(58, 55, $adresses[$adressOut]);

	$sheet->setCellValueByColumnAndRow(58, 14, $adr_places[$adressOut].' '.$adr_places_info[$adressOut]);
	$sheet->setCellValueByColumnAndRow(58, 53, $adr_places[$adressOut].' '.$adr_places_info[$adressOut]);


    $sheet->getPageSetup()->setFitToPage(true);

// Page 2
	$spreadsheet->setActiveSheetIndex(1);
	$sheet2 = $spreadsheet->getActiveSheet();

	$sheet2->setCellValueByColumnAndRow(2, 7, $tranporter);

	$sheet2->setCellValueByColumnAndRow(3, 47, $company);

	$sheet2->setCellValueByColumnAndRow(58, 47, $tranporter);

	$sheet2->setCellValueByColumnAndRow(2, 9, $row['car_driver_name']);
	$sheet2->setCellValueByColumnAndRow(49, 9, 'ИНН '.$row['car_driver_inn']);
	$sheet2->setCellValueByColumnAndRow(80, 9, $row['car_driver_phone']);

	$sheet2->setCellValueByColumnAndRow(2, 12, $row['car_name']);
	$sheet2->setCellValueByColumnAndRow(58, 12, $row['car_number'].' , '.$row['car_extra_number']);

	$sheet2->setCellValueByColumnAndRow(64, 14, $row['car_owner_type']);

	$sheet2->setCellValueByColumnAndRow(58, 32, $row['tr_chief']);

	//$sheet2->setCellValueByColumnAndRow(2, 35, $row['tr_cash']);
	$sheet2->setCellValueByColumnAndRow(2, 35, 'согласно договора');

	$sheet2->setCellValueByColumnAndRow(58, 47, $tranporter);

    $sheet->getPageSetup()->setFitToPage(true);

	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ТН_№'.$row['id'].'.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
}