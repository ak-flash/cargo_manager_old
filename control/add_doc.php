<?php
// Подключение и выбор БД
include "../config.php";
session_start();



if(@$_POST['doc_id']!=""){
$validate=true;
$error='Не заполнено поле <font color="red">"';
$err='"</font><br>';

$order_id=(int)$_POST['order_id'];
$query_order = "SELECT `cl_event`,`tr_event`,`transp` FROM `orders` WHERE `Id`='".mysql_escape_string($order_id)."'";
$result_order = mysql_query($query_order) or die(mysql_error());
$order = mysql_fetch_row($result_order);
$valid=true;
if((int)$_POST['cl_doc_list']!=$order[0]&&$order[0]!=1&&$order[0]!=2&&$_SESSION["group"]!="4"){echo 'Форма полученных документов от <b>Клиента</b> не совпадает с указанной в Заявке!<br>';$valid_cl=false;} else {$valid_cl=true;}

if((int)$_POST['tr_doc_list']!=$order[1]&&$order[1]!=1&&$order[1]!=2&&$_SESSION["group"]!="4"&&in_array($order[2], $_SESSION['own_transporter_ids'])!=1){ echo 'Форма полученных документов от <b>Перевозчика</b> не совпадает с указанной в Заявке!';$valid_tr=false;} else {$valid_tr=true;}



if(@$_POST['cl_doc_list']=="0"&&$_SESSION["group"]!="4"){echo $error."Форма документов от Клиента".$err; $validate=false;}
if(@$_POST['tr_doc_list']=="0"&&$_SESSION["group"]!="4"&&$order[2]!=0){echo $error."Форма документов от Перевозчика".$err; $validate=false;}


//if(@$_POST['add_bill_cl']==""){echo $error."Номер счета для Клиента".$err; $validate=false;}
//if(@$_POST['add_bill_tr']!=""&&mb_ereg("[^0-9 /-]",$_POST['add_bill_tr'])){echo $error."Номер счета от Перевозчика".$err; $validate=false;}

if((@$_POST['add_bill_cl']!=""&&@$_POST['add_date_cl_bill']==""&&$_SESSION["group"]!="4")||(@$_POST['add_bill_cl']==""&&@$_POST['add_date_cl_bill']!="")){echo $error."Дата выставления Счета Клиенту".$err; $validate=false;}
//if((@$_POST['add_bill_tr']!=""&&@$_POST['add_date_tr_bill']==""&&$_SESSION["group"]!="4")||(@$_POST['add_bill_tr']==""&&@$_POST['add_date_tr_bill']!=""&&$_SESSION["group"]!="4")){echo $error."Счет Перевозчика".$err; $validate=false;}
//if((@$_POST['add_akt_tr']!=""&&@$_POST['add_date_tr_akt']==""&&$_SESSION["group"]!="4")||(@$_POST['add_akt_tr']==""&&@$_POST['add_date_tr_akt']!=""&&$_SESSION["group"]!="4")){echo $error."Акт от Перевозчика".$err; $validate=false;}
//if((@$_POST['add_ttn_tr']!=""&&@$_POST['add_date_tr_ttn']==""&&$_SESSION["group"]!="4")||(@$_POST['add_ttn_tr']==""&&@$_POST['add_date_tr_ttn']!=""&&$_SESSION["group"]!="4")){echo $error."ТТН от Перевозчика".$err; $validate=false;}

//if((@$_POST['add_ttn_tr']!=""&&@$_POST['add_date_tr_ttn']==""&&$_SESSION["group"]!="4")||(@$_POST['add_ttn_tr']==""&&@$_POST['add_date_tr_ttn']!=""&&$_SESSION["group"]!="4")){echo $error."ТТН от Перевозчика".$err; $validate=false;}
if((@$_POST['add_date_cl_sent']!=""&&@$_POST['send_type_cl']=="0"&&$_SESSION["group"]!="4")){echo $error."Способ отправки документов Клиенту".$err; $validate=false;}
}


if($validate&&@$_POST['edit']=="1")
{
$doc_id=(int)$_POST['doc_id'];

$cl_event=(int)$_POST['cl_doc_list'];
$tr_event=(int)$_POST['tr_doc_list'];

$send_type_cl=(int)$_POST['send_type_cl'];

$doc_notify=mysql_real_escape_string(stripslashes($_POST['doc_notify']));

$cl_bill=mysql_real_escape_string(stripslashes($_POST['add_bill_cl']));
$in_elements  = explode("/",$_POST['add_date_cl_bill']);
$add_date_cl_bill=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));
$cl_elements  = explode("/",$_POST['add_date_cl_all']);
$add_date_cl_all=date("Y-m-d",strtotime($cl_elements[2]."-".$cl_elements[1]."-".$cl_elements[0]));

$cl_elements_sent  = explode("/",$_POST['add_date_cl_sent']);
$add_date_cl_sent=date("Y-m-d",strtotime($cl_elements_sent[2]."-".$cl_elements_sent[1]."-".$cl_elements_sent[0]));

$tr_elements  = explode("/",$_POST['add_date_tr_all']);
$add_date_tr_all=date("Y-m-d",strtotime($tr_elements[2]."-".$tr_elements[1]."-".$tr_elements[0]));

$tr_bill=mysql_real_escape_string(stripslashes($_POST['add_bill_tr']));
$in_elements  = explode("/",$_POST['add_date_tr_bill']);
$add_date_tr_bill=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));

$tr_akt=mysql_real_escape_string(stripslashes($_POST['add_akt_tr']));
$in_elements  = explode("/",$_POST['add_date_tr_akt']);
$add_date_tr_akt=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));

$tr_ttn=mysql_real_escape_string(stripslashes($_POST['add_ttn_tr']));
$in_elements  = explode("/",$_POST['add_date_tr_ttn']);
$add_date_tr_ttn=date("Y-m-d",strtotime($in_elements[2]."-".$in_elements[1]."-".$in_elements[0]));



if($valid_cl&&$valid_tr){$query = "UPDATE `docs` SET  `cl_bill`='$cl_bill',`date_add_bill`='$add_date_cl_bill',`date_cl_receve`='$add_date_cl_all',`tr_bill`='$tr_bill',`date_tr_bill`='$add_date_tr_bill',`tr_akt`='$tr_akt',`date_tr_akt`='$add_date_tr_akt',`tr_ttn`='$tr_ttn',`date_tr_ttn`='$add_date_tr_ttn',`notify`='$doc_notify',`tr_event`='$tr_event',`cl_event`='$cl_event',`date_tr_receve`='$add_date_tr_all',`add_date_cl_sent`='$add_date_cl_sent',`s_type`='$send_type_cl' WHERE `id`='".mysql_escape_string($doc_id)."'";
$result = mysql_query($query) or die(mysql_error());
echo 'Сохранено!|1';
}



}



?>