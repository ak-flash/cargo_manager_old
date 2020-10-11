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
 return !mb_ereg("[^A-Za-zа-яА-Я0-9 _-]",$srt);   
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
if(@$_POST['tr_name']==""){echo $error.'"Перевозчик"'.$err.ValFail('tr_name'); $validate=false;} else {if(@$_POST['tr_name']!=""&&mb_ereg("[^A-Za-zа-яёА-ЯЁ0-9._ +-]",$_POST['tr_name'])){echo $error.'"Перевозчик"'.$err.ValFail('tr_name'); $validate=false;} else echo ValOk('tr_name');}

//if(@$_POST['edit']!="1"&&@$_POST['tr_name']=="ВТЛ-Строй") echo $error.'"Перевозчик ВТЛ-Строй уже создан!"'.$err; $validate=false;
	
if(@$_POST['tr_name']!=""&&@$_POST['edit']!="1"){
$query_transp = "SELECT `Id` FROM `transporters` WHERE `name`='".mysql_escape_string(@$_POST['tr_name'])."' AND `tr_inn`='".mysql_escape_string(@$_POST['tr_inn'])."'";
$result_transp = mysql_query($query_transp) or die(mysql_error());
if (mysql_num_rows($result_transp)>0){
echo $error.'"<font size=3>Такой Перевозчик уже существует!</font>"'.$err; $validate=false;
}
}

if(@$_POST['tr_code_ati']==""){echo $error.'"Код в АТИ"'.$err; $validate=false;}
//if((!intval(@$_POST['adr_tr_select_f'])||(int)$_POST['adr_tr_select_f']==0)&&@$_POST['tr_nds']!="2"){echo $error.'"Адрес (фактический)"'.$err; $validate=false;}
//if((!intval(@$_POST['adr_tr_select_u'])||(int)$_POST['adr_tr_select_u']==0)&&@$_POST['tr_nds']!="2"){echo $error.'"Адрес (юридический)"'.$err; $validate=false;}

if(@$_POST['tr_point']=="0"&&@$_POST['tr_nds']!="2"){echo $error.'"Точка оплаты"'.$err; $validate=false;}
if(@$_POST['tr_time']==""&&@$_POST['tr_nds']!="2"){echo $error.'"Период расчётов"'.$err; $validate=false;}
//if((@$_POST['tr_support']==""||!CheckStr($_POST['tr_support']))&&@$_POST['tr_nds']!="2"){echo $error.'"Контактное лицо" ФИО полностью!'.$err.ValFail('tr_support'); $validate=false;} else {echo ValOk('tr_support');}
//if((@$_POST['tr_phone']=="")&&@$_POST['tr_nds']!="2"){echo $error.'"Телефон"'.$err.ValFail('tr_phone'); $validate=false;}  else {echo ValOk('tr_phone');}

$mail_spam="";
$query_gorod = "SELECT `Id` FROM `adress` WHERE `id`='".(int)$_POST['adr_tr_select_f']."' AND `obl` LIKE 'Волгоградская'";
$result_gorod = mysql_query($query_gorod) or die(mysql_error());
if (mysql_num_rows($result_gorod)>0){
if(@$_POST['tr_mail']==""){echo $error.'"E-mail"'.$err.ValFail('tr_mail'); $validate=false;} else {echo ValOk('tr_mail');$mail_spam=$_POST['tr_mail'];}
}





//if((@$_POST['tr_inn']==""||!CheckInt($_POST['tr_inn']))&&@$_POST['tr_nds']!="2"){echo $error.'"ИНН"'.$err.ValFail('tr_inn');  $validate=false;} else {if(!is_valid_inn($_POST['tr_inn'])&&$_POST['tr_pref']!=3&&@$_POST['tr_nds']!="2") {echo $error."ИНН - не существует!".$err.ValFail('tr_inn');$validate=false;} else echo ValOk('tr_inn');}
//if(@$_POST['tr_kpp']!=""&&!CheckInt($_POST['tr_kpp'])&&@$_POST['tr_nds']!="2"){echo $error.'"КПП"'.$err.ValFail('tr_kpp'); $validate=false;} else {echo ValOk('tr_kpp');}
//if((@$_POST['tr_bik']==""||!CheckInt($_POST['tr_bik']))&&@$_POST['tr_nds']!="2"){echo $error.'"БИК'.$err.ValFail('tr_bik'); $validate=false;} else {echo ValOk('tr_bik');}
//if(@$_POST['tr_bank']==""&&@$_POST['tr_nds']!="2"){echo $error.'"Банк плательщика"'.$err.ValFail('tr_bank'); $validate=false;} else {echo ValOk('tr_bank');}
//if((@$_POST['tr_rs']==""||!CheckInt($_POST['tr_rs']))&&@$_POST['tr_nds']!="2"){echo $error.'"Рассчетный счет"'.$err.ValFail('tr_rs'); $validate=false;} else {echo ValOk('tr_rs');}
//if(!CheckInt($_POST['tr_ogrn'])){echo $error.'"ОГРН"'.$err.ValFail('tr_ogrn'); $validate=false;} else {if(@$_POST['tr_ogrn']!="") echo ValOk('tr_ogrn');}

