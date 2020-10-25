<?php 
session_start();
if (@$_GET['company']!=""||intval($_GET['company'])){
$company=$_GET['company'];
} 
?>


<script type="text/javascript">


    $('#btnCl_bill').button();

    $('#s_bill_show').load('/control/c_bill.php?mode=show&company=<?php echo $company;?>');
    






   
</script>


<?php if ($company != "") {


    echo '&nbsp;&nbsp;&nbsp;<font size="5"><b>' . $_GET['c_name'] . '</b></font><br>';
} ?>
<br>
<div id="s_bill_show"></div>

<br>
<button id="btnadd_bill"
        onclick='$("#fa_add_bill").load("theme/forms/add_bill.php?mode=new&company=<?php echo $company; ?>");$("#fa_add_bill").dialog({ title: "Добавить новый счет" },{width: 520,height: 390,modal: true,resizable: false});'
        style="width: 150px;" class="button3">Добавить счет
</button>
<button class="button" id="btnCl_bill" onclick="$('#fa_c_bill').dialog('close');" style="width: 150px;height:35px;">
    Закрыть
</button>




