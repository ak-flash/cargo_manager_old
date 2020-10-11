<?php
// Подключение и выбор БД
include "../config.php";
session_start();

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
 return !mb_ereg("[^A-Za-zа-яёА-ЯЁ0-9_-]",$srt);     
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

if(@$_POST['cl_name']==""){echo $error.'"Имя клиента"'.$err.ValFail('cl_name'); $validate=false;} else {if(@$_POST['cl_name']!=""&&mb_ereg("[^A-Za-zа-яёА-ЯЁ0-9._ +-]",$_POST['tr_name'])){echo $error.'"Имя клиента"'.$err.ValFail('cl_name'); $validate=false;} else echo ValOk('cl_name');}

if(@$_POST['cl_name']!=""&&@$_POST['edit']!="1"&&$_SESSION["group"]!=1&&$_SESSION["group"]!=2){
$query_clients = "SELECT `Id` FROM `clients` WHERE `name`='".mysql_escape_string(@$_POST['cl_name'])."' AND `cl_manager`='".mysql_escape_string($_SESSION["user_id"])."'";
$result_clients = mysql_query($query_clients) or die(mysql_error());
if (mysql_num_rows($result_clients)>0){
echo $error.'"<font size=3>Такой Клиент уже существует!</font>"'.$err; $validate=false;
}
}


//if(!intval(@$_POST['adr_cl_select_f'])){echo $error.'"Адрес (фактический)"'.$err; $validate=false;}
//if(!intval(@$_POST['adr_cl_select_u'])){echo $error.'"Адрес (юридический)"'.$err; $validate=false;}

if(@$_POST['cl_point']=="0"){echo $error.'"Точка оплаты"'.$err; $validate=false;}
if(@$_POST['cl_time']==""){echo $error.'"Период расчётов"'.$err; $validate=false;}

if(@$_POST['cl_support']==""||mb_ereg("[^A-Za-zа-яёА-ЯЁ -\"]",$_POST['cl_support'])){echo $error.'"Контактное лицо" ФИО полностью!'.$err.ValFail('cl_support'); $validate=false;} else {echo ValOk('cl_support');}

if(@$_POST['cl_chief']==""||mb_ereg("[^A-Za-zа-яёА-ЯЁ -\"]",$_POST['cl_chief'])){echo $error.'"Ответственное лицо" ФИО полностью!'.$err.ValFail('cl_chief'); $validate=false;} else {echo ValOk('cl_chief');}

if(@$_POST['cl_phone']==""){echo $error.'"Телефон"'.$err.ValFail('cl_phone'); $validate=false;}  else {echo ValOk('cl_phone');}

//if(@$_POST['cl_mail']!=""){if(!preg_match("/^([a-z,._,0-9-])+@([a-z,._,0-9-])+(.([a-z])+)+$/", $_POST['cl_mail'])){echo $error.'"E-mail"'.$err.ValFail('cl_mail'); $validate=false;} else {echo ValOk('cl_mail');}} 

if((@$_POST['cl_inn']==""||!CheckInt($_POST['cl_inn']))&&@$_POST['cl_nds']!="2"){echo $error.'"ИНН"'.$err.ValFail('cl_inn');  $validate=false;} else {if(!is_valid_inn($_POST['cl_inn'])&&$_POST['cl_pref']!=2&&@$_POST['cl_nds']!="2") {echo $error."ИНН - не существует!".$err.ValFail('cl_inn');$validate=false;} else {echo ValOk('cl_inn');

if(@$_POST['edit']!="1"){$query = "SELECT `id`,`name`,`cl_inn` FROM `clients` WHERE `cl_inn`='".mysql_escape_string(@$_POST['cl_inn'])."'";
$result = mysql_query($query) or die(mysql_error());
 if (mysql_num_rows($result)>0)
		{
		$cl = mysql_fetch_row($result);
		//$validate=false;
		echo '<br><font size="4"> Клиент <font color="red" >«'.addslashes($cl[1]).'»</font><br>с ИНН: <font color="red">'.addslashes($cl[2]).'</font> уже имеется в базе!'.$err.ValFail('cl_name');
		}
		} 


}}




