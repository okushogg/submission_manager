<?php
session_start();
require('../dbconnect.php');
require('../libs.php');

// class_id
$class_id = filter_input(INPUT_GET, 'class_id', FILTER_SANITIZE_NUMBER_INT);
// var_dump($class_id);

// 今日の日付
$today = date('Y-m-d');

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

// 該当クラスの課題を求める
$stmt = $db->prepare("SELECT submissions.id, submissions.name, submissions.dead_line,
                             subjects.name, teachers.first_name, teachers.last_name
                      FROM submissions
                      LEFT JOIN subjects
                      ON submissions.subject_id = subjects.id
                      LEFT JOIN teachers
                      ON submissions.teacher_id = teachers.id
                      WHERE submissions.class_id = :class_id");
if (!$stmt) {
  die($db->error);
}
$stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$submission_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
var_dump($submission_info);

?>

<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>課題一覧ページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1><?php ?>課題一覧ページ</h1>
    </div>
    <div id="content">
      <!-- ナビゲーション -->
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">ホーム</a></div>

      <!-- ユーザー情報 -->
      <div style="text-align: left">
        <img src="../teacher_pictures/<?php echo h($pic_info['path']); ?>" width="100" height="100" alt="" />
        <?php echo $last_name ?> <?php echo $first_name . ' 先生' ?>
      </div>

      <!-- 課題一覧 -->
      <div>
        <table class="XXX">
          <tr>
            <th>A</th>
            <th>B</th>
            <th>C</th>
          </tr>
          <tr>
            <td>D</td>
            <td>E</td>
            <td>F</td>
          </tr>
          <tr>
            <td>G</td>
            <td>H</td>
            <td>I</td>
          </tr>
          <tr>
            <td>J</td>
            <td>K</td>
            <td>L</td>
          </tr>
        </table>
      </div>


</body>

</html>