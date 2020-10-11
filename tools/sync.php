<?php
// Подключение и выбор БД
include "../config.php";	

if(@$_GET['type']=="export"){
header('Content-Type: text/comma-separated-values');
	
	
$str = explode(',',$_GET['orders']);
$res = (int)sizeof($str)-1;

header('Content-Disposition: attachment; filename="export_'.$_GET['orders'].'.csv"');

$error='';



//echo $print_orders;

$print_docs="";



$f=0;
while ($f<=$res) {
$print_orders="";
	
$query = "SELECT * FROM `orders` WHERE `Id`='".mysql_escape_string($str[$f])."' ORDER BY `Id` DESC";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_row($result);
$colCount = mysql_num_fields($result); 

$print_orders.="(";

$m=1;
while($m<=($colCount-1)) {
if($m==57||$m==12||$m==11){
switch($m) {
case '11': $print_orders.="'@@@@'";break;
case '12': $print_orders.="'@@@@@'";break;
case '57': $print_orders.="'A-@@@'";break;}
} else $print_orders.="'".$row[$m]."'";

if($m<($colCount-1)) $print_orders.=",";
$m++;
}
$print_orders.=")";
//if($f<$res) $print_orders.=",";


// Адреса экспорт загрузка
$print_adress_in="";

$str_in = explode('&',$row[11]);
$str_adr_in = (int)sizeof($str_in)-2;
$q=0;
while ($q<=$str_adr_in) {
$query_adr = "SELECT * FROM `adress` WHERE `Id`='".mysql_escape_string($str_in[$q])."'";
$result_adr = mysql_query($query_adr) or die(mysql_error());
$row_adr = mysql_fetch_row($result_adr);
$colCount_adr = mysql_num_fields($result_adr);
$m=1;
$print_adress_in.="(";
while($m<=($colCount_adr-1)) {
$print_adress_in.="'".$row_adr[$m]."'";
if($m<($colCount_adr-1)) $print_adress_in.=",";
$m++;
}
$print_adress_in.=")";
if($q<$str_adr_in) $print_adress_in.=",";
$q++;
}
$print_adress_in.=";";

// Адреса экспорт выгрузки
$print_adress_out="";

$str_out = explode('&',$row[12]);
$str_adr_out = (int)sizeof($str_out)-2;
$q=0;

while ($q<=$str_adr_out) {
$query_adr = "SELECT * FROM `adress` WHERE `Id`='".mysql_escape_string($str_out[$q])."'";
$result_adr = mysql_query($query_adr) or die(mysql_error());
$row_adr = mysql_fetch_row($result_adr);
$colCount_adr = mysql_num_fields($result_adr);
$m=1;
$print_adress_out.="(";
while($m<=($colCount_adr-1)) {
$print_adress_out.="'".$row_adr[$m]."'";
if($m<($colCount_adr-1)) $print_adress_out.=",";
$m++;
}
$print_adress_out.=")";
if($q<$str_adr_in) $print_adress_out.=",";
$q++;
}
$print_adress_out.=";";

$print_orders.=";";


$f++;


if((int)$row[13]!=71){
// Экспорт водителя
$print_car="";


$query_car = "SELECT * FROM `tr_autopark` WHERE `Id`='".mysql_escape_string($row[22])."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$row_car = mysql_fetch_row($result_car);
$colCount_car = mysql_num_fields($result_car);
$m=1;
$print_car.="(";
while($m<=($colCount_car-1)) {
$print_car.="'".$row_car[$m]."'";
if($m<($colCount_car-1)) $print_car.=",";
$m++;
}
$print_car.=");";

// Экспорт перевозчика
$print_tr="";


$query_tr = "SELECT * FROM `transporters` WHERE `Id`='".mysql_escape_string($row[13])."'";
$result_tr = mysql_query($query_tr) or die(mysql_error());
$row_tr = mysql_fetch_row($result_tr);
$colCount_tr = mysql_num_fields($result_tr);
$m=1;
$print_tr.="(";
while($m<=($colCount_tr-1)) {
$print_tr.="'".$row_tr[$m]."'";
if($m<($colCount_tr-1)) $print_tr.=",";
$m++;
}
$print_tr.=");";

}



echo '<ord_num>'.$f.'</ord_num>';
echo '<order>'.$print_orders.'</order>';
echo '<adress_in>'.$print_adress_in.'</adress_in>';
echo '<adress_in_id>'.$row[11].'</adress_in_id>';
echo '<adress_out>'.$print_adress_out.'</adress_out>';
echo '<adress_out_id>'.$row[12].'</adress_out_id>';
echo '<transp>'.$print_tr.'</transp>';
echo '<transp_id>'.$row[13].'</transp_id>';
echo '<car>'.$print_car.'</car>';
echo '<car_id>'.$row[22].'</car_id>';
}


}

?>