<?php
// Подключение и выбор БД
include "../../config.php";	

if ($_GET['mode']=='trip') 
{
    if (!isset($data)) $data = new stdClass();

    $page = (int)$_GET['page'];      // Номер запришиваемой страницы
    $limit = (int)$_GET['rows'];     // Количество запрашиваемых записей
    $sidx = mysql_real_escape_string($_GET['sidx']);      // Номер элемента массива по котору следует производить сортировку // Проще говоря поле, по которому следует производить сортировку
    $sord = mysql_real_escape_string($_GET['sord']);      // Направление сортировки


// Если не указано поле сортировки, то производить сортировку по первому полю
    if (!$sidx) $sidx = 1;


    if ($_GET['date_start'] != '') {
        $start_elements = explode("/", $_GET['date_start']);
        if ($start_elements[0] == '01') {
            $month = 1;
        }
        $date_start = $start_elements[2] . "-" . $start_elements[1] . "-" . $start_elements[0];
$end_elements  = explode("/",$_GET['date_end']);
$date_end=$end_elements[2]."-".$end_elements[1]."-".$end_elements[0];

$result = mysql_query("SELECT COUNT(*) AS count FROM `vtl_trip` WHERE DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."'");
} else {$result = mysql_query("SELECT COUNT(*) AS count FROM `vtl_trip`");}




// Выполним запрос, который вернет суммарное кол-во записей в таблице




$row = mysql_fetch_array($result,MYSQL_ASSOC);
$count = $row['count'];     // Теперь эта переменная хранит кол-во записей в таблице



// Рассчитаем сколько всего страниц займут данные в БД
if( $count > 0 && $limit > 0) {
    $total_pages = ceil($count/$limit);
} else {
    $total_pages = 0;
}
// Если по каким-то причинам клиент запросил
if ($page > $total_pages) $page=$total_pages;

// Рассчитываем стартовое значение для LIMIT запроса
$start = $limit*$page - $limit;
// Зашита от отрицательного значения
if($start <0) $start = 0;

if(@$_GET['search']=='true'){
mb_internal_encoding("UTF-8");
$g=0;

$query_search = "SELECT `id`,`order` FROM `vtl_trip` WHERE `delete`='0'";
$result_search = mysql_query($query_search) or die(mysql_error());

while($row = mysql_fetch_array($result_search)) {	

$str_order = explode('&',$row['order']);
$str_order_list =(int)sizeof($str_order)-2;
$f=0;
while ($f<=$str_order_list) {

if(mb_stristr($row['id'], $_GET['s_data'])||mb_stristr($str_order[($str_order_list-$f)], $_GET['s_data']))	{$id_mass[$g]=$row['id'];$g++;}
$f++;

}


}

$count=$g;
if( $count > 0 && $limit > 0) {
    $total_pages = ceil($count/$limit);
} else {
    $total_pages = 0;
}
// Если по каким-то причинам клиент запросил
if ($page > $total_pages) $page=$total_pages;

// Рассчитываем стартовое значение для LIMIT запроса
$start = $limit*$page - $limit;
// Зашита от отрицательного значения
if($start <0) $start = 0;

$row=0;



}


// Запрос выборки данных
if($id_mass){$query = "SELECT * FROM `vtl_trip` WHERE `delete`='0' AND `id` IN (".implode(',' , $id_mass).") ORDER BY `data` DESC LIMIT ".$start.", ".$limit;} else {if(@$_GET['search']=='true')$query = "SELECT * FROM `vtl_trip` WHERE `id`='0'"; else {if ($_GET['date_start']!=''){$query = "SELECT * FROM `vtl_trip` WHERE `delete`='0' AND DATE(`data`) BETWEEN '".$date_start."' AND '".$date_end."' ORDER BY `data` DESC LIMIT ".$start.", ".$limit;}else {$query = "SELECT * FROM `vtl_trip` WHERE `delete`='0' ORDER BY `data` DESC LIMIT ".$start.", ".$limit;}}}


$result = mysql_query($query) or die(mysql_error());



// Начало формирование массива
// для последующего преобразоования
// в JSON объект
$data->page       = $page;
$data->total      = $total_pages;
$data->records    = $count;

$query_drv = "SELECT `id`,`name` FROM `workers` WHERE `group`='5'";
$result_drv = mysql_query($query_drv) or die(mysql_error());

while($drv = mysql_fetch_row($result_drv)){
$pieces = explode(" ", $drv[1]);
$print_name=$pieces[0]." ".substr($pieces[1],0,2).". ".substr($pieces[2],0,2).".";
$driver[$drv[0]]=$print_name;
}

	
// Строки данных для таблицы
$i = 0;
while($row = mysql_fetch_array($result)) {
$res_order='';
$str_order = explode('&',$row['order']);
$str_order_list =(int)sizeof($str_order)-1;
$f=1;
while ($f<$str_order_list) {
$res_order=$res_order.$str_order[($str_order_list-$f)].', ';
$f++;
}
if($f==$str_order_list)$res_order=$res_order.$str_order[($str_order_list-$f)];

	

$km='<b>'.$row['km'].'</b> км';
if($row['cash_day']==0)$day_cash="-"; else $day_cash='<b>'.($row['cash_day']/100).'</b> руб.';
if($row['plan_cash']==0)$cash="-"; else $cash=$row['plan_cash']/100;

$query_report = "SELECT `way`,`cash` FROM `drivers_report` WHERE `trip`='".(int)$row['id']."' AND `delete`='0'";
$result_report = mysql_query($query_report) or die(mysql_error());
$cash_beznal=0;
$cash_back=0;
$cash_nal=0;
while($report = mysql_fetch_row($result_report)) {
if($report[0]!=0&&$report[0]!=33&&$report[0]!=10)$cash_nal=(int)$cash_nal+(int)$report[1];
if($report[0]==10)$cash_beznal=(int)$cash_beznal+(int)$report[1];
if($report[0]==33)$cash_back=(int)$cash_back+(int)$report[1];
}


$str_drv = explode('&',$row['tr_auto']);



$data->rows[$i]['id'] = $row['id'];
    $data->rows[$i]['cell'] = array($row['id'],'<font size="3">'.date('d/m/Y',strtotime($row['data'])).'</font>','<font size="4">'.$res_order.'</font>',$km,'<b>'.$driver[$str_drv[2]].'</b>','<font size="4">'.($cash-$cash_back/100).'</font> руб. (Суточные:'.$day_cash.')','<font color="green" size="4">'.($cash_nal/100).'</font> руб.');
    $i++;

}

// Перед выводом не забывайте выставить header
// с типом контента и кодировкой
header("Content-type: text/script;charset=utf-8");
echo json_encode($data);
	
}

if ($_GET['mode']=='desc') 
{
$id =$_GET['id'];

$query_trip= "SELECT * FROM `vtl_trip` WHERE `id`='".(int)$id."' AND `delete`='0'";
$result_trip = mysql_query($query_trip) or die(mysql_error());
if(mysql_num_rows($result_trip) == 1){

$row = mysql_fetch_array($result_trip);

$str_order = explode('&',$row['order']);
$str_order_list =(int)sizeof($str_order)-1;
$f=1;
while ($f<=$str_order_list) {
$res_order=$res_order.$str_order[($str_order_list-$f)].', ';


$query_km = "SELECT `km` FROM `orders` WHERE `id`='".(int)$str_order[($str_order_list-$f)]."' AND `delete`='0'";
$result_km = mysql_query($query_km) or die(mysql_error()); 
$row_km = mysql_fetch_row($result_km);
$km_plan=(int)$km_plan+(int)$row_km[0];

$f++;
}
//if($f==$str_order_list)$res_order=$res_order.$str_order[($str_order_list-$f)];




$str_auto = explode('&',$row['tr_auto']);
            	
$query_car = "SELECT `id`,`name`,`type`,`number`,`km`,`lpk`,`petrol` FROM `vtl_auto` WHERE `id`='".(int)$str_auto[0]."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$auto = mysql_fetch_row($result_car);          	

$query_car = "SELECT `id`,`name`,`type`,`number` FROM `vtl_auto` WHERE `id`='".(int)$str_auto[1]."'";
$result_car = mysql_query($query_car) or die(mysql_error());
$dop_auto = mysql_fetch_row($result_car);             	

$query_drv = "SELECT `id`,`name`,`z_day`,`z_city`,`z_repair`,`z_km` FROM `workers` WHERE `id`='".(int)$str_auto[2]."'";
$result_drv = mysql_query($query_drv) or die(mysql_error());
$drv = mysql_fetch_row($result_drv);

$query_trip = "SELECT `end_petrol` FROM `vtl_trip` WHERE `tr_auto` LIKE '".$str_auto[0]."&%' AND `delete`='0' AND `id`!=".(int)$row['id']." AND `id`<".(int)$row['id']." ORDER BY `data` DESC";
$result_trip = mysql_query($query_trip) or die(mysql_error());
$trip = mysql_fetch_row($result_trip); 

$query_free = "SELECT `way`,`cash` FROM `drivers_report` WHERE `trip` IN (SELECT `id` FROM `vtl_trip` WHERE `tr_auto` LIKE '%&".(int)$str_auto[2]."') AND `delete`='0'";
$result_free = mysql_query($query_free) or die(mysql_error());
$cash_free=0;	
while($free = mysql_fetch_row($result_free)) {
if($free[0]!=0&&$free[0]!=33&&$free[0]!=10)$cash_free=(int)$cash_free+(int)$free[1];
}

$query_fr = "SELECT `plan_cash`,`o_cash` FROM `vtl_trip` WHERE `tr_auto` LIKE '%&".(int)$str_auto[2]."'";
$result_fr = mysql_query($query_fr) or die(mysql_error());
$cash_fr=0;
$cash_o=0;	
while($fr = mysql_fetch_row($result_fr)) {
$cash_fr=(int)$cash_fr+(int)$fr[0];
$cash_o=(int)$cash_o+(int)$fr[1];
}

$query_report = "SELECT `way`,`cash`,`l` FROM `drivers_report` WHERE `trip`='".(int)$row['id']."' AND `delete`='0'";
$result_report = mysql_query($query_report) or die(mysql_error());
$cash_beznal=0;
$cash_back=0;
$cash_nal=0;
$petrol=0;
while($report = mysql_fetch_row($result_report)) {
if($report[0]!=0&&$report[0]!=33&&$report[0]!=10)$cash_nal=(int)$cash_nal+(int)$report[1];
if($report[0]==10)$cash_beznal=(int)$cash_beznal+(int)$report[1];
if($report[0]==33)$cash_back=(int)$cash_back+(int)$report[1];
if($report[2]!=0)$petrol=(int)$petrol+(int)$report[2];
}

$query_repair = "SELECT `cash` FROM `vtl_repair` WHERE `driver`='".(int)$str_auto[2]."' AND `delete`='0'";
$result_repair = mysql_query($query_repair) or die(mysql_error());
$cash_repair=0;
while($repair = mysql_fetch_row($result_repair)) {
$cash_repair=(int)$cash_repair+(int)$repair[0];
}


if(($row['plan_cash']-$cash_back-$cash_nal)<0)$ost="<font color='red'><b>Перерасход:</b></font>&nbsp;&nbsp;".((($row['plan_cash']-$cash_back-$cash_nal))/100)." руб.  (В подотчёте:&nbsp;&nbsp;".(($cash_o-$cash_repair)/100)." руб.)"; else $ost="<font color='green'><b>Остаток:</b></font>&nbsp;&nbsp;".((($row['plan_cash']-$cash_back-$cash_nal))/100)." руб.  (В подотчёте:&nbsp;&nbsp;".(($cash_o-$cash_repair)/100)." руб.)";



if($row['km']==0)$km=''; else $km=$row['km'];

$query_card = "SELECT `id` FROM `vtl_oil_card` WHERE `car_id`='".mysql_escape_string((int)$str_auto[0])."'";
$result_card = mysql_query($query_card) or die(mysql_error());
$card = mysql_fetch_row($result_card);

if($row['credit']==0){
$query_pay = "SELECT `id`,`status` FROM `pays` WHERE `trip_id`='".mysql_escape_string($row['id'])."' AND `delete`='0'";
$result_pay = mysql_query($query_pay) or die(mysql_error());
if (mysql_num_rows($result_pay)>0){$pay = mysql_fetch_row($result_pay);
$bt_cash='disabled';$credit='disabled';
switch ($pay[1]) {
case '0': $status="<font color='red'>не проведён</font>";break;
case '1': $status="<font color='green'>проведен</font>";break;
}
$pays_cash="<font color='green' size='3'>Выдано: <b>".($row['plan_cash']/100)."</b>  руб.</font> (Включая суточные: <b>".($row['cash_day']/100)."</b> руб.)<br>Платеж №<b>".$pay[0]."</b> (".$status.")";} else {$pays_cash="<font color='red'><b>Платёж отсутствует!!!</font></b> Предполагаемая сумма: <b>".($row['plan_cash']/100)."</b> руб.";}
} else {$pays_cash="<font color='green' size='3'>Используется НАЛ из подотчёта</font><br> (Включая суточные: <b>".($row['cash_day']/100)."</b> руб.)";}

if($row['credit']==1){$credit='checked disabled';$bt_cash='disabled';}

//if($row['plan_cash']==0&&$row['credit']!=1){$avans='disabled';$avans_n='Выдайте средства';} else {$avans='';$avans_n='';}
   
$days=round($km/$auto[4]);

//<fieldset style='float:left;width:400px;margin-top:0;'><legend>Средства под отчёт </legend><table><tr><td colspan='4'  style='border-style:none;'>$ost<hr><input type='checkbox' name='nal_credit' id='nal_credit' value='0' ".$credit." onchange=\"if(document.getElementById('nal_credit').checked){\$('#btnReport_drv').attr('disabled','');$('#nal_credit').val(1);} else {\$('#btnReport_drv').attr('disabled','disabled');$('#nal_credit').val(2);}\"> выдать из подотчётных</td></tr><tr><td style='border-style:none;'><b>НАЛ:</b></td><td style='border-style:none;' valign='middle'><input name='cash_p' id='cash_p_".$row['id']."' style='width: 60px;margin-top:-5px;' class='input' value='' onchange=\"$('#cash_p_".$row['id']."').val($('#cash_p_".$row['id']."').val().replace(/,+/,'.'));\" ".$bt_cash."></td><td style='border-style:none;' width='230'><input type='button' id='btnCash_p_change' onclick=\"$.post('control/auto/trip.php?nal_credit='+$('#nal_credit').val()+'&nal='+$('#cash_p_".$row['id']."').val()+'&days_cash='+$('#cash_day_div').html()+'&trip_id=".$row['id']."&data=".$row['data']."', function(data) {var arr = data.split(/[|]/);if(arr[0]==1){\$('#save_cash_p').html('сохранено');$('#save_cash_p').fadeIn(500);$('#save_cash_p').fadeOut(2000);$('#cash_p_".$row['id']."').attr('disabled','disabled');$('#btnCash_p_change').attr('disabled','disabled');$('#pays_cash').html(arr[1]);$('#avans_n').html('');$('#btnReport_drv').attr('disabled','');} else {\$('#result').html(data); $('#result').dialog({ title: 'Внимание' },{width: 400,height: 170,modal: true,resizable: false},{ buttons: { 'Ok': function() {\$(this).dialog('close'); } } });}});\" value='выдать' class='button2' style='width: 100px;margin-top:-15px;' ".$bt_cash.">&nbsp;&nbsp;&nbsp;<div id='save_cash_p' style='display:inline;color:green;'></div></td></tr><tr><td colspan='3'><div id='pays_cash'>".$pays_cash."</div></td></tr></table></fieldset>

//<tr><td style='border-style:none;font-size:18px;' valign='top'>Суток на рейс:</td><td style='border-style:none;'>планово: <b><div id='km_div' style='display: inline'>".$days."</div></b> дн.</td><td style='border-style:none;'>фактически:</td><td style='border-style:none;' valign='middle'><input name='days' id='days' style='width: 40px;margin-top:-5px;' class='input' value='".$row['days']."' onchange=\"$('#cash_day_div').html($('#days').val()*$drv[2]);$('#cash_p_".$row['id']."').val($('#days').val()*$drv[2]);\"> дн.</td><td style='border-style:none;'><input type='button' id='btnDays_change' onclick=\"$.post('control/auto/trip.php?days='+$('#days').val()+'&trip_id=".$row['id']."', function(data) {var arr = data.split(/[|]/);if(arr[0]==1)$('#save_days').html('сохранено');$('#save_days').fadeIn(500);$('#save_days').fadeOut(2000);});\" value='изменить' class='button2' style='width: 100px;margin-top:-15px;'>&nbsp;&nbsp;&nbsp;<div id='save_days' style='display:inline;color:green;'></div></td></tr>
       	
$data->rows[0]['cell'] =array("<div id='petrol_".$row['id']."' style='background:#F8F8F8;display:none;'><table><tr><td>На начало рейса:</td><td><input name='start_petrol' id='start_petrol_".$row['id']."' style='width: 40px;' class='input' value='".$row['start_petrol']."'> л</td></tr><tr><td>На конец рейса:</td><td><input name='end_petrol' id='end_petrol_".$row['id']."' style='width: 40px;' class='input' value='".$row['end_petrol']."'> л</td></tr></table></div><fieldset><legend>Заявки <b>".$res_order."</b> (".date('d/m/Y',strtotime($row['data'])).") </legend><hr><table style='float:left;'><tr><td>Водитель: </td><td><b>".$drv[1]."</b></td></tr><tr><td>Тягач: </td><td><b>".$auto[1]." - ".$auto[3]."</b></td></tr><tr><td>П/прицеп: </td><td><b>".$dop_auto[1]." - ".$dop_auto[3]."</b></td></tr></table><table><tr><td style='border-style:none;font-size:18px;'>Километраж:</td><td style='border-style:none;' valign='middle'>по ati: <b>".$km_plan."</b> км</td><td style='border-style:none;' valign='middle'>фактически</td><td style='border-style:none;' valign='middle'><input name='km' id='km' style='width: 40px;margin-top:-5px;' class='input' value='".$km."' onchange=\"var days_p=Number($('#km').val()/$auto[4]).toFixed(0);$('#km_div').html(days_p);$('#days').val(days_p);$('#cash_day_div').html($('#days').val()*$drv[2]);$('#cash_p_".$row['id']."').val($('#days').val()*$drv[2]);\"> км</td><td style='border-style:none;'><input type='button' id='btnKm_change' onclick=\"$.post('control/auto/trip.php?km='+$('#km').val()+'&trip_id=".$row['id']."', function(data) {var arr = data.split(/[|]/);if(arr[0]==1){\$('#save_km').html('сохранено');$('#save_km').fadeIn(500);$('#save_km').fadeOut(2000);}});\" value='изменить' class='button2' style='width: 100px;margin-top:-15px;'>&nbsp;&nbsp;&nbsp;<div id='save_km' style='display:inline;color:green;'></div></td></tr><tr><td style='border-style:none;font-size:18px;' valign='top'>&nbsp;</td><td style='border-style:none;' align='center'></td><td style='border-style:none;'></td><td style='border-style:none;' colspan='2'><input type='button' id='btnReport_drv' onclick=\"$('#fa_report').load('add_drv_report.html?trip=".$row['id']."&card_id=".$card[0]."');$('#fa_report').dialog({ title: 'Авансовый отчет по рейсу №".$row['id']."' },{width: 790,height: 670,modal: true,resizable: false});\" value='Авансовый отчет' ".$avans." class='button2' style='width: 200px;height:35px;margin-top:-5px;'></td><td style='border-style:none;' width='30'><div style='margin-top:-5px;color:red;' id='avans_n'>".$avans_n."</div></td></tr></table></fieldset>


<fieldset  style='width:630px;float:left;'><legend>Подтверждённый расход:</legend><table><tr><td style='border-style:none;'><font color='green' size='3'>Подтв. <b>(НАЛ)</b>:</font></td><td style='border-style:none;' width='100'><b>".($cash_nal/100)."</b>  руб.</td><td style='border-style:none;'><font color='green' size='3'>Возврат <b>(НАЛ)</b>:</font></td><td style='border-style:none;'><b>".($cash_back/100)."</b>  руб.</td><td style='border-style:none;'><div id='petrol_div_d_".$row['id']."' style='display: inline'><b>".$row['start_petrol']." - ".$row['end_petrol']."</b> л (".$trip[0].")</div></td></tr><tr><td style='border-style:none;'><font color='green' size='3'>Подтв. <b>(БезНАЛ)</b>:</font></td><td style='border-style:none;'><b>".($cash_beznal/100)."</b>  руб.</td><td style='border-style:none;'><font color='green' size='3'>Потрачено ГСМ:</font></td><td style='border-style:none;'><b><div id='petrol_div_".$row['id']."' style='display: inline'>".((int)$row['start_petrol']-(int)$row['end_petrol']+(int)$petrol/100)."</div></b> л.</td><td style='border-style:none;'><input type='button' id='btnPetrol' onclick=\"$('#petrol_".$row['id']."').dialog({ title: 'Остаток топлива' },{ modal: true },{ resizable: false },{ buttons: { 'Сохранить': function() {\$.post('control/auto/trip.php?mode=petrol&start_petrol='+$('#start_petrol_".$row['id']."').val()+'&end_petrol='+$('#end_petrol_".$row['id']."').val()+'&trip_id=".$row['id']."', function(data) {var arr = data.split(/[|]/);if(arr[0]==1){\$('#petrol_div_d_".$row['id']."').html('<b>'+$('#start_petrol_".$row['id']."').val()+' - '+$('#end_petrol_".$row['id']."').val()+ '</b> л');$('#petrol_div_".$row['id']."').html(parseInt(document.getElementById('start_petrol_".$row['id']."').value)+parseFloat('".((int)$petrol/100)."')-parseInt(document.getElementById('end_petrol_".$row['id']."').value));}}); $(this).dialog('close'); } } });\" value='Топливо' class='button3' style='width: 100px;margin-top:-10px;height:35px;'></td></tr></table></fieldset><fieldset  style='width:330px;margin-top:20px;'><legend><b>Комментарий:</b></legend>".$row['notify']."</fieldset>");
	                
	        }



header("Content-type: text/script;charset=utf-8");
echo json_encode($data);



}

if ($_GET['mode']=='delete') 
{
$id =$_GET['id'];

$query_trip = "UPDATE `vtl_trip` SET `delete`='1' WHERE `id`='".(int)$id."'";
$result_trip = mysql_query($query_trip) or die(mysql_error());

$query_pay = "UPDATE `drivers_report` SET `delete`='1' WHERE `trip`='".(int)$id."'";
$result_pay = mysql_query($query_pay) or die(mysql_error());


echo '<font color="red" size="4">Рейс удалён!</font>';

}

?>