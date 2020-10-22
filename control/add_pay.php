<?php
// Подключение и выбор БД
include "../config.php";
session_start();


function CheckStr($srt){ 
 return !preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/",$srt);     
}

function ValFail($srt){ 
 return '<script type="text/javascript">$("#'.$srt.'").css({"border-color":"red","background-color":"#FFBABA","color":"#000"});</script>';     
}

function ValOk($srt){ 
 return '<script type="text/javascript">$("#'.$srt.'").css({"border-color":"#187C22","background-color":"#BAE2BD","color":"#000"});</script>';     
}


$error='Проверьте поле  <font color="red">';
$err='</font><br>';
$validate=true;




if(isset($_POST['date_pay'])&&@$_POST['date_pay']==""){echo $error.'Дата платежа'.$err.ValFail('date_pay'); $validate=false;} else {echo ValOk('date_pay');}

if(@$_POST['error']=="1"){echo '<font color="red">Заявки не существует!'.$err; $validate=false;}
if(@$_POST['nds']=="33"){echo $error.'Способ платежа'.$err; $validate=false;}
if(@$_POST['way']=="0"){echo $error.'Направление платежа'.$err; $validate=false;}
if(@$_POST['appointment']=="0"){echo $error.'Назначение платежа'.$err; $validate=false;}
if((@$_POST['appointment']=="28"||@$_POST['appointment']=="32"||@$_POST['appointment']=="25"||@$_POST['appointment']=="33"||@$_POST['appointment']=="34"||@$_POST['appointment']=="35"||@$_POST['appointment']=="37")&&$_POST['car_number']==0){echo $error.'Транспорт'.$err; $validate=false;}

//if(@$_POST['payment_source']=="0"){echo $error.'Источник получатель'.$err; $validate=false;}
//if(@$_POST['payment_source']!="1"&&@$_POST['pay_bill']=="0"){echo $error.'Выберите счет'.$err; $validate=false;}
if(@$_POST['order']!=""&&mb_ereg("[^0-9]",$_POST['order'])){echo $error.'Заявка'.$err; $validate=false;}
if((isset($_POST['cash'])&&@$_POST['cash']=="")||mb_ereg("[^0-9.]",$_POST['cash'])){echo $error.'Сумма платежа'.$err; $validate=false;}

if(@$_POST['appointment']=="32"&&@$_POST['car_number']!=0){

$query_pay = "SELECT `date`,`cash`,`id` FROM `pays` WHERE `way`='2' AND `category`='2' AND `delete`='0' AND `appoint`='32' AND `status`='1' AND DATE(`date`) BETWEEN '".date('Y')."-01-01' AND '".date('Y')."-12-31' AND `car_id`='".(int)$_POST['car_number']."'";
$result_pay = mysql_query($query_pay) or die(mysql_error());
if(mysql_num_rows($result_pay)>=3){
echo '<b>В базе уже есть платежи:</b><br><br>';
while($pay = mysql_fetch_row($result_pay)){
echo '&nbsp;&nbsp;&nbsp;<b>'.$pay[2].'</b> - '.date("d/m/Y",strtotime($pay[0])).' ('.($pay[1]/100).' руб.)<br>';
}

echo $err; 

$validate=false;}}



	
if($validate)
{
$pay_id=(int)$_POST['pay_id'];
//$pay_num=(int)$_POST['pay_num'];

$in_elements  = explode("/",$_POST['date_pay']);
$date_pay=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));
$way=(int)$_POST['way'];
$category=(int)$_POST['category'];
$payment_source=(int)$_POST['payment_source_id'];
$appointment=(int)$_POST['appointment'];
$order=(int)$_POST['order'];
//if(strpos($_POST['cash'], ",")){str_replace(",", ".",$_POST['cash']);$cash=(float)$_POST['cash']*100;} else 

$cash = (float)$_POST['cash'] * 100;
 $status = (int)$_POST['transaction'];


$currency = $_POST['currency'];
//$add_name=(int)$_POST['add_name'];
$add_name = (int)$_SESSION['user_id'];

