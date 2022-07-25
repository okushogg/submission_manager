<?php
session_start();
require('../private/libs.php');
require('../private/dbconnect.php');

require_once('../private/set_up.php');
$smarty = new Smarty_submission_manager();

// フォームの初期化
$form = [
  array(
    'student_submissions_id' => 'score'
  )
];

// submission_id
$submission_id = filter_input(INPUT_GET, 'submission_id', FILTER_SANITIZE_NUMBER_INT);
$smarty->assign('submission_id', $submission_id);

// 今日の日付
$today = date('Y-m-d');
$smarty->assign('today',$today);

// 現在のバンコクの時刻
$current_time = bkk_time();

// ログイン情報がないとログインページへ移る
login_check();

// 教員のログインか確認
is_teacher_login();

// セッション内の情報
$teacher_info = $_SESSION['auth'];
$smarty->assign('teacher_info', $teacher_info);

// 教員がログインしていた場合
$teacher_id = $_SESSION['auth']['teacher_id'];
$image_id = $_SESSION['auth']['teacher_image_id'];

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
$smarty->assign('pic_info', $pic_info);

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
$smarty->assign('submission_info', $submission_info);
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
                              AND submissions.class_id = :class_id
                              AND students.is_active = 1;");
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
$smarty->assign('students_who_have_submission', $students_who_have_submission);

// 課題が与えられた生徒のclass_idからbelongsを求める
$belong_stmt = $db->prepare("SELECT student_id, student_num
                             FROM belongs
                             WHERE class_id = $class_id");
$belong_stmt->execute();
$student_num_array = $belong_stmt->fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_UNIQUE);
$smarty->assign('student_num_array', $student_num_array);

// scoreの値
$scoreList = array(
  "-" => null,
  "A" => 3,
  "B" => 2,
  "C" => 1,
  "未提出" => 0
);
$smarty->assign('scoreList', $scoreList);

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

$smarty->caching = 0;
$smarty->display('teacher/show_submission.tpl');
?>