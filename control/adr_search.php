<?php
include "../config.php";
header("Content-type: text/script;charset=utf-8");

if (!isset($data)) $data = new stdClass();

if (@$_GET['adr_id']!=""){
$query_adress = "SELECT * FROM `adress` WHERE `Id`='".$_GET['adr_id']."'";
$result_adress = mysql_query($query_adress) or die(mysql_error());

$adress = mysql_fetch_row($result_adress);       
	     	
if($adress[1]=="0") $postcode=""; else $postcode=$adress[1];       	
    if($adress[8]=="0") $flat=""; else $flat=" - кв/офис ".$adress[8];        	
    if($adress[6]=="0") $dom=""; else $dom=' д.'.$adress[6];
            if($adress[7]=="") $dom_ext=""; else $dom_ext='('.$adress[7].')';

 echo $postcode.$adress[2].' '.$adress[3].' обл. <b>'.$adress[4].'</b> ул. '.$adress[5].$dom.$dom_ext.$flat.' ('.$adress[9].') ';

}

if (@$_GET['mode']!=""){
$mode=(int)$_GET['mode'];
$s_query = $_GET['q'];

switch ($mode) {
case '1': $fmode=33;break;
case '2': $fmode=33;break;
case '3': $fmode=4;break;
case '4': $fmode=3;break;
case '5': $fmode=5;break;
}   


$query_adress = "SELECT * FROM `adress` WHERE (`adr_mode`='".mysql_escape_string($mode)."' OR `adr_mode`='".mysql_escape_string($fmode)."') AND (`postcode` LIKE '".mysql_escape_string($s_query)."%' OR `city` LIKE '%".mysql_escape_string($s_query)."%' OR `street` LIKE '%".mysql_escape_string($s_query)."%' OR `dom` LIKE '".mysql_escape_string($s_query)."%' OR `id`='".mysql_escape_string($s_query)."')";
$result_adress = mysql_query($query_adress) or die(mysql_error());



 if (mysql_num_rows($result_adress)>0)
		{ 

	         
	       
            while($adress = mysql_fetch_row($result_adress)) {
     if($adress[1]=="0") $postcode=""; else $postcode=$adress[1];       	
    if($adress[8]=="0") $flat=""; else $flat=" - кв/офис ".$adress[8];        	
    if($adress[6]=="0") $dom=""; else $dom=' д.'.$adress[6];
            if($adress[7]=="") $dom_ext=""; else $dom_ext='('.$adress[7].')';	
            
            	if($mode=='2'||$mode=='1'){$data->results[]=array('id'=>$adress[0],'name'=>$adress[2].' '.$adress[3].' обл. '.$adress[4].' ул. '.$adress[5].'&nbsp;'.$dom.$dom_ext.$flat.' ('.$adress[9].') ');} 
            	if($mode=='3'||$mode=='4'){ $data->results[]=array('id'=>$adress[0],'name'=>$postcode.' '.$adress[2].' '.$adress[3].' обл. '.$adress[4].' ул.'.$adress[5].' '.$dom.$dom_ext.$flat);}
	             if($mode=='5'){ $data->results[]=array('id'=>$adress[0],'name'=>$postcode.' '.$adress[2].' '.$adress[3].' обл. '.$adress[4].' ул.'.$adress[5].' '.$dom.$dom_ext.$flat);}  
	     	}        
	     	

 echo json_encode($data);
  
 } else {echo '{"results":[{"id":"0","name":"Ничего не найдено..."}]}';}
 }
?>