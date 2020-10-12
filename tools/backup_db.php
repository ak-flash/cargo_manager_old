<?php


exec('C:\xampp\mysql\bin\mysqldump.exe -uroot -p1565015650 cargo | gzip > d:\mydb.sql.gz', $out, $status);
if (0 === $status) {
    var_dump($out);
} else {
    echo "Command failed with status: $status";
}
?>