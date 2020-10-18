<?php
include ('../config.php');

require '../vendor/autoload.php';

use Google\Cloud\Storage\StorageClient;

// Authenticating with keyfile data.
$storage = new StorageClient([
    'keyFile' => json_decode(file_get_contents(GOOGLE_CLOUD_STORAGE.'.json'), true)
]);
$bucket = $storage->bucket('ak-flash-bucket');

    $objects = $bucket->objects([
		    'fields' => 'items',
		     'prefix' => APP_COMPANY.'/db/db-'
		]);

    foreach ($objects as $object) {
        $pieces = explode("-", $object->name());

        $origin = new DateTime(substr($pieces[5],0,4).'-'.$pieces[4].'-'.$pieces[3]);
        $target = new DateTime('now');
        $interval = $origin->diff($target);
        $lifetime = $interval->format('%a');
        if($lifetime>30){
            $object->delete();
            echo $object->name().' - <b>deleted</b><br>';
        } else {
            echo $object->name().'<br>';
        }
    }


?>