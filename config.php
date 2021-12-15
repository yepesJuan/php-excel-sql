<?php
$db_host = 'DB_HOST';
$db_username = 'DB_USERNAME';
$db_password = 'DB_PASSWORD';
$db_name = 'DB_NAME';
  
$db = new mysqli($db_host, $db_username, $db_password, $db_name);
  
if($db->connect_error){
    die("Unable to connect to the database: " . $db->connect_error);
}
