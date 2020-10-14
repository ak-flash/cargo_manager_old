<?php
		include ('../../.env');
    /*
        {
  "access_token": "ya29.a0AfH6SMCeV-TiunoJZ59yrY1L8FTYisuDC7Iy7TDQM9uESypShsSpuVlNCkjWtJV-Wp4vzi0t1nF1MKtNx3izazVdiYfuTAsFEHaUa-WcG_rHDmxR8UUSMzpl1YJm6JqY2NbekZeCwAuX0_uHbi8lNTEdZAilooGdpfk",
  "expires_in": 3599,
  "refresh_token": "1//0cO2ysu_2PiL7CgYIARAAGAwSNwF-L9IrrGphPY919sqLV42WKzvzoeZyN5JQcecBQOfXkKsSB0TgjLTTC-au1pmm_KADS7GCCEM",
  "scope": "https://www.googleapis.com/auth/drive.file",
  "token_type": "Bearer"
}
*/

//function get_message($message_id) {

    $api_secret = 'ya29.a0AfH6SMCeV-TiunoJZ59yrY1L8FTYisuDC7Iy7TDQM9uESypShsSpuVlNCkjWtJV-Wp4vzi0t1nF1MKtNx3izazVdiYfuTAsFEHaUa-WcG_rHDmxR8UUSMzpl1YJm6JqY2NbekZeCwAuX0_uHbi8lNTEdZAilooGdpfk';
    
    $headers = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => "authorization: Bearer ".$api_secret." Content-Length: 0",
            'name' => 'db-backup-13-10-2020_euroasia.sql.gz',
        ),
    ));
     
    $response = file_get_contents("https://www.googleapis.com/upload/drive/v3/files?uploadType=media", false, $headers);
    
    $result = json_decode($response,true);
    
    echo $response;
    
//    }
        
    
    
    //upload_gdrive('files/db-backup-13-10-2020_euroasia.sql.gz');
    

	$tables = '*';

    //Call the core function
    if(@$_POST['token']=='cf2efc22cf2'){
        echo backup_tables($db_host, $db_user, $db_pass,$db_base, $tables);
    }

    if(@$_GET['mode']=='cron'){
        echo backup_tables($db_host, $db_user, $db_pass,$db_base, $tables);
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
function backup_tables($host, $user, $pass, $dbname, $tables = '*') {
    $link = mysqli_connect($host,$user,$pass, $dbname);

    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit;
    }

    mysqli_query($link, "SET NAMES 'utf8'");

    //get all of the tables
    if($tables == '*')
    {
        $tables = array();
        $result = mysqli_query($link, 'SHOW TABLES');
        while($row = mysqli_fetch_row($result))
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
        $result = mysqli_query($link, 'SELECT * FROM '.$table);
        $num_fields = mysqli_num_fields($result);
        $num_rows = mysqli_num_rows($result);

        $return.= 'DROP TABLE IF EXISTS '.$table.';';
        $row2 = mysqli_fetch_row(mysqli_query($link, 'SHOW CREATE TABLE '.$table));
        $return.= "\n\n".$row2[1].";\n\n";
        $counter = 1;

        //Over tables
        for ($i = 0; $i < $num_fields; $i++) 
        {   //Over rows
            while($row = mysqli_fetch_row($result))
            {   
                if($counter == 1){
                    $return.= 'INSERT INTO '.$table.' VALUES(';
                } else{
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
                } else{
                    $return.= "),\n";
                }
                ++$counter;
            }
        }
        $return.="\n\n\n";
    }

    //save file
    $fileName = 'files/db-backup-'.date('d-m-Y').'_euroasia.sql';
    $handle = fopen($fileName,'w+');
    fwrite($handle,$return);
    if(fclose($handle)){
        gzcompressfile($fileName);
        echo "Успешно сохранено в файл!"; //$fileName
        //upload_gdrive($fileName);
        unlink($fileName);
        exit; 
    }
}




	?>
	