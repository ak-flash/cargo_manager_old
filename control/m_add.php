<?php
// Подключение и выбор БД
include "../config.php";

if(@$_POST['num']!="")
{
$num=$_POST['num'];
$nds=$_POST['nds'];  
$date=$_POST['date']; 
$query = "UPDATE `order` SET `num`='$num' WHERE `Id`='$num'";
$result = mysql_query($query) or die(mysql_error());

echo 'Сохранено!<br>'.$date.'<br>';
if ($nds=='1'){echo 'С НДС';} else {echo 'Без НДС';}

}	

?>