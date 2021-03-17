<?php
session_start();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Helper\Html as PHPExcel_Helper_HTML;

include "../config.php";
include "../lib/plural.php";

$DOCS_TEMPLATES_DIR = 'docs_templates/';

function convert($temp)
{
    return iconv("UTF-8", "windows-1251", $temp);
}

if (@$_GET['id'] != "" && isset($_SESSION['user_id'])) {

    $id = base64_decode($_GET['id']);

    $template_type = (int)$_GET['type'];

    $query = "SELECT * FROM `orders` WHERE `Id`=" . mysql_escape_string($id);
    $result = mysql_query($query) or die(mysql_error());
    $row = mysql_fetch_array($result);


    if ($_GET['mode'] == 'cl') {

        $query_clients = "SELECT * FROM clients WHERE id = " . mysql_escape_string($row['client']);
        $result_clients = mysql_query($query_clients) or die(mysql_error());
        $client = mysql_fetch_array($result_clients);

        $manager = $row['manager'];

        $print_adr_u = $client['cl_adr_u'];
        $print_adr_f = $client['cl_adr_f'];

        $print_currency = $row['cl_currency'];
        
        $printContract = $client['contract'];
        
        $print_support = $client['cl_support'];


        $print_cont = $row['cl_cont'];
        $print_pref = $row['cl_pref'];
        $print_nds = $row['cl_nds'];
        $print_tfpay = $row['cl_tfpay'];
        $print_event = $row['cl_event'];
        $print_contract = $client['contract'];
        $print_id = $client['id'];
        $print_name = $client['name'];
        $print_inn = $client['cl_inn'];
        $print_kpp = $client['cl_kpp'];
        $print_ogrn = $client['cl_ogrn'];
        $print_okpo = $client['cl_okpo'];
        $print_rs = $client['cl_rs'] . ' в ' . $client['cl_bank'];
        $print_bik = $client['cl_bik'];
        $print_ks = $client['cl_ks'];
        $print_phone = '8 (' . $client['cl_pref_phone'] . ') ' . $client['cl_phone'];

        if ($row['cl_kop'] != "" && (int)$row['cl_kop'] != 0) $cl_kop = '.' . $row['cl_kop']; else $cl_kop = "";

        $print_cash = $row['cl_cash'] . $cl_kop;

        $pieces = explode(" ", $client['cl_chief']);
        $print_chief = $pieces[0] . " " . substr($pieces[1], 0, 2) . ". " . substr($pieces[2], 0, 2) . ".";
        $print_dchief = $client['cl_dchief'];
        $print_ochief = $client['cl_ochief'];

        $cont_type = 'Перевозчик';
        $print_type = 'Клиент';
    }

    if ($_GET['mode'] == 'tr') {
        $query_trans = "SELECT * FROM transporters WHERE id = " . mysql_escape_string($row['transp']);
        $result_trans = mysql_query($query_trans) or die(mysql_error());
        $transporter = mysql_fetch_array($result_trans);

        $manager = $row['tr_manager'];

        $print_adr_u = $transporter['tr_adr_u'];
        $print_adr_f = $transporter['tr_adr_f'];

        $print_currency = $row['tr_currency'];

        $print_cont = $row['tr_cont'];
        $print_pref = $row['tr_pref'];
        $print_support = $transporter['tr_support'];
        $print_phone = '8 (' . $transporter['tr_pref_phone'] . ') ' . $transporter['tr_phone'];
        $print_nds = $row['tr_nds'];
        $print_tfpay = $row['tr_tfpay'];
        $print_event = $row['tr_event'];
        $print_contract = $transporter['contract'];
        $print_id = $transporter['id'];
        $print_name = $transporter['name'];
        $print_inn = $transporter['tr_inn'];
        $print_kpp = $transporter['tr_kpp'];
        $print_ogrn = $transporter['tr_ogrn'];
        $print_okpo = $transporter['tr_okpo'];
        $print_rs = $transporter['tr_rs'] . ' в ' . $transporter['tr_bank'];
        $print_bik = $transporter['tr_bik'];
        $print_ks = $transporter['tr_ks'];
        
        $printContract = $transporter['contract'];

        $print_ati = $transporter['tr_code_ati'];
        $print_cash = $row['tr_cash'];

        $pieces = explode(" ", $transporter['tr_chief']);
        $print_chief = $pieces[0] . " " . substr($pieces[1], 0, 2) . ". " . substr($pieces[2], 0, 2) . ".";
        $print_dchief = $transporter['tr_dchief'];
        $print_ochief = $transporter['tr_ochief'];

        $cont_type = 'Клиент';
        $print_type = 'Перевозчик';
    }

    if ($print_pref == 3) $print_dchief = 'ИП';
    


    $query_cont = "SELECT * FROM company WHERE id = " . mysql_escape_string($print_cont);
    $result_cont = mysql_query($query_cont) or die(mysql_error());
    $company = mysql_fetch_array($result_cont);

    $cont_phone = '+7 (' . $company['pref_phone'] . ') ' . $company['phone'];

    if (!isset($print_ati)) $print_ati = $company['code_ati'];


    $query_user = "SELECT `id`,`name`,`phone`,`email`,`pref_phone`,`fake_name` FROM `workers` WHERE id=" . $manager;
    $result_user = mysql_query($query_user) or die(mysql_error());
    $user = mysql_fetch_array($result_user);


    if ($row['transp'] == '2' || $row['transp'] == '-1') {
        $car_id = explode('&', $row['tr_auto']);
        if ($car_id[0] != 0) {
            $query_car = "SELECT `name`,`number` FROM `vtl_auto` WHERE `Id`='" . mysql_escape_string($car_id[0]) . "'";
            $result_car = mysql_query($query_car) or die(mysql_error());
            $car_vtl = mysql_fetch_row($result_car);
            $car[2] = $car_vtl[0];
            $car[3] = $car_vtl[1];
        }

        if ($car_id[1] != 0) {
            $query_car = "SELECT `name`,`number` FROM `vtl_auto` WHERE `Id`='" . mysql_escape_string($car_id[1]) . "'";
            $result_car = mysql_query($query_car) or die(mysql_error());
            $car_vtl = mysql_fetch_row($result_car);
            $car[7] = $car_vtl[0];
            $car[8] = $car_vtl[1];
        }

        if ($car_id[2] != 0) {
            $query_drv = "SELECT `name`,`pref_phone`,`phone`,`passport` FROM `workers` WHERE `id`='" . mysql_escape_string($car_id[2]) . "'";
            $result_drv = mysql_query($query_drv) or die(mysql_error());
            $drv_vtl = mysql_fetch_row($result_drv);
            $car[9] = $drv_vtl[0];
            $car_info = explode('|', $drv_vtl[3]);
            $car[10] = $drv_vtl[3];
            $car[11] = '8 (' . $drv_vtl[1] . ') ' . $drv_vtl[2];
        }

    } else {
        $query_car = "SELECT * FROM tr_autopark WHERE id='" . $row['tr_auto'] . "'";
        $result_car = mysql_query($query_car) or die(mysql_error());
        $car = mysql_fetch_row($result_car);
        $car_info = explode('|', $car[10]);

    }

    $query_adress = "SELECT * FROM `adress` WHERE `id`='" . mysql_escape_string($print_adr_u) . "' OR `id`='" . mysql_escape_string($print_adr_f) . "'";
    $result_adress = mysql_query($query_adress) or die(mysql_error());

    while ($print_adress = mysql_fetch_array($result_adress)) {
        if ($print_adress['flat'] == 0) $flat = ''; else $flat = $print_adress['flat'];
        if ($print_adress['dom_extra'] == '') $dom_extra = ''; else $dom_extra = $print_adress['dom_extra'];

        $adr = $print_adress['postcode'] . ', ' . $print_adress['country'] . ', ' . $print_adress['obl'] . ' ' . $print_adress['city'] . ' ул. ' . $print_adress['street'] . ' д. ' . $print_adress['dom'] . $dom_extra . ' ' . $flat;

        if ($print_adress['id'] == $print_adr_u) {
            $print_adr_u = $adr;
        }
        if ($print_adress['id'] == $print_adr_f) {
            $print_adr_f = $adr;
        }

    }

    switch ($company['pref']) {
        case '1':
            $cont_pref = 'ООО';
            break;
        case '2':
            $cont_pref = 'ОАО';
            break;
        case '3':
            $cont_pref = 'ИП';
            break;
        case '4':
            $cont_pref = 'ЗАО';
            break;
        case '5':
            $cont_pref = '';
            break;
        case '6':
            $cont_pref = 'Физ.Л.';
            break;
        case '7':
            $cont_pref = 'АО';
            break;
    }

    switch ($print_pref) {
        case '1':
            $print_pref = 'ООО';
            break;
        case '2':
            $print_pref = 'ОАО';
            break;
        case '3':
            $print_pref = 'ИП';
            break;
        case '4':
            $print_pref = 'ЗАО';
            break;
        case '5':
            $print_pref = '';
            break;
        case '6':
            $print_pref = 'Физ.Л.';
            break;
        case '7':
            $print_pref = 'АО';
            break;
    }

    switch ($print_nds) {
        case '0':
            $print_nds = '(без НДС)';
            break;
        case '1':
            $print_nds = '(с НДС)';
            break;
        case '2':
            $print_nds = '(НАЛ)';
            break;
    }

    if ($row['days_tfpay'] == 0 || $_GET['mode'] == 'cl') $days_tfpay = 'календарных'; else $days_tfpay = 'банковских';


    if ($print_tfpay == 0) {
        $cl_tfpay = '';
    } else {
        $cl_tr_tfpay = ' + ' . $print_tfpay . '  дн. (' . $days_tfpay . ')';
    }


    switch ($print_event) {
        case '1':
            $print_event = 'По факту Загрузки' . $cl_tr_tfpay;
            break;
        case '2':
            $print_event = 'По факту Выгрузки' . $cl_tr_tfpay;
            break;
        case '3':
            $print_event = 'Поступление факсимильных документов' . $cl_tr_tfpay;
            break;
        case '4':
            $print_event = 'Поступление оригинальных документов' . $cl_tr_tfpay;
            break;
    }

    switch ($row['gr_load']) {
        case '1':
            $kuzovLoadType = 'верхняя';
            break;
        case '2':
            $kuzovLoadType = 'задняя';
            break;
        case '3':
            $kuzovLoadType = 'боковая';
            break;
    }
    if ($print_contract != '') {
        $cl_tr_contract = $print_contract;
    } else {
        $cl_tr_contract = $print_id;
    }

    if ($row['date_in1'] != "1970-01-01" && $row['date_in1'] != "0000-00-00") {
        $date_in1 = "<b>" . date("d/m/Y", strtotime($row['date_in1'])) . "</b>";
        if ($row['time_in11'] != '00:00:00') $date_in1 .= " с " . date("H:i", strtotime($row['time_in11']));

        if ($row['time_in12'] != '00:00:00') $date_in1 .= " до " . date("H:i", strtotime($row['time_in12']));

    } else $date_in1 = "";

    if ($row['date_in2'] != "1970-01-01" && $row['date_in2'] != "0000-00-00") {
        $date_in2 = " по <b>" . date("d/m/Y", strtotime($row['date_in2'])) . "</b>";

        if ($row['time_in21'] != '00:00:00') $date_in2 .= " с " . date("H:i", strtotime($row['time_in21']));
        if ($row['time_in22'] != '00:00:00') $date_in2 .= " до " . date("H:i", strtotime($row['time_in22']));

        $date_in = " c ";
    } else {
        $date_in2 = "";
        $date_in = "";
    }


    $d = getdate(strtotime($row['data']));
    $month_d = $d['mon'];

    switch ($d['mon']) {
        case '1':
            $month = 'января';
            break;
        case '2':
            $month = 'февраля';
            break;
        case '3':
            $month = 'марта';
            break;
        case '4':
            $month = 'апреля';
            break;
        case '5':
            $month = 'мая';
            break;
        case '6':
            $month = 'июня';
            break;
        case '7':
            $month = 'июля';
            break;
        case '8':
            $month = 'августа';
            break;
        case '9':
            $month = 'сентября';
            break;
        case '10':
            $month = 'октября';
            break;
        case '11':
            $month = 'ноября';
            break;
        case '12':
            $month = 'декабря';
            break;
    }

// Печать номера заявки
    if ($row['international_number'] != "0") $id = $row['international_number'] . '-' . $row['id']; else $id = $row['id'];

// Gruz info
    $gruz = '';
    $gr_number = 0;
    $gr_m = 0;
    $gr_v = 0;
    $in_adress = '';
    $out_adress = '';

    if ($row['group_id'] != 0) {
        $id = 'ГР-' . $row['group_id'];
        $print_cash = 0;

        //$query_group = "SELECT in_adress, out_adress, tr_cash, gruz, gr_number, gr_m, gr_v FROM orders WHERE id IN (" . $row['group_id'] . ")";
        $query_group = "SELECT in_adress, out_adress, tr_cash, gruz, gr_number, gr_m, gr_v FROM orders 
                        WHERE group_id = " . $row['group_id'];
        $result_group = mysql_query($query_group) or die(mysql_error());

        while ($group = mysql_fetch_array($result_group)) {
            $print_cash += $group['tr_cash'];
            if ($gruz != '') $gruz = $gruz . ', ' . $group['gruz']; else $gruz = $group['gruz'];
            if ($group['gr_number'] != 0) $gr_number += $group['gr_number'];
            $gr_m += $group['gr_m'];
            $gr_v += $group['gr_v'];
            $in_adress .= $group['in_adress'];
            $out_adress .= $group['out_adress'];
        }
    } else {
        $gruz = $row['gruz'];
        if ($row['gr_number'] != 0) $gr_number = $row['gr_number'] . ', '; else $gr_number = '';
        $gr_m = $row['gr_m'];
        $gr_v = $row['gr_v'];
        $in_adress = $row['in_adress'];
        $out_adress = $row['out_adress'];
    }

//if($orderform=='4'){
//$query_settings = "SELECT `agat_number` FROM `orders` WHERE `id`='".mysql_escape_string($id)."'";
//$result_settings = mysql_query($query_settings) or die(mysql_error());
//$settings = mysql_fetch_row($result_settings);
//$id=$settings[0];
//}

    if ($user['fake_name'] != '') {
        $manager_name = $user['fake_name'];
    } else {
        $pieces = explode(" ", $user['name']);
        $manager_name = $pieces[1];
    }

    if ($car[7] != "" || $car[8] != "") {
        $carPricep =  "(П/п: " . $car[7] . " - " . $car[8] . ")";
    } else {
        $carPricep = "";
    }

    $pieces = explode(" ", $company['chief']);
    $cont_chief = $pieces[0] . " " . substr($pieces[1], 0, 2) . ". " . substr($pieces[2], 0, 2) . ".";


    $manager_phone = convert("8 (" . $user['pref_phone'] . ") " . $user['phone']);


    $str_in = explode('&', $in_adress);
    $str_adr_in = (int)sizeof($str_in) - 2;

    $f = 0;
    $adress_in = '';
    while ($f <= $str_adr_in) {
        $res_adr_in = "";
        $adr_in_cont = "";
        $adr_in_phone = "";

        $query_adress = "SELECT * FROM `adress` WHERE `id`='" . $str_in[($str_adr_in - $f)] . "'";
        $result_adress = mysql_query($query_adress) or die(mysql_error());

        $adress = mysql_fetch_row($result_adress);

        if ($adress[8] != "" && $adress[8] != 0) $flat = ' - ' . $adress[8]; else $flat = "";
        if ($adress[6] != 0) $dom = ' д.' . $adress[6]; else $dom = '';

        $res_adr_in = $adress[2] . ', ' . $adress[3] . ' обл., ' . $adress[4] . ' ул. ' . $adress[5] . $dom . $adress[7] . $flat;
        $adr_in_cont = $adr_in_cont . " " . $adress[9];
        $city_start = $adress[4];
        $adr_in_phone = $adr_in_phone . " " . $adress[10];

        if($row['cl_cont']==2 || $row['tr_cont']==2)
        { 
            $adress_in .= ($f + 1) . ') ' . $res_adr_in . ' {' . $adress[15] . '} (' . $adr_in_cont . $adr_in_phone . ')'."\r\n";
        } else {
            if ($f == $str_adr_in) $par = ''; else $par = '\par ';
                $adress_in .= ($f + 1) . ') ' . $res_adr_in . ' { \b ' . $adress[15] . '} (' . $adr_in_cont . $adr_in_phone . ')' . $par;
        }
        

        $f++;
    }


    if ($row['date_in1'] != "1970-01-01" && $row['date_in1'] != "0000-00-00") {
        $date_in1 = date("d/m/Y", strtotime($row['date_in1']));
        if ($row['time_in11'] != '00:00:00') $date_in1 .= " с " . date("H:i", strtotime($row['time_in11']));

        if ($row['time_in12'] != '00:00:00') $date_in1 .= " до " . date("H:i", strtotime($row['time_in12']));

    } else $date_in1 = "";

    if ($row['date_in2'] != "1970-01-01" && $row['date_in2'] != "0000-00-00") {
        $date_in2 = " по " . date("d/m/Y", strtotime($row['date_in2']));

        if ($row['time_in21'] != '00:00:00') $date_in2 .= " с " . date("H:i", strtotime($row['time_in21']));
        if ($row['time_in22'] != '00:00:00') $date_in2 .= " до " . date("H:i", strtotime($row['time_in22']));

        $date_in = " c ";
    } else {
        $date_in2 = "";
        $date_in = "";
    }


    if ($print_support != '') $print_support = '' . $print_support;


    $str_out = explode('&', $out_adress);
    $str_adr_out = (int)sizeof($str_out) - 2;

    $f = 0;
    while ($f <= $str_adr_out) {
        $res_adr_out = "";
        $adr_out_cont = "";
        $adr_out_phone = "";
        $query_adress = "SELECT * FROM `adress` WHERE `id`='" . $str_out[($str_adr_out - $f)] . "'";
        $result_adress = mysql_query($query_adress) or die(mysql_error());

        $adress = mysql_fetch_row($result_adress);
        if ($adress[8] != "" && $adress[8] != 0) $flat = ' - ' . $adress[8]; else $flat = "";
        if ($adress[6] != 0) $dom = ' д.' . $adress[6]; else $dom = '';
        $res_adr_out = $res_adr_out . " " . $adress[2] . ', ' . $adress[3] . ' обл., ' . $adress[4] . ' ул. ' . $adress[5] . $dom . $adress[7] . $flat;
        $city_end = $adress[4];
        $adr_out_cont = $adr_out_cont . " " . $adress[9];
        $adr_out_phone = $adr_out_phone . " " . $adress[10];

        if($row['cl_cont']==2 || $row['tr_cont']==2)
        {
            $adress_out .= ($f + 1) . ') ' . $res_adr_out . ' {' . $adress[15] . '} (' . $adr_out_cont . $adr_out_phone . ')'."\r\n";;
        } else {

            if ($f == $str_adr_out) $par = ''; else $par = '\par ';
                $adress_out .= ($f + 1) . ') ' . $res_adr_out . ' { \b ' . $adress[15] . '} (' . $adr_out_cont . $adr_out_phone . ')' . $par;
        }

        $f++;
    }

    if ($row['date_out1'] != "0000-00-00" && $row['date_out1'] != "1970-01-01") {
        $date_out1 = date("d/m/Y", strtotime($row['date_out1']));
        if ($row['time_out1'] != '00:00:00') $date_out1 .= ' с ' . date('H:i', strtotime($row['time_out1']));
    } else $date_out1 = "";
    if ($row['date_out2'] != "0000-00-00" && $row['date_out2'] != "1970-01-01") {
        $date_out2 = ' по ' . date("d/m/Y", strtotime($row['date_out2']));
        if ($row['time_out2'] != '00:00:00') $date_out2 .= ' до ' . date('H:i', strtotime($row['time_out2']));

    } else $date_out2 = "";

    if (!empty($date_out2)) $date_out = " c "; else $date_out = "";


    $kuzovType = $car[12];

    $dateIn = $date_in . $date_in1 . $date_in2;
    $dateOut = $date_out . $date_out1 . ' ' . $date_out2;



    $plural_info = new Plural();

    $selfCompanyInfo = $cont_pref . ' «' . $company['name'] . '»
Юр. адрес: '.$company['adr_u'].'
Факт. адрес: '.$company['adr_f'].'
ИНН/КПП: '.$company['inn'] . ' / '.$company['kpp'] . '
ОГРН: '.$company['ogrn'] . '
Телефон/факс: +7 ('.$company['pref_phone'].') '.$company['phone'] . '
Р/с: '.$company['rs'] . ' в ' . $company['bank'].'
К/с: '.$company['ks'] . '
БИК: '.$company['bik'] . '
    ';

    $companyInfo = $print_pref . ' «' . $print_name . '»
Юр. адрес: '.$print_adr_u.'
Факт. адрес: '.$print_adr_f.'
ИНН/КПП: '.$print_inn.' / '.$print_kpp.'
ОГРН  '.$print_ogrn.'
Телефон/факс: '.$print_phone.'
Р/с: '.$print_rs.'
К/с: '.$print_ks.'
БИК: '.$print_bik.'
    ';

    if ($_GET['mode'] == 'cl') {
        $name = 'Клиенту';
        $orderTitle = 'Заказчик:';

        $clientSupport = $print_support.', тел. '.$print_phone;
        $transporterSupport = $manager_name.', тел. '.$manager_phone;

        $clientChief = $print_chief;
        $transporterChief = $cont_chief;

        $clientInfo = 'Клиент '.$companyInfo;
        $transporterInfo = 'Перевозчик '.$selfCompanyInfo;
    }

    if ($_GET['mode'] == 'tr') {
        $name = 'Перевозчику';
        $orderTitle = 'Перевозчик:';
        
        $clientSupport = $manager_name.', тел. '.$manager_phone;
        $transporterSupport = $print_support.', тел. '.$print_phone;

        $clientChief = $cont_chief;
        $transporterChief = $print_chief;

        $clientInfo = $selfCompanyInfo;
        $transporterInfo = $companyInfo;
    }

// Make XLS form

    require '../vendor/autoload.php';

    //load spreadsheet
    $spreadsheet = PhpOffice\PhpSpreadsheet\IOFactory::load($DOCS_TEMPLATES_DIR.'order.xlsx');

    
    $sheet = $spreadsheet->getActiveSheet();
    


    $orderTitleText = 'ЗАЯВКА № '.$id.'/'.$month_d.' от  "'.$d['mday'].'" '.$month.' '.$d['year'].' г. 
к договору транспортной экспедиции № '.$printContract;

    $sheet->setCellValue('A7',$orderTitleText);

    
    $sheet->setCellValue('A9',$orderTitle);


    $sheet->setCellValue('D9',$print_pref . " «" . $print_name . "»");

    $sheet->setCellValue('D10', $city_start.' - '.$city_end);

    $sheet->setCellValue('D11', $kuzovType);
    $sheet->setCellValue('I11', $kuzovLoadType);



    $sheet->setCellValue('C12', $gruz);
    $sheet->setCellValue('H12', $gr_m);
    $sheet->setCellValue('J12', $gr_v);


    $sheet->setCellValue('D13', $dateIn);
    $sheet->setCellValue('H13', $dateOut);

    $sheet->setCellValue('D14', $adress_in);
    $sheet->getStyle('D14')->getAlignment()->setWrapText(true);
    
    $spreadsheet->getActiveSheet()->getRowDimension('14')->setRowHeight(($str_adr_in+1) * 35);

    
    $sheet->setCellValue('D15', $adress_out);
    $sheet->getStyle('D15')->getAlignment()->setWrapText(true);

    $spreadsheet->getActiveSheet()->getRowDimension('15')->setRowHeight(($str_adr_out+1) * 35);
    

    $sheet->setCellValue('D16', $car[2] . " - " . $car[3].', '.$carPricep);
    $sheet->setCellValue('D17', $car[9] . ", " . $car_info[0] . '   Выдан: ' . $car_info[1] . ' (' . $car_info[2] . 'г.)'.', тел. ' . $car[11]);


    $cash = $print_cash . ' ' . $print_currency.' ( <b>' .$plural_info->asString((int)$print_cash, plural::FEMALE, array('', '', '')). '</b> ) '.$print_nds;
        $wizard = new PHPExcel_Helper_HTML;
        $sheet->setCellValue('D18', $wizard->toRichTextObject($cash));


    $sheet->setCellValue('D19', $print_event);


    $sheet->setCellValue('D21', $clientSupport);
    $sheet->setCellValue('D22', $transporterSupport);

    $sheet->setCellValue('A27', $clientInfo);
    $sheet->setCellValue('F27', $transporterInfo);

    $sheet->setCellValue('A37', 'м.п. _____________________ '.$clientChief);
    $sheet->setCellValue('F37', 'м.п. _____________________ '.$transporterChief);

    $sheet->setTitle("Заявка-" . $name . "-№".$id);

    $sheet->getPageSetup()->setPrintArea('A1:J40');
    $sheet->getPageSetup()->setFitToPage(true);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Заявка-' . $name . '-№'.$id.'.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');




} else {
    http_response_code(400);
    echo 'Доступ запрещён';
}

?>