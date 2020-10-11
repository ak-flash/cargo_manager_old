<?php 
 if(@$_GET['mode']=="show_group")
{
include "../config.php";


	
$query_group = "SELECT * FROM `cl_group`";
$result_group = mysql_query($query_group) or die(mysql_error());




echo '<div style="height: 300px; overflow: auto;">';


if(mysql_num_rows($result_group)!=0)
		{
			
echo '<table cellpadding="5"  style="width:100%;overflow:auto;border-collapse: collapse;border-style: solid;border-color: black;" border="1"><tr><td bgcolor="#edf1f3" align=center>№</td><td bgcolor="#edf1f3" align=center>Клиент</td><td bgcolor="#edf1f3" align=center>№ клиентов в базе</td><td bgcolor="#edf1f3" align=center>Упр.</td></tr>';			

while($group = mysql_fetch_array($result_group)) {
	


echo '<tr><td align=center bgcolor=#F2F5F7><font size=4><b>'.$group['group_id'].'</b></font></td><td><b>'.$group['group_name'].'</b></td><td align=center>'.$group['group_cl'].'</td><td align=center bgcolor=#F2F5F7><a href="#" onclick=\'$("#result").html("<b>Удалить группу <font color=red>«'.$group['group_name'].'»</font>?</b>");
$("#result").dialog({ title: "Внимание" },{ modal: true },{ width: 350 },{ resizable: false },{ buttons: [{text: "Да",click: function() {$(this).dialog("close");$.post("control/admin.php?mode=del_cl_group&group_id='.$group['group_id'].'", function(data) {$("#div_cl_group").load("/control/cl_group.php?mode=show_group");});}},{text: "Нет",click: function() {$(this).dialog("close");}}] });\' class="btnAdress">удалить</a></td></tr>';

 
}
echo '</table></fieldset>';

} 
echo '</div></fieldset>';


}
?>	