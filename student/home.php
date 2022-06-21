<?php
session_start();
require('../dbconnect.php');
require('../libs.php');
// ログイン情報がないとログインページへ移る
if (isset($_SESSION['id']) && isset($_SESSION['last_name']) && isset($_SESSION['first_name'])) {
  $id = $_SESSION['id'];
  $last_name = $_SESSION['last_name'];
  $first_name = $_SESSION['first_name'];
  $image_id = $_SESSION['image_id'];
} else {
  header('Location: log_in.php');
  exit();
}
// 画像の情報を取得
$stmt = $db->prepare("select path from images where id=:id");
if (!$stmt) {
  die($db->error);
}
$stmt->bindParam(':id', $image_id, PDO::PARAM_INT);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$pic_info = $stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($pic_info);

// 該当年度の年度のクラス
$classes_stmt = $db->prepare("select id, year, grade, class from classes where year=:year");
$classes_stmt->bindParam(':year', $year, PDO::PARAM_STR);
$classes_stmt->execute();
$classes_info = $classes_stmt->fetchAll(PDO::FETCH_ASSOC);
$cnt = count($classes_info);
?>

<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>生徒トップページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>生徒トップページ</h1>
    </div>
    <div id="content">
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: left">
        <img src="../student_pictures/<?php echo h($pic_info['path']); ?>" width="48" height="48" alt="" />
        <?php echo $last_name ?> <?php echo $first_name . ' さん' ?>
      </div>

      <div>
        <div class="box">
          <?php foreach ($classes_array['1'] as $a): ?>
            <div class="box">
              <?php echo "{$a['grade']} - {$a['class']}";?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>


</body>

</html>