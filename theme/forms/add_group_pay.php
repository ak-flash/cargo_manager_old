<?php 
include "../../config.php";

$query = "SELECT `id`,`name` FROM `company` ORDER BY `Id` ASC";
$result = mysql_query($query) or die(mysql_error());
while($company= mysql_fetch_row($result)) {
$source.='<option value='.$company[0].'>«'.$company[1].'»</option>';
}
?>


<script type="text/javascript">
    $('#save_pay_group').button();
    $('#btnClose_pay_group').button();
    $("#date_pay_group").datepicker();

    $("#autopays_tabs").tabs({fx: {opacity: 'toggle', duration: 1}});


    $.mask.definitions['~'] = '[+-]';
    $('#date').mask('99/99/9999');

    function number_format(number, decimals, dec_point, thousands_sep) {	// Format a number with grouped thousands
        //
        // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
        // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
        // +	 bugfix by: Michael White (http://crestidg.com)

	var i, j, kw, kd, km;

	// input sanitation & defaults
	if( isNaN(decimals = Math.abs(decimals)) ){
		decimals = 2;
	}
	if( dec_point == undefined ){
		dec_point = ",";
	}
	if( thousands_sep == undefined ){
		thousands_sep = ".";
	}

	i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

	if( (j = i.length) > 3 ){
		j = j % 3;
	} else{
		j = 0;
	}

	km = (j ? i.substr(0, j) + thousands_sep : "");
	kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
	//kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
	kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


	return km + kw + kd;
}


    $("#form_pay_group").submit(function () {
        validate = true;


        if (validate) {

            $("#save_pay_group").attr("disabled", "disabled");

            var perfTimes = $("#form_pay_group").serialize();
            $.post("control/add_listpay_tr.php?create=gr&mode=order", perfTimes, function (data) {

                var arr = data.split(/[|]/);
                $('#result').html(arr[0]);
                if (arr[1] == 1) {
                    $("#fa_pay_group").dialog("close");
                } else $("#save_pay_group").attr("disabled", "");

                $("#result").dialog({title: 'Готово'}, {width: 410}, {modal: true}, {resizable: false}, {
                    buttons: {
                        "Ok": function () {
                            $(this).dialog("close");
                            jQuery("#table").trigger("reloadGrid");
                        }
                    }
                });
            });
        }

        return false;

    });

    function check_tr_cash(num, order_id) {

        $('#plan_cash_' + num).load('control/pay_load.php?order_id=' + order_id + '&mode=2&way=2', function (data) {
            var arr = data.split(/[|]/);

            if (arr[0] == 'not_exist') {
                $('#result').html('Заявка №<b>' + order_id + '</b> не существует!');
            }

            if (arr[0] == 'fully_payed') {
                $('#result').html('Заявка №<b>' + order_id + '</b> оплачена!');
            }

            if (arr[0] == 'success') {
                $('#plan_cash_' + num).html(arr[3]);
                $('#payed_cash_' + num).html(arr[2]);
                $('#ord_pay_cash_' + num).val(arr[1]);
                $('#plan_cash_currency_' + num).html(arr[4]);
            } else {
                $('#plan_cash_' + num).html('');
                $('#ord_pay_' + num).val('');
                $("#result").dialog({title: 'Внимание'}, {width: 300}, {modal: true}, {resizable: false}, {
                    buttons: {
                        "Ok": function () {
                            $(this).dialog("close");
                        }
                    }
                });
            }
        });
    }

    function load_orders_group(group_id) {

        $.get('control/pay_load.php?group_id=' + group_id + '&mode=2&way=2', function (data) {
            var arr = data.split(/[|]/);

            if (arr[0] == 'not_exist') {
                $('#result').html('Группы заявок №<b>' + group_id + '</b> не существует!');
            }

            if (arr[0] == 'fully_payed') {
                $('#result').html('Группа заявок №<b>' + group_id + '</b> оплачена!');
            }

            if (arr[0] == 'success') {

                var order_info = JSON.parse(arr[1]);
                let response = "";

                $('#orders_group_plan_cash').html('<b>' + order_info['tr_total_cash'] + '</b> ' + order_info['tr_currency']);
                $('#orders_group_payed_cash').html(order_info['tr_total_payed']);


                let group_array = order_info['group_info'];
                for (let group_info in order_info['group_info']) {
                    response += '<tr><td align="center">' + group_array[group_info].id + '<input type="hidden" name="order_pay[]" value="' + group_array[group_info].id + '"></td><td align="center">' + group_array[group_info].tr_cash + '</td><td align="center" style="color: green; font-weight: bold;">' + group_array[group_info].tr_payed + '</td><td align="center"><input type="text" name="cash_pay[]" style="width:60px;margin: 3px;" value="' + (group_array[group_info].tr_cash - group_array[group_info].tr_payed) + '" class="input group-pay" onchange="var sum = 0;\n' +
                        '$(\'.group-pay\').each(function(){\n' +
                        '    sum += parseFloat(this.value);\n' +
                        '});$(\'#orders_group_cash\').html(sum)"></td></tr>';
                }
                $("#group_id_info").html(response);

                $('#orders_group_cash').html(order_info['tr_total_cash']);

            } else {
                $("#group_id_info").html('');
                $('#orders_group_plan_cash').html('');
                $('#orders_group_payed_cash').html('');
                $('#orders_group_id_pay').val('');
                $("#result").dialog({title: 'Внимание'}, {width: 300}, {modal: true}, {resizable: false}, {
                    buttons: {
                        "Ok": function () {
                            $(this).dialog("close");
                        }
                    }
                });
            }
        });

    }