//if((@$_POST['tr_chief']==""||!CheckStr($_POST['tr_chief']))&&@$_POST['tr_nds']!="2"){echo $error.'"Ответственное лицо" - полностью'.$err.ValFail('tr_chief');$validate=false;} else {echo ValOk('tr_chief');}

//if(((@$_POST['tr_dchief']==""||!CheckStr($_POST['tr_dchief']))&&@$_POST['tr_nds']!="2")&&$_POST['tr_pref']!=3){echo $error.'"Должность ответственного лица"'.$err.ValFail('tr_dchief');$validate=false;} else {echo ValOk('tr_dchief');}

//if((@$_POST['tr_chief_contract']==""||!CheckStr($_POST['tr_chief_contract']))&&@$_POST['tr_nds']!="2"){echo $error.'"Ответственное лицо"<br> ФИО в родительном падеже!'.$err.ValFail('tr_chief_contract');$validate=false;} else {echo ValOk('tr_chief_contract');}

//if(((@$_POST['tr_dchief_contract']==""||!CheckStr($_POST['tr_dchief_contract']))&&@$_POST['tr_nds']!="2")&&$_POST['tr_pref']!=3){echo $error.'"Должность ответственного лица"<br> ФИО в родительном падеже!'.$err.ValFail('tr_dchief_contract');$validate=false;} else {echo ValOk('tr_dchief_contract');}

	
if($validate)
{
$transporter=mysql_real_escape_string(stripslashes($_POST['tr_name']));

$tr_manager=(int)$_POST['tr_manager'];
$tr_icq=mysql_real_escape_string(stripslashes($_POST['tr_icq']));
$tr_id=$_POST['tr_id'];
$tr_adr_f=(int)$_POST['adr_tr_select_f'];
$tr_adr_u=(int)$_POST['adr_tr_select_u'];
$tr_pref=$_POST['tr_pref'];
$tr_nds=$_POST['tr_nds'];
$tr_point=$_POST['tr_point'];

$tr_cont=$_POST['tr_cont'];
$tr_time=$_POST['tr_time'];
$contract=mysql_real_escape_string(stripslashes($_POST['contract']));
$tr_inn=$_POST['tr_inn'];
$tr_kpp=$_POST['tr_kpp'];
$tr_ogrn=$_POST['tr_ogrn'];
$tr_rs=$_POST['tr_rs'];
$tr_bank=mysql_real_escape_string(stripslashes($_POST['tr_bank']));
$tr_bik=$_POST['tr_bik'];
$tr_ks=$_POST['tr_ks'];

$tr_support=mysql_real_escape_string(stripslashes($_POST['tr_support']));
$tr_phone=mysql_real_escape_string(stripslashes($_POST['tr_phone']));
$tr_mail=mysql_real_escape_string(stripslashes($_POST['tr_mail']));

$tr_notify=mysql_real_escape_string(stripslashes($_POST['tr_notify']));
$tr_orderform=(int)$_POST['tr_orderform'];

$tr_pref_phone=$_POST['tr_pref_phone'];
$tr_chief=mysql_real_escape_string(stripslashes($_POST['tr_chief']));
$tr_ochief=mysql_real_escape_string(stripslashes($_POST['tr_ochief']));
$tr_dsupport=$_POST['tr_dsupport'];
$tr_support=$_POST['tr_support'];

$tr_orderform=$_POST['tr_orderform'];
$tr_chief_contract=mysql_real_escape_string(stripslashes($_POST['tr_chief_contract']));
if($tr_pref!=3){
$tr_dchief=mysql_real_escape_string(stripslashes($_POST['tr_dchief']));
$tr_dchief_contract=mysql_real_escape_string(stripslashes($_POST['tr_dchief_contract']));}

$tr_code_ati=mysql_real_escape_string(stripslashes($_POST['tr_code_ati']));

switch ($tr_pref) {
case '1': $pref_tr='&nbsp;&nbsp;&nbsp;ООО';break;
case '2': $pref_tr='&nbsp;&nbsp;&nbsp;ОАО';break;
case '3': $pref_tr='&nbsp;&nbsp;&nbsp;ИП&nbsp;';break;
case '4': $pref_tr='&nbsp;&nbsp;&nbsp;ЗАО';break;}

if(@$_POST['edit']=="1")
{
$query = "UPDATE `transporters` SET `name`='$transporter',`nds`='$tr_nds',`pref`='$tr_pref',`tr_cont`='$tr_cont',`tr_point`='$tr_point',`tr_time`='$tr_time',`tr_support`='$tr_support',`tr_phone`='$tr_phone',`tr_mail`='$tr_mail',`tr_notify`='$tr_notify',`tr_inn`='$tr_inn',`tr_kpp`='$tr_kpp',`tr_ogrn`='$tr_ogrn',`tr_rs`='$tr_rs',`tr_bank`='$tr_bank',`tr_bik`='$tr_bik',`tr_ks`='$tr_ks',`tr_adr_f`='$tr_adr_f',`tr_adr_u`='$tr_adr_u',`tr_orderform`='$tr_orderform',`contract`='$contract',`tr_pref_phone`='$tr_pref_phone',`tr_chief`='$tr_chief',`tr_chief`='$tr_chief',`tr_dchief`='$tr_dchief',`tr_ochief`='$tr_ochief',`tr_dsupport`='$tr_dsupport',`tr_chief_contract`='$tr_chief_contract',`tr_dchief_contract`='$tr_dchief_contract',`tr_manager`='$tr_manager',`tr_icq`='$tr_icq',`tr_code_ati`='$tr_code_ati' WHERE `id`='".$tr_id."'";
	} else {
$query = "INSERT INTO `transporters` (`name`,`nds`,`pref`,`tr_cont`,`tr_point`,`tr_time`,`tr_support`,`tr_phone`,`tr_mail`,`tr_notify`,`tr_adr_f`,`tr_adr_u`,`tr_inn`,`tr_kpp`,`tr_ogrn`,`tr_rs`,`tr_bank`,`tr_bik`,`tr_ks`,`tr_orderform`,`contract`,`tr_pref_phone`,`tr_chief`,`tr_dchief`,`tr_ochief`,`tr_dsupport`,`tr_chief_contract`,`tr_dchief_contract`,`tr_manager`,`tr_icq`,`tr_code_ati`) VALUES ('$transporter','$tr_nds','$tr_pref','$tr_cont','$tr_point','$tr_time','$tr_support','$tr_phone','$tr_mail','$tr_notify','$tr_adr_u','$tr_adr_f','$tr_inn','$tr_kpp','$tr_ogrn','$tr_rs','$tr_bank','$tr_bik','$tr_ks','$tr_orderform','$contract','$tr_pref_phone','$tr_chief','$tr_dchief','$tr_ochief','$tr_dsupport','$tr_chief_contract','$tr_dchief_contract','$tr_manager','$tr_icq','$tr_code_ati')";

if($mail_spam!=""){ 
$f = fopen("../mail.txt", "w");
fwrite($f, $mail_spam);
fclose($f);}
}

$query_block = "SELECT `block` FROM `transporters` WHERE `Id`='".$tr_id."'";
$result_block = mysql_query($query_block) or die(mysql_error());
$block = mysql_fetch_row($result_block);
if($block[0]!='1')
{
$result = mysql_query($query) or die(mysql_error());
echo 'Сохранено!|1|';
if($tr_id!=0&&$tr_id!="") echo $tr_id; else echo mysql_insert_id();
echo '|'.$transporter.'|'.$tr_nds.'|'.$pref_tr.'|'.$tr_time.'|'.$tr_point.'|'.$tr_pref.'|'.$tr_cont.'|'; 

if (mysql_insert_id()!=0) {echo '1|'.base64_encode(mysql_insert_id());}

} else {
echo '<font size="3" color="red">Перевозчик заблокирован для редактирования!</font>';
}
}
?>


