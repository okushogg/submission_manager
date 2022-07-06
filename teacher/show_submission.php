<?php
session_start();
require('../dbconnect.php');
require('../libs.php');

// フォームの初期化
$form = [
  array(
    'student_submissions_id' => 'score'
  )
];

// submission_id
$submission_id = filter_input(INPUT_GET, 'submission_id', FILTER_SANITIZE_NUMBER_INT);

// 今日の日付
$today = date('Y-m-d');

// 現在のバンコクの時刻
$current_time = bkk_time();

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

// 課題の情報を求める
$submission_stmt = $db->prepare("SELECT submissions.name, submissions.dead_line,
                                        subjects.name as subject_name,
                                        submissions.class_id,
                                        classes.grade, classes.class
                                   FROM submissions
                                   LEFT JOIN subjects
                                   ON submissions.subject_id = subjects.id
                                   LEFT JOIN classes
                                   ON submissions.class_id = classes.id
                                   WHERE submissions.id = :submission_id");
if (!$submission_stmt) {
  die($db->error);
}
$submission_stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
$success = $submission_stmt->execute();
if (!$success) {
  die($db->error);
}
$submission_info = $submission_stmt->fetch(PDO::FETCH_ASSOC);
$class_id = $submission_info['class_id'];

// 該当の課題が与えられた全ての生徒を求める
$student_stmt = $db->prepare("SELECT student_submissions.id as student_submissions_id,
                                     student_submissions.student_id as student_id,
                                     COALESCE(student_submissions.approved_date,'-') as approved_date,
                                     COALESCE(student_submissions.score,NULL) as score,
                                     submissions.dead_line as dead_line,
                                     students.first_name, students.last_name
                              FROM student_submissions
                              LEFT JOIN students
                              ON student_submissions.student_id = students.id
                              LEFT JOIN submissions
                              ON student_submissions.submission_id = submissions.id
                              WHERE student_submissions.submission_id = :submission_id
                              AND submissions.class_id = :class_id");
if (!$student_stmt) {
  die($db->error);
}
$student_stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
$student_stmt->bindValue(':class_id', $class_id, PDO::PARAM_INT);
$student_success = $student_stmt->execute();
if (!$student_success) {
  die($db->error);
}
$students_who_have_submission = $student_stmt->fetchAll(PDO::FETCH_ASSOC);

// 課題が与えられた生徒のclass_idからbelongsを求める
$belong_stmt = $db->prepare("SELECT student_id, student_num
                             FROM belongs
                             WHERE class_id = $class_id");
$belong_stmt->execute();
$student_num_array = $belong_stmt->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_UNIQUE);
var_dump($student_num_array);

// scoreの値
$scoreList = array(
  "-" => null,
  "A" => 3,
  "B" => 2,
  "C" => 1,
  "未提出" => 0
);

// 評価を更新をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($students_who_have_submission as $homework) {
    $score_array = $_POST['score'];
    $h_id = $homework['student_submissions_id'];
    if ($homework['score'] !=  $score_array[$h_id]) {
      $homework_stmt = $db->prepare("UPDATE student_submissions
                                      SET score = :score,
                                          approved_date = :approved_date,
                                          updated_at = :updated_at
                                   WHERE id = :student_submissions_id");
      if (!$homework_stmt) {
        die($db->error);
      }
      $homework_stmt->bindValue(':score', $score_array[$h_id], PDO::PARAM_INT);
      $homework_stmt->bindValue(':approved_date', $today, PDO::PARAM_STR);
      $homework_stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
      $homework_stmt->bindValue(':student_submissions_id', $h_id, PDO::PARAM_INT);
      $homework_success = $homework_stmt->execute();
      if (!$homework_success) {
        die($db->error);
      }
    }
  }

  header("Location: show_submission.php?submission_id={$submission_id}");
  exit();
}

?>

<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>課題入力ページ</title>
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div id="wrap">
    <div id="head">
      <h1>評価入力ページ</h1>
    </div>


    <div id="content">
      <!-- ナビゲーション -->
      <div style="text-align: right"><a href="log_out.php">ログアウト</a></div>
      <div style="text-align: right"><a href="edit_submission.php?submission_id=<?php echo $submission_id; ?>">課題編集</a></div>
      <div style="text-align: right"><a href="delete_submission.php?submission_id=<?php echo $submission_id; ?>">課題削除</a></div>
      <div style="text-align: right"><a href="index_submission.php?class_id=<?php echo $class_id; ?>">課題一覧へ</a></div>

      <!-- ユーザー情報 -->
      <div style="text-align: left">
        <img src="../teacher_pictures/<?php echo h($pic_info['path']); ?>" width="100" height="100" alt="" />
        <?php echo $last_name ?> <?php echo $first_name . ' 先生' ?>
      </div>

      <!-- 課題情報 -->
      <div>
        <h3><?php echo "{$submission_info['grade']} - {$submission_info['class']}"; ?></h3>
        <h3><?php echo $submission_info['subject_name']; ?></h3>
        <h1><?php echo $submission_info['name']; ?></h1>
      </div>

      <!-- 生徒一覧 -->
      <div>
        <form action="" , method="post">
          <table class="">
            <tr>
              <!-- <th>h_id</th> -->
              <th>No.</th>
              <th>生徒名</th>
              <th>提出期限</th>
              <th>受領日</th>
              <th>評価</th>
            </tr>
            <?php foreach ($students_who_have_submission as $student) : ?>

              <!-- student_submissions_id -->
              <!-- <td>
                <?php echo $student['student_submissions_id']; ?>
              </td> -->

              <!-- 出席番号 -->
              <td>
                <?php echo $student_num_array[$student['student_id']]['student_num'] ; ?>
              </td>

              <!-- 生徒名 -->
              <td>
                <a href="../student/home.php?student_id=<?php echo h($student['student_id']); ?>">
                  <?php echo $student['last_name'] . $student['first_name']; ?>
                </a>
              </td>

              <!-- 提出期限 -->
              <?php if ($student['dead_line'] <= $today && $student['score'] == null || 0) : ?>
                <td style="color: red;">
                  <?php echo $student['dead_line']; ?>
                </td>
              <?php else : ?>
                <td>
                  <?php echo $student['dead_line']; ?>
                </td>
              <?php endif; ?>

              <!-- 受領日 -->
              <td>
                <?php echo $student['approved_date']; ?>
              </td>

              <!-- スコア -->
              <td>
                <select size="1" name="score[<?php echo $student['student_submissions_id']; ?>]">
                  <?php
                  foreach ($scoreList as $key => $value) {
                    $student_score_int = intval($student['score']);
                    if (isset($student['score']) && $value === $student_score_int) {
                      echo "<option value={$value} selected>" . $key . "</option>";
                    } else {
                      echo "<option value='$value'>" . $key . "</option>";
                    }
                  }
                  ?>
                </select>
              </td>
              </tr>
            <?php endforeach; ?>
          </table>
          <div><input type="submit" value="評価を更新" /></div>
        </form>
      </div>


</body>

</html>