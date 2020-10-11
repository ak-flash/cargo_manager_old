<?php 
session_start();

include "../../config.php";

function vk_date($date) {
$m['01']="янв"; 
$m['02']="фев"; 
$m['03']="мар"; 
$m['04']="апр"; 
$m['05']="мая";
$m['06']="июн"; 
$m['07']="июл"; 
$m['08']="авг"; 
$m['09']="сен"; 
$m['10']="окт"; 
$m['11']="ноя";
$m['12']="дек";
	
	if(date('Y-m-d',strtotime($date))==date('Y-m-d'))
$date_m='сегодня в '.date("H:i",strtotime($date));
else if(date('Y-m-d', strtotime($date))==date('Y-m-d', strtotime("-1 day"))) $date_m='вчера в '.date("H:i",strtotime($date)); else $date_m=date("j", strtotime($date)).' '.$m[date("m",strtotime($date))].' в '.date("H:i",strtotime($date));
return $date_m;
}






if ($_GET['mode']=='show') {
$cl_id=(int)$_GET['cl_id'];
$sort=(int)$_GET['sort'];
$page=(int)$_GET['view'];

if((int)$_GET['s_man']!=0){
if((int)$_GET['s_man']==1000000) $s_man=" AND `s_contract`='1' "; else 
$s_man=" AND `s_man`='".mysql_escape_string($_GET['s_man'])."' ";

$_SESSION["s_man"]=(int)$_GET['s_man'];


} else {$s_man='';$_SESSION["s_man"]=0;}

if($_GET['v_man']!='off'){$v_man=" AND `manager` LIKE '".mysql_escape_string($_GET['v_man'])."%' ";$_SESSION["v_man"]=$_GET['v_man'];} else {$v_man='';$_SESSION["v_man"]="";}

//if($sort==0)$_SESSION["s_man"]=0;

switch ($cl_id) {
case '1': $admin=array(6,23,37,35);break;
case '2': $admin=array(9);break;
case '3': $admin=array(7);break;
case '4': $admin=array(13,37);break;
}

//Пагинация
if($sort!=0)
$result = mysql_query("SELECT COUNT(*) AS count FROM `cl_gruz` WHERE `cl_id`='".mysql_escape_string($cl_id)."' AND `status`='".mysql_escape_string($sort)."'".$s_man.$v_man); else $result = mysql_query("SELECT COUNT(*) AS count FROM `cl_gruz` WHERE `cl_id`='".mysql_escape_string($cl_id)."'".$s_man.$v_man);

$row = mysql_fetch_array($result,MYSQL_ASSOC);
$rows_max = $row['count'];

$show_mess = 10;

if ($page){$offset = (($show_mess * $page) - $show_mess);}
else
{
$page = 1;
$offset = 0;
}


//

if($_SESSION["group"]==1||$_SESSION["group"]==2||in_array($_SESSION["user_id"], $admin)) {
$edit=true;
echo '<input style="font-size: 20px;height: 45px;width:140px;margin-bottom:-5px;margin-top:-5px;" type="button" onclick=\'$("#fa_gruz").load("add_gruz.html?cl_id='.$cl_id.'");$("#fa_gruz").dialog({ title: "Новый рейс" },{width: 500,height: 530,modal: true,resizable: false});\' value="Добавить" class="button3">&nbsp;&nbsp;&nbsp;&nbsp;|';
}
echo '&nbsp;&nbsp;&nbsp;&nbsp;<input style="height: 35px;font-size: 20px;width:130px;margin:-5px;color:black;" type="button" onclick=\'$("#gruz_show-'.$cl_id.'").load("/control/auto/gruz_show.php?mode=show&cl_id='.$cl_id.'&s_man=0&v_man="+document.getElementById("v_man_search").value);\' value="Все заявки" class="button4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input style="height: 35px;font-size: 20px;width:145px;margin:-5px;" type="button" onclick=\'$("#gruz_show-'.$cl_id.'").load("/control/auto/gruz_show.php?mode=show&cl_id='.$cl_id.'&sort=1&s_man="+document.getElementById("s_man_search").value+"&v_man="+document.getElementById("v_man_search").value);\' value="актуальные" class="button2">&nbsp;<input style="height: 35px;font-size: 20px;width:125px;margin:-5px;" type="button" onclick=\'$("#gruz_show-'.$cl_id.'").load("/control/auto/gruz_show.php?mode=show&cl_id='.$cl_id.'&sort=3&s_man="+document.getElementById("s_man_search").value+"&v_man="+document.getElementById("v_man_search").value);\' value="закрытые" class="button2">';
if($cl_id==1){ echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Менеджер Сельты:&nbsp;&nbsp;<select name="s_man_search" id="s_man_search" style="width:160px; font-size: 18px;" class="select" onchange=\'$("#gruz_show-'.$cl_id.'").load("/control/auto/gruz_show.php?mode=show&cl_id='.$cl_id.'&s_man="+document.getElementById("s_man_search").value+"&v_man="+document.getElementById("v_man_search").value);\'><option value="0">Выберите...</option><option value="1000000"';
if($_SESSION["s_man"]==1000000) echo ' selected';
echo '>Контракт</option>';
$query = "SELECT `id`,`s_name` FROM `cl_selta` ORDER BY `s_name` ASC";
$result = mysql_query($query) or die(mysql_error());
while($s_user= mysql_fetch_row($result)) {
$pieces = explode(" ", $s_user[1]);
$print_add_name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
if($_SESSION["s_man"]==$s_user[0]) echo '<option value="'.$s_user[0].'"  selected>'.$print_add_name.'</option>'; else echo '<option value="'.$s_user[0].'">'.$print_add_name.'</option>';
}
echo '</select>&nbsp;&nbsp;Менеджер: <select name="v_man_search" id="v_man_search" style="width:160px; font-size: 18px;" class="select" onchange=\'$("#gruz_show-'.$cl_id.'").load("/control/auto/gruz_show.php?mode=show&cl_id='.$cl_id.'&s_man="+document.getElementById("s_man_search").value+"&v_man="+document.getElementById("v_man_search").value);\'><option value="off">Выберите...</option>';
$query = "SELECT `name` FROM `workers` WHERE `group`='3' AND `delete`='0' ORDER BY `name` ASC";
$result = mysql_query($query) or die(mysql_error());
while($v_user= mysql_fetch_row($result)) {
$pieces = explode(" ", $v_user[0]);
$print_add_name=$pieces[0];
if($_SESSION["v_man"]==$print_add_name) echo '<option value="'.$print_add_name.'"  selected>'.$print_add_name.'</option>'; else echo '<option value="'.$print_add_name.'">'.$print_add_name.'</option>';
}
echo '</select>';



}



echo '
<br><br>	
<table cellpadding="5"  style="width:99%;overflow:auto;border-collapse: collapse;border-style: solid;border-color: black;" border="1" width="100%"><tr style="color:#000000;"><td bgcolor="#edf1f3" align="center">№</td><td bgcolor="#edf1f3" align="center">$</td><td bgcolor="#edf1f3" align="center"><b>Дата</b></td><td bgcolor="#edf1f3" align="center"><b>Город загрузки</b></td><td bgcolor="#edf1f3" align="center"><b>Город выгрузки</b></td><td bgcolor="#edf1f3" align="center"><b>Ставка кл.(Трансп.компания)</b></td><td bgcolor="#edf1f3" align="center"><b>Ставка факт.</b></td><td bgcolor="#edf1f3" align="center"><b>Заявка/статус</b></td><td bgcolor="#edf1f3" align="center"><b>Менеджер</b></td><td bgcolor="#edf1f3" align="center">&nbsp;</td></tr>';


if($sort!=0) $query_gruz = "SELECT * FROM `cl_gruz` WHERE `cl_id`='".mysql_escape_string($cl_id)."' AND `status`='".mysql_escape_string($sort)."'".$s_man.$v_man." ORDER BY `date` DESC LIMIT $offset, $show_mess"; else $query_gruz = "SELECT * FROM `cl_gruz` WHERE `cl_id`='".mysql_escape_string($cl_id)."'".$s_man.$v_man." ORDER BY `date` DESC LIMIT $offset, $show_mess";

$result_gruz = mysql_query($query_gruz) or die(mysql_error());
if (mysql_num_rows($result_gruz)>0){
$num=1;
while($gruz = mysql_fetch_row($result_gruz)) {

if($gruz[6]=='')$manager="-"; else $manager=$gruz[6];
if($gruz[8]==0){$order="-";$cash_tr="-";

if($gruz[9]==2){
$query_user = "SELECT `id`,`name` FROM `workers` WHERE `id`='".mysql_escape_string($gruz[11])."'";
$result_user = mysql_query($query_user) or die(mysql_error());
$user= mysql_fetch_row($result_user);
$pieces = explode(" ", $user[1]);
$tr_manager=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
$man='<b>'.$tr_manager.'</b><br><font size="2">('.$manager.')</font>';
} else $man='<b>'.$manager.'</b>';

} else {
$order=$gruz[8];

if($gruz[9]!=3){$query_st = "UPDATE `cl_gruz` SET `status`='3',`progress_manager`='' WHERE `id`='".mysql_escape_string($gruz[0])."'";
$result_st = mysql_query($query_st) or die(mysql_error());
$gruz[9]=3;
}

$query_order = "SELECT `tr_cash`,`tr_manager` FROM `orders` WHERE `id`='".mysql_escape_string($order)."'";
$result_order = mysql_query($query_order) or die(mysql_error());
$gr_order = mysql_fetch_row($result_order);
$cash_tr=$gr_order[0].' руб.';

$query_user = "SELECT `id`,`name` FROM `workers` WHERE `id`='".mysql_escape_string($gr_order[1])."'";
$result_user = mysql_query($query_user) or die(mysql_error());
$user= mysql_fetch_row($result_user);
$pieces = explode(" ", $user[1]);
$tr_manager=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
$man='<b>'.$tr_manager.'</b><br><font size="2">('.$manager.')</font>';
}
if($gruz[5]==0)$cash_cl="-"; else $cash_cl=$gruz[5];
if($gruz[10]==0)$cash_vtl="-"; else $cash_vtl=$gruz[10];
//if($gruz[7]=="")$notify="-"; else $notify='<a href="#" onclick=\'$("#result_temp").html("'.$gruz[7].'");$("#result_temp").dialog({ title: "Комментарий к рейсу" },{width: 330 },{ modal: true },{ resizable: false },{ buttons: { "Ok": function() { $(this).dialog("close");}}});\'>прочитать</a>';

if($gruz[7]=="")$notify="white"; else $notify='#60DE79';
if($gruz[13]==1)$notify='#FF3D4A';
//$notify='<br><font color="green" size="2">информация</font>';

switch ($gruz[9]) {
case '0': echo '<tr bgcolor="#C7C7C7" style="color:#646363;">';$st="не актуальна";break;
case '1': echo '<tr onmouseover=\'$("#popup_info_'.$num.'").hide();\'>';$st="актуальна";break;
case '2': echo '<tr bgcolor="#FFC1C1" style="color:#2C4359;">';$st="в процессе";break;
case '3': echo '<tr bgcolor="#B3F495" style="color:#2C4359;">';$st="закрыта";break;
}
if($cl_id==1){
$query_s = "SELECT `s_name` FROM `cl_selta` WHERE `id`='".mysql_escape_string($gruz[12])."'";
$result_s = mysql_query($query_s) or die(mysql_error());
$s_user= mysql_fetch_row($result_s);
$pieces = explode(" ", $s_user[0]);
$print_s_user="<br><font size=1><b>".$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".</b></font>";
}

echo '<td align="center">'.$gruz[0].' </td><td align="center" onmouseover=\'$("#popup_info_'.$num.'").load("/control/auto/gruz_show.php?mode=popup&gruz_id='.$gruz[0].'",function() {$("#popup_info_'.$num.'").show();});\' onmouseout=\'$("#popup_info_'.$num.'").hide();\' bgcolor="'.$notify.'">
<div id="popup_info_'.$num.'" style="width:350px;background:#EBF1FF;border: 5px solid #A0A0A0;z-index:1003;display:none;border-radius: 5px;position:absolute;margin-top:-23px;left:200px;padding:10px;"></div></td><td align="center"  onmouseover=\'$("#popup_info_'.$num.'").hide();\'><b>'.vk_date($gruz[2]).'</b>'.$print_s_user.'</td><td align="center">'.$gruz[3].' </td><td align="center">'.$gruz[4].'</td><td align="center"><b>'.$cash_cl.' руб.</b><br><font size="2">('.$cash_vtl.' руб.)</font></td><td align="center"><b>'.$cash_tr.'</b></td><td align="center"><b>'.$order.'</b> ('.$st.')</td><td align="center">'.$man.'</td><td align="center">';

$num++;

if($gruz[9]==1||($_SESSION["user_id"]==$gruz[11]&&$gruz[9]==2)) echo '<a href="#" id="gruz_order" onclick=\'$("#fa").load("add_order.html?gruz_id='.$gruz[0].'&gruz_cl_id='.$gruz[1].'&gruz_cl_cash='.$gruz[5].'");$("#fa").dialog({ title: "Новая заявка" },{close: function(event, ui) {$.post("control/auto/gruz_show.php?mode=cancel&gruz_id='.$gruz[0].'", function(data) {$("#gruz_show-'.$gruz[1].'").load("/control/auto/gruz_show.php?mode=show&cl_id='.$gruz[1].'");});}},{width: 970,position:[150,50],modal: true,resizable: false});$.post("control/auto/gruz_show.php?mode=status&gruz_id='.$gruz[0].'", function(data) {$("#gruz_show-'.$cl_id.'").load("/control/auto/gruz_show.php?mode=show&cl_id='.$gruz[1].'");});\' style="text-decoration:none;"><img src="data/img/plus.png" style="width:25px;"></a>&nbsp;&nbsp;';

if($edit==true) echo '<a href="#" id="gruz_p" onclick=\'$("#fa_gruz").load("add_gruz.html?edit=1&gruz_id='.$gruz[0].'&cl_id='.$gruz[1].'");$("#fa_gruz").dialog({ title: "Редактировать рейс" },{width: 500,height: 530,modal: true,resizable: false});\' style="text-decoration:none;"><img src="data/img/document-edit.png" style="width:25px;"></a>&nbsp;&nbsp;';

//<a href="#" id="card_p" onclick=\'$("#result").html("<b>Пометить рейс неактивным?");$("#result").dialog({ title: "Внимание" },{ modal: true },{ width: 350 },{ resizable: false },{ buttons: [{text: "Да",click: function() {$.post("control/auto/gruz_show.php?mode=del_gruz&gruz_id='.$gruz[0].'", function(data) {$("#gruz_show-'.$cl_id.'").load("/control/auto/gruz_show.php?mode=show&cl_id='.$gruz[1].'");});$(this).dialog("close");}},{text: "Нет",click: function() {$(this).dialog("close");}}] });\' style="text-decoration:none;"><img src="data/img/delete.png" style="width:25px;"></a>';

echo '</td></tr>';

}

} else {echo '<tr><td align="center" colspan="7"><font color="red"><b>Рейсов нет...</b></font></td></tr>';}

echo '</table>';


echo '<div style="top:20px;position:relative;"><a onclick=\'$("#gruz_show-'.$cl_id.'").load("/control/auto/gruz_show.php?mode=show&cl_id='.$cl_id.'&sort='.$sort.'&view=1&s_man="+document.getElementById("s_man_search").value+"&v_man="+document.getElementById("v_man_search").value);\' style="color: #1E1E1E;text-decoration: none;cursor: pointer;margin-left:20px;margin-right:20px;">В начало</a>';

if($rows_max>$show_mess&&$page>1) $paginator.='<div style="margin:10px;display:inline;"><a onclick=\'$("#gruz_show-'.$cl_id.'").load("/control/auto/gruz_show.php?mode=show&cl_id='.$cl_id.'&sort='.$sort.'&view='.($page-1).'&s_man="+document.getElementById("s_man_search").value+"&v_man="+document.getElementById("v_man_search").value);\' style="text-decoration: none;color: #1E1E1E;cursor: pointer;font-size:120%;">Назад</a>&nbsp;&nbsp;<b><font size="5">'; else $paginator.='<b><font size="5">';
$paginator.=$page;
if($rows_max>$show_mess&&$page<ceil($rows_max/$show_mess)) $paginator.='</b></font>&nbsp;&nbsp;<a onclick=\'$("#gruz_show-'.$cl_id.'").load("/control/auto/gruz_show.php?mode=show&cl_id='.$cl_id.'&sort='.$sort.'&view='.($page+1).'&s_man="+document.getElementById("s_man_search").value+"&v_man="+document.getElementById("v_man_search").value);\' style="color: #1E1E1E;text-decoration: none;cursor: pointer;font-size:120%;">Вперед</a>'; else $paginator.='</b></font></div>';

echo $paginator.'</div><table style="float:right;"><tr><td><b>Обозначения:</b>&nbsp;&nbsp;</td><td bgcolor="#60DE79" width="15"></td><td>&nbsp;&nbsp; - есть комментарий</td></tr></table>';

}


