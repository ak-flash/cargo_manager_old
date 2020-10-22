<?php
session_start();
include "../config.php";


if(@$_GET['way']!=""&&@$_GET['category']!="")
{
$way=(int)$_GET['way'];	
$category=(int)$_GET['category'];

if($category==1){
$query = "SELECT `id`,`app` FROM `pays_appoints` WHERE `way`='".mysql_escape_string($way)."' AND `group`='1'";
}

if($category==2&&($_SESSION['group']==1||$_SESSION['group']==2)){
$query = "SELECT `id`,`app`,`car` FROM `pays_appoints` WHERE `group`='2' ORDER BY `app` ASC";
}

if($category==2&&$_SESSION['group']==4){
$query = "SELECT `id`,`app`,`car` FROM `pays_appoints` WHERE `group`='2' AND `auth_id`='3' ORDER BY `app` ASC";
}

$result = mysql_query($query) or die(mysql_error());
echo ' <select name="appointment"  id="appointment" class="select" style="width:140px;" ';
if($_GET['status']==1)echo 'disabled';
echo '>
   <option value="0">Выберите...</option>';
while($pays_app= mysql_fetch_row($result)) {
	if($pays_app[2]=='1')$temp='document.getElementById(\'car_ls\').style.display=\'inline\';'; else $temp='document.getElementById(\'car_ls\').style.display=\'none\';';
echo '<option value='.$pays_app[0].' onclick="$(\'#appoint\').html(\'<u>\'+this.text+\'</u>\');'.$temp.'">'.$pays_app[1].'</option>';
}
echo ' </select>';
}





if(@$_GET['order_id']!="")
{
$order_id = (int)$_GET['order_id'];
$order_type = (int)$_GET['order_type'];
$mode = (int)$_GET['mode'];
$way = (int)$_GET['way'];


if($mode=='1'){

$query = "SELECT `cl_cash`,`cl_kop`,`cl_currency` FROM `orders` WHERE `id`='" . mysql_escape_string($order_id) . "' AND `delete`='0'";
    $result = mysql_query($query) or die(mysql_error());
if(mysql_num_rows($result) == 1){

    $row = mysql_fetch_row($result);

    $cl_pay = 0;
    $query_pays_in = "SELECT `cash` FROM `pays` WHERE `order`='" . mysql_escape_string($order_id) . "' AND `delete`='0' AND `way`='" . mysql_escape_string($way) . "' AND `appoint`='" . mysql_escape_string($mode) . "' AND `status`='1'";
    $result_pays_in = mysql_query($query_pays_in) or die(mysql_error());
    while ($pay_in = mysql_fetch_row($result_pays_in)) {
        $cl_pay_in = (int)$pay_in[0] + (int)$cl_pay_in;
    }

//echo ($row[0].'.'.$row[1]-$cl_pay_in/100);
    if ((int)$row[0] * 100 == (int)$cl_pay_in) {
        echo 'fully_payed|';
    } else {
        echo 'success|' . ((int)$row[0] - (int)$cl_pay_in / 100) . '|' . ((int)$cl_pay_in / 100) . '|' . $row[0] . '|' . $row[1];
    }
} else echo 'not_exist|';
}

if($mode=='2'){
    $query = "SELECT `tr_cash`,`tr_currency` FROM `orders` WHERE `id`='" . mysql_escape_string($order_id) . "' AND `delete`='0'";
    $result = mysql_query($query) or die(mysql_error());
    if (mysql_num_rows($result) == 1) {
        $row = mysql_fetch_row($result);

        $tr_pay = 0;
        $query_pays_in = "SELECT `cash` FROM `pays` WHERE `order`='" . mysql_escape_string($order_id) . "' AND `delete`='0' AND `way`='" . mysql_escape_string($way) . "' AND `appoint`='" . mysql_escape_string($mode) . "' AND `status`='1'";
        $result_pays_in = mysql_query($query_pays_in) or die(mysql_error());
        while ($pay_in = mysql_fetch_row($result_pays_in)) {
            $tr_pay_in = (int)$pay_in[0] + (int)$tr_pay_in;
        }
        if ((int)$row[0] * 100 == (int)$tr_pay_in) {
            echo 'fully_payed|';
        } else {
            echo 'success|' . ((int)$row[0] - (int)$tr_pay_in / 100) . '|' . ((int)$tr_pay_in / 100) . '|' . $row[0] . '|' . $row[1];
        }
    } else echo 'not_exist|';
}	

if($mode=='3'){
    $query = "SELECT `cl_minus`,`cl_currency` FROM `orders` WHERE `id`='" . mysql_escape_string($order_id) . "' AND `delete`='0'";
    $result = mysql_query($query) or die(mysql_error());
    if (mysql_num_rows($result) == 1) {
        $row = mysql_fetch_row($result);

        $cl_pay = 0;
        $query_pays_in = "SELECT `cash` FROM `pays` WHERE `order`='" . mysql_escape_string($order_id) . "' AND `delete`='0' AND `way`='" . mysql_escape_string($way) . "' AND `appoint`='" . mysql_escape_string($mode) . "' AND `status`='1'";
        $result_pays_in = mysql_query($query_pays_in) or die(mysql_error());
        while ($pay_in = mysql_fetch_row($result_pays_in)) {
            $cl_pay_in = (int)$pay_in[0] + (int)$cl_pay_in;
        }
        if ((int)$row[0] * 100 == (int)$cl_pay_in) {
            echo 'fully_payed|';
        } else {
            echo 'success|' . ((int)$row[0] - (int)$cl_pay_in / 100) . '|' . ((int)$cl_pay_in / 100) . '|' . $row[0] . '|' . $row[1];
        }
    } else echo 'not_exist|';
}

if($mode=='4'){
    $query = "SELECT `cl_plus`,`cl_currency` FROM `orders` WHERE `id`='" . mysql_escape_string($order_id) . "' AND `delete`='0'";
    $result = mysql_query($query) or die(mysql_error());
    if (mysql_num_rows($result) == 1) {
        $row = mysql_fetch_row($result);

        $cl_pay = 0;
        $query_pays_in = "SELECT `cash` FROM `pays` WHERE `order`='" . mysql_escape_string($order_id) . "' AND `delete`='0' AND `way`='" . mysql_escape_string($way) . "' AND `appoint`='" . mysql_escape_string($mode) . "' AND `status`='1'";
        $result_pays_in = mysql_query($query_pays_in) or die(mysql_error());
        while ($pay_in = mysql_fetch_row($result_pays_in)) {
            $cl_pay_in = (int)$pay_in[0] + (int)$cl_pay_in;
        }
        echo 'success|' . ((int)$row[0] + (int)$cl_pay_in / 100) . '|' . ((int)$cl_pay_in / 100) . '|' . $row[0] . '|' . $row[1];
    } else echo 'not_exist|';
}

if($mode=='5'){
    $query = "SELECT `tr_minus`,`tr_currency` FROM `orders` WHERE `id`='" . mysql_escape_string($order_id) . "' AND `delete`='0'";
    $result = mysql_query($query) or die(mysql_error());
    if (mysql_num_rows($result) == 1) {
        $row = mysql_fetch_row($result);

        $tr_pay_in = 0;
        $query_pays_in = "SELECT `cash` FROM `pays` WHERE `order`='" . mysql_escape_string($order_id) . "' AND `delete`='0' AND `way`='" . mysql_escape_string($way) . "' AND `appoint`='" . mysql_escape_string($mode) . "' AND `status`='1'";
        $result_pays_in = mysql_query($query_pays_in) or die(mysql_error());
        while ($pay_in = mysql_fetch_row($result_pays_in)) {
            $tr_pay_in = (int)$pay_in[0] + (int)$tr_pay_in;
        }
        echo 'success|' . ((int)$row[0] + (int)$tr_pay_in / 100) . '|' . ((int)$tr_pay_in / 100) . '|' . $row[0] . '|' . $row[1];
    } else echo 'not_exist|';
}

if($mode=='6'){
    $query = "SELECT `tr_plus`,`tr_currency` FROM `orders` WHERE `id`='" . mysql_escape_string($order_id) . "' AND `delete`='0'";
    $result = mysql_query($query) or die(mysql_error());
    if (mysql_num_rows($result) == 1) {
        $row = mysql_fetch_row($result);

        $tr_pay_out = 0;
        $query_pays_out = "SELECT `cash` FROM `pays` WHERE `order`='" . mysql_escape_string($order_id) . "' AND `delete`='0' AND `way`='" . mysql_escape_string($way) . "' AND `appoint`='" . mysql_escape_string($mode) . "' AND `status`='1'";
        $result_pays_out = mysql_query($query_pays_out) or die(mysql_error());
        while ($pay_out = mysql_fetch_row($result_pays_out)) {
            $tr_pay_out = (int)$pay_out[0] + (int)$tr_pay_out;
        }

        echo 'success|' . ((int)$row[0] - (int)$tr_pay_out / 100) . '|' . ((int)$tr_pay_out / 100) . '|' . $row[0] . '|' . $row[1];
    } else echo 'not_exist|';
}


}

