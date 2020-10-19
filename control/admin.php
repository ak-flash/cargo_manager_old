<?php
include "../config.php";
session_start();

if (@$_GET['mode'] == "profile" && isset($_SESSION['user_id'])) {
    $password = $_POST['password'];
    $email = mysql_escape_string($_POST['email']);
    $extra_query = '';

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if ($password != '') $extra_query = ", password = '" . md5($password) . "', pass_hash = '" . $hashed_password . "'";


    $query_update_pass = "UPDATE workers SET email = '" . $email . "'" . $extra_query . " WHERE id = " . (int)$_SESSION['user_id'];
    $result_update_pass = mysql_query($query_update_pass) or die(mysql_error());

    echo 'Сохранено!';
}

if (@$_GET['mode'] == "save_date") {
    $now_month = (int)$_GET['now_month'];
    $last_month = (int)$_GET['last_month'];
    $now_date = (int)$_GET['now_date'];

    $query = "UPDATE `settings` SET `all_day_month`='" . mysql_escape_string($now_month) . "',`day_last_month`='" . mysql_escape_string($last_month) . "',`work_day`='" . mysql_escape_string($now_date) . "',`now_date`='" . date("Y-m-d") . "'";
    $result = mysql_query($query) or die(mysql_error());
    echo 'Сохранено!';
}

if (@$_GET['mode'] == "renew_international") {
    $ord_agat_id = (int)$_GET['ord_international_id'];
    $query_check = "SELECT `international_number`,`cl_cont`,`tr_cont` FROM `orders` WHERE `id`='" . $ord_agat_id . "'";
    $result_check = mysql_query($query_check) or die(mysql_error());
    $check = mysql_fetch_row($result_check);

    if (((int)$check[1] == 9 || (int)$check[1] == 10 || (int)$check[2] == 9 || (int)$check[2] == 10) && $check[0] == "") {
        $query_settings = "SELECT `international_number` FROM `settings`";
        $result_settings = mysql_query($query_settings) or die(mysql_error());
        $settings = mysql_fetch_row($result_settings);
        $international_number = 'М-' . $settings[0];
        $query_settings = "UPDATE `settings` SET `international_number`='" . ((int)$settings[0] + 1) . "'";
        $result_settings = mysql_query($query_settings) or die(mysql_error());
$query_international_renew = "UPDATE `orders` SET `international_number`='".$international_number."' WHERE `id`='".$ord_agat_id."'";
$result_international_renew = mysql_query($query_international_renew) or die(mysql_error());
$message="Номер заявки: <b>".$international_number.'<b>';
} else $message="Номер уже присвоен"; 

echo $message;
}


if(@$_GET['mode']=="pay_unlock")
{
    $pays = '';

if(@$_GET['pay']=="") {echo "Выберите платеж(и)!";} else {
$str = explode(',',$_GET['pay']);
$res = (int)sizeof($str)-1;
$f=0;
$error='';
while ($f<=$res) {
$pays = $str[$f].",".$pays;

	
if($_SESSION['group']==1||$_SESSION['group']==2||$_SESSION['group']==4){
$query = "SELECT * FROM `pays` WHERE `Id`='".mysql_escape_string($str[$f])."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);

if($row['status']==1){
$query_bill = "SELECT `c_cash` FROM `bill` WHERE `id`='".mysql_escape_string($row['pay_bill'])."'";
$result_bill = mysql_query($query_bill) or die(mysql_error());
$bill = mysql_fetch_row($result_bill);

if($row['way']==1){$c_cash=$bill[0]-$row['cash'];}
if($row['way']==2){$c_cash=$bill[0]+$row['cash'];}

$query = "UPDATE `bill` SET `c_cash`='$c_cash' WHERE `id`='".mysql_escape_string($row['pay_bill'])."'";
$result = mysql_query($query) or die(mysql_error());

$query = "UPDATE `pays` SET `status`='0' WHERE `id`='".mysql_escape_string($str[$f])."'";
$result = mysql_query($query) or die(mysql_error());
$success='Платёж <b>№ '.substr($pays,0,strlen($pays)-1).'</b> распроведён!<br>';
} else {$error.='Платёж №'.$str[$f].' не является проведённым!<br>';}

$f++;
}

}

echo $success.$error;
}
}


