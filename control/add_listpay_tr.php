<?php

// Подключение и выбор БД
include "../config.php";
session_start();

if (@$_GET['create'] == "gr" && @$_GET['mode'] == "order") {
    $add_name = (int)$_SESSION['user_id'];
    $date_elements = explode("/", $_POST['date_pay_group']);
    $date_pay = date("Y-m-d", strtotime(trim($date_elements[2]) . "-" . trim($date_elements[1]) . "-" . trim($date_elements[0])));
    $status = (int)$_POST['transaction'];

    for ($i = 0; $i < count($_POST['order_pay']); $i++) {

        $order = @$_POST['order_pay'][$i];

        if ($order != "") {


            //if(@$_POST['transaction_full'][$i]==1) $cash=@$_POST['cash'][$i]*100; else $cash=@$_POST['cash_pay'][$i]*100;
            $cash = (int)$_POST['cash_pay'][$i] * 100;

            if ($cash != 0) {

                $query_order = "SELECT `tr_nds`,`tr_cont` FROM `orders` WHERE  `id`='" . mysql_escape_string($order) . "'";
                $result_order = mysql_query($query_order) or die(mysql_error());
                $get_order = mysql_fetch_row($result_order);


                $query = "INSERT INTO `pays` (`date`,`way`,`nds`,`category`,`appoint`,`order`,`cash`,`status`,`notify`,`add_name`,`payment_source`) VALUES ('$date_pay','2','$get_order[0]','1','2','$order','$cash','$status','---','$add_name','$get_order[1]')";
$result = mysql_query($query) or die(mysql_error());
   
// $info.='<b>'.$cash.'-'.@$_POST['transaction_full'][$i].'</b>,';  
$info.='<b>'.@$_POST['order_pay'][$i].'</b>,';
 }
 
   }
   
   
}

//echo $info;
if($info!="") echo '<font color=green>Платежи для заявок: '.$info.' созданы!</font>|1'; else echo 'Не было создано ни одного платежа! Укажите суммы.';



}




?>