if(@$_POST['cl_kpp']!=""&&!CheckInt($_POST['cl_kpp'])&&@$_POST['cl_nds']!="2"){echo $error.'"КПП"'.$err.ValFail('cl_kpp'); $validate=false;}
if((@$_POST['cl_bik']==""||!CheckInt($_POST['cl_bik']))&&@$_POST['cl_nds']!="2"){echo $error.'"БИК'.$err.ValFail('cl_bik'); $validate=false;} else {echo ValOk('cl_bik');}
if(@$_POST['cl_bank']==""&&@$_POST['cl_nds']!="2"){echo $error.'"Банк плательщика"'.$err.ValFail('cl_bank'); $validate=false;} else {echo ValOk('cl_bank');}
if((@$_POST['cl_rs']==""||!CheckInt($_POST['cl_rs']))&&@$_POST['cl_nds']!="2"){echo $error.'"Рассчетный счет"'.$err.ValFail('cl_rs'); $validate=false;} else {echo ValOk('cl_rs');}
//if(!CheckInt($_POST['cl_ogrn'])){echo $error.'"ОГРН"'.$err.ValFail('cl_ogrn'); $validate=false;} else {if(@$_POST['cl_ogrn']!="") echo ValOk('cl_ogrn');}
//if(@$_POST['cl_ogrn']!=""&&!CheckInt($_POST['cl_ogrn'])&&@$_POST['cl_nds']!="2"){echo $error.'"ОГРН"'.$err.ValFail('cl_ogrn'); $validate=false;}
//if(@$_POST['cl_kpp']!=""&&!CheckInt($_POST['cl_kpp'])&&@$_POST['cl_nds']!="2"){echo $error.'"К/c"'.$err.ValFail('cl_kpp'); $validate=false;}

//if(@$_POST['cl_chief']==""||mb_ereg("[^A-Za-zа-яёА-ЯЁ 0-9_-№]",$_POST['cl_chief'])){echo $error.'"Ответственное лицо" - полностью'.$err.ValFail('cl_chief');$validate=false;} else {echo ValOk('cl_chief');}

//if((@$_POST['cl_dchief']==""||mb_ereg("[^A-Za-zа-яёА-ЯЁ 0-9_-№]",$_POST['cl_dchief']))&&$_POST['cl_pref']!=3){echo $error.'"Должность ответственного лица"'.$err.ValFail('cl_dchief');$validate=false;} else {echo ValOk('cl_dchief');}

//if(@$_POST['cl_chief_contract']==""||mb_ereg("[^A-Za-zа-яёА-ЯЁ 0-9_-№]",$_POST['cl_chief_contract'])){echo $error.'"Ответственное лицо"<br> ФИО в родительном падеже!'.$err.ValFail('cl_chief_contract');$validate=false;} else {echo ValOk('cl_chief_contract');}

