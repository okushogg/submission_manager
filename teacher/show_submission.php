<?php
session_start();
require('../dbconnect.php');
require('../libs.php');

// フォームの初期化
$form = [
  'score' => ''
];

// submission_id
$submission_id = filter_input(INPUT_GET, 'submission_id', FILTER_SANITIZE_NUMBER_INT);

// 今日の日付
$today = date('Y-m-d');

// 現在のバンコクの時刻
$current_time = bkk_time();

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

// 該当の課題が与えられた全ての生徒を求める
$student_stmt = $db->prepare("SELECT student_submissions.id as homework_id, student_submissions.student_id,
                                      COALESCE(student_submissions.approved_date,'-') as approved_date,
                                      COALESCE(student_submissions.score,NULL) as score,
                                     students.first_name, students.last_name,
                                     belongs.student_num
                              FROM student_submissions
                              LEFT JOIN students
                              ON student_submissions.student_id = students.id
                              LEFT JOIN belongs
                              ON belongs.student_id = students.id
                              WHERE student_submissions.submission_id = :submission_id
                              ORDER BY belongs.student_num");
if (!$student_stmt) {
  die($db->error);
}
$student_stmt->bindValue(':submission_id', $submission_id, PDO::PARAM_INT);
$student_success = $student_stmt->execute();
if (!$student_success) {
  die($db->error);
}
$students_who_have_submission = $student_stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($students_who_have_submission);

// 課題の情報を求める
$submission_stmt = $db->prepare("SELECT submissions.name, submissions.dead_line,
                                        subjects.name as subject_name,
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
// var_dump($submission_info);

// scoreの値
$scoreList = array(
  "-" => 99,
  "A" => 3,
  "B" => 2,
  "C" => 1,
  "未提出" => 0
);

// var_dump($form['score']);

// 評価を更新をクリック
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $form['score'] = filter_input(INPUT_POST, 'score', FILTER_SANITIZE_NUMBER_INT);
  foreach ($students_who_have_submission as $homework) {
    if ($homework['score'] !== $form['score']) {
      $homework_stmt = $db->prepare("UPDATE student_submissions
                                        SET score = :score,
                                            approved_date = :approved_date,
                                            updated_at = :updated_at
                                     WHERE id = :homework_id");
      if (!$homework_stmt) {
        die($db->error);
      }
      $homework_stmt->bindValue(':score', $homework['score'], PDO::PARAM_INT);
      $homework_stmt->bindValue(':approved_date', $today, PDO::PARAM_STR);
      $homework_stmt->bindValue(':updated_at', $current_time, PDO::PARAM_STR);
      $homework_stmt->bindValue(':homework_id', $homework['homework_id'], PDO::PARAM_INT);
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
      <div style="text-align: right"><a href="home.php">ホーム</a></div>

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
              <th>No.</th>
              <th>生徒名</th>
              <th>受領日</th>
              <th>評価</th>
            </tr>
            <?php foreach ($students_who_have_submission as $student) : ?>
              <!-- 出席番号 -->
              <td>
                <a href="../student/home.php?student_id=<?php echo h($student['student_id']); ?>">
                  <?php echo $student['student_num']; ?>
                </a>
              </td>

              <!-- 生徒名 -->
              <td>
                <a href="../student/home.php?student_id=<?php echo h($student['student_id']); ?>">
                  <?php echo $student['last_name'] . $student['first_name']; ?>
                </a>
              </td>

              <!-- 受領日 -->
              <td>
                <?php echo $student['approved_date']; ?>
              </td>

              <!-- スコア -->
              <td>
                <select size="1" name="score">
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