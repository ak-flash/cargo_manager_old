<?php
include ('../../config.php');

require '../../vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

// Authenticating with keyfile data.
$storage = new StorageClient([
    'keyFile' => json_decode(file_get_contents('../'.GOOGLE_CLOUD_STORAGE.'.json'), true)
]);

$bucket = $storage->bucket('ak-flash-bucket');

$tables = '*';

//Call the core function
if(@$_POST['token']=='cf2efc22cf2' || @$_GET['mode']=='cron'){
    delete_old_backup($bucket, 90);
    echo backup_tables($bucket, $tables);
}

function delete_old_backup($bucket, $days_older){
$objects = $bucket->objects([
    'fields' => 'items',
    'prefix' => APP_COMPANY.'/db/db-'
]);

foreach ($objects as $object) {
    $pieces = explode("-", $object->name());
    $pieces_name = explode("/", $object->name());

    $origin = new DateTime(substr($pieces[5],0,4).'-'.$pieces[4].'-'.$pieces[3]);
    $target = new DateTime('now');
    $interval = $origin->diff($target);
    $lifetime = $interval->format('%a');
    if($lifetime>$days_older){
        $object->delete();
        unlink('files/'.$pieces_name[2]);
        echo $object->name().' - <b>deleted</b><br>';
    }
}
}

function gzcompressfile($source,$level=false){
$dest=$source.'.gz';
$mode='wb'.$level;
$error=false;
if($fp_out=gzopen($dest,$mode)){
    if($fp_in=fopen($source,'rb')){
        while(!feof($fp_in))
            gzwrite($fp_out,fread($fp_in,1024*512));
        fclose($fp_in);
        }
      else $error=true;
    gzclose($fp_out);
    }
  else $error=true;
if($error) return false;
  else return $dest;
}

//Core function
function backup_tables($bucket, $tables = '*') {





    //get all of the tables
    if($tables == '*')
    {
        $tables = array();
        $result = mysql_query('SHOW TABLES');
        while($row = mysql_fetch_row($result))
        {
            $tables[] = $row[0];
        }
    }
    else
    {
        $tables = is_array($tables) ? $tables : explode(',',$tables);
    }

    $return = '';
    //cycle through
    foreach($tables as $table)
    {
        $result = mysql_query('SELECT * FROM `'.$table.'`');
        $num_fields = mysql_num_fields($result);
        $num_rows = mysql_num_rows($result);

        $return.= 'DROP TABLE IF EXISTS '.$table.';';
        $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE `'.$table.'`'));
        $return.= "\n\n".$row2[1].";\n\n";
        $counter = 1;

        //Over tables
        for ($i = 0; $i < $num_fields; $i++) 
        {   //Over rows
            while($row = mysql_fetch_row($result))
            {   
                if($counter == 1){
                    $return.= 'INSERT INTO `'.$table.'` VALUES(';
                } else {
                    $return.= '(';
                }

                //Over fields
                for($j=0; $j<$num_fields; $j++) 
                {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = str_replace("\n","\\n",$row[$j]);
                    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                    if ($j<($num_fields-1)) { $return.= ','; }
                }

                if($num_rows == $counter){
                    $return.= ");\n";
                } else {
                    $return.= "),\n";
                }
                ++$counter;
            }
        }
        $return.="\n\n\n";
    }

    //save file
    $fileName = 'files/db-'.APP_COMPANY.'-backup-'.date('d-m-Y').'.sql';
    $handle = fopen($fileName,'w+');
    fwrite($handle,$return);
    if(fclose($handle)){
        gzcompressfile($fileName);
        echo "<b>Успешно сохранено в файл!</b>"; //$fileName

        $bucket->upload(
            fopen('files/db-'.APP_COMPANY.'-backup-'.date('d-m-Y').'.sql.gz', 'r'),
            ['name' => APP_COMPANY.'/db/db-'.APP_COMPANY.'-backup-'.date('d-m-Y').'.sql.gz']
        );

        unlink($fileName);
        exit; 
    }
}

?>
	