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
if (isset($_SESSION['teacher_id']) && isset($_SESSION['last_name']) && isset($_SESSION['first_name'])) {
  $teacher_id = $_SESSION['teacher_id'];
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
$stmt = $db->prepare("SELECT submissions.id, submissions.name as submission_name, submissions.dead_line,
                             subjects.name as subject_name, teachers.first_name, teachers.last_name
                      FROM submissions
                      LEFT JOIN subjects
                      ON submissions.subject_id = subjects.id
                      LEFT JOIN teachers
                      ON submissions.teacher_id = teachers.id
                      WHERE submissions.class_id = :class_id
                      ORDER BY id DESC");
if (!$stmt) {
  die($db->error);
}
$stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$submission_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($submission_info);

// クラスの情報を求める
$class_stmt = $db->prepare("SELECT id as class_id, year, grade, class
                            FROM classes
                            WHERE id = :class_id");
$class_stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
$class_success = $class_stmt->execute();
if (!$class_success){
  die($db->error);
}
$class_info = $class_stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($class_info);

?>

<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo "{$class_info['grade']} - {$class_info['class']}" ;?> 課題一覧ページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1><?php echo "{$class_info['grade']} - {$class_info['class']}";?> 課題一覧ページ</h1>
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
        <table class="">
          <tr>
            <th>課題名</th>
            <th>教科名</th>
            <th>提出期限</th>
          </tr>
          <?php foreach ($submission_info as $submission) : ?>
            <tr>
              <td>
                <a href="show_submission.php?submission_id=<?php echo h($submission['id']); ?>">
                  <?php echo $submission['submission_name'] ?>
                </a>
              </td>
              <td><?php echo $submission['subject_name'] ?></td>
              <td><?php echo $submission['dead_line'] ?></td>
            </tr>
          <?php endforeach; ?>
        </table>
      </div>


</body>

</html>