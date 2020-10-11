<?php
// Подключение и выбор БД
include "../../config.php";

$validate=true;

if(@$_POST['card']==""){echo '<font color="red" size="3">Заполните номер карты!</font><br>';$validate=false;}

if(strlen(@$_POST['card'])!=4){echo '<font color="red" size="3">Номер карты из 4-х цифр!</font><br>';$validate=false;}


	
if($validate)
{
$auto=(int)$_POST['auto'];
$card=addslashes($_POST['card']);

if(@$_POST['edit']=="1"){
$query = "UPDATE `vtl_oil_card` SET `card`='$card',`car_id`='$auto' WHERE `id`='".mysql_escape_string($_POST['card_id'])."'";
$result = mysql_query($query) or die(mysql_error());


echo 'Сохранено!|1';}

else {

$query = "INSERT INTO `vtl_oil_card` (`card`,`car_id`) VALUES ('$card','$auto')";
$result = mysql_query($query) or die(mysql_error());


echo 'Сохранено!|1';
}

} else {echo 'Не все поля заполнены!';}	

?>


