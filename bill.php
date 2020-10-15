<?php 
session_start();
if (@$_GET['company']!=""||intval($_GET['company'])){
$company=$_GET['company'];
} 
?>


<?php  if ($company!="") {

echo '<fieldset style="width:93%;"><legend><font size="4"><b>'.$_GET['c_name'].'</b></font></legend>';
 
include "config.php";


	
$query_bill = "SELECT * FROM `bill` WHERE `company`='".mysql_escape_string($company)."' AND `delete`='0'";
$result_bill = mysql_query($query_bill) or die(mysql_error());

$f=0;


echo '<div style="height: 240px; overflow: auto;">';


if(mysql_num_rows($result_bill)!=0)
		{
			
echo '<table cellpadding="5"  style="width:100%;overflow:auto;border-collapse: collapse;border-style: solid;border-color: black;" border="1"><tr><td bgcolor="#edf1f3" align=center>№</td><td bgcolor="#edf1f3" align=center>№ Счета</td><td bgcolor="#edf1f3" align=center>БАНК</td><td bgcolor="#edf1f3" align=center width=200>Баланс</td><td bgcolor="#edf1f3" align=center>Упр.</td></tr>';			

while($bill_info = mysql_fetch_row($result_bill)) {
	
$cash_el= explode(",",number_format($bill_info[6]/100, 2, ',', ' '));
$cash_all="<font size=\"4\">".$cash_el[0]." </font>руб.".$cash_el[1]." коп.";

echo '<tr><td align=center bgcolor=#F2F5F7><font size=4><b>'.($f+1).'</b></font></td><td align=center><b>'.$bill_info[2].'</b></td><td align=center>'.$bill_info[4].'</td><td  align=center>'.$cash_all.' </td><td align=center bgcolor=#F2F5F7><a href="#" id="car" onclick=\'$("#pay_bill").val("'.$bill_info[0].'");$("#bill_info_balance").html("Баланс: <font size=5><b>'.number_format($bill_info[6]/100, 2, ',', ' ').'</b></font> руб.");$("#fa_bill_company").dialog("close");$("#bill_info_bank").html("Банк: <b>'.addslashes($bill_info[4]).'</b>");\' class="btnAdress">выбрать</a></td></tr>';

 
$f++;}
echo '</table></fieldset>';

} else {echo '<h2>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Счета отсутствуют...</h2>
<script type="text/javascript">$("#bill_info_bank").html("-");$("#bill_info").html("<b>Счет не выбран</b>");$("#bill_info_balance").html("<font size=4>Баланс: <b>n/a</b></font> руб.");</script>';}
echo '</div></fieldset>';

}

?>	








