<?php  
include "../config.php";
session_start();
$validate=true;

if(@$_POST['list_pay_id']==""){echo 'Выберите платежи для импорта!';$validate=false;}
if(@$_POST['create']!="true"){echo 'Ошибка!';$validate=false;}

if($validate){
$add_name=(int)$_SESSION['user_id'];
$app=(int)@$_POST['app'];
$list_pay_id=@$_POST['list_pay_id'];
if ($list_pay_id){
	 foreach($list_pay_id as $in){
	 $elems= explode("|",$in);

$datas= explode(".",trim($elems[1]));
$data=$datas[2]."-".$datas[1]."-".$datas[0];
$cash=(int)$elems[3]*100;
$query = "INSERT INTO `pays` (`date`,`way`,`nds`,`category`,`appoint`,`order`,`cash`,`status`,`notify`,`add_name`,`payment_source`,`Id`,`pay_bill`) VALUES ('$data','2','0','2','$app','','$cash','0','$elems[4]','$add_name','$elems[5]','$elems[0]','$elems[2]')";
$result = mysql_query($query) or die(mysql_error());

}
}

echo 'Платежи созданы!|1';
	 
}

?>