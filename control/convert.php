<?php
// Подключение и выбор БД
include "../config.php";

$m=0;
$n=0;

$query = "SELECT `id`,`tr_cash`,`data` FROM `orders` WHERE (`transp`='2' OR `transp`='462')";
$result = mysql_query($query) or die(mysql_error());
while($order= mysql_fetch_row($result)) {
$cash=(int)$order[1]*100;

$tr_pay=0;

$query_tr_pays = "SELECT `cash` FROM `pays` WHERE `order`='".mysql_escape_string($order[0])."' AND `delete`='0' AND `appoint`='2' AND `way`='2' AND `status`='1'";
$result_tr_pays = mysql_query($query_tr_pays) or die(mysql_error());
while($t_pay = mysql_fetch_row($result_tr_pays)) {
$tr_pay=(int)$t_pay[0]+(int)$tr_pay;
}

if((int)$tr_pay!=(int)$cash){
$query_upd = "INSERT INTO `pays` (`date`,`way`,`nds`,`category`,`appoint`,`order`,`cash`,`status`,`notify`,`add_name`,`payment_source`,`del_id`,`pay_bill`,`car_id`) VALUES ('$order[2]','2','1','1','2','$order[0]','".((int)$cash-(int)$tr_pay)."','1','','0','0','0','0','0')";
$result_upd = mysql_query($query_upd) or die(mysql_error());
echo $order[0].'-'.$cash.'-'.$tr_pay.'<br>';
$n++;
}

$m++;

}
	
	
echo "Обработано - ".$m."<br>Добавлено - ".$n;	







?>