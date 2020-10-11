<?php 
include "../config.php";

if (@$_GET['company']!=""||intval($_GET['company'])){
$company=$_GET['company'];

	
$query_bill = "SELECT * FROM `bill` WHERE `company`='".mysql_escape_string($company)."' AND `delete`='0' AND `default`='1'";
$result_bill = mysql_query($query_bill) or die(mysql_error());

$f=0;





if(mysql_num_rows($result_bill)!=0)
		{
		$bill_info = mysql_fetch_row($result_bill);
		
		echo '<script type="text/javascript">$("#pay_bill").val("'.$bill_info[0].'");$("#bill_info_balance").html("Баланс: <font size=5><b>'.number_format($bill_info[6]/100, 2, ',', ' ').'</b></font> руб.");$("#fa_bill_company").dialog("close");$("#bill_info_bank").html("Банк: <b>'.addslashes($bill_info[4]).'</b>");</script>';
		
		
		} else {echo '<script type="text/javascript">$("#bill_info_bank").html("-");$("#bill_info").html("<b>Счет не выбран</b>");$("#bill_info_balance").html("<font size=4>Баланс: <b>n/a</b></font> руб.");</script>';}	
}	
	?>