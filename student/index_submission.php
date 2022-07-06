<?php
session_start();
require('../dbconnect.php');
require('../libs.php');


// 選択した教科のidを求める
if (isset($_GET['subject_id'])) {
  $subject_id = $_GET['subject_id'];
} else {
  header('Location: log_in.php');
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

// 本年度のクラスを求める
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

//生徒が所属していたクラスを求める
$classes_stmt = $db->prepare("SELECT c.grade, b.id, b.class_id, c.grade, c.year,  c.class, b.student_num
                              FROM belongs AS b INNER JOIN classes AS c ON b.class_id = c.id
                              WHERE student_id=:student_id
                              ORDER BY c.year ASC");
$classes_stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
$classes_stmt->execute();
$belonged_classes = $classes_stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);

// 教科一覧
$subjects_stmt = $db->prepare("SELECT id, name FROM subjects");
$subjects_stmt->execute();
$all_subjects = $subjects_stmt->fetchAll(PDO::FETCH_ASSOC | PDO::FETCH_UNIQUE);

// 生徒が持つ本年度の該当教科課題を求める
$stmt = $db->prepare("SELECT student_submissions.id, submissions.name as submission_name, submissions.dead_line,
                             COALESCE(student_submissions.approved_date,'-') as approved_date,
                             COALESCE(student_submissions.score,NULL) as score
                        FROM student_submissions
                   LEFT JOIN submissions
                          ON student_submissions.submission_id = submissions.id
                       WHERE student_submissions.student_id = :student_id
                         AND submissions.class_id = :class_id
                         AND submissions.subject_id = :subject_id");
if (!$stmt) {
  die($db->error);
}
$stmt->bindValue(':student_id', $student_id, PDO::PARAM_INT);
$stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
$stmt->bindValue(':subject_id', $subject_id, PDO::PARAM_INT);
$success = $stmt->execute();
if (!$success) {
  die($db->error);
}
$submission_info = $stmt->fetchAll(PDO::FETCH_ASSOC);

// scoreの値
$scoreList = array(
  null => "-",
  3 => "A",
  2 => "B",
  1 => "C",
  0 => "未提出"
);
?>

<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo h($all_subjects[$subject_id]['name']); ?>課題一覧</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1><?php echo h($all_subjects[$subject_id]['name']); ?>課題一覧</h1>
    </div>
    <div id="content">
      <?php if (isset($_SESSION['teacher_id'])) : ?>
        <div style="text-align: right"><a href="../teacher/home.php">教員ホームへ</a></div>
      <?php endif; ?>
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="home.php">ホーム</a></div>
      <div style="text-align: left">
        <div style="margin: 10px;">
          <p>クラス</p>
          <?php foreach ($belonged_classes as $c) : ?>
            <div style="display: flex;">
                <?php echo "{$c['year']}年度{$c['grade']} 年 {$c['class']}組"; ?>
            </div>
          <?php endforeach; ?>
        </div>
        <img src="../student_pictures/<?php echo h($student_pic_info['path']); ?>" width="100" height="100" alt="" />
        <?php echo "{$this_year_class['grade']} - {$this_year_class['class']} No_{$this_year_class['student_num']}"; ?>
        <?php echo "{$student_info['student_last_name']} {$student_info['student_first_name']}" . ' さん' ?>
      </div>

      <!-- 課題一覧 -->
      <div>
        <form action="" , method="post">
          <table class="" style="text-align: center;">
            <tr>
              <!-- <th>h_id</th> -->
              <th>課題名</th>
              <th>提出期限</th>
              <th>受領日</th>
              <th>評価</th>
            </tr>
            <?php foreach ($submission_info as $submission) : ?>

              <!-- student_submissions_id -->
              <!-- <td>
                <?php echo h($submission['student_submissions_id']); ?>
              </td> -->

              <!-- 課題名 -->
              <td>
                <?php echo h($submission['submission_name']); ?>
              </td>

              <!-- 提出期限 -->
              <td>
                <?php echo h($submission['dead_line']); ?>
              </td>

              <!-- 受領日 -->
              <td>
                <?php echo $submission['approved_date']; ?>
              </td>

              <!-- スコア -->
              <td>
                <?php echo $scoreList[$submission['score']]; ?>
              </td>
              </tr>
            <?php endforeach; ?>
          </table>


</body>

</html>