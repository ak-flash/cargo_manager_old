<?php
// Подключение и выбор БД
include "../config.php";
session_start();
$adr_mode=0;

function CheckStr($srt){ 
 return !preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/",$srt);     
}

$error='Проверьте поле  <font color="red">';
$err='</font><br>';
$validate=true;
//if(@$_POST['postcode']==""||!CheckStr($_POST['postcode'])){echo $error.'"почтовый код"'.$err; $validate=false;}
if (@$_POST['country'] == "") {
    echo $error . '"страна"' . $err;
    $validate = false;
}
if ($_SESSION["group"] != 4) {
    if (@$_POST['region'] == "" && @$_POST['city'] != "Москва" && @$_POST['city'] != "Санкт-Петербург") {
        echo $error . '"область"' . $err;
        $validate = false;
    }
    if (@$_POST['city'] == "") {
        echo $error . '"город"' . $err;
        $validate = false;
    }
    if (@$_POST['street'] == "") {
        echo $error . '"улица"' . $err;
        $validate = false;
    }
    
    /*if (@$_POST['ttn'] != "on" && @$_POST['building'] == "") {
        echo $error . '"строение"' . $err;
        $validate = false;
    }*/

    //if(@$_POST['flat']==""||!intval($_POST['flat'])){echo $error.'"квартира(офис)"'.$err; $validate=false;} 

    if (@$_POST['adr_mode'] == "" || !intval($_POST['adr_mode'])) {
        echo $error . '"вид адреса"' . $err;
        $validate = false;
    }
//if(@$_POST['contact_name']==""){echo $error.'"контактное лицо"<br>';}
//if(@$_POST['contact_phone']==""){echo $error.'"номер телефона контактного лица"<br>';}
}

if (@$_POST['city'] != "" && @$_POST['region'] != "" && @$_POST['street'] != "" && @$_POST['building'] != "" && @$_POST['edit'] != "1") {
    $query_adr = "SELECT `Id` FROM `adress` WHERE `obl` LIKE '%" . mysql_real_escape_string(@$_POST['obl']) . "' AND `city` LIKE '%" . mysql_real_escape_string(@$_POST['city']) . "' AND `street` LIKE '%" . mysql_real_escape_string(@$_POST['street']) . "' AND `dom` LIKE '" . mysql_real_escape_string(@$_POST['building']) . "' AND `adr_mode` LIKE '%" . mysql_real_escape_string(@$_POST['adr_mode']) . "' AND `postcode`='" . mysql_real_escape_string(@$_POST['postcode']) . "'";
    $result_adr = mysql_query($query_adr) or die(mysql_error());
    if (mysql_num_rows($result_adr) > 0) {
        echo $error . '"<font size=3>Такой Адрес уже существует!</font>"' . $err;
        $validate = false;
    }
}
	
if($validate) {
    $postcode = (int)$_POST['postcode'];
    $adr_mode = $_POST['adr_mode'];
    $country = $_POST['country'];
    $adr_id = (int)$_POST['adr_id'];
    $obl = mysql_real_escape_string(stripslashes($_POST['region']));
    $city = mysql_real_escape_string(stripslashes($_POST['city']));
    $street = mysql_real_escape_string(stripslashes($_POST['street']));
    $dom = mysql_real_escape_string(stripslashes($_POST['building']));
    $dom_extra = mysql_real_escape_string(stripslashes($_POST['building_extra']));
//if($dom_extra=="")$dom_extra=""; else $dom_extra=" - ".$dom_extra;

    $flat = (int)$_POST['flat'];
    $contact_name = mysql_real_escape_string(stripslashes($_POST['contact_name']));
    $contact_phone = mysql_real_escape_string(stripslashes($_POST['contact_phone']));
    $adr_mode_cl_tr = (int)$_POST['adr_mode_cl_tr'];
    $adr_place = mysql_real_escape_string(stripslashes($_POST['adr_place']));

    $adr_place_info = mysql_real_escape_string(stripslashes($_POST['adr_place_info']));

    if ($adr_mode == 3 || $adr_mode == 4 || $adr_mode == 5) {
        $select = $postcode . ' ';
    }

if($flat=="") $flats=""; else $flats=" - ".$flat;
if($contact_name!="") $c_name=' ('.$contact_name.')'; else $c_name="";

	
if(@$_POST['edit']=="1")
{
	$query = "UPDATE `adress` SET `postcode`='$postcode',`country`='$country',`obl`='$obl',`city`='$city',`street`='$street',`dom`='$dom',`dom_extra`='$dom_extra',`flat`='$flat',`contact_name`='$contact_name',`contact_phone`='$contact_phone',`adr_mode`='$adr_mode',`adr_mode_cl_tr`='$adr_mode_cl_tr',`adr_place`='$adr_place',`adr_place_info`='$adr_place_info' WHERE `id`='".mysql_real_escape_string($adr_id)."'";
$query_block = "SELECT `block` FROM `adress` WHERE `Id`='".mysql_real_escape_string($adr_id)."'";
$result_block = mysql_query($query_block) or die(mysql_error());
$block = mysql_fetch_row($result_block);
	
} else {
$query = "INSERT INTO `adress` (`postcode`,`country`,`obl`,`city`,`street`,`dom`,`dom_extra`,`flat`,`contact_name`,`contact_phone`,`adr_mode`,`adr_mode_cl_tr`,`adr_place`,`adr_place_info`) VALUES ('$postcode','$country','$obl','$city','$street','$dom','$dom_extra','$flat','$contact_name','$contact_phone','$adr_mode','$adr_mode_cl_tr','$adr_place','$adr_place_info')";
}

if($block[0]!='1')
{
$result = mysql_query($query) or die(mysql_error());
echo 'Сохранено!|1|'.mysql_insert_id().'|'.$select.$country.' '.$obl.' обл. '.$city.' ул. '.$street.' д.'.$dom.$dom_extra.$flats.$c_name.'|'.$adr_mode.'|'.$adr_mode_cl_tr;

} else {
echo '<font size="3" color="red">Адрес заблокирован для редактирования!</font>';
}
}	

?>