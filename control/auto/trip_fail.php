<?php
if(@$_GET['mode']=='orders'){
include "../../config.php";	

$query = "SELECT `id` FROM `orders` WHERE `transp`='2' AND `delete`='0'";
$result = mysql_query($query) or die(mysql_error()); 
while($row = mysql_fetch_array($result)) {
$id_mass_order[]=$row[0];
}


$query_s = "SELECT `id`,`order` FROM `vtl_trip` WHERE `delete`='0'";
$result_s = mysql_query($query_s) or die(mysql_error());
while($row_s = mysql_fetch_array($result_s)){
$str_ord = explode('&',$row_s['order']);
$str_ord_list =(int)sizeof($str_ord)-2;
for($m=0; $m<=$str_ord_list; $m++){
$id_mass_trip[]=$str_ord[($str_ord_list-$m)];
}
}


    if(isset($id_mass_order) && isset($id_mass_trip)){
        $result = array_diff ($id_mass_order, $id_mass_trip);
        echo implode(', ' , $result);
    }
}
?>