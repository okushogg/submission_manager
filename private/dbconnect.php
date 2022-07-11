<?php
try {
  $db = new PDO('mysql:dbname=submissions_manager;host=localhost;charset=utf8','root','root');
} catch(PDOException $e) {
  echo 'Db接続エラー:　' . $e->getMessage();
}
?>