<?php

function get_order_group_number()
{
    $query_check = "SELECT DISTINCT group_id FROM orders GROUP BY group_id DESC";
    $result_check = mysql_query($query_check) or die(mysql_error());
    $order_group_number = mysql_fetch_row($result_check);

    return (int)$order_group_number[0];
}