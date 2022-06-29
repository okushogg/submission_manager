<?php
session_start();
require('../dbconnect.php');
require('../libs.php');

// 今年度
$this_year = (new \DateTime('-3 month'))->format('Y');

// teacherがstudent/を閲覧した場合
if (isset($_GET['student_id'])) {
  $_SESSION['student_id'] = $_GET['student_id'];
}

// ログイン情報がないとログインページへ移る
if (isset($_SESSION['student_id']) && isset($_SESSION['last_name']) && isset($_SESSION['first_name'])) {
  $student_id = intval($_SESSION['student_id']);
  $last_name = $_SESSION['last_name'];
  $first_name = $_SESSION['first_name'];
} else {
  header('Location: log_in.php');
  exit();
}

// studentの情報を求める
$student_stmt = $db->prepare("SELECT first_name as student_first_name,
                                     last_name as student_last_name,
                                     image_id
                              FROM students
                              WHERE id=:student_id");
$student_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$student_stmt->execute();
$student_info = $student_stmt->fetch(PDO::FETCH_ASSOC);
// var_dump($student_info);


// 生徒の画像情報を取得
$stmt = $db->prepare("select path from images where id=:image_id");
if (!$stmt) {
  die($db->error);
}
$stmt->bindValue(':image_id', $student_info['image_id'], PDO::PARAM_INT);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$student_pic_info = $stmt->fetch(PDO::FETCH_ASSOC);

// 本年度の所属クラスを求める
$this_year_class_stmt = $db->prepare("SELECT belongs.id, belongs.class_id, belongs.student_num as student_num,
                                             classes.class as class, classes.grade as grade
                                      FROM belongs
                                      LEFT JOIN classes
                                      ON belongs.class_id = classes.id
                                      WHERE belongs.student_id = :student_id
                                        AND classes.year = :year");
$this_year_class_stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
$this_year_class_stmt->bindValue(':year', $this_year, PDO::PARAM_INT);
$this_year_class_stmt->execute();
$this_year_class = $this_year_class_stmt->fetch(PDO::FETCH_ASSOC);
$class_id = $this_year_class['class_id'];
// var_dump($this_year_class);

//生徒が所属していたクラスを求める
$classes_stmt = $db->prepare("SELECT c.grade, b.id, b.class_id, c.grade, c.year,  c.class, b.student_num
                              FROM belongs AS b INNER JOIN classes AS c ON b.class_id = c.id
                              WHERE student_id=:student_id
                              ORDER BY c.year ASC");
$classes_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$classes_stmt->execute();
$belonged_classes = $classes_stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);
// echo ('<pre>');
// var_dump($belonged_classes);
// echo ('<pre>');
// var_dump(end($belonged_classes));

// 教科一覧
$subjects_stmt = $db->prepare("SELECT id, name FROM subjects");
$subjects_stmt->execute();
$all_subjects = $subjects_stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($teacher_id);

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
      <?php if(isset($_SESSION['teacher_id'])): ?>
        <div style="text-align: right"><a href="../teacher/home.php">教員ホームへ</a></div>
      <?php endif; ?>
      <div style="text-align: right"><a href="edit_student.php">生徒情報編集ページ</a></div>
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: left">
        <div>
          <p>所属クラス</p>
          <?php foreach ($belonged_classes as $c) : ?>
            <div style="display: flex;">
              <a href="/">
                <?php echo "{$c['year']}年度{$c['grade']} 年 {$c['class']}組"; ?>
              </a>
            </div>
          <?php endforeach; ?>
        </div>
        <img src="../student_pictures/<?php echo h($student_pic_info['path']); ?>" width="100" height="100" alt="" />
        <?php echo "{$this_year_class['grade']} - {$this_year_class['class']} No_{$this_year_class['student_num']}"; ?>
        <?php echo "{$student_info['student_last_name']} {$student_info['student_first_name']}" . ' さん' ?>
      </div>

      <div>
        <p> 各教科 課題一覧</p>
        <?php foreach ($all_subjects as $subject) : ?>
          <div style="display: flex;">
            <a href="index_submission.php?subject_id=<?php echo h($subject['id']); ?>">
              <?php echo $subject['name']; ?>
            </a>
          </div>
        <?php endforeach; ?>
      </div>


</body>

</html>