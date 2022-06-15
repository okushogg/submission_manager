<?php
session_start();
require('../dbconnect.php');
require('../libs.php');
// ログイン情報がないとログインページへ移る
if (isset($_SESSION['id']) && isset($_SESSION['last_name']) && isset($_SESSION['first_name'])){
  $id = $_SESSION['id'];
  $last_name = $_SESSION['last_name'];
  $first_name = $_SESSION['first_name'];
} else {
  header('Location: log_in.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>教員トップページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>教員トップページ</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="../log_out.php">ログアウト</a></div>
    </div>
  </div>


</body>

</html>