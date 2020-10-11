<?php 
include "../../config.php";

if ($_GET['mode']=='card') {
echo '<option value="0">Выберите...</option>';	
	
$query_card = "SELECT * FROM `vtl_oil_card` WHERE `delete`='0' ORDER BY `id` ASC";
$result_card = mysql_query($query_card) or die(mysql_error());
while($card = mysql_fetch_row($result_card)) {

$query_car = "SELECT `id`,`name`,`number` FROM `vtl_auto` WHERE `id`='".mysql_escape_string($card[2])."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$car = mysql_fetch_row($result_car);


echo '<option value='.$card[0].' onclick=\'$("#card_load_info").load("/control/auto/vtl_card.php?mode=card&card_id='.$card[0].'");\'>'.$card[1].' - '.$car[1].' ('.$car[2].')</option>';
}
}


?>