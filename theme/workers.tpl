<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Управление сотрудниками</title>
    <?php include_once("data/header.html");?>


    <script type="text/javascript">
        $(function () {

            $("#control_menu_css").css({ "background": "#BCBCBC", "color": "#000", "text-shadow": "1px 2px 2px #FFF"});
            $('#btnPass').button();
            $('#btnReport').button();
            $('#btnReport_new').button();
            $('#btnMotive').button();
            $('#btnBill').button();
            $('#btnAdd').button();
            $('#btnAll').button();
            $('#btnDrivers').button();
            $('#btnManagers').button();
            $('#btnShowDel').button();
            let table = $('#table');
            table.jqGrid({
                url: 'control/workers.php?mode=workers',
                datatype: 'json',
                mtype: 'GET',
                colNames: ['№', 'Ф.И.О.', 'Статус', 'Почта', 'Телефон', 'Дата приема<br> на работу', 'Оклад'],
                colModel: [
                    {name: 'id', index: 'id', width: 5, align: ['center']},
                    {name: 'name', index: 'name', width: 40},
                    {name: 'group', index: 'group', width: 20, align: ['center']},
                    {name: 'email', index: 'email', width: 20, align: ['center']},
                    {name: 'phone', index: 'phone', width: 20, align: ['center']},
                    {name: 'date_start', index: 'date_start', width: 15, align: ['center']},
                    {name: 'zarplata', index: 'zarplata', width: 15, align: ['center']}]
                ,
                rowNum: 8,
                height: 'auto',
                autowidth: true,
                viewrecords: true,
                sortname: 'id',
                sortorder: 'asc',
                caption: '&nbsp;&nbsp;&nbsp;&nbsp;Управление сотрудниками',
                subGrid: true,
                subGridUrl: 'control/workers.php?mode=desc', afterInsertRow: function (row_id, row_data) {


                },
                subGridOptions: {
                    "plusicon": "ui-icon-triangle-1-e",
                    "minusicon": "ui-icon-triangle-1-s",
                    "openicon": "ui-icon-arrowreturn-1-e"
                },
                subGridModel: [{
                    name: ['', 'Информация о сотруднике'],
                    align: ['center', 'left'],
                    width: [1, 640],
                    params: ['id']
                }],
                rownumbers: false,
                pager: $('#tablePager'),
                ondblClickRow: function (id) {
                    $("#fa_worker").load("theme/forms/add_worker.php?worker_id=" + id)
                        .dialog({title: 'Редактировать сотрудника №' + id}, {
                            width: 920, height: 730,
                            modal: true, resizable: false
                        });

                }


            }).navGrid('#tablePager', {edit:false,add:false,del:false,refresh:true,search:false,excel:false});
           
function updateTable(value) {
    jQuery("#table")
      .setGridParam({url:"control/workers.php?mode=search&name="+value, page:1})
        .trigger("reloadGrid");
}


            $("#btnAdd").click(function () {
                $("#fa_worker").load("theme/forms/add_worker.php")
                    .dialog({title: 'Новый сотрудник'}, {
                        width: 920, height: 730,
                        modal: true, resizable: false
                    });
            });

            $("#btnMotive").click(function () {
                $("#fa_worker").load("theme/forms/add_motive.php")
                    .dialog({title: 'Схема рассчета комиссии'}, {
                        width: 530, height: 460,
                        modal: true, resizable: false
                    });
            });


            $("#zarplata_year").val(<?php if((date("m")-1)==0)echo (date("Y")-1); else echo date("Y");?>).change();
$("#zarplata_month").val(<?php if((date("m")-1)==0)echo '12'; else echo (date("m")-1);?>).change();

});
    
</script>

</head>
<body>
<?php include "data/menu.html";?>


<div id="fa_bill" style="background:#F8F8F8;"></div>
<div id="fa_worker" style="display: none;background:#F8F8F8;"></div>
<!  - - форма добавления адреса  - - >  
<div id="fa_adr" style="background:#F8F8F8;"></div>
<div id="result" style="display: none;"></div><div id="result_temp" style="display: none;"></div>

<div class="main">
<a class="button3" id="btnAdd" href="#">Добавить</a>
<a class="button5" id="btnAll" href="#" onclick="$('#table').setGridParam({url:'control/workers.php?mode=workers'});
        jQuery('#table').trigger('reloadGrid');" style="width:100px;">Все</a>&nbsp;<a id="btnManagers" href="#"
                                                                                      onclick="$('#table').setGridParam({url:'control/workers.php?mode=workers&m=managers'});
                                                                                              jQuery('#table').trigger('reloadGrid');">Менеджеры</a>&nbsp;<a
            class="button5" id="btnDrivers" href="#"
            onclick="$('#table').setGridParam({url:'control/workers.php?mode=workers&m=drivers'});
                    jQuery('#table').trigger('reloadGrid');">Водители</a>&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;<a
            class="button3" id="btnShowDel" href="#"
            onclick="$('#table').setGridParam({url:'control/workers.php?mode=workers&m=arhiv'});
                    jQuery('#table').trigger('reloadGrid');">Архив</a>
    <div class="main_container">
        <table id="table"></table>
        <div id="tablePager"></div>


        <fieldset>
            <legend>Дополнительно:</legend>
            <input type="button" id="btnBill"
                   onclick='$("#fa_bill").load("theme/forms/add_zbill.php").dialog({ title: "Ведомость по оплате заработной платы" },{width: 970,height: 710,modal: true,resizable: false});'
                   value="Ведомость" style="font-size: 16px;width:150px;">&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
            Зарплатная ведомость:&nbsp;&nbsp;&nbsp;&nbsp;<select name="zarplata_month" style="width:110px;"
                                                                 id="zarplata_month" class="select">
                <option value="0">...</option>
                <option value="1">Январь</option>
                <option value="2">Февраль</option>
                <option value="3">Март</option>
                <option value="4">Апрель</option>
                <option value="5">Май</option>
                <option value="6">Июнь</option>
                <option value="7">Июль</option>
                <option value="8">Август</option>
                <option value="9">Сентябрь</option>
                <option value="10">Октябрь</option>
                <option value="11">Ноябрь</option>
                <option value="12">Декабрь</option>
            </select>&nbsp;&nbsp;<select name="zarplata_year" style="width:110px;" id="zarplata_year" class="select">';
                <?php
$i=-1;
while ($i <= 1) {
echo '<option value="'.(date("Y")+$i).'">'.(date("Y")+$i).'</option>';
                $i++;
                }
                echo '</select>&nbsp;&nbsp;&nbsp;<input type="button" id="btnReport_new"
                                                        onclick=\'window.location.href="/control/zarplata.php?mode=zarplata&month="+$("#zarplata_month").val()+"&year="+$("#zarplata_year").val();\'
                                                        value="Сформировать" style="font-size: 18px;width: 160px;">&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;';
            if($_SESSION["group"]==1||$_SESSION["group"]==2){echo '<input type="button" id="btnMotive" value="Рассчет комиссии" style="font-size: 14px;width: 180px;">';}?>
</fieldset>
<div id="pass_change_dialog" style="display: none;"><br>&nbsp;&nbsp;&nbsp;Пароль: <input name="pass_code" id="pass_code" value=""></div>         
</div>


<div class="right_container">&nbsp;</div>

</body>
</html>