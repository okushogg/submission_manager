<?php
// 環境変数にてDBアクセス情報を管理
$db_user = getenv('DB_USERNAME');
$db_password = getenv('DB_PASSWORD');
try {
  $db = new PDO('mysql:dbname=submissions_manager;host=localhost;charset=utf8',$db_user ,$db_password );
} catch(PDOException $e) {
  echo 'Db接続エラー:' . $e->getMessage();
}