if(@$_GET['mode']=="pay_lock")
{


if(@$_GET['pay']=="") {echo "Выберите платеж(и)!";} else {
$str = explode(',',$_GET['pay']);
$res = (int)sizeof($str)-1;
$f=0;
$error='';
while ($f<=$res) {
$pays=$str[$f].",".$pays;

	
if($_SESSION['group']==1||$_SESSION['group']==2||$_SESSION['group']==4){
$query = "SELECT * FROM `pays` WHERE `Id`='".mysql_escape_string($str[$f])."'";
$result = mysql_query($query) or die(mysql_error());
$row = mysql_fetch_array($result);

if($row['status']==0){
$query_bill = "SELECT `c_cash` FROM `bill` WHERE `id`='".mysql_escape_string($row['pay_bill'])."'";
$result_bill = mysql_query($query_bill) or die(mysql_error());
$bill = mysql_fetch_row($result_bill);

if($row['way']==1){$c_cash=$bill[0]+$row['cash'];}
if($row['way']==2){$c_cash=$bill[0]-$row['cash'];}

$query = "UPDATE `bill` SET `c_cash`='$c_cash' WHERE `id`='".mysql_escape_string($row['pay_bill'])."'";
$result = mysql_query($query) or die(mysql_error());

$query = "UPDATE `pays` SET `status`='1' WHERE `id`='".mysql_escape_string($str[$f])."'";
$result = mysql_query($query) or die(mysql_error());
$success='Платёж(ы) <b>№ '.substr($pays,0,strlen($pays)-1).'</b> проведён(ы)!';
} else {$error.='Платёж №'.$str[$f].' уже проведён!<br>';}

$f++;
}

}

echo $success.$error;
}
}


if(@$_GET['mode']=="day")
{
$query = "SELECT `now_date`,`work_day`,`all_day_month` FROM `settings`";
$result = mysql_query($query) or die(mysql_error());
$datas= mysql_fetch_row($result);

if(@$_GET['count_day']!="1")
{
if(strtotime($datas[0])!=strtotime(date("Y-m-d"))){
$query = "update `settings` SET `work_day`=`work_day`+1,`now_date`='".date("Y-m-d")."'";
$result = mysql_query($query) or die(mysql_error());	
echo "Приятного рабочего дня!";
} else echo "Рабочий день уже начат!";
} else {

echo $datas[1].'|'.$datas[2];
}




}


if(@$_GET['mode']=="close_info")
{

$query = "update `workers` SET `show_info`='".date("Y-m-d")."' WHERE `Id`='".mysql_escape_string((int)$_SESSION["user_id"])."'";
$result = mysql_query($query) or die(mysql_error());	


}


if(@$_GET['del_appoint']!=""&&isset($_GET['del_appoint']))
{
if($_SESSION["group"]==1||$_SESSION["group"]==2){

$query = "SELECT `id` FROM `pays` WHERE `appoint`='".mysql_escape_string((int)$_GET['del_appoint'])."'";
$result = mysql_query($query) or die(mysql_error());	


if(mysql_num_rows($result) == 0){
$query = "DELETE FROM `pays_appoints` WHERE Id='".mysql_escape_string((int)$_GET['del_appoint'])."'";
$result = mysql_query($query) or die(mysql_error());} else {echo "По данной категории имеются платежи! Удаление невозможно...";}
}	
}


	
if(@$_GET['order_code']!=""&&isset($_GET['order_code']))
{
$code=$_GET['order_code'];
$query_code = "UPDATE `security_code` SET `code`='".$code."'";
$result_code = mysql_query($query_code) or die(mysql_error());
echo "Пароль добавлен.";
}

