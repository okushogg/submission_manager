<?php
session_start();
require('../dbconnect.php');
require('../libs.php');

// 今年度
$this_year = (new \DateTime('-3 month'))->format('Y');

// ログイン情報がないとログインページへ移る
if (isset($_SESSION['id']) && isset($_SESSION['last_name']) && isset($_SESSION['first_name'])) {
  $student_id = intval($_SESSION['id']);
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

//生徒が所属していたクラスを求める
$classes_stmt = $db->prepare("SELECT b.id, b.class_id,  c.year, c.grade, c.class, b.student_num
                              FROM belongs AS b INNER JOIN classes AS c ON b.class_id = c.id
                              WHERE student_id=:student_id
                              ORDER BY c.year ASC");
$classes_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$classes_stmt->execute();
$belonged_classes = $classes_stmt->fetchAll(PDO::FETCH_ASSOC);
// echo ('<pre>');
// var_dump($belonged_classes);
// echo ('<pre>');
// var_dump(end($belonged_classes));

// 教科一覧
$subjects_stmt = $db->prepare("SELECT id, name FROM subjects");
$subjects_stmt->execute();
$all_subjects = $subjects_stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($all_subjects);

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
        <div>
          <p>クラス</p>
          <?php foreach ($belonged_classes as $c) : ?>
            <div style="display: flex;">
              <a href="/">
                <?php echo "{$c['year']}年度{$c['grade']} 年 {$c['class']}組"; ?>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
        <img src="../student_pictures/<?php echo h($pic_info['path']); ?>" width="100" height="100" alt="" />
        <?php echo "$last_name $first_name" . ' さん' ?>
      </div>

      <div>
        <p>教科一覧</p>
        <?php foreach ($all_subjects as $subject) : ?>
          <div style="display: flex;">
            <a href="/"><?php echo $subject['name']; ?></a>
          </div>
        <?php endforeach; ?>
      </div>


</body>

</html>