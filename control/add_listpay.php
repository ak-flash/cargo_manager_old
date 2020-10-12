<?php

// Подключение и выбор БД
include "../config.php";
session_start();


if(@$_GET['create']=="true"){


$docs_id=@$_GET['docs_id'];
$date_pay=date("Y-m-d",strtotime("now"));;
$add_name=(int)$_SESSION['user_id'];	

$query_doc = "SELECT * FROM `docs` WHERE `id`='".mysql_escape_string($docs_id)."'";
$result_doc = mysql_query($query_doc) or die(mysql_error());
$get_doc = mysql_fetch_row($result_doc);

$query_order = "SELECT `cl_nds`,`cl_cash`,`tr_nds`,`tr_cash`,`cl_kop` FROM `orders` WHERE  `id`='".mysql_escape_string($get_doc[1])."'";
$result_order = mysql_query($query_order) or die(mysql_error());
$get_order = mysql_fetch_row($result_order);







if(@$_GET['mode']=="cl"){
$cl_pay=0;
$check=true;
$query_pays = "SELECT `cash`,`status` FROM `pays` WHERE `order`='".mysql_escape_string($get_doc[1])."' AND `delete`='0' AND `appoint`='1'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {

if($pay[1]==1)$cl_pay=(int)$pay[0]+(int)$cl_pay; else $check=false;

}

if(!$check) echo 'Имеются непроведённые платежи. Проверьте и повторите попытку.'; else {
$cash=($get_order[1].'.'.$get_order[4]-(int)$cl_pay/100)*100;
if((int)(($get_order[1].'.'.$get_order[4])*100)==(int)$cl_pay||$cash==0)echo 'Заявка оплачена клиентом!'; else {



$query = "INSERT INTO `pays` (`date`,`way`,`nds`,`category`,`appoint`,`order`,`cash`,`status`,`notify`,`add_name`,`payment_source`) VALUES ('$date_pay','1','$get_order[0]','1','1','$get_doc[1]','$cash','0','Номер счёта $get_doc[2]','$add_name','0')";
$result = mysql_query($query) or die(mysql_error());
echo "Платеж к заявке №".$get_doc[1]."  (Счёт № <b>".$get_doc[2]."</b>) создан<br>";
}
}
}

if(@$_GET['mode']=="tr"){
	
$tr_pay=0;
$check=true;
$query_pays = "SELECT `cash`,`status` FROM `pays` WHERE `order`='".mysql_escape_string($get_doc[1])."' AND `delete`='0' AND `appoint`='2'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
while($pay = mysql_fetch_row($result_pays)) {

if($pay[1]==1)$tr_pay=(int)$pay[0]+(int)$tr_pay; else $check=false;

}

if(!$check) echo 'Имеются непроведённые платежи. Проверьте и повторите попытку.'; else {
$cash=(int)$get_order[3]*100-(int)$tr_pay;
if((int)$get_order[1]*100==(int)$tr_pay||$cash==0)echo 'Заявка оплачена перевозчику!'; else {



$query = "INSERT INTO `pays` (`date`,`way`,`nds`,`category`,`appoint`,`order`,`cash`,`status`,`notify`,`add_name`,`payment_source`) VALUES ('$date_pay','2','$get_order[2]','1','2','$get_doc[1]','$cash','0','Номер счёта $get_doc[5]','$add_name','0')";
$result = mysql_query($query) or die(mysql_error());
echo "Платеж к заявке №".$get_doc[1]."  (Счёт № <b>".$get_doc[5]."</b>) создан<br>";
}

}	 
}
} else {
	
	
$validate=true;


if(@$_POST['list_pay_id']==""){echo 'Выберите заявки для создания платежа!';$validate=false;}

	
if($validate)
{
$date_pay=date("Y-m-d",strtotime("now"));;
$add_name=(int)$_SESSION['user_id'];	
$list_pay_id=$_POST['list_pay_id'];
if ($list_pay_id){
	 foreach($list_pay_id as $in){
	 $elems= explode("|",$in);
	 
if((int)$_POST['cash_tr_'.$elems[0]]!=0) $elems[1]=(int)$_POST['cash_tr_'.$elems[0]]*100;
	 
$query = "INSERT INTO `pays` (`date`,`way`,`nds`,`category`,`appoint`,`order`,`cash`,`status`,`notify`,`add_name`,`payment_source`) VALUES ('$date_pay','2','$elems[2]','1','2','$elems[0]','$elems[1]','0','','$add_name','0')";
$result = mysql_query($query) or die(mysql_error());
	 	 
	 	 
echo "Платеж к заявке №".$in." создан<br>";	 
	 
	 
	 	 
	 }
	}


}




}	

?>