</script>
<form method="post" id="form_pay_group">

    <fieldset style="width:93%;margin-top:0;">
        <legend>Платежи:</legend>
        <table>
            <tr>
                <td align="right" width="50">Дата:</td>
                <td width="150"><input type="text" id="date_pay_group" name="date_pay_group" style="width:80px;"
                                       value="<?php echo date('d/m/Y'); ?>" class="input"></td>
                <td><input type="checkbox" name="transaction" id="transaction"
                           onclick="if(this.checked){$('#prov').html('<u>ПРОВЕДЕНИЕ</u>');$('#transaction').val(1);} else {$('#prov').html('<u>ПЛАНИРОВАНИЕ</u>');$('#transaction').val(0);}"
                           value="0">&nbsp;&nbsp;<div style="font-size: medium;font-weight: bold;display: inline;">
                        Провести
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>

    <div id="autopays_tabs">

        <ul style="font-size: 18px;height: 28px;">
            <li><a href="#autopays_tabs-1">По заявкам:</a></li>
            <li><a href="#autopays_tabs-2">По группе заявок</a></li>
        </ul>

        <div id="autopays_tabs-1">

            <table style="width:98%;border-collapse: collapse;" border="1" align="center">
                <tr style="background: #b0e0e6;">
                    <td width="90" align="center"><b>Заявка:</b></td>
                    <td width="80" align="center"><b>Ставка</b></td>
                    <td width="100" align="center"><b>Оплачено</b></td>
                    <td align="center"><b>Оплатить</b></td>
                </tr>

                <tr>
                    <td><input type="text" name="order_pay[]" style="width:80px;margin: 3px;" id="ord_pay_1" value=""
                               class="input" onchange="check_tr_cash (1, $(this).val())"></td>
                    <td align="center">
                        <div id="plan_cash_1"></div>
                    </td>
                    <td align="center">
                        <div id="payed_cash_1" style="color: green;font-weight: bold;"></div>
                    </td>
                    <td><input name="cash_pay[]" id="ord_pay_cash_1" type="text" style="width:80px;margin: 3px;"
                               class="input" value="">&nbsp;&nbsp;<div id="plan_cash_currency_1"
                                                                       style="display: inline;"></div>
                    </td>
                </tr>

                <tr>
                    <td><input type="text" name="order_pay[]" style="width:80px;margin: 3px;" id="ord_pay_2" value=""
                               class="input" onchange="check_tr_cash (2, $(this).val())"></td>
                    <td align="center">
                        <div id="plan_cash_2"></div>
                    </td>
                    <td align="center">
                        <div id="payed_cash_2" style="color: green;font-weight: bold;"></div>
                    </td>
                    <td><input name="cash_pay[]" id="ord_pay_cash_2" type="text" style="width:80px;margin: 3px;"
                               class="input" value="">&nbsp;&nbsp;<div id="plan_cash_currency_2"
                                                                       style="display: inline;"></div>
                    </td>
                </tr>

                <tr>
                    <td><input type="text" name="order_pay[]" style="width:80px;margin: 3px;" id="ord_pay_3" value=""
                               class="input" onchange="check_tr_cash (3, $(this).val())"></td>
                    <td align="center">
                        <div id="plan_cash_3"></div>
                    </td>
                    <td align="center">
                        <div id="payed_cash_3" style="color: green;font-weight: bold;"></div>
                    </td>
                    <td><input name="cash_pay[]" id="ord_pay_cash_3" type="text" style="width:80px;margin: 3px;"
                               class="input" value="">&nbsp;&nbsp;<div id="plan_cash_currency_3"
                                                                       style="display: inline;"></div>
                    </td>
                </tr>

                <tr>
                    <td><input type="text" name="order_pay[]" style="width:80px;margin: 3px;" id="ord_pay_4" value=""
                               class="input" onchange="check_tr_cash (4, $(this).val())"></td>
                    <td align="center">
                        <div id="plan_cash_4"></div>
                    </td>
                    <td align="center">
                        <div id="payed_cash_4" style="color: green;font-weight: bold;"></div>
                    </td>
                    <td><input name="cash_pay[]" id="ord_pay_cash_4" type="text" style="width:80px;margin: 3px;"
                               class="input" value="">&nbsp;&nbsp;<div id="plan_cash_currency_4"
                                                                       style="display: inline;"></div>
                    </td>
                </tr>

                <tr>
                    <td><input type="text" name="order_pay[]" style="width:80px;margin: 3px;" id="ord_pay_5" value=""
                               class="input" onchange="check_tr_cash (5, $(this).val())"></td>
                    <td align="center">
                        <div id="plan_cash_5"></div>
                    </td>
                    <td align="center">
                        <div id="payed_cash_5" style="color: green;font-weight: bold;"></div>
                    </td>
                    <td><input name="cash_pay[]" id="ord_pay_cash_5" type="text" style="width:80px;margin: 3px;"
                               class="input" value="">&nbsp;&nbsp;<div id="plan_cash_currency_5"
                                                                       style="display: inline;"></div>
                    </td>
                </tr>

                <tr>
                    <td><input type="text" name="order_pay[]" style="width:80px;margin: 3px;" id="ord_pay_6" value=""
                               class="input" onchange="check_tr_cash (6, $(this).val())"></td>
                    <td align="center">
                        <div id="plan_cash_6"></div>
                    </td>
                    <td align="center">
                        <div id="payed_cash_6" style="color: green;font-weight: bold;"></div>
                    </td>
                    <td><input name="cash_pay[]" id="ord_pay_cash_6" type="text" style="width:80px;margin: 3px;"
                               class="input" value="">&nbsp;&nbsp;<div id="plan_cash_currency_6"
                                                                       style="display: inline;"></div>
                    </td>
                </tr>

                <tr>
                    <td><input type="text" name="order_pay[]" style="width:80px;margin: 3px;" id="ord_pay_7" value=""
                               class="input" onchange="check_tr_cash (7, $(this).val())"></td>
                    <td align="center">
                        <div id="plan_cash_7"></div>
                    </td>
                    <td align="center">
                        <div id="payed_cash_7" style="color: green;font-weight: bold;"></div>
                    </td>
                    <td><input name="cash_pay[]" id="ord_pay_cash_7" type="text" style="width:80px;margin: 3px;"
                               class="input" value="">&nbsp;&nbsp;<div id="plan_cash_currency_7"
                                                                       style="display: inline;"></div>
                    </td>
                </tr>

            </table>

        </div>

        <div id="autopays_tabs-2">
            <table style="width:98%;border-collapse: collapse;" border="1" align="center">

                <tr style="background: #007F7F;color: #FFF;">
                    <td align="center"><b>Группа заявок:</b></td>
                    <td align="center"><b>Ставка перевозчика</b></td>
                    <td align="center"><b>Оплачено</b></td>
                    <td align="center"><b>Предлагается</b></td>
                </tr>

                <tr>
                    <td align="center">№ <input type="text" name="orders_group_id_pay" style="width:60px;margin: 3px;"
                                                id="orders_group_id_pay" value="" class="input"
                                                onchange="load_orders_group($(this).val())"></td>
                    <td align="center">
                        <div id="orders_group_plan_cash"></div>
                    </td>
                    <td align="center">
                        <div id="orders_group_payed_cash"></div>
                    </td>
                    <td align="center">
                        <div id="orders_group_cash"></div>
                    </td>
                </tr>

            </table>

            <table style="margin-top: 10px; width:98%;border-collapse: collapse;" border="1" align="center">
                <tr style="background: #b0e0e6;">
                    <td width="90" align="center"><b>Заявка:</b></td>
                    <td width="80" align="center"><b>Ставка</b></td>
                    <td width="100" align="center"><b>Оплачено</b></td>
                    <td align="center"><b>Оплатить</b></td>
                </tr>
                <tbody id="group_id_info">
                </tbody>
            </table>
        </div>


        <button type="submit" id="save_pay_group" style="width: 150px;margin: 10px;margin-left: 100px;">Создать</button>
        <input type="button" id="btnClose_pay_group" onclick="$('#fa_pay_group').dialog('close');" value="Закрыть"
               style="width: 100px;margin-left: 30px;">


</form>