if (isset($_GET['group_id']) && @$_GET['group_id'] != "") {
    $group_id = (int)$_GET['group_id'];

    $mode = (int)$_GET['mode'];
    $way = (int)$_GET['way'];

    $data_arr = array();
    $data_arr["group_info"] = array();
    $tr_pay_in_total = 0;
    $tr_cash_total = 0;

    if ($mode == '2') {
        $query = "SELECT `id`,`tr_cash`,`tr_currency` FROM `orders` WHERE `group_id`=" . $group_id;
        $result = mysql_query($query) or die(mysql_error());

        if (mysql_num_rows($result) > 0) {

            while ($row = mysql_fetch_array($result)) {
                $tr_pay_in = 0;

                $query_pays_in = "SELECT `cash` FROM `pays` WHERE `order`='" . $row['id'] . "' AND `delete`='0' AND `way`='" . $way . "' AND `appoint`='" . $mode . "' AND `status`='1'";
                $result_pays_in = mysql_query($query_pays_in) or die(mysql_error());
                while ($pay_in = mysql_fetch_row($result_pays_in)) {
                    $tr_pay_in = (int)$pay_in[0] + (int)$tr_pay_in;
                }

                $data_item = array(
                    "id" => $row['id'],
                    "tr_cash" => $row['tr_cash'],
                    "tr_payed" => (int)$tr_pay_in / 100
                );

                array_push($data_arr["group_info"], $data_item);

                $tr_pay_in_total += (int)$tr_pay_in / 100;
                $tr_cash_total += $row['tr_cash'];

                $data_arr["tr_currency"] = $row['tr_currency'];
            }


            $data_arr["tr_total_cash"] = $tr_cash_total;
            $data_arr["tr_total_payed"] = $tr_pay_in_total;

            if ($tr_pay_in_total == $tr_cash_total) {
                echo 'fully_payed|';
            } else {
                echo 'success|' . json_encode($data_arr);
            }


        } else echo 'not_exist|';
    }
}
?>