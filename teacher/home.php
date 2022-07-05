<?php
session_start();
require('../dbconnect.php');
require('../libs.php');

$form = [
  "last_name" => '',
  "first_name" => '',
  "year" => '',
  "grade" => '',
  "class" => ''
];

// ログイン情報がないとログインページへ移る
if (isset($_SESSION['teacher_id']) && isset($_SESSION['last_name']) && isset($_SESSION['first_name'])) {
  $teacher_id = $_SESSION['teacher_id'];
  $last_name = $_SESSION['last_name'];
  $first_name = $_SESSION['first_name'];
  $image_id = $_SESSION['teacher_image_id'];
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

// 今年度のクラスを取得する
$classes_stmt = $db->prepare("select id, year, grade, class from classes where year=:year");
$classes_stmt->bindParam(':year', $this_year, PDO::PARAM_STR);
$classes_stmt->execute();
$classes_info = $classes_stmt->fetchAll(PDO::FETCH_ASSOC);
$cnt = count($classes_info);

// 学年ごとにクラスのデータを配列で取得
$classes_array = get_classes($classes_info);
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
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="search_student.php">生徒検索</a></div>
      <div style="text-align: right"><a href="register_class.php">クラス登録</a></div>
      <div style="text-align: right"><a href="create_submission.php">提出物登録</a></div>
      <div style="text-align: left">
        <img src="../teacher_pictures/<?php echo h($pic_info['path']); ?>" width="100" height="100" alt="" />
        <?php echo $last_name ?> <?php echo $first_name . ' 先生' ?>
      </div>

      <div>
        <div class="box">
          <?php foreach ($classes_array['1'] as $a) : ?>
            <div class="box">
              <a href="index_submission.php?class_id=<?php echo h($a['id']); ?>"><?php echo "{$a['grade']} - {$a['class']}"; ?></a>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="box">
          <?php foreach ($classes_array['2'] as $a) : ?>
            <div class="box">
              <a href="index_submission.php?class_id=<?php echo h($a['id']); ?>"><?php echo "{$a['grade']} - {$a['class']}"; ?></a>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="box">
          <?php foreach ($classes_array['3'] as $a) : ?>
            <div class="box">
              <a href="index_submission.php?class_id=<?php echo h($a['id']); ?>"><?php echo "{$a['grade']} - {$a['class']}"; ?></a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>


</body>

</html>