<?php



function komissia($cl_cash,$cl_minus,$cl_plus,$cl_nds_inp,$tr_cash,$tr_minus,$tr_plus,$tr_nds_inp){
$cl_cash_all=0;
$tr_cash_all=0;
$cash=0;

$query_motive = "SELECT * FROM `settings`";
$result_motive = mysql_query($query_motive) or die(mysql_error());
$motive = mysql_fetch_array($result_motive);

$cl_cash_all=$cl_cash-$cl_minus+$cl_plus;
$tr_cash_all=$tr_cash-$tr_minus+$tr_plus;
$cl_nds=(int)$cl_nds_inp;
$tr_nds=(int)$tr_nds_inp;
    if($cl_nds==0&&$tr_nds==0)$cash=$cl_cash_all-$tr_cash_all;
    if($cl_nds==1&&$tr_nds==1)$cash=$cl_cash_all-$tr_cash_all;
    if($cl_nds==1&&$tr_nds==0)$cash=($cl_cash_all-round($cl_cash_all/$motive['motive_2'],2))-$tr_cash_all;
    if($cl_nds==1&&$tr_nds==2)$cash=($cl_cash_all-$cl_cash_all*$motive['motive_3'])-$tr_cash_all;
    if($cl_nds==0&&$tr_nds==1)$cash=$cl_cash_all-$tr_cash_all-round($tr_cash_all/$motive['motive_4'],2);
    if($cl_nds==0&&$tr_nds==2)$cash=($cl_cash_all-$cl_cash_all*$motive['motive_6'])-$tr_cash_all;
    if($cl_nds==2&&$tr_nds==1)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*$motive['motive_7'];
    if($cl_nds==2&&$tr_nds==0)$cash=$cl_cash_all-$tr_cash_all+$tr_cash_all*$motive['motive_8'];
    if($cl_nds==2&&$tr_nds==2)$cash=$cl_cash_all-$tr_cash_all;

return round($cash, 0);
}

?>