$pay_bill=(int)$_POST['pay_bill'];
$app_other=mysql_real_escape_string(stripslashes($_POST['app_other']));
$notify=mysql_real_escape_string(stripslashes($_POST['pay_notify']));
$nds=(int)$_POST['nds'];
$del_id=(int)$_POST['del_id'];
$car_id=(int)$_POST['car_number'];
$check=true;

$query_check = "SELECT `c_cash` FROM `bill` WHERE `id`='$pay_bill'";
$result_check = mysql_query($query_check) or die(mysql_error());
$check_bill = mysql_fetch_row($result_check);

if(($way==2&&$check_bill[0]<$cash&&$status==1)&&@$_POST['payment_source_id']!="0"){echo 'На балансе недостаточно средств для проведения платежа!';$check=false;}

if((int)$_POST['pay_cash_full']==1){echo 'Заявка полностью оплачена!!!';$check=false;}

$query_status = "SELECT `status`,`delete` FROM `pays` WHERE `id`='".mysql_escape_string($pay_id)."'";
$result_status = mysql_query($query_status) or die(mysql_error());	
$stats = mysql_fetch_row($result_status);

if($stats[0]=="1"){echo 'Невозможно изменить данные. <font color="red">Платеж проведён!</font><br>';$check=false;}

if($stats[1]=="1"){echo 'Невозможно изменить данные. <font color="red">Платеж удалён!</font>';$check=false;}

if(@$_POST['edit']=="1"&&$check)
{



if($stats[0]=="0"&&$stats[1]=="0")
//if($stats[1]=="0")
{

$query = "UPDATE `pays` SET  `date`='$date_pay',`way`='$way',`payment_source`='$payment_source',`nds`='$nds',`category`='$category',`appoint`='$appointment',`order`='$order',`cash`='$cash',`status`='$status',`notify`='$notify',`add_name`='$add_name',`del_id`='$del_id',`pay_bill`='$pay_bill',`car_id`='$car_id',`currency`='$currency' WHERE `id`='$pay_id'";
$result = mysql_query($query) or die(mysql_error());
echo 'Сохранено!|1';

if($status==1){
$query_bill = "SELECT `c_cash` FROM `bill` WHERE `id`='$pay_bill'";
$result_bill = mysql_query($query_bill) or die(mysql_error());
$bill = mysql_fetch_row($result_bill);
if($way==1){$c_cash=$bill[0]+$cash;}
if($way==2){$c_cash=$bill[0]-$cash;}
$query = "UPDATE `bill` SET `c_cash`='$c_cash' WHERE `id`='$pay_bill'";
$result = mysql_query($query) or die(mysql_error());

}
}
}
 
if(@$_POST['edit']!="1"&&$check) {

$query = "INSERT INTO `pays` (`date`,`way`,`nds`,`category`,`appoint`,`order`,`cash`,`status`,`notify`,`add_name`,`payment_source`,`del_id`,`pay_bill`,`car_id`,`delete`,`back`,`currency`) VALUES ('$date_pay','$way','$nds','$category','$appointment','$order','$cash','$status','$notify','$add_name','$payment_source','$del_id','$pay_bill','$car_id','0','0','$currency')";
$result = mysql_query($query) or die(mysql_error());
echo 'Сохранено!|1';

if($status==1){
$query_bill = "SELECT `c_cash` FROM `bill` WHERE `id`='$pay_bill'";
$result_bill = mysql_query($query_bill) or die(mysql_error());
$bill = mysql_fetch_row($result_bill);

if($way==1){$c_cash=$bill[0]+$cash;}
if($way==2){$c_cash=$bill[0]-$cash;}

$query = "UPDATE `bill` SET `c_cash`='$c_cash' WHERE `id`='$pay_bill'";
$result = mysql_query($query) or die(mysql_error());


}

}

if(@$_POST['ddd']=="1") {
$query = "UPDATE `pays` SET `delete`='1' WHERE Id='".mysql_escape_string($del_id)."'";
$result = mysql_query($query) or die(mysql_error());
}
}	

?>


