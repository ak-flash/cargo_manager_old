<?php
// Подключение и выбор БД
include "../config.php";

function is_valid_inn($inn)
{
    if ( preg_match('/\D/', $inn) ) return false;
    
    $inn = (string) $inn;
    $len = strlen($inn);
    
    if ( $len === 10 )
    {
        return $inn[9] === (string) (((
            2*$inn[0] + 4*$inn[1] + 10*$inn[2] + 
            3*$inn[3] + 5*$inn[4] +  9*$inn[5] + 
            4*$inn[6] + 6*$inn[7] +  8*$inn[8]
        ) % 11) % 10);
    }
    elseif ( $len === 12 )
    {
        $num10 = (string) (((
             7*$inn[0] + 2*$inn[1] + 4*$inn[2] +
            10*$inn[3] + 3*$inn[4] + 5*$inn[5] + 
             9*$inn[6] + 4*$inn[7] + 6*$inn[8] +
             8*$inn[9]
        ) % 11) % 10);
        
        $num11 = (string) (((
            3*$inn[0] +  7*$inn[1] + 2*$inn[2] +
            4*$inn[3] + 10*$inn[4] + 3*$inn[5] +
            5*$inn[6] +  9*$inn[7] + 4*$inn[8] +
            6*$inn[9] +  8*$inn[10]
        ) % 11) % 10);
        
        return $inn[11] === $num11 && $inn[10] === $num10;
    }
    
    return false;
}



function CheckStr($srt){ 
 return !mb_ereg("[^A-Za-zа-яА-Я0-9_-]",$srt);     
}