if(isset($_GET['unlock']))
{
 if(@$_GET['unlock']=="") {echo "Выберите заявку(и)!";} else {
$str = explode(',',$_GET['unlock']);
$res = (int)sizeof($str)-1;
$f=0;
while ($f<=$res) {
$query_orders = "UPDATE `orders` SET `block`='0' WHERE Id='".$str[$f]."'";
$result_orders = mysql_query($query_orders) or die(mysql_error());
$orders=$str[$f].",".$orders;
$f++;
}

echo "Заявка(и) <b>№ ".substr($orders,0,strlen($orders)-1)."</b> разблокирована(ы)";
}
}

if(isset($_GET['lock']))
{
 if(@$_GET['lock']=="") {echo "Выберите заявку(и)!";} else {
$str = explode(',',$_GET['lock']);
$res = (int)sizeof($str)-1;
$f=0;
while ($f<=$res) {
$query_orders = "UPDATE `orders` SET `block`='1' WHERE Id='".$str[$f]."'";
$result_orders = mysql_query($query_orders) or die(mysql_error());
$orders=$str[$f].",".$orders;
$f++;
}

echo "Заявка(и) <b>№ ".substr($orders,0,strlen($orders)-1)."</b> заблокирована(ы)";
}
}

if(isset($_GET['vzaimozachet']))
{
 if(@$_GET['vzaimozachet']=="") {echo "Выберите заявку(и)!";} else {
$str = explode(',',$_GET['vzaimozachet']);
$res = (int)sizeof($str)-1;
$f=0;
while ($f<=$res) {

$query_ord = "SELECT `vzaimozachet` FROM `orders` WHERE `Id`='".mysql_escape_string($str[$f])."'";
$result_ord = mysql_query($query_ord) or die(mysql_error());
$check_ord= mysql_fetch_row($result_ord);
if($check_ord[0]==0) {$query_orders = "UPDATE `orders` SET `vzaimozachet`='1' WHERE `Id`='".mysql_escape_string($str[$f])."'"; $set=1;} else {$query_orders = "UPDATE `orders` SET `vzaimozachet`='0' WHERE `Id`='".mysql_escape_string($str[$f])."'";$set=0;}
$result_orders = mysql_query($query_orders) or die(mysql_error());
$orders=$str[$f].",".$orders;
$f++;
}
if($set==1) echo "Заявка(и) <b>№ ".substr($orders,0,strlen($orders)-1)."</b> отмечена(ы) как <b>взаимозачтённые</b>."; else echo "C заявки(ок) <b>№ ".substr($orders,0,strlen($orders)-1)."</b> снята(ы) отметка(и) - <b>взаимозачтённые</b>.";
}
}


if(isset($_GET['pretenzia']))
{
 if(@$_GET['pretenzia']=="") {echo "Выберите заявку(и)!";} else {
$str = explode(',',$_GET['pretenzia']);
$res = (int)sizeof($str)-1;
$f=0;
while ($f<=$res) {

$query_ord = "SELECT `pretenzia`,`cl_cash`,`data` FROM `orders` WHERE `Id`='".mysql_escape_string($str[$f])."'";
$result_ord = mysql_query($query_ord) or die(mysql_error());
$check_ord= mysql_fetch_row($result_ord);
if($check_ord[0]==0) {
$query_orders = "UPDATE `orders` SET `pretenzia`='1' WHERE `Id`='".mysql_escape_string($str[$f])."'"; $set=1;

$query_autopay = "INSERT INTO `pays` (`date`,`way`,`nds`,`category`,`appoint`,`order`,`cash`,`status`,`notify`,`add_name`,`payment_source`,`del_id`,`pay_bill`,`car_id`) VALUES ('".$check_ord[2]."','1','1','1','1','".$str[$f]."','".((int)$check_ord[1]*100)."','1','pretenzia','1000','0','0','0','0')";
$result_autopay = mysql_query($query_autopay) or die(mysql_error());

} else {

$query_orders = "UPDATE `orders` SET `pretenzia`='0' WHERE `Id`='".mysql_escape_string($str[$f])."'";$set=0;

$query_del = "DELETE FROM `pays` WHERE `order`='".mysql_escape_string($str[$f])."' AND `way`='1' AND `category`='1' AND `appoint`='1'";
$result_del = mysql_query($query_del) or die(mysql_error());

}
$result_orders = mysql_query($query_orders) or die(mysql_error());
$orders=$str[$f].",".$orders;
$f++;
}
if($set==1) echo "Заявка(и) <b>№ ".substr($orders,0,strlen($orders)-1)."</b> отмечена(ы) как <b>претензионные</b>."; else echo "C заявки(ок) <b>№ ".substr($orders,0,strlen($orders)-1)."</b> снята(ы) отметка(и) - <b>претензионные</b>.";
}
}


