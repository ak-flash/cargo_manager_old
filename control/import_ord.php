<?php  
include "../config.php";
ini_set("max_execution_time","0");
set_time_limit (0); 
ignore_user_abort (true);

function convert($temp) {
return iconv("windows-1251", "UTF-8", $temp);
}


if($_FILES["import_ord"]["size"] > 1024*3*1024)
   {
     echo ("Размер файла превышает три мегабайта");
     exit;
   }
   // Проверяем загружен ли файл
   if(is_uploaded_file($_FILES["import_ord"]["tmp_name"]))
   {
   	   $data_array = file($_FILES["import_ord"]["tmp_name"]);
   	 


preg_match_all( '#<order>(.+?)</order>#is', $data_array[0], $orders);
preg_match_all( '#<adress_in>(.+?)</adress_in>#is', $data_array[0], $adress_in);
preg_match_all( '#<adress_in_id>(.+?)</adress_in_id>#is', $data_array[0], $adress_in_id);
preg_match_all( '#<adress_out>(.+?)</adress_out>#is', $data_array[0], $adress_out);
preg_match_all( '#<adress_out_id>(.+?)</adress_out_id>#is', $data_array[0], $adress_out_id);
preg_match_all( '#<transp>(.+?)</transp>#is', $data_array[0], $transp);
preg_match_all( '#<transp_id>(.+?)</transp_id>#is', $data_array[0], $transp_id);
preg_match_all( '#<car>(.+?)</car>#is', $data_array[0], $car);
preg_match_all( '#<car_id>(.+?)</car_id>#is', $data_array[0], $car_id);
$m=0;
foreach ( $orders[1] as $order) {
//Заявка
echo $orders[1][$m].'<hr>';
//адрес загрузки
echo $adress_in[1][$m].'<hr>';

$res_adr_in='';
$str_in = explode('&',$adress_in_id[1][$m]);
$str_adr_in =(int)sizeof($str_in)-2;
$f=0;
while ($f<=$str_adr_in) {
echo $str_in[($str_adr_in-$f)].'<br>';


$f++;
}


//адрес выгрузки
echo $adress_out[1][$m].'<hr>'.$adress_out_id[1][$m];
//перевозчик
echo $transp[1][$m].'<hr>'.$transp_id[1][$m];
//автотранспорт
echo $car[1][$m].'<hr>'.$car_id[1][$m];
	//$order
$m++;
}

   } else {die("Ошибка загрузки файла!");} 




//$print_orders="INSERT INTO `orders` (`data`, `manager`, `client`, `cl_pref`, `cl_nds`, `cl_cash`, `cl_tfpay`, `cl_event`, `cl_minus`, `cl_plus`, `in_adress`, `out_adress`, `transp`, `tr_pref`, `tr_nds`, `tr_manager`, `tr_cash`, `tr_tfpay`, `tr_event`, `tr_minus`, `tr_plus`, `tr_auto`, `gruz`, `gr_m`, `gr_v`, `gr_number`, `gr_load`, `status`, `date_in1`, `date_out1`, `cl_cont`, `block`, `rent`, `tr_cont`, `delete`, `time_in11`, `time_out1`, `date_out2`, `tr_receive`, `print_tr`, `print_cl`, `notify`, `time_out2`, `car_notify`, `time_in12`, `date_in2`, `time_in21`, `time_in22`, `km`, `timestamp`, `date_plan`, `krugoreis`, `days_tfpay`, `vzaimozachet`, `complete`, `pretenzia`, `agat_number`, `cl_kop`) VALUES ";



//$print_adress="INSERT INTO `adress` (`postcode`, `country`, `obl`, `city`, `street`, `dom`, `dom_extra`, `flat`, `contact_name`, `contact_phone`, `adr_mode`, `block`, `delete`, `adr_mode_cl_tr`, `adr_place`) VALUES ";


//$print_car="INSERT INTO `tr_autopark` (`transporter`, `car_name`, `car_number`, `car_m`, `car_v`, `car_load`, `car_extra_name`, `car_extra_number`, `car_driver_name`, `car_driver_doc`, `car_driver_phone`, `car_kuzov`, `delete`, `car_owner`, `car_owner_doc`, `check_mail`, `check_date`, `check`, `car_notify`) VALUES ";


//$print_tr="INSERT INTO `transporters` (`name`, `nds`, `pref`, `tr_cont`, `tr_point`, `tr_time`, `tr_support`, `tr_phone`, `tr_mail`, `block`, `tr_notify`, `tr_adr_f`, `tr_inn`, `tr_kpp`, `tr_ogrn`, `tr_rs`, `tr_bank`, `tr_bik`, `tr_ks`, `contract`, `tr_chief`, `tr_dchief`, `tr_orderform`, `tr_dsupport`, `tr_pref_phone`, `tr_ochief`, `tr_chief_contract`, `tr_dchief_contract`, `tr_adr_u`, `tr_manager`, `tr_icq`, `tr_code_ati`) VALUES ";
?>