if ($_GET['mode']=='del_gruz') {
$gruz_id=(int)$_GET['gruz_id'];

$query = "UPDATE `cl_gruz` SET `delete`='1' WHERE `id`='".mysql_escape_string($gruz_id)."'";
$result = mysql_query($query) or die(mysql_error());
echo 'Удалено!';

}

if ($_GET['mode']=='status') {
$gruz_id=(int)$_GET['gruz_id'];
$query = "UPDATE `cl_gruz` SET `status`='2',`progress_manager`='".mysql_escape_string($_SESSION["user_id"])."' WHERE `id`='".mysql_escape_string($gruz_id)."'";
$result = mysql_query($query) or die(mysql_error());
}

if ($_GET['mode']=='cancel') {
$gruz_id=(int)$_GET['gruz_id'];
$query = "UPDATE `cl_gruz` SET `status`='1',`progress_manager`='' WHERE `id`='".mysql_escape_string($gruz_id)."'";
$result = mysql_query($query) or die(mysql_error());
}

if ($_GET['mode']=='popup') {
$gruz_id=(int)$_GET['gruz_id'];
$row=(int)$_GET['row'];
$query_popup = "SELECT `notify`,`s_man`,`s_contract` FROM `cl_gruz` WHERE `id`='".mysql_escape_string($gruz_id)."'";
$result_popup = mysql_query($query_popup) or die(mysql_error());
$popup= mysql_fetch_row($result_popup);

$query_s = "SELECT `s_name` FROM `cl_selta` WHERE `id`='".mysql_escape_string($popup[1])."'";
$result_s = mysql_query($query_s) or die(mysql_error());
$s_user= mysql_fetch_row($result_s);

echo '<div align="left">';
if($popup[2]==1) echo '<div style="margin-bottom:10px;"><font size="5"><b>Контракт!</b></font></div>';
if($popup[1]!=0) echo 'Менеджер Сельты: <br><b>'.$s_user[0].'</b><hr>';	
if($popup[0]!='') echo $popup[0];
echo '</div>';
}
?>