function CheckInt($srt){ 
 return !mb_ereg("[^0-9]",$srt);     
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
if(@$_POST['company_name']==""||mb_ereg("[^A-Za-zа-яА-Я 0-9_-]",$_POST['company_name'])){echo $error.'"Компания"'.$err.ValFail('company_name'); $validate=false;} else {echo ValOk('company_name');}



if(@$_POST['company_adr_f']==""){echo $error.'"Адрес (фактический)"'.$err; $validate=false;}
if(@$_POST['company_adr_u']==""){echo $error.'"Адрес (юридический)"'.$err; $validate=false;}


if(@$_POST['company_phone']==""){echo $error.'"Телефон"'.$err.ValFail('company_phone'); $validate=false;}  else {echo ValOk('company_phone');}

//if(@$_POST['company_mail']!=""){if(!preg_match("/^([a-z,._,0-9])+@([a-z,._,0-9])+(.([a-z])+)+$/", $_POST['company_mail'])){echo $error.'"E-mail"'.$err.ValFail('company_mail'); $validate=false;} else {echo ValOk('company_mail');}} 
if(@$_POST['company_inn']==""||!CheckInt($_POST['company_inn'])){echo $error.'"ИНН"'.$err.ValFail('company_inn');  $validate=false;} else {if(!is_valid_inn($_POST['company_inn'])&&$_POST['company_pref']!=2) {echo $error."ИНН - не существует!".$err.ValFail('company_inn');$validate=false;} else echo ValOk('company_inn');}
if(@$_POST['company_kpp']==""||!CheckInt($_POST['company_kpp'])){echo $error.'"КПП"'.$err.ValFail('company_kpp'); $validate=false;} else {echo ValOk('cl_kpp');}

if(@$_POST['company_bank']==""){echo $error.'"Банк"'.$err.ValFail('company_bank'); $validate=false;} else {echo ValOk('company_bank');}
if(@$_POST['company_rs']==""||!CheckInt($_POST['company_rs'])){echo $error.'"Рассчетный счет"'.$err.ValFail('company_rs'); $validate=false;} else {echo ValOk('company_rs');}
if(!CheckInt($_POST['company_ogrn'])){echo $error.'"ОГРН"'.$err.ValFail('company_ogrn'); $validate=false;} else {if(@$_POST['company_ogrn']!="") echo ValOk('company_ogrn');}

if(@$_POST['company_chief']==""||mb_ereg("[^A-Za-zа-яА-Я 0-9_-№]",$_POST['company_chief'])){echo $error.'"Ответственное лицо" - полностью'.$err.ValFail('company_chief');$validate=false;} else {echo ValOk('company_chief');}

if((@$_POST['company_dchief']=="")&&$_POST['company_pref']!=3){echo $error.'"Должность ответственного лица"'.$err.ValFail('company_dchief');$validate=false;} else {echo ValOk('company_dchief');}

if(@$_POST['company_chief_contract']==""||mb_ereg("[^A-Za-zа-яА-Я 0-9_-№]",$_POST['company_chief_contract'])){echo $error.'"Ответственное лицо"<br> ФИО в родительном падеже!'.$err.ValFail('company_chief_contract');$validate=false;} else {echo ValOk('company_chief_contract');}

if((@$_POST['company_dchief_contract']==""||mb_ereg("[^A-Za-zа-яА-Я 0-9_-№]",$_POST['company_dchief_contract']))&&$_POST['company_pref']!=3){echo $error.'"Должность ответственного лица"<br> ФИО в родительном падеже!'.$err.ValFail('company_dchief_contract');$validate=false;} else {echo ValOk('company_dchief_contract');}


	
if($validate)
{

$name=$_POST['company_name'];
$short_name=$_POST['company_short_name'];
$company_id=(int)$_POST['company_id'];
$pref=(int)$_POST['company_pref'];
$adr_f=mysql_real_escape_string(stripslashes($_POST['company_adr_f']));
$adr_u=mysql_real_escape_string(stripslashes($_POST['company_adr_u']));
$inn=$_POST['company_inn'];
$kpp=$_POST['company_kpp'];
$ogrn=$_POST['company_ogrn'];
$rs=$_POST['company_rs'];
$bank=mysql_real_escape_string(stripslashes($_POST['company_bank']));
$bik=$_POST['company_bik'];
$ks=$_POST['company_ks'];
$nds=(int)$_POST['c_nds'];
$notify=mysql_real_escape_string(stripslashes($_POST['company_notify']));


$pref_phone=$_POST['company_pref_phone'];
$chief=mysql_real_escape_string(stripslashes($_POST['company_chief']));

$ochief=mysql_real_escape_string(stripslashes($_POST['company_ochief']));

$phone=mysql_real_escape_string(stripslashes($_POST['company_phone']));
$email=$_POST['company_mail'];

$chief_contract=mysql_real_escape_string(stripslashes($_POST['company_chief_contract']));
if($pref!=3){
$dchief=mysql_real_escape_string($_POST['company_dchief']);
$dchief_contract=mysql_real_escape_string(stripslashes($_POST['company_dchief_contract']));}

	
	if(@$_POST['edit']=="1")
{
$query = "UPDATE `company` SET  `name`='$name',`short_name`='$short_name',`pref`='$pref',`adr_f`='$adr_f',`inn`='$inn',`kpp`='$kpp',`ogrn`='$ogrn',`rs`='$rs',`bank`='$bank',`bik`='$bik',`ks`='$ks',`notify`='$notify',`chief`='$chief',`chief_status`='$dchief',`ochief_contract`='$ochief',`phone`='$phone',`email`='$email',`pref_phone`='$pref_phone',`chief_contract`='$chief_contract',`dchief_contract`='$dchief_contract',`adr_u`='$adr_u',`nds`='$nds' WHERE `id`='$company_id'";

} else {

$query = "INSERT INTO `company` (`name`,`short_name`,`nds`,`pref`,`adr_f`,`inn`,`kpp`,`ogrn`,`rs`,`bank`,`bik`,`ks`,`notify`,`chief`,`chief_status`,`ochief_contract`,`phone`,`email`,`pref_phone`,`chief_contract`,`dchief_contract`,`adr_u`) VALUES ('$name','$short_name','$nds','$pref','$adr_f','$inn','$kpp','$ogrn','$rs','$bank','$bik','$ks','$notify','$chief','$dchief','$ochief','$phone','$email','$pref_phone','$chief_contract','$dchief_contract','$adr_u')";
}


$result = mysql_query($query) or die(mysql_error());
echo 'Сохранено!|1|'; 
}
?>


