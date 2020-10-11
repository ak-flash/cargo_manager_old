<?php
if (@$_GET['tr']!=""){
$trans=(int)$_GET['tr'];
}
?>


<script type="text/javascript">
$('#car_show').load('/control/car.php?mode=all&tr=<?php echo $trans;?>');




</script>


<fieldset><legend>Поиск:</legend>
<table><tr><td width="250">
<input name="q" id="search_car" placeholder="по автотранспорту" style="float:left;">
<input type="button" class="search_btn" value="" onclick="$('#car_show').load('/control/car.php?mode=all&search=true&s_data='+$('#search_car').val());">
</td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;* По Марке, Госномеру,Ф.И.О. водителя,номеру телефона водителя</td></tr></table>
</fieldset><br>
<div id="car_show"></div>


