<?php 
    include "config.php";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Настройки пользователя</title>
    <?php include_once("data/header.html");?>
    <script type="text/javascript">
        $(function () {
            $("#control_menu_css").css({"background": "#BCBCBC", "color":"#000","text-shadow": "1px 2px 2px #FFF"});

$('#btn_save').button();


        });


    </script>

</head>
<body>
<?php
    require_once("data/menu.html");
    $query_user = "SELECT email FROM `workers` WHERE id = ".mysql_real_escape_string($_SESSION['user_id']);
    $result_user = mysql_query($query_user) or die(mysql_error());
    $user = mysql_fetch_row($result_user);
?>

<div id="result"></div>
</div>
<div id="fa_app" style="background:#F8F8F8;"></div>


<fieldset style="float:left;width:320px;margin:20px;font-size:15px;">
    <legend>Настройки</legend>
    <table>
        <tr>
            <td width="240">Сменить пароль:</td>
            <td><input type="password" name="password" id="password" style="width: 125px;" class="input"
                       value="" placeholder="*********"></td>
        </tr>
        <tr>
            <td>Почтовый ящик:</td>
            <td><input name="email" id="email" style="width: 225px;" class="input" value="<?php echo $user[0];?>"></td>
        </tr>

        <tr>
            <td><a href="#" id="btn_save"
                   onclick='$.post("control/admin.php?mode=profile", { "email": $("#email").val(), "password": $("#password").val() }, function(data) { toastr.success(data); });'
                   style="margin-top: 10px;">Сохранить</a>
            <td></td>
        </tr>
    </table>
</fieldset>


</body>
</html>