<?php
// Подключение и выбор БД
include "../config.php";


function ValFail($srt){ 
 return '<script type="text/javascript">$("#'.$srt.'").css({"border-color":"red","background-color":"#FFBABA","color":"#000"});</script>';     
}

function ValOk($srt){ 
 return '<script type="text/javascript">$("#'.$srt.'").css({"border-color":"#187C22","background-color":"#BAE2BD","color":"#000"});</script>';     
}

$error='Проверьте поле  <font color="red">';
$err='</font><br>';
$validate=true;
//if(@$_POST['w_name']==""||mb_ereg("[^A-Za-zа-яА-Я 0-9_-]",$_POST['w_name'])){echo $error.'"Ф.И.О."'.$err.ValFail('w_name'); $validate=false;} else {echo ValOk('w_name');}



if(@$_POST['w_adr']==""){echo $error.'"Адрес проживания"'.$err; $validate=false;}

if(@$_POST['w_phone']==""||mb_ereg("[^0-9-]",$_POST['w_phone'])){echo $error.'"Телефон"'.$err.ValFail('w_phone'); $validate=false;}  else {echo ValOk('w_phone');}


//if((int)$_POST['w_group']!=5&&@$_POST['w_mail']!=""){if(!preg_match("/^([a-z,._,0-9])+@([a-z,._,0-9])+(.([a-z])+)+$/", $_POST['w_mail'])){echo $error.'"E-mail"'.$err.ValFail('w_mail'); $validate=false;} else {echo ValOk('w_mail');}} 

//if((int)$_POST['w_group']!=5&&@$_POST['w_login']==""||mb_ereg("[^A-Za-z0-9_-]",$_POST['w_login'])){echo $error.'"Логин"'.$err.ValFail('w_login'); $validate=false;} else {echo ValOk('w_login');}

if((int)$_POST['w_group']!=5&&@$_POST['w_voip']!=""&&mb_ereg("[^A-Za-z0-9_-]",$_POST['w_voip'])){echo $error.'"Логин Rynga.com"'.$err.ValFail('w_voip'); $validate=false;} else {echo ValOk('w_voip');}

//if(@$_POST['w_icq']!=""&&mb_ereg("[^0-9]",$_POST['w_icq'])){echo $error.'"ICQ"'.$err.ValFail('w_icq'); $validate=false;}  else {echo ValOk('w_icq');}

if(@$_POST['w_pass']!=""&&mb_ereg("[^A-Za-z0-9_-]",$_POST['w_pass'])){echo $error.'"Пароль. (Только латинские буквы и цифры!)"'.$err.ValFail('w_pass'); $validate=false;} else {echo ValOk('w_pass');}

if(@$_POST['w_data']==""){echo $error.'"Дата приема на работу"'.$err.ValFail('w_data'); $validate=false;}  else {echo ValOk('w_data');}
	
if($validate)
{

$name=addslashes($_POST['w_name']);
$fake_name=addslashes($_POST['fake_name']);
$worker_id=(int)$_POST['worker_id'];

$w_adr=addslashes($_POST['w_adr']);
$w_group=(int)$_POST['w_group'];

$w_notify=mysql_real_escape_string(stripslashes($_POST['w_notify']));

$in_elements  = explode("/",$_POST['w_data']);
$w_data=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));

$in_elements  = explode("/",$_POST['w_date_birth']);
$w_date_birth=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));

$w_passport=mysql_real_escape_string(stripslashes($_POST['w_passport1'])).'|'.mysql_real_escape_string(stripslashes($_POST['w_passport2'])).'|'.mysql_real_escape_string(stripslashes($_POST['w_passport3']));


$w_mail=mysql_real_escape_string(stripslashes($_POST['w_mail']));
//$w_voip=mysql_real_escape_string(stripslashes($_POST['w_voip']));
$w_icq=(int)$_POST['w_icq'];
$w_ndfl=(int)$_POST['w_ndfl'];
$zarplata=(int)$_POST['w_zarplata'];
$motive=(int)$_POST['w_motive'];
$w_pref_phone=(int)$_POST['w_pref_phone'];
$w_phone=mysql_real_escape_string(stripslashes($_POST['w_phone']));







$w_ip=mysql_real_escape_string(stripslashes($_POST['w_ip']));  

$zday=(float)$_POST['w_zday'];
$zcity=(float)$_POST['w_zcity'];
$zrepair=(float)$_POST['w_zrepair'];
$zkm=(float)$_POST['w_zkm'];

if(strlen($_POST['w_pass'])==32){$w_pass=$_POST['w_pass'];} else {$w_pass=md5($_POST['w_pass']);}

	
	if(@$_POST['edit']=="1")
{
$query = "UPDATE `workers` SET  `name`='$name',`fake_name`='$fake_name',`adress`='$w_adr',`group`='$w_group',`phone`='$w_phone',`date_start`='$w_data',`zarplata`='$zarplata',`motive`='$motive',`email`='$w_mail',`icq`='$w_icq',`password`='$w_pass',`ip`='$w_ip',`notify`='$w_notify',`pref_phone`='$w_pref_phone',`ndfl`='$w_ndfl',`passport`='$w_passport',`z_day`='$zday',`z_city`='$zcity',`z_repair`='$zrepair',`z_km`='$zkm',`date_birth`='$w_date_birth' WHERE `id`='$worker_id'";

$result = mysql_query($query) or die(mysql_error());
} else {

$query = "INSERT INTO `workers` (`name`,`fake_name`,`adress`,`group`,`phone`,`date_start`,`zarplata`,`motive`,`email`,`icq`,`voip`,`login`,`password`,`ip`,`notify`,`pref_phone`,`ndfl`,`passport`,`z_day`,`z_city`,`z_repair`,`z_km`,`delete`,`date_birth`) VALUES ('$name','$fake_name','$w_adr','$w_group','$w_phone','$w_data','$zarplata','$motive','$w_mail','$w_icq','$w_voip','0','$w_pass','$w_ip','$w_notify','$w_pref_phone','$w_ndfl','$w_passport','$zday','$zcity','$zrepair','$zkm','0','$w_date_birth')";

$result = mysql_query($query) or die(mysql_error());
$result_id=mysql_insert_id();

switch ($w_group) {
case '2': $w_login='dir'.$result_id;break;
case '3': $w_login='manager'.$result_id;break;
case '4': $w_login='buh'.$result_id;break;
case '5': $w_login='driver'.$result_id;break;
}
	
$query_set_login = "UPDATE `workers` SET  `login`='$w_login' WHERE `id`='$result_id'";
$result_set_login = mysql_query($query_set_login) or die(mysql_error());
	

}



echo 'Сохранено!|1|';

}
?>


