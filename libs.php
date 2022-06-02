<?php
function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES);
}

function dbconnect(){
  $db = new mysqli('localhost', 'root', 'root', 'submissions_manager');
  if(!$db){
		die($db->error);
  }
  return $db;
}

function jp_time(){
  date_default_timezone_set('Asia/Tokyo');
  $jp_time = date('Y-m-d H:i:s');
  return $jp_time;
}
?>