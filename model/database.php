<?php
class database
{
  public function connect_db()
  {
    $db_user = getenv('DB_USERNAME');
    $db_password = getenv('DB_PASSWORD');
    try {
      // PDOのインスタンスをクラス変数に格納する
      $this->pdo = new PDO('mysql:dbname=submissions_manager;host=localhost;charset=utf8', $db_user, $db_password);
      //return $db;
    } catch (Exception $e) {
      // Exceptionが発生したら表示して終了
      exit($e->getMessage());
    }
  }
}