if(@$_GET['pay_id']!=""&&isset($_GET['pay_id']))
{

$str = explode(',',$_GET['pay_id']);
$res = (int)sizeof($str)-1;
$f=0;
while ($f<=$res) {
$query_pays = "UPDATE `pays` SET `status`='1' WHERE Id='".mysql_escape_string($str[$f])."'";
$result_pays = mysql_query($query_pays) or die(mysql_error());
$pays=$str[$f].",".$pays;
$f++;
}

echo "Платеж(и) <b>№ ".substr($pays,0,strlen($pays)-1)."</b> проведен(ы)";
}


if(@$_GET['mode']=="group")
{

$group_name=$_GET['cl_group_name'];
$group_cl=$_GET['group_cl'];

$query_group = "INSERT INTO `cl_group` (`group_name`,`group_cl`) VALUES ('".mysql_escape_string($group_name)."','".mysql_escape_string($group_cl)."')";
$result_group = mysql_query($query_group) or die(mysql_error());

echo "Клиенты <b>№ ".$group_cl."</b> обьединены в группу <b>".$group_name."</b>";


}

if(@$_GET['mode']=="del_cl_group")
{
$group_id=$_GET['group_id'];

$query = "DELETE FROM `cl_group` WHERE `group_id`='".mysql_escape_string($group_id)."'";
$result = mysql_query($query) or die(mysql_error());

}

if(isset($_GET['groupping']))
{
    if(@$_GET['groupping']=="") {echo "Выберите заявку(и)!";} else {
        $str = explode(',',$_GET['groupping']);

        $res = (int)sizeof($str)-1;
        $f=0;
        $err_info = '';
        asort($str);
        while ($f<=$res) {

            $query_ord = "SELECT `group_id` FROM `orders` WHERE `Id`='".mysql_escape_string($str[$f])."'";
            $result_ord = mysql_query($query_ord) or die(mysql_error());
            $check_ord= mysql_fetch_row($result_ord);

            $check_group_count = explode(',',$check_ord[0]);

            if($check_ord[0]==0||$check_ord[0]==''){
                $query_orders = "UPDATE `orders` SET `group_id`='" . mysql_escape_string(implode(',', $str)) . "' WHERE `Id`='" . mysql_escape_string($str[$f]) . "'";
                $set = 1;
            } else {
                if (sizeof($check_group_count) != sizeof($str)) {
                    $err_info = 'Выберите все заявки группы для разъединения!';
                } else {
                    $query_orders = "UPDATE `orders` SET `group_id`='' WHERE `Id`='" . mysql_escape_string($str[$f]) . "'";
                    $set = 2;
                }

            }

            if(isset($query_orders)) $result_orders = mysql_query($query_orders) or die(mysql_error());
            $orders=$str[$f].",".$orders;
            $f++;
        }
        if($set==1) echo "Заявка(и) <b>№ ".substr($orders,0,strlen($orders)-1)."</b> объединены по перевозчику";
        if($set==2) echo "С заявок <b>№ ".substr($orders,0,strlen($orders)-1)."</b> снята <b>группировка</b>.";
        if($err_info!='') echo "<b>".$err_info."</b>";
    }
}

?>