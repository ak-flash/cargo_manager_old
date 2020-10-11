<?php


$ServerName = '{imap.yandex.ru:993/imap/ssl}INBOX';

$UserName = "proverka@vtl-stroy.ru";
$PassWord = "332333334p";
   
$mbox = imap_open($ServerName, $UserName,$PassWord) or die("Could not open Mailbox - try again later!"); 
   if ($hdr = imap_mailboxmsginfo($mbox)) {$msg=$hdr->Unread; if($msg>0)echo '<font color="red">'.$msg.'</font>'; else echo $msg;}	
   
   
?>