//if((@$_POST['cl_dchief_contract']==""||mb_ereg("[^A-Za-zа-яёА-ЯЁ 0-9_-№]",$_POST['cl_dchief_contract']))&&$_POST['cl_pref']!=3){echo $error.'"Должность ответственного лица"<br> ФИО в родительном падеже!'.$err.ValFail('cl_dchief_contract');$validate=false;} else {echo ValOk('cl_dchief_contract');}


	
if($validate)
{

$name=mysql_real_escape_string(stripslashes($_POST['cl_name']));
$client_id=(int)$_POST['client_id'];
$pref=(int)$_POST['cl_pref'];
$nds=(int)$_POST['cl_nds'];
$cl_adr_f=(int)$_POST['adr_cl_select_f'];
$cl_adr_u=(int)$_POST['adr_cl_select_u'];
$cl_cont=(int)$_POST['cl_cont_sel'];
$cl_point=(int)$_POST['cl_point'];
$cl_time=(int)$_POST['cl_time'];
$cl_limit=(int)$_POST['cl_limit'];
$cl_limit_order=(int)$_POST['cl_limit_order'];
$cl_inn=$_POST['cl_inn'];
$cl_kpp=$_POST['cl_kpp'];
$cl_ogrn=$_POST['cl_ogrn'];
$cl_rs=$_POST['cl_rs'];
$cl_bank=mysql_real_escape_string(stripslashes($_POST['cl_bank']));
$cl_bik=$_POST['cl_bik'];
$cl_ks=$_POST['cl_ks'];
$cl_order=(int)$_POST['cl_order'];
$cl_manager=(int)$_POST['cl_manager'];
//$cl_manager=(int)$_SESSION['user_id'];

$cl_notify=$_POST['cl_notify'];
$cl_orderform=$_POST['cl_orderform'];

$cl_pref_phone=$_POST['cl_pref_phone'];
$cl_chief=mysql_real_escape_string(stripslashes($_POST['cl_chief']));
$cl_icq=mysql_real_escape_string(stripslashes($_POST['cl_icq']));
$cl_ochief=mysql_real_escape_string(stripslashes($_POST['cl_ochief']));

$cl_dsupport=mysql_real_escape_string(stripslashes($_POST['cl_dsupport']));
$cl_support=mysql_real_escape_string(stripslashes($_POST['cl_support']));
$cl_phone=mysql_real_escape_string(stripslashes($_POST['cl_phone']));
$cl_mail=$_POST['cl_mail'];

$contract=mysql_real_escape_string(stripslashes($_POST['contract']));
$cl_orderform=$_POST['cl_orderform'];
$cl_chief_contract=mysql_real_escape_string(stripslashes($_POST['cl_chief_contract']));
if($pref!=3){
$cl_dchief=mysql_real_escape_string(stripslashes($_POST['cl_dchief']));
$cl_dchief_contract=mysql_real_escape_string(stripslashes($_POST['cl_dchief_contract']));}

switch ($pref) {
case '1': $pref_cl='&nbsp;&nbsp;&nbsp;ООО';break;
case '2': $pref_cl='&nbsp;&nbsp;&nbsp;ОАО';break;
case '3': $pref_cl='&nbsp;&nbsp;&nbsp;ИП&nbsp;';break;
case '4': $pref_cl='&nbsp;&nbsp;&nbsp;ЗАО;';break;
case '5': $pref_cl='&nbsp;&nbsp;&nbsp;';break;}
	
	if(@$_POST['edit']=="1")
{
$query = "UPDATE `clients` SET  `name`='$name',`nds`='$nds',`pref`='$pref',`cl_adr_f`='$cl_adr_f',`cl_cont`='$cl_cont',`cl_point`='$cl_point',`cl_time`='$cl_time',`cl_inn`='$cl_inn',`cl_kpp`='$cl_kpp',`cl_ogrn`='$cl_ogrn',`cl_rs`='$cl_rs',`cl_bank`='$cl_bank',`cl_bik`='$cl_bik',`cl_ks`='$cl_ks',`cl_order`='$cl_order',`cl_manager`='$cl_manager',`cl_orderform`='$cl_orderform',`notify`='$cl_notify',`cl_chief`='$cl_chief',`cl_dchief`='$cl_dchief',`cl_ochief`='$cl_ochief',`cl_support`='$cl_support',`cl_dsupport`='$cl_dsupport',`cl_phone`='$cl_phone',`cl_mail`='$cl_mail',`contract`='$contract',`cl_pref_phone`='$cl_pref_phone',`cl_chief_contract`='$cl_chief_contract',`cl_dchief_contract`='$cl_dchief_contract',`cl_adr_u`='$cl_adr_u',`cl_icq`='$cl_icq',`cl_limit`='$cl_limit',`cl_limit_order`='$cl_limit_order' WHERE `id`='$client_id'";

} else {

$query = "INSERT INTO `clients` (`name`,`nds`,`pref`,`cl_adr_f`,`cl_cont`,`cl_point`,`cl_time`,`cl_inn`,`cl_kpp`,`cl_ogrn`,`cl_rs`,`cl_bank`,`cl_bik`,`cl_ks`,`cl_order`,`cl_manager`,`cl_orderform`,`cl_chief`,`cl_dchief`,`cl_ochief`,`cl_support`,`cl_dsupport`,`cl_phone`,`cl_mail`,`notify`,`contract`,`cl_pref_phone`,`cl_chief_contract`,`cl_dchief_contract`,`cl_adr_u`,`cl_icq`,`cl_limit`,`cl_limit_order`) VALUES ('$name','$nds','$pref','$cl_adr_f','$cl_cont','$cl_point','$cl_time','$cl_inn','$cl_kpp','$cl_ogrn','$cl_rs','$cl_bank','$cl_bik','$cl_ks','$cl_order','$cl_manager','$cl_orderform','$cl_chief','$cl_dchief','$cl_ochief','$cl_support','$cl_dsupport','$cl_phone','$cl_mail','$cl_notify','$contract','$cl_pref_phone','$cl_chief_contract','$cl_dchief_contract','$cl_adr_u','$cl_icq','$cl_limit','$cl_limit_order')";
}
$query_block = "SELECT `block` FROM `clients` WHERE `Id`='".mysql_escape_string($client_id)."'";
$result_block = mysql_query($query_block) or die(mysql_error());
$block = mysql_fetch_row($result_block);
if($block[0]!='1')
{
$result = mysql_query($query) or die(mysql_error());
echo 'Сохранено!|1|';
if($client_id!=""&&$client_id!=0) echo $client_id; else echo mysql_insert_id();

echo '|'.$name.'|'.$nds.'|'.$pref_cl.'|'.$cl_time.'|'.$cl_point.'|'.$pref.'|'.$cl_cont.'|'; 

if (mysql_insert_id()!=0) {echo '1|'.base64_encode(mysql_insert_id());}


} else {
echo '<font size="3" color="red">Клиент заблокирован для редактирования!</font>';
}
}
?>


