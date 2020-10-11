<?php
// Подключение и выбор БД
include "../../config.php";
$error='Не заполнено поле <font color="red">';
$err='</font><br>';
$validate=true;
if(@$_POST['repair_auto']=="0"){echo '<font color="red" size="5">Выберите транспорт!'.$err;$validate=false;}

//if(@$_POST['repair_details']==""){echo $error.'"Обьем"'.$err;$validate=false;}

if(@$_POST['repair_area']==0){echo $error.'"Назначение"'.$err;$validate=false;}

	
if($validate)
{
$repair_auto=(int)$_POST['repair_auto'];
$repair_drv=(int)$_POST['repair_drv'];
$repair_area=(int)$_POST['repair_area'];
$repair_details=addslashes($_POST['repair_details']);

$in_elements  = explode("/",$_POST['date_repair']);
$date_repair=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));


$cash_repair=(float)$_POST['cash_repair']*100;


if(@$_POST['edit']=="1"){
$query = "UPDATE `vtl_repair` SET `date`='$date_repair',`auto`='$repair_auto',`driver`='$repair_drv',`area`='$repair_area',`details`='$repair_details',`cash`='$cash_repair' WHERE `id`='".mysql_escape_string($_POST['repair_idd'])."'";
}
else
{
$query = "INSERT INTO `vtl_repair` (`date`,`auto`,`driver`,`area`,`details`,`cash`) VALUES ('$date_repair','$repair_auto','$repair_drv','$repair_area','$repair_details','$cash_repair')";
}
$result = mysql_query($query) or die(mysql_error());

echo 'Сохранено!|1';
} else {echo 'Не все поля заполнены!';